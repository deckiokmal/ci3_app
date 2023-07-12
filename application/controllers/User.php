<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Secure Direct Access (check_login_helper)
        check_login();
    }
    
    public function index()
    {
        // Mengirimkan data Header Page dengan variable title yang dapat di panggil di html view
        $data['title'] = 'My Profile';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        // Mengirimkan data Header Page dengan variable title yang dapat di panggil di html view
        // Ingat Title harus sesuai dengan yg ada di database supaya fitur active link nya jalan.
        $data['title'] = 'Edit Profile';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Rules dalam form validation input edit profile
        $this->form_validation->set_rules('name', 'Fullname', 'required|trim');

        // Jalankan form validation untuk check input user
        if($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // Check jika ada Pictures yang di Upload
            $upload_image = $_FILES['image']['name'];
            if($upload_image)
            {
                $config['upload_path'] = './assets/img/profile/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']     = '2048';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('image')) 
                {
                    // parameter untuk menghapus pictures yg lama dengan method unlink(FCPATH)
                    $old_image = $data['user']['image'];
                    if($old_image != 'default.png') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }
                    
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                }
                else 
                {
                    echo $this->upload->display_errors();
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profile have been updated!</div>');
            redirect('user');
        }

    }
    public function changepassword()
    {
        // Mengirimkan data Header Page dengan variable title yang dapat di panggil di html view
        // Ingat Title harus sesuai dengan yg ada di database supaya fitur active link nya jalan.
        $data['title'] = 'Change Password';
        //ambil session user berdasarkan email yang login
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Rules dalam form validation input edit password
        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('password1', 'New Password', 'required|trim|min_length[8]|matches[password2]', [
            'matches' => 'password not match!',
            'min_length' => 'password too short min 8 digit!'
        ]);
        $this->form_validation->set_rules('password2', 'Retype Password', 'required|trim|matches[password1]');

        // Jalankan form validation untuk check input user
        if($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            //Check Password Lama
            $current_password = $this->input->post('current_password');
            $password1 = $this->input->post('password1');
            $password2 = $this->input->post('password2');

            if (!password_verify($current_password, $data['user']['password']))
            {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password. Try again!</div>');
                redirect('user/changepassword'); 
            } else {
                if($current_password == $password1) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as the current password!</div>');
                    redirect('user/changepassword'); 
                } else {
                    // password OK
                    $password_hash = password_hash($password1, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');
                    
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password have been updated!</div>');
                    redirect('user/changepassword');
                }
            }

        }

    }

}
