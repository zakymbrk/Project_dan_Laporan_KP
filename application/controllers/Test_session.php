<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_session extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function test_login_and_idcard()
    {
        // Simulate login
        $this->load->model('Login');
        $data_login = array(
            'username' => 'siswa1',
            'password' => 'password123'
        );
        
        $user = $this->Login->cek_login($data_login);
        
        if($user && $user->level == 3) {
            echo "Login successful!\n";
            echo "User data: " . print_r($user, true) . "\n";
            
            // Set session
            $user_login = array(
                'userdata' => array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'nama_lengkap' => $user->nama_lengkap,
                    'level' => $user->level,
                    'group_name' => $user->group_name,
                    'foto_profil' => isset($user->foto_profil) ? $user->foto_profil : null
                ),
            );
            
            $this->session->set_userdata($user_login);
            echo "Session set successfully\n";
            
            // Now test the ID card generation
            $this->load->library('qr_generator');
            
            // Fetch student data
            $this->db->select('s.*, u.nama_lengkap, u.foto_profil, d.dudi_nama, d.dudi_id');
            $this->db->from('tb_siswa s');
            $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
            $this->db->join('tb_dudi d', 'd.dudi_id = s.dudi_id', 'left');
            $this->db->where('s.user_id', $user->id);
            $siswa = $this->db->get()->row();
            
            if($siswa) {
                echo "Student data found: " . print_r($siswa, true) . "\n";
                
                // Generate QR code
                $qr_code_url = $this->qr_generator->generate_student_qr($siswa);
                echo "QR Code URL: " . $qr_code_url . "\n";
                
                // Test QR code accessibility
                $headers = @get_headers($qr_code_url);
                if($headers && strpos($headers[0], '200')) {
                    echo "QR Code is accessible\n";
                } else {
                    echo "QR Code may not be accessible, but URL is valid\n";
                }
                
                echo "QR Code can be displayed with: <img src='$qr_code_url' alt='QR Code'>\n";
            } else {
                echo "No student data found for this user\n";
            }
            
        } else {
            echo "Login failed\n";
        }
    }
}