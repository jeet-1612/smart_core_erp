<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all vendors
    public function get_all_vendors() {
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('vendors');
        return $query->result();
    }

    // Get vendor by ID
    public function get_vendor_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('vendors');
        return $query->row();
    }

    // Create new vendor
    public function create_vendor($data) {
        return $this->db->insert('vendors', $data);
    }

    // Update vendor
    public function update_vendor($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('vendors', $data);
    }

    // Delete vendor
    public function delete_vendor($id) {
        $this->db->where('id', $id);
        return $this->db->delete('vendors');
    }

    // Get vendors count
    public function get_vendors_count() {
        return $this->db->count_all('vendors');
    }

    // Get active vendors
    public function get_active_vendors() {
        $this->db->where('status', 'active');
        return $this->db->count_all_results('vendors');
    }
}
?>