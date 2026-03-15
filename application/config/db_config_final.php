<?php
/**
 * Konfigurasi Database Final untuk Aplikasi SiPKL
 * 
 * File ini dibuat untuk memastikan bahwa aplikasi hanya menggunakan
 * satu database yaitu db_sipkl dan tidak ada database lain yang digunakan.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// Konfigurasi database utama
$config['database']['main'] = array(
    'name' => 'db_sipkl',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci'
);

// Daftar tabel yang digunakan dalam database db_sipkl
$config['database']['tables'] = array(
    'tb_group',
    'tb_user',
    'tb_dudi',
    'tb_siswa',
    'tb_pembimbing',
    'tb_pengelompokan',
    'tb_pengumuman'
);

// Konfirmasi database tunggal
$config['database']['single_database'] = true;
$config['database']['used_databases'] = array('db_sipkl');

// Pesan konfirmasi
$config['database']['confirmation_message'] = 'Aplikasi hanya menggunakan satu database yaitu db_sipkl. Tidak ada database lain yang digunakan.';

// Informasi tambahan
$config['database']['info'] = array(
    'total_tables' => 7,
    'primary_database' => 'db_sipkl',
    'other_databases' => array(),
    'status' => 'ACTIVE',
    'verification_date' => date('Y-m-d H:i:s')
);

/* End of file db_config_final.php */
/* Location: ./application/config/db_config_final.php */