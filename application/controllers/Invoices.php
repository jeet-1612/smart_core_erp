<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Invoice_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function index() {
        $data['title'] = 'Invoices - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get invoices with filters
        $status = $this->input->get('status');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $data['invoices'] = $this->Invoice_model->get_invoices($status, $start_date, $end_date);
        $data['invoice_stats'] = $this->Invoice_model->get_invoice_statistics();
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function create() {
        $data['title'] = 'Create Invoice - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['customers'] = $this->Invoice_model->get_customers();
        $data['products'] = $this->Invoice_model->get_products();
        $data['tax_rates'] = $this->Invoice_model->get_tax_rates();
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/create', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view($id) {
        $data['title'] = 'Invoice Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['invoice'] = $this->Invoice_model->get_invoice_by_id($id);
        $data['invoice_items'] = $this->Invoice_model->get_invoice_items($id);
        
        if (!$data['invoice']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/view', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function edit($id) {
        $data['title'] = 'Edit Invoice - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['invoice'] = $this->Invoice_model->get_invoice_by_id($id);
        $data['invoice_items'] = $this->Invoice_model->get_invoice_items($id);
        $data['customers'] = $this->Invoice_model->get_customers();
        $data['products'] = $this->Invoice_model->get_products();
        $data['tax_rates'] = $this->Invoice_model->get_tax_rates();
        
        if (!$data['invoice']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/edit', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function customers() {
        $data['title'] = 'Customers - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $data['customers'] = $this->Invoice_model->get_customers();
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/customers', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process create invoice
    public function process_invoice() {
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
        $this->form_validation->set_rules('due_date', 'Due Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('invoices/create');
        } else {
            $invoice_data = array(
                'invoice_number' => $this->generate_invoice_number(),
                'customer_id' => $this->input->post('customer_id'),
                'invoice_date' => $this->input->post('invoice_date'),
                'due_date' => $this->input->post('due_date'),
                'reference' => $this->input->post('reference'),
                'customer_notes' => $this->input->post('customer_notes'),
                'terms_conditions' => $this->input->post('terms_conditions'),
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'balance_due' => 0,
                'status' => 'draft',
                'created_by' => $this->session->userdata('user_id')
            );
            
            $invoice_id = $this->Invoice_model->create_invoice($invoice_data);
            
            if ($invoice_id) {
                // Process items
                $items = $this->input->post('items');
                $subtotal = 0;
                
                if (!empty($items)) {
                    foreach ($items as $item) {
                        if (!empty($item['product_id']) && !empty($item['quantity'])) {
                            $quantity = floatval($item['quantity']);
                            $unit_price = floatval($item['unit_price']);
                            $tax_rate = floatval($item['tax_rate']);
                            
                            $item_total = $quantity * $unit_price;
                            $item_tax = $item_total * ($tax_rate / 100);
                            
                            $item_data = array(
                                'invoice_id' => $invoice_id,
                                'product_id' => $item['product_id'],
                                'description' => $item['description'],
                                'quantity' => $quantity,
                                'unit_price' => $unit_price,
                                'tax_rate' => $tax_rate,
                                'line_total' => $item_total + $item_tax
                            );
                            
                            $this->Invoice_model->add_invoice_item($item_data);
                            
                            $subtotal += $item_total;
                            $invoice_data['tax_amount'] += $item_tax;
                        }
                    }
                }
                
                $invoice_data['subtotal'] = $subtotal;
                $invoice_data['total_amount'] = $subtotal + $invoice_data['tax_amount'];
                $invoice_data['balance_due'] = $invoice_data['total_amount'];
                
                // Update invoice with totals
                $this->Invoice_model->update_invoice_totals($invoice_id, $invoice_data);
                
                $this->session->set_flashdata('success', 'Invoice created successfully!');
                redirect('invoices/view/' . $invoice_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to create invoice. Please try again.');
                redirect('invoices/create');
            }
        }
    }
    
    // Update invoice
    public function update_invoice($id) {
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
        $this->form_validation->set_rules('due_date', 'Due Date', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('invoices/edit/' . $id);
        } else {
            $invoice_data = array(
                'customer_id' => $this->input->post('customer_id'),
                'invoice_date' => $this->input->post('invoice_date'),
                'due_date' => $this->input->post('due_date'),
                'reference' => $this->input->post('reference'),
                'customer_notes' => $this->input->post('customer_notes'),
                'terms_conditions' => $this->input->post('terms_conditions'),
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'balance_due' => 0
            );
            
            // Delete existing items
            $this->Invoice_model->delete_invoice_items($id);
            
            // Process new items
            $items = $this->input->post('items');
            $subtotal = 0;
            
            if (!empty($items)) {
                foreach ($items as $item) {
                    if (!empty($item['product_id']) && !empty($item['quantity'])) {
                        $quantity = floatval($item['quantity']);
                        $unit_price = floatval($item['unit_price']);
                        $tax_rate = floatval($item['tax_rate']);
                        
                        $item_total = $quantity * $unit_price;
                        $item_tax = $item_total * ($tax_rate / 100);
                        
                        $item_data = array(
                            'invoice_id' => $id,
                            'product_id' => $item['product_id'],
                            'description' => $item['description'],
                            'quantity' => $quantity,
                            'unit_price' => $unit_price,
                            'tax_rate' => $tax_rate,
                            'line_total' => $item_total + $item_tax
                        );
                        
                        $this->Invoice_model->add_invoice_item($item_data);
                        
                        $subtotal += $item_total;
                        $invoice_data['tax_amount'] += $item_tax;
                    }
                }
            }
            
            $invoice_data['subtotal'] = $subtotal;
            $invoice_data['total_amount'] = $subtotal + $invoice_data['tax_amount'];
            $invoice_data['balance_due'] = $invoice_data['total_amount'];
            
            // Update invoice
            $result = $this->Invoice_model->update_invoice($id, $invoice_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Invoice updated successfully!');
                redirect('invoices/view/' . $id);
            } else {
                $this->session->set_flashdata('error', 'Failed to update invoice. Please try again.');
                redirect('invoices/edit/' . $id);
            }
        }
    }
    
    // Add customer
    public function add_customer() {
        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $customer_data = array(
                'customer_name' => $this->input->post('customer_name'),
                'contact_person' => $this->input->post('contact_person'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zip_code' => $this->input->post('zip_code'),
                'country' => $this->input->post('country'),
                'tax_number' => $this->input->post('tax_number'),
                'is_active' => 1
            );
            
            $result = $this->Invoice_model->add_customer($customer_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Customer added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add customer. Please try again.');
            }
        }
        
        redirect('invoices/customers');
    }
    
    // Update invoice status
    public function update_status($id, $status) {
        $allowed_statuses = array('draft', 'sent', 'paid', 'cancelled');
        
        if (!in_array($status, $allowed_statuses)) {
            $this->session->set_flashdata('error', 'Invalid status.');
            redirect('invoices');
        }
        
        $result = $this->Invoice_model->update_invoice_status($id, $status);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Invoice status updated to ' . $status . '!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update invoice status.');
        }
        
        redirect('invoices/view/' . $id);
    }
    
    // Record payment
    public function record_payment($id) {
        $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('invoices/view/' . $id);
        } else {
            $payment_data = array(
                'invoice_id' => $id,
                'payment_amount' => $this->input->post('payment_amount'),
                'payment_date' => $this->input->post('payment_date'),
                'payment_method' => $this->input->post('payment_method'),
                'reference' => $this->input->post('reference'),
                'notes' => $this->input->post('notes'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $result = $this->Invoice_model->record_payment($payment_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Payment recorded successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to record payment. Please try again.');
            }
            
            redirect('invoices/view/' . $id);
        }
    }
    
    // Delete invoice
    public function delete_invoice($id) {
        $result = $this->Invoice_model->delete_invoice($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Invoice deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete invoice. Please try again.');
        }
        
        redirect('invoices');
    }
    
    // Generate unique invoice number
    private function generate_invoice_number() {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        
        // Get the last invoice number
        $this->db->select('invoice_number');
        $this->db->like('invoice_number', $prefix . $year . $month, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('invoices');
        
        if ($query->num_rows() > 0) {
            $last_number = $query->row()->invoice_number;
            $last_seq = intval(substr($last_number, -4));
            $next_seq = str_pad($last_seq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_seq = '0001';
        }
        
        return $prefix . $year . $month . $next_seq;
    }
    
    // Print invoice
    public function print_invoice($id) {
        $data['invoice'] = $this->Invoice_model->get_invoice_by_id($id);
        $data['invoice_items'] = $this->Invoice_model->get_invoice_items($id);
        
        if (!$data['invoice']) {
            show_404();
        }
        
        $this->load->view('invoices/print', $data);
    }
    
    // Export invoice as PDF
    public function pdf_invoice($id) {
        // You'll need to install a PDF library like TCPDF or Dompdf
        // This is a basic implementation
        $data['invoice'] = $this->Invoice_model->get_invoice_by_id($id);
        $data['invoice_items'] = $this->Invoice_model->get_invoice_items($id);
        
        if (!$data['invoice']) {
            show_404();
        }
        
        // Load PDF library (you need to install this)
        // $this->load->library('pdf');
        
        $html = $this->load->view('invoices/pdf', $data, TRUE);
        
        // Generate PDF (implementation depends on your PDF library)
        // $this->pdf->generate($html, 'invoice_' . $data['invoice']->invoice_number . '.pdf');
        
        // For now, we'll just show the PDF view
        $this->load->view('invoices/pdf', $data);
    }
}
?>