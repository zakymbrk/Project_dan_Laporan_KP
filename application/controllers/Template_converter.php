<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_converter extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function analyze_manual_template()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Template Converter memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu dengan composer install.');
        }
        
        require_once $autoload_path;
        
        $template_path = FCPATH . 'assets/Template.xlsx';
        
        if (!file_exists($template_path)) {
            die('File Template.xlsx tidak ditemukan di folder assets/');
        }
        
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            echo "<h2>Analisis Template.xlsx Manual</h2>";
            
            if (empty($rows)) {
                echo "<p>File tidak memiliki data apapun.</p>";
                return;
            }
            
            echo "<h3>Header (Baris 1):</h3>";
            $header_row = $rows[0];
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            echo "<tr><th>No</th><th>Nama Kolom</th><th>Posisi Harapan</th><th>Status</th></tr>";
            
            // Expected headers for the system
            $expected_headers = [
                0 => 'Username *',
                1 => 'Nama Lengkap *', 
                2 => 'Password *',
                3 => 'NIS',
                4 => 'NISN',
                5 => 'Jenis Kelamin (L/P)',
                6 => 'Tempat Lahir',
                7 => 'Tanggal Lahir (YYYY-MM-DD)',
                8 => 'Kelas *',
                9 => 'Jurusan',
                10 => 'Telepon',
                11 => 'Alamat',
                12 => 'Email',
                13 => 'Periode',
                14 => 'Tanggal Mulai (YYYY-MM-DD)',
                15 => 'Tanggal Selesai (YYYY-MM-DD)',
                16 => 'Nama DUDI',
                17 => 'Status Pengajuan'
            ];
            
            foreach ($header_row as $index => $header) {
                $expected = isset($expected_headers[$index]) ? $expected_headers[$index] : 'EKSTRA/KOLOM TAMBAHAN';
                $status = (isset($expected_headers[$index]) && trim($header) == trim($expected_headers[$index])) ? 
                         '<span style="color: green;">✓ Cocok</span>' : 
                         '<span style="color: orange;">~ Berbeda</span>';
                
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td>\"" . htmlspecialchars($header) . "\"</td>";
                echo "<td>" . htmlspecialchars($expected) . "</td>";
                echo "<td>" . $status . "</td>";
                echo "</tr>";
            }
            
            // Check for missing columns
            $missing_cols = [];
            foreach ($expected_headers as $idx => $exp_hdr) {
                if (!isset($header_row[$idx]) || trim($header_row[$idx]) != trim($exp_hdr)) {
                    $missing_cols[] = ($idx + 1) . ". " . $exp_hdr;
                }
            }
            
            if (!empty($missing_cols)) {
                echo "<tr style='background-color: #ffe6e6;'>";
                echo "<td colspan='4'>";
                echo "<strong>⚠️ Kolom yang hilang atau tidak sesuai:</strong><br>";
                foreach ($missing_cols as $missing) {
                    echo "- " . $missing . "<br>";
                }
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            echo "<p><strong>Jumlah kolom ditemukan:</strong> " . count($header_row) . "</p>";
            echo "<p><strong>Jumlah baris data:</strong> " . (count($rows) - 1) . " (tidak termasuk header)</p>";
            
            // Show first data row if exists
            if (isset($rows[1])) {
                echo "<h3>Contoh Data (Baris 2):</h3>";
                echo "<ul>";
                foreach ($rows[1] as $index => $value) {
                    echo "<li>Kolom " . ($index + 1) . ": \"" . htmlspecialchars($value) . "\"</li>";
                }
                echo "</ul>";
            }
            
            echo "<h3>Rekomendasi Tindakan:</h3>";
            echo "<ol>";
            echo "<li>Gunakan tombol di bawah untuk mengkonversi template manual ke format sistem</li>";
            echo "<li>Atau, perbarui nama kolom di file Excel Anda agar sesuai dengan format sistem</li>";
            echo "<li>Untuk akun siswa saja, gunakan template khusus yang bisa di-generate</li>";
            echo "</ol>";
            
            echo "<a href='" . base_url('template_converter/convert_to_system_format') . "' class='btn btn-primary'>Konversi Template ke Format Sistem</a>";
            echo " ";
            echo "<a href='" . base_url('template_converter/generate_student_accounts_only') . "' class='btn btn-success'>Buat Template Akun Siswa Saja</a>";
            
        } catch (Exception $e) {
            echo "<h2>Error membaca file Template.xlsx:</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    
    public function convert_to_system_format()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Template Converter memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu dengan composer install.');
        }
        
        require_once $autoload_path;
        
        $template_path = FCPATH . 'assets/Template.xlsx';
        
        if (!file_exists($template_path)) {
            die('File Template.xlsx tidak ditemukan di folder assets/');
        }
        
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template_path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (empty($rows)) {
                die('File tidak memiliki data apapun.');
            }
            
            // Create new spreadsheet for converted data
            $new_spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $new_sheet = $new_spreadsheet->getActiveSheet();
            
            // Define expected headers
            $expected_headers = [
                'Username *',
                'Nama Lengkap *', 
                'Password *',
                'NIS',
                'NISN',
                'Jenis Kelamin (L/P)',
                'Tempat Lahir',
                'Tanggal Lahir (YYYY-MM-DD)',
                'Kelas *',
                'Jurusan',
                'Telepon',
                'Alamat',
                'Email',
                'Periode',
                'Tanggal Mulai (YYYY-MM-DD)',
                'Tanggal Selesai (YYYY-MM-DD)',
                'Nama DUDI',
                'Status Pengajuan'
            ];
            
            // Map the original columns to system columns
            $original_headers = $rows[0];
            $mapping = $this->mapHeaders($original_headers, $expected_headers);
            
            // Write headers
            $col = 'A';
            foreach($expected_headers as $header) {
                $new_sheet->setCellValue($col . '1', $header);
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
            
            $new_sheet->getStyle('A1:R1')->applyFromArray($style_header);
            
            // Process data rows (skip header)
            for ($row_idx = 1; $row_idx < count($rows); $row_idx++) {
                $original_row = $rows[$row_idx];
                $new_row_num = $row_idx + 1; // +1 because row 1 is headers
                
                foreach ($expected_headers as $sys_col_idx => $sys_header) {
                    $mapped_value = '';
                    
                    // Find if this system column exists in original data
                    if (isset($mapping[$sys_header]) && isset($original_row[$mapping[$sys_header]])) {
                        $mapped_value = $original_row[$mapping[$sys_header]];
                    }
                    
                    // Map certain columns if they're named differently
                    if (strtolower(trim($sys_header)) === 'username *') {
                        // Try to find username equivalent
                        $username_cols = ['username', 'user', 'uname', 'id', 'userid', 'login', 'nama pengguna', 'akun'];
                        foreach ($username_cols as $ucol) {
                            if ($mapped_value === '' && $this->findColumnIndex($original_headers, $ucol) !== -1) {
                                $orig_idx = $this->findColumnIndex($original_headers, $ucol);
                                $mapped_value = $original_row[$orig_idx] ?? '';
                                break;
                            }
                        }
                    } elseif (strtolower(trim($sys_header)) === 'nama lengkap *') {
                        // Try to find name equivalent
                        $name_cols = ['nama lengkap', 'nama', 'full name', 'name', 'nama siswa', 'lengkap'];
                        foreach ($name_cols as $ncol) {
                            if ($mapped_value === '' && $this->findColumnIndex($original_headers, $ncol) !== -1) {
                                $orig_idx = $this->findColumnIndex($original_headers, $ncol);
                                $mapped_value = $original_row[$orig_idx] ?? '';
                                break;
                            }
                        }
                    } elseif (strtolower(trim($sys_header)) === 'password *') {
                        // Try to find password equivalent
                        $pass_cols = ['password', 'sandi', 'pwd', 'pass', 'kata sandi'];
                        foreach ($pass_cols as $pcol) {
                            if ($mapped_value === '' && $this->findColumnIndex($original_headers, $pcol) !== -1) {
                                $orig_idx = $this->findColumnIndex($original_headers, $pcol);
                                $mapped_value = $original_row[$orig_idx] ?? '';
                                break;
                            }
                        }
                    } elseif (strtolower(trim($sys_header)) === 'kelas *') {
                        // Try to find class equivalent
                        $class_cols = ['kelas', 'class', 'rombel', 'tingkat'];
                        foreach ($class_cols as $ccol) {
                            if ($mapped_value === '' && $this->findColumnIndex($original_headers, $ccol) !== -1) {
                                $orig_idx = $this->findColumnIndex($original_headers, $ccol);
                                $mapped_value = $original_row[$orig_idx] ?? '';
                                break;
                            }
                        }
                    }
                    
                    $col_letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($sys_col_idx + 1);
                    $new_sheet->setCellValue($col_letter . $new_row_num, $mapped_value);
                }
            }
            
            // Auto-size columns
            foreach(range('A', 'R') as $col) {
                $new_sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Set filename
            $filename = 'template-terkonversi-' . date("YmdHis") . '.xlsx';
            
            // Create writer
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($new_spreadsheet);
            
            // Send headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Output file
            $writer->save('php://output');
            exit;
            
        } catch (Exception $e) {
            echo "<h2>Error mengkonversi file:</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    
    private function mapHeaders($original, $expected) {
        $mapping = [];
        
        foreach ($expected as $exp_idx => $exp_header) {
            $exp_clean = strtolower(trim(preg_replace('/[*\(].*/', '', $exp_header))); // Remove annotations
            
            // Direct match
            foreach ($original as $orig_idx => $orig_header) {
                $orig_clean = strtolower(trim($orig_header));
                if ($orig_clean === $exp_clean) {
                    $mapping[$exp_header] = $orig_idx;
                    break;
                }
            }
            
            // Partial match
            if (!isset($mapping[$exp_header])) {
                foreach ($original as $orig_idx => $orig_header) {
                    $orig_clean = strtolower(trim($orig_header));
                    if (strpos($orig_clean, $exp_clean) !== false || strpos($exp_clean, $orig_clean) !== false) {
                        $mapping[$exp_header] = $orig_idx;
                        break;
                    }
                }
            }
        }
        
        return $mapping;
    }
    
    private function findColumnIndex($headers, $search_term) {
        $search_lower = strtolower($search_term);
        foreach ($headers as $idx => $header) {
            if (strpos(strtolower(trim($header)), $search_lower) !== false) {
                return $idx;
            }
        }
        return -1;
    }
    
    public function generate_student_accounts_only()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Template Generator memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu dengan composer install.');
        }
        
        require_once $autoload_path;
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers for student accounts only (minimum required for login)
        $headers = [
            'Username *',
            'Nama Lengkap *', 
            'Password *',
            'Kelas *',
            'Email',
            'Telepon'
        ];
        
        // Fill header row
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Add sample data
        $sample_data = [
            'siswa001',
            'Andi Setiawan',
            'password123',
            'XI RPL 1',
            'andi@example.com',
            '08123456789'
        ];
        
        $col = 'A';
        foreach($sample_data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        // Add another sample row
        $sample_data2 = [
            'siswa002',
            'Budi Santoso',
            'password123',
            'XI TKJ 1',
            'budi@example.com',
            '08123456790'
        ];
        
        $col = 'A';
        foreach($sample_data2 as $value) {
            $sheet->setCellValue($col . '3', $value);
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
                'startColor' => ['rgb' => '28a745']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($style_header);
        
        // Auto-size columns
        foreach(range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'template-akun-siswa-saja-' . date("YmdHis") . '.xlsx';
        
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