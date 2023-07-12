<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        //Fitur jika sudah sudah berhasil login dan sesi email sudah ada, maka block akses ke controller auth
        if ($this->session->userdata('email'))
        {
            redirect('user');
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) 
        {
        
        $title['title'] = 'DopnetIndo Login';
        $this->load->view('templates/auth_header', $title);
        $this->load->view('auth/login');
        $this->load->view('templates/auth_footer');
        } else {
            // validasi success login
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        //Query ke Database
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if($user) {
            //JIka user aktif
            if($user['is_active'] == 1){
                // cek password
                if(password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    // simpan data ke session dan arahkan ke controller user
                    $this->session->set_userdata($data);
                    
                    if($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    redirect('auth');
                }

            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This account is not activated!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This account is not registered!</div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        //Fitur jika sudah sudah berhasil login dan sesi email sudah ada, maka block akses ke controller auth
        if ($this->session->userdata('email'))
        {
            redirect('user');
        }
        
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'this email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
            'matches' => 'password not match!',
            'min_length' => 'password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == FALSE) {
            $title['title'] = 'Registration Form';
            $this->load->view('templates/auth_header', $title);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', TRUE);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', TRUE)),
                'email' => htmlspecialchars($email),
                'image' => 'default.png',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            // Siapkan token / OTP untuk verifikasi email activation
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];
            
            // Insert data baru ke dalam database
            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            //setelah data dimasukkan dalam kondisi not active, jalankan email link aktivasi.
            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Account created successfully. please activate the link sent to your email!</div>');
            redirect('auth');
            
        }
    }

    private function _sendEmail ($token, $type)
    {
        // Mengatur pengaturan SMTP secara dinamis di CodeIgniter.
        // SMTP setting pada server terletak di php/php.ini file.
        ini_set('SMTP', 'smtp.gmail.com');
        ini_set('smtp_port', '465');
        ini_set('smtp_ssl', 'ssl');

        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'no.reply.dopnetindo@gmail.com',
            'smtp_pass' => 'ksfrrxhfamivwvaw',
            'smtp_port' => 465,
            'smtp_crypto' => 'ssl',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);
        // $this->email->set_encryption('ssl');
        $this->email->from('no.reply.dopnetindo@gmail.com', 'Dopnetindo Empowertech Partner');
        $this->email->to($this->input->post('email'));

        if($type == 'verify'){
            
            $this->email->subject('User Activation');
            // message untuk verifikasi email registrasi
            $this->email->message('Click this link to verify your account : <a href="'. base_url() . 'auth/verify?email=' . $this->input->post('email') . '& token=' . urlencode($token) . '">Activated</a>');

        } else if ($type == 'forgot') {
            
            $this->email->subject('Reset your password');
            // message untuk verifikasi email registrasi
            $this->email->message('Click this link to reset your password : <a href="'. base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '& token=' . urlencode($token) . '">Reset Password</a>');

        }
        

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify ()
    {
        // ambil dulu email dan token pada email yang ada di url verifikasi
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        // pastikan email nya valid pada db tabel user
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // cek jika ada usernya pada database maka aktifkan usernya di tabel user['is_active=1]
        if ($user) {
            // query token nya pada database
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                //validasi token expire selama 1 hari
                if(time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    //aktifkan user dan update database usernya
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    //delete user token pada database karna sudah tidak digunakan lagi
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'. $email .' Account have been activated. please login.</div>');
                    redirect('auth');
                } else {
                    //hapus email dan token jika sudah expired
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed!. Your token expired!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed!. Makesure your token is not valid!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account activation failed!. Makesure your email is valid!</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logout!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
        $this->load->view('templates/footer');
    }

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        
        if ($this->form_validation-> run() == false) {
            $title['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $title);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user  = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);

                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Please check your email to reset password.</div>');
                redirect('auth');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account is not registered or activated!</div>');
                redirect('auth/forgotpassword');
            }
        }

    }

    public function resetpassword ()
    {
         // ambil dulu email dan token pada email yang ada di url
         $email = $this->input->get('email');
         $token = $this->input->get('token');

          // pastikan email nya valid pada db tabel user
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // cek jika ada usernya pada database maka aktifkan usernya di tabel user['is_active=1]
        if ($user) {
            // query token nya pada database
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                //validasi token expire selama 1 hari
                if(time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    //delete user token pada database karna sudah tidak digunakan lagi
                    $this->session->set_userdata('email', $email);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->changePassword();

                } else {
                    //hapus token jika sudah expired
                    $this->db->delete('user_token', ['email' => $email]);
                    
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password  failed!. Your token expired!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed!. Makesure your token is not valid!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Reset password failed!. Makesure your email is valid!</div>');
            redirect('auth');
        }
    }

    public function changePassword() 
    {
        // Jika tidak ada session nya maka redirect ke login page
        if (!$this->session->userdata('email'))
        {
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
            'matches' => 'password not match!',
            'min_length' => 'password too short min 8 digit!'
        ]);
        $this->form_validation->set_rules('password2', 'Retype Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run () == false) {
            $title['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $title);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');
        } else {
            
            // enkripsi password dan ambil data email di dalam session
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email    = $this->session->userdata('email');

            // update tabel user dengan password yang baru sesuai dengan email yg ada di dalam session
            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');
            
            // sebelum login, hapus session nya
            $this->session->unset_userdata('email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your password have been change. Please login!</div>');
            redirect('auth');
        }
    }
}
