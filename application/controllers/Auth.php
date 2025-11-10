<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }
    
    public function login() {
        $data['title'] = 'Login - Smart-Core ERP';
        $this->load->view('auth/login', $data);
    }
    
    public function register() {
        $data['title'] = 'Register - Smart-Core ERP';
        $this->load->view('auth/register', $data);
    }
    
    public function forgot_password() {
        $data['title'] = 'Forgot Password - Smart-Core ERP';
        $this->load->view('auth/forgot_password', $data);
    }
}
?>