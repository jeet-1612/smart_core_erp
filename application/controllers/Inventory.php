<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Inventory_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function index() {
        $data['title'] = 'Inventory Dashboard - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get inventory summary
        $data['inventory_summary'] = $this->Inventory_model->get_inventory_summary();
        $data['low_stock_items'] = $this->Inventory_model->get_low_stock_items();
        $data['recent_movements'] = $this->Inventory_model->get_recent_movements();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inventory/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function products() {
        $data['title'] = 'Products - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all products
        $data['products'] = $this->Inventory_model->get_products();
        $data['categories'] = $this->Inventory_model->get_categories();
        $data['suppliers'] = $this->Inventory_model->get_suppliers();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inventory/products', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function categories() {
        $data['title'] = 'Categories - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all categories
        $data['categories'] = $this->Inventory_model->get_categories();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inventory/categories', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function stock_movements() {
        $data['title'] = 'Stock Movements - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get filter parameters
        $product_id = $this->input->get('product_id');
        $movement_type = $this->input->get('movement_type');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $data['movements'] = $this->Inventory_model->get_stock_movements($product_id, $movement_type, $start_date, $end_date);
        $data['products'] = $this->Inventory_model->get_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inventory/stock_movements', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function stock_adjustment() {
        $data['title'] = 'Stock Adjustment - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['products'] = $this->Inventory_model->get_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('inventory/stock_adjustment', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Add new product
    public function add_product() {
        $this->form_validation->set_rules('product_code', 'Product Code', 'required');
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('cost_price', 'Cost Price', 'required|numeric');
        $this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $product_data = array(
                'product_code' => $this->input->post('product_code'),
                'product_name' => $this->input->post('product_name'),
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category_id'),
                'supplier_id' => $this->input->post('supplier_id'),
                'cost_price' => $this->input->post('cost_price'),
                'selling_price' => $this->input->post('selling_price'),
                'reorder_level' => $this->input->post('reorder_level'),
                'initial_stock' => $this->input->post('initial_stock'),
                'unit_of_measure' => $this->input->post('unit_of_measure'),
                'is_active' => 1,
                'created_by' => $this->session->userdata('user_id')
            );
            
            $result = $this->Inventory_model->add_product($product_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Product added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add product. Please try again.');
            }
        }
        
        redirect('inventory/products');
    }
    
    // Add new category
    public function add_category() {
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $category_data = array(
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description'),
                'is_active' => 1
            );
            
            $result = $this->Inventory_model->add_category($category_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Category added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add category. Please try again.');
            }
        }
        
        redirect('inventory/categories');
    }
    
    // Process stock adjustment
    public function process_stock_adjustment() {
        $this->form_validation->set_rules('product_id', 'Product', 'required');
        $this->form_validation->set_rules('adjustment_type', 'Adjustment Type', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('reason', 'Reason', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $adjustment_data = array(
                'product_id' => $this->input->post('product_id'),
                'adjustment_type' => $this->input->post('adjustment_type'),
                'quantity' => $this->input->post('quantity'),
                'reason' => $this->input->post('reason'),
                'reference' => $this->input->post('reference'),
                'adjusted_by' => $this->session->userdata('user_id')
            );
            
            $result = $this->Inventory_model->process_stock_adjustment($adjustment_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Stock adjustment processed successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to process stock adjustment. Please try again.');
            }
        }
        
        redirect('inventory/stock_adjustment');
    }
    
    // Get product details via AJAX
    public function get_product_details($product_id) {
        $product = $this->Inventory_model->get_product_by_id($product_id);
        echo json_encode($product);
    }
}
?>