<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_export extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_siswa');
        $this->load->model('M_user');
        $this->load->model('M_dudi');
    }

    public function index()
    {
        echo "<h1>Export/Import Test Suite</h1>";
        echo "<ul>";
        echo "<li><a href='" . base_url('test_export/test_export_xlsx') . "'>Test XLSX Export</a> - Download XLSX template</li>";
        echo "<li><a href='" . base_url('test_export/test_export_csv') . "'>Test CSV Export</a> - Download CSV template</li>";
        echo "<li><a href='" . base_url('test_export/test_import_debug') . "'>Test Import Debug</a> - Debug import functionality</li>";
        echo "</ul>";
    }

    public function test_import_debug()
    {
        echo "<h1>Import Debug Test</h1>";
        
        // Check if library exists
        $autoload_path = FCPATH . 'vendor/autoload.php';
        echo "<p>Checking autoload path: " . $autoload_path . "</p>";
        
        if (!file_exists($autoload_path)) {
            echo "<p style='color: red;'>ERROR: Autoload file not found!</p>";
            return;
        } else {
            echo "<p style='color: green;'>SUCCESS: Autoload file found</p>";
        }
        
        // Try to load the library
        try {
            require_once $autoload_path;
            echo "<p style='color: green;'>SUCCESS: Library loaded successfully</p>";
            
            // Test if we can create a spreadsheet object
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            echo "<p style='color: green;'>SUCCESS: Spreadsheet object created</p>";
            
            // Test if we can create a worksheet
            $worksheet = $spreadsheet->getActiveSheet();
            echo "<p style='color: green;'>SUCCESS: Worksheet created</p>";
            
            // Test basic functionality
            $worksheet->setCellValue('A1', 'Test Data');
            $value = $worksheet->getCell('A1')->getValue();
            echo "<p style='color: green;'>SUCCESS: Basic cell operations working. Value: " . $value . "</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
        }
        
        echo "<p><a href='" . base_url('test_export') . "'>Back to test suite</a></p>";
    }

    public function test_export_xlsx()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Ambil data user siswa (level 2) dan join dengan data siswa dan user detail
        $this->db->select('
            tb_user.username, 
            tb_user.nama_lengkap, 
            tb_user.email as user_email,
            tb_user.telepon as user_telepon,
            tb_user.alamat as user_alamat,
            tb_user.tempat_lahir,
            tb_user.tanggal_lahir,
            tb_user.jenis_kelamin,
            tb_siswa.siswa_nis,
            tb_siswa.siswa_nisn,
            tb_siswa.siswa_kelas,
            tb_siswa.siswa_jurusan,
            tb_siswa.siswa_telepon,
            tb_siswa.siswa_alamat,
            tb_siswa.periode,
            tb_siswa.tanggal_mulai,
            tb_siswa.tanggal_selesai,
            tb_siswa.status_pengajuan,
            tb_dudi.dudi_nama
        ');
        $this->db->from('tb_user');
        $this->db->join('tb_siswa', 'tb_siswa.user_id = tb_user.id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->where('tb_user.level', 2);
        $this->db->order_by('tb_user.id', 'ASC');
        $siswa = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Username',
            'Nama Lengkap', 
            'Password',
            'NIS',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas',
            'Jurusan',
            'Telepon',
            'Alamat',
            'Email',
            'Periode',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Nama DUDI',
            'Status Pengajuan'
        ];
        
        // Fill header row
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row = 2;
        foreach($siswa->result() as $data) {
            $sheet->setCellValue('A' . $row, $data->username);
            $sheet->setCellValue('B' . $row, $data->nama_lengkap);
            $sheet->setCellValue('C' . $row, ''); // Password dikosongkan untuk keamanan
            $sheet->setCellValue('D' . $row, $data->siswa_nis ? $data->siswa_nis : '');
            $sheet->setCellValue('E' . $row, $data->siswa_nisn ? $data->siswa_nisn : '');
            $sheet->setCellValue('F' . $row, $data->jenis_kelamin ? ($data->jenis_kelamin == 'Laki-laki' ? 'L' : 'P') : '');
            $sheet->setCellValue('G' . $row, $data->tempat_lahir ? $data->tempat_lahir : '');
            $sheet->setCellValue('H' . $row, $data->tanggal_lahir ? date('Y-m-d', strtotime($data->tanggal_lahir)) : '');
            $sheet->setCellValue('I' . $row, $data->siswa_kelas ? $data->siswa_kelas : '');
            $sheet->setCellValue('J' . $row, $data->siswa_jurusan ? $data->siswa_jurusan : '');
            $sheet->setCellValue('K' . $row, $data->siswa_telepon ? $data->siswa_telepon : $data->user_telepon);
            $sheet->setCellValue('L' . $row, $data->siswa_alamat ? $data->siswa_alamat : $data->user_alamat);
            $sheet->setCellValue('M' . $row, $data->user_email ? $data->user_email : '');
            $sheet->setCellValue('N' . $row, $data->periode ? $data->periode : '');
            $sheet->setCellValue('O' . $row, $data->tanggal_mulai ? date('Y-m-d', strtotime($data->tanggal_mulai)) : '');
            $sheet->setCellValue('P' . $row, $data->tanggal_selesai ? date('Y-m-d', strtotime($data->tanggal_selesai)) : '');
            $sheet->setCellValue('Q' . $row, $data->dudi_nama ? $data->dudi_nama : '');
            $sheet->setCellValue('R' . $row, $data->status_pengajuan ? $data->status_pengajuan : 'draft');
            $row++;
        }
        
        // Auto-size columns
        foreach(range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'test-template-import-siswa-' . date("YmdHis") . '.xlsx';
        
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

    public function test_export_csv()
    {
        // Ambil data user siswa (level 2) dan join dengan data siswa dan user detail
        $this->db->select('
            tb_user.username, 
            tb_user.nama_lengkap, 
            tb_user.email as user_email,
            tb_user.telepon as user_telepon,
            tb_user.alamat as user_alamat,
            tb_user.tempat_lahir,
            tb_user.tanggal_lahir,
            tb_user.jenis_kelamin,
            tb_siswa.siswa_nis,
            tb_siswa.siswa_nisn,
            tb_siswa.siswa_kelas,
            tb_siswa.siswa_jurusan,
            tb_siswa.siswa_telepon,
            tb_siswa.siswa_alamat,
            tb_siswa.periode,
            tb_siswa.tanggal_mulai,
            tb_siswa.tanggal_selesai,
            tb_siswa.status_pengajuan,
            tb_dudi.dudi_nama
        ');
        $this->db->from('tb_user');
        $this->db->join('tb_siswa', 'tb_siswa.user_id = tb_user.id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->where('tb_user.level', 2);
        $this->db->order_by('tb_user.id', 'ASC');
        $siswa = $this->db->get();
        
        // Generate CSV content with 18 columns
        $csv_content = "Username,Nama Lengkap,Password,NIS,NISN,Jenis Kelamin,Tempat Lahir,Tanggal Lahir,Kelas,Jurusan,Telepon,Alamat,Email,Periode,Tanggal Mulai,Tanggal Selesai,Nama DUDI,Status Pengajuan\n";
        
        foreach($siswa->result() as $data){
            $csv_content .= '"' . str_replace('"', '""', $data->username) . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->nama_lengkap) . '",';
            $csv_content .= '""' . ','; // Password dikosongkan untuk keamanan
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_nis ? $data->siswa_nis : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_nisn ? $data->siswa_nisn : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->jenis_kelamin ? ($data->jenis_kelamin == 'Laki-laki' ? 'L' : 'P') : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->tempat_lahir ? $data->tempat_lahir : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->tanggal_lahir ? date('Y-m-d', strtotime($data->tanggal_lahir)) : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_kelas ? $data->siswa_kelas : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_jurusan ? $data->siswa_jurusan : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_telepon ? $data->siswa_telepon : $data->user_telepon) . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->siswa_alamat ? $data->siswa_alamat : $data->user_alamat) . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->user_email ? $data->user_email : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->periode ? $data->periode : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->tanggal_mulai ? date('Y-m-d', strtotime($data->tanggal_mulai)) : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->tanggal_selesai ? date('Y-m-d', strtotime($data->tanggal_selesai)) : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->dudi_nama ? $data->dudi_nama : '') . '",';
            $csv_content .= '"' . str_replace('"', '""', $data->status_pengajuan ? $data->status_pengajuan : 'draft') . "\"\n";
        }
        
        $filename = 'test-template-import-siswa-' . date("YmdHis") . '.csv';
        
        // Send CSV response
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); 
        header('Cache-Control: max-age=0');
        
        echo $csv_content;
        exit;
    }
}