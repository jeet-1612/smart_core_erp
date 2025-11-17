<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get financial summary for dashboard
    public function get_financial_summary() {
        $data = array();
        
        // Total Revenue
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'paid');
        $this->db->where('MONTH(invoice_date)', date('m'));
        $this->db->where('YEAR(invoice_date)', date('Y'));
        $query = $this->db->get('invoices');
        $data['total_revenue'] = $query->row()->total_amount ?: 0;
        
        // Total Expenses
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'received');
        $this->db->where('MONTH(po_date)', date('m'));
        $this->db->where('YEAR(po_date)', date('Y'));
        $query = $this->db->get('purchase_orders');
        $data['total_expenses'] = $query->row()->total_amount ?: 0;
        
        // Accounts Receivable
        $this->db->select_sum('balance_due');
        $this->db->where('status !=', 'paid');
        $query = $this->db->get('invoices');
        $data['accounts_receivable'] = $query->row()->balance_due ?: 0;
        
        // Accounts Payable
        $this->db->select_sum('total_amount');
        $this->db->where('status', 'confirmed');
        $query = $this->db->get('purchase_orders');
        $data['accounts_payable'] = $query->row()->total_amount ?: 0;
        
        // Net Profit
        $data['net_profit'] = $data['total_revenue'] - $data['total_expenses'];
        
        return $data;
    }

    // Get recent transactions
    public function get_recent_transactions() {
        $this->db->select('*');
        $this->db->from('journal_entries');
        $this->db->where('status', 'posted');
        $this->db->order_by('entry_date', 'DESC');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    // Get chart of accounts
    public function get_chart_of_accounts() {
        $this->db->select('*');
        $this->db->from('chart_of_accounts');
        $this->db->where('is_active', 1);
        $this->db->order_by('account_code', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get account by ID
    public function get_account_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('chart_of_accounts');
        return $query->row();
    }

    // Add new account
    public function add_account($data) {
        return $this->db->insert('chart_of_accounts', $data);
    }

    // Get ledger entries for an account
    public function get_ledger_entries($account_id, $start_date = null, $end_date = null) {
        $this->db->select('je.entry_date, je.entry_number, je.description, 
                          jei.debit_amount, jei.credit_amount, jei.description as item_description');
        $this->db->from('journal_entry_items jei');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('jei.account_id', $account_id);
        $this->db->where('je.status', 'posted');
        
        if ($start_date) {
            $this->db->where('je.entry_date >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('je.entry_date <=', $end_date);
        }
        
        $this->db->order_by('je.entry_date', 'ASC');
        $this->db->order_by('je.id', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    // Get trial balance
    public function get_trial_balance($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $this->db->select('coa.id, coa.account_code, coa.account_name, coa.account_type,
                          SUM(jei.debit_amount) as total_debit,
                          SUM(jei.credit_amount) as total_credit');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id', 'left');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id', 'left');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date <=', $date);
        $this->db->group_by('coa.id, coa.account_code, coa.account_name, coa.account_type');
        $this->db->order_by('coa.account_code', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    // Get profit and loss statement
    public function get_profit_loss_statement($start_date, $end_date) {
        $data = array();
        
        // Revenue accounts (income type)
        $this->db->select('coa.account_name, SUM(jei.credit_amount - jei.debit_amount) as amount');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'income');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date >=', $start_date);
        $this->db->where('je.entry_date <=', $end_date);
        $this->db->group_by('coa.id, coa.account_name');
        $query = $this->db->get();
        $data['revenues'] = $query->result();
        
        // Expense accounts (expense type)
        $this->db->select('coa.account_name, SUM(jei.debit_amount - jei.credit_amount) as amount');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'expense');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date >=', $start_date);
        $this->db->where('je.entry_date <=', $end_date);
        $this->db->group_by('coa.id, coa.account_name');
        $query = $this->db->get();
        $data['expenses'] = $query->result();
        
        // Calculate totals
        $data['total_revenue'] = 0;
        foreach ($data['revenues'] as $revenue) {
            $data['total_revenue'] += $revenue->amount;
        }
        
        $data['total_expenses'] = 0;
        foreach ($data['expenses'] as $expense) {
            $data['total_expenses'] += $expense->amount;
        }
        
        $data['net_income'] = $data['total_revenue'] - $data['total_expenses'];
        
        return $data;
    }

    // Get balance sheet
    public function get_balance_sheet($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $data = array();
        
        // Assets
        $this->db->select('coa.account_name, SUM(jei.debit_amount - jei.credit_amount) as balance');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'asset');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date <=', $date);
        $this->db->group_by('coa.id, coa.account_name');
        $query = $this->db->get();
        $data['assets'] = $query->result();
        
        // Liabilities
        $this->db->select('coa.account_name, SUM(jei.credit_amount - jei.debit_amount) as balance');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'liability');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date <=', $date);
        $this->db->group_by('coa.id, coa.account_name');
        $query = $this->db->get();
        $data['liabilities'] = $query->result();
        
        // Equity
        $this->db->select('coa.account_name, SUM(jei.credit_amount - jei.debit_amount) as balance');
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'equity');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date <=', $date);
        $this->db->group_by('coa.id, coa.account_name');
        $query = $this->db->get();
        $data['equity'] = $query->result();
        
        // Calculate totals
        $data['total_assets'] = 0;
        foreach ($data['assets'] as $asset) {
            $data['total_assets'] += $asset->balance;
        }
        
        $data['total_liabilities'] = 0;
        foreach ($data['liabilities'] as $liability) {
            $data['total_liabilities'] += $liability->balance;
        }
        
        $data['total_equity'] = 0;
        foreach ($data['equity'] as $equity) {
            $data['total_equity'] += $equity->balance;
        }
        
        $data['liabilities_equity'] = $data['total_liabilities'] + $data['total_equity'];
        
        return $data;
    }

    // Get cash flow statement
    public function get_cash_flow_statement($start_date, $end_date) {
        $data = array();
        
        // Operating Activities
        $this->db->select("'Operating' as category, coa.account_name, SUM(jei.debit_amount - jei.credit_amount) as amount");
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where_in('coa.account_type', array('income', 'expense'));
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date >=', $start_date);
        $this->db->where('je.entry_date <=', $end_date);
        $this->db->group_by('coa.account_name');
        $query = $this->db->get();
        $data['operating'] = $query->result();
        
        // Investing Activities
        $this->db->select("'Investing' as category, coa.account_name, SUM(jei.debit_amount - jei.credit_amount) as amount");
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where('coa.account_type', 'asset');
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date >=', $start_date);
        $this->db->where('je.entry_date <=', $end_date);
        $this->db->group_by('coa.account_name');
        $query = $this->db->get();
        $data['investing'] = $query->result();
        
        // Financing Activities
        $this->db->select("'Financing' as category, coa.account_name, SUM(jei.credit_amount - jei.debit_amount) as amount");
        $this->db->from('chart_of_accounts coa');
        $this->db->join('journal_entry_items jei', 'coa.id = jei.account_id');
        $this->db->join('journal_entries je', 'jei.journal_entry_id = je.id');
        $this->db->where_in('coa.account_type', array('liability', 'equity'));
        $this->db->where('je.status', 'posted');
        $this->db->where('je.entry_date >=', $start_date);
        $this->db->where('je.entry_date <=', $end_date);
        $this->db->group_by('coa.account_name');
        $query = $this->db->get();
        $data['financing'] = $query->result();
        
        return $data;
    }

    // Journal Entries functions
    public function get_journal_entries() {
        $this->db->select('*');
        $this->db->from('journal_entries');
        $this->db->order_by('entry_date', 'DESC');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_journal_entry_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('journal_entries');
        return $query->row();
    }

    public function get_journal_entry_items($entry_id) {
        $this->db->select('jei.*, coa.account_code, coa.account_name');
        $this->db->from('journal_entry_items jei');
        $this->db->join('chart_of_accounts coa', 'jei.account_id = coa.id');
        $this->db->where('jei.journal_entry_id', $entry_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function create_journal_entry($data) {
        $this->db->insert('journal_entries', $data);
        return $this->db->insert_id();
    }

    public function add_journal_entry_item($data) {
        return $this->db->insert('journal_entry_items', $data);
    }

    public function update_journal_entry_totals($id, $total_debit, $total_credit) {
        $data = array(
            'total_debit' => $total_debit,
            'total_credit' => $total_credit
        );
        $this->db->where('id', $id);
        return $this->db->update('journal_entries', $data);
    }

    public function post_journal_entry($id) {
        $this->db->where('id', $id);
        return $this->db->update('journal_entries', array('status' => 'posted'));
    }

    public function delete_journal_entry($id) {
        // First delete items
        $this->db->where('journal_entry_id', $id);
        $this->db->delete('journal_entry_items');
        
        // Then delete journal entry
        $this->db->where('id', $id);
        return $this->db->delete('journal_entries');
    }
}
?>