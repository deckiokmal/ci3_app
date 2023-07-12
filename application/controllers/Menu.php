<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Menu_model'); // Memuat model Menu_model

        // Secure Direct Access (check_login_helper)
        check_login();
    }
    
    public function index ()
    {
        $data['title'] = 'Menu Management';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if($this->form_validation->run() == false) {
            
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');

        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu have been added!</div>');
            redirect('menu');
        }


    }

    public function edit($id) 
    {
        $data['title'] = 'Edit Menu';
        // Memperbarui data menu berdasarkan ID
        if ($this->input->post()) {
            $data = array(
                'menu' => $this->input->post('menu_edit')
            );
            $this->Menu_model->update_menu($id, $data);
            redirect('menu');
        }

        // Menampilkan form edit
        $data['user_menu_edit'] = $this->Menu_model->get_menu_by_id($id);

        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/edit', $data);
            $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        // Memeriksa apakah data dengan ID yang diberikan ada
        $user_menu_del = $this->Menu_model->get_menu_by_id($id);

        if(!$user_menu_del)
        {
            // Jika data tidak ditemukan, tampilkan pesan error atau lakukan pengalihan ke halaman lain
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Not found!</div>');
            redirect('menu');
            return;
        }
        
        // Hapus data menu berdasarkan ID
        $this->Menu_model->delete_menu($id);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Deleted successfully!</div>');
        redirect('menu');
    }

    public function submenu ()
    {
        $data['title'] = 'Submenu Management';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['subMenu'] = $this->Menu_model->user_menu_join_submenu ();
        $data['menu']    = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if($this->form_validation->run() == false) 
        {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('menu/submenu', $data);
        $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu have been added!</div>');
            redirect('menu/submenu');

        }
    }

    public function submenu_edit($id) 
    {
        $data['title'] = 'Edit subMenu';
        // Memperbarui data menu berdasarkan ID
        if ($this->input->post()) {
            $data = array(
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            );
            $this->Menu_model->update_submenu($id, $data);
            redirect('menu/submenu');
        }

        // Menampilkan form edit
        $data['user_submenu_edit'] = $this->Menu_model->get_submenu_by_id($id);

        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['subMenu'] = $this->Menu_model->user_menu_join_submenu ();
        $data['menu']    = $this->db->get('user_menu')->result_array();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu_edit', $data);
            $this->load->view('templates/footer');
    }

    public function submenudelete($id)
    {
        // Memeriksa apakah data dengan ID yang diberikan ada
        $user_submenu_del = $this->Menu_model->get_submenu_by_id($id);

        if(!$user_submenu_del)
        {
            // Jika data tidak ditemukan, tampilkan pesan error atau lakukan pengalihan ke halaman lain
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Not found!</div>');
            redirect('menu/submenu');
            return;
        }
        
        // Hapus data menu berdasarkan ID
        $this->Menu_model->delete_submenu($id);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Deleted successfully!</div>');
        redirect('menu/submenu');
    }
}