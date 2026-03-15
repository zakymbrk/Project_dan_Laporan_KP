-- Database: db_sipkl
-- Updated database structure for Sistem Informasi Praktek Kerja Lapangan (SIPKL)
-- Changes: Pembimbing is no longer a user level but just data assigned to approved student applications
-- Updated with all required columns for application compatibility
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `db_sipkl`
--

CREATE DATABASE IF NOT EXISTS `db_sipkl` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_sipkl`;

-- --------------------------------------------------------

--
-- Table structure for table `tb_group`
--

CREATE TABLE `tb_group` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_group`
--

INSERT INTO `tb_group` (`group_id`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'Hubin', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(2, 'Siswa', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `user_code` varchar(30) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `nip_nim` varchar(50) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL COMMENT '1=Hubin, 2=Siswa',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `unique_nip_nim` (`nip_nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `user_code`, `username`, `password`, `nama_lengkap`, `email`, `telepon`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `nip_nim`, `foto_profil`, `level`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin12345678901234567890123456', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@example.com', '081234567890', 'Jl. Contoh No. 123', 'Bandung', '1990-01-01', 'Laki-laki', 'NIP001', NULL, 1, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(2, 'hubin12345678901234567890123456', 'hubin', '$2y$10$eibUfa8QpwhE6K2kTN.XM.DfXNS.x.ii1R16JwzK8eRxU1pNeckX6', 'Hubin Staff', 'hubin@example.com', '081234567891', 'Jl. Sekolah No. 1', 'Bandung', '1985-05-15', 'Laki-laki', 'NIP002', NULL, 1, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(3, 'siswa1234567890123456789012345', 'siswa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siswa Test', 'siswa@example.com', '081234567892', 'Jl. Contoh No. 124', 'Bandung', '2005-05-15', 'Laki-laki', '1234567890', NULL, 2, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembimbing`
--

CREATE TABLE `tb_pembimbing` (
  `pembimbing_id` int(11) NOT NULL,
  `pembimbing_code` varchar(30) NOT NULL,
  `pembimbing_nama` varchar(255) NOT NULL,
  `pembimbing_nip` varchar(50) DEFAULT NULL,
  `pembimbing_telepon` varchar(20) DEFAULT NULL,
  `pembimbing_email` varchar(255) DEFAULT NULL,
  `pembimbing_alamat` text DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `pendidikan_terakhir` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `jurusan_keahlian` varchar(100) DEFAULT NULL,
  `tahun_masuk` year DEFAULT NULL,
  `status_kepegawaian` varchar(50) DEFAULT NULL,
  `tempat_tugas` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `unique_pembimbing_nip` (`pembimbing_nip`),
  UNIQUE KEY `unique_pembimbing_email` (`pembimbing_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pembimbing`
--

INSERT INTO `tb_pembimbing` (`pembimbing_id`, `pembimbing_code`, `pembimbing_nama`, `pembimbing_nip`, `pembimbing_telepon`, `pembimbing_email`, `pembimbing_alamat`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `pendidikan_terakhir`, `jabatan`, `jurusan_keahlian`, `tahun_masuk`, `status_kepegawaian`, `tempat_tugas`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'pembimbing12345678901234567890', 'Pembimbing Test', 'NIP002', '081234567892', 'pembimbing@example.com', 'Jl. Contoh No. 125', 'Bandung', '2000-12-12', 'Laki-laki', 'S2', 'Guru', 'Teknologi Informasi', 2010, 'PNS', 'Sekolah ABC', 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_dudi`
--

CREATE TABLE `tb_dudi` (
  `dudi_id` int(11) NOT NULL,
  `dudi_code` varchar(30) NOT NULL,
  `dudi_nama` varchar(255) NOT NULL,
  `dudi_alamat` text DEFAULT NULL,
  `dudi_telepon` varchar(20) DEFAULT NULL,
  `dudi_email` varchar(255) DEFAULT NULL,
  `dudi_pic` varchar(255) DEFAULT NULL,
  `dudi_nip_pic` varchar(50) DEFAULT NULL,
  `dudi_instruktur` varchar(255) DEFAULT NULL,
  `dudi_nip_instruktur` varchar(50) DEFAULT NULL,
  `status_kerjasama` enum('mitra','non_mitra','pengajuan') DEFAULT 'pengajuan',
  `is_mitra` tinyint(1) DEFAULT 0,
  `sumber_data` enum('sekolah','siswa') DEFAULT 'sekolah',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `unique_dudi_nama` (`dudi_nama`),
  UNIQUE KEY `unique_dudi_email` (`dudi_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_dudi`
--

INSERT INTO `tb_dudi` (`dudi_id`, `dudi_code`, `dudi_nama`, `dudi_alamat`, `dudi_telepon`, `dudi_email`, `dudi_pic`, `dudi_nip_pic`, `dudi_instruktur`, `dudi_nip_instruktur`, `status_kerjasama`, `is_mitra`, `sumber_data`, `created_at`, `updated_at`) VALUES
(1, 'dudi12345678901234567890123456', 'PT. Contoh Perusahaan', 'Jl. Industri No. 123, Bandung', '02212345678', 'info@contohperusahaan.com', NULL, NULL, NULL, NULL, 'mitra', 1, 'sekolah', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `siswa_id` int(11) NOT NULL,
  `siswa_code` varchar(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `dudi_id` int(11) DEFAULT NULL,
  `pembimbing_id` int(11) DEFAULT NULL,
  `siswa_nama` varchar(255) NOT NULL,
  `siswa_kelas` varchar(20) DEFAULT NULL,
  `siswa_jurusan` varchar(100) DEFAULT NULL,
  `siswa_nis` varchar(50) DEFAULT NULL,
  `siswa_nisn` varchar(50) DEFAULT NULL,
  `status_pengajuan` enum('draft','menunggu','disetujui','ditolak','selesai') DEFAULT 'draft',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `lama_pelaksanaan` int(11) DEFAULT NULL COMMENT 'durasi dalam hari',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `surat_permohonan` varchar(255) DEFAULT NULL,
  `surat_balasan` varchar(255) DEFAULT NULL,
  `periode` varchar(20) DEFAULT NULL,
  `other_dudi_nama` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `unique_siswa_nis` (`siswa_nis`),
  UNIQUE KEY `unique_siswa_nisn` (`siswa_nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`siswa_id`, `siswa_code`, `user_id`, `dudi_id`, `pembimbing_id`, `siswa_nama`, `siswa_kelas`, `siswa_jurusan`, `siswa_nis`, `siswa_nisn`, `status_pengajuan`, `tanggal_mulai`, `tanggal_selesai`, `lama_pelaksanaan`, `surat_permohonan`, `surat_balasan`, `periode`, `other_dudi_nama`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'siswa12345678901234567890123456', 3, 1, 1, 'Siswa Test', 'XII RPL 1', 'Rekayasa Perangkat Lunak', '1234567890', '0987654321', 'disetujui', '2024-06-01', '2024-07-30', 60, NULL, NULL, '2026/2027', NULL, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengelompokan`
--

CREATE TABLE `tb_pengelompokan` (
  `id` int(11) NOT NULL,
  `pembimbing_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pengelompokan`
--

INSERT INTO `tb_pengelompokan` (`id`, `pembimbing_id`, `siswa_id`, `created_at`, `updated_at`) VALUES
(2, 1, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengumuman`
--

CREATE TABLE `tb_pengumuman` (
  `pengumuman_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pengumuman`
--

INSERT INTO `tb_pengumuman` (`pengumuman_id`, `judul`, `isi`, `gambar`, `created_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pengumuman PKL', 'Mulai tanggal 1 Juni 2024, pelaksanaan PKL akan dimulai sesuai jadwal yang telah ditentukan.', NULL, 1, 1, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_group`
--
ALTER TABLE `tb_group`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_code` (`user_code`);

--
-- Indexes for table `tb_pembimbing`
--
ALTER TABLE `tb_pembimbing`
  ADD PRIMARY KEY (`pembimbing_id`),
  ADD UNIQUE KEY `pembimbing_code` (`pembimbing_code`);

--
-- Indexes for table `tb_dudi`
--
ALTER TABLE `tb_dudi`
  ADD PRIMARY KEY (`dudi_id`),
  ADD UNIQUE KEY `dudi_code` (`dudi_code`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `siswa_code` (`siswa_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dudi_id` (`dudi_id`),
  ADD KEY `pembimbing_id` (`pembimbing_id`);

--
-- Indexes for table `tb_pengelompokan`
--
ALTER TABLE `tb_pengelompokan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembimbing_id` (`pembimbing_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `tb_pengumuman`
--
ALTER TABLE `tb_pengumuman`
  ADD PRIMARY KEY (`pengumuman_id`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_group`
--
ALTER TABLE `tb_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_pembimbing`
--
ALTER TABLE `tb_pembimbing`
  MODIFY `pembimbing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_dudi`
--
ALTER TABLE `tb_dudi`
  MODIFY `dudi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pengelompokan`
--
ALTER TABLE `tb_pengelompokan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_pengumuman`
--
ALTER TABLE `tb_pengumuman`
  MODIFY `pengumuman_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_siswa_ibfk_2` FOREIGN KEY (`dudi_id`) REFERENCES `tb_dudi` (`dudi_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tb_siswa_ibfk_3` FOREIGN KEY (`pembimbing_id`) REFERENCES `tb_pembimbing` (`pembimbing_id`) ON DELETE SET NULL;

--
-- Constraints for table `tb_pengelompokan`
--
ALTER TABLE `tb_pengelompokan`
  ADD CONSTRAINT `tb_pengelompokan_ibfk_1` FOREIGN KEY (`pembimbing_id`) REFERENCES `tb_pembimbing` (`pembimbing_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_pengelompokan_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `tb_siswa` (`siswa_id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`level`) REFERENCES `tb_group` (`group_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `tb_pengumuman`
--
ALTER TABLE `tb_pengumuman`
  ADD CONSTRAINT `tb_pengumuman_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL;

COMMIT;