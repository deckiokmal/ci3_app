<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mengambil semua data menu
    public function get_all_menus() 
    {
        $query = $this->db->get('user_menu');
        return $query->result();
    }

    // Fungsi untuk mengambil data menu berdasarkan ID
    public function get_menu_by_id($id) 
    {
        $query = $this->db->get_where('user_menu', array('id' => $id));
        return $query->row();
    }

    // Fungsi untuk menambahkan data menu baru
    public function add_menu($data) 
    {
        $this->db->insert('user_menu', $data);
        return $this->db->insert_id();
    }

    // Fungsi untuk mengupdate data menu berdasarkan ID
    public function update_menu($id, $data) 
    {
        $this->db->where('id', $id);
        $this->db->update('user_menu', $data);
    }

    // Fungsi untuk menghapus data menu berdasarkan ID
    public function delete_menu($id) 
    {
        $this->db->where('id', $id);
        $this->db->delete('user_menu');
    }

    public function user_menu_join_submenu ()
    {
        // Fungsi join tabel user_sub_menu dengan tabel user_menu
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                  FROM `user_sub_menu` JOIN `user_menu`
                  ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                  ";
        // Menampilkan semua data join nya
        return $this->db->query($query)->result_array();
    }

    // Fungsi untuk mengambil data submenu berdasarkan ID
    public function get_submenu_by_id($id) 
    {
        $query = $this->db->get_where('user_sub_menu', array('id' => $id));
        return $query->row();
    }

    // Fungsi untuk mengupdate data submenu berdasarkan ID
    public function update_submenu($id, $data) 
    {
        $this->db->where('id', $id);
        $this->db->update('user_sub_menu', $data);
    }

    // Fungsi delete submenu
    public function delete_submenu($id) 
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sub_menu');
    }
}
