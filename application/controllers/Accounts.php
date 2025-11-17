<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('Account_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            header('Location: /smart_core_erp/login');
            exit();
        }
    }
    
    public function index() {
        $data['title'] = 'Accounts Dashboard - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get financial summary
        $data['financial_summary'] = $this->Account_model->get_financial_summary();
        $data['recent_transactions'] = $this->Account_model->get_recent_transactions();
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/index', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function chart_of_accounts() {
        $data['title'] = 'Chart of Accounts - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get chart of accounts
        $data['accounts'] = $this->Account_model->get_chart_of_accounts();
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/chart_of_accounts', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function ledger() {
        $data['title'] = 'General Ledger - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get accounts for dropdown
        $data['accounts'] = $this->Account_model->get_chart_of_accounts();
        
        // Get ledger entries if account is selected
        $account_id = $this->input->get('account_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        if ($account_id) {
            $data['ledger_entries'] = $this->Account_model->get_ledger_entries($account_id, $start_date, $end_date);
            $data['selected_account'] = $this->Account_model->get_account_by_id($account_id);
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/ledger', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function trial_balance() {
        $data['title'] = 'Trial Balance - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $date = $this->input->get('date') ?: date('Y-m-d');
        $data['trial_balance'] = $this->Account_model->get_trial_balance($date);
        $data['selected_date'] = $date;
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/trial_balance', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function financial_reports() {
        $data['title'] = 'Financial Reports - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        $report_type = $this->input->get('report_type') ?: 'profit_loss';
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $data['report_type'] = $report_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        switch ($report_type) {
            case 'profit_loss':
                $data['report_data'] = $this->Account_model->get_profit_loss_statement($start_date, $end_date);
                break;
            case 'balance_sheet':
                $data['report_data'] = $this->Account_model->get_balance_sheet($end_date);
                break;
            case 'cash_flow':
                $data['report_data'] = $this->Account_model->get_cash_flow_statement($start_date, $end_date);
                break;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/financial_reports', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function journal_entries() {
        $data['title'] = 'Journal Entries - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get journal entries
        $data['journal_entries'] = $this->Account_model->get_journal_entries();
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/journal_entries', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function create_journal_entry() {
        $data['title'] = 'Create Journal Entry - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get accounts for dropdown
        $data['accounts'] = $this->Account_model->get_chart_of_accounts();
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/create_journal_entry', $data);
        $this->load->view('templates/footer', $data);
    }
    
    public function view_journal_entry($id) {
        $data['title'] = 'Journal Entry Details - Smart-Core ERP';
        $data['user'] = array(
            'name' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        );
        
        // Get journal entry data
        $data['journal_entry'] = $this->Account_model->get_journal_entry_by_id($id);
        $data['journal_entry_items'] = $this->Account_model->get_journal_entry_items($id);
        
        if (!$data['journal_entry']) {
            show_404();
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('accounts/view_journal_entry', $data);
        $this->load->view('templates/footer', $data);
    }
    
    // Process create journal entry
    public function process_journal_entry() {
        $this->form_validation->set_rules('entry_date', 'Entry Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            header('Location: /smart_core_erp/accounts/create_journal_entry');
            exit();
        } else {
            $journal_data = array(
                'entry_number' => $this->generate_entry_number(),
                'entry_date' => $this->input->post('entry_date'),
                'reference' => $this->input->post('reference'),
                'description' => $this->input->post('description'),
                'total_debit' => 0,
                'total_credit' => 0,
                'status' => 'draft',
                'created_by' => $this->session->userdata('user_id')
            );
            
            $entry_id = $this->Account_model->create_journal_entry($journal_data);
            
            if ($entry_id) {
                // Process items
                $items = $this->input->post('items');
                $total_debit = 0;
                $total_credit = 0;
                
                if (!empty($items)) {
                    foreach ($items as $item) {
                        if (!empty($item['account_id']) && (!empty($item['debit_amount']) || !empty($item['credit_amount']))) {
                            $debit_amount = floatval($item['debit_amount']) ?: 0;
                            $credit_amount = floatval($item['credit_amount']) ?: 0;
                            
                            $item_data = array(
                                'journal_entry_id' => $entry_id,
                                'account_id' => $item['account_id'],
                                'debit_amount' => $debit_amount,
                                'credit_amount' => $credit_amount,
                                'description' => $item['item_description']
                            );
                            
                            $this->Account_model->add_journal_entry_item($item_data);
                            
                            $total_debit += $debit_amount;
                            $total_credit += $credit_amount;
                        }
                    }
                }
                
                // Check if debits equal credits
                if (abs($total_debit - $total_credit) > 0.01) {
                    $this->Account_model->delete_journal_entry($entry_id);
                    $this->session->set_flashdata('error', 'Journal entry must balance. Total debits (₹' . number_format($total_debit, 2) . ') must equal total credits (₹' . number_format($total_credit, 2) . ').');
                    header('Location: /smart_core_erp/accounts/create_journal_entry');
                    exit();
                }
                
                // Update journal entry with totals
                $this->Account_model->update_journal_entry_totals($entry_id, $total_debit, $total_credit);
                
                $this->session->set_flashdata('success', 'Journal entry created successfully!');
                header('Location: /smart_core_erp/accounts/journal_entries');
                exit();
            } else {
                $this->session->set_flashdata('error', 'Failed to create journal entry. Please try again.');
                header('Location: /smart_core_erp/accounts/create_journal_entry');
                exit();
            }
        }
    }
    
    // Post journal entry
    public function post_journal_entry($id) {
        $result = $this->Account_model->post_journal_entry($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Journal entry posted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to post journal entry. Please try again.');
        }
        
        header('Location: /smart_core_erp/accounts/journal_entries');
        exit();
    }
    
    // Delete journal entry
    public function delete_journal_entry($id) {
        $result = $this->Account_model->delete_journal_entry($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Journal entry deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete journal entry. Please try again.');
        }
        
        header('Location: /smart_core_erp/accounts/journal_entries');
        exit();
    }
    
    // Add new account to chart of accounts
    public function add_account() {
        $this->form_validation->set_rules('account_code', 'Account Code', 'required');
        $this->form_validation->set_rules('account_name', 'Account Name', 'required');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $account_data = array(
                'account_code' => $this->input->post('account_code'),
                'account_name' => $this->input->post('account_name'),
                'account_type' => $this->input->post('account_type'),
                'parent_account_id' => $this->input->post('parent_account_id') ?: NULL,
                'description' => $this->input->post('description')
            );
            
            $result = $this->Account_model->add_account($account_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Account added successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to add account. Please try again.');
            }
        }
        
        header('Location: /smart_core_erp/accounts/chart_of_accounts');
        exit();
    }
    
    // Generate unique journal entry number
    private function generate_entry_number() {
        $prefix = 'JE';
        $timestamp = date('Ymd');
        $random = mt_rand(100, 999);
        return $prefix . $timestamp . $random;
    }
}
?>