<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_db extends CI_Controller {
    
    public function index()
    {
        echo "<h2>Database Structure Debug</h2>";
        
        // Check tb_user structure
        echo "<h3>tb_user table structure:</h3>";
        $fields = $this->db->field_data('tb_user');
        echo "<ul>";
        foreach ($fields as $field) {
            echo "<li>" . $field->name . " - " . $field->type . " - " . ($field->max_length ? $field->max_length : 'N/A') . "</li>";
        }
        echo "</ul>";
        
        // Check tb_siswa structure
        echo "<h3>tb_siswa table structure:</h3>";
        $siswa_fields = $this->db->field_data('tb_siswa');
        echo "<ul>";
        foreach ($siswa_fields as $field) {
            echo "<li>" . $field->name . " - " . $field->type . " - " . ($field->max_length ? $field->max_length : 'N/A') . "</li>";
        }
        echo "</ul>";
        
        // Show sample data
        echo "<h3>Sample user data:</h3>";
        $user = $this->db->get('tb_user', 1)->row();
        if ($user) {
            echo "<pre>";
            print_r($user);
            echo "</pre>";
        } else {
            echo "<p>No user data found</p>";
        }
    }
}