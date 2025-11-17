<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all purchase orders
    public function get_all_purchase_orders() {
        $this->db->select('po.*, v.company_name, v.contact_person');
        $this->db->from('purchase_orders po');
        $this->db->join('vendors v', 'po.vendor_id = v.id', 'left');
        $this->db->order_by('po.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get purchase order by ID
    public function get_purchase_order_by_id($id) {
        $this->db->select('po.*, v.company_name, v.contact_person, v.email, v.phone, v.address, v.gstin');
        $this->db->from('purchase_orders po');
        $this->db->join('vendors v', 'po.vendor_id = v.id', 'left');
        $this->db->where('po.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get purchase order items
    public function get_purchase_order_items($po_id) {
        $this->db->select('poi.*, p.product_name, p.product_code, p.hsn_code');
        $this->db->from('purchase_order_items poi');
        $this->db->join('products p', 'poi.product_id = p.id', 'left');
        $this->db->where('poi.po_id', $po_id);
        $query = $this->db->get();
        return $query->result();
    }

    // Create new purchase order
    public function create_purchase_order($data) {
        $this->db->insert('purchase_orders', $data);
        return $this->db->insert_id();
    }

    // Add purchase order item
    public function add_purchase_order_item($data) {
        return $this->db->insert('purchase_order_items', $data);
    }

    // Update purchase order
    public function update_purchase_order($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('purchase_orders', $data);
    }

    // Update purchase order totals
    public function update_purchase_order_totals($id, $sub_total, $tax_amount, $total_amount) {
        $data = array(
            'sub_total' => $sub_total,
            'tax_amount' => $tax_amount,
            'total_amount' => $total_amount
        );
        $this->db->where('id', $id);
        return $this->db->update('purchase_orders', $data);
    }

    // Update purchase order status
    public function update_purchase_order_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('purchase_orders', array('status' => $status));
    }

    // Delete purchase order
    public function delete_purchase_order($id) {
        // First delete items
        $this->delete_purchase_order_items($id);
        
        // Then delete purchase order
        $this->db->where('id', $id);
        return $this->db->delete('purchase_orders');
    }

    // Delete purchase order items
    public function delete_purchase_order_items($po_id) {
        $this->db->where('po_id', $po_id);
        return $this->db->delete('purchase_order_items');
    }

    // Get purchase statistics
    public function get_purchase_statistics() {
        $data = array();
        
        // Total purchase count
        $data['total_purchases'] = $this->db->count_all('purchase_orders');
        
        // Total purchase amount
        $this->db->select_sum('total_amount');
        $query = $this->db->get('purchase_orders');
        $data['total_amount'] = $query->row()->total_amount ?: 0;
        
        // Pending orders
        $this->db->where('status', 'draft');
        $data['pending_orders'] = $this->db->count_all_results('purchase_orders');
        
        // Received orders
        $this->db->where('status', 'received');
        $data['received_orders'] = $this->db->count_all_results('purchase_orders');
        
        return $data;
    }
}
?>