<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_cleanup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        echo "<h1>Database Cleanup Tool</h1>";
        echo "<p>This tool will remove unused fields from tb_siswa table that are not used in the application.</p>";
        echo "<a href='" . site_url('database_cleanup/cleanup_siswa_table') . "' class='btn btn-danger'>Start Cleanup</a>";
        echo " | <a href='" . site_url('database_cleanup/analyze_fields') . "'>Analyze Fields</a>";
    }

    public function analyze_fields() {
        echo "<h2>Field Usage Analysis</h2>";
        
        // Get all fields in tb_siswa
        $fields = $this->db->field_data('tb_siswa');
        $used_fields = $this->get_used_fields();
        $unused_fields = array();
        
        echo "<h3>All Fields in tb_siswa:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field Name</th><th>Type</th><th>Used in App</th><th>Status</th></tr>";
        
        foreach($fields as $field) {
            $is_used = in_array($field->name, $used_fields);
            $status = $is_used ? '<span style="color:green;">USED</span>' : '<span style="color:red;">UNUSED</span>';
            
            if(!$is_used) {
                $unused_fields[] = $field->name;
            }
            
            echo "<tr>";
            echo "<td>" . $field->name . "</td>";
            echo "<td>" . $field->type . "(" . $field->max_length . ")</td>";
            echo "<td>" . ($is_used ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $status . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Unused Fields (" . count($unused_fields) . "):</h3>";
        echo "<ul>";
        foreach($unused_fields as $field) {
            echo "<li>" . $field . "</li>";
        }
        echo "</ul>";
        
        if(!empty($unused_fields)) {
            echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0;'>";
            echo "<h4>Warning!</h4>";
            echo "<p>The following fields will be removed from the database:</p>";
            echo "<p><strong>" . implode(', ', $unused_fields) . "</strong></p>";
            echo "<p><a href='" . site_url('database_cleanup/cleanup_siswa_table') . "' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Confirm Removal</a></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0;'>";
            echo "<p style='color: #155724;'><strong>All fields are currently in use. No cleanup needed.</strong></p>";
            echo "</div>";
        }
    }

    private function get_used_fields() {
        // Fields that are actually used in the application
        return array(
            // Core student fields
            'siswa_id',
            'siswa_code',
            'user_id',
            'dudi_id',
            'pembimbing_id',
            'siswa_nama',
            'siswa_kelas',
            'siswa_jurusan',
            'siswa_nis',
            'siswa_nisn',
            'siswa_jk',
            'siswa_tempat_lahir',
            'siswa_tanggal_lahir',
            'siswa_alamat',
            'siswa_no_hp',
            'siswa_asal_sekolah',
            'siswa_telepon',
            'status_pengajuan',
            'tanggal_mulai',
            'tanggal_selesai',
            'lama_pelaksanaan',
            'keterangan',
            'is_active',
            'created_at',
            'updated_at',
            'surat_permohonan',
            'surat_balasan',
            'periode',
            'other_dudi_nama'
        );
    }

    public function cleanup_siswa_table() {
        echo "<h2>Cleaning Up tb_siswa Table</h2>";
        
        $unused_fields = $this->get_unused_fields();
        
        if(empty($unused_fields)) {
            echo "<p style='color: green;'><strong>All fields are in use. No cleanup needed.</strong></p>";
            return;
        }
        
        echo "<p>Removing the following unused fields:</p>";
        echo "<ul>";
        foreach($unused_fields as $field) {
            echo "<li>" . $field . "</li>";
        }
        echo "</ul>";
        
        // Remove unused fields
        foreach($unused_fields as $field) {
            $this->db->query("ALTER TABLE `tb_siswa` DROP COLUMN `{$field}`");
            echo "✓ Removed field: {$field}<br>";
        }
        
        echo "<h3 style='color: green;'>✓ Cleanup completed successfully!</h3>";
        echo "<p><a href='" . site_url('database_cleanup/analyze_fields') . "'>View Analysis</a></p>";
    }

    private function get_unused_fields() {
        $all_fields = $this->db->field_data('tb_siswa');
        $used_fields = $this->get_used_fields();
        $unused_fields = array();
        
        foreach($all_fields as $field) {
            if(!in_array($field->name, $used_fields)) {
                $unused_fields[] = $field->name;
            }
        }
        
        return $unused_fields;
    }

    public function run_complete_cleanup() {
        echo "<h1>Complete Database Cleanup</h1>";
        
        echo "<h3>1. Analyzing tb_siswa fields...</h3>";
        $this->analyze_fields();
        
        echo "<hr>";
        
        echo "<h3>2. Cleaning up unused fields...</h3>";
        $this->cleanup_siswa_table();
        
        echo "<hr>";
        
        echo "<h3>3. Running database synchronization...</h3>";
        $this->load->controller('database_sync');
        $this->database_sync->run_complete_sync();
        
        echo "<h2 style='color: green;'>🎉 Complete cleanup finished!</h2>";
    }
}