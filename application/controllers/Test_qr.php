<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_qr extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('qr_generator');
    }
    
    public function test_qr_generation()
    {
        // Create a mock student object
        $siswa = (object) array(
            'siswa_id' => 4,
            'siswa_nama' => 'Ahmad Siswa',
            'siswa_kelas' => 'XII RPL 1'
        );
        
        // Generate QR code
        $qr_code_url = $this->qr_generator->generate_student_qr($siswa);
        
        echo "Student Data:\n";
        print_r($siswa);
        echo "\nQR Code URL: " . $qr_code_url . "\n";
        
        // Test if URL is accessible
        $headers = @get_headers($qr_code_url);
        if($headers && strpos($headers[0], '200')) {
            echo "QR Code URL is accessible\n";
        } else {
            echo "QR Code URL is not accessible\n";
        }
        
        // Display the QR code
        echo "\n<img src='" . $qr_code_url . "' alt='Test QR Code'>\n";
    }
}