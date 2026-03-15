<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_qr_debug extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('qr_generator');
    }
    
    public function index()
    {
        echo "<h1>Debug QR Code Generation</h1>";
        
        // Test 1: Generate QR code with mock data
        $mock_siswa = (object) [
            'siswa_id' => 1,
            'siswa_nama' => 'Test Siswa',
            'siswa_kelas' => 'XII RPL 1'
        ];
        
        $qr_url = $this->qr_generator->generate_student_qr($mock_siswa);
        
        echo "<h2>Test 1: Mock Student Data</h2>";
        echo "<p>Student Data: " . print_r($mock_siswa, true) . "</p>";
        echo "<p>QR Code URL: <a href='$qr_url' target='_blank'>$qr_url</a></p>";
        echo "<div style='border: 2px solid blue; padding: 10px; display: inline-block;'>";
        echo "<img src='$qr_url' alt='QR Code' style='max-width: 200px;'>";
        echo "</div>";
        
        // Test 2: Check if we can access database
        echo "<h2>Test 2: Database Connection</h2>";
        $this->db->where('username', 'siswa1');
        $user = $this->db->get('tb_user')->row();
        
        if($user) {
            echo "<p style='color: green;'>User 'siswa1' found: " . $user->nama_lengkap . "</p>";
            
            // Get student data
            $this->db->where('user_id', $user->id);
            $siswa = $this->db->get('tb_siswa')->row();
            
            if($siswa) {
                echo "<p style='color: green;'>Student data found: " . $siswa->siswa_nama . "</p>";
                
                // Generate QR code with real data
                $real_qr_url = $this->qr_generator->generate_student_qr($siswa);
                echo "<p>Real QR Code URL: <a href='$real_qr_url' target='_blank'>$real_qr_url</a></p>";
                echo "<div style='border: 2px solid green; padding: 10px; display: inline-block;'>";
                echo "<img src='$real_qr_url' alt='Real QR Code' style='max-width: 200px;'>";
                echo "</div>";
            } else {
                echo "<p style='color: red;'>No student data found for user ID: " . $user->id . "</p>";
            }
        } else {
            echo "<p style='color: red;'>User 'siswa1' not found in database</p>";
        }
        
        echo "<h2>Test 3: Direct URL Access</h2>";
        $test_url = "https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=Test&choe=UTF-8&chld=M|2";
        echo "<p>Test URL: <a href='$test_url' target='_blank'>$test_url</a></p>";
        echo "<div style='border: 2px solid purple; padding: 10px; display: inline-block;'>";
        echo "<img src='$test_url' alt='Test QR Code' style='max-width: 200px;'>";
        echo "</div>";
    }
}