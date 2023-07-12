<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database(); // Memuat pustaka database CodeIgniter
    }
    
    // Fungsi untuk mendapatkan semua data pengguna
    public function get_users() {
        return $this->db->get('user')->result();
    }
    
    // Fungsi untuk mendapatkan data pengguna berdasarkan ID
    public function get_user($id) {
        return $this->db->get_where('user', array('id' => $id))->row();
    }
    
    // Fungsi untuk menambahkan pengguna baru
    public function add_user($data) {
        return $this->db->insert('user', $data);
    }
    
    // Fungsi untuk memperbarui data pengguna berdasarkan ID
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }
    
    // Fungsi untuk menghapus data pengguna berdasarkan ID
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('user');
    }
}

