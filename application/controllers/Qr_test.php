<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr_test extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('qr_generator');
    }
    
    public function index()
    {
        // Create a mock student object
        $siswa = (object) array(
            'siswa_id' => 4,
            'siswa_nama' => 'Ahmad Siswa',
            'siswa_kelas' => 'XII RPL 1'
        );
        
        // Generate QR code
        $qr_code_url = $this->qr_generator->generate_student_qr($siswa);
        
        echo "<h1>QR Code Test Page</h1>";
        
        echo "<h2>Student Data:</h2>";
        echo "<pre>" . print_r($siswa, true) . "</pre>";
        
        echo "<h2>QR Code URL:</h2>";
        echo "<p>" . $qr_code_url . "</p>";
        
        echo "<h2>QR Code Image:</h2>";
        echo "<img src='" . $qr_code_url . "' alt='QR Code' style='border: 2px solid red; padding: 10px;'>";
        
        // Test if URL is accessible
        $headers = @get_headers($qr_code_url);
        if($headers && strpos($headers[0], '200')) {
            echo "<p style='color: green; font-weight: bold;'>QR Code URL is accessible</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>QR Code URL may not be accessible</p>";
        }
        
        echo "<h2>Direct URL Test:</h2>";
        echo "<a href='" . $qr_code_url . "' target='_blank'>Click to open QR code URL directly</a>";
    }
}