<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_idcard_access extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('qr_generator');
    }
    
    public function test_student_view()
    {
        // Set session data for student
        $user_data = array(
            'userdata' => array(
                'id' => 8,
                'username' => 'siswa1',
                'nama_lengkap' => 'zaky mubarok',
                'level' => 3,
                'group_name' => 'Siswa',
                'foto_profil' => null
            )
        );
        $this->session->set_userdata($user_data);
        
        // Call the method like it's called from the real Siswa controller
        $page = 'id-card';
        $userdata = $this->session->userdata('userdata');
        $this->db->select('s.*, u.nama_lengkap, u.foto_profil, d.dudi_nama, d.dudi_id');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
        $this->db->join('tb_dudi d', 'd.dudi_id = s.dudi_id', 'left');
        $this->db->where('s.user_id', $userdata['id']);
        $siswa = $this->db->get()->row();
        
        if($siswa) {
            // Generate QR code
            $qr_code_url = $this->qr_generator->generate_qr_code(
                'https://smk.itikurih-hibarna.sch.id/?page_id=140&id=' . $siswa->siswa_id . 
                '&nama=' . urlencode($siswa->siswa_nama) . '&kelas=' . urlencode($siswa->siswa_kelas),
                160, 'M', 2
            );
            
            // Pre-process data
            $siswa->foto_profil_url = null;
            if($siswa->user_id && $siswa->foto_profil && file_exists('./uploads/profil/'.$siswa->foto_profil)){
                $siswa->foto_profil_url = base_url('uploads/profil/'.$siswa->foto_profil);
            }
            
            $data['siswa'] = $siswa;
            $data['qr_code_url'] = $qr_code_url;
            
            echo "<h1>ID Card Test - Student View</h1>";
            echo "<p>Student: " . $siswa->siswa_nama . "</p>";
            echo "<p>Class: " . $siswa->siswa_kelas . "</p>";
            echo "<p>Company: " . $siswa->dudi_nama . "</p>";
            echo "<p>QR Code URL: " . $qr_code_url . "</p>";
            
            // Test QR code accessibility
            $headers = @get_headers($qr_code_url);
            if($headers && strpos($headers[0], '200')) {
                echo "<p style='color: green;'>QR Code is accessible</p>";
                echo "<img src='{$qr_code_url}' alt='QR Code' style='width: 150px; height: 150px; border: 2px solid green;'>";
            } else {
                echo "<p style='color: red;'>QR Code is not accessible</p>";
            }
            
            // Load the view
            echo "<h2>ID Card Preview:</h2>";
            $this->load->view('siswa/id-card', $data, FALSE);
        } else {
            echo "<p style='color: red;'>No student data found!</p>";
        }
    }
    
    public function test_hubin_view()
    {
        // Test with specific student ID
        $siswa_id = 6; // zaky mubarok's student ID
        
        $this->db->select('s.*, u.nama_lengkap, u.foto_profil, d.dudi_nama, d.dudi_id as dudi_real_id');
        $this->db->from('tb_siswa s');
        $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
        $this->db->join('tb_dudi d', 'd.dudi_id = s.dudi_id', 'left');
        $this->db->where('s.siswa_id', $siswa_id);
        $siswa = $this->db->get()->row();
        
        if($siswa) {
            // Generate QR code
            $qr_code_url = $this->qr_generator->generate_qr_code(
                'https://smk.itikurih-hibarna.sch.id/?page_id=140&id=' . $siswa->siswa_id . 
                '&nama=' . urlencode($siswa->siswa_nama) . '&kelas=' . urlencode($siswa->siswa_kelas),
                160, 'M', 2
            );
            
            // Pre-process data
            $siswa->foto_profil_url = null;
            if($siswa->user_id && $siswa->foto_profil && file_exists('./uploads/profil/'.$siswa->foto_profil)){
                $siswa->foto_profil_url = base_url('uploads/profil/'.$siswa->foto_profil);
            }
            
            $data['siswa'] = $siswa;
            $data['qr_code_url'] = $qr_code_url;
            
            echo "<h1>ID Card Test - Hubin View</h1>";
            echo "<p>Student: " . $siswa->siswa_nama . "</p>";
            echo "<p>Class: " . $siswa->siswa_kelas . "</p>";
            echo "<p>Company: " . $siswa->dudi_nama . "</p>";
            echo "<p>QR Code URL: " . $qr_code_url . "</p>";
            
            // Test QR code accessibility
            $headers = @get_headers($qr_code_url);
            if($headers && strpos($headers[0], '200')) {
                echo "<p style='color: green;'>QR Code is accessible</p>";
                echo "<img src='{$qr_code_url}' alt='QR Code' style='width: 150px; height: 150px; border: 2px solid green;'>";
            } else {
                echo "<p style='color: red;'>QR Code is not accessible</p>";
            }
            
            // Load the view
            echo "<h2>ID Card Preview:</h2>";
            $this->load->view('hubin/id-card', $data, FALSE);
        } else {
            echo "<p style='color: red;'>No student data found!</p>";
        }
    }
}