<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('userdata')){
            redirect('auth/');
        }elseif($this->session->userdata('userdata')['level'] != 2 && $this->session->userdata('userdata')['level'] != 3){
            redirect('auth/');
        }
    }
    
    // Calculate distance between two coordinates (Haversine formula)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $R = 6371000; // Earth radius in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = 
            sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }
    
    function view($page = 'home')
    {
        // Handle detail-pengumuman dengan parameter
        if($page == 'detail-pengumuman'){
            $pengumuman_id = $this->uri->segment(4);
            if($pengumuman_id){
                $data['content'] = 'siswa/detail-pengumuman';
            } else {
                $data['content'] = '404';
            }
        } else if($page == 'detail-perusahaan') {
            $data['content'] = 'siswa/detail-perusahaan';
        } else if($page == 'pengajuan') {
            $data['content'] = 'siswa/pengajuan';
        } else if($page == 'pengajuan-sukses') {
            $data['content'] = 'siswa/pengajuan-sukses';
        } else if($page == 'buat-pengajuan') {
            // Check if student already has pending application
            $userdata = $this->session->userdata('userdata');
            $this->db->where('user_id', $userdata['id']);
            $siswa = $this->db->get('tb_siswa')->row();
            
            // If student already has a pending application (not rejected or draft), redirect to pengajuan-sukses
            if($siswa && $siswa->status_pengajuan == 'menunggu'){
                redirect('siswa/view/pengajuan-sukses');
                return;
            }
            // Allow access if student has been rejected or has draft, or no record exists yet
            $data['content'] = 'siswa/buat-pengajuan';
        } else if(!file_exists(APPPATH . 'views/siswa/' . $page . '.php')){
            $data['content'] = '404';
        }else{
            // Use simplified home page
            if($page == 'home'){
                $data['content'] = 'siswa/home';
            } else {
                $data['content'] = 'siswa/' . $page;
            }
        }
        
        // Load helpers
        $this->load->helper('text');
        
        // Load pagination library for data tables
        // (pengajuan page removed - pagination no longer needed)
        
        // Load user data for profile page
        if($page == 'profile'){
            $this->load->model('M_user');
            $userdata = $this->session->userdata('userdata');
            $data['user'] = $this->M_user->get_user_by_id($userdata['id']);
            
            // Load student data
            $this->db->where('user_id', $userdata['id']);
            $data['siswa'] = $this->db->get('tb_siswa')->row();
        }
        
        // Load QR generator for ID card page
        if($page == 'id-card'){
            $this->load->library('qr_generator');
            
            // Fetch required data for the view
            $userdata = $this->session->userdata('userdata');
            $this->db->select('s.*, u.nama_lengkap, u.foto_profil, d.dudi_nama, d.dudi_id');
            $this->db->from('tb_siswa s');
            $this->db->join('tb_user u', 'u.id = s.user_id', 'left');
            $this->db->join('tb_dudi d', 'd.dudi_id = s.dudi_id', 'left');
            $this->db->where('s.user_id', $userdata['id']);
            $siswa = $this->db->get()->row();
            
            // Generate QR code after student data is fetched
            $data['qr_code_url'] = $this->qr_generator->generate_student_qr($siswa);
            
            if($siswa){
                // Pre-process data for faster view rendering
                $siswa->foto_profil_url = null;
                if($siswa->user_id && $siswa->foto_profil && file_exists('./uploads/profil/'.$siswa->foto_profil)){
                    $siswa->foto_profil_url = base_url('uploads/profil/'.$siswa->foto_profil);
                }
                $data['siswa'] = $siswa;
            }
            
            // Load ID card as standalone page without template wrapper
            $this->load->view('siswa/id-card', $data, FALSE);
            return; // Exit early to prevent loading template
        }
        
        // Load data for kontak pembimbing page
        if($page == 'kontak-pembimbing'){
            $this->load->model('M_pengelompokan');
            $this->load->model('M_pembimbing');
            $userdata = $this->session->userdata('userdata');
            
            // Get student data
            $this->db->where('user_id', $userdata['id']);
            $siswa = $this->db->get('tb_siswa')->row();
            
            if($siswa){
                // Get pembimbing assigned to this student
                $this->db->select('tb_pengelompokan.pembimbing_id');
                $this->db->from('tb_pengelompokan');
                $this->db->where('tb_pengelompokan.siswa_id', $siswa->siswa_id);
                $pengelompokan = $this->db->get()->row();
                
                if($pengelompokan){
                    // Get pembimbing data (no longer joined with user table)
                    $pembimbing = $this->M_pembimbing->get_pembimbing_by_id($pengelompokan->pembimbing_id);
                    
                    if($pembimbing){
                        $data['pembimbing'] = $pembimbing;
                    } else {
                        $data['pembimbing'] = null;
                    }
                } else {
                    $data['pembimbing'] = null;
                }
            } else {
                $data['pembimbing'] = null;
            }
        }
        
        // Load data for home page (supervisor information)
        if($page == 'home'){
            $this->load->model('M_pengelompokan');
            
            // Check if mentor assignment notification has already been shown
            $userdata = $this->session->userdata('userdata');
            $this->db->where('user_id', $userdata['id']);
            $siswa = $this->db->get('tb_siswa')->row();
            
            if($siswa && $siswa->status_pengajuan == 'disetujui') {
                $pembimbing_info = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
                
                // If mentor is assigned and hasn't been notified yet ever (not just this session)
                // Check if the property exists to avoid undefined property error
                $mentorNotified = isset($siswa->mentor_assignment_notified) ? $siswa->mentor_assignment_notified : 0;
                if($pembimbing_info && !$mentorNotified) {
                    // Check if the column exists before updating to avoid database errors
                    $columns_query = $this->db->query("SHOW COLUMNS FROM tb_siswa LIKE 'mentor_assignment_notified'");
                    if($columns_query->num_rows() > 0) {
                        // Update the database to mark that the notification has been shown
                        $this->db->where('siswa_id', $siswa->siswa_id);
                        $this->db->update('tb_siswa', array('mentor_assignment_notified' => 1));
                    }
                    
                    // Set message to be displayed in the view
                    $pembimbing_nama = isset($pembimbing_info->user_nama) && $pembimbing_info->user_nama ? 
                                      $pembimbing_info->user_nama : 
                                      (isset($pembimbing_info->pembimbing_nama) ? $pembimbing_info->pembimbing_nama : 'Pembimbing');
                    
                    $notification_html = '
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Selamat!</strong> Anda telah ditugaskan pembimbing. 
                        Guru pembimbing Anda adalah <strong>'.$pembimbing_nama.'</strong>.
                        <a href="'.base_url('siswa/view/kontak-pembimbing').'" class="alert-link ms-2">Lihat kontak pembimbing</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    
                    $data['mentor_assignment_notification'] = $notification_html;
                }
            }
        }
        
        // Load data for penempatan page
        if($page == 'penempatan'){
            $this->load->model('M_pengelompokan');
        }
        

        
        // Load template khusus untuk siswa (mobile-friendly)
        $this->load->view('template_siswa', $data, FALSE);
    }
    
    // Tampilkan form detail perusahaan untuk siswa yang memilih "other"
    function detail_perusahaan()
    {
        $data['content'] = 'siswa/detail-perusahaan';
        $this->load->view('template_siswa', $data, FALSE);
    }
    
    // Simpan detail perusahaan dari siswa
    function simpan_detail_perusahaan()
    {
        $status = true;
        $userdata = $this->session->userdata('userdata');
        
        // Validasi input
        $this->form_validation->set_rules('dudi_alamat', 'Alamat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }
        
        if($status){
            // Get student data
            $this->db->where('user_id', $userdata['id']);
            $siswa = $this->db->get('tb_siswa')->row();
            
            if(!$siswa || !$siswa->other_dudi_nama){
                $this->session->set_flashdata('error_message', 'Data tidak valid');
                redirect('siswa/view/home');
            }
            
            // Prepare company data
            $dudi_data = array(
                'dudi_nama' => $siswa->other_dudi_nama,
                'dudi_telepon' => $this->input->post('dudi_telepon'),
                'dudi_alamat' => $this->input->post('dudi_alamat'),
                'dudi_email' => $this->input->post('dudi_email'),
                'dudi_pic' => $this->input->post('dudi_pic'),
                'dudi_nip_pic' => $this->input->post('dudi_nip_pic'),
                'dudi_instruktur' => $this->input->post('dudi_instruktur'),
                'dudi_nip_instruktur' => $this->input->post('dudi_nip_instruktur'),
                'dudi_code' => 'dudi_' . time(),
                'is_mitra' => 0,  // Student-added companies are non-mitra by default
                'status_kerjasama' => 'non_mitra',  // Consistent status
                'sumber_data' => 'siswa'  // Mark as student-submitted
            );
            
            // Insert company data
            $this->db->insert('tb_dudi', $dudi_data);
            $dudi_id = $this->db->insert_id();
            
            // Update student with new company ID
            $siswa_update = array(
                'dudi_id' => $dudi_id,
                'other_dudi_nama' => null
            );
            
            $this->db->where('siswa_id', $siswa->siswa_id);
            $this->db->update('tb_siswa', $siswa_update);
            
            $this->session->set_flashdata('message', 'Detail perusahaan berhasil disimpan');
            redirect('siswa/view/home');
        } else {
            $this->detail_perusahaan();
        }
    }
    
    // Pengajuan PKL
    function buat_pengajuan()
    {
        $status = true;
        $userdata = $this->session->userdata('userdata');
        
        $this->form_validation->set_rules('siswa_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('siswa_telepon', 'Telepon', 'required|callback_valid_phone_number');
        $this->form_validation->set_rules('siswa_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('dudi_id', 'Perusahaan', 'required');
        $this->form_validation->set_rules('periode', 'Periode PKL', 'required');
        
        // Validasi tambahan untuk perusahaan lainnya
        $dudi_id = $this->input->post('dudi_id');
        if($dudi_id == 'other'){
            $this->form_validation->set_rules('other_dudi_nama', 'Nama Perusahaan Lainnya', 'required');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            $this->load->model('M_siswa');
            $code = $this->M_siswa->generateRandomString(30);
            
            // Cek apakah siswa sudah punya data
            $this->db->where('user_id', $userdata['id']);
            $cek_siswa = $this->db->get('tb_siswa')->row();
            
            // Logging untuk debugging
            log_message('debug', 'Pengajuan PKL: cek_siswa=' . print_r($cek_siswa, true));
            log_message('debug', 'Pengajuan PKL: status_pengajuan=' . ($cek_siswa ? $cek_siswa->status_pengajuan : 'null'));
            
            // Persiapkan data
            $data = array(
                'siswa_kelas' => $this->input->post('siswa_kelas'),
                'siswa_telepon' => $this->input->post('siswa_telepon'),
                'siswa_alamat' => $this->input->post('siswa_alamat'),
                'periode' => $this->input->post('periode'),
                'status_pengajuan' => 'menunggu',
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Tangani pilihan perusahaan
            if($dudi_id == 'other'){
                // Jika memilih "Lainnya", simpan nama perusahaan di field khusus
                $data['other_dudi_nama'] = $this->input->post('other_dudi_nama');
                $data['dudi_id'] = NULL; // Set dudi_id ke NULL karena belum ada di database
                
                // Simpan data lengkap perusahaan yang tidak terdaftar
                $data['other_dudi_alamat'] = $this->input->post('other_dudi_alamat');
                $data['other_dudi_telepon'] = $this->input->post('other_dudi_telepon');
                $data['other_dudi_email'] = $this->input->post('other_dudi_email');
                $data['other_dudi_pic'] = $this->input->post('other_dudi_pic');
                $data['other_dudi_nip_pic'] = $this->input->post('other_dudi_nip_pic');
                $data['other_dudi_instruktur'] = $this->input->post('other_dudi_instruktur');
                $data['other_dudi_nip_instruktur'] = $this->input->post('other_dudi_nip_instruktur');
            } else {
                // Jika memilih perusahaan yang sudah ada
                $data['dudi_id'] = $dudi_id;
            }
            
            // Handle upload surat permohonan/pernyataan
            if(!empty($_FILES['surat_permohonan']['name'])){
                $config['upload_path'] = './uploads/pengajuan/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['max_size'] = 5120; // 5MB
                $config['file_name'] = 'surat_permohonan_'.$userdata['id'].'_'.time();
                
                // Buat folder jika belum ada
                if(!is_dir($config['upload_path'])){
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if($this->upload->do_upload('surat_permohonan')){
                    $upload_data = $this->upload->data();
                    // Hapus file lama jika ada
                    if($cek_siswa && $cek_siswa->surat_permohonan && file_exists('./uploads/pengajuan/'.$cek_siswa->surat_permohonan)){
                        unlink('./uploads/pengajuan/'.$cek_siswa->surat_permohonan);
                    }
                    $data['surat_permohonan'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error_message', 'Gagal upload surat permohonan: ' . $this->upload->display_errors('', ''));
                    redirect('siswa/view/buat-pengajuan');
                }
            }
            
            if($cek_siswa && $cek_siswa->status_pengajuan != 'disetujui'){
                // Update existing - bisa ajukan ulang jika ditolak atau draft
                log_message('debug', 'Pengajuan PKL: Updating existing record');
                $this->M_siswa->update_siswa($data, $cek_siswa->siswa_code);
                $this->session->set_flashdata('message', 'Pengajuan PKL berhasil diperbarui dan dikirim');
            } else {
                // Create new - untuk pengajuan pertama atau ajukan ulang setelah disetujui
                log_message('debug', 'Pengajuan PKL: Creating new record');
                $data['user_id'] = $userdata['id'];
                $data['siswa_nama'] = $userdata['nama_lengkap'];
                $data['siswa_code'] = $code;
                $data['siswa_nis'] = isset($userdata['nip_nim']) ? $userdata['nip_nim'] : null; // Add NIS from user profile if available
                unset($data['updated_at']); // Remove updated_at for new record
                $this->M_siswa->tambah_data_siswa($data);
                $this->session->set_flashdata('message', 'Pengajuan PKL baru berhasil dibuat dan dikirim');
            }
            
            redirect('siswa/view/pengajuan-sukses');
        } else {
            $this->view('buat-pengajuan');
        }
    }
    
    // Callback function to validate phone number
    public function valid_phone_number($phone)
    {
        // Allow digits, spaces, hyphens, parentheses, plus signs, and dots
        if (preg_match('/^[0-9\s\-\+\(\)\.]+$/', $phone)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_phone_number', 'Format {field} tidak valid. Gunakan hanya angka dan karakter seperti spasi, tanda hubung, kurung, plus, atau titik.');
            return FALSE;
        }
    }

    // Test method for file upload debugging
    function test_upload()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            log_message('debug', 'Test upload POST data: ' . print_r($_POST, true));
            log_message('debug', 'Test upload FILES data: ' . print_r($_FILES, true));
            
            if(!empty($_FILES['test_file']['name'])) {
                $config['upload_path'] = './uploads/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['file_name'] = 'test_' . time();
                
                $this->load->library('upload', $config);
                
                if($this->upload->do_upload('test_file')) {
                    $upload_data = $this->upload->data();
                    echo "Upload successful: " . $upload_data['file_name'];
                } else {
                    echo "Upload failed: " . $this->upload->display_errors();
                }
            } else {
                echo "No file uploaded";
            }
            return;
        }
        
        // Display test form
        echo '
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="test_file" accept="image/*">
            <button type="submit">Upload Test</button>
        </form>
        ';
    }
    
    function update_profile()
    {
        // Debug session data
        $userdata = $this->session->userdata('userdata');
        log_message('debug', 'Session userdata: ' . print_r($userdata, true));
        
        $status = true;
        $user_code = $userdata['id'];

        $this->load->model('M_user');
        $this->load->model('M_siswa');
        $user = $this->M_user->get_user_by_id($user_code);
        
        // Debug user data
        log_message('debug', 'User data: ' . print_r($user, true));
        
        if(!$user){
            $status = false;
            $this->session->set_flashdata('error_message', 'User tidak ditemukan');
        }

        // Validate required fields
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('siswa_kelas', 'Kelas', 'required');
        
        // Validate NIS/NISN uniqueness for student data
        $nis = $this->input->post('siswa_nis');
        $nisn = $this->input->post('siswa_nisn');
        
        if($nis) {
            // Get the current student record to exclude from uniqueness check
            $this->db->where('user_id', $user_code);
            $current_student = $this->db->get('tb_siswa')->row();
            if($current_student && isset($current_student->siswa_code)) {
                $this->form_validation->set_rules('siswa_nis', 'NIS', 'callback_nis_check['.$current_student->siswa_code.']');
            } else {
                // If no current record exists, just check for general uniqueness
                $this->form_validation->set_rules('siswa_nis', 'NIS', 'is_unique[tb_siswa.siswa_nis]');
            }
        }
        
        if($nisn) {
            // Get the current student record to exclude from uniqueness check
            $this->db->where('user_id', $user_code);
            $current_student = $this->db->get('tb_siswa')->row();
            if($current_student && isset($current_student->siswa_code)) {
                $this->form_validation->set_rules('siswa_nisn', 'NISN', 'callback_nisn_check['.$current_student->siswa_code.']');
            } else {
                // If no current record exists, just check for general uniqueness
                $this->form_validation->set_rules('siswa_nisn', 'NISN', 'is_unique[tb_siswa.siswa_nisn]');
            }
        }
        
        // Run validation but don't fail if only photo upload fails
        if ($this->form_validation->run() == FALSE) {
            log_message('debug', 'Form validation failed: ' . validation_errors());
            $status = false;
        } else {
            log_message('debug', 'Form validation passed');
        }
        
        // Additional validation for file upload
        // Debug: Check if file is received
        log_message('debug', 'FILES array: ' . print_r($_FILES, true));
        log_message('debug', 'POST data: ' . print_r($this->input->post(), true));
        
        if(!empty($_FILES['foto_profil']['name'])){
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $file_type = $_FILES['foto_profil']['type'];
            $file_size = $_FILES['foto_profil']['size'];
            
            if(!in_array($file_type, $allowed_types)){
                $this->session->set_flashdata('error_message', 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                $status = false;
            }
            
            if($file_size > 2097152){ // 2MB in bytes
                $this->session->set_flashdata('error_message', 'Ukuran file terlalu besar. Maksimal 2MB.');
                $status = false;
            }
        }

        if($status){
            // Update user data
            $jenis_kelamin = $this->input->post('jenis_kelamin');
            log_message('debug', 'Jenis kelamin from POST: ' . $jenis_kelamin);
            log_message('debug', 'POST data: ' . print_r($this->input->post(), true));
            
            // Convert 'L'/'P' to 'Laki-laki'/'Perempuan' to match database ENUM
            if ($jenis_kelamin === 'L') {
                $jenis_kelamin = 'Laki-laki';
                log_message('debug', 'Converted L to Laki-laki');
            } elseif ($jenis_kelamin === 'P') {
                $jenis_kelamin = 'Perempuan';
                log_message('debug', 'Converted P to Perempuan');
            } else {
                log_message('debug', 'No conversion needed or empty value');
            }
            
            log_message('debug', 'Jenis kelamin after conversion: ' . $jenis_kelamin);
            
            $user_update = array(
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $this->input->post('alamat'),
                'email' => $this->input->post('email'),
                'telepon' => $this->input->post('telepon'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            // Handle upload foto
            if(!empty($_FILES['foto_profil']['name'])){
                log_message('debug', 'Starting file upload process');
                $config['upload_path'] = './uploads/profil/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'profil_'.$user_code.'_'.time();
                $config['overwrite'] = FALSE; // Changed to FALSE to prevent conflicts
                
                // Buat folder jika belum ada
                if(!is_dir($config['upload_path'])){
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if($this->upload->do_upload('foto_profil')){
                    log_message('debug', 'File upload successful');
                    $upload_data = $this->upload->data();
                    log_message('debug', 'Upload data: ' . print_r($upload_data, true));
                    // Hapus foto lama jika ada
                    if(isset($user->foto_profil) && $user->foto_profil && file_exists('./uploads/profil/'.$user->foto_profil)){
                        unlink('./uploads/profil/'.$user->foto_profil);
                    }
                    $user_update['foto_profil'] = $upload_data['file_name'];
                    $this->session->set_flashdata('message', 'Profile dan foto berhasil diupdate');
                } else {
                    $error = $this->upload->display_errors('', '');
                    log_message('debug', 'File upload failed: ' . $error);
                    $this->session->set_flashdata('error_message', 'Gagal upload foto: '.$error);
                    // Continue with profile update even if photo upload fails
                }
            }

            // Update user table
            log_message('debug', 'Updating user with data: ' . print_r($user_update, true));
            $update_result = $this->M_user->update_user_by_id($user_update, $user_code);
            log_message('debug', 'Update result: ' . ($update_result ? 'success' : 'failed'));
            
            // Get student data
            $this->db->where('user_id', $user_code);
            $siswa = $this->db->get('tb_siswa')->row();
            
            if($siswa){
                // Update student data
                $siswa_update = array(
                    'siswa_nama' => $this->input->post('nama_lengkap'), // Sync with user name
                    'siswa_nis' => $this->input->post('siswa_nis'),
                    'siswa_kelas' => $this->input->post('siswa_kelas'),
                    'siswa_jurusan' => $this->input->post('siswa_jurusan')
                );
                
                // Filter empty values
                $siswa_update = array_filter($siswa_update, function($value) {
                    return $value !== '' && $value !== null;
                });
                
                $siswa_result = $this->M_siswa->update_siswa_by_user_id($siswa_update, $user_code);
                
                if($siswa_result === false) {
                    $this->session->set_flashdata('error_message', 'Gagal mengupdate data siswa. Pastikan NIS tidak duplikat dengan siswa lain.');
                    redirect('siswa/view/profile');
                    return;
                }
            } else {
                // Create new student record if not exists
                $siswa_data = array(
                    'siswa_nama' => $this->input->post('nama_lengkap'), // Sync with user name
                    'siswa_nis' => $this->input->post('siswa_nis'),
                    'siswa_kelas' => $this->input->post('siswa_kelas'),
                    'siswa_jurusan' => $this->input->post('siswa_jurusan'),
                    'user_id' => $user_code,
                    'siswa_code' => 'siswa_' . time() . '_' . rand(1000, 9999)
                );
                
                // Filter empty values
                $siswa_data = array_filter($siswa_data, function($value) {
                    return $value !== '' && $value !== null;
                });
                
                $siswa_create_result = $this->M_siswa->tambah_data_siswa($siswa_data);
                
                if($siswa_create_result === false) {
                    $this->session->set_flashdata('error_message', 'Gagal membuat data siswa. Pastikan NIS tidak duplikat dengan siswa lain.');
                    redirect('siswa/view/profile');
                    return;
                }
            }
            
            // Get updated user data
            $updated_user = $this->M_user->get_user_by_id($user_code);
            
            // Update session
            $user_login = array(
                'userdata' => array(
                    'id' => $userdata['id'],
                    'username' => $userdata['username'],
                    'nama_lengkap' => $user_update['nama_lengkap'],
                    'level' => $userdata['level'],
                    'group_name' => $userdata['group_name'],
                    'foto_profil' => isset($user_update['foto_profil']) ? $user_update['foto_profil'] : (isset($updated_user->foto_profil) ? $updated_user->foto_profil : null)
                ),
            );
            $this->session->set_userdata($user_login);
            
            // Only show general success message if no photo was uploaded
            if(empty($_FILES['foto_profil']['name'])) {
                $this->session->set_flashdata('message', 'Profile berhasil diupdate');
            }
            redirect('siswa/view/profile');
        } else {
            $data['user'] = $user;
            $this->db->where('user_id', $user_code);
            $data['siswa'] = $this->db->get('tb_siswa')->row();
            $data['content'] = 'siswa/profile';
            $this->load->view('template_siswa', $data, FALSE);
        }
    }

    function change_password()
    {
        $status = true;
        $userdata = $this->session->userdata('userdata');
        $user_code = $userdata['id'];

        $this->load->model('M_user');
        $user = $this->M_user->get_user_by_id($user_code);
        
        if(!$user){
            $status = false;
            $this->session->set_flashdata('error_message', 'User tidak ditemukan');
        }

        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() == FALSE) {
            $status = false;
        }

        if($status){
            if(!password_verify($this->input->post('password_lama'), $user->password)){
                $this->session->set_flashdata('error_message', 'Password lama tidak sesuai');
                $status = false;
            }

            if($status){
                $hash = password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT);
                $update = array('password' => $hash);
                $this->M_user->update_user_by_id($update, $user_code);
                
                $this->session->set_flashdata('message', 'Password berhasil diubah');
                redirect('siswa/view/change-password');
            }
        }

        $data['content'] = 'siswa/change-password';
        $this->load->view('template', $data, FALSE);
    }

    
    // Callback function to validate NIS uniqueness (excluding current record)
    public function nis_check($nis, $current_siswa_code)
    {
        if (empty($nis)) {
            return TRUE; // Skip validation if empty
        }
        
        $this->db->where('siswa_nis', $nis);
        $this->db->where('siswa_code !=', $current_siswa_code);
        $query = $this->db->get('tb_siswa');
        
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('nis_check', 'NIS sudah digunakan oleh siswa lain');
            return FALSE;
        }
        
        return TRUE;
    }
    
    // Callback function to validate NISN uniqueness (excluding current record)
    public function nisn_check($nisn, $current_siswa_code)
    {
        if (empty($nisn)) {
            return TRUE; // Skip validation if empty
        }
        
        $this->db->where('siswa_nisn', $nisn);
        $this->db->where('siswa_code !=', $current_siswa_code);
        $query = $this->db->get('tb_siswa');
        
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('nisn_check', 'NISN sudah digunakan oleh siswa lain');
            return FALSE;
        }
        
        return TRUE;
    }
    
    // API endpoint to check NIS availability
    function check_nis_availability()
    {
        // Only allow AJAX requests
        if(!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $nis = isset($input['nis']) ? trim($input['nis']) : '';
        $current_nis = isset($input['current_nis']) ? trim($input['current_nis']) : '';
        
        $response = array(
            'available' => false,
            'message' => ''
        );
        
        // Validate input
        if(empty($nis)) {
            $response['message'] = 'NIS tidak boleh kosong';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Validate NIS format
        if(!preg_match('/^[0-9]{10,15}$/', $nis)) {
            $response['message'] = 'NIS harus berupa angka 10-15 digit';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Check if NIS is the same as current user's NIS
        if($nis === $current_nis) {
            $response['available'] = true;
            $response['message'] = 'NIS ini milik Anda';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Check if NIS already exists in database
        $this->db->where('siswa_nis', $nis);
        $existing_siswa = $this->db->get('tb_siswa')->row();
        
        if($existing_siswa) {
            $response['message'] = 'NIS sudah digunakan oleh siswa lain (' . $existing_siswa->siswa_nama . ' dari kelas ' . $existing_siswa->siswa_kelas . ')';
        } else {
            $response['available'] = true;
            $response['message'] = 'NIS tersedia';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    // API endpoint to check NISN availability
    function check_nisn_availability()
    {
        // Only allow AJAX requests
        if(!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $nisn = isset($input['nisn']) ? trim($input['nisn']) : '';
        $current_nisn = isset($input['current_nisn']) ? trim($input['current_nisn']) : '';
        
        $response = array(
            'available' => false,
            'message' => ''
        );
        
        // Validate input
        if(empty($nisn)) {
            $response['message'] = 'NISN tidak boleh kosong';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Validate NISN format
        if(!preg_match('/^[0-9]{10}$/', $nisn)) {
            $response['message'] = 'NISN harus berupa angka 10 digit';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Check if NISN is the same as current user's NISN
        if($nisn === $current_nisn) {
            $response['available'] = true;
            $response['message'] = 'NISN ini milik Anda';
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
        
        // Check if NISN already exists in database
        $this->db->where('siswa_nisn', $nisn);
        $existing_siswa = $this->db->get('tb_siswa')->row();
        
        if($existing_siswa) {
            $response['message'] = 'NISN sudah digunakan oleh siswa lain (' . $existing_siswa->siswa_nama . ' dari kelas ' . $existing_siswa->siswa_kelas . ')';
        } else {
            $response['available'] = true;
            $response['message'] = 'NISN tersedia';
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    // API endpoint to check pembimbing assignment status
    function check_pembimbing_assignment()
    {
        $this->load->model('M_pengelompokan');
        
        $userdata = $this->session->userdata('userdata');
        $this->db->where('user_id', $userdata['id']);
        $siswa = $this->db->get('tb_siswa')->row();
        
        $response = array('has_pembimbing' => false);
        
        if($siswa && $siswa->status_pengajuan == 'disetujui') {
            $pembimbing = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
            if($pembimbing) {
                $response = array(
                    'has_pembimbing' => true,
                    'pembimbing_nama' => isset($pembimbing->user_nama) && $pembimbing->user_nama ? 
                                       $pembimbing->user_nama : 
                                       (isset($pembimbing->pembimbing_nama) ? $pembimbing->pembimbing_nama : 'Pembimbing')
                );
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
}
