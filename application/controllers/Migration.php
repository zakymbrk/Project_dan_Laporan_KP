<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function add_is_mitra_to_dudi()
    {
        // Check if column already exists
        $fields = $this->db->field_data('tb_dudi');
        $column_exists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'is_mitra') {
                $column_exists = true;
                break;
            }
        }
        
        if (!$column_exists) {
            // Add the is_mitra column
            $this->db->query("ALTER TABLE tb_dudi ADD COLUMN is_mitra TINYINT(1) DEFAULT 1 COMMENT '1 for mitra, 0 for non-mitra'");
            
            // Update existing records to be mitra by default
            $this->db->query("UPDATE tb_dudi SET is_mitra = 1");
            
            echo "Column 'is_mitra' added to 'tb_dudi' table successfully!<br>";
            echo "All existing DUDI records are set as mitra by default.";
        } else {
            echo "Column 'is_mitra' already exists in 'tb_dudi' table.";
        }
    }
    
    public function add_biodata_fields_to_pembimbing()
    {
        // Check if columns already exist
        $fields = $this->db->field_data('tb_pembimbing');
        $nip_exists = false;
        $telepon_exists = false;
        $email_exists = false;
        $alamat_exists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'pembimbing_nip') {
                $nip_exists = true;
            }
            if ($field->name === 'pembimbing_telepon') {
                $telepon_exists = true;
            }
            if ($field->name === 'pembimbing_email') {
                $email_exists = true;
            }
            if ($field->name === 'pembimbing_alamat') {
                $alamat_exists = true;
            }
        }
        
        $added_fields = [];
        
        if (!$nip_exists) {
            $this->db->query("ALTER TABLE tb_pembimbing ADD COLUMN pembimbing_nip VARCHAR(50) DEFAULT NULL");
            $added_fields[] = "pembimbing_nip";
        }
        
        if (!$telepon_exists) {
            $this->db->query("ALTER TABLE tb_pembimbing ADD COLUMN pembimbing_telepon VARCHAR(50) DEFAULT NULL");
            $added_fields[] = "pembimbing_telepon";
        }
        
        if (!$email_exists) {
            $this->db->query("ALTER TABLE tb_pembimbing ADD COLUMN pembimbing_email VARCHAR(100) DEFAULT NULL");
            $added_fields[] = "pembimbing_email";
        }
        
        if (!$alamat_exists) {
            $this->db->query("ALTER TABLE tb_pembimbing ADD COLUMN pembimbing_alamat TEXT DEFAULT NULL");
            $added_fields[] = "pembimbing_alamat";
        }
        
        if (!empty($added_fields)) {
            echo "Columns added to 'tb_pembimbing' table: " . implode(", ", $added_fields) . "<br>";
            echo "Migration completed successfully!";
        } else {
            echo "All required columns already exist in 'tb_pembimbing' table.";
        }
    }
    
    public function add_biodata_fields_to_siswa()
    {
        // Check if columns already exist
        $fields = $this->db->field_data('tb_siswa');
        $nis_exists = false;
        $nama_ayah_exists = false;
        $nama_ibu_exists = false;
        $pekerjaan_ayah_exists = false;
        $pekerjaan_ibu_exists = false;
        $telepon_orang_tua_exists = false;
        $alamat_orang_tua_exists = false;
        $agama_exists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'siswa_nis') {
                $nis_exists = true;
            }
            if ($field->name === 'nama_ayah') {
                $nama_ayah_exists = true;
            }
            if ($field->name === 'nama_ibu') {
                $nama_ibu_exists = true;
            }
            if ($field->name === 'pekerjaan_ayah') {
                $pekerjaan_ayah_exists = true;
            }
            if ($field->name === 'pekerjaan_ibu') {
                $pekerjaan_ibu_exists = true;
            }
            if ($field->name === 'telepon_orang_tua') {
                $telepon_orang_tua_exists = true;
            }
            if ($field->name === 'alamat_orang_tua') {
                $alamat_orang_tua_exists = true;
            }
            if ($field->name === 'siswa_agama') {
                $agama_exists = true;
            }
        }
        
        $added_fields = [];
        
        if (!$nis_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN siswa_nis VARCHAR(50) DEFAULT NULL");
            $added_fields[] = "siswa_nis";
        }
        
        if (!$nama_ayah_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN nama_ayah VARCHAR(255) DEFAULT NULL");
            $added_fields[] = "nama_ayah";
        }
        
        if (!$nama_ibu_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN nama_ibu VARCHAR(255) DEFAULT NULL");
            $added_fields[] = "nama_ibu";
        }
        
        if (!$pekerjaan_ayah_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN pekerjaan_ayah VARCHAR(255) DEFAULT NULL");
            $added_fields[] = "pekerjaan_ayah";
        }
        
        if (!$pekerjaan_ibu_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN pekerjaan_ibu VARCHAR(255) DEFAULT NULL");
            $added_fields[] = "pekerjaan_ibu";
        }
        
        if (!$telepon_orang_tua_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN telepon_orang_tua VARCHAR(20) DEFAULT NULL");
            $added_fields[] = "telepon_orang_tua";
        }
        
        if (!$alamat_orang_tua_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN alamat_orang_tua TEXT DEFAULT NULL");
            $added_fields[] = "alamat_orang_tua";
        }
        
        if (!$agama_exists) {
            $this->db->query("ALTER TABLE tb_siswa ADD COLUMN siswa_agama VARCHAR(50) DEFAULT NULL");
            $added_fields[] = "siswa_agama";
        }
        
        if (!empty($added_fields)) {
            echo "Columns added to 'tb_siswa' table: " . implode(", ", $added_fields) . "<br>";
            echo "Migration completed successfully!";
        } else {
            echo "All required columns already exist in 'tb_siswa' table.";
        }
    }
    
    public function run_all()
    {
        echo "<h3>Running All Migrations</h3>";
        
        echo "<h4>1. Adding is_mitra column to tb_dudi...</h4>";
        $this->add_is_mitra_to_dudi();
        
        echo "<br><h4>2. Adding biodata fields to tb_pembimbing...</h4>";
        $this->add_biodata_fields_to_pembimbing();
        
        echo "<br><h4>3. Adding biodata fields to tb_siswa...</h4>";
        $this->add_biodata_fields_to_siswa();
        
        echo "<br><h4>All migrations completed!</h4>";
    }
    
    public function add_parent_fields_to_siswa()
    {
        // This is the new migration for parent/orang tua fields
        $this->add_biodata_fields_to_siswa();
    }
}