<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_email_fix extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
        $this->load->model('M_siswa');
    }
    
    public function index()
    {
        echo "<h2>Test Email Fix Verification</h2>";
        echo "<p>Testing email storage and retrieval functionality</p>";
        
        // Test 1: Create test student with email
        echo "<h3>Test 1: Create Test Student with Email</h3>";
        
        $test_username = 'test_email_' . time();
        $test_email = 'test_email_' . time() . '@example.com';
        $test_password = password_hash('test123', PASSWORD_DEFAULT);
        
        $user_data = array(
            'username' => $test_username,
            'password' => $test_password,
            'nama_lengkap' => 'Test Student Email',
            'email' => $test_email,
            'telepon' => '081234567899',
            'alamat' => 'Test Address Email',
            'level' => 2,
            'is_active' => 1,
            'user_code' => $this->M_user->generateRandomString(30)
        );
        
        $this->M_user->tambah_data_user($user_data);
        $user_id = $this->db->insert_id();
        
        if($user_id) {
            echo "<p style='color:green;'>✓ User created successfully with email: " . $test_email . "</p>";
            
            // Create student record
            $siswa_data = array(
                'user_id' => $user_id,
                'siswa_nama' => 'Test Student Email',
                'siswa_kelas' => 'XII - TKJ1',
                'siswa_jurusan' => 'Teknik Komputer dan Jaringan',
                'siswa_nis' => '9999999999',
                'siswa_code' => $this->M_siswa->generateRandomString(30)
            );
            
            $this->M_siswa->tambah_data_siswa($siswa_data);
            $siswa_id = $this->db->insert_id();
            
            echo "<p style='color:green;'>✓ Student record created with ID: " . $siswa_id . "</p>";
            
            // Test 2: Verify email storage in user table
            echo "<h3>Test 2: Verify Email Storage in User Table</h3>";
            
            $this->db->where('id', $user_id);
            $user_check = $this->db->get('tb_user')->row();
            
            if($user_check && $user_check->email == $test_email) {
                echo "<p style='color:green;'>✓ Email correctly stored in tb_user: " . $user_check->email . "</p>";
            } else {
                echo "<p style='color:red;'>✗ Email not stored correctly in tb_user</p>";
                if($user_check) {
                    echo "<p>Expected: " . $test_email . "</p>";
                    echo "<p>Actual: " . ($user_check->email ? $user_check->email : 'NULL') . "</p>";
                }
            }
            
            // Test 3: Verify no email in student table
            echo "<h3>Test 3: Verify No Email in Student Table</h3>";
            
            $this->db->where('siswa_id', $siswa_id);
            $siswa_check = $this->db->get('tb_siswa')->row();
            
            if($siswa_check) {
                $has_email_field = isset($siswa_check->email);
                if(!$has_email_field || empty($siswa_check->email)) {
                    echo "<p style='color:green;'>✓ No email field or empty email in tb_siswa (correct)</p>";
                } else {
                    echo "<p style='color:orange;'>⚠ Email found in tb_siswa but should not be there</p>";
                }
            }
            
            // Test 4: Get complete student data with email
            echo "<h3>Test 4: Get Complete Student Data with Email</h3>";
            
            $complete_data = $this->M_siswa->get_siswa_with_biodata_by_user_id($user_id);
            
            if($complete_data) {
                $user_email = isset($complete_data->user_email) ? $complete_data->user_email : (isset($complete_data->email) ? $complete_data->email : 'Not found');
                echo "<p style='color:green;'>✓ Email retrieved from user data: " . $user_email . "</p>";
                
                if($user_email == $test_email) {
                    echo "<p style='color:green;'>✓ Email matches expected value</p>";
                } else {
                    echo "<p style='color:red;'>✗ Email mismatch. Expected: " . $test_email . ", Got: " . $user_email . "</p>";
                }
            } else {
                echo "<p style='color:red;'>✗ Failed to retrieve complete student data</p>";
            }
            
            // Test 5: Update email via biodata update
            echo "<h3>Test 5: Update Email via Biodata Update</h3>";
            
            $new_email = 'updated_' . $test_email;
            
            $user_update = array(
                'email' => $new_email,
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            $this->M_user->update_user($user_update, $user_check->user_code);
            
            // Verify update
            $this->db->where('id', $user_id);
            $updated_user = $this->db->get('tb_user')->row();
            
            if($updated_user && $updated_user->email == $new_email) {
                echo "<p style='color:green;'>✓ Email successfully updated to: " . $new_email . "</p>";
            } else {
                echo "<p style='color:red;'>✗ Email update failed</p>";
            }
            
            // Test 6: Verify data retrieval after update
            echo "<h3>Test 6: Verify Data Retrieval After Update</h3>";
            
            $updated_data = $this->M_siswa->get_siswa_with_biodata_by_user_id($user_id);
            
            if($updated_data) {
                $retrieved_email = isset($updated_data->user_email) ? $updated_data->user_email : (isset($updated_data->email) ? $updated_data->email : 'Not found');
                if($retrieved_email == $new_email) {
                    echo "<p style='color:green;'>✓ Updated email correctly retrieved: " . $retrieved_email . "</p>";
                } else {
                    echo "<p style='color:red;'>✗ Email retrieval mismatch after update</p>";
                }
            }
            
            // Cleanup
            echo "<h3>Cleanup</h3>";
            $this->db->where('user_id', $user_id);
            $this->db->delete('tb_siswa');
            
            $this->db->where('id', $user_id);
            $this->db->delete('tb_user');
            
            echo "<p style='color:green;'>✓ Test data cleaned up</p>";
            
        } else {
            echo "<p style='color:red;'>✗ Failed to create test user</p>";
        }
        
        echo "<h3>Test Summary</h3>";
        echo "<p>If all tests show green ✓, the email functionality is working correctly.</p>";
        echo "<p><a href='" . base_url('hubin/view/tambah-siswa') . "'>Test tambah-siswa form</a></p>";
        echo "<p><a href='" . base_url('hubin/detail_biodata/O8qRQ8v0GP2EuYg586xUPFHqmksx7r') . "'>Test detail biodata page</a></p>";
    }
}