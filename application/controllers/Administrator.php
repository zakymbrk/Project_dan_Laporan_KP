<?php
defined('BASEPATH')OR exit('No direct script access allowed');

class Administrator extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('userdata')){
            redirect('auth/');
        
        }elseif($this->session->userdata('userdata')['level'] != 1){
            redirect('auth/');
        }
        //Do your magic here
    }

    function view($page = 'home')
    {
        if(!file_exists(APPPATH . 'views/hubin/' . $page . '.php')){
            $data['content']    = '404';
            // exit;
        }else{
            $data['content']    = 'hubin/' . $page;
        }
        
        // Load pagination library for data tables
        if($page == 'data-user'){
            $this->load->library('pagination');
        }
        
        $this->load->view('template', $data, FALSE);
    }
    
    function export_siswa()
    {
        $this->view('export-siswa');
    }
    
    function import_siswa()
    {
        $this->view('import-siswa');
    }


    function tambah_user()
    {
        $status = true;
        //Form validation
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('level', 'Level', 'required');
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }


        if($status){
            /*===========================
            =     Load Models M_user    =
            ===========================*/
            $this->load->model('M_user');
            
            $hash = password_hash($this->input->post('password'), PASSWORD_DEFAULT);//UBAH PASSWORD KE HASH   
            $code = $this->M_user->generateRandomString(30);//Membuat random string untuk user_code      
            $data = array(
                'username'      => $this->input->post('username'),
                'password'      => $hash,
                'nama_lengkap'  => $this->input->post('nama_lengkap'),
                'level'         => $this->input->post('level'),
                'user_code'     => $code
            );

            $this->M_user->tambah_data_user($data);
            
            $this->session->set_flashdata('message', 'Berhasil menambah user');
            redirect('hubin/view/data-user');
        
        }else{
            $this->view('tambah-user');
        }
    }

    function read_user($user_code = null)
    {
        /*===========================
        =     Load Models M_user    =
        ===========================*/
        $this->load->model('M_user');
        $user = $this->M_user->get_data_user($user_code);
        if($user){
            $data['user']       = $user;
            $data['content']    = 'hubin/read-user';
        }else{
            $data['content']    = '404';
        }
        $this->load->view('template', $data, FALSE);
    }

    function edit_user($user_code = null)
    {
        /*===========================
        =     Load Models M_user    =
        ===========================*/
        $this->load->model('M_user');
        $user = $this->M_user->get_data_user($user_code);
        if($user){

            $data['user']       = $user;
            $data['content']    = 'hubin/edit-user';
        }else{
            $data['content']    = '404';
        }
        $this->load->view('template', $data, FALSE);
    }

    function update_data_user()
    {
        $status     = true;
        $content    = 'hubin/edit-user';
        $user_code  = $this->input->post('user_code');

        /*===========================
        =     Load Models M_user    =
        ===========================*/
        $this->load->model('M_user');
        $cekusers = $this->M_user->get_data_user($user_code);
        if(!$cekusers){
            $status     = false;
            $content    = '404';
        
        }else{
            $data['user']   = $cekusers;
        }
        
        //Form validation
        $this->form_validation->set_rules('user_code', 'code', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('level', 'Level', 'required');
        if ($this->form_validation->run() == FALSE) {
            $status             = false;
        }

        if($status){
            $update = array(
                'username'      => $this->input->post('username'),
                'nama_lengkap'  => $this->input->post('nama_lengkap'),
                'level'         => $this->input->post('level'),
            );

            $this->M_user->update_user($update, $user_code);
            $this->session->set_flashdata('message', 'Berhasil update data user');
            redirect('hubin/view/data-user');
        }

        $data['content']    = $content; 
        $this->load->view('template', $data, FALSE);
    }

    function hapus_user($user_code = null)
    {
        /*===========================
        =     Load Models M_user    =
        ===========================*/
        $this->load->model('M_user');
        $cekusers = $this->M_user->get_data_user($user_code);
        if($cekusers){
            $this->M_user->delete_user($user_code);
            $this->session->set_flashdata('message', 'Berhasil menghapus user');
            redirect('hubin/view/data-user');
        }else{
            echo "<script>alert('User tidak ditemukan');</script>";
            redirect('hubin/view/data-user');
        }        
    }

    function update_profile()
    {
        $status = true;
        $userdata = $this->session->userdata('userdata');
        $user_code = $userdata['id'];

        $this->load->model('M_user');
        $user = $this->M_user->get_user_by_id($user_code);
        
        if(!$user){
            $status = false;
            $this->session->set_flashdata('error_message', 'User tidak ditemukan');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            $update = array(
                'username' => $this->input->post('username'),
                'nama_lengkap' => $this->input->post('nama_lengkap'),
            );

            $this->M_user->update_user_by_id($update, $user_code);
            
            // Update session
            $user_login = array(
                'userdata' => array(
                    'id' => $userdata['id'],
                    'username' => $update['username'],
                    'nama_lengkap' => $update['nama_lengkap'],
                    'level' => $userdata['level'],
                    'group_name' => $userdata['group_name']
                ),
            );
            $this->session->set_userdata($user_login);
            
            $this->session->set_flashdata('message', 'Profile berhasil diupdate');
            redirect('hubin/view/profile');
        } else {
            $data['user'] = $user;
            $data['content'] = 'hubin/profile';
            $this->load->view('template', $data, FALSE);
        }
    }

    function change_password()
    {
        $status = true;
        $userdata = $this->session->userdata('userdata');
        $user_code = $userdata['id'];

        $this->load->model('M_user');
        $user = $this->M_user->get_user_by_id($user_code);
        
        if(!$user){
            $status = false;
            $this->session->set_flashdata('error_message', 'User tidak ditemukan');
        }

        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            // Verify old password
            if(!password_verify($this->input->post('password_lama'), $user->password)){
                $this->session->set_flashdata('error_message', 'Password lama tidak sesuai');
                $status = false;
            }

            if($status){
                $hash = password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT);
                $update = array('password' => $hash);
                $this->M_user->update_user_by_id($update, $user_code);
                
                $this->session->set_flashdata('message', 'Password berhasil diubah');
                redirect('hubin/view/change-password');
            }
        }

        $data['content'] = 'hubin/change-password';
        $this->load->view('template', $data, FALSE);
    }
}