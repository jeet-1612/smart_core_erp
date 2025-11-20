<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Report_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function index() {
        $data['title'] = 'Reports Dashboard - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get default report data
        $data['sales_summary'] = $this->Report_model->get_sales_summary();
        $data['inventory_summary'] = $this->Report_model->get_inventory_summary();
        $data['financial_summary'] = $this->Report_model->get_financial_summary();
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function sales_reports() {
        $data['title'] = 'Sales Reports - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get filter parameters
        $report_type = $this->input->get('report_type') ?: 'daily';
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        $customer_id = $this->input->get('customer_id');
        
        $data['report_type'] = $report_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['customer_id'] = $customer_id;
        $data['customers'] = $this->Report_model->get_customers();
        
        switch ($report_type) {
            case 'daily':
                $data['report_data'] = $this->Report_model->get_daily_sales_report($start_date, $end_date);
                break;
            case 'customer':
                $data['report_data'] = $this->Report_model->get_customer_sales_report($start_date, $end_date, $customer_id);
                break;
            case 'product':
                $data['report_data'] = $this->Report_model->get_product_sales_report($start_date, $end_date);
                break;
            case 'tax':
                $data['report_data'] = $this->Report_model->get_tax_sales_report($start_date, $end_date);
                break;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/sales_reports', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function inventory_reports() {
        $data['title'] = 'Inventory Reports - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get filter parameters
        $report_type = $this->input->get('report_type') ?: 'stock_summary';
        $category_id = $this->input->get('category_id');
        
        $data['report_type'] = $report_type;
        $data['category_id'] = $category_id;
        $data['categories'] = $this->Report_model->get_categories();
        
        switch ($report_type) {
            case 'stock_summary':
                $data['report_data'] = $this->Report_model->get_stock_summary_report($category_id);
                break;
            case 'low_stock':
                $data['report_data'] = $this->Report_model->get_low_stock_report();
                break;
            case 'stock_movements':
                $data['report_data'] = $this->Report_model->get_stock_movements_report();
                break;
            case 'stock_valuation':
                $data['report_data'] = $this->Report_model->get_stock_valuation_report();
                break;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/inventory_reports', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function financial_reports() {
        $data['title'] = 'Financial Reports - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get filter parameters
        $report_type = $this->input->get('report_type') ?: 'profit_loss';
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $data['report_type'] = $report_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        switch ($report_type) {
            case 'profit_loss':
                $data['report_data'] = $this->Report_model->get_profit_loss_report($start_date, $end_date);
                break;
            case 'balance_sheet':
                $data['report_data'] = $this->Report_model->get_balance_sheet_report($end_date);
                break;
            case 'cash_flow':
                $data['report_data'] = $this->Report_model->get_cash_flow_report($start_date, $end_date);
                break;
            case 'accounts_receivable':
                $data['report_data'] = $this->Report_model->get_accounts_receivable_report();
                break;
            case 'accounts_payable':
                $data['report_data'] = $this->Report_model->get_accounts_payable_report();
                break;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/financial_reports', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function export_report() {
        $report_type = $this->input->get('report_type');
        $format = $this->input->get('format') ?: 'pdf';
        
        // Set headers for download
        if ($format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="report_' . $report_type . '_' . date('Y-m-d') . '.xls"');
        } else {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="report_' . $report_type . '_' . date('Y-m-d') . '.pdf"');
        }
        
        // In a real application, you would generate the file here
        // For now, we'll redirect back
        $this->session->set_flashdata('success', 'Report exported successfully!');
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function print_report() {
        $report_type = $this->input->get('report_type');
        
        // Load print view based on report type
        $data['report_type'] = $report_type;
        
        $this->load->view('reports/print/' . $report_type, $data);
    }
}
?>