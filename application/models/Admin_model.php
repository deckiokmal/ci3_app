<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mengambil semua data role
    public function get_all_user_role() 
    {
        $query = $this->db->get('user_role');
        return $query->result();
    }

    // Fungsi untuk mengambil data role berdasarkan ID
    public function get_role_by_id($id) 
    {
        $query = $this->db->get_where('user_role', array('id' => $id));
        return $query->row();
    }

    // Fungsi untuk menambahkan data role baru
    public function add_role($data) 
    {
        $this->db->insert('user_role', $data);
        return $this->db->insert_id();
    }

    // Fungsi untuk mengupdate data role berdasarkan ID
    public function update_role($id, $data) 
    {
        $this->db->where('id', $id);
        $this->db->update('user_role', $data);
    }

    // Fungsi untuk menghapus data role berdasarkan ID
    public function delete_role($id) 
    {
        $this->db->where('id', $id);
        $this->db->delete('user_role');
    }

    public function user_role_join_subrole ()
    {
        // Fungsi join tabel user_sub_role dengan tabel user_role
        $query = "SELECT `user_sub_role`.*, `user_role`.`role`
                  FROM `user_sub_role` JOIN `user_role`
                  ON `user_sub_role`.`role_id` = `user_role`.`id`
                  ";
        // Menampilkan semua data join nya
        return $this->db->query($query)->result_array();
    }

    // Fungsi untuk mengambil data subrole berdasarkan ID
    public function get_subrole_by_id($id) 
    {
        $query = $this->db->get_where('user_sub_role', array('id' => $id));
        return $query->row();
    }

    // Fungsi untuk mengupdate data subrole berdasarkan ID
    public function update_subrole($id, $data) 
    {
        $this->db->where('id', $id);
        $this->db->update('user_sub_role', $data);
    }

    // Fungsi delete subrole
    public function delete_subrole($id) 
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sub_role');
    }
}
