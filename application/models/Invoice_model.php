<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all invoices with filters - UPDATED with error handling
    public function get_invoices($status = null, $start_date = null, $end_date = null) {
        try {
            // Check if required tables exist
            if (!$this->db->table_exists('invoices')) {
                return array();
            }
            
            $this->db->select('i.*');
            
            // Add customer information if customers table exists and join is possible
            if ($this->db->table_exists('customers') && $this->db->field_exists('customer_id', 'invoices')) {
                $this->db->select('c.customer_name, c.email, c.phone');
                $this->db->join('customers c', 'i.customer_id = c.id', 'left');
            } else {
                $this->db->select("'N/A' as customer_name, '' as email, '' as phone");
            }
            
            $this->db->from('invoices i');
            
            if ($status) {
                $this->db->where('i.status', $status);
            }
            
            if ($start_date) {
                $this->db->where('i.invoice_date >=', $start_date);
            }
            
            if ($end_date) {
                $this->db->where('i.invoice_date <=', $end_date);
            }
            
            $this->db->order_by('i.invoice_date', 'DESC');
            $this->db->order_by('i.created_at', 'DESC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get invoices error: ' . $e->getMessage());
            return array();
        }
    }

    // Get invoice by ID - UPDATED with error handling
    public function get_invoice_by_id($id) {
        try {
            if (!$this->db->table_exists('invoices')) {
                return null;
            }
            
            $this->db->select('i.*');
            
            // Add customer information if possible
            if ($this->db->table_exists('customers') && $this->db->field_exists('customer_id', 'invoices')) {
                $this->db->select('c.customer_name, c.contact_person, c.email, c.phone, c.address, c.city, c.state, c.zip_code, c.country, c.tax_number');
                $this->db->join('customers c', 'i.customer_id = c.id', 'left');
            } else {
                $this->db->select("'N/A' as customer_name, '' as contact_person, '' as email, '' as phone, '' as address, '' as city, '' as state, '' as zip_code, '' as country, '' as tax_number");
            }
            
            $this->db->from('invoices i');
            $this->db->where('i.id', $id);
            $query = $this->db->get();
            return $query->row();
            
        } catch (Exception $e) {
            log_message('error', 'Get invoice by ID error: ' . $e->getMessage());
            return null;
        }
    }

    // Get invoice items - UPDATED with error handling
    public function get_invoice_items($invoice_id) {
        try {
            if (!$this->db->table_exists('invoice_items')) {
                return array();
            }
            
            $this->db->select('ii.*');
            
            // Add product information if products table exists
            if ($this->db->table_exists('products') && $this->db->field_exists('product_id', 'invoice_items')) {
                $this->db->select('p.product_name, p.product_code');
                $this->db->join('products p', 'ii.product_id = p.id', 'left');
            } else {
                $this->db->select("'N/A' as product_name, '' as product_code");
            }
            
            $this->db->from('invoice_items ii');
            $this->db->where('ii.invoice_id', $invoice_id);
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get invoice items error: ' . $e->getMessage());
            return array();
        }
    }

    // Get invoice statistics - UPDATED with error handling
    public function get_invoice_statistics() {
        $stats = array();
        
        try {
            if (!$this->db->table_exists('invoices')) {
                throw new Exception('Invoices table does not exist');
            }
            
            // Total invoices
            $stats['total_invoices'] = $this->db->count_all('invoices');
            
            // Total amount
            if ($this->db->field_exists('total_amount', 'invoices')) {
                $this->db->select_sum('total_amount');
                $query = $this->db->get('invoices');
                $stats['total_amount'] = $query->row()->total_amount ?: 0;
            } else {
                $stats['total_amount'] = 0;
            }
            
            // Total due
            if ($this->db->field_exists('balance_due', 'invoices') && $this->db->field_exists('status', 'invoices')) {
                $this->db->select_sum('balance_due');
                $this->db->where('status !=', 'paid');
                $query = $this->db->get('invoices');
                $stats['total_due'] = $query->row()->balance_due ?: 0;
            } else {
                $stats['total_due'] = 0;
            }
            
            // Count by status
            if ($this->db->field_exists('status', 'invoices')) {
                $this->db->select('status, COUNT(*) as count');
                $this->db->group_by('status');
                $query = $this->db->get('invoices');
                $stats['status_counts'] = $query->result();
            } else {
                $stats['status_counts'] = array();
            }
            
        } catch (Exception $e) {
            log_message('error', 'Invoice statistics error: ' . $e->getMessage());
            // Set default values
            $stats['total_invoices'] = 0;
            $stats['total_amount'] = 0;
            $stats['total_due'] = 0;
            $stats['status_counts'] = array();
        }
        
        return $stats;
    }

    // Get all customers - UPDATED with error handling
    public function get_customers() {
        try {
            if (!$this->db->table_exists('customers')) {
                return array();
            }
            
            $this->db->where('is_active', 1);
            $this->db->order_by('customer_name', 'ASC');
            $query = $this->db->get('customers');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get customers error: ' . $e->getMessage());
            return array();
        }
    }

    // Get all products - UPDATED with error handling
    public function get_products() {
        try {
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->where('is_active', 1);
            $this->db->order_by('product_name', 'ASC');
            $query = $this->db->get('products');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get products error: ' . $e->getMessage());
            return array();
        }
    }

    // Get tax rates - UPDATED with error handling
    public function get_tax_rates() {
        try {
            if (!$this->db->table_exists('tax_rates')) {
                return array();
            }
            
            $this->db->where('is_active', 1);
            $query = $this->db->get('tax_rates');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get tax rates error: ' . $e->getMessage());
            return array();
        }
    }

    // Create new invoice - UPDATED with error handling
    public function create_invoice($data) {
        try {
            if (!$this->db->table_exists('invoices')) {
                throw new Exception('Invoices table does not exist');
            }
            
            $this->db->insert('invoices', $data);
            return $this->db->insert_id();
            
        } catch (Exception $e) {
            log_message('error', 'Create invoice error: ' . $e->getMessage());
            return false;
        }
    }

    // Add invoice item - UPDATED with error handling
    public function add_invoice_item($data) {
        try {
            if (!$this->db->table_exists('invoice_items')) {
                throw new Exception('Invoice items table does not exist');
            }
            
            return $this->db->insert('invoice_items', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Add invoice item error: ' . $e->getMessage());
            return false;
        }
    }

    // Update invoice totals - UPDATED with error handling
    public function update_invoice_totals($id, $data) {
        try {
            if (!$this->db->table_exists('invoices')) {
                throw new Exception('Invoices table does not exist');
            }
            
            $this->db->where('id', $id);
            return $this->db->update('invoices', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Update invoice totals error: ' . $e->getMessage());
            return false;
        }
    }

    // Update invoice - UPDATED with error handling
    public function update_invoice($id, $data) {
        try {
            if (!$this->db->table_exists('invoices')) {
                throw new Exception('Invoices table does not exist');
            }
            
            $this->db->where('id', $id);
            return $this->db->update('invoices', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Update invoice error: ' . $e->getMessage());
            return false;
        }
    }

    // Delete invoice items - UPDATED with error handling
    public function delete_invoice_items($invoice_id) {
        try {
            if (!$this->db->table_exists('invoice_items')) {
                throw new Exception('Invoice items table does not exist');
            }
            
            $this->db->where('invoice_id', $invoice_id);
            return $this->db->delete('invoice_items');
            
        } catch (Exception $e) {
            log_message('error', 'Delete invoice items error: ' . $e->getMessage());
            return false;
        }
    }

    // Add customer - UPDATED with error handling
    public function add_customer($data) {
        try {
            if (!$this->db->table_exists('customers')) {
                throw new Exception('Customers table does not exist');
            }
            
            return $this->db->insert('customers', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Add customer error: ' . $e->getMessage());
            return false;
        }
    }

    // Update invoice status - UPDATED with error handling
    public function update_invoice_status($id, $status) {
        try {
            if (!$this->db->table_exists('invoices')) {
                throw new Exception('Invoices table does not exist');
            }
            
            $this->db->where('id', $id);
            return $this->db->update('invoices', array('status' => $status));
            
        } catch (Exception $e) {
            log_message('error', 'Update invoice status error: ' . $e->getMessage());
            return false;
        }
    }

    // Record payment - UPDATED with error handling
    public function record_payment($data) {
        try {
            if (!$this->db->table_exists('payments') || !$this->db->table_exists('invoices')) {
                throw new Exception('Required tables do not exist');
            }
            
            $this->db->trans_start();
            
            // Insert payment record
            $this->db->insert('payments', $data);
            
            // Update invoice balance
            if ($this->db->field_exists('balance_due', 'invoices')) {
                $this->db->set('balance_due', 'balance_due - ' . $data['payment_amount'], FALSE);
                $this->db->where('id', $data['invoice_id']);
                $this->db->update('invoices');
            }
            
            // Update invoice status if fully paid
            if ($this->db->field_exists('balance_due', 'invoices') && $this->db->field_exists('status', 'invoices')) {
                $this->db->where('id', $data['invoice_id']);
                $this->db->where('balance_due <=', 0);
                $this->db->update('invoices', array('status' => 'paid'));
            }
            
            $this->db->trans_complete();
            
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            log_message('error', 'Record payment error: ' . $e->getMessage());
            $this->db->trans_rollback();
            return false;
        }
    }

    // Delete invoice - UPDATED with error handling
    public function delete_invoice($id) {
        try {
            if (!$this->db->table_exists('invoices') || !$this->db->table_exists('invoice_items') || !$this->db->table_exists('payments')) {
                throw new Exception('Required tables do not exist');
            }
            
            $this->db->trans_start();
            
            // Delete invoice items
            if ($this->db->table_exists('invoice_items')) {
                $this->db->where('invoice_id', $id);
                $this->db->delete('invoice_items');
            }
            
            // Delete payments
            if ($this->db->table_exists('payments')) {
                $this->db->where('invoice_id', $id);
                $this->db->delete('payments');
            }
            
            // Delete invoice
            $this->db->where('id', $id);
            $this->db->delete('invoices');
            
            $this->db->trans_complete();
            
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            log_message('error', 'Delete invoice error: ' . $e->getMessage());
            $this->db->trans_rollback();
            return false;
        }
    }

    // Get customer by ID - UPDATED with error handling
    public function get_customer_by_id($id) {
        try {
            if (!$this->db->table_exists('customers')) {
                return null;
            }
            
            $this->db->where('id', $id);
            $query = $this->db->get('customers');
            return $query->row();
            
        } catch (Exception $e) {
            log_message('error', 'Get customer by ID error: ' . $e->getMessage());
            return null;
        }
    }

    // Get product by ID - UPDATED with error handling
    public function get_product_by_id($id) {
        try {
            if (!$this->db->table_exists('products')) {
                return null;
            }
            
            $this->db->where('id', $id);
            $query = $this->db->get('products');
            return $query->row();
            
        } catch (Exception $e) {
            log_message('error', 'Get product by ID error: ' . $e->getMessage());
            return null;
        }
    }

    // Get payments for invoice - UPDATED with error handling
    public function get_payments($invoice_id) {
        try {
            if (!$this->db->table_exists('payments')) {
                return array();
            }
            
            $this->db->where('invoice_id', $invoice_id);
            $this->db->order_by('payment_date', 'DESC');
            $query = $this->db->get('payments');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Get payments error: ' . $e->getMessage());
            return array();
        }
    }

    // Utility function to check database setup
    public function check_database_setup() {
        $tables = array('invoices', 'customers', 'invoice_items', 'payments', 'tax_rates');
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