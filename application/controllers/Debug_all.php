<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_all extends CI_Controller {
    
    public function index()
    {
        echo "<h2>Database Debug</h2>";
        
        $this->load->database();
        
        // Show all students
        echo "<h3>All Students</h3>";
        $query = $this->db->get('tb_siswa');
        $students = $query->result();
        
        if($students) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Nama</th><th>NIS</th><th>Kelas</th><th>User ID</th></tr>";
            foreach($students as $student) {
                echo "<tr>";
                echo "<td>" . $student->siswa_id . "</td>";
                echo "<td>" . $student->siswa_nama . "</td>";
                echo "<td>" . ($student->siswa_nis ? $student->siswa_nis : '-') . "</td>";
                echo "<td>" . $student->siswa_kelas . "</td>";
                echo "<td>" . $student->user_id . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No students found</p>";
        }
        
        echo "<p>Implementation is complete and ready for testing.</p>";
    }
}