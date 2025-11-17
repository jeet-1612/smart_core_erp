<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
    
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
        $data['title'] = 'Purchase - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get all purchase orders
        $data['purchase_orders'] = $this->Purchase_model->get_all_purchase_orders();
        
        $this->load->view('templates/header', $data);
        $this->load->view('purchase/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function create() {
        $data['title'] = 'Create Purchase Order - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get vendors and products for dropdowns
        $data['vendors'] = $this->Vendor_model->get_all_vendors();
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('purchase/create', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit($id) {
        $data['title'] = 'Edit Purchase Order - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get purchase order data
        $data['purchase_order'] = $this->Purchase_model->get_purchase_order_by_id($id);
        $data['purchase_order_items'] = $this->Purchase_model->get_purchase_order_items($id);
        
        if (!$data['purchase_order']) {
            show_404();
        }
        
        // Get vendors and products for dropdowns
        $data['vendors'] = $this->Vendor_model->get_all_vendors();
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('templates/header', $data);
        $this->load->view('purchase/edit', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view($id) {
        $data['title'] = 'Purchase Order Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get purchase order data
        $data['purchase_order'] = $this->Purchase_model->get_purchase_order_by_id($id);
        $data['purchase_order_items'] = $this->Purchase_model->get_purchase_order_items($id);
        
        if (!$data['purchase_order']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('purchase/view', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process create purchase order
    public function process_create() {
        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required');
        $this->form_validation->set_rules('po_date', 'Purchase Order Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/purchase/create');
            exit();
        } else {
            $purchase_data = array(
                'po_number' => $this->generate_po_number(),
                'vendor_id' => $this->input->post('vendor_id'),
                'po_date' => $this->input->post('po_date'),
                'expected_delivery_date' => $this->input->post('expected_delivery_date'),
                'status' => 'draft',
                'sub_total' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'notes' => $this->input->post('notes'),
                'terms_conditions' => $this->input->post('terms_conditions'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $po_id = $this->Purchase_model->create_purchase_order($purchase_data);
            
            if ($po_id) {
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
                                'po_id' => $po_id,
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                                'tax_rate' => $item['tax_rate'],
                                'tax_amount' => $item_tax,
                                'total_amount' => $item_total + $item_tax
                            );
                            
                            $this->Purchase_model->add_purchase_order_item($item_data);
                            
                            $sub_total += $item_total;
                            $tax_amount += $item_tax;
                        }
                    }
                }
                
                // Update purchase order with totals
                $total_amount = $sub_total + $tax_amount;
                $this->Purchase_model->update_purchase_order_totals($po_id, $sub_total, $tax_amount, $total_amount);
                
                $this->session->set_flashdata('success', 'Purchase order created successfully!');
                header('Location: /smart_core_erp/purchase');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to create purchase order. Please try again.');
                header('Location: /smart_core_erp/purchase/create');
                exit();
            }
        }
    }
    
    // Process edit purchase order
    public function process_edit($id) {
        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required');
        $this->form_validation->set_rules('po_date', 'Purchase Order Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/purchase/edit/' . $id);
            exit();
        } else {
            $purchase_data = array(
                'vendor_id' => $this->input->post('vendor_id'),
                'po_date' => $this->input->post('po_date'),
                'expected_delivery_date' => $this->input->post('expected_delivery_date'),
                'status' => $this->input->post('status'),
                'notes' => $this->input->post('notes'),
                'terms_conditions' => $this->input->post('terms_conditions')
            );
            
            // First delete existing items
            $this->Purchase_model->delete_purchase_order_items($id);
            
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
                            'po_id' => $id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'tax_rate' => $item['tax_rate'],
                            'tax_amount' => $item_tax,
                            'total_amount' => $item_total + $item_tax
                        );
                        
                        $this->Purchase_model->add_purchase_order_item($item_data);
                        
                        $sub_total += $item_total;
                        $tax_amount += $item_tax;
                    }
                }
            }
            
            // Update purchase order with totals
            $total_amount = $sub_total + $tax_amount;
            $purchase_data['sub_total'] = $sub_total;
            $purchase_data['tax_amount'] = $tax_amount;
            $purchase_data['total_amount'] = $total_amount;
            
            $result = $this->Purchase_model->update_purchase_order($id, $purchase_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Purchase order updated successfully!');
                header('Location: /smart_core_erp/purchase');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to update purchase order. Please try again.');
                header('Location: /smart_core_erp/purchase/edit/' . $id);
                exit();
            }
        }
    }
    
    // Delete purchase order
    public function delete($id) {
        $result = $this->Purchase_model->delete_purchase_order($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Purchase order deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete purchase order. Please try again.');
        }
        
        header('Location: /smart_core_erp/purchase');
        exit();
    }
    
    // Update purchase order status
    public function update_status($id) {
        $purchase_order = $this->Purchase_model->get_purchase_order_by_id($id);
        
        if (!$purchase_order) {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Purchase order not found'));
                return;
            }
            show_404();
        }
        
        $new_status = $this->input->post('status');
        $allowed_statuses = array('draft', 'sent', 'confirmed', 'received', 'cancelled');
        
        if (!in_array($new_status, $allowed_statuses)) {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Invalid status'));
                return;
            }
            $this->session->set_flashdata('error', 'Invalid status');
            header('Location: /smart_core_erp/purchase/view/' . $id);
            exit();
        }
        
        $result = $this->Purchase_model->update_purchase_order_status($id, $new_status);
        
        if ($result) {
            // If status is changed to received, update inventory
            if ($new_status == 'received') {
                $this->update_inventory_for_receipt($id);
            }
            
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => true, 'message' => 'Status updated successfully'));
                return;
            }
            $this->session->set_flashdata('success', 'Purchase order status updated successfully!');
        } else {
            if ($this->input->post('ajax')) {
                echo json_encode(array('success' => false, 'message' => 'Failed to update status'));
                return;
            }
            $this->session->set_flashdata('error', 'Failed to update purchase order status');
        }
        
        header('Location: /smart_core_erp/purchase/view/' . $id);
        exit();
    }
    
    // Update inventory when purchase order is received
    private function update_inventory_for_receipt($po_id) {
        $items = $this->Purchase_model->get_purchase_order_items($po_id);
        
        foreach ($items as $item) {
            $this->Product_model->update_product_stock_receive($item->product_id, $item->quantity);
            
            // Add inventory transaction
            $transaction_data = array(
                'product_id' => $item->product_id,
                'transaction_type' => 'purchase',
                'reference_id' => $po_id,
                'quantity' => $item->quantity,
                'unit_cost' => $item->unit_price,
                'transaction_date' => date('Y-m-d H:i:s'),
                'notes' => 'Purchase order receipt: ' . $this->get_po_number($po_id),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $this->db->insert('inventory_transactions', $transaction_data);
        }
    }
    
    // Helper function to get PO number
    private function get_po_number($po_id) {
        $purchase_order = $this->Purchase_model->get_purchase_order_by_id($po_id);
        return $purchase_order ? $purchase_order->po_number : 'N/A';
    }
    
    // Generate unique purchase order number
    private function generate_po_number() {
        $prefix = 'PO';
        $timestamp = date('Ymd');
        $random = mt_rand(100, 999);
        return $prefix . $timestamp . $random;
    }
}
?>