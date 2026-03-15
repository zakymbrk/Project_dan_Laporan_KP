<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_fix_student_display extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
    }
    
    public function index()
    {
        echo "<h2>Testing Student Display Fix</h2>";
        
        // Test 1: Check current student count with level = 2
        $this->db->where('level', 2);
        $student_count = $this->db->count_all_results('tb_user');
        echo "<p><strong>Students with level = 2:</strong> " . $student_count . "</p>";
        
        // Test 2: Check students with level = 3
        $this->db->where('level', 3);
        $old_student_count = $this->db->count_all_results('tb_user');
        echo "<p><strong>Students with level = 3:</strong> " . $old_student_count . "</p>";
        
        // Test 3: Show all students with their levels
        $this->db->select('username, nama_lengkap, level');
        $this->db->where('level IN (2,3)');
        $students = $this->db->get('tb_user')->result();
        
        echo "<h3>All Student Accounts:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Username</th><th>Nama Lengkap</th><th>Level</th></tr>";
        foreach($students as $student) {
            $level_color = $student->level == 2 ? 'green' : 'red';
            echo "<tr>";
            echo "<td>" . $student->username . "</td>";
            echo "<td>" . $student->nama_lengkap . "</td>";
            echo "<td style='color:$level_color; font-weight:bold;'>" . $student->level . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Test Complete!</h3>";
        echo "<p>Students with level = 2 should now appear in the daftar-siswa page.</p>";
        echo "<p><a href='" . base_url('hubin/view/daftar-siswa') . "'>Go to Daftar Siswa Page</a></p>";
    }
}