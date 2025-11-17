<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {
    
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
        $data['title'] = 'Sales - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all sales orders
        $data['sales_orders'] = $this->Sales_model->get_all_sales_orders();
        
        $this->load->view('templates/header', $data);
        $this->load->view('sales/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function create() {
        $data['title'] = 'Create Sales Order - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get clients and products for dropdowns
        $data['clients'] = $this->Client_model->get_all_clients();
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('sales/create', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit($id) {
        $data['title'] = 'Edit Sales Order - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get sales order data
        $data['sales_order'] = $this->Sales_model->get_sales_order_by_id($id);
        $data['sales_order_items'] = $this->Sales_model->get_sales_order_items($id);
        
        if (!$data['sales_order']) {
            show_404();
        }
        
        // Get clients and products for dropdowns
        $data['clients'] = $this->Client_model->get_all_clients();
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('sales/edit', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view($id) {
        $data['title'] = 'Sales Order Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get sales order data
        $data['sales_order'] = $this->Sales_model->get_sales_order_by_id($id);
        $data['sales_order_items'] = $this->Sales_model->get_sales_order_items($id);
        
        if (!$data['sales_order']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('sales/view', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function invoices() {
        $data['title'] = 'Invoices - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all invoices
        $data['invoices'] = $this->Sales_model->get_all_invoices();
        
        $this->load->view('templates/header', $data);
        $this->load->view('sales/invoices', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process create sales order
    public function process_create() {
        $this->form_validation->set_rules('client_id', 'Client', 'required');
        $this->form_validation->set_rules('so_date', 'Sales Order Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/sales/create');
            exit();
        } else {
            $sales_data = array(
                'so_number' => $this->generate_so_number(),
                'client_id' => $this->input->post('client_id'),
                'so_date' => $this->input->post('so_date'),
                'delivery_date' => $this->input->post('delivery_date'),
                'status' => 'draft',
                'sub_total' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'notes' => $this->input->post('notes'),
                'terms_conditions' => $this->input->post('terms_conditions'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $so_id = $this->Sales_model->create_sales_order($sales_data);
            
            if ($so_id) {
                // Process items
                $items = $this->input->post('items');
                $sub_total = 0;
                $tax_amount = 0;
                
                if (!empty($items)) {
                    foreach ($items as $item) {
                        if (!empty($item['product_id']) && !empty($item['quantity'])) {
                            $product = $this->Product_model->get_product_by_id($item['product_id']);
                            $item_total = $item['quantity'] * $item['unit_price'];
                            $item_tax = $item_total * ($item['tax_rate'] / 100);
                            
                            $item_data = array(
                                'so_id' => $so_id,
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                                'tax_rate' => $item['tax_rate'],
                                'tax_amount' => $item_tax,
                                'total_amount' => $item_total + $item_tax
                            );
                            
                            $this->Sales_model->add_sales_order_item($item_data);
                            
                            $sub_total += $item_total;
                            $tax_amount += $item_tax;
                        }
                    }
                }
                
                // Update sales order with totals
                $total_amount = $sub_total + $tax_amount;
                $this->Sales_model->update_sales_order_totals($so_id, $sub_total, $tax_amount, $total_amount);
                
                $this->session->set_flashdata('success', 'Sales order created successfully!');
                header('Location: /smart_core_erp/sales');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to create sales order. Please try again.');
                header('Location: /smart_core_erp/sales/create');
                exit();
            }
        }
    }
    
    // Process edit sales order
    public function process_edit($id) {
        $this->form_validation->set_rules('client_id', 'Client', 'required');
        $this->form_validation->set_rules('so_date', 'Sales Order Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/sales/edit/' . $id);
            exit();
        } else {
            $sales_data = array(
                'client_id' => $this->input->post('client_id'),
                'so_date' => $this->input->post('so_date'),
                'delivery_date' => $this->input->post('delivery_date'),
                'status' => $this->input->post('status'),
                'notes' => $this->input->post('notes'),
                'terms_conditions' => $this->input->post('terms_conditions')
            );
            
            // First delete existing items
            $this->Sales_model->delete_sales_order_items($id);
            
            // Process new items
            $items = $this->input->post('items');
            $sub_total = 0;
            $tax_amount = 0;
            
            if (!empty($items)) {
                foreach ($items as $item) {
                    if (!empty($item['product_id']) && !empty($item['quantity'])) {
                        $item_total = $item['quantity'] * $item['unit_price'];
                        $item_tax = $item_total * ($item['tax_rate'] / 100);
                        
                        $item_data = array(
                            'so_id' => $id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'tax_rate' => $item['tax_rate'],
                            'tax_amount' => $item_tax,
                            'total_amount' => $item_total + $item_tax
                        );
                        
                        $this->Sales_model->add_sales_order_item($item_data);
                        
                        $sub_total += $item_total;
                        $tax_amount += $item_tax;
                    }
                }
            }
            
            // Update sales order with totals
            $total_amount = $sub_total + $tax_amount;
            $sales_data['sub_total'] = $sub_total;
            $sales_data['tax_amount'] = $tax_amount;
            $sales_data['total_amount'] = $total_amount;
            
            $result = $this->Sales_model->update_sales_order($id, $sales_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Sales order updated successfully!');
                header('Location: /smart_core_erp/sales');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to update sales order. Please try again.');
                header('Location: /smart_core_erp/sales/edit/' . $id);
                exit();
            }
        }
    }
    
    // Delete sales order
    public function delete($id) {
        $result = $this->Sales_model->delete_sales_order($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Sales order deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete sales order. Please try again.');
        }
        
        header('Location: /smart_core_erp/sales');
        exit();
    }
    
    // Create invoice from sales order
    public function create_invoice($so_id) {
        $sales_order = $this->Sales_model->get_sales_order_by_id($so_id);
        
        if (!$sales_order) {
            show_404();
        }
        
        $invoice_data = array(
            'invoice_number' => $this->generate_invoice_number(),
            'so_id' => $so_id,
            'client_id' => $sales_order->client_id,
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+15 days')),
            'status' => 'draft',
            'sub_total' => $sales_order->sub_total,
            'tax_amount' => $sales_order->tax_amount,
            'total_amount' => $sales_order->total_amount,
            'created_by' => $this->session->userdata('user_id')
        );
        
        $invoice_id = $this->Sales_model->create_invoice($invoice_data);
        
        if ($invoice_id) {
            // Copy sales order items to invoice items
            $sales_order_items = $this->Sales_model->get_sales_order_items($so_id);
            
            foreach ($sales_order_items as $item) {
                $invoice_item_data = array(
                    'invoice_id' => $invoice_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'tax_amount' => $item->tax_amount,
                    'total_amount' => $item->total_amount
                );
                
                $this->Sales_model->add_invoice_item($invoice_item_data);
            }
            
            // Update sales order status
            $this->Sales_model->update_sales_order_status($so_id, 'confirmed');
            
            $this->session->set_flashdata('success', 'Invoice created successfully!');
            header('Location: /smart_core_erp/sales/invoices');
            exit();
        } else {
            $this->session->set_flashdata('error', 'Failed to create invoice. Please try again.');
            header('Location: /smart_core_erp/sales');
            exit();
        }
    }
    
    // Generate unique sales order number
    private function generate_so_number() {
        $prefix = 'SO';
        $timestamp = date('Ymd');
        $random = mt_rand(100, 999);
        return $prefix . $timestamp . $random;
    }
    
    // Generate unique invoice number
    private function generate_invoice_number() {
        $prefix = 'INV';
        $timestamp = date('Ymd');
        $random = mt_rand(100, 999);
        return $prefix . $timestamp . $random;
    }

    // Update sales order status
    public function update_status($id) {
        $sales_order = $this->Sales_model->get_sales_order_by_id($id);
        
        if (!$sales_order) {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Sales order not found'));
                return;
            }
            show_404();
        }
        
        $new_status = $this->input->post('status');
        $allowed_statuses = array('draft', 'confirmed', 'shipped', 'delivered', 'cancelled');
        
        if (!in_array($new_status, $allowed_statuses)) {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Invalid status'));
                return;
            }
            $this->session->set_flashdata('error', 'Invalid status');
            header('Location: /smart_core_erp/sales/view/' . $id);
            exit();
        }
        
        $result = $this->Sales_model->update_sales_order_status($id, $new_status);
        
        if ($result) {
            // If status is changed to delivered, update inventory
            if ($new_status == 'delivered') {
                $this->update_inventory_for_delivery($id);
            }
            
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => true, 'message' => 'Status updated successfully'));
                return;
            }
            $this->session->set_flashdata('success', 'Sales order status updated successfully!');
        } else {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Failed to update status'));
                return;
            }
            $this->session->set_flashdata('error', 'Failed to update sales order status');
        }
        
        header('Location: /smart_core_erp/sales/view/' . $id);
        exit();
    }

    // Update inventory when order is delivered
    private function update_inventory_for_delivery($so_id) {
        $items = $this->Sales_model->get_sales_order_items($so_id);
        
        foreach ($items as $item) {
            $this->Product_model->update_product_stock($item->product_id, $item->quantity);
            
            // Add inventory transaction
            $transaction_data = array(
                'product_id' => $item->product_id,
                'transaction_type' => 'sale',
                'reference_id' => $so_id,
                'quantity' => $item->quantity,
                'unit_cost' => $item->unit_price,
                'transaction_date' => date('Y-m-d H:i:s'),
                'notes' => 'Sales order delivery: ' . $this->get_so_number($so_id),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $this->db->insert('inventory_transactions', $transaction_data);
        }
    }

    // Helper function to get SO number
    private function get_so_number($so_id) {
        $sales_order = $this->Sales_model->get_sales_order_by_id($so_id);
        return $sales_order ? $sales_order->so_number : 'N/A';
    }
}
?>