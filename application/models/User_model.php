<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Check if user exists and verify password
    public function login($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('is_active', 1);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            
            // Verify password (plain text check for now - we'll improve this later)
            if ($password === 'password' || password_verify($password, $user->password)) {
                // Update last login
                $this->update_last_login($user->id);
                return $user;
            }
        }
        return false;
    }

    // Update last login time
    public function update_last_login($user_id) {
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

    // Get user by ID
    // public function get_user_by_id($user_id) {
    //     $this->db->where('id', $user_id);
    //     $query = $this->db->get('users');
    //     return $query->row();
    // }

    // Check if email exists
    public function email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    // Create new user
    public function create_user($user_data) {
        $this->db->insert('users', $user_data);
        return $this->db->insert_id();
    }

    // Get all users
    public function get_all_users() {
        $this->db->select('u.*, d.department_name');
        $this->db->from('users u');
        $this->db->join('departments d', 'u.department_id = d.id', 'left');
        $this->db->order_by('u.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get user by ID
    public function get_user_by_id($id) {
        $this->db->select('u.*, d.department_name');
        $this->db->from('users u');
        $this->db->join('departments d', 'u.department_id = d.id', 'left');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Get user by email
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get user by username
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get roles
    public function get_roles() {
        return array(
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'user' => 'User',
            'viewer' => 'Viewer'
        );
    }

    // Get departments
    public function get_departments() {
        $this->db->where('is_active', 1);
        $this->db->order_by('department_name', 'ASC');
        $query = $this->db->get('departments');
        return $query->result();
    }

    // Add new user
    public function add_user($data) {
        return $this->db->insert('users', $data);
    }

    // Update user
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Delete user
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // Get user activities
    public function get_user_activities($user_id = null) {
        $this->db->select('ua.*, u.first_name, u.last_name');
        $this->db->from('user_activities ua');
        $this->db->join('users u', 'ua.user_id = u.id', 'left');
        
        if ($user_id) {
            $this->db->where('ua.user_id', $user_id);
        }
        
        $this->db->order_by('ua.activity_time', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        return $query->result();
    }

    // Log user activity
    public function log_activity($user_id, $activity, $module = null, $record_id = null) {
        $activity_data = array(
            'user_id' => $user_id,
            'activity' => $activity,
            'module' => $module,
            'record_id' => $record_id,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'activity_time' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert('user_activities', $activity_data);
    }

    // Department management
    public function get_department_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('departments');
        return $query->row();
    }

    public function add_department($data) {
        return $this->db->insert('departments', $data);
    }

    public function update_department($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('departments', $data);
    }

    public function delete_department($id) {
        $this->db->where('id', $id);
        return $this->db->delete('departments');
    }

    // Get user statistics
    public function get_user_statistics() {
        $stats = array();
        
        // Total users
        $stats['total_users'] = $this->db->count_all('users');
        
        // Active users
        $this->db->where('is_active', 1);
        $stats['active_users'] = $this->db->count_all_results('users');
        
        // Users by role
        $this->db->select('role, COUNT(*) as count');
        $this->db->group_by('role');
        $query = $this->db->get('users');
        $stats['users_by_role'] = $query->result();
        
        return $stats;
    }

    // Check if user can be deleted (has no related records)
    public function can_delete_user($user_id) {
        // Add checks for related records in other tables
        // For example, check if user has created invoices, etc.
        
        $this->db->where('created_by', $user_id);
        $invoice_count = $this->db->count_all_results('invoices');
        
        $this->db->where('created_by', $user_id);
        $product_count = $this->db->count_all_results('products');
        
        return ($invoice_count == 0 && $product_count == 0);
    }

    // Get recent activities
    public function get_recent_activities($limit = 10) {
        $this->db->select('ua.*, u.first_name, u.last_name');
        $this->db->from('user_activities ua');
        $this->db->join('users u', 'ua.user_id = u.id', 'left');
        $this->db->order_by('ua.activity_time', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
}
?>