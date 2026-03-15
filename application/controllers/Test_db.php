<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_db extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function check_users()
    {
        echo "Checking users in database:\n\n";
        
        // Check all users
        $users = $this->db->get('tb_user')->result();
        foreach($users as $user) {
            echo "User ID: " . $user->id . "\n";
            echo "Username: " . $user->username . "\n";
            echo "Level: " . $user->level . "\n";
            echo "Active: " . $user->active . "\n";
            echo "Password hash: " . substr($user->password, 0, 20) . "...\n";
            echo "---\n";
        }
        
        echo "\nChecking student data:\n\n";
        
        // Check student data
        $this->db->select('s.*, u.username, u.nama_lengkap');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
        $students = $this->db->get()->result();
        
        foreach($students as $student) {
            echo "Student ID: " . $student->siswa_id . "\n";
            echo "User ID: " . $student->user_id . "\n";
            echo "Username: " . $student->username . "\n";
            echo "Student Name: " . $student->siswa_nama . "\n";
            echo "Class: " . $student->siswa_kelas . "\n";
            echo "Status: " . $student->status_pengajuan . "\n";
            echo "---\n";
        }
        
        echo "\nTesting password verification:\n\n";
        
        // Test password verification for siswa1
        $this->db->where('username', 'siswa1');
        $user = $this->db->get('tb_user')->row();
        
        if($user) {
            echo "Found user 'siswa1'\n";
            echo "Stored password hash: " . $user->password . "\n";
            
            if(password_verify('password123', $user->password)) {
                echo "Password verification: SUCCESS\n";
            } else {
                echo "Password verification: FAILED\n";
            }
        } else {
            echo "User 'siswa1' not found\n";
        }
    }
}