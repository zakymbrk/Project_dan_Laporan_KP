<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_constraints extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function add_unique_constraints()
    {
        echo "<h3>Adding Unique Constraints to Database</h3>";
        
        // Add unique constraint to tb_user.username
        $this->add_unique_constraint('tb_user', 'username', 'unique_username');
        
        // Add unique constraint to tb_user.email
        $this->add_unique_constraint('tb_user', 'email', 'unique_email');
        
        // Add unique constraint to tb_user.nip_nim
        $this->add_unique_constraint('tb_user', 'nip_nim', 'unique_nip_nim');
        
        // Add unique constraint to tb_dudi.dudi_nama
        $this->add_unique_constraint('tb_dudi', 'dudi_nama', 'unique_dudi_nama');
        
        // Add unique constraint to tb_dudi.dudi_email
        $this->add_unique_constraint('tb_dudi', 'dudi_email', 'unique_dudi_email');
        
        // Add unique constraint to tb_siswa.siswa_nis
        $this->add_unique_constraint('tb_siswa', 'siswa_nis', 'unique_siswa_nis');
        
        // Add unique constraint to tb_siswa.siswa_nisn
        $this->add_unique_constraint('tb_siswa', 'siswa_nisn', 'unique_siswa_nisn');
        
        echo "<h4>All unique constraints have been added successfully!</h4>";
    }
    
    private function add_unique_constraint($table, $column, $constraint_name)
    {
        // Check if constraint already exists
        $query = $this->db->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                                  WHERE TABLE_SCHEMA = DATABASE() 
                                  AND TABLE_NAME = '$table' 
                                  AND CONSTRAINT_NAME = '$constraint_name'");
        
        $result = $query->row();
        
        if($result->count == 0) {
            // Add the unique constraint
            $this->db->query("ALTER TABLE `$table` ADD UNIQUE `$constraint_name` (`$column`)");
            echo "✓ Added unique constraint '$constraint_name' to '$table.$column'<br>";
        } else {
            echo "✓ Unique constraint '$constraint_name' already exists on '$table.$column'<br>";
        }
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
    
    public function cleanup_all()
    {
        echo "<h2>Database Cleanup and Constraint Addition</h2>";
        
        echo "<h3>Step 1: Removing Duplicate Users</h3>";
        $this->remove_duplicate_users();
        
        echo "<h3>Step 2: Adding Unique Constraints</h3>";
        $this->add_unique_constraints();
        
        echo "<h3>Complete!</h3>";
        echo "<p>Database is now protected against duplicate entries.</p>";
    }
}