<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // load Admin_model


        // Secure Direct Access (check_login_helper)
        check_login();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $data['title'] = 'Access Role';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleEdit ()
    {
        $data['title'] = 'Role Edit';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role_edit', $data);
        $this->load->view('templates/footer');
    }

    public function accessRole($role_id)
    {
        $data['title'] = 'Access Role';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // ambil role nya berdasarkan role_id
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        
        $this->db->where('id !=', 1);
        // ambil semua data yg ada di tabel user_menu
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/access_role', $data);
        $this->load->view('templates/footer');
    }

    public function change_access()
    {
        // check data menu_id dan role_id pada tabel user_access_menu. Jika ada maka hapus, jika kosong maka tambahkan yang baru.
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'menu_id' => $menu_id,
            'role_id' => $role_id
        ];

        // Jalankan query database untuk tabel user_access_menu dan cocokan dengan variable $data diatas
        $result = $this->db->get_where('user_access_menu', $data);

        //Kondisi untuk member dengan role_id=2
        if ($result->num_rows() < 1) {
            //Jika belum ada data, maka tambahkan
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Access have been changed!</div>');

    }

}
