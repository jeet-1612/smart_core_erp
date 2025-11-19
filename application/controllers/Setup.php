<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Inventory_model');
        $this->load->model('Invoice_model');
    }
    
    public function check_inventory_tables() {
        echo "<h2>Database Setup Check</h2>";
        
        $table_status = $this->Inventory_model->check_database_setup();
        
        foreach ($table_status as $table => $exists) {
            echo "<p><strong>$table:</strong> " . ($exists ? '✓ EXISTS' : '✗ MISSING') . "</p>";
        }
        
        // Check products table columns
        if ($table_status['products']) {
            $columns = $this->Inventory_model->get_table_columns('products');
            echo "<h3>Products Table Columns:</h3>";
            echo "<pre>";
            print_r($columns);
            echo "</pre>";
        }
    }

    public function check_invoice_tables() {
        echo "<h2>Invoice Database Setup Check</h2>";
        
        $table_status = $this->Invoice_model->check_database_setup();
        
        foreach ($table_status as $table => $exists) {
            echo "<p><strong>$table:</strong> " . ($exists ? '✓ EXISTS' : '✗ MISSING') . "</p>";
        }
        
        // Check invoices table columns
        if ($table_status['invoices']) {
            $columns = $this->Invoice_model->get_table_columns('invoices');
            echo "<h3>Invoices Table Columns:</h3>";
            echo "<pre>";
            print_r($columns);
            echo "</pre>";
        }
        
        // Check customers table columns
        if ($table_status['customers']) {
            $columns = $this->Invoice_model->get_table_columns('customers');
            echo "<h3>Customers Table Columns:</h3>";
            echo "<pre>";
            print_r($columns);
            echo "</pre>";
        }
    }
}
?>