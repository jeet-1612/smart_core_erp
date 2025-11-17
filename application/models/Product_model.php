<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all products
    public function get_all_products() {
        $this->db->where('is_active', 1);
        $this->db->order_by('product_name', 'ASC');
        $query = $this->db->get('products');
        return $query->result();
    }

    // Get product by ID
    public function get_product_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('products');
        return $query->row();
    }

    // Update product stock
    public function update_product_stock($product_id, $quantity) {
        $this->db->set('current_stock', 'current_stock - ' . $quantity, FALSE);
        $this->db->where('id', $product_id);
        return $this->db->update('products');
    }

    public function update_product_stock_receive($product_id, $quantity) {
        $this->db->set('current_stock', 'current_stock + ' . $quantity, FALSE);
        $this->db->where('id', $product_id);
        return $this->db->update('products');
    }    
}
?>