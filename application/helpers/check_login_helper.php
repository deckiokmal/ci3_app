<?php 

// Jangan Lupa masukkan fungsi ini ke autoload.php : $autoload['helper']   
function check_login()
{
    $ci = get_instance();

    if(!$ci->session->userdata('email'))
    {
        redirect('auth');
    } else {
        // Check role_id user nya
        $role_id = $ci->session->userdata('role_id');
        
        // Check user berhak akses ke menu apa saja (controller yang mana)
        $menu = $ci->uri->segment(1);

        //kita query tabel user_menu untuk mendapatkan menu_id
        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();

        $menu_id = $queryMenu['id'];

        // kita query tabel user_access_menu dan mencocokan role_id dan menu_id pada tabel nya
        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id, 
            'menu_id' => $menu_id
        ]);

        //Jika userAccess tidak sesuai role nya maka diarahkan ke block page
        if($userAccess->num_rows() < 1)
        {
            redirect('auth/blocked');
        }
    }
}


function check_access ($role_id, $menu_id)
{
    $ci = get_instance();

    // kita query tabel user_access_menu dan mencocokan role_id dan menu_id pada tabel nya
    // $ci->db->get_where('user_access_menu', [
    //     'role_id' => $role_id, 
    //     'menu_id' => $menu_id
    // ]);

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);

    $result = $ci->db->get('user_access_menu');

    // cek variabel result, jika result ada isinya dan isinya lebih besar dari 0 = ada
    if($result->num_rows() > 0)
    {
        // kembalikan value ke checked ke view accessrole
        return "checked='checked'";
    }
}