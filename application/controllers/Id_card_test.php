<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Id_card_test extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('qr_generator');
    }
    
    public function test_id_card_view()
    {
        // Simulate a logged in student session
        $user_login = array(
            'userdata' => array(
                'id' => 8,
                'username' => 'siswa1',
                'nama_lengkap' => 'zaky mubarok',
                'level' => 3,
                'group_name' => 'Siswa',
                'foto_profil' => null
            ),
        );
        
        $this->session->set_userdata($user_login);
        
        echo "<h1>ID Card View Test</h1>";
        
        // Simulate the exact code from Siswa controller
        $userdata = $this->session->userdata('userdata');
        echo "<h2>User Data from Session:</h2>";
        echo "<pre>" . print_r($userdata, true) . "</pre>";
        
        // Fetch required data for the view
        $this->db->select('s.*, u.nama_lengkap, u.foto_profil, d.dudi_nama, d.dudi_id');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
        $this->db->join('tb_dudi d', 'd.dudi_id = s.dudi_id', 'left');
        $this->db->where('s.user_id', $userdata['id']);
        $siswa = $this->db->get()->row();
        
        echo "<h2>Student Data from Database:</h2>";
        if($siswa) {
            echo "<pre>" . print_r($siswa, true) . "</pre>";
        } else {
            echo "<p style='color: red;'>No student data found!</p>";
            return;
        }
        
        // Generate QR code after student data is fetched
        $qr_code_url = $this->qr_generator->generate_student_qr($siswa);
        
        echo "<h2>QR Code URL:</h2>";
        echo "<p>" . $qr_code_url . "</p>";
        
        // Check if QR code URL is empty
        if(empty($qr_code_url)) {
            echo "<p style='color: red; font-weight: bold;'>QR CODE URL IS EMPTY!</p>";
        } else {
            echo "<p style='color: green; font-weight: bold;'>QR Code URL is present</p>";
        }
        
        // Display the QR code
        echo "<h2>QR Code Display:</h2>";
        echo "<div style='border: 3px solid blue; padding: 20px; width: 200px; height: 200px; background: white;'>";
        if (!empty($qr_code_url)) {
            echo "<img src='" . $qr_code_url . "' alt='QR Code Verifikasi' style='width: 100%; height: 100%; border: 2px solid green;'>";
        } else {
            echo "<div style='width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666; font-size: 14px;'>";
            echo "QR Code tidak tersedia";
            echo "</div>";
        }
        echo "</div>";
        
        // Test the actual view rendering
        echo "<h2>Testing View Variables:</h2>";
        $data['siswa'] = $siswa;
        $data['qr_code_url'] = $qr_code_url;
        $data['foto_profil'] = isset($siswa->foto_profil_url) ? $siswa->foto_profil_url : base_url('assets/img/default-avatar.png');
        $data['dudi'] = isset($siswa->dudi_nama) ? (object)['dudi_nama' => $siswa->dudi_nama] : null;
        
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
}