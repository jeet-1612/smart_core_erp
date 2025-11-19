<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get inventory summary for dashboard
    public function get_inventory_summary() {
        $data = array();
        
        try {
            // Check if products table exists
            if (!$this->db->table_exists('products')) {
                throw new Exception('Products table does not exist');
            }
            
            // Total Products
            $this->db->where('is_active', 1);
            $data['total_products'] = $this->db->count_all_results('products');
            
            // Total Categories
            if ($this->db->table_exists('categories')) {
                $this->db->where('is_active', 1);
                $data['total_categories'] = $this->db->count_all_results('categories');
            } else {
                $data['total_categories'] = 0;
            }
            
            // Total Stock Value
            if ($this->db->field_exists('current_stock', 'products') && $this->db->field_exists('cost_price', 'products')) {
                $this->db->select('SUM(current_stock * cost_price) as total_value', FALSE);
                $this->db->where('is_active', 1);
                $query = $this->db->get('products');
                $data['total_stock_value'] = $query->row()->total_value ?: 0;
            } else {
                $data['total_stock_value'] = 0;
            }
            
            // Low Stock Items Count
            if ($this->db->field_exists('reorder_level', 'products') && $this->db->field_exists('current_stock', 'products')) {
                $this->db->where('is_active', 1);
                $this->db->where('current_stock <= reorder_level');
                $this->db->where('current_stock >', 0);
                $data['low_stock_count'] = $this->db->count_all_results('products');
            } else {
                $data['low_stock_count'] = 0;
            }
            
            // Out of Stock Items Count
            if ($this->db->field_exists('current_stock', 'products')) {
                $this->db->where('is_active', 1);
                $this->db->where('current_stock', 0);
                $data['out_of_stock_count'] = $this->db->count_all_results('products');
            } else {
                $data['out_of_stock_count'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Inventory summary error: ' . $e->getMessage());
            // Set default values
            $data['total_products'] = 0;
            $data['total_categories'] = 0;
            $data['total_stock_value'] = 0;
            $data['low_stock_count'] = 0;
            $data['out_of_stock_count'] = 0;
        }
        
        return $data;
    }

    // Get low stock items with comprehensive checks
    public function get_low_stock_items() {
        try {
            // Check if required tables and columns exist
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            if (!$this->db->field_exists('reorder_level', 'products') || 
                !$this->db->field_exists('current_stock', 'products')) {
                return array();
            }
            
            $this->db->select('p.*');
            
            // Add category name if categories table and join are possible
            if ($this->db->table_exists('categories') && $this->db->field_exists('category_id', 'products')) {
                $this->db->select('c.category_name');
                $this->db->join('categories c', 'p.category_id = c.id', 'left');
            }
            
            $this->db->from('products p');
            $this->db->where('p.is_active', 1);
            $this->db->where('p.current_stock <= p.reorder_level');
            $this->db->where('p.current_stock >', 0);
            $this->db->order_by('p.current_stock', 'ASC');
            $this->db->limit(10);
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Low stock items error: ' . $e->getMessage());
            return array();
        }
    }

    // Get all products with safe joins
    public function get_products() {
        try {
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('p.*');
            
            // Add category name if possible
            if ($this->db->table_exists('categories') && $this->db->field_exists('category_id', 'products')) {
                $this->db->select('c.category_name');
                $this->db->join('categories c', 'p.category_id = c.id', 'left');
            } else {
                $this->db->select("'N/A' as category_name");
            }
            
            // Add supplier name if possible
            if ($this->db->table_exists('suppliers') && $this->db->field_exists('supplier_id', 'products')) {
                $this->db->select('s.supplier_name');
                $this->db->join('suppliers s', 'p.supplier_id = s.id', 'left');
            } else {
                $this->db->select("'N/A' as supplier_name");
            }
            
            $this->db->from('products p');
            $this->db->where('p.is_active', 1);
            $this->db->order_by('p.product_name', 'ASC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get products error: ' . $e->getMessage());
            return array();
        }
    }

    // Get recent stock movements with safe joins
    public function get_recent_movements() {
        try {
            if (!$this->db->table_exists('stock_movements') || !$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('sm.*, p.product_name, p.product_code');
            
            // Add user name if users table exists
            if ($this->db->table_exists('users') && $this->db->field_exists('adjusted_by', 'stock_movements')) {
                $this->db->select('u.first_name, u.last_name');
                $this->db->join('users u', 'sm.adjusted_by = u.id', 'left');
            } else {
                $this->db->select("'System' as first_name, '' as last_name");
            }
            
            $this->db->from('stock_movements sm');
            $this->db->join('products p', 'sm.product_id = p.id');
            $this->db->order_by('sm.created_at', 'DESC');
            $this->db->limit(10);
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Recent movements error: ' . $e->getMessage());
            return array();
        }
    }

    // Get all categories
    public function get_categories() {
        try {
            if (!$this->db->table_exists('categories')) {
                return array();
            }
            
            $this->db->where('is_active', 1);
            $this->db->order_by('category_name', 'ASC');
            $query = $this->db->get('categories');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get categories error: ' . $e->getMessage());
            return array();
        }
    }

    // Get all suppliers
    public function get_suppliers() {
        try {
            if (!$this->db->table_exists('suppliers')) {
                return array();
            }
            
            $this->db->where('is_active', 1);
            $this->db->order_by('supplier_name', 'ASC');
            $query = $this->db->get('suppliers');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get suppliers error: ' . $e->getMessage());
            return array();
        }
    }

    // Add new product with validation
    public function add_product($data) {
        try {
            if (!$this->db->table_exists('products')) {
                throw new Exception('Products table does not exist');
            }
            
            // Set initial stock
            $initial_stock = isset($data['initial_stock']) ? $data['initial_stock'] : 0;
            $data['current_stock'] = $initial_stock;
            
            // Remove initial_stock from data as it's not a column
            unset($data['initial_stock']);
            
            $result = $this->db->insert('products', $data);
            
            if ($result && $initial_stock > 0 && $this->db->table_exists('stock_movements')) {
                // Record initial stock movement
                $movement_data = array(
                    'product_id' => $this->db->insert_id(),
                    'adjustment_type' => 'in',
                    'quantity' => $initial_stock,
                    'reason' => 'Initial Stock',
                    'reference' => 'INITIAL',
                    'adjusted_by' => isset($data['created_by']) ? $data['created_by'] : 1
                );
                $this->db->insert('stock_movements', $movement_data);
            }
            
            return $result;
            
        } catch (Exception $e) {
            log_message('error', 'Add product error: ' . $e->getMessage());
            return false;
        }
    }

    // Add new category
    public function add_category($data) {
        try {
            if (!$this->db->table_exists('categories')) {
                throw new Exception('Categories table does not exist');
            }
            
            return $this->db->insert('categories', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Add category error: ' . $e->getMessage());
            return false;
        }
    }

    // Process stock adjustment
    public function process_stock_adjustment($data) {
        try {
            if (!$this->db->table_exists('products') || !$this->db->table_exists('stock_movements')) {
                throw new Exception('Required tables do not exist');
            }
            
            $this->db->trans_start();
            
            // Get current stock
            $this->db->select('current_stock');
            $this->db->where('id', $data['product_id']);
            $query = $this->db->get('products');
            
            if ($query->num_rows() == 0) {
                throw new Exception('Product not found');
            }
            
            $current_stock = $query->row()->current_stock;
            
            // Calculate new stock
            if ($data['adjustment_type'] == 'in') {
                $new_stock = $current_stock + $data['quantity'];
            } else {
                $new_stock = $current_stock - $data['quantity'];
                
                // Check if we have enough stock
                if ($new_stock < 0) {
                    throw new Exception('Insufficient stock for adjustment');
                }
            }
            
            // Update product stock
            $this->db->where('id', $data['product_id']);
            $this->db->update('products', array('current_stock' => $new_stock));
            
            // Record stock movement
            $this->db->insert('stock_movements', $data);
            
            $this->db->trans_complete();
            
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            log_message('error', 'Stock adjustment error: ' . $e->getMessage());
            $this->db->trans_rollback();
            return false;
        }
    }

    // Utility function to check database setup
    public function check_database_setup() {
        $tables = array('products', 'categories', 'stock_movements', 'suppliers');
        $results = array();
        
        foreach ($tables as $table) {
            $results[$table] = $this->db->table_exists($table);
        }
        
        return $results;
    }

    // Utility function to get table structure
    public function get_table_columns($table_name) {
        if ($this->db->table_exists($table_name)) {
            return $this->db->list_fields($table_name);
        }
        return array();
    }
}
?>