<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_sync extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function sync_database_structure()
    {
        echo "<h2>Database Structure Synchronization</h2>";
        
        // Check and add missing columns to tb_siswa
        $this->sync_siswa_table();
        
        // Check and add missing columns to tb_pembimbing
        $this->sync_pembimbing_table();
        
        // Check and add missing columns to tb_dudi
        $this->sync_dudi_table();
        
        echo "<h3>✓ Database synchronization completed!</h3>";
        echo "<p>All tables are now aligned with the application structure.</p>";
    }
    
    private function sync_siswa_table()
    {
        echo "<h4>Syncing tb_siswa table...</h4>";
        
        // Check existing columns
        $fields = $this->db->field_data('tb_siswa');
        $existing_columns = array();
        foreach($fields as $field) {
            $existing_columns[] = $field->name;
        }
        
        // Required columns for tb_siswa
        $required_columns = array(
            'siswa_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            'siswa_code' => 'VARCHAR(30) NOT NULL',
            'user_id' => 'INT(11) DEFAULT NULL',
            'dudi_id' => 'INT(11) DEFAULT NULL',
            'pembimbing_id' => 'INT(11) DEFAULT NULL',
            'siswa_nama' => 'VARCHAR(255) NOT NULL',
            'siswa_kelas' => 'VARCHAR(20) DEFAULT NULL',
            'siswa_jurusan' => 'VARCHAR(100) DEFAULT NULL',
            'siswa_nis' => 'VARCHAR(50) DEFAULT NULL',
            'siswa_nisn' => 'VARCHAR(50) DEFAULT NULL',
            'siswa_jk' => "ENUM('L','P') DEFAULT NULL",
            'siswa_tempat_lahir' => 'VARCHAR(255) DEFAULT NULL',
            'siswa_tanggal_lahir' => 'DATE DEFAULT NULL',
            'siswa_alamat' => 'TEXT DEFAULT NULL',
            'siswa_no_hp' => 'VARCHAR(20) DEFAULT NULL',
            'siswa_asal_sekolah' => 'VARCHAR(255) DEFAULT NULL',
            'status_pengajuan' => "ENUM('menunggu','disetujui','ditolak','selesai') DEFAULT 'menunggu'",
            'tanggal_mulai' => 'DATE DEFAULT NULL',
            'tanggal_selesai' => 'DATE DEFAULT NULL',
            'lama_pelaksanaan' => 'INT(11) DEFAULT NULL',
            'keterangan' => 'TEXT DEFAULT NULL',
            'is_active' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'surat_permohonan' => 'VARCHAR(255) DEFAULT NULL',
            'surat_balasan' => 'VARCHAR(255) DEFAULT NULL',
            'periode' => 'VARCHAR(20) DEFAULT NULL',
            'other_dudi_nama' => 'VARCHAR(255) DEFAULT NULL',
            'other_dudi_alamat' => 'TEXT DEFAULT NULL',
            'other_dudi_telepon' => 'VARCHAR(20) DEFAULT NULL',
            'other_dudi_email' => 'VARCHAR(255) DEFAULT NULL',
            'other_dudi_pic' => 'VARCHAR(255) DEFAULT NULL',
            'other_dudi_nip_pic' => 'VARCHAR(50) DEFAULT NULL',
            'other_dudi_instruktur' => 'VARCHAR(255) DEFAULT NULL',
            'other_dudi_nip_instruktur' => 'VARCHAR(50) DEFAULT NULL',
            'siswa_telepon' => 'VARCHAR(20) DEFAULT NULL',
            'updated_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
        
        // Add missing columns
        foreach($required_columns as $column => $definition) {
            if(!in_array($column, $existing_columns)) {
                $this->db->query("ALTER TABLE `tb_siswa` ADD COLUMN `{$column}` {$definition}");
                echo "✓ Added column: {$column}<br>";
            }
        }
        
        // Add indexes if they don't exist
        $this->add_unique_index('tb_siswa', 'unique_siswa_nis', 'siswa_nis');
        $this->add_unique_index('tb_siswa', 'unique_siswa_nisn', 'siswa_nisn');
    }
    
    private function sync_pembimbing_table()
    {
        echo "<h4>Syncing tb_pembimbing table...</h4>";
        
        // Check existing columns
        $fields = $this->db->field_data('tb_pembimbing');
        $existing_columns = array();
        foreach($fields as $field) {
            $existing_columns[] = $field->name;
        }
        
        // Required columns for tb_pembimbing
        $required_columns = array(
            'pembimbing_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            'pembimbing_code' => 'VARCHAR(30) NOT NULL',
            'user_id' => 'INT(11) DEFAULT NULL',
            'pembimbing_nama' => 'VARCHAR(255) NOT NULL',
            'pembimbing_nip' => 'VARCHAR(50) DEFAULT NULL',
            'pembimbing_telepon' => 'VARCHAR(20) DEFAULT NULL',
            'pembimbing_email' => 'VARCHAR(255) DEFAULT NULL',
            'pembimbing_alamat' => 'TEXT DEFAULT NULL',
            'pendidikan_terakhir' => 'VARCHAR(100) DEFAULT NULL',
            'jabatan' => 'VARCHAR(100) DEFAULT NULL',
            'jurusan_keahlian' => 'VARCHAR(100) DEFAULT NULL',
            'tahun_masuk' => 'YEAR DEFAULT NULL',
            'status_kepegawaian' => 'VARCHAR(50) DEFAULT NULL',
            'tempat_tugas' => 'VARCHAR(255) DEFAULT NULL',
            'is_active' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
        
        // Add missing columns
        foreach($required_columns as $column => $definition) {
            if(!in_array($column, $existing_columns)) {
                $this->db->query("ALTER TABLE `tb_pembimbing` ADD COLUMN `{$column}` {$definition}");
                echo "✓ Added column: {$column}<br>";
            }
        }
        
        // Add indexes if they don't exist
        $this->add_unique_index('tb_pembimbing', 'unique_pembimbing_nip', 'pembimbing_nip');
        $this->add_unique_index('tb_pembimbing', 'unique_pembimbing_email', 'pembimbing_email');
    }
    
    private function sync_dudi_table()
    {
        echo "<h4>Syncing tb_dudi table...</h4>";
        
        // Check existing columns
        $fields = $this->db->field_data('tb_dudi');
        $existing_columns = array();
        foreach($fields as $field) {
            $existing_columns[] = $field->name;
        }
        
        // Required columns for tb_dudi
        $required_columns = array(
            'dudi_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            'dudi_code' => 'VARCHAR(30) NOT NULL',
            'dudi_nama' => 'VARCHAR(255) NOT NULL',
            'dudi_alamat' => 'TEXT DEFAULT NULL',
            'dudi_telepon' => 'VARCHAR(20) DEFAULT NULL',
            'dudi_email' => 'VARCHAR(255) DEFAULT NULL',
            'dudi_pic' => 'VARCHAR(255) DEFAULT NULL',
            'dudi_nip_pic' => 'VARCHAR(50) DEFAULT NULL',
            'dudi_instruktur' => 'VARCHAR(255) DEFAULT NULL',
            'dudi_nip_instruktur' => 'VARCHAR(50) DEFAULT NULL',
            'status_kerjasama' => "ENUM('mitra','non_mitra','pengajuan') DEFAULT 'pengajuan'",
            'is_mitra' => 'TINYINT(1) DEFAULT 0',
            'sumber_data' => "ENUM('sekolah','siswa') DEFAULT 'sekolah'",
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
        
        // Add missing columns
        foreach($required_columns as $column => $definition) {
            if(!in_array($column, $existing_columns)) {
                $this->db->query("ALTER TABLE `tb_dudi` ADD COLUMN `{$column}` {$definition}");
                echo "✓ Added column: {$column}<br>";
            }
        }
        
        // Add indexes if they don't exist
        $this->add_unique_index('tb_dudi', 'unique_dudi_nama', 'dudi_nama');
        $this->add_unique_index('tb_dudi', 'unique_dudi_email', 'dudi_email');
    }
    
    private function add_unique_index($table, $index_name, $column)
    {
        // Check if index exists
        $query = $this->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$index_name}'");
        
        if($query->num_rows() == 0) {
            $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE `{$index_name}` (`{$column}`)");
            echo "✓ Added unique index: {$index_name} on {$column}<br>";
        }
    }
    
    public function update_sample_data()
    {
        echo "<h2>Updating Sample Data</h2>";
        
        // Update sample student data with more complete information
        $sample_siswa_data = array(
            'siswa_nis' => '1234567890',
            'siswa_nisn' => '0987654321',
            'siswa_jk' => 'L',
            'siswa_tempat_lahir' => 'Bandung',
            'siswa_tanggal_lahir' => '2005-05-15',
            'siswa_alamat' => 'Jl. Contoh No. 124',
            'siswa_no_hp' => '081234567891',
            'siswa_asal_sekolah' => 'SMK ABC',
            'siswa_jurusan' => 'Rekayasa Perangkat Lunak',
            'tanggal_mulai' => '2024-06-01',
            'tanggal_selesai' => '2024-07-30',
            'lama_pelaksanaan' => 60,
            'keterangan' => 'Melaksanakan PKL dengan baik'
        );
        
        $this->db->where('siswa_id', 1);
        $this->db->update('tb_siswa', $sample_siswa_data);
        echo "✓ Updated sample student data<br>";
        
        // Update sample pembimbing data
        $sample_pembimbing_data = array(
            'tempat_tugas' => 'Sekolah ABC',
            'pendidikan_terakhir' => 'S2',
            'jabatan' => 'Guru',
            'jurusan_keahlian' => 'Teknologi Informasi',
            'tahun_masuk' => '2010',
            'status_kepegawaian' => 'PNS'
        );
        
        $this->db->where('pembimbing_id', 1);
        $this->db->update('tb_pembimbing', $sample_pembimbing_data);
        echo "✓ Updated sample pembimbing data<br>";
        
        echo "<h3>✓ Sample data updated successfully!</h3>";
    }
    
    public function run_complete_sync()
    {
        echo "<h1>Complete Database Synchronization</h1>";
        $this->sync_database_structure();
        echo "<hr>";
        $this->update_sample_data();
        echo "<hr>";
        $this->remove_duplicate_data();
        echo "<hr>";
        $this->cleanup_unused_fields();
        $this->sync_approved_companies_to_dudi();
        echo "<hr>";
        echo "<h2>🎉 All synchronization completed!</h2>";
    }
    
    public function remove_duplicate_data()
    {
        echo "<h2>Removing Duplicate Data</h2>";
        
        // Remove duplicates based on NIS/NISN
        $this->db->query("
            DELETE s1 FROM tb_siswa s1
            INNER JOIN tb_siswa s2 
            WHERE s1.siswa_id > s2.siswa_id 
            AND (
                (s1.siswa_nis = s2.siswa_nis AND s1.siswa_nis IS NOT NULL) 
                OR 
                (s1.siswa_nisn = s2.siswa_nisn AND s1.siswa_nisn IS NOT NULL)
            )
        ");
        
        $affected = $this->db->affected_rows();
        echo "<p>Removed $affected duplicate records based on NIS/NISN</p>";
        
        // Add unique constraints if not exists
        $this->db->query("ALTER TABLE tb_siswa ADD UNIQUE KEY IF NOT EXISTS unique_siswa_nis (siswa_nis)");
        $this->db->query("ALTER TABLE tb_siswa ADD UNIQUE KEY IF NOT EXISTS unique_siswa_nisn (siswa_nisn)");
        
        echo "<p>✓ Added unique constraints for NIS and NISN</p>";
        
        // Remove duplicates based on name + class combination
        $this->db->query("
            DELETE s1 FROM tb_siswa s1
            INNER JOIN tb_siswa s2 
            WHERE s1.siswa_id > s2.siswa_id 
            AND s1.siswa_nama = s2.siswa_nama
            AND s1.siswa_kelas = s2.siswa_kelas
            AND s1.siswa_nama IS NOT NULL
            AND s1.siswa_kelas IS NOT NULL
        ");
        
        $affected2 = $this->db->affected_rows();
        if($affected2 > 0) {
            echo "<p>Removed $affected2 duplicate records based on name + class combination</p>";
        }
        
        echo "<p>✓ Duplicate data removal completed!</p>";
    }
    
    public function cleanup_unused_fields()
    {
        echo "<h2>Cleaning Up Unused Fields</h2>";
        
        // Fields that should be kept (used in application)
        $used_fields = array(
            'siswa_id', 'siswa_code', 'user_id', 'dudi_id', 'pembimbing_id',
            'siswa_nama', 'siswa_kelas', 'siswa_jurusan', 'siswa_nis', 'siswa_nisn',
            'siswa_jk', 'siswa_tempat_lahir', 'siswa_tanggal_lahir', 'siswa_alamat',
            'siswa_no_hp', 'siswa_asal_sekolah', 'siswa_telepon', 'status_pengajuan',
            'tanggal_mulai', 'tanggal_selesai', 'lama_pelaksanaan', 'keterangan',
            'is_active', 'created_at', 'updated_at', 'surat_permohonan', 'surat_balasan',
            'periode', 'other_dudi_nama'
        );
        
        // Get all current fields
        $fields = $this->db->field_data('tb_siswa');
        $unused_fields = array();
        
        foreach($fields as $field) {
            if(!in_array($field->name, $used_fields)) {
                $unused_fields[] = $field->name;
            }
        }
        
        if(empty($unused_fields)) {
            echo "<p style='color: green;'>✓ No unused fields found. Database is clean.</p>";
            return;
        }
        
        echo "<p>Found " . count($unused_fields) . " unused fields:</p>";
        echo "<ul>";
        foreach($unused_fields as $field) {
            echo "<li>" . $field . "</li>";
        }
        echo "</ul>";
        
        // Remove unused fields
        foreach($unused_fields as $field) {
            $this->db->query("ALTER TABLE `tb_siswa` DROP COLUMN `{$field}`");
            echo "✓ Removed unused field: {$field}<br>";
        }
        
        echo "<p style='color: green;'>✓ Unused field cleanup completed!</p>";
    }
    
    public function sync_approved_companies_to_dudi()
    {
        echo "<h2>Synchronizing Approved Companies to DUDI Table</h2>";
        
        // Get all students with approved status and assigned to a company (either existing DUDI or proposed company)
        $this->db->select('tb_siswa.*, tb_user.nama_lengkap, tb_dudi.dudi_nama as existing_dudi_nama');
        $this->db->from('tb_siswa');
        $this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->where('tb_siswa.status_pengajuan', 'disetujui');
        $this->db->where('tb_siswa.dudi_id IS NOT NULL OR tb_siswa.other_dudi_nama IS NOT NULL');
        
        $students = $this->db->get()->result();
        
        $processed_companies = array();
        $added_count = 0;
        $updated_count = 0;
        
        foreach($students as $student) {
            $company_name = null;
            $company_exists = false;
            
            // Determine the company name based on what's available
            if($student->dudi_id && $student->existing_dudi_nama) {
                // Company already exists in DUDI table
                $company_name = $student->existing_dudi_nama;
                $company_exists = true;
            } elseif($student->other_dudi_nama) {
                // Student proposed a new company
                $company_name = $student->other_dudi_nama;
                $company_exists = false;
            }
            
            if($company_name && !in_array($company_name, $processed_companies)) {
                $processed_companies[] = $company_name;
                
                // Check if company already exists in DUDI table
                $existing_dudi = $this->db->where('dudi_nama', $company_name)->get('tb_dudi')->row();
                
                if(!$existing_dudi) {
                    // Company doesn't exist, create it
                    $dudi_data = array(
                        'dudi_nama' => $company_name,
                        'dudi_alamat' => $student->other_dudi_alamat ? $student->other_dudi_alamat : 'Alamat belum diisi',
                        'dudi_telepon' => $student->other_dudi_telepon,
                        'dudi_email' => $student->other_dudi_email,
                        'dudi_pic' => $student->other_dudi_pic,
                        'dudi_nip_pic' => $student->other_dudi_nip_pic,
                        'dudi_instruktur' => $student->other_dudi_instruktur,
                        'dudi_nip_instruktur' => $student->other_dudi_nip_instruktur,
                        'status_kerjasama' => 'non_mitra', // Default for newly discovered companies
                        'is_mitra' => 0,
                        'sumber_data' => 'siswa', // Since it came from student proposal
                        'dudi_code' => $this->generateRandomString(30),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->db->insert('tb_dudi', $dudi_data);
                    $added_count++;
                    echo "✓ Added company: " . $company_name . "<br>";
                } else {
                    // Company exists, check if it needs updating
                    $update_needed = false;
                    $update_data = array();
                    
                    // Update company details if they were provided by student and not filled in DUDI
                    if($student->other_dudi_alamat && empty($existing_dudi->dudi_alamat)) {
                        $update_data['dudi_alamat'] = $student->other_dudi_alamat;
                        $update_needed = true;
                    }
                    if($student->other_dudi_telepon && empty($existing_dudi->dudi_telepon)) {
                        $update_data['dudi_telepon'] = $student->other_dudi_telepon;
                        $update_needed = true;
                    }
                    if($student->other_dudi_email && empty($existing_dudi->dudi_email)) {
                        $update_data['dudi_email'] = $student->other_dudi_email;
                        $update_needed = true;
                    }
                    if($student->other_dudi_pic && empty($existing_dudi->dudi_pic)) {
                        $update_data['dudi_pic'] = $student->other_dudi_pic;
                        $update_needed = true;
                    }
                    if($student->other_dudi_nip_pic && empty($existing_dudi->dudi_nip_pic)) {
                        $update_data['dudi_nip_pic'] = $student->other_dudi_nip_pic;
                        $update_needed = true;
                    }
                    if($student->other_dudi_instruktur && empty($existing_dudi->dudi_instruktur)) {
                        $update_data['dudi_instruktur'] = $student->other_dudi_instruktur;
                        $update_needed = true;
                    }
                    if($student->other_dudi_nip_instruktur && empty($existing_dudi->dudi_nip_instruktur)) {
                        $update_data['dudi_nip_instruktur'] = $student->other_dudi_nip_instruktur;
                        $update_needed = true;
                    }
                    
                    if($update_needed) {
                        $update_data['updated_at'] = date('Y-m-d H:i:s');
                        $this->db->where('dudi_id', $existing_dudi->dudi_id);
                        $this->db->update('tb_dudi', $update_data);
                        $updated_count++;
                        echo "✓ Updated company: " . $company_name . "<br>";
                    }
                }
            }
        }
        
        echo "<h3>✓ Synchronization completed!</h3>";
        echo "<p>Added: $added_count companies | Updated: $updated_count companies</p>";
    }
    
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}