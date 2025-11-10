<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); // URL helper manually load karo
        $this->load->library('session');
        $this->load->database();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Location: /smart_core_erp/login'); // Manual redirect
            exit();
        }
    }
    
    public function index() {
        $data['title'] = 'Dashboard - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role'),
            'email' => $this->session->userdata('email')
        );
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer', $data);
    }
}
?>