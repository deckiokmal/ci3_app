<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model'); // Memuat model user
    }
    
    // Fungsi untuk mengambil semua data pengguna
    public function users_get() {
        $users = $this->user_model->get_users();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($users));
    }
    
    // Fungsi untuk mengambil data pengguna berdasarkan ID
    public function user_get($id) {
        $user = $this->user_model->get_user($id);
        
        if ($user) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($user));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'User not found.']));
            }
        }
        
        // Fungsi untuk menambahkan pengguna baru
        public function user_post() {
            $data = $this->input->post();
            
            if (!empty($data)) {
                $result = $this->user_model->add_user($data);
                
                if ($result) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => 'User added successfully.']));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => 'Failed to add user.']));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'Invalid request.']));
            }
        }
        
        // Fungsi untuk memperbarui data pengguna berdasarkan ID
        public function user_put($id) {
            $data = $this->input->input_stream();
            
            if (!empty($data)) {
                $result = $this->user_model->update_user($id, $data);
                
                if ($result) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => 'User updated successfully.']));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => 'Failed to update user.']));
                }
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'Invalid request.']));
            }
        }
        
        // Fungsi untuk menghapus data pengguna berdasarkan ID
        public function user_delete($id) {
            $result = $this->user_model->delete_user($id);
            
            if ($result) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'User deleted successfully.']));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'Failed to delete user.']));
            }
        }
}