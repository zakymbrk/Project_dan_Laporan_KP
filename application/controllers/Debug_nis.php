<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_nis extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_siswa');
    }
    
    public function index()
    {
        echo "<h2>Debug NIS Data</h2>";
        
        // Check all students with NIS
        echo "<h3>All Students with NIS</h3>";
        $this->db->select('siswa_id, siswa_nama, siswa_nis, siswa_kelas, user_id');
        $this->db->where('siswa_nis IS NOT NULL');
        $this->db->order_by('siswa_nis');
        $students = $this->db->get('tb_siswa')->result();
        
        if($students) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Nama</th><th>NIS</th><th>Kelas</th><th>User ID</th></tr>";
            foreach($students as $student) {
                echo "<tr>";
                echo "<td>" . $student->siswa_id . "</td>";
                echo "<td>" . $student->siswa_nama . "</td>";
                echo "<td>" . $student->siswa_nis . "</td>";
                echo "<td>" . $student->siswa_kelas . "</td>";
                echo "<td>" . $student->user_id . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No students with NIS found</p>";
        }
        
        // Test specific case
        echo "<h3>Test NIS 11223345677</h3>";
        $this->db->where('siswa_nis', '11223345677');
        $student = $this->db->get('tb_siswa')->row();
        
        if($student) {
            echo "<p style='color:green;'>Found: " . $student->siswa_nama . " in " . $student->siswa_kelas . "</p>";
        } else {
            echo "<p style='color:blue;'>NIS 11223345677 not found</p>";
        }
        
        echo "<p><a href='" . base_url('siswa/view/profile') . "'>Test Profile Page</a></p>";
    }
}