<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_student extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function add_sample_student()
    {
        // Add sample student user
        $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
        
        $user_data = array(
            'username' => 'siswa1',
            'password' => $hashed_password,
            'nama_lengkap' => 'Ahmad Siswa',
            'email' => 'siswa1@sekolah.sch.id',
            'telepon' => '081234567891',
            'alamat' => 'Jl. Siswa No. 123, Kota Sekolah',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2005-01-15',
            'jenis_kelamin' => 'L',
            'nip_nim' => '200501152023001001',
            'level' => 3, // Level 3 for student
            'active' => '1',
            'user_code' => 'siswa001'
        );
        
        $this->db->insert('tb_user', $user_data);
        $user_id = $this->db->insert_id();
        
        echo "Student user 'siswa1' created with ID: $user_id\n";
        
        // Add sample student data
        $siswa_data = array(
            'user_id' => $user_id,
            'siswa_nama' => 'Ahmad Siswa',
            'siswa_nis' => '1234567890',
            'siswa_kelas' => 'XII RPL 1',
            'siswa_jurusan' => 'Rekayasa Perangkat Lunak',
            'siswa_telepon' => '081234567891',
            'siswa_alamat' => 'Jl. Siswa No. 123, Kota Sekolah',
            'periode' => '2023/2024',
            'status_pengajuan' => 'disetujui',
            'siswa_code' => 'siswa_' . time()
        );
        
        $this->db->insert('tb_siswa', $siswa_data);
        $siswa_id = $this->db->insert_id();
        
        echo "Student data created with ID: $siswa_id\n";
        echo "You can now login with username: siswa1 and password: password123\n";
    }
}