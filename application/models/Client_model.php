<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all clients
    public function get_all_clients() {
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('clients');
        return $query->result();
    }

    // Get client by ID
    public function get_client_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('clients');
        return $query->row();
    }

    // Create new client
    public function create_client($data) {
        return $this->db->insert('clients', $data);
    }

    // Update client
    public function update_client($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('clients', $data);
    }

    // Delete client
    public function delete_client($id) {
        $this->db->where('id', $id);
        return $this->db->delete('clients');
    }

    // Get clients count
    public function get_clients_count() {
        return $this->db->count_all('clients');
    }

    // Get active clients
    public function get_active_clients() {
        $this->db->where('status', 'active');
        return $this->db->count_all_results('clients');
    }
}
?>