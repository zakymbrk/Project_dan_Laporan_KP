<?php
defined('BASEPATH')OR exit('No direct script access allowed');

class M_siswa extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private $table = 'tb_siswa'; 
    private $status= true; 

    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function get_semua_siswa()
    {
        $siswa = $this->db->get($this->table);
        return $siswa;
    }

    function tambah_data_siswa($data)
    {
        // Ensure required fields are set with defaults if not provided
        if(!isset($data['siswa_nis'])) {
            $data['siswa_nis'] = NULL;
        }
        
        // Check NIS uniqueness before insert
        if(isset($data['siswa_nis']) && !empty($data['siswa_nis'])) {
            $this->db->where('siswa_nis', $data['siswa_nis']);
            $existing_nis = $this->db->get('tb_siswa')->row();
            
            if($existing_nis) {
                log_message('error', 'Duplicate NIS found during insert: ' . $data['siswa_nis']);
                $this->status = false;
                return $this->status;
            }
        }
        
        // Check NISN uniqueness before insert
        if(isset($data['siswa_nisn']) && !empty($data['siswa_nisn'])) {
            $this->db->where('siswa_nisn', $data['siswa_nisn']);
            $existing_nisn = $this->db->get('tb_siswa')->row();
            
            if($existing_nisn) {
                log_message('error', 'Duplicate NISN found during insert: ' . $data['siswa_nisn']);
                $this->status = false;
                return $this->status;
            }
        }
        
        /*===========================
        =    INSERT KE DATABASE     =
        ===========================*/
        $insert = $this->db->insert($this->table, $data);
        if(!$insert){
            $this->status = false;
        }
        return $this->status;  
    }

    //search siswa
    function get_data_siswa($siswa_code)
    {
        $result = array();

        $this->db->where('siswa_code', $siswa_code);
        $ceksiswa = $this->db->get($this->table);
        if($ceksiswa->num_rows() != 0){
            $result = $ceksiswa->row();
        }

        return $result;
    }

    function update_siswa($data, $siswa_code)
    {
        // Filter out fields that don't exist in the database
        $allowed_fields = $this->get_allowed_siswa_fields();
        $filtered_data = array();
        
        foreach($data as $field => $value) {
            if(in_array($field, $allowed_fields)) {
                $filtered_data[$field] = $value;
            }
        }
        
        if(empty($filtered_data)) {
            return false; // No valid fields to update
        }
        
        $this->db->where('siswa_code', $siswa_code);
        $result = $this->db->update('tb_siswa', $filtered_data);
        
        return $result;
    }

    function hapus_siswa($siswa_code)
    {
        // Get the student record first to get the user_id
        $this->db->where('siswa_code', $siswa_code);
        $student = $this->db->get($this->table)->row();
        
        if($student) {
            // If student record has a user_id, delete the user first (which will cascade delete the student record)
            if($student->user_id) {
                // Delete the user (this will cascade delete the student record and related pengelompokan records)
                $this->db->where('id', $student->user_id);
                $cek_user = $this->db->delete('tb_user');
                
                return $cek_user;
            } else {
                // If there's no user_id, just delete the student record
                // This will also delete related pengelompokan records due to CASCADE
                $this->db->where('siswa_code', $siswa_code);
                $cek = $this->db->delete($this->table);
                
                return $cek;
            }
        } else {
            // Student not found
            return false;
        }
    }
    
    // New function to get siswa with full biodata
    function get_siswa_with_biodata($siswa_code)
    {
        $this->db->select('tb_siswa.*, tb_user.nama_lengkap as user_nama, tb_user.username, tb_user.foto_profil, tb_user.alamat, tb_user.telepon, tb_user.email, tb_dudi.dudi_nama, tb_dudi.is_mitra as dudi_is_mitra');
        $this->db->from('tb_siswa');
        $this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->where('tb_siswa.siswa_code', $siswa_code);
        return $this->db->get()->row();
    }
    
    // New function to get siswa with biodata by user_id
    function get_siswa_with_biodata_by_user_id($user_id)
    {
        $this->db->select('tb_siswa.*, tb_user.user_code, tb_user.nama_lengkap as user_nama, tb_user.username, tb_user.foto_profil, tb_user.alamat as user_alamat, tb_user.telepon as user_telepon, tb_user.email as user_email, tb_user.tempat_lahir as user_tempat_lahir, tb_user.tanggal_lahir as user_tanggal_lahir, tb_user.jenis_kelamin as user_jenis_kelamin, tb_user.nip_nim as user_nip_nim, tb_dudi.dudi_nama, tb_dudi.is_mitra as dudi_is_mitra, tb_pembimbing.pembimbing_nama, tb_pembimbing.pembimbing_email, tb_pembimbing.pembimbing_telepon');
        $this->db->from('tb_user');
        $this->db->join('tb_siswa', 'tb_siswa.user_id = tb_user.id', 'left');
        $this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
        $this->db->join('tb_pembimbing', 'tb_pembimbing.pembimbing_id = tb_siswa.pembimbing_id', 'left');
        $this->db->where('tb_user.id', $user_id);
        return $this->db->get()->row();
    }
    
    // New function to update siswa by user_id
    function update_siswa_by_user_id($data, $user_id)
    {
        // Filter out fields that don't exist in the database
        $allowed_fields = $this->get_allowed_siswa_fields();
        $filtered_data = array();
        
        foreach($data as $field => $value) {
            if(in_array($field, $allowed_fields)) {
                $filtered_data[$field] = $value;
            }
        }
        
        if(empty($filtered_data)) {
            return false; // No valid fields to update
        }
        
        // Check NIS uniqueness before update (exclude current record)
        if(isset($filtered_data['siswa_nis']) && !empty($filtered_data['siswa_nis'])) {
            $this->db->where('siswa_nis', $filtered_data['siswa_nis']);
            $this->db->where('user_id !=', $user_id);
            $existing_nis = $this->db->get('tb_siswa')->row();
            
            if($existing_nis) {
                log_message('error', 'Duplicate NIS found: ' . $filtered_data['siswa_nis'] . ' already exists for user_id: ' . $existing_nis->user_id);
                return false; // Prevent duplicate NIS
            }
        }
        
        // Check NISN uniqueness before update (exclude current record)
        if(isset($filtered_data['siswa_nisn']) && !empty($filtered_data['siswa_nisn'])) {
            $this->db->where('siswa_nisn', $filtered_data['siswa_nisn']);
            $this->db->where('user_id !=', $user_id);
            $existing_nisn = $this->db->get('tb_siswa')->row();
            
            if($existing_nisn) {
                log_message('error', 'Duplicate NISN found: ' . $filtered_data['siswa_nisn'] . ' already exists for user_id: ' . $existing_nisn->user_id);
                return false; // Prevent duplicate NISN
            }
        }
        
        $this->db->where('user_id', $user_id);
        $result = $this->db->update('tb_siswa', $filtered_data);
        
        // Log the query for debugging
        log_message('debug', 'Siswa update query: ' . $this->db->last_query());
        log_message('debug', 'Siswa update affected rows: ' . $this->db->affected_rows());
        
        return $result;
    }
    
    // Helper function to get allowed fields in tb_siswa table
    private function get_allowed_siswa_fields()
    {
        $fields = $this->db->field_data('tb_siswa');
        $allowed_fields = array();
        foreach($fields as $field) {
            $allowed_fields[] = $field->name;
        }
        return $allowed_fields;
    }
    
    // Function to get pengajuan count for today
    function get_pengajuan_hari_ini_count()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('tb_siswa');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $query = $this->db->get();
        return $query->row()->count ? $query->row()->count : 0;
    }
    
    // Function to get students without pembimbing assigned
    function get_siswa_belum_assign_count()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('tb_siswa');
        $this->db->where('pembimbing_id IS NULL');
        $this->db->where('status_pengajuan', 'disetujui'); // Only approved students without pembimbing
        $query = $this->db->get();
        return $query->row()->count ? $query->row()->count : 0;
    }
}