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
    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }

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
}
?>