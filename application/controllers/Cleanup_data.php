<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cleanup_data extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function remove_duplicate_users()
    {
        echo "<h3>Removing Duplicate Users</h3>";
        
        // Find and remove duplicate users (keep the one with highest ID)
        $duplicates = $this->db->query("
            SELECT username, COUNT(*) as count 
            FROM tb_user 
            GROUP BY username 
            HAVING COUNT(*) > 1
        ")->result();
        
        $removed_count = 0;
        
        foreach($duplicates as $duplicate) {
            // Get all users with this username, ordered by ID descending
            $users = $this->db->query("
                SELECT id, username 
                FROM tb_user 
                WHERE username = ? 
                ORDER BY id DESC
            ", array($duplicate->username))->result();
            
            // Keep the first one (highest ID), delete the rest
            for($i = 1; $i < count($users); $i++) {
                $this->db->where('id', $users[$i]->id);
                $this->db->delete('tb_user');
                $removed_count++;
                echo "Deleted duplicate user: {$users[$i]->username} (ID: {$users[$i]->id})<br>";
            }
        }
        
        echo "<br>✓ Removed $removed_count duplicate users<br>";
    }
    
    public function cleanup_student_addresses()
    {
        echo "<h3>Cleaning up Student Address Data</h3>";
        
        // Remove duplicate alamat fields from tb_siswa
        // This will standardize the address field usage
        $updated_count = 0;
        
        // Get all students with both alamat and siswa_alamat fields
        $students = $this->db->query("
            SELECT siswa_id, siswa_alamat, alamat 
            FROM tb_siswa 
            WHERE siswa_alamat IS NOT NULL OR alamat IS NOT NULL
        ")->result();
        
        foreach($students as $student) {
            // If siswa_alamat exists and alamat doesn't, move it to alamat
            if(!empty($student->siswa_alamat) && empty($student->alamat)) {
                $this->db->where('siswa_id', $student->siswa_id);
                $this->db->update('tb_siswa', array('alamat' => $student->siswa_alamat));
                $updated_count++;
                echo "Updated student ID {$student->siswa_id}: moved siswa_alamat to alamat<br>";
            }
            // If both exist, keep the non-empty one in alamat
            elseif(!empty($student->siswa_alamat) && !empty($student->alamat)) {
                $preferred_address = !empty($student->alamat) ? $student->alamat : $student->siswa_alamat;
                $this->db->where('siswa_id', $student->siswa_id);
                $this->db->update('tb_siswa', array('alamat' => $preferred_address));
                $updated_count++;
                echo "Updated student ID {$student->siswa_id}: consolidated address data<br>";
            }
        }
        
        echo "<br>✓ Updated $updated_count student records<br>";
    }
    
    public function cleanup_all()
    {
        echo "<h2>Data Cleanup Operations</h2>";
        
        echo "<h3>Step 1: Removing Duplicate Users</h3>";
        $this->remove_duplicate_users();
        
        echo "<h3>Step 2: Cleaning up Student Address Data</h3>";
        $this->cleanup_student_addresses();
        
        echo "<h3>Complete!</h3>";
        echo "<p>Data cleanup has been completed successfully.</p>";
    }
}