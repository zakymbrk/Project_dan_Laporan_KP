<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_import extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_siswa');
        $this->load->model('M_user');
        $this->load->model('M_dudi');
    }

    public function index()
    {
        echo "<h1>Import Test</h1>";
        echo "<p>This is a test page to debug import functionality.</p>";
        echo "<a href='" . base_url('test_import/test_upload') . "'>Test File Upload</a><br>";
        echo "<a href='" . base_url('test_import/test_import') . "'>Test Import Process</a><br>";
        echo "<a href='" . base_url('hubin/view/daftar-siswa') . "'>Back to Daftar Siswa</a>";
    }

    public function test_upload()
    {
        echo "<h1>File Upload Test</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file_excel'])) {
                echo "<p>File uploaded successfully!</p>";
                echo "<p>File name: " . $_FILES['file_excel']['name'] . "</p>";
                echo "<p>File size: " . $_FILES['file_excel']['size'] . " bytes</p>";
                echo "<p>File type: " . $_FILES['file_excel']['type'] . "</p>";
                echo "<p>Temp file: " . $_FILES['file_excel']['tmp_name'] . "</p>";
                
                // Check if file was moved to the uploads directory
                $config['upload_path'] = './uploads/import/';
                $config['allowed_types'] = 'xlsx|xls|csv';
                $config['max_size'] = 5120; // 5MB
                $config['file_name'] = 'test_import_' . time();
                
                if(!is_dir($config['upload_path'])){
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('file_excel')) {
                    echo "<p style='color: red;'>Upload Error: " . $this->upload->display_errors() . "</p>";
                } else {
                    echo "<p style='color: green;'>Upload successful!</p>";
                    $upload_data = $this->upload->data();
                    echo "<p>Uploaded file path: " . $upload_data['full_path'] . "</p>";
                    
                    // Test reading the file
                    $autoload_path = FCPATH . 'vendor/autoload.php';
                    if (file_exists($autoload_path)) {
                        require_once $autoload_path;
                        try {
                            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($upload_data['full_path']);
                            $worksheet = $spreadsheet->getActiveSheet();
                            $rows = $worksheet->toArray();
                            echo "<p style='color: green;'>File read successfully! Total rows: " . count($rows) . "</p>";
                            
                            // Show first few rows
                            echo "<h3>First 3 rows of data:</h3>";
                            echo "<pre>";
                            for($i = 0; $i < min(3, count($rows)); $i++) {
                                echo "Row " . ($i + 1) . ": " . json_encode($rows[$i]) . "\n";
                            }
                            echo "</pre>";
                            
                            // Clean up
                            unlink($upload_data['full_path']);
                        } catch (Exception $e) {
                            echo "<p style='color: red;'>Error reading file: " . $e->getMessage() . "</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>Autoload file not found!</p>";
                    }
                }
            } else {
                echo "<p style='color: red;'>No file uploaded!</p>";
            }
        } else {
            echo "<form method='post' enctype='multipart/form-data'>";
            echo "<input type='file' name='file_excel' accept='.xlsx,.xls,.csv' required>";
            echo "<input type='submit' value='Upload Test'>";
            echo "</form>";
        }
        
        echo "<p><a href='" . base_url('test_import') . "'>Back to test menu</a></p>";
    }

    public function test_import()
    {
        echo "<h1>Import Process Test</h1>";
        echo "<p>Testing the import process without actual file upload.</p>";
        
        // Test library loading
        $autoload_path = FCPATH . 'vendor/autoload.php';
        echo "<p>Checking library: " . $autoload_path . "</p>";
        
        if (!file_exists($autoload_path)) {
            echo "<p style='color: red;'>Library not found!</p>";
            return;
        }
        
        require_once $autoload_path;
        echo "<p style='color: green;'>Library loaded successfully</p>";
        
        // Test database connection
        if ($this->db->conn_id) {
            echo "<p style='color: green;'>Database connection active</p>";
        } else {
            echo "<p style='color: red;'>Database connection failed</p>";
        }
        
        // Test models
        echo "<p>Testing models...</p>";
        echo "<p>M_siswa: " . (isset($this->M_siswa) ? "Available" : "Not available") . "</p>";
        echo "<p>M_user: " . (isset($this->M_user) ? "Available" : "Not available") . "</p>";
        echo "<p>M_dudi: " . (isset($this->M_dudi) ? "Available" : "Not available") . "</p>";
        
        // Test user permission check
        $user_data = $this->session->userdata('userdata');
        if ($user_data) {
            echo "<p style='color: green;'>User session found: Level " . $user_data['level'] . "</p>";
        } else {
            echo "<p style='color: orange;'>No user session. Testing with Hubin level (1) manually.</p>";
        }
        
        echo "<p><a href='" . base_url('test_import') . "'>Back to test menu</a></p>";
    }
}