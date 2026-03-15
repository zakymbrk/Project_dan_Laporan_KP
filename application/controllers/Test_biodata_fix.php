<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_biodata_fix extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Only allow access in development environment
        if(ENVIRONMENT !== 'development') {
            show_404();
        }
        $this->load->model('M_user');
        $this->load->model('M_siswa');
    }
    
    public function index()
    {
        echo "<h1>Test Biodata Fix</h1>";
        echo "<p>Testing the biodata functionality fix for jenis kelamin, email, and sekolah fields.</p>";
        
        echo "<h2>Current Database Structure</h2>";
        
        // Check tb_user structure
        echo "<h3>tb_user table structure:</h3>";
        $user_fields = $this->db->field_data('tb_user');
        echo "<ul>";
        foreach ($user_fields as $field) {
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
        
        // Show recent student data with biodata
        echo "<h2>Recent Student Data Test</h2>";
        $this->db->select('tb_user.*, tb_siswa.*');
        $this->db->from('tb_user');
        $this->db->join('tb_siswa', 'tb_siswa.user_id = tb_user.id', 'left');
        $this->db->where('tb_user.level', 2);
        $this->db->order_by('tb_user.created_at', 'DESC');
        $this->db->limit(5);
        $recent_data = $this->db->get()->result();
        
        if(!empty($recent_data)) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Username</th><th>Nama</th><th>Email</th><th>Jenis Kelamin</th><th>Telepon</th><th>Sekolah</th><th>Created At</th></tr>";
            foreach($recent_data as $data) {
                echo "<tr>";
                echo "<td>" . $data->username . "</td>";
                echo "<td>" . $data->nama_lengkap . "</td>";
                echo "<td>" . ($data->email ? $data->email : 'NULL') . "</td>";
                echo "<td>" . ($data->jenis_kelamin ? $data->jenis_kelamin : 'NULL') . "</td>";
                echo "<td>" . ($data->telepon ? $data->telepon : 'NULL') . "</td>";
                echo "<td>" . ($data->siswa_asal_sekolah ? $data->siswa_asal_sekolah : 'NULL') . "</td>";
                echo "<td>" . $data->created_at . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No recent student data found.</p>";
        }
        
        echo "<h2>Test Links</h2>";
        echo "<a href='" . base_url('hubin/view/tambah-siswa') . "'>Test Tambah Siswa Form</a><br>";
        echo "<a href='" . base_url('hubin/view/daftar-siswa') . "'>View Daftar Siswa</a><br>";
        echo "<a href='" . base_url('test_import_fix') . "'>Test Import Fix</a><br>";
    }
    
    // Test function to create a sample student with all biodata fields
    public function create_test_student()
    {
        // Create test user
        $user_data = array(
            'user_code' => $this->M_user->generateRandomString(30),
            'username' => 'test_student_' . time(),
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'nama_lengkap' => 'Test Student Biodata',
            'email' => 'test@student.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2005-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'level' => 2,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('tb_user', $user_data);
        $user_id = $this->db->insert_id();
        
        if($user_id) {
            // Create corresponding student data
            $siswa_data = array(
                'user_id' => $user_id,
                'siswa_nama' => 'Test Student Biodata',
                'siswa_nis' => '9988776655',
                'siswa_nisn' => '1122334455',
                'siswa_jk' => 'L',
                'siswa_tempat_lahir' => 'Bandung',
                'siswa_tanggal_lahir' => '2005-05-15',
                'siswa_kelas' => 'XI RPL 1',
                'siswa_jurusan' => 'Rekayasa Perangkat Lunak',
                'siswa_telepon' => '081234567890',
                'siswa_alamat' => 'Jl. Test No. 123',
                'siswa_asal_sekolah' => 'SMK ITIKURIH HIBARNA',
                'status_pengajuan' => 'draft',
                'siswa_code' => $this->M_siswa->generateRandomString(30),
                'created_at' => date('Y-m-d H:i:s')
            );
            
            $this->db->insert('tb_siswa', $siswa_data);
            
            if($this->db->affected_rows() > 0) {
                echo "<div class='alert alert-success'>Test student created successfully!</div>";
                echo "<p>Username: " . $user_data['username'] . "</p>";
                echo "<p>Password: password123</p>";
                echo "<p>You can now test the biodata functionality with this account.</p>";
            } else {
                echo "<div class='alert alert-danger'>Failed to create student data.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to create user account.</div>";
        }
        
        echo "<br><a href='" . base_url('test_biodata_fix') . "'>Back to Test</a>";
    }
}