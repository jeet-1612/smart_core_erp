<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Vendor_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Location: /smart_core_erp/login');
            exit();
        }
    }
    
    public function index() {
        $data['title'] = 'Vendors - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all vendors
        $data['vendors'] = $this->Vendor_model->get_all_vendors();
        
        $this->load->view('templates/header', $data);
        $this->load->view('vendors/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function add() {
        $data['title'] = 'Add New Vendor - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $this->load->view('templates/header', $data);
        $this->load->view('vendors/add', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit($id) {
        $data['title'] = 'Edit Vendor - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get vendor data
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id);
        
        if (!$data['vendor']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('vendors/edit', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view($id) {
        $data['title'] = 'Vendor Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get vendor data
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id);
        
        if (!$data['vendor']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('vendors/view', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process add vendor form
    public function process_add() {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/vendors/add');
            exit();
        } else {
            $vendor_data = array(
                'vendor_code' => $this->generate_vendor_code(),
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
                'bank_name' => $this->input->post('bank_name'),
                'bank_account_number' => $this->input->post('bank_account_number'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'status' => $this->input->post('status'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $vendor_id = $this->Vendor_model->create_vendor($vendor_data);
            
            if ($vendor_id) {
                $this->session->set_flashdata('success', 'Vendor added successfully!');
                header('Location: /smart_core_erp/vendors');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to add vendor. Please try again.');
                header('Location: /smart_core_erp/vendors/add');
                exit();
            }
        }
    }
    
    // Process edit vendor form
    public function process_edit($id) {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/vendors/edit/' . $id);
            exit();
        } else {
            $vendor_data = array(
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
                'bank_name' => $this->input->post('bank_name'),
                'bank_account_number' => $this->input->post('bank_account_number'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'status' => $this->input->post('status')
            );
            
            $result = $this->Vendor_model->update_vendor($id, $vendor_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Vendor updated successfully!');
                header('Location: /smart_core_erp/vendors');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to update vendor. Please try again.');
                header('Location: /smart_core_erp/vendors/edit/' . $id);
                exit();
            }
        }
    }
    
    // Delete vendor
    public function delete($id) {
        $result = $this->Vendor_model->delete_vendor($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Vendor deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete vendor. Please try again.');
        }
        
        header('Location: /smart_core_erp/vendors');
        exit();
    }
    
    // Generate unique vendor code
    private function generate_vendor_code() {
        $prefix = 'VEN';
        $timestamp = date('YmdHis');
        $random = mt_rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }
}
?>