<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Location: /smart_core_erp/login');
            exit();
        }
    }
    
    public function index() {
        $data['title'] = 'Clients - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all clients
        $data['clients'] = $this->Client_model->get_all_clients();
        
        $this->load->view('templates/header', $data);
        $this->load->view('clients/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function add() {
        $data['title'] = 'Add New Client - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $this->load->view('templates/header', $data);
        $this->load->view('clients/add', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit($id) {
        $data['title'] = 'Edit Client - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get client data
        $data['client'] = $this->Client_model->get_client_by_id($id);
        
        if (!$data['client']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('clients/edit', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view($id) {
        $data['title'] = 'Client Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get client data
        $data['client'] = $this->Client_model->get_client_by_id($id);
        
        if (!$data['client']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('clients/view', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process add client form
    public function process_add() {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/clients/add');
            exit();
        } else {
            $client_data = array(
                'client_code' => $this->generate_client_code(),
                'company_name' => $this->input->post('company_name'),
                'contact_person' => $this->input->post('contact_person'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'mobile' => $this->input->post('mobile'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'pin_code' => $this->input->post('pin_code'),
                'gstin' => $this->input->post('gstin'),
                'pan_number' => $this->input->post('pan_number'),
                'credit_limit' => $this->input->post('credit_limit'),
                'status' => $this->input->post('status'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $client_id = $this->Client_model->create_client($client_data);
            
            if ($client_id) {
                $this->session->set_flashdata('success', 'Client added successfully!');
                header('Location: /smart_core_erp/clients');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to add client. Please try again.');
                header('Location: /smart_core_erp/clients/add');
                exit();
            }
        }
    }
    
    // Process edit client form
    public function process_edit($id) {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/clients/edit/' . $id);
            exit();
        } else {
            $client_data = array(
                'company_name' => $this->input->post('company_name'),
                'contact_person' => $this->input->post('contact_person'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'mobile' => $this->input->post('mobile'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'pin_code' => $this->input->post('pin_code'),
                'gstin' => $this->input->post('gstin'),
                'pan_number' => $this->input->post('pan_number'),
                'credit_limit' => $this->input->post('credit_limit'),
                'status' => $this->input->post('status')
            );
            
            $result = $this->Client_model->update_client($id, $client_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Client updated successfully!');
                header('Location: /smart_core_erp/clients');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to update client. Please try again.');
                header('Location: /smart_core_erp/clients/edit/' . $id);
                exit();
            }
        }
    }
    
    // Delete client
    public function delete($id) {
        $result = $this->Client_model->delete_client($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Client deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete client. Please try again.');
        }
        
        header('Location: /smart_core_erp/clients');
        exit();
    }
    
    // Generate unique client code
    private function generate_client_code() {
        $prefix = 'CL';
        $timestamp = date('YmdHis');
        $random = mt_rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }
}
?>