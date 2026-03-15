<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_nip_nim_removal extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
        $this->load->model('M_siswa');
    }
    
    public function index()
    {
        echo "<h2>Test NIP/NIM Removal Verification</h2>";
        echo "<p>Testing that NIP/NIM field has been completely removed from the system</p>";
        
        // Test 1: Check database structure
        echo "<h3>Test 1: Database Structure Check</h3>";
        
        $query = $this->db->query("DESCRIBE tb_user");
        $user_fields = $query->result_array();
        
        $nip_nim_exists = false;
        foreach($user_fields as $field) {
            if($field['Field'] == 'nip_nim') {
                $nip_nim_exists = true;
                break;
            }
        }
        
        if($nip_nim_exists) {
            echo "<p style='color:orange;'>⚠ NIP/NIM field still exists in database structure</p>";
            echo "<p>You may want to remove it from database if no longer needed:</p>";
            echo "<code>ALTER TABLE tb_user DROP COLUMN nip_nim;</code>";
        } else {
            echo "<p style='color:green;'>✓ NIP/NIM field removed from database structure</p>";
        }
        
        // Test 2: Create test user without NIP/NIM
        echo "<h3>Test 2: User Creation Without NIP/NIM</h3>";
        
        $test_username = 'test_no_nip_' . time();
        $test_email = 'test_no_nip_' . time() . '@example.com';
        $test_password = password_hash('test123', PASSWORD_DEFAULT);
        
        $user_data = array(
            'username' => $test_username,
            'password' => $test_password,
            'nama_lengkap' => 'Test User No NIP',
            'email' => $test_email,
            'telepon' => '081234567899',
            'alamat' => 'Test Address No NIP',
            'level' => 3, // Regular user level
            'is_active' => 1,
            'user_code' => $this->M_user->generateRandomString(30)
        );
        
        $this->M_user->tambah_data_user($user_data);
        $user_id = $this->db->insert_id();
        
        if($user_id) {
            echo "<p style='color:green;'>✓ User created successfully without NIP/NIM</p>";
            
            // Verify no NIP/NIM in user data
            $this->db->where('id', $user_id);
            $query = $this->db->get('tb_user');
            $user_check = $query->row_array();
            
            if($user_check) {
                if(!isset($user_check['nip_nim']) || empty($user_check['nip_nim'])) {
                    echo "<p style='color:green;'>✓ No NIP/NIM field or empty value in user record</p>";
                } else {
                    echo "<p style='color:orange;'>⚠ NIP/NIM still present in user record: " . $user_check['nip_nim'] . "</p>";
                }
            }
            
            // Cleanup
            $this->db->where('id', $user_id);
            $this->db->delete('tb_user');
            echo "<p style='color:green;'>✓ Test user cleaned up</p>";
        } else {
            echo "<p style='color:red;'>✗ Failed to create test user</p>";
        }
        
        // Test 3: Check form fields
        echo "<h3>Test 3: Form Field Verification</h3>";
        
        // Check if NIP/NIM fields still exist in forms
        $form_files = [
            'application/views/hubin/edit-biodata-siswa.php',
            'application/views/hubin/detail-biodata-siswa.php',
            'application/views/hubin/edit-biodata-lainnya.php',
            'application/views/hubin/detail-biodata-lainnya.php',
            'application/views/hubin/edit-biodata-pembimbing.php',
            'application/views/pembimbing/profile.php'
        ];
        
        $nip_nim_found = [];
        
        foreach($form_files as $file) {
            if(file_exists($file)) {
                $content = file_get_contents($file);
                if(stripos($content, 'nip_nim') !== false || stripos($content, 'NIP/NIM') !== false || stripos($content, 'NIP') !== false) {
                    $nip_nim_found[] = $file;
                }
            }
        }
        
        if(empty($nip_nim_found)) {
            echo "<p style='color:green;'>✓ No NIP/NIM fields found in form files</p>";
        } else {
            echo "<p style='color:orange;'>⚠ NIP/NIM references found in:</p>";
            foreach($nip_nim_found as $file) {
                echo "<p>- " . $file . "</p>";
            }
        }
        
        // Test 4: Check model functions
        echo "<h3>Test 4: Model Function Verification</h3>";
        
        // Check if nip_nim is still in valid fields
        $reflection = new ReflectionMethod('M_user', 'update_user_identity');
        $filename = $reflection->getFileName();
        $start_line = $reflection->getStartLine();
        $end_line = $reflection->getEndLine();
        
        $file_content = file($filename);
        $function_content = implode('', array_slice($file_content, $start_line - 1, $end_line - $start_line + 1));
        
        if(stripos($function_content, 'nip_nim') !== false) {
            echo "<p style='color:orange;'>⚠ NIP/NIM still referenced in M_user::update_user_identity</p>";
        } else {
            echo "<p style='color:green;'>✓ NIP/NIM removed from M_user::update_user_identity</p>";
        }
        
        // Test 5: Check controller validation
        echo "<h3>Test 5: Controller Validation Check</h3>";
        
        $controller_file = 'application/controllers/Hubin.php';
        if(file_exists($controller_file)) {
            $content = file_get_contents($controller_file);
            if(stripos($content, 'nip_nim_check') !== false) {
                echo "<p style='color:orange;'>⚠ nip_nim_check function still exists in Hubin controller</p>";
            } else {
                echo "<p style='color:green;'>✓ nip_nim_check function removed from Hubin controller</p>";
            }
        }
        
        echo "<h3>Test Summary</h3>";
        echo "<p>If most tests show green ✓, the NIP/NIM removal is successful.</p>";
        echo "<p>Some warnings (⚠) may appear if database field still exists - this can be removed manually if needed.</p>";
        echo "<p><a href='" . base_url('hubin/view/tambah-siswa') . "'>Test tambah-siswa form</a></p>";
        echo "<p><a href='" . base_url('hubin/detail_biodata/O8qRQ8v0GP2EuYg586xUPFHqmksx7r') . "'>Test detail biodata page</a></p>";
    }
}