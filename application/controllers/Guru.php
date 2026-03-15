<?php
defined('BASEPATH')OR exit('No direct script access allowed');

class Guru extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('userdata')){
            redirect('auth/');
        }elseif($this->session->userdata('userdata')['level'] != 4){
            redirect('auth/');
        }
        //Do your magic here
    }
    
    function view($page = 'home')
    {
        if(!file_exists(APPPATH . 'views/guru/' . $page . '.php')){
            $data['content']    = '404';
            // exit;
        }else{
            $data['content']    = 'guru/' . $page;
        }
        
        // Load pagination library for data tables
        if($page == 'data-siswa'){
            $this->load->library('pagination');
        }
        
        $this->load->view('template', $data, FALSE);
    }

    function tambah_siswa()
    {
        $status = true;
        //Form validation
        $this->form_validation->set_rules('siswa_nama', 'Nama Siswa', 'required');
        $this->form_validation->set_rules('siswa_kelas', 'Kelas Siswa', 'required');
        $this->form_validation->set_rules('siswa_telepon', 'Telepon Siswa', 'required|numeric');
        $this->form_validation->set_rules('siswa_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('dudi_id', 'Perusahaan (DUDI)', 'required');
        $this->form_validation->set_rules('status_pengajuan', 'Status Pengajuan', 'required');
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            /*===========================
            =     Load Models M_siswa   =
            ===========================*/
            $this->load->model('M_siswa');
            $code = $this->M_siswa->generateRandomString(30);//Membuat random string untuk siswa_code      
            
            $data = array(
                'siswa_nama'        => $this->input->post('siswa_nama'),
                'siswa_kelas'       => $this->input->post('siswa_kelas'),
                'siswa_telepon'     => $this->input->post('siswa_telepon'),
                'siswa_alamat'      => $this->input->post('siswa_alamat'),
                'siswa_jurusan'     => $this->input->post('siswa_jurusan'),
                'dudi_id'           => $this->input->post('dudi_id'),
                'status_pengajuan'  => $this->input->post('status_pengajuan'),
                'periode'           => $this->input->post('periode'),
                'siswa_code'        => $code
            );

            $this->M_siswa->tambah_data_siswa($data);
            
            $this->session->set_flashdata('message', 'Berhasil menambah siswa');
            redirect('guru/view/data-siswa');
        
        }else{
            $this->view('tambah-siswa');
        }
    }

    function read_siswa($siswa_code = '')
    {
        /*===========================
        =     Load Models M_siswa   = 
        ===========================*/
        $this->load->model('M_siswa');
        $cekk_siswa_code = $this->M_siswa->get_data_siswa($siswa_code);
        if($cekk_siswa_code){
            //JIka ada siswa yang mempunyai code tersebut, maka tampilkan
            $data['siswa']      = $cekk_siswa_code;
            $data['content']    = 'guru/lihat-siswa';
            
        }else{
            $data['content']    = '404';
        }
        
        $this->load->view('template', $data, FALSE);
    }

    function edit_siswa($siswa_code)
    {
        /*===========================
        =     Load Models M_siswa   =
        ===========================*/
        $this->load->model('M_siswa');
        $siswa = $this->M_siswa->get_data_siswa($siswa_code);
        if($siswa){

            $data['siswa']      = $siswa;
            $data['content']    = 'Guru/edit-siswa';
        }else{
            $data['content']    = '404';
        }
        $this->load->view('template', $data, FALSE);
    }

    function update_data_siswa()
    {
        $status     = true;
        $content    = 'guru/edit-siswa';
        $siswa_code = $this->input->post('siswa_code');

        /*===========================
        =     Load Models M_siswa   =
        ===========================*/
        $this->load->model('M_siswa');
        $ceksiswa = $this->M_siswa->get_data_siswa($siswa_code);
        if(!$ceksiswa){
            $status     = false;
            $content    = '404';
        
        }else{
            $data['siswa']   = $ceksiswa;
        }
        
        //Form validation
        $this->form_validation->set_rules('siswa_code', 'code', 'required');
        $this->form_validation->set_rules('siswa_nama', 'Nama Siswa', 'required');
        $this->form_validation->set_rules('siswa_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('siswa_telepon', 'Telepon Siswa', 'required|numeric');
        $this->form_validation->set_rules('siswa_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('dudi_id', 'Perusahaan (DUDI)', 'required');
        $this->form_validation->set_rules('status_pengajuan', 'Status Pengajuan', 'required');
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            $update = array(
                'siswa_nama'        => $this->input->post('siswa_nama'),
                'siswa_kelas'       => $this->input->post('siswa_kelas'),
                'siswa_telepon'     => $this->input->post('siswa_telepon'),
                'siswa_alamat'      => $this->input->post('siswa_alamat'),
                'siswa_jurusan'     => $this->input->post('siswa_jurusan'),
                'dudi_id'           => $this->input->post('dudi_id'),
                'status_pengajuan'  => $this->input->post('status_pengajuan'),
                'periode'           => $this->input->post('periode'),
            );

            $this->M_siswa->update_siswa($update, $siswa_code);
            $this->session->set_flashdata('message', 'Berhasil update data siswa');
            redirect('Guru/view/data-siswa');
        }

        $data['content']    = $content; 
        $this->load->view('template', $data, FALSE);
    }

    function hapus_siswa($siswa_code)
    {
        /*===========================
        =     Load Models M_siswa   =
        ===========================*/
        $this->load->model('M_siswa');
        $cek_query = $this->M_siswa->hapus_siswa($siswa_code);
        if($cek_query){
            $this->session->set_flashdata('message', 'Berhasil Hapus data siswa');
            redirect('Guru/view/data-siswa');
        }else{
            $this->session->set_flashdata('error_message', 'Gagal Hapus data siswa');
            redirect('Guru/view/data-siswa');
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
            redirect('guru/view/profile');
        } else {
            $data['user'] = $user;
            $data['content'] = 'guru/profile';
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
                redirect('guru/view/change-password');
            }
        }

        $data['content'] = 'guru/change-password';
        $this->load->view('template', $data, FALSE);
    }
}