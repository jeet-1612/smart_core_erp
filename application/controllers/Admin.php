<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('User_model');
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Check if user has admin privileges
        if ($this->session->userdata('role') != 'admin') {
            show_error('You do not have permission to access this page.', 403);
        }
    }
    
    public function users() {
        $data['title'] = 'User Management - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all users
        $data['users'] = $this->User_model->get_all_users();
        $data['roles'] = $this->User_model->get_roles();
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/users', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function add_user() {
        $data['title'] = 'Add New User - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['roles'] = $this->User_model->get_roles();
        $data['departments'] = $this->User_model->get_departments();
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/add_user', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit_user($id) {
        $data['title'] = 'Edit User - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['user_data'] = $this->User_model->get_user_by_id($id);
        $data['roles'] = $this->User_model->get_roles();
        $data['departments'] = $this->User_model->get_departments();
        
        if (!$data['user_data']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/edit_user', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function profile($id = null) {
        if (!$id) {
            $id = $this->session->userdata('user_id');
        }
        
        $data['title'] = 'User Profile - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['user_data'] = $this->User_model->get_user_by_id($id);
        $data['roles'] = $this->User_model->get_roles();
        $data['departments'] = $this->User_model->get_departments();
        
        if (!$data['user_data']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/profile', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process add user
    public function process_add_user() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('role', 'Role', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/add_user');
        } else {
            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role'),
                'department_id' => $this->input->post('department_id'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zip_code' => $this->input->post('zip_code'),
                'country' => $this->input->post('country'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'created_by' => $this->session->userdata('user_id')
            );
            
            $result = $this->User_model->add_user($user_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'User added successfully!');
                redirect('admin/users');
            } else {
                $this->session->set_flashdata('error', 'Failed to add user. Please try again.');
                redirect('admin/add_user');
            }
        }
    }
    
    // Process edit user
    public function process_edit_user($id) {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required');
        
        // Check if email is unique (excluding current user)
        $existing_user = $this->User_model->get_user_by_id($id);
        if ($existing_user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]');
        }
        
        // Check if username is unique (excluding current user)
        if ($existing_user->username != $this->input->post('username')) {
            $this->form_validation->set_rules('username', 'Username', 'is_unique[users.username]');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/edit_user/' . $id);
        } else {
            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'role' => $this->input->post('role'),
                'department_id' => $this->input->post('department_id'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zip_code' => $this->input->post('zip_code'),
                'country' => $this->input->post('country'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Update password only if provided
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
                
                if ($this->form_validation->run() == TRUE) {
                    $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
            }
            
            $result = $this->User_model->update_user($id, $user_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'User updated successfully!');
                redirect('admin/users');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user. Please try again.');
                redirect('admin/edit_user/' . $id);
            }
        }
    }
    
    // Update user status
    public function update_user_status($id, $status) {
        if (!in_array($status, array('active', 'inactive'))) {
            $this->session->set_flashdata('error', 'Invalid status.');
            redirect('admin/users');
        }
        
        $user_data = array(
            'is_active' => $status == 'active' ? 1 : 0,
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $result = $this->User_model->update_user($id, $user_data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'User status updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update user status.');
        }
        
        redirect('admin/users');
    }
    
    // Delete user
    public function delete_user($id) {
        // Prevent users from deleting themselves
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admin/users');
        }
        
        $result = $this->User_model->delete_user($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'User deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user. Please try again.');
        }
        
        redirect('admin/users');
    }
    
    // Update profile (for own account)
    public function update_profile() {
        $id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        // Check if email is unique (excluding current user)
        $existing_user = $this->User_model->get_user_by_id($id);
        if ($existing_user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/profile/' . $id);
        } else {
            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zip_code' => $this->input->post('zip_code'),
                'country' => $this->input->post('country'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Update password only if provided
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
                
                if ($this->form_validation->run() == TRUE) {
                    $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
            }
            
            $result = $this->User_model->update_user($id, $user_data);
            
            if ($result) {
                // Update session data if profile is updated
                if ($id == $this->session->userdata('user_id')) {
                    $updated_user = $this->User_model->get_user_by_id($id);
                    $this->session->set_userdata(array(
                        'first_name' => $updated_user->first_name,
                        'last_name' => $updated_user->last_name,
                        'email' => $updated_user->email
                    ));
                }
                
                $this->session->set_flashdata('success', 'Profile updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
            }
            
            redirect('admin/profile/' . $id);
        }
    }
    
    // User activity log
    public function user_activity($user_id = null) {
        $data['title'] = 'User Activity Log - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['activities'] = $this->User_model->get_user_activities($user_id);
        $data['users'] = $this->User_model->get_all_users();
        $data['selected_user'] = $user_id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/user_activity', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Departments management
    public function departments() {
        $data['title'] = 'Department Management - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['departments'] = $this->User_model->get_departments();
        
        $this->load->view('templates/header', $data);
        $this->load->view('admin/departments', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Add department
    public function add_department() {
        $this->form_validation->set_rules('department_name', 'Department Name', 'required|trim|is_unique[departments.department_name]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $department_data = array(
                'department_name' => $this->input->post('department_name'),
                'description' => $this->input->post('description'),
                'is_active' => 1
            );
            
            $result = $this->User_model->add_department($department_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Department added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add department. Please try again.');
            }
        }
        
        redirect('admin/departments');
    }
    
    // Update department
    public function update_department($id) {
        $this->form_validation->set_rules('department_name', 'Department Name', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $department_data = array(
                'department_name' => $this->input->post('department_name'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            
            $result = $this->User_model->update_department($id, $department_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Department updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update department. Please try again.');
            }
        }
        
        redirect('admin/departments');
    }
    
    // Delete department
    public function delete_department($id) {
        $result = $this->User_model->delete_department($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Department deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete department. Please try again.');
        }
        
        redirect('admin/departments');
    }
}
?>