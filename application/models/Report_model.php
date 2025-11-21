<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get sales summary for dashboard
    public function get_sales_summary() {
        $data = array();
        
        try {
            // Total Sales (Current Month)
            if ($this->db->table_exists('invoices')) {
                $this->db->select_sum('total_amount');
                $this->db->where('status', 'paid');
                $this->db->where('MONTH(invoice_date)', date('m'));
                $this->db->where('YEAR(invoice_date)', date('Y'));
                $query = $this->db->get('invoices');
                $data['total_sales'] = $query->row()->total_amount ?: 0;
                
                // Sales Growth (vs Previous Month)
                $this->db->select_sum('total_amount');
                $this->db->where('status', 'paid');
                $this->db->where('MONTH(invoice_date)', date('m', strtotime('-1 month')));
                $this->db->where('YEAR(invoice_date)', date('Y'));
                $query = $this->db->get('invoices');
                $prev_month_sales = $query->row()->total_amount ?: 0;
                
                $data['sales_growth'] = $prev_month_sales > 0 ? 
                    (($data['total_sales'] - $prev_month_sales) / $prev_month_sales) * 100 : 0;
                
                // Total Invoices
                $this->db->where('status', 'paid');
                $this->db->where('MONTH(invoice_date)', date('m'));
                $this->db->where('YEAR(invoice_date)', date('Y'));
                $data['total_invoices'] = $this->db->count_all_results('invoices');
                
                // Average Invoice Value
                $data['avg_invoice_value'] = $data['total_invoices'] > 0 ? 
                    $data['total_sales'] / $data['total_invoices'] : 0;
            } else {
                $data['total_sales'] = 0;
                $data['sales_growth'] = 0;
                $data['total_invoices'] = 0;
                $data['avg_invoice_value'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Sales summary error: ' . $e->getMessage());
            $data['total_sales'] = 0;
            $data['sales_growth'] = 0;
            $data['total_invoices'] = 0;
            $data['avg_invoice_value'] = 0;
        }
        
        return $data;
    }

    // Get inventory summary for dashboard - CORRECTED
    public function get_inventory_summary() {
        $data = array();
        
        try {
            if ($this->db->table_exists('products')) {
                // Total Products
                $this->db->where('is_active', 1);
                $data['total_products'] = $this->db->count_all_results('products');
                
                // Total Stock Value - CORRECTED QUERY
                if ($this->db->field_exists('current_stock', 'products') && $this->db->field_exists('cost_price', 'products')) {
                    // Method 1: Using manual query
                    $sql = "SELECT SUM(current_stock * cost_price) as total_value FROM products WHERE is_active = 1";
                    $query = $this->db->query($sql);
                    $data['total_stock_value'] = $query->row()->total_value ?: 0;
                    
                    // Alternative Method 2: Using CodeIgniter query builder with FALSE parameter
                    // $this->db->select('SUM(current_stock * cost_price) as total_value', FALSE);
                    // $this->db->where('is_active', 1);
                    // $query = $this->db->get('products');
                    // $data['total_stock_value'] = $query->row()->total_value ?: 0;
                } else {
                    $data['total_stock_value'] = 0;
                }
                
                // Low Stock Items
                if ($this->db->field_exists('reorder_level', 'products')) {
                    $this->db->where('is_active', 1);
                    $this->db->where('current_stock <= reorder_level');
                    $this->db->where('current_stock >', 0);
                    $data['low_stock_count'] = $this->db->count_all_results('products');
                } else {
                    $data['low_stock_count'] = 0;
                }
                
                // Out of Stock Items
                if ($this->db->field_exists('current_stock', 'products')) {
                    $this->db->where('is_active', 1);
                    $this->db->where('current_stock', 0);
                    $data['out_of_stock_count'] = $this->db->count_all_results('products');
                } else {
                    $data['out_of_stock_count'] = 0;
                }
            } else {
                $data['total_products'] = 0;
                $data['total_stock_value'] = 0;
                $data['low_stock_count'] = 0;
                $data['out_of_stock_count'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Inventory summary error: ' . $e->getMessage());
            $data['total_products'] = 0;
            $data['total_stock_value'] = 0;
            $data['low_stock_count'] = 0;
            $data['out_of_stock_count'] = 0;
        }
        
        return $data;
    }

    // Get financial summary for dashboard
    public function get_financial_summary() {
        $data = array();
        
        try {
            if ($this->db->table_exists('invoices')) {
                // Accounts Receivable
                $this->db->select_sum('balance_due');
                $this->db->where('status !=', 'paid');
                $query = $this->db->get('invoices');
                $data['accounts_receivable'] = $query->row()->balance_due ?: 0;
                
                // Current Month Revenue
                $this->db->select_sum('total_amount');
                $this->db->where('status', 'paid');
                $this->db->where('MONTH(invoice_date)', date('m'));
                $this->db->where('YEAR(invoice_date)', date('Y'));
                $query = $this->db->get('invoices');
                $data['current_month_revenue'] = $query->row()->total_amount ?: 0;
                
                // Previous Month Revenue
                $this->db->select_sum('total_amount');
                $this->db->where('status', 'paid');
                $this->db->where('MONTH(invoice_date)', date('m', strtotime('-1 month')));
                $this->db->where('YEAR(invoice_date)', date('Y'));
                $query = $this->db->get('invoices');
                $data['prev_month_revenue'] = $query->row()->total_amount ?: 0;
                
                // Revenue Growth
                $data['revenue_growth'] = $data['prev_month_revenue'] > 0 ? 
                    (($data['current_month_revenue'] - $data['prev_month_revenue']) / $data['prev_month_revenue']) * 100 : 0;
            } else {
                $data['accounts_receivable'] = 0;
                $data['current_month_revenue'] = 0;
                $data['prev_month_revenue'] = 0;
                $data['revenue_growth'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Financial summary error: ' . $e->getMessage());
            $data['accounts_recreceivable'] = 0;
            $data['current_month_revenue'] = 0;
            $data['prev_month_revenue'] = 0;
            $data['revenue_growth'] = 0;
        }
        
        return $data;
    }

    // Sales Reports
    public function get_daily_sales_report($start_date, $end_date) {
        try {
            if (!$this->db->table_exists('invoices')) {
                return array();
            }
            
            $this->db->select('DATE(invoice_date) as date, COUNT(*) as invoice_count, SUM(total_amount) as total_sales, SUM(tax_amount) as total_tax');
            $this->db->where('invoice_date >=', $start_date);
            $this->db->where('invoice_date <=', $end_date);
            $this->db->where('status', 'paid');
            $this->db->group_by('DATE(invoice_date)');
            $this->db->order_by('date', 'ASC');
            
            $query = $this->db->get('invoices');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Daily sales report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_customer_sales_report($start_date, $end_date, $customer_id = null) {
        try {
            if (!$this->db->table_exists('invoices') || !$this->db->table_exists('customers')) {
                return array();
            }
            
            $this->db->select('c.customer_name, COUNT(i.id) as invoice_count, SUM(i.total_amount) as total_sales, SUM(i.tax_amount) as total_tax');
            $this->db->from('invoices i');
            $this->db->join('customers c', 'i.customer_id = c.id', 'left');
            $this->db->where('i.invoice_date >=', $start_date);
            $this->db->where('i.invoice_date <=', $end_date);
            $this->db->where('i.status', 'paid');
            
            if ($customer_id) {
                $this->db->where('i.customer_id', $customer_id);
            }
            
            $this->db->group_by('i.customer_id, c.customer_name');
            $this->db->order_by('total_sales', 'DESC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Customer sales report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_product_sales_report($start_date, $end_date) {
        try {
            if (!$this->db->table_exists('invoice_items') || !$this->db->table_exists('invoices') || !$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('p.product_name, p.product_code, SUM(ii.quantity) as total_quantity, SUM(ii.line_total) as total_sales');
            $this->db->from('invoice_items ii');
            $this->db->join('invoices i', 'ii.invoice_id = i.id');
            $this->db->join('products p', 'ii.product_id = p.id', 'left');
            $this->db->where('i.invoice_date >=', $start_date);
            $this->db->where('i.invoice_date <=', $end_date);
            $this->db->where('i.status', 'paid');
            $this->db->group_by('ii.product_id, p.product_name, p.product_code');
            $this->db->order_by('total_sales', 'DESC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Product sales report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_tax_sales_report($start_date, $end_date) {
        try {
            if (!$this->db->table_exists('invoice_items') || !$this->db->table_exists('invoices')) {
                return array();
            }
            
            $this->db->select('ii.tax_rate, SUM(ii.quantity * ii.unit_price) as taxable_amount, SUM((ii.quantity * ii.unit_price) * ii.tax_rate / 100) as tax_amount');
            $this->db->from('invoice_items ii');
            $this->db->join('invoices i', 'ii.invoice_id = i.id');
            $this->db->where('i.invoice_date >=', $start_date);
            $this->db->where('i.invoice_date <=', $end_date);
            $this->db->where('i.status', 'paid');
            $this->db->where('ii.tax_rate >', 0);
            $this->db->group_by('ii.tax_rate');
            $this->db->order_by('ii.tax_rate', 'ASC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Tax sales report error: ' . $e->getMessage());
            return array();
        }
    }

    // Inventory Reports
    public function get_stock_summary_report($category_id = null) {
        try {
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('p.*');
            
            // Add category name if categories table exists and join is possible
            if ($this->db->table_exists('categories') && 
                $this->db->field_exists('category_id', 'products') &&
                $this->db->field_exists('category_name', 'categories')) {
                $this->db->select('c.category_name');
                $this->db->join('categories c', 'p.category_id = c.id', 'left');
            } else {
                // If categories table doesn't exist or missing columns, use default value
                $this->db->select("'General' as category_name");
            }
            
            $this->db->from('products p');
            $this->db->where('p.is_active', 1);
            
            if ($category_id && $this->db->field_exists('category_id', 'products')) {
                $this->db->where('p.category_id', $category_id);
            }
            
            $this->db->order_by('p.product_name', 'ASC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Stock summary report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_low_stock_report() {
        try {
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('p.*');
            
            // Add category name if possible
            if ($this->db->table_exists('categories') && 
                $this->db->field_exists('category_id', 'products') &&
                $this->db->field_exists('category_name', 'categories')) {
                $this->db->select('c.category_name');
                $this->db->join('categories c', 'p.category_id = c.id', 'left');
            } else {
                $this->db->select("'General' as category_name");
            }
            
            $this->db->from('products p');
            $this->db->where('p.is_active', 1);
            
            // Check if reorder_level and current_stock columns exist
            if ($this->db->field_exists('reorder_level', 'products') && 
                $this->db->field_exists('current_stock', 'products')) {
                $this->db->where('p.current_stock <= p.reorder_level');
            }
            
            $this->db->order_by('p.current_stock', 'ASC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Low stock report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_stock_movements_report() {
        try {
            if (!$this->db->table_exists('stock_movements')) {
                return array();
            }
            
            $this->db->select('sm.*');
            
            // Add product information if products table exists
            if ($this->db->table_exists('products') && 
                $this->db->field_exists('product_id', 'stock_movements')) {
                $this->db->select('p.product_name, p.product_code');
                $this->db->join('products p', 'sm.product_id = p.id', 'left');
            } else {
                $this->db->select("'Product' as product_name, '' as product_code");
            }
            
            $this->db->from('stock_movements sm');
            $this->db->order_by('sm.created_at', 'DESC');
            $this->db->limit(100); // Limit to recent 100 movements
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Stock movements report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_stock_valuation_report() {
        try {
            if (!$this->db->table_exists('products')) {
                return array();
            }
            
            $this->db->select('p.*');
            
            // Add category name if possible
            if ($this->db->table_exists('categories') && 
                $this->db->field_exists('category_id', 'products') &&
                $this->db->field_exists('category_name', 'categories')) {
                $this->db->select('c.category_name');
                $this->db->join('categories c', 'p.category_id = c.id', 'left');
            } else {
                $this->db->select("'General' as category_name");
            }
            
            // Calculate stock value
            if ($this->db->field_exists('current_stock', 'products') && 
                $this->db->field_exists('cost_price', 'products')) {
                $this->db->select('(p.current_stock * p.cost_price) as stock_value', FALSE);
            } else {
                $this->db->select('0 as stock_value');
            }
            
            $this->db->from('products p');
            $this->db->where('p.is_active', 1);
            
            // Order by stock value if available, otherwise by product name
            if ($this->db->field_exists('current_stock', 'products') && 
                $this->db->field_exists('cost_price', 'products')) {
                $this->db->order_by('stock_value', 'DESC');
            } else {
                $this->db->order_by('p.product_name', 'ASC');
            }
            
            $query = $this->db->get();
            $products = $query->result();
            
            // Manual calculation as fallback
            foreach ($products as $product) {
                if (!isset($product->stock_value) || $product->stock_value === null) {
                    $product->stock_value = isset($product->current_stock) && isset($product->cost_price) ? 
                        $product->current_stock * $product->cost_price : 0;
                }
            }
            
            return $products;
            
        } catch (Exception $e) {
            log_message('error', 'Stock valuation report error: ' . $e->getMessage());
            return array();
        }
    }

    // Financial Reports
    public function get_profit_loss_report($start_date, $end_date) {
        $data = array();
        
        try {
            if ($this->db->table_exists('invoices')) {
                // Revenue
                $this->db->select_sum('total_amount', 'revenue');
                $this->db->where('invoice_date >=', $start_date);
                $this->db->where('invoice_date <=', $end_date);
                $this->db->where('status', 'paid');
                $query = $this->db->get('invoices');
                $data['revenue'] = $query->row()->revenue ?: 0;
                
                // Cost of Goods Sold (Estimate - 60% of revenue)
                $data['cogs'] = $data['revenue'] * 0.6;
                
                // Gross Profit
                $data['gross_profit'] = $data['revenue'] - $data['cogs'];
                
                // Operating Expenses (Estimate - 30% of revenue)
                $data['operating_expenses'] = $data['revenue'] * 0.3;
                
                // Net Profit
                $data['net_profit'] = $data['gross_profit'] - $data['operating_expenses'];
                
                // Gross Profit Margin
                $data['gross_profit_margin'] = $data['revenue'] > 0 ? ($data['gross_profit'] / $data['revenue']) * 100 : 0;
                
                // Net Profit Margin
                $data['net_profit_margin'] = $data['revenue'] > 0 ? ($data['net_profit'] / $data['revenue']) * 100 : 0;
            } else {
                $data['revenue'] = 0;
                $data['cogs'] = 0;
                $data['gross_profit'] = 0;
                $data['operating_expenses'] = 0;
                $data['net_profit'] = 0;
                $data['gross_profit_margin'] = 0;
                $data['net_profit_margin'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Profit loss report error: ' . $e->getMessage());
            $data['revenue'] = 0;
            $data['cogs'] = 0;
            $data['gross_profit'] = 0;
            $data['operating_expenses'] = 0;
            $data['net_profit'] = 0;
            $data['gross_profit_margin'] = 0;
            $data['net_profit_margin'] = 0;
        }
        
        return $data;
    }

    public function get_balance_sheet_report($as_of_date) {
        $data = array();
        
        try {
            // Assets
            $data['assets'] = array();
            
            // Current Assets
            if ($this->db->table_exists('invoices')) {
                // Accounts Receivable
                $this->db->select_sum('balance_due', 'accounts_receivable');
                $this->db->where('status !=', 'paid');
                $this->db->where('invoice_date <=', $as_of_date);
                $query = $this->db->get('invoices');
                $data['assets']['accounts_receivable'] = $query->row()->accounts_receivable ?: 0;
            }
            
            if ($this->db->table_exists('products')) {
                // Inventory - CORRECTED: Use manual calculation
                $this->db->select('current_stock, cost_price');
                $this->db->where('is_active', 1);
                $query = $this->db->get('products');
                $inventory_value = 0;
                foreach ($query->result() as $product) {
                    $inventory_value += $product->current_stock * $product->cost_price;
                }
                $data['assets']['inventory'] = $inventory_value;
            }
            
            // Cash (Estimate)
            $data['assets']['cash'] = 50000; // Example fixed amount
            
            // Total Assets
            $data['total_assets'] = array_sum($data['assets']);
            
            // Liabilities & Equity
            $data['liabilities'] = array(
                'accounts_payable' => 25000, // Example
                'loans_payable' => 100000    // Example
            );
            
            $data['equity'] = array(
                'capital' => 200000, // Example
                'retained_earnings' => $data['total_assets'] - array_sum($data['liabilities']) - 200000
            );
            
            $data['total_liabilities_equity'] = array_sum($data['liabilities']) + array_sum($data['equity']);
            
        } catch (Exception $e) {
            log_message('error', 'Balance sheet report error: ' . $e->getMessage());
            $data['assets'] = array();
            $data['total_assets'] = 0;
            $data['liabilities'] = array();
            $data['equity'] = array();
            $data['total_liabilities_equity'] = 0;
        }
        
        return $data;
    }

    public function get_cash_flow_report($start_date, $end_date) {
        $data = array();
        
        try {
            if ($this->db->table_exists('invoices')) {
                // Cash from Operating Activities
                $this->db->select_sum('total_amount', 'cash_from_sales');
                $this->db->where('invoice_date >=', $start_date);
                $this->db->where('invoice_date <=', $end_date);
                $this->db->where('status', 'paid');
                $query = $this->db->get('invoices');
                $data['operating_activities'] = $query->row()->cash_from_sales ?: 0;
                
                // Cash from Investing Activities (Example)
                $data['investing_activities'] = -15000; // Equipment purchase
                
                // Cash from Financing Activities (Example)
                $data['financing_activities'] = 50000; // Loan received
                
                // Net Cash Flow
                $data['net_cash_flow'] = $data['operating_activities'] + $data['investing_activities'] + $data['financing_activities'];
                
                // Beginning Cash (Example)
                $data['beginning_cash'] = 50000;
                
                // Ending Cash
                $data['ending_cash'] = $data['beginning_cash'] + $data['net_cash_flow'];
            } else {
                $data['operating_activities'] = 0;
                $data['investing_activities'] = 0;
                $data['financing_activities'] = 0;
                $data['net_cash_flow'] = 0;
                $data['beginning_cash'] = 0;
                $data['ending_cash'] = 0;
            }
            
        } catch (Exception $e) {
            log_message('error', 'Cash flow report error: ' . $e->getMessage());
            $data['operating_activities'] = 0;
            $data['investing_activities'] = 0;
            $data['financing_activities'] = 0;
            $data['net_cash_flow'] = 0;
            $data['beginning_cash'] = 0;
            $data['ending_cash'] = 0;
        }
        
        return $data;
    }

    public function get_accounts_receivable_report() {
        try {
            if (!$this->db->table_exists('invoices') || !$this->db->table_exists('customers')) {
                return array();
            }
            
            $this->db->select('i.*, c.customer_name, c.email, c.phone');
            $this->db->from('invoices i');
            $this->db->join('customers c', 'i.customer_id = c.id', 'left');
            $this->db->where('i.status !=', 'paid');
            $this->db->where('i.balance_due >', 0);
            $this->db->order_by('i.due_date', 'ASC');
            
            $query = $this->db->get();
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Accounts receivable report error: ' . $e->getMessage());
            return array();
        }
    }

    public function get_accounts_payable_report() {
        // This would typically come from purchase orders or bills
        // For now, return empty array or sample data
        return array();
    }

    // Utility methods
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

    public function get_categories() {
        try {
            if (!$this->db->table_exists('categories')) {
                return array();
            }
            
            // Check if required columns exist
            if (!$this->db->field_exists('category_name', 'categories')) {
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

    // Get chart data for dashboard
    public function get_sales_chart_data($period = 'monthly') {
        $data = array();
        
        try {
            if (!$this->db->table_exists('invoices')) {
                return $data;
            }
            
            if ($period == 'monthly') {
                $this->db->select('MONTH(invoice_date) as month, YEAR(invoice_date) as year, SUM(total_amount) as total_sales');
                $this->db->where('status', 'paid');
                $this->db->where('invoice_date >=', date('Y-01-01'));
                $this->db->group_by('YEAR(invoice_date), MONTH(invoice_date)');
                $this->db->order_by('year, month', 'ASC');
            } else {
                // Weekly
                $this->db->select('WEEK(invoice_date) as week, YEAR(invoice_date) as year, SUM(total_amount) as total_sales');
                $this->db->where('status', 'paid');
                $this->db->where('invoice_date >=', date('Y-m-d', strtotime('-30 days')));
                $this->db->group_by('YEAR(invoice_date), WEEK(invoice_date)');
                $this->db->order_by('year, week', 'ASC');
            }
            
            $query = $this->db->get('invoices');
            return $query->result();
            
        } catch (Exception $e) {
            log_message('error', 'Sales chart data error: ' . $e->getMessage());
            return $data;
        }
    }
}
?>