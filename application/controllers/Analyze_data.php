<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analyze_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        echo "<h2>Student Data Analysis</h2>";
        
        // Check for duplicate NIS/NISN
        echo "<h3>Duplicate NIS/NISN Analysis</h3>";
        $query = $this->db->query("
            SELECT siswa_nis, siswa_nisn, siswa_nama, COUNT(*) as count 
            FROM tb_siswa 
            WHERE siswa_nis IS NOT NULL OR siswa_nisn IS NOT NULL 
            GROUP BY siswa_nis, siswa_nisn 
            HAVING COUNT(*) > 1
        ");
        
        if($query->num_rows() > 0) {
            echo "<p style='color:red;'>Found " . $query->num_rows() . " duplicate NIS/NISN entries:</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>NIS</th><th>NISN</th><th>Name</th><th>Count</th></tr>";
            foreach($query->result() as $row) {
                echo "<tr>";
                echo "<td>" . ($row->siswa_nis ? $row->siswa_nis : '-') . "</td>";
                echo "<td>" . ($row->siswa_nisn ? $row->siswa_nisn : '-') . "</td>";
                echo "<td>" . $row->siswa_nama . "</td>";
                echo "<td>" . $row->count . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:green;'>No duplicate NIS/NISN found</p>";
        }
        
        // Check for duplicate names in same class
        echo "<h3>Duplicate Names in Same Class</h3>";
        $query2 = $this->db->query("
            SELECT siswa_nama, siswa_kelas, COUNT(*) as count 
            FROM tb_siswa 
            WHERE siswa_nama IS NOT NULL AND siswa_kelas IS NOT NULL
            GROUP BY siswa_nama, siswa_kelas 
            HAVING COUNT(*) > 1
        ");
        
        if($query2->num_rows() > 0) {
            echo "<p style='color:red;'>Found " . $query2->num_rows() . " duplicate names in same class:</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Name</th><th>Class</th><th>Count</th></tr>";
            foreach($query2->result() as $row) {
                echo "<tr>";
                echo "<td>" . $row->siswa_nama . "</td>";
                echo "<td>" . $row->siswa_kelas . "</td>";
                echo "<td>" . $row->count . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:green;'>No duplicate names in same class found</p>";
        }
        
        // Show current table structure
        echo "<h3>Current tb_siswa Structure</h3>";
        $fields = $this->db->field_data('tb_siswa');
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach($fields as $field) {
            echo "<tr>";
            echo "<td>" . $field->name . "</td>";
            echo "<td>" . $field->type . "(" . $field->max_length . ")</td>";
            echo "<td>" . ($field->primary_key ? 'PRI' : '') . "</td>";
            echo "<td>" . ($field->default ? $field->default : 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show current data sample
        echo "<h3>Current Data Sample (First 10 records)</h3>";
        $this->db->limit(10);
        $data = $this->db->get('tb_siswa')->result();
        if(!empty($data)) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Name</th><th>NIS</th><th>NISN</th><th>Class</th><th>Status</th></tr>";
            foreach($data as $row) {
                echo "<tr>";
                echo "<td>" . $row->siswa_id . "</td>";
                echo "<td>" . $row->siswa_nama . "</td>";
                echo "<td>" . ($row->siswa_nis ? $row->siswa_nis : '-') . "</td>";
                echo "<td>" . ($row->siswa_nisn ? $row->siswa_nisn : '-') . "</td>";
                echo "<td>" . ($row->siswa_kelas ? $row->siswa_kelas : '-') . "</td>";
                echo "<td>" . $row->status_pengajuan . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    
    public function remove_duplicates() {
        echo "<h2>Removing Duplicate Data</h2>";
        
        // Remove duplicates based on NIS/NISN
        $this->db->query("
            DELETE s1 FROM tb_siswa s1
            INNER JOIN tb_siswa s2 
            WHERE s1.siswa_id > s2.siswa_id 
            AND (
                (s1.siswa_nis = s2.siswa_nis AND s1.siswa_nis IS NOT NULL) 
                OR 
                (s1.siswa_nisn = s2.siswa_nisn AND s1.siswa_nisn IS NOT NULL)
            )
        ");
        
        $affected = $this->db->affected_rows();
        echo "<p>Removed $affected duplicate records based on NIS/NISN</p>";
        
        // Add unique constraints if not exists
        $this->db->query("ALTER TABLE tb_siswa ADD UNIQUE KEY IF NOT EXISTS unique_siswa_nis (siswa_nis)");
        $this->db->query("ALTER TABLE tb_siswa ADD UNIQUE KEY IF NOT EXISTS unique_siswa_nisn (siswa_nisn)");
        
        echo "<p>Added unique constraints for NIS and NISN</p>";
        
        echo "<a href='" . site_url('analyze_data') . "'>Back to Analysis</a>";
    }
}