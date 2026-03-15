<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_excel extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    // Export data siswa dalam format XLSX
    function export_daftar_siswa()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Load Models
        $this->load->model('M_siswa');
        $this->load->model('M_user');
        $this->load->model('M_dudi');
        
        // Check if specific students are selected
        $selected_ids = $this->input->get('selected');
        
        // Ambil data user siswa (level 2) dan join dengan data siswa dan user detail
        $this->db->select('
            tb_user.username, 
            tb_user.nama_lengkap, 
            tb_user.password,
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
        
        // Filter by selected IDs if provided
        if ($selected_ids) {
            $siswa_ids_array = explode(',', $selected_ids);
            $this->db->where_in('tb_siswa.siswa_id', $siswa_ids_array);
        }
        
        $this->db->order_by('tb_user.id', 'ASC');
        $siswa = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama Siswa',
            'NIS',
            'NISN', 
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas',
            'Jurusan',
            'Sekolah',
            'Telepon Siswa',
            'Username',
            'Password',
            'Email',
            'Alamat Lengkap',
            'Perusahaan (DUDI)',
            'Tanggal Mulai PKL',
            'Tanggal Selesai PKL',
            'Lama Pelaksanaan (hari)',
            'Status Pengajuan',
            'Periode PKL'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($siswa->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->nama_lengkap); $col++; // Nama Siswa
            $sheet->setCellValue($col . $row_num, $data->siswa_nis ? $data->siswa_nis : ''); $col++; // NIS
            $sheet->setCellValue($col . $row_num, $data->siswa_nisn ? $data->siswa_nisn : ''); $col++; // NISN
            $sheet->setCellValue($col . $row_num, $data->jenis_kelamin ? ($data->jenis_kelamin == 'Laki-laki' ? 'L' : 'P') : ''); $col++; // Jenis Kelamin
            $sheet->setCellValue($col . $row_num, $data->tempat_lahir ? $data->tempat_lahir : ''); $col++; // Tempat Lahir
            $sheet->setCellValue($col . $row_num, $data->tanggal_lahir ? date('Y-m-d', strtotime($data->tanggal_lahir)) : ''); $col++; // Tanggal Lahir
            $sheet->setCellValue($col . $row_num, $data->siswa_kelas ? $data->siswa_kelas : ''); $col++; // Kelas
            $sheet->setCellValue($col . $row_num, $data->siswa_jurusan ? $data->siswa_jurusan : ''); $col++; // Jurusan
            $sheet->setCellValue($col . $row_num, 'SMK ITIKURIH HIBARNA'); $col++; // Sekolah (default)
            $sheet->setCellValue($col . $row_num, $data->siswa_telepon ? $data->siswa_telepon : $data->user_telepon); $col++; // Telepon Siswa
            $sheet->setCellValue($col . $row_num, $data->username); $col++; // Username
            $sheet->setCellValue($col . $row_num, ''); // Password (empty for security) $col++;
            $sheet->setCellValue($col . $row_num, $data->user_email ? $data->user_email : ''); $col++; // Email
            $sheet->setCellValue($col . $row_num, $data->siswa_alamat ? $data->siswa_alamat : $data->user_alamat); $col++; // Alamat Lengkap
            $sheet->setCellValue($col . $row_num, $data->dudi_nama ? $data->dudi_nama : ''); $col++; // Perusahaan (DUDI)
            $sheet->setCellValue($col . $row_num, $data->tanggal_mulai ? date('Y-m-d', strtotime($data->tanggal_mulai)) : ''); $col++; // Tanggal Mulai PKL
            $sheet->setCellValue($col . $row_num, $data->tanggal_selesai ? date('Y-m-d', strtotime($data->tanggal_selesai)) : ''); $col++; // Tanggal Selesai PKL
            // Calculate duration in days
            if($data->tanggal_mulai && $data->tanggal_selesai) {
                $start_date = new DateTime($data->tanggal_mulai);
                $end_date = new DateTime($data->tanggal_selesai);
                $interval = $start_date->diff($end_date);
                $lama_pelaksanaan = $interval->days + 1; // +1 because we include both start and end dates
            } else {
                $lama_pelaksanaan = '';
            }
            $sheet->setCellValue($col . $row_num, $lama_pelaksanaan); $col++; // Lama Pelaksanaan (hari)
            $sheet->setCellValue($col . $row_num, $data->status_pengajuan ? $data->status_pengajuan : 'draft'); $col++; // Status Pengajuan
            $sheet->setCellValue($col . $row_num, $data->periode ? $data->periode : ''); // Periode PKL
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'template-import-siswa-' . date("YmdHis") . '.xlsx';
        
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

    // Export data pembimbing dalam format XLSX
    function export_data_pembimbing()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Load Model
        $this->load->model('M_pembimbing');
        
        // Check if specific pembimbing are selected
        $selected_ids = $this->input->get('selected');
        
        // Ambil data pembimbing
        $this->db->select('*');
        $this->db->from('tb_pembimbing');
        
        // Filter by selected IDs if provided
        if ($selected_ids) {
            $pembimbing_ids_array = explode(',', $selected_ids);
            $this->db->where_in('pembimbing_id', $pembimbing_ids_array);
        }
        
        $this->db->order_by('pembimbing_nama', 'ASC');
        $pembimbing = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama Pembimbing',
            'NIP',
            'Tempat Tugas',
            'Pendidikan Terakhir',
            'Jabatan',
            'Telepon',
            'Email'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($pembimbing->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->pembimbing_nama); $col++; // Nama Pembimbing
            $sheet->setCellValue($col . $row_num, $data->pembimbing_nip ? $data->pembimbing_nip : ''); $col++; // NIP
            $sheet->setCellValue($col . $row_num, $data->tempat_tugas ? $data->tempat_tugas : ''); $col++; // Tempat Tugas
            $sheet->setCellValue($col . $row_num, $data->pendidikan_terakhir ? $data->pendidikan_terakhir : ''); $col++; // Pendidikan Terakhir
            $sheet->setCellValue($col . $row_num, $data->jabatan ? $data->jabatan : ''); $col++; // Jabatan
            $sheet->setCellValue($col . $row_num, $data->pembimbing_telepon ? $data->pembimbing_telepon : ''); $col++; // Telepon
            $sheet->setCellValue($col . $row_num, $data->pembimbing_email ? $data->pembimbing_email : ''); // Email
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'export-pembimbing-' . date("YmdHis") . '.xlsx';
        
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

    // Export data pembimbing dalam format XLSX (alias for export_data_pembimbing)
    function export_daftar_pembimbing()
    {
        $this->export_data_pembimbing();
    }

    // Export data siswa dalam format XLSX (for data-siswa page)
    function export_data_siswa()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Load Models
        $this->load->model('M_siswa');
        $this->load->model('M_user');
        $this->load->model('M_dudi');
        
        // Check if specific students are selected
        $selected_codes = $this->input->get('selected');
        
        // Get students with approved status (same as data-siswa page)
        $this->db->select('
            tb_siswa.*,
            tb_user.nama_lengkap as user_nama,
            tb_dudi.dudi_nama,
            tb_dudi.is_mitra as dudi_is_mitra,
            tb_pembimbing.pembimbing_nama as pembimbing_nama,
            tb_pengelompokan.pembimbing_id as kelompok_pembimbing_id
        ');
        $this->db->from('tb_siswa');
        $this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->join('tb_pengelompokan', 'tb_pengelompokan.siswa_id = tb_siswa.siswa_id', 'left');
        $this->db->join('tb_pembimbing', 'tb_pembimbing.pembimbing_id = tb_pengelompokan.pembimbing_id', 'left');
        $this->db->where('tb_siswa.status_pengajuan', 'disetujui');
        
        // Filter by selected codes if provided
        if ($selected_codes) {
            $siswa_codes_array = explode(',', $selected_codes);
            $this->db->where_in('tb_siswa.siswa_code', $siswa_codes_array);
        }
        
        $this->db->order_by('CASE WHEN tb_pengelompokan.pembimbing_id IS NULL THEN 0 ELSE 1 END', '', FALSE);
        $this->db->order_by('tb_pembimbing.pembimbing_nama', 'ASC');
        $this->db->order_by('tb_siswa.siswa_nama', 'ASC');
        
        $siswa = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama Siswa',
            'NIS',
            'NISN',
            'Kelas',
            'Jurusan',
            'Perusahaan (DUDI)',
            'Status Pengajuan',
            'Periode',
            'Guru Pembimbing'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($siswa->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->siswa_nama); $col++; // Nama Siswa
            $sheet->setCellValue($col . $row_num, $data->siswa_nis ? $data->siswa_nis : ''); $col++; // NIS
            $sheet->setCellValue($col . $row_num, $data->siswa_nisn ? $data->siswa_nisn : ''); $col++; // NISN
            $sheet->setCellValue($col . $row_num, $data->siswa_kelas ? $data->siswa_kelas : ''); $col++; // Kelas
            $sheet->setCellValue($col . $row_num, $data->siswa_jurusan ? $data->siswa_jurusan : ''); $col++; // Jurusan
            $sheet->setCellValue($col . $row_num, $data->dudi_nama ? $data->dudi_nama : ''); $col++; // Perusahaan (DUDI)
            $sheet->setCellValue($col . $row_num, $data->status_pengajuan ? $data->status_pengajuan : ''); $col++; // Status Pengajuan
            $sheet->setCellValue($col . $row_num, $data->periode ? $data->periode : ''); $col++; // Periode
            // Guru Pembimbing column - show "Belum Mendapatkan Pembimbing" if not assigned
            if ($data->kelompok_pembimbing_id) {
                $sheet->setCellValue($col . $row_num, $data->pembimbing_nama ? $data->pembimbing_nama : '-');
            } else {
                $sheet->setCellValue($col . $row_num, 'Belum Mendapatkan Pembimbing');
            }
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'export-data-siswa-' . date("YmdHis") . '.xlsx';
        
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

    // Export data DUDI dalam format XLSX
    function export_data_dudi()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Load Model
        $this->load->model('M_dudi');
        
        // Check if specific DUDI are selected
        $selected_codes = $this->input->get('selected');
        
        // Get all DUDI data
        $this->db->select('*');
        $this->db->from('tb_dudi');
        
        // Filter by selected codes if provided
        if ($selected_codes) {
            $dudi_codes_array = explode(',', $selected_codes);
            $this->db->where_in('dudi_code', $dudi_codes_array);
        }
        
        $this->db->order_by('dudi_nama', 'ASC');
        $dudi = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama DUDI',
            'Alamat',
            'Telepon',
            'Email',
            'PIC',
            'Status Kerjasama',
            'Kategori (Mitra/Non-Mitra)',
            'Sumber Data'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($dudi->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->dudi_nama); $col++; // Nama DUDI
            $sheet->setCellValue($col . $row_num, $data->dudi_alamat ? $data->dudi_alamat : ''); $col++; // Alamat
            $sheet->setCellValue($col . $row_num, $data->dudi_telepon ? $data->dudi_telepon : ''); $col++; // Telepon
            $sheet->setCellValue($col . $row_num, $data->dudi_email ? $data->dudi_email : ''); $col++; // Email
            $sheet->setCellValue($col . $row_num, $data->dudi_pic ? $data->dudi_pic : ''); $col++; // PIC
            $sheet->setCellValue($col . $row_num, $data->status_kerjasama ? $data->status_kerjasama : ''); $col++; // Status Kerjasama
            // Kategori column
            $kategori = ($data->is_mitra == 1) ? 'Mitra' : 'Non-Mitra';
            $sheet->setCellValue($col . $row_num, $kategori); $col++; // Kategori
            $sheet->setCellValue($col . $row_num, $data->sumber_data ? $data->sumber_data : ''); // Sumber Data
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'export-data-dudi-' . date("YmdHis") . '.xlsx';
        
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

    // Import data siswa dengan template yang sesuai
    function import_daftar_siswa()
    {
        // Debug: Log the start of the import process
        log_message('debug', 'Import process started');
        
        // Check if user is logged in and has permission (Hubin level)
        $userdata = $this->session->userdata('userdata');
        log_message('debug', 'User data: ' . json_encode($userdata));
        
        if(!$userdata || $userdata['level'] != 1){
            log_message('error', 'User not authorized or not logged in');
            $this->session->set_flashdata('error_message', 'Anda tidak memiliki izin untuk melakukan import data.');
            redirect('hubin/view/daftar-siswa');
            return;
        }

        $config['upload_path'] = './uploads/import/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'import_daftar_siswa_' . time();

        // Create directory if not exists
        if(!is_dir($config['upload_path'])){
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);
        log_message('debug', 'Upload library loaded');

        if (!$this->upload->do_upload('file_excel')) {
            $error_message = $this->upload->display_errors('', '');
            log_message('error', 'Upload failed: ' . $error_message);
            $this->session->set_flashdata('error_message', $error_message);
            redirect('hubin/view/daftar-siswa');
            return;
        }

        $upload_data = $this->upload->data();
        $file_path = $config['upload_path'] . $upload_data['file_name'];
        log_message('debug', 'File uploaded successfully: ' . $file_path);

        try {
            $this->load->model('M_user');
            $this->load->model('M_siswa');
            log_message('debug', 'Models loaded successfully');
            
            $success_count = 0;
            $error_count = 0;
            $errors = array();

            // Determine file type and parse accordingly
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
            log_message('debug', 'File extension: ' . $file_extension);
            
            if(strtolower($file_extension) === 'csv') {
                // Handle CSV file
                log_message('debug', 'Processing CSV file');
                if (($handle = fopen($file_path, "r")) !== FALSE) {
                    $row_index = 0;
                    $header_check = null;
                    
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $row_index++;
                        log_message('debug', 'Processing row ' . $row_index . ': ' . json_encode($row));
                        
                        // Check header row
                        if($row_index == 1) {
                            if (count($row) < 20) {
                                fclose($handle);
                                throw new Exception('File CSV harus memiliki 20 kolom sesuai template. Ditemukan hanya ' . count($row) . ' kolom.');
                            }
                            $header_check = $row;
                            log_message('debug', 'Header row validated');
                            continue;
                        }

                        // Skip empty rows
                        if(empty($row[0])) {
                            log_message('debug', 'Skipping empty row ' . $row_index);
                            continue;
                        }

                        // Extract data from row - Mengikuti urutan kolom template yang benar (20 kolom)
                        $nama_siswa = trim($row[0]);
                        $nis = isset($row[1]) ? trim($row[1]) : '';
                        $nisn = isset($row[2]) ? trim($row[2]) : '';
                        $jenis_kelamin = isset($row[3]) ? trim($row[3]) : '';
                        $tempat_lahir = isset($row[4]) ? trim($row[4]) : '';
                        $tanggal_lahir = isset($row[5]) ? trim($row[5]) : '';
                        $kelas = isset($row[6]) ? trim($row[6]) : '';
                        $jurusan = isset($row[7]) ? trim($row[7]) : '';
                        $sekolah = isset($row[8]) ? trim($row[8]) : '';
                        $telepon_siswa = isset($row[9]) ? trim($row[9]) : '';
                        $username = isset($row[10]) ? trim($row[10]) : '';
                        $password = isset($row[11]) ? trim($row[11]) : '';
                        $email = isset($row[12]) ? trim($row[12]) : '';
                        $alamat_lengkap = isset($row[13]) ? trim($row[13]) : '';
                        $perusahaan_dudi = isset($row[14]) ? trim($row[14]) : '';
                        $tanggal_mulai_pkl = isset($row[15]) ? trim($row[15]) : '';
                        $tanggal_selesai_pkl = isset($row[16]) ? trim($row[16]) : '';
                        $lama_pelaksanaan = isset($row[17]) ? trim($row[17]) : '';
                        $status_pengajuan = isset($row[18]) ? strtolower(trim($row[18])) : 'draft';
                        $periode_pkl = isset($row[19]) ? trim($row[19]) : '';

                        log_message('debug', 'Extracted data - Nama: ' . $nama_siswa . ', Username: ' . $username . ', Kelas: ' . $kelas);

                        // Validasi data wajib
                        if(empty($nama_siswa) || empty($kelas) || empty($username) || empty($password)) {
                            $error_msg = 'Baris ' . $row_index . ': Data tidak lengkap (Nama Siswa, Kelas, Username, dan Password wajib diisi)';
                            log_message('error', $error_msg);
                            $errors[] = $error_msg;
                            $error_count++;
                            continue;
                        }
                        
                        // Cek duplikasi username
                        $this->db->where('username', $username);
                        if($this->db->get('tb_user')->num_rows() > 0) {
                            $error_msg = 'Baris ' . $row_index . ': Username "' . $username . '" sudah ada dalam database';
                            log_message('error', $error_msg);
                            $errors[] = $error_msg;
                            $error_count++;
                            continue;
                        }
                        
                        log_message('debug', 'Data validation passed for row ' . $row_index);
                        
                        // Hash password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        log_message('debug', 'Password hashed');

                        // Simpan data user - Pastikan semua data disimpan dengan benar
                        $user_data = array(
                            'username' => $username,
                            'password' => $hashed_password,
                            'nama_lengkap' => $nama_siswa,
                            'email' => !empty($email) ? $email : NULL,
                            'telepon' => !empty($telepon_siswa) ? $telepon_siswa : NULL,
                            'alamat' => !empty($alamat_lengkap) ? $alamat_lengkap : NULL,
                            'tempat_lahir' => !empty($tempat_lahir) ? $tempat_lahir : NULL,
                            'tanggal_lahir' => !empty($tanggal_lahir) ? date('Y-m-d', strtotime($tanggal_lahir)) : NULL,
                            'jenis_kelamin' => !empty($jenis_kelamin) ? (strtoupper($jenis_kelamin) == 'L' ? 'Laki-laki' : 'Perempuan') : NULL,
                            'level' => 2, // Level siswa
                            'is_active' => 1,
                            'user_code' => $this->M_user->generateRandomString(30)
                        );

                        $this->db->insert('tb_user', $user_data);
                        log_message('debug', 'User insert query executed');
                        
                        if($this->db->affected_rows() > 0) {
                            $success_count++;
                            log_message('debug', 'User inserted successfully');
                            
                            // Auto-create data siswa
                            $user_id = $this->db->insert_id();
                            
                            $dudi_id = NULL;
                            
                            // Cek apakah DUDI sudah ada
                            if(!empty($perusahaan_dudi)) {
                                $this->db->where('dudi_nama', $perusahaan_dudi);
                                $existing_dudi = $this->db->get('tb_dudi')->row();
                                
                                if($existing_dudi) {
                                    $dudi_id = $existing_dudi->dudi_id;
                                } else {
                                    // Buat DUDI baru jika belum ada
                                    $dudi_code = $this->M_siswa->generateRandomString(30);
                                    $dudi_data = array(
                                        'dudi_nama' => $perusahaan_dudi,
                                        'dudi_code' => $dudi_code,
                                        'status_kerjasama' => 'non_mitra',
                                        'is_mitra' => 0,
                                        'sumber_data' => 'sekolah',
                                        'created_at' => date('Y-m-d H:i:s')
                                    );
                                    
                                    $this->db->insert('tb_dudi', $dudi_data);
                                    if($this->db->affected_rows() > 0) {
                                        $dudi_id = $this->db->insert_id();
                                    }
                                }
                            }
                            
                            $siswa_data = array(
                                'user_id' => $user_id,
                                'siswa_nama' => $nama_siswa,
                                'siswa_nis' => !empty($nis) ? $nis : NULL,
                                'siswa_nisn' => !empty($nisn) ? $nisn : NULL,
                                'siswa_jk' => !empty($jenis_kelamin) ? $jenis_kelamin : NULL,
                                'siswa_tempat_lahir' => !empty($tempat_lahir) ? $tempat_lahir : NULL,
                                'siswa_tanggal_lahir' => !empty($tanggal_lahir) ? date('Y-m-d', strtotime($tanggal_lahir)) : NULL,
                                'siswa_kelas' => $kelas,
                                'siswa_jurusan' => $jurusan,
                                'siswa_telepon' => $telepon_siswa,
                                'siswa_alamat' => $alamat_lengkap,
                                'siswa_asal_sekolah' => !empty($sekolah) ? $sekolah : NULL,
                                'dudi_id' => $dudi_id,
                                'periode' => !empty($periode_pkl) ? $periode_pkl : NULL,
                                'tanggal_mulai' => !empty($tanggal_mulai_pkl) ? date('Y-m-d', strtotime($tanggal_mulai_pkl)) : NULL,
                                'tanggal_selesai' => !empty($tanggal_selesai_pkl) ? date('Y-m-d', strtotime($tanggal_selesai_pkl)) : NULL,
                                'status_pengajuan' => in_array($status_pengajuan, ['draft', 'menunggu', 'disetujui', 'ditolak', 'selesai']) ? $status_pengajuan : 'draft',
                                'siswa_code' => $this->M_siswa->generateRandomString(30)
                            );
                            
                            $this->db->insert('tb_siswa', $siswa_data);
                            log_message('debug', 'Siswa data inserted');
                        } else {
                            $error_msg = 'Baris ' . $row_index . ': Gagal menyimpan data user';
                            log_message('error', $error_msg);
                            $errors[] = $error_msg;
                            $error_count++;
                        }
                    }
                    fclose($handle);
                }
            } else {
                // Handle Excel file
                log_message('debug', 'Processing Excel file');
                $autoload_path = FCPATH . 'vendor/autoload.php';
                if (!file_exists($autoload_path)) {
                    throw new Exception('Fitur Import Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
                }
                
                require_once $autoload_path;
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                // Check if file has data
                if (empty($rows)) {
                    throw new Exception('File Excel tidak memiliki data apapun.');
                }

                // Check if there are headers
                if (count($rows[0]) < 20) {
                    throw new Exception('File Excel harus memiliki 20 kolom sesuai template. Ditemukan hanya ' . count($rows[0]) . ' kolom.');
                }

                // Skip header row
                array_shift($rows);
                log_message('debug', 'Excel file processed, ' . count($rows) . ' data rows found');

                foreach($rows as $index => $row){
                    // Skip empty rows
                    if(empty($row[0])) {
                        log_message('debug', 'Skipping empty row ' . ($index + 2));
                        continue;
                    }

                    // Extract data from row (same as CSV) - Mengikuti urutan kolom template yang benar (20 kolom)
                    $nama_siswa = trim($row[0]);
                    $nis = isset($row[1]) ? trim($row[1]) : '';
                    $nisn = isset($row[2]) ? trim($row[2]) : '';
                    $jenis_kelamin = isset($row[3]) ? trim($row[3]) : '';
                    $tempat_lahir = isset($row[4]) ? trim($row[4]) : '';
                    $tanggal_lahir = isset($row[5]) ? trim($row[5]) : '';
                    $kelas = isset($row[6]) ? trim($row[6]) : '';
                    $jurusan = isset($row[7]) ? trim($row[7]) : '';
                    $sekolah = isset($row[8]) ? trim($row[8]) : '';
                    $telepon_siswa = isset($row[9]) ? trim($row[9]) : '';
                    $username = isset($row[10]) ? trim($row[10]) : '';
                    $password = isset($row[11]) ? trim($row[11]) : '';
                    $email = isset($row[12]) ? trim($row[12]) : '';
                    $alamat_lengkap = isset($row[13]) ? trim($row[13]) : '';
                    $perusahaan_dudi = isset($row[14]) ? trim($row[14]) : '';
                    $tanggal_mulai_pkl = isset($row[15]) ? trim($row[15]) : '';
                    $tanggal_selesai_pkl = isset($row[16]) ? trim($row[16]) : '';
                    $lama_pelaksanaan = isset($row[17]) ? trim($row[17]) : '';
                    $status_pengajuan = isset($row[18]) ? strtolower(trim($row[18])) : 'draft';
                    $periode_pkl = isset($row[19]) ? trim($row[19]) : '';

                    log_message('debug', 'Processing Excel row ' . ($index + 2) . ' - Nama: ' . $nama_siswa . ', Username: ' . $username);

                    // Validasi data wajib (same as CSV)
                    if(empty($nama_siswa) || empty($kelas) || empty($username) || empty($password)) {
                        $error_msg = 'Baris ' . ($index + 2) . ': Data tidak lengkap (Nama Siswa, Kelas, Username, dan Password wajib diisi)';
                        log_message('error', $error_msg);
                        $errors[] = $error_msg;
                        $error_count++;
                        continue;
                    }
                    
                    // Cek duplikasi username
                    $this->db->where('username', $username);
                    if($this->db->get('tb_user')->num_rows() > 0) {
                        $error_msg = 'Baris ' . ($index + 2) . ': Username "' . $username . '" sudah ada dalam database';
                        log_message('error', $error_msg);
                        $errors[] = $error_msg;
                        $error_count++;
                        continue;
                    }
                    
                    log_message('debug', 'Data validation passed for Excel row ' . ($index + 2));
                    
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Simpan data user (same as CSV) - Pastikan semua data disimpan dengan benar
                    $user_data = array(
                        'username' => $username,
                        'password' => $hashed_password,
                        'nama_lengkap' => $nama_siswa,
                        'email' => !empty($email) ? $email : NULL,
                        'telepon' => !empty($telepon_siswa) ? $telepon_siswa : NULL,
                        'alamat' => !empty($alamat_lengkap) ? $alamat_lengkap : NULL,
                        'tempat_lahir' => !empty($tempat_lahir) ? $tempat_lahir : NULL,
                        'tanggal_lahir' => !empty($tanggal_lahir) ? date('Y-m-d', strtotime($tanggal_lahir)) : NULL,
                        'jenis_kelamin' => !empty($jenis_kelamin) ? (strtoupper($jenis_kelamin) == 'L' ? 'Laki-laki' : 'Perempuan') : NULL,
                        'level' => 2, // Level siswa
                        'is_active' => 1,
                        'user_code' => $this->M_user->generateRandomString(30)
                    );

                    $this->db->insert('tb_user', $user_data);
                    
                    if($this->db->affected_rows() > 0) {
                        $success_count++;
                        log_message('debug', 'User inserted successfully from Excel');
                        
                        // Auto-create data siswa (same as CSV)
                        $user_id = $this->db->insert_id();
                        
                        $dudi_id = NULL;
                        
                        // Cek apakah DUDI sudah ada
                        if(!empty($perusahaan_dudi)) {
                            $this->db->where('dudi_nama', $perusahaan_dudi);
                            $existing_dudi = $this->db->get('tb_dudi')->row();
                            
                            if($existing_dudi) {
                                $dudi_id = $existing_dudi->dudi_id;
                            } else {
                                // Buat DUDI baru jika belum ada
                                $dudi_code = $this->M_siswa->generateRandomString(30);
                                $dudi_data = array(
                                    'dudi_nama' => $perusahaan_dudi,
                                    'dudi_code' => $dudi_code,
                                    'status_kerjasama' => 'non_mitra',
                                    'is_mitra' => 0,
                                    'sumber_data' => 'sekolah',
                                    'created_at' => date('Y-m-d H:i:s')
                                );
                                
                                $this->db->insert('tb_dudi', $dudi_data);
                                if($this->db->affected_rows() > 0) {
                                    $dudi_id = $this->db->insert_id();
                                }
                            }
                        }
                        
                        $siswa_data = array(
                            'user_id' => $user_id,
                            'siswa_nama' => $nama_siswa,
                            'siswa_nis' => !empty($nis) ? $nis : NULL,
                            'siswa_nisn' => !empty($nisn) ? $nisn : NULL,
                            'siswa_jk' => !empty($jenis_kelamin) ? $jenis_kelamin : NULL,
                            'siswa_tempat_lahir' => !empty($tempat_lahir) ? $tempat_lahir : NULL,
                            'siswa_tanggal_lahir' => !empty($tanggal_lahir) ? date('Y-m-d', strtotime($tanggal_lahir)) : NULL,
                            'siswa_kelas' => $kelas,
                            'siswa_jurusan' => $jurusan,
                            'siswa_telepon' => $telepon_siswa,
                            'siswa_alamat' => $alamat_lengkap,
                            'siswa_asal_sekolah' => !empty($sekolah) ? $sekolah : NULL,
                            'dudi_id' => $dudi_id,
                            'periode' => !empty($periode_pkl) ? $periode_pkl : NULL,
                            'tanggal_mulai' => !empty($tanggal_mulai_pkl) ? date('Y-m-d', strtotime($tanggal_mulai_pkl)) : NULL,
                            'tanggal_selesai' => !empty($tanggal_selesai_pkl) ? date('Y-m-d', strtotime($tanggal_selesai_pkl)) : NULL,
                            'status_pengajuan' => in_array($status_pengajuan, ['draft', 'menunggu', 'disetujui', 'ditolak', 'selesai']) ? $status_pengajuan : 'draft',
                            'siswa_code' => $this->M_siswa->generateRandomString(30)
                        );
                        
                        $this->db->insert('tb_siswa', $siswa_data);
                        log_message('debug', 'Siswa data inserted from Excel');
                    } else {
                        $error_msg = 'Baris ' . ($index + 2) . ': Gagal menyimpan data user';
                        log_message('error', $error_msg);
                        $errors[] = $error_msg;
                        $error_count++;
                    }
                }
            }

            // Hapus file setelah diproses
            unlink($file_path);
            log_message('debug', 'Temporary file deleted');

            // Set flash message
            if($success_count > 0) {
                $this->session->set_flashdata('message', $success_count . ' data siswa berhasil diimport.');
                log_message('debug', $success_count . ' records imported successfully');
            } else {
                // Even if no successful imports, show a message to indicate processing happened
                if($error_count > 0) {
                    $this->session->set_flashdata('error_message', 'Tidak ada data yang berhasil diimport. Terdapat ' . $error_count . ' error.');
                    log_message('error', 'No records imported, ' . $error_count . ' errors occurred');
                } else {
                    $this->session->set_flashdata('message', 'File berhasil diproses tetapi tidak ada data baru yang ditambahkan.');
                    log_message('debug', 'File processed but no new data added');
                }
            }
            
            if(!empty($errors)) {
                $error_message = 'Terdapat ' . $error_count . ' error:\n' . implode("\n", array_slice($errors, 0, 10));
                $this->session->set_flashdata('error_message', $error_message);
                log_message('error', 'Import completed with errors: ' . json_encode($errors));
            }

        } catch (Exception $e) {
            log_message('error', 'Import exception: ' . $e->getMessage());
            $this->session->set_flashdata('error_message', 'Error: ' . $e->getMessage());
            // Delete uploaded file on error
            if(file_exists($file_path)){
                unlink($file_path);
            }
        }

        log_message('debug', 'Import process completed');
        redirect('hubin/view/daftar-siswa');
    }

    // Export data pengajuan PKL dalam format XLSX
    function export_data_pengajuan_pkl()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Check if specific submissions are selected
        $selected_ids = $this->input->get('selected');
        
        // Get all submission data
        $this->db->select('
            tb_siswa.*, 
            tb_dudi.dudi_nama, 
            tb_dudi.is_mitra as dudi_is_mitra,
            tb_user.nama_lengkap as user_nama
        ');
        $this->db->from('tb_siswa');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
        $this->db->where('tb_siswa.status_pengajuan !=', 'draft');
        $this->db->where('tb_siswa.status_pengajuan !=', 'disetujui');
        
        // Filter by selected IDs if provided
        if ($selected_ids) {
            $siswa_ids_array = explode(',', $selected_ids);
            $this->db->where_in('tb_siswa.siswa_id', $siswa_ids_array);
        }
        
        $this->db->order_by('tb_siswa.created_at', 'DESC');
        $pengajuan = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'NIS',
            'NISN',
            'Perusahaan (DUDI)',
            'Status Pengajuan',
            'Tanggal Pengajuan',
            'Alamat Perusahaan',
            'Telepon Perusahaan',
            'Email Perusahaan',
            'Posisi/Jabatan',
            'Tanggal Mulai PKL',
            'Tanggal Selesai PKL',
            'Lama Pelaksanaan (hari)',
            'Periode PKL'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($pengajuan->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->user_nama ? $data->user_nama : $data->siswa_nama); $col++; // Nama Siswa
            $sheet->setCellValue($col . $row_num, $data->siswa_kelas ? $data->siswa_kelas : ''); $col++; // Kelas
            $sheet->setCellValue($col . $row_num, $data->siswa_jurusan ? $data->siswa_jurusan : ''); $col++; // Jurusan
            $sheet->setCellValue($col . $row_num, $data->siswa_nis ? $data->siswa_nis : ''); $col++; // NIS
            $sheet->setCellValue($col . $row_num, $data->siswa_nisn ? $data->siswa_nisn : ''); $col++; // NISN
            $sheet->setCellValue($col . $row_num, $data->dudi_nama ? $data->dudi_nama : ''); $col++; // Perusahaan (DUDI)
            $sheet->setCellValue($col . $row_num, $data->status_pengajuan ? $data->status_pengajuan : ''); $col++; // Status Pengajuan
            $sheet->setCellValue($col . $row_num, $data->created_at ? date('Y-m-d H:i:s', strtotime($data->created_at)) : ''); $col++; // Tanggal Pengajuan
            $sheet->setCellValue($col . $row_num, $data->dudi_alamat ? $data->dudi_alamat : ''); $col++; // Alamat Perusahaan
            $sheet->setCellValue($col . $row_num, $data->dudi_telepon ? $data->dudi_telepon : ''); $col++; // Telepon Perusahaan
            $sheet->setCellValue($col . $row_num, $data->dudi_email ? $data->dudi_email : ''); $col++; // Email Perusahaan
            $sheet->setCellValue($col . $row_num, $data->posisi_jabatan ? $data->posisi_jabatan : ''); $col++; // Posisi/Jabatan
            $sheet->setCellValue($col . $row_num, $data->tanggal_mulai ? date('Y-m-d', strtotime($data->tanggal_mulai)) : ''); $col++; // Tanggal Mulai PKL
            $sheet->setCellValue($col . $row_num, $data->tanggal_selesai ? date('Y-m-d', strtotime($data->tanggal_selesai)) : ''); $col++; // Tanggal Selesai PKL
            // Calculate duration in days
            if($data->tanggal_mulai && $data->tanggal_selesai) {
                $start_date = new DateTime($data->tanggal_mulai);
                $end_date = new DateTime($data->tanggal_selesai);
                $interval = $start_date->diff($end_date);
                $lama_pelaksanaan = $interval->days + 1;
            } else {
                $lama_pelaksanaan = '';
            }
            $sheet->setCellValue($col . $row_num, $lama_pelaksanaan); $col++; // Lama Pelaksanaan (hari)
            $sheet->setCellValue($col . $row_num, $data->periode ? $data->periode : ''); // Periode PKL
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'export-pengajuan-pkl-' . date("YmdHis") . '.xlsx';
        
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

    // Export data pengajuan PKL dalam format XLSX (alias for export_data_pengajuan_pkl)
    function export_pengajuan_pkl()
    {
        $this->export_data_pengajuan_pkl();
    }

    // Export data assign pembimbing dalam format XLSX
    function export_assign_pembimbing()
    {
        // Check if library exists before using it
        $autoload_path = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            die('Fitur Export Excel memerlukan library PhpSpreadsheet. Silakan install dependencies terlebih dahulu.');
        }
        
        require_once $autoload_path;
        
        // Check if specific assignments are selected
        $selected_ids = $this->input->get('selected');
        
        // Get all assignment data - including both assigned and unassigned students
        $this->db->select('
            tb_siswa.siswa_id,
            tb_siswa.siswa_code,
            tb_siswa.siswa_nama,
            tb_siswa.siswa_kelas,
            tb_siswa.siswa_jurusan,
            tb_siswa.siswa_nis,
            tb_siswa.siswa_nisn,
            tb_siswa.status_pengajuan,
            tb_dudi.dudi_nama,
            tb_pembimbing.pembimbing_nama,
            tb_pembimbing.pembimbing_nip,
            tb_pembimbing.tempat_tugas,
            tb_pengelompokan.id as kelompok_id,
            tb_pengelompokan.created_at as assigned_date
        ');
        $this->db->from('tb_siswa');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->join('tb_pengelompokan', 'tb_pengelompokan.siswa_id = tb_siswa.siswa_id', 'left');
        $this->db->join('tb_pembimbing', 'tb_pembimbing.pembimbing_id = tb_pengelompokan.pembimbing_id', 'left');
        $this->db->where('tb_siswa.status_pengajuan', 'disetujui');
        
        // Filter by selected IDs if provided (kelompok_id for assigned, siswa_id for unassigned)
        if ($selected_ids) {
            $selected_array = explode(',', $selected_ids);
            // Check if we're filtering by kelompok_id (assigned) or siswa_code (unassigned/assigned)
            $this->db->group_start();
            $this->db->where_in('tb_pengelompokan.id', $selected_array);
            $this->db->or_where_in('tb_siswa.siswa_code', $selected_array);
            $this->db->group_end();
        }
        
        $this->db->order_by('tb_pengelompokan.id', 'DESC');
        $this->db->order_by('tb_siswa.siswa_nama', 'ASC');
        $assignments = $this->db->get();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = [
            'Nama Siswa',
            'NIS',
            'NISN',
            'Kelas',
            'Jurusan',
            'Perusahaan (DUDI)',
            'Status Pengajuan',
            'Pembimbing',
            'NIP Pembimbing',
            'Tempat Tugas Pembimbing',
            'Tanggal Assign',
            'Status Assignment'
        ];
        
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Fill data rows
        $row_num = 2;
        foreach($assignments->result() as $data){
            $col = 'A';
            $sheet->setCellValue($col . $row_num, $data->siswa_nama ? $data->siswa_nama : ''); $col++; // Nama Siswa
            $sheet->setCellValue($col . $row_num, $data->siswa_nis ? $data->siswa_nis : ''); $col++; // NIS
            $sheet->setCellValue($col . $row_num, $data->siswa_nisn ? $data->siswa_nisn : ''); $col++; // NISN
            $sheet->setCellValue($col . $row_num, $data->siswa_kelas ? $data->siswa_kelas : ''); $col++; // Kelas
            $sheet->setCellValue($col . $row_num, $data->siswa_jurusan ? $data->siswa_jurusan : ''); $col++; // Jurusan
            $sheet->setCellValue($col . $row_num, $data->dudi_nama ? $data->dudi_nama : ''); $col++; // Perusahaan (DUDI)
            $sheet->setCellValue($col . $row_num, $data->status_pengajuan ? $data->status_pengajuan : ''); $col++; // Status Pengajuan
            
            // Pembimbing columns - show "Belum Di-Assign" if not assigned
            if ($data->kelompok_id) {
                $sheet->setCellValue($col . $row_num, $data->pembimbing_nama ? $data->pembimbing_nama : '-'); $col++; // Pembimbing
                $sheet->setCellValue($col . $row_num, $data->pembimbing_nip ? $data->pembimbing_nip : '-'); $col++; // NIP Pembimbing
                $sheet->setCellValue($col . $row_num, $data->tempat_tugas ? $data->tempat_tugas : '-'); $col++; // Tempat Tugas Pembimbing
                $sheet->setCellValue($col . $row_num, $data->assigned_date ? date('Y-m-d H:i:s', strtotime($data->assigned_date)) : ''); $col++; // Tanggal Assign
                $sheet->setCellValue($col . $row_num, 'Sudah Di-Assign'); // Status Assignment
            } else {
                $sheet->setCellValue($col . $row_num, 'Belum Di-Assign'); $col++; // Pembimbing
                $sheet->setCellValue($col . $row_num, '-'); $col++; // NIP Pembimbing
                $sheet->setCellValue($col . $row_num, '-'); $col++; // Tempat Tugas Pembimbing
                $sheet->setCellValue($col . $row_num, '-'); $col++; // Tanggal Assign
                $sheet->setCellValue($col . $row_num, 'Belum Di-Assign'); // Status Assignment
            }
            
            $row_num++;
        }
        
        // Auto-size columns
        foreach(range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'export-assign-pembimbing-' . date("YmdHis") . '.xlsx';
        
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