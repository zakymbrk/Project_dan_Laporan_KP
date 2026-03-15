<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_generator extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Only allow access in development environment
        if(ENVIRONMENT !== 'development') {
            show_404();
        }
    }
    
    public function generate_template()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            show_error('Fitur Template Generator memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu dengan composer install.');
        }
        
        require_once $autoload_path;
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Define headers - Template yang digunakan saat ini (20 kolom)
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
        
        // Add some sample data - Sesuai dengan template 20 kolom
        $sample_data = [
            'Andi Setiawan',
            '1234567890',
            '0987654321',
            'L',
            'Jakarta',
            '2005-01-15',
            'XI RPL 1',
            'Rekayasa Perangkat Lunak',
            'SMK ITIKURIH HIBARNA',
            '08123456789',
            'siswa001',
            'password123',
            'andi@example.com',
            'Jl. Contoh No. 1',
            'PT Contoh Perusahaan',
            '2024-06-01',
            '2024-08-30',
            '90',
            'menunggu',
            '2026/2027'
        ];
        
        $col = 'A';
        foreach($sample_data as $value) {
            $sheet->setCellValue($col . '2', $value);
            $col++;
        }
        
        // Add another sample row
        $sample_data2 = [
            'Budi Santoso',
            '1234567891',
            '0987654322',
            'L',
            'Bandung',
            '2005-03-20',
            'XI TKJ 1',
            'Teknik Komputer dan Jaringan',
            'SMK ITIKURIH HIBARNA',
            '08123456790',
            'siswa002',
            'password123',
            'budi@example.com',
            'Jl. Contoh No. 2',
            'PT Teknologi Indonesia',
            '2024-06-01',
            '2024-08-30',
            '90',
            'menunggu',
            '2026/2027'
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
        $filename = 'template-import-siswa-terbaru-' . date("YmdHis") . '.xlsx';
        
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