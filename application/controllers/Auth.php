<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Manually load URL helper
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('User_model');
    }
    
    public function login() {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in')) {
            header('Location: /smart_core_erp/dashboard'); // Manual redirect
            exit();
        }

        $data['title'] = 'Login - Smart-Core ERP';
        $this->load->view('auth/login', $data);
    }
    
    public function register() {
        $data['title'] = 'Create Account - Smart-Core ERP';
        $this->load->view('auth/register', $data);
    }
    
    public function forgot_password() {
        $data['title'] = 'Forgot Password - Smart-Core ERP';
        $this->load->view('auth/forgot_password', $data);
    }

    // Process login form
    public function process_login() {
        // Check if it's an AJAX request
        if ($this->input->post('ajax')) {
            $this->ajax_login();
            return;
        }

        // Regular form submission
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/login'); // Manual redirect
            exit();
        } else {
            // Validation successful, try to login
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $remember = $this->input->post('rememberMe');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                // Login successful
                $user_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role' => $user->role,
                    'logged_in' => true
                );

                $this->session->set_userdata($user_data);

                // Set remember me cookie if checked
                if ($remember) {
                    $this->set_remember_me($user->id);
                }

                $this->session->set_flashdata('success', 'Welcome back, ' . $user->first_name . '!');
                header('Location: /smart_core_erp/dashboard'); // Manual redirect
                exit();

            } else {
                // Login failed
                $this->session->set_flashdata('error', 'Invalid email or password');
                header('Location: /smart_core_erp/login'); // Manual redirect
                exit();
            }
        }
    }

    // AJAX login handler
    private function ajax_login() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'success' => false,
                'message' => validation_errors()
            ));
            return;
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $remember = $this->input->post('rememberMe');

        $user = $this->User_model->login($email, $password);

        if ($user) {
            // Login successful
            $user_data = array(
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role' => $user->role,
                'logged_in' => true
            );

            $this->session->set_userdata($user_data);

            // Set remember me cookie if checked
            if ($remember) {
                $this->set_remember_me($user->id);
            }

            echo json_encode(array(
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => '/smart_core_erp/dashboard'
            ));

        } else {
            // Login failed
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid email or password'
            ));
        }
    }

    // Set remember me cookie
    private function set_remember_me($user_id) {
        $token = bin2hex(random_bytes(32));
        $expire = time() + (30 * 24 * 60 * 60); // 30 days

        set_cookie('remember_me', $token, $expire);
    }

    // Process registration
    public function process_register() {
        $this->form_validation->set_rules('firstName', 'First Name', 'required|min_length[2]');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('agreeTerms', 'Terms and Conditions', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/register'); // Manual redirect
            exit();
        } else {
            // Validation successful, create user
            $user_data = array(
                'username' => strtolower($this->input->post('firstName') . $this->input->post('lastName')),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'first_name' => $this->input->post('firstName'),
                'last_name' => $this->input->post('lastName'),
                'phone' => $this->input->post('phone'),
                'role' => 'sales',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            );

            $user_id = $this->User_model->create_user($user_data);

            if ($user_id) {
                // Auto login after registration
                $user_data = array(
                    'user_id' => $user_id,
                    'username' => $user_data['username'],
                    'email' => $user_data['email'],
                    'first_name' => $user_data['first_name'],
                    'last_name' => $user_data['last_name'],
                    'role' => $user_data['role'],
                    'logged_in' => true
                );

                $this->session->set_userdata($user_data);
                $this->session->set_flashdata('success', 'Account created successfully! Welcome to Smart-Core ERP.');
                header('Location: /smart_core_erp/dashboard'); // Manual redirect
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to create account. Please try again.');
                header('Location: /smart_core_erp/register'); // Manual redirect
                exit();
            }
        }
    }

    // AJAX registration handler
    public function ajax_register() {
        $this->form_validation->set_rules('firstName', 'First Name', 'required|min_length[2]');
        $this->form_validation->set_rules('lastName', 'Last Name', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('agreeTerms', 'Terms and Conditions', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'success' => false,
                'message' => validation_errors()
            ));
            return;
        }

        // Validation successful, create user
        $user_data = array(
            'username' => strtolower($this->input->post('firstName') . $this->input->post('lastName')),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'first_name' => $this->input->post('firstName'),
            'last_name' => $this->input->post('lastName'),
            'phone' => $this->input->post('phone'),
            'role' => 'sales',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        $user_id = $this->User_model->create_user($user_data);

        if ($user_id) {
            // Auto login after registration
            $user_data = array(
                'user_id' => $user_id,
                'username' => $user_data['username'],
                'email' => $user_data['email'],
                'first_name' => $user_data['first_name'],
                'last_name' => $user_data['last_name'],
                'role' => $user_data['role'],
                'logged_in' => true
            );

            $this->session->set_userdata($user_data);

            echo json_encode(array(
                'success' => true,
                'message' => 'Account created successfully! Welcome to Smart-Core ERP.',
                'redirect' => '/smart_core_erp/dashboard'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to create account. Please try again.'
            ));
        }
    }

    // Logout function
    public function logout() {
        // Destroy session
        $this->session->sess_destroy();
        
        // Clear remember me cookie
        delete_cookie('remember_me');
        
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        header('Location: /smart_core_erp/login'); // Manual redirect use karo
        exit();
    }

    // Check if user is logged in (helper function)
    public function is_logged_in() {
        return $this->session->userdata('logged_in');
    }
}
?>