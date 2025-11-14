<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all sales orders
    public function get_all_sales_orders() {
        $this->db->select('so.*, c.company_name, c.contact_person');
        $this->db->from('sales_orders so');
        $this->db->join('clients c', 'so.client_id = c.id', 'left');
        $this->db->order_by('so.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get sales order by ID
    public function get_sales_order_by_id($id) {
        $this->db->select('so.*, c.company_name, c.contact_person, c.email, c.phone, c.address');
        $this->db->from('sales_orders so');
        $this->db->join('clients c', 'so.client_id = c.id', 'left');
        $this->db->where('so.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get sales order items
    public function get_sales_order_items($so_id) {
        $this->db->select('soi.*, p.product_name, p.product_code, p.hsn_code');
        $this->db->from('sales_order_items soi');
        $this->db->join('products p', 'soi.product_id = p.id', 'left');
        $this->db->where('soi.so_id', $so_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Create new sales order
    public function create_sales_order($data) {
        $this->db->insert('sales_orders', $data);
        return $this->db->insert_id();
    }

    // Add sales order item
    public function add_sales_order_item($data) {
        return $this->db->insert('sales_order_items', $data);
    }

    // Update sales order
    public function update_sales_order($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('sales_orders', $data);
    }

    // Update sales order totals
    public function update_sales_order_totals($id, $sub_total, $tax_amount, $total_amount) {
        $data = array(
            'sub_total' => $sub_total,
            'tax_amount' => $tax_amount,
            'total_amount' => $total_amount
        );
        $this->db->where('id', $id);
        return $this->db->update('sales_orders', $data);
    }

    // Update sales order status
    public function update_sales_order_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('sales_orders', array('status' => $status));
    }

    // Delete sales order
    public function delete_sales_order($id) {
        // First delete items
        $this->delete_sales_order_items($id);
        
        // Then delete sales order
        $this->db->where('id', $id);
        return $this->db->delete('sales_orders');
    }

    // Delete sales order items
    public function delete_sales_order_items($so_id) {
        $this->db->where('so_id', $so_id);
        return $this->db->delete('sales_order_items');
    }

    // Get all invoices
    public function get_all_invoices() {
        $this->db->select('i.*, c.company_name, c.contact_person, so.so_number');
        $this->db->from('invoices i');
        $this->db->join('clients c', 'i.client_id = c.id', 'left');
        $this->db->join('sales_orders so', 'i.so_id = so.id', 'left');
        $this->db->order_by('i.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Create invoice
    public function create_invoice($data) {
        $this->db->insert('invoices', $data);
        return $this->db->insert_id();
    }

    // Add invoice item
    public function add_invoice_item($data) {
        return $this->db->insert('invoice_items', $data);
    }

    // Get sales statistics
    public function get_sales_statistics() {
        $data = array();
        
        // Total sales count
        $data['total_sales'] = $this->db->count_all('sales_orders');
        
        // Total sales amount
        $this->db->select_sum('total_amount');
        $query = $this->db->get('sales_orders');
        $data['total_amount'] = $query->row()->total_amount ?: 0;
        
        // Pending orders
        $this->db->where('status', 'draft');
        $data['pending_orders'] = $this->db->count_all_results('sales_orders');
        
        // Confirmed orders
        $this->db->where('status', 'confirmed');
        $data['confirmed_orders'] = $this->db->count_all_results('sales_orders');
        
        return $data;
    }
}
?>