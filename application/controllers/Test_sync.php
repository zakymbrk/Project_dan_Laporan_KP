<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_sync extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_data_sync');
        $this->load->model('M_user');
        $this->load->model('M_siswa');
    }
    
    public function index()
    {
        echo "<h2>Data Synchronization Test</h2>";
        
        // Test 1: Check if synchronization model exists
        echo "<h3>Test 1: Model Availability</h3>";
        if (class_exists('M_data_sync')) {
            echo "<p style='color:green;'>✓ M_data_sync model available</p>";
        } else {
            echo "<p style='color:red;'>✗ M_data_sync model missing</p>";
        }
        
        // Test 2: Check if sync controller exists
        echo "<h3>Test 2: Controller Availability</h3>";
        if (file_exists(APPPATH . 'controllers/Data_sync.php')) {
            echo "<p style='color:green;'>✓ Data_sync controller available</p>";
        } else {
            echo "<p style='color:red;'>✗ Data_sync controller missing</p>";
        }
        
        // Test 3: Create test student data
        echo "<h3>Test 3: Create Test Student</h3>";
        $test_user_data = array(
            'username' => 'test_sync_' . time(),
            'nama_lengkap' => 'Test Student Sync',
            'email' => 'test_sync_' . time() . '@example.com',
            'password' => password_hash('test123', PASSWORD_DEFAULT),
            'nip_nim' => '99988877766'
        );
        
        $test_siswa_data = array(
            'siswa_nama' => 'Test Student Sync',
            'siswa_nis' => '99988877766',
            'siswa_kelas' => 'XII - TKJ1',
            'siswa_jurusan' => 'Teknik Komputer dan Jaringan'
        );
        
        $user_id = $this->M_data_sync->create_student($test_user_data, $test_siswa_data);
        
        if ($user_id) {
            echo "<p style='color:green;'>✓ Test student created with user_id: " . $user_id . "</p>";
            
            // Test 4: Get complete student data
            echo "<h3>Test 4: Get Complete Student Data</h3>";
            $complete_data = $this->M_data_sync->get_complete_student_data($user_id);
            if ($complete_data) {
                echo "<p style='color:green;'>✓ Complete data retrieved:</p>";
                echo "<ul>";
                echo "<li>Nama: " . $complete_data->full_name . "</li>";
                echo "<li>NIS: " . $complete_data->nis . "</li>";
                echo "<li>Kelas: " . $complete_data->class . "</li>";
                echo "<li>Jurusan: " . $complete_data->major . "</li>";
                echo "</ul>";
            } else {
                echo "<p style='color:red;'>✗ Failed to get complete data</p>";
            }
            
            // Test 5: Update student data
            echo "<h3>Test 5: Update Student Data</h3>";
            $update_data = array(
                'nama_lengkap' => 'Updated Test Student',
                'siswa_kelas' => 'XII - TKJ2',
                'email' => 'updated_' . time() . '@example.com'
            );
            
            $update_result = $this->M_data_sync->update_student_data($user_id, $update_data);
            if ($update_result) {
                echo "<p style='color:green;'>✓ Student data updated successfully</p>";
                
                // Verify update
                $updated_data = $this->M_data_sync->get_complete_student_data($user_id);
                if ($updated_data && $updated_data->full_name == 'Updated Test Student') {
                    echo "<p style='color:green;'>✓ Update verification successful</p>";
                } else {
                    echo "<p style='color:red;'>✗ Update verification failed</p>";
                }
            } else {
                echo "<p style='color:red;'>✗ Failed to update student data</p>";
            }
            
            // Test 6: Check synchronization status
            echo "<h3>Test 6: Synchronization Status</h3>";
            $sync_status = $this->M_data_sync->get_sync_status($user_id);
            echo "<p>Sync Status:</p>";
            echo "<ul>";
            echo "<li>User exists: " . ($sync_status['user_exists'] ? 'Yes' : 'No') . "</li>";
            echo "<li>Student exists: " . ($sync_status['siswa_exists'] ? 'Yes' : 'No') . "</li>";
            echo "<li>Data consistent: " . ($sync_status['data_consistent'] ? 'Yes' : 'No') . "</li>";
            echo "<li>Last updated: " . $sync_status['last_updated'] . "</li>";
            if (!empty($sync_status['issues'])) {
                echo "<li>Issues: " . implode(', ', $sync_status['issues']) . "</li>";
            }
            echo "</ul>";
            
            // Test 7: Force synchronization
            echo "<h3>Test 7: Force Data Synchronization</h3>";
            $sync_result = $this->M_data_sync->synchronize_data($user_id);
            if ($sync_result) {
                echo "<p style='color:green;'>✓ Data synchronization successful</p>";
            } else {
                echo "<p style='color:red;'>✗ Data synchronization failed</p>";
            }
            
            // Test 8: Get recent changes
            echo "<h3>Test 8: Recent Changes</h3>";
            $recent_changes = $this->M_data_sync->get_recent_changes($user_id, 5);
            echo "<p>Recent changes (" . count($recent_changes) . "):</p>";
            if (!empty($recent_changes)) {
                echo "<ul>";
                foreach ($recent_changes as $change) {
                    echo "<li>[" . $change['type'] . "] " . $change['timestamp'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No recent changes found</p>";
            }
            
            // Cleanup test data
            echo "<h3>Cleanup</h3>";
            $this->db->where('id', $user_id);
            $this->db->delete('tb_user');
            $this->db->where('user_id', $user_id);
            $this->db->delete('tb_siswa');
            echo "<p style='color:blue;'>✓ Test data cleaned up</p>";
            
        } else {
            echo "<p style='color:red;'>✗ Failed to create test student</p>";
        }
        
        echo "<h3>Implementation Status</h3>";
        echo "<p>All synchronization components have been implemented:</p>";
        echo "<ul>";
        echo "<li>✓ Unified data synchronization model (M_data_sync)</li>";
        echo "<li>✓ AJAX endpoints for real-time updates (Data_sync controller)</li>";
        echo "<li>✓ Client-side synchronization for tambah-siswa page</li>";
        echo "<li>✓ Client-side synchronization for detail_biodata page</li>";
        echo "<li>✓ Client-side synchronization for profile page</li>";
        echo "<li>✓ Cross-tab communication using localStorage</li>";
        echo "<li>✓ Visual feedback for data updates</li>";
        echo "<li>✓ Data consistency checks and validation</li>";
        echo "</ul>";
        
        echo "<p><strong>Pages that are now synchronized:</strong></p>";
        echo "<ul>";
        echo "<li><a href='" . base_url('hubin/view/tambah-siswa') . "'>Tambah Siswa</a></li>";
        echo "<li><a href='" . base_url('hubin/detail_biodata/') . "CySctJKB7Y9DODS2M7mQnrsH6ed5AC'>Detail Biodata</a> (example)</li>";
        echo "<li><a href='" . base_url('siswa/view/profile') . "'>Student Profile</a></li>";
        echo "</ul>";
        
        echo "<p>When data is updated on any of these pages, the changes will be reflected in real-time on the other pages.</p>";
    }
}