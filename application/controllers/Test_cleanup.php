<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_cleanup extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function cleanup_and_setup()
    {
        echo "Cleaning up duplicate users...\n";
        
        // Delete duplicate siswa1 users (keep only the latest one)
        $this->db->where('username', 'siswa1');
        $users = $this->db->get('tb_user')->result();
        
        if(count($users) > 1) {
            // Keep the user with highest ID
            $keep_id = 0;
            foreach($users as $user) {
                if($user->id > $keep_id) {
                    $keep_id = $user->id;
                }
            }
            
            // Delete all except the one to keep
            $this->db->where('username', 'siswa1');
            $this->db->where('id !=', $keep_id);
            $this->db->delete('tb_user');
            
            echo "Deleted duplicate siswa1 users, kept user ID: $keep_id\n";
        }
        
        // Update the password for siswa1 to ensure it's correct
        $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
        $this->db->where('username', 'siswa1');
        $this->db->update('tb_user', array('password' => $hashed_password));
        
        echo "Updated password for siswa1\n";
        
        // Verify the setup
        $this->db->where('username', 'siswa1');
        $user = $this->db->get('tb_user')->row();
        
        if($user && password_verify('password123', $user->password)) {
            echo "Setup successful! User 'siswa1' is ready for testing.\n";
            echo "Username: siswa1\n";
            echo "Password: password123\n";
        } else {
            echo "Setup failed!\n";
        }
    }
}