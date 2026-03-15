<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_import_fix extends CI_Controller {

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
        echo "<h1>Test Import Fix</h1>";
        echo "<p>Testing the import functionality fix for email, jenis kelamin, and sekolah fields.</p>";
        
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
        
        // Show recent imported data
        echo "<h2>Recent Imported Data Test</h2>";
        $recent_users = $this->db->where('level', 2)->order_by('created_at', 'DESC')->limit(5)->get('tb_user')->result();
        
        if(!empty($recent_users)) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Username</th><th>Nama Lengkap</th><th>Email</th><th>Jenis Kelamin</th><th>Telepon</th><th>Created At</th></tr>";
            foreach($recent_users as $user) {
                echo "<tr>";
                echo "<td>" . $user->username . "</td>";
                echo "<td>" . $user->nama_lengkap . "</td>";
                echo "<td>" . ($user->email ? $user->email : 'NULL') . "</td>";
                echo "<td>" . ($user->jenis_kelamin ? $user->jenis_kelamin : 'NULL') . "</td>";
                echo "<td>" . ($user->telepon ? $user->telepon : 'NULL') . "</td>";
                echo "<td>" . $user->created_at . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Show corresponding siswa data
            echo "<h3>Corresponding Siswa Data:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Siswa ID</th><th>Nama Siswa</th><th>NIS</th><th>Kelas</th><th>Jurusan</th><th>Status</th></tr>";
            foreach($recent_users as $user) {
                $siswa = $this->db->where('user_id', $user->id)->get('tb_siswa')->row();
                if($siswa) {
                    echo "<tr>";
                    echo "<td>" . $siswa->siswa_id . "</td>";
                    echo "<td>" . $siswa->siswa_nama . "</td>";
                    echo "<td>" . ($siswa->siswa_nis ? $siswa->siswa_nis : 'NULL') . "</td>";
                    echo "<td>" . $siswa->siswa_kelas . "</td>";
                    echo "<td>" . ($siswa->siswa_jurusan ? $siswa->siswa_jurusan : 'NULL') . "</td>";
                    echo "<td>" . $siswa->status_pengajuan . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        } else {
            echo "<p>No recent student data found.</p>";
        }
        
        echo "<h2>Test Links</h2>";
        echo "<a href='" . base_url('test_import_fix/generate_test_template') . "'>Generate Test Template</a><br>";
        echo "<a href='" . base_url('hubin/view/daftar-siswa') . "'>Back to Daftar Siswa</a>";
    }
    
    public function generate_test_template()
    {
        // Generate a test template with sample data
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            show_error('Library PhpSpreadsheet tidak ditemukan.');
        }
        
        require_once $autoload_path;
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Define headers (20 kolom sesuai template yang benar)
        $headers = [
            'Nama Siswa *',
            'NIS',
            'NISN',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Kelas *',
            'Jurusan',
            'Sekolah',
            'Telepon',
            'Username *',
            'Password *',
            'Email',
            'Alamat',
            'Perusahaan DUDI',
            'Tanggal Mulai (YYYY-MM-DD)',
            'Tanggal Selesai (YYYY-MM-DD)',
            'Lama Pelaksanaan (hari)',
            'Status Pengajuan',
            'Periode PKL'
        ];
        
        // Fill header row
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Add test data
        $test_data = [
            'Ahmad Test Siswa',
            '9988776655',
            '1122334455',
            'L',
            'Bandung',
            '2005-05-15',
            'XI RPL 2',
            'Rekayasa Perangkat Lunak',
            'SMK ITIKURIH HIBARNA',
            '08111111111',
            'test_siswa1',
            'password123',
            'ahmad@test.com',
            'Jl. Test No. 123',
            'PT Test Company',
            '2024-07-01',
            '2024-09-30',
            '90',
            'menunggu',
            '2026/2027'
        ];
        
        $col = 'A';
        foreach($test_data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        // Style header row
        $style_header = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3498db']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
        
        $sheet->getStyle('A1:T1')->applyFromArray($style_header);
        
        // Auto-size columns
        foreach(range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'test-import-template-' . date("YmdHis") . '.xlsx';
        
        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Send headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Output file
        $writer->save('php://output');
        exit;
    }
}