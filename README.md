# SiPKL - Sistem Pendataan Siswa Prakerin
## SMK ITIKURIH HIBARNA

Aplikasi web berbasis CodeIgniter 3 untuk manajemen Praktik Kerja Lapangan (PKL) dengan sistem terintegrasi untuk Siswa, Hubin, dan Pembimbing. Aplikasi ini dilengkapi dengan fitur QR Code verifikasi, manajemen data lengkap, dan sistem pagination untuk data besar. Fokus aplikasi adalah pada proses pengajuan PKL, verifikasi oleh Hubin, dan penempatan pembimbing.


### Fitur Manajemen Biodata Lengkap

**Untuk Hubin:**
- ✅ **Detail Biodata Lengkap** - Lihat semua informasi user dengan tab navigasi
- ✅ **Edit Biodata** - Form komprehensif dengan validasi
- ✅ **Update Biodata** - Proses penyimpanan dengan cross-table sync
- ✅ **Hapus Biodata** - Soft delete dengan konfirmasi keamanan


**Untuk Siswa:**
- ✅ **Data Siswa Komplit** - Informasi akademik dan pribadi terintegrasi

### Tampilan Antarmuka
- **Tab Navigasi Intuitif** - Data Dasar, Pribadi, Profesional, Kontak
- **Validasi Otomatis** - Format tahun masuk, golongan, email
- **Indikator Kelengkapan** - Visualisasi status identitas user
- **Responsive Design** - Tampilan yang ramah mobile

---

## 🎯 Fitur Utama

### 1. **Siswa (Level 2)**
- ✅ **Landing Page**: Halaman beranda dengan informasi sekolah dan statistik
- ✅ **Pengajuan PKL**: Buat dan kirim pengajuan PKL dengan memilih perusahaan (DUDI) dan kelas (XI TKJ 1-3, XI Perbankan)
- ✅ **Ajukan Ulang**: Siswa dapat mengajukan ulang pengajuan setelah diproses oleh Hubin (disetujui/ditolak)
- ✅ **Lihat Status Pengajuan**: Pantau status pengajuan (draft, menunggu, disetujui, ditolak)
- ✅ **ID Card PKL**: Cetak ID Card setelah pengajuan disetujui dengan QR Code verifikasi
- ✅ **Profile Management**: Edit profile dan ubah password
- ✅ **Pengumuman**: Lihat pengumuman terbaru dari Hubin
- ✅ **QR Code Verifikasi**: Setiap ID Card dilengkapi dengan QR Code untuk verifikasi keaslian

**Alur Siswa:**
1. Kunjungi halaman beranda (landing page)
2. Klik "Masuk ke Sistem" untuk login
3. Login → Dashboard
4. Buat Pengajuan PKL (pilih kelas, DUDI, isi data)
5. Tunggu persetujuan Hubin
6. Setelah disetujui, cetak ID Card PKL dengan QR Code

### 2. **Hubin (Level 1)**
- ✅ **Login Langsung**: Akses langsung ke halaman login khusus hubin
- ✅ **Manajemen User**: Kelola data Siswa, Pembimbing, dan Hubin dengan identitas lengkap
- ✅ **Manajemen DUDI**: Kelola data perusahaan tempat PKL
- ✅ **Approve Pengajuan**: Setujui atau tolak pengajuan PKL dari siswa
- ✅ **Ajukan Ulang Siswa**: Siswa dapat mengajukan ulang setelah pengajuan diproses (disetujui/ditolak)
- ✅ **Assign Pembimbing**: Tentukan pembimbing untuk setiap siswa yang disetujui
- ✅ **Manajemen Siswa**: Lihat dan kelola semua data siswa (dikelompokkan berdasarkan pembimbing)
- ✅ **ID Card Management**: Buat dan cetak ID Card untuk siswa yang disetujui dengan QR Code verifikasi
- ✅ **Pengumuman**: Buat, edit, dan hapus pengumuman yang tampil di beranda
- ✅ **Dashboard Statistik**: Grafik dan statistik lengkap
- ✅ **Pagination**: Navigasi data besar dengan sistem pagination
- ✅ **Pencarian & Filter**: Cari dan filter data user, siswa, dan pengajuan
- ✅ **Export Excel**: Export data ke format Excel untuk laporan

**Alur Hubin:**
1. Akses langsung `/login/hubin` untuk login
2. Login → Dashboard
3. Kelola DUDI (tambah/edit perusahaan)
4. Kelola User (tambah siswa, pembimbing)
5. Review pengajuan PKL dari siswa
6. Approve/Tolak pengajuan
7. Assign pembimbing ke siswa yang disetujui
8. Buat ID Card untuk siswa yang disetujui
9. Buat pengumuman untuk siswa

---

## 🔄 Alur Kerja Terintegrasi

```
SISWA                    HUBIN                  PEMBIMBING
  │                        │                        │
  ├─ Buat Pengajuan ──────>│                        │
  │                        │                        │
  │                        ├─ Review Pengajuan     │
  │                        ├─ Approve/Tolak        │
  │                        ├─ Assign Pembimbing    │
  │                        ├─ Buat ID Card         │
  │                        │                        │
  │<── Status Update ──────┤                        │
  │<── ID Card ───────────┤                        │
  │                        │                        │
  │                        
```

---

## 🗄️ Struktur Database

### Database File: `db_sipkl.sql`

File `db_sipkl.sql` merupakan database utama yang berisi semua struktur dan data awal aplikasi SiPKL. File ini mencakup:

- Pembuatan database `db_sipkl`
- Pembuatan semua tabel dengan struktur lengkap
- Data awal (sample data) untuk testing
- Foreign key constraints
- Migrasi field identitas lengkap
- Trigger dan prosedur migrasi otomatis

### Tabel Utama:

1. **tb_user**: Data pengguna (Siswa, Hubin, Pembimbing) dengan identitas lengkap
   - Field: id, username, password, nama_lengkap, email, telepon, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, nip_nim, level, active, user_code, foto_profil, created_at, updated_at

2. **tb_group**: Level pengguna (1=Hubin, 2=Pembimbing, 3=Siswa)
   - Field: group_id, group_name

3. **tb_siswa**: Data siswa dan pengajuan PKL
   - Field: siswa_id, user_id, siswa_nama, siswa_nis, siswa_kelas, siswa_jurusan, siswa_telepon, siswa_alamat, dudi_id, other_dudi_nama, pembimbing_id, periode, status_pengajuan, tanggal_mulai, tanggal_selesai, surat_permohonan, surat_balasan, idcard_template_depan, idcard_template_belakang, siswa_code, created_at, updated_at

4. **tb_pembimbing**: Data pembimbing terpisah dengan biodata lengkap
   - Field: pembimbing_id, user_id, pembimbing_nama, pembimbing_nip, pembimbing_telepon, pembimbing_email, pembimbing_alamat, pendidikan_terakhir, jabatan, jurusan_keahlian, tahun_masuk, status_kepegawaian, golongan, tempat_tugas, pembimbing_code, created_at, updated_at

5. **tb_pengelompokan**: Relasi siswa-pembimbing dengan sistem pengelompokan
   - Field: pengelompokan_id, pembimbing_id, siswa_id, created_at

6. **tb_dudi**: Data perusahaan (DUDI)
   - Field: dudi_id, dudi_nama, dudi_alamat, dudi_telepon, dudi_email, dudi_pic, dudi_nip_pic, dudi_instruktur, dudi_nip_instruktur, latitude, longitude, dudi_code, created_at

7. **tb_pengumuman**: Pengumuman dari Hubin
   - Field: pengumuman_id, judul, isi, created_by, created_at, updated_at

### Relasi Database:
- `tb_user.level` → `tb_group.group_id`
- `tb_siswa.user_id` → `tb_user.id`
- `tb_siswa.dudi_id` → `tb_dudi.dudi_id`
- `tb_siswa.pembimbing_id` → `tb_pembimbing.pembimbing_id`
- `tb_pembimbing.user_id` → `tb_user.id`
- `tb_pengelompokan.pembimbing_id` → `tb_pembimbing.pembimbing_id`
- `tb_pengelompokan.siswa_id` → `tb_siswa.siswa_id`
- `tb_pengumuman.created_by` → `tb_user.id`

---

## 🚀 Instalasi & Setup

### Persyaratan Sistem
- PHP 7.4+ (Compatible dengan PHP 8.2)
- MySQL/MariaDB 5.6+
- Apache dengan mod_rewrite enabled
- XAMPP (Recommended)
- Composer (untuk dependency management)

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   cd c:\xampp\htdocs\sipkl
   ```

2. **Install Dependencies**
   
   **Cara Manual:**
   ```bash
   composer install
   ```
   
   Atau jika composer belum terinstall:
   ```bash
   php composer.phar install
   ```
   
   Ini akan menginstall:
   - **PhpSpreadsheet**: Untuk fitur export/import Excel
   - **Dependencies lainnya**: Yang dibutuhkan oleh PhpSpreadsheet
   
   **Catatan:** 
   - Jika composer belum terinstall, download dari https://getcomposer.org/Composer-Setup.exe
   - Pilih PHP dari XAMPP: `C:\xampp\php\php.exe`
   - Setelah install composer, jalankan `composer install` atau `php composer.phar install`
   - Pastikan koneksi internet tersedia saat install dependencies

3. **Akses Sistem**
   - **Untuk Siswa**: `http://localhost/sipkl/` (menampilkan landing page dulu)
   - **Untuk Hubin**: `http://localhost/sipkl/login/hubin` (langsung ke halaman login)
   - **Untuk Testing QR Code**: `http://localhost/sipkl/qr_test` (halaman testing QR Code)

3. **Setup Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Klik tab **Import**
   - Pilih file `db_sipkl.sql` (ada di root folder project)
   - Klik **Go** untuk mengimport database
   - Pastikan database `db_sipkl` sudah terbuat dengan semua tabel
   - File `db_sipkl.sql` mencakup semua migrasi termasuk penambahan field identitas lengkap

4. **Konfigurasi Database**
   File `application/config/database.php` sudah dikonfigurasi untuk menggunakan `db_sipkl`:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => '', // Sesuaikan dengan password MySQL Anda
   'database' => 'db_sipkl',
   ```

5. **Verifikasi Database**
   Setelah import, pastikan database `db_sipkl` memiliki 7 tabel:
   - `tb_group`
   - `tb_user`
   - `tb_dudi`
   - `tb_siswa`
   - `tb_pembimbing` (baru)
   - `tb_pengelompokan` (baru)
   - `tb_pengumuman`

5. **File Migrasi Database**
   - Semua migrasi dilakukan dalam satu file `db_sipkl.sql`
   - File ini mencakup penambahan field identitas lengkap (email, telepon, alamat, dll.)
   - Juga mencakup penambahan field biodata lengkap untuk pembimbing
   - Tidak perlu menjalankan file migrasi tambahan seperti `migration_add_user_identity_fields.sql` karena sudah tergabung dalam `db_sipkl.sql`

6. **Setup Folder Upload**
   Pastikan folder berikut ada dan memiliki permission write:
   - `uploads/profil/` - untuk foto profil
   - `uploads/import/` - untuk file import Excel

8. **Jalankan Aplikasi**
   - Start Apache dan MySQL di XAMPP
   - **Untuk Siswa**: Akses `http://localhost/sipkl/` untuk melihat landing page
   - **Untuk Hubin**: Akses `http://localhost/sipkl/login/hubin` untuk login langsung
   - **Catatan:** Base URL sudah diubah dari `sipklci2` menjadi `sipkl`
   - Pastikan folder aplikasi juga diubah namanya dari `sipklci2` menjadi `sipkl` atau sesuaikan `base_url` di `application/config/config.php`

---

## 🔐 Login Credentials Default

## 📁 Struktur File Penting

```
application/
├── controllers/
│   ├── Auth.php          # Authentication
│   ├── Home.php          # Landing page
│   ├── Hubin.php         # Controller Hubin (Level 1)
│   ├── Pembimbing.php    # Controller Pembimbing (Level 2)
│   ├── Siswa.php         # Controller Siswa (Level 3)
│   └── Export_excel.php  # Export ke Excel
│
├── models/
│   ├── Login.php         # Model login
│   ├── M_user.php        # Model user
│   ├── M_siswa.php       # Model siswa
│   ├── M_dudi.php        # Model DUDI
│   ├── M_pembimbing.php  # Model pembimbing
│   ├── M_pengelompokan.php # Model pengelompokan
│   └── M_pengumuman.php  # Model pengumuman
│
└── views/
    ├── landing/          # Landing page
    ├── authentication/   # Login page
    ├── hubin/            # Views Hubin
    ├── pembimbing/       # Views Pembimbing
    └── siswa/            # Views Siswa
```

---

## 🛠️ Fitur Teknis

### Teknologi yang Digunakan:
- **Backend:** CodeIgniter 3
- **Frontend:** Bootstrap 5, Chart.js, AOS Animation
- **Database:** MySQL/MariaDB
- **Library:** PhpSpreadsheet (untuk export/import Excel)
- **Security:** Password hashing (bcrypt), Session management

### Database Management:
- **Single Database:** Menggunakan satu database `db_sipkl.sql` untuk semua data
- **Migration Safe:** Semua migrasi menggunakan `IF NOT EXISTS` untuk keamanan
- **Backup Friendly:** Struktur database modular dan terdokumentasi dengan baik
- **Identitas Lengkap:** Field identitas lengkap untuk semua user (email, telepon, alamat, dll.)

### Fitur Keamanan:
- ✅ Password hashing dengan bcrypt
- ✅ Session-based authentication
- ✅ Level-based access control
- ✅ Form validation
- ✅ SQL injection protection (CodeIgniter Query Builder)
- ✅ QR Code verification untuk keaslian dokumen

### Fitur Tambahan:
- ✅ **Pagination:** Sistem pagination untuk menangani data besar
- ✅ **Pencarian & Filter:** Fitur pencarian dan filter pada semua data tables
- ✅ **Export Excel:** Export data ke format Excel menggunakan PhpSpreadsheet
- ✅ **Responsive Design:** Tampilan yang optimal di berbagai device
- ✅ **QR Code Generator:** Library khusus untuk generate QR Code verifikasi

---

## 📊 Fitur Dashboard

### Dashboard Hubin:
- Statistik total siswa, pembimbing, DUDI
- Grafik distribusi user
- Grafik status pengajuan PKL
- Notifikasi pengajuan menunggu
- Pengumuman terkini

### Dashboard Pembimbing:
- Statistik siswa yang dibimbing
- Pengumuman terkini

### Dashboard Siswa:
- Status pengajuan PKL
- Informasi DUDI
- Pengumuman terkini

---

## 🎨 Fitur Khusus

### 1. Logo Sekolah
- Logo sekolah ditampilkan di beranda (landing page)
- Logo tersedia di `assets/img/logo-sekolah.png`

### 2. Pengumuman
- Hubin dapat membuat pengumuman yang tampil di beranda
- Pengumuman juga tampil di dashboard siswa
- Fitur CRUD lengkap untuk pengumuman

### 3. Kelompok Siswa
- Data siswa di Hubin dikelompokkan berdasarkan pembimbing
- Data siswa di Pembimbing dikelompokkan berdasarkan perusahaan
- Memudahkan manajemen pengajuan dan penempatan

### 4. ID Card PKL dengan QR Code
- ID Card dibuat oleh Hubin untuk siswa yang disetujui
- ID Card dilengkapi dengan QR Code untuk verifikasi keaslian
- QR Code mengandung informasi identitas siswa
- ID Card dapat dicetak dengan format profesional
- Siswa juga dapat mencetak ID Card mereka sendiri setelah disetujui

### 5. Kelas Terbatas
- Hanya tersedia kelas: XI TKJ1, XI TKJ2, XI TKJ3, dan XI Perbankan
- Sesuai dengan kebutuhan sekolah

### 6. Identitas Lengkap User
- Setiap user memiliki identitas lengkap (email, telepon, alamat, tempat & tanggal lahir, jenis kelamin)
- Interface dengan tab navigasi untuk manajemen data
- Validasi input otomatis

### 7. Pagination & Pencarian
- Sistem pagination untuk menangani data besar
- Fitur pencarian dan filter pada semua data tables
- Navigasi yang user-friendly

### 8. Export/Import Excel
- Export data ke format Excel menggunakan PhpSpreadsheet
- Import data siswa dari file Excel
- Format standar untuk kemudahan penggunaan

---

## 🆕 Fitur Baru (Version 2.5) - QR Code Verification & Enhanced Features

### a. QR Code Verification System
- ✅ **QR Code Generator Library**: Library khusus untuk generate QR Code
- ✅ **Verifikasi Keaslian**: Setiap ID Card dilengkapi QR Code untuk verifikasi
- ✅ **Data Terenkripsi**: QR Code mengandung informasi identitas siswa yang terenkripsi
- ✅ **Mobile Friendly**: QR Code dapat di-scan dengan smartphone

### b. Pagination & Data Management
- ✅ **Sistem Pagination**: Navigasi data besar dengan pagination
- ✅ **Pencarian & Filter**: Fitur pencarian dan filter pada semua data tables
- ✅ **Performance Optimization**: Load time yang lebih cepat dengan pagination
- ✅ **User Experience**: Navigasi yang intuitif dan user-friendly

### c. Export/Import Excel
- ✅ **PhpSpreadsheet Integration**: Library untuk export/import Excel
- ✅ **Export Data**: Export user, siswa, dan pengajuan ke Excel
- ✅ **Import Data**: Import data siswa dari file Excel
- ✅ **Format Standar**: Template import yang sudah disediakan

### d. Identitas Lengkap User (Version 2.3)
- ✅ **Field Identitas Baru**: Email, Telepon, Alamat, Tempat Lahir, Tanggal Lahir, Jenis Kelamin, NIP/NIM
- ✅ **Tab Navigasi**: Interface dengan tab Data Dasar, Data Pribadi, dan Kontak
- ✅ **Validasi Input**: Format email dan nomor telepon divalidasi
- ✅ **Auto-update Timestamp**: Field `updated_at` otomatis terupdate

### e. Database Management
- ✅ **Single Database Approach**: Semua migrasi dan update dilakukan di `db_sipkl.sql` saja
- ✅ **Safe Migration**: Menggunakan `IF NOT EXISTS` untuk mencegah error duplikasi
- ✅ **Data Sample**: User `hubin` dan `pembimbing1` sudah memiliki data identitas lengkap

### f. Interface Improvements
- ✅ **Form Tambah/Edit User**: Dilengkapi dengan tab identitas lengkap
- ✅ **Detail User View**: Tampilan detail dengan tab navigasi
- ✅ **Tabel Data User**: Menampilkan kolom email dan telepon
- ✅ **Responsive Design**: Layout yang ramah mobile

## 🆕 Fitur Baru (Version 1.8)

### a. Perbaikan Export/Import Excel
- ✅ Fixed error `vendor/autoload.php` - path sudah diperbaiki
- ✅ Menambahkan fungsi import data siswa dari Excel
- ✅ Export Excel sudah berfungsi dengan baik
- ✅ Format import: Nama Siswa, Kelas, Telepon, Alamat, Perusahaan, Status, Periode, Tanggal Mulai

### b. Pemisahan Tabel Data
- ✅ Tabel `tb_pembimbing` dibuat terpisah untuk data pembimbing
- ✅ Tabel `tb_pengelompokan` dibuat untuk relasi siswa-pembimbing
- ✅ View `data-pembimbing.php` dibuat untuk menampilkan data pembimbing terpisah
- ✅ Sistem pengelompokan dengan minimum 10 siswa per pembimbing

### c. Auto-create Data
- ✅ Saat menambah user dengan level "Siswa", data siswa otomatis dibuat di tabel `tb_siswa`
- ✅ Saat menambah user dengan level "Pembimbing", data pembimbing otomatis dibuat di tabel `tb_pembimbing`
- ✅ Form tambah user diperbarui dengan field tambahan untuk siswa

### d. Pengelompokan Siswa-Pembimbing
- ✅ Sistem pengelompokan dengan minimum 10 siswa per pembimbing
- ✅ Manual assignment siswa ke pembimbing yang masih memiliki slot tersedia
- ✅ Jika lebih dari 10 siswa, akan ditugaskan ke pembimbing berikutnya secara manual

### e. Keamanan Pengajuan
- ✅ Hanya siswa (level 3) yang dapat membuat pengajuan PKL
- ✅ Controller `Siswa` sudah memiliki validasi level di constructor

## 📝 Changelog

### Version 2.5 (Latest)
- ✅ Implementasi sistem QR Code verification untuk ID Card
- ✅ Penambahan library QR Generator
- ✅ Integrasi pagination untuk semua data tables
- ✅ Fitur pencarian dan filter yang lebih lengkap
- ✅ Export/import Excel dengan PhpSpreadsheet
- ✅ Optimasi performance dengan pagination
- ✅ Tampilan yang lebih responsive dan user-friendly

### Version 2.3
- ✅ Implementasi identitas lengkap user dengan 8 field baru
- ✅ Migrasi database pindah ke `db_sipkl.sql` sebagai single source of truth
- ✅ Interface dengan tab navigasi untuk manajemen identitas
- ✅ Validasi input dan auto-update timestamp
- ✅ Tampilan detail user yang lebih informatif
- ✅ Semua migrasi digabung dalam satu file `db_sipkl.sql` untuk kemudahan deployment

### Version 2.0 (Final)
- ✅ Fokus pada pengajuan PKL, verifikasi, dan penempatan pembimbing
- ✅ Kode dan database dibersihkan dari tabel-tabel yang tidak digunakan

### Version 1.9
- ✅ Fitur logbook dihapus dari sistem
- ✅ Sistem yang ringkas dan fokus pada inti proses PKL  
- ✅ Fitur absensi dihapus dari sistem
- ✅ Kode dan database dibersihkan dari tabel-tabel yang tidak digunakan

### Version 1.8
- ✅ Perbaikan export/import Excel dengan PhpSpreadsheet
- ✅ Pemisahan tabel pembimbing dan sistem pengelompokan
- ✅ Auto-create data siswa dan pembimbing saat tambah user
- ✅ Sistem pengelompokan minimum 10 siswa per pembimbing
- ✅ Keamanan pengajuan hanya untuk siswa

### Version 1.7
- ✅ Logo sekolah di beranda
- ✅ Fitur pengumuman lengkap (CRUD)
- ✅ Tabel siswa dikelompokkan berdasarkan pembimbing
- ✅ ID Card PKL dibuat oleh Hubin
- ✅ ID Card dapat dicetak dengan format profesional
- ✅ Kelas terbatas: XI TKJ 1-3 dan XI Perbankan
- ✅ Alur kerja: Siswa → Hubin (approve & assign) → Pembimbing
- ✅ Integrasi lengkap antara tiga pengguna

### Version 1.6
- ✅ Update informasi sekolah ke SMK ITIKURIH HIBARNA
- ✅ Sistem 3 level: Siswa, Hubin, Pembimbing
- ✅ Fitur pengajuan PKL terintegrasi
- ✅ Dashboard dengan charts
- ✅ Profile management untuk semua level
- ✅ Landing page profesional

---

## 📖 Cara Menggunakan Fitur Baru

### Import Data Siswa dari Excel
1. Login sebagai Hubin
2. Buka menu "Data Siswa"
3. Klik tombol "Import Excel"
4. Upload file Excel dengan format (20 kolom):
   - Kolom 1: Nama Siswa (wajib)
   - Kolom 2: NIS
   - Kolom 3: NISN
   - Kolom 4: Jenis Kelamin (L/P)
   - Kolom 5: Tempat Lahir
   - Kolom 6: Tanggal Lahir (YYYY-MM-DD)
   - Kolom 7: Kelas (wajib)
   - Kolom 8: Jurusan
   - Kolom 9: Sekolah
   - Kolom 10: Telepon
   - Kolom 11: Username (wajib)
   - Kolom 12: Password (wajib)
   - Kolom 13: Email
   - Kolom 14: Alamat
   - Kolom 15: Perusahaan DUDI
   - Kolom 16: Tanggal Mulai (YYYY-MM-DD)
   - Kolom 17: Tanggal Selesai (YYYY-MM-DD)
   - Kolom 18: Lama Pelaksanaan (hari)
   - Kolom 19: Status Pengajuan (draft/menunggu/disetujui/ditolak)
   - Kolom 20: Periode PKL
5. Klik "Import Data"
6. Sistem akan otomatis membuat user jika belum ada (password default: `siswa123`)

### Export Data Siswa ke Excel
1. Login sebagai Hubin
2. Buka menu "Data Siswa"
3. Klik tombol "Export" lalu pilih:
   - "Export to XLSX" untuk template 20 kolom dalam format Excel
   - "Export to CSV" untuk template 20 kolom dalam format CSV
4. File template akan otomatis terdownload dengan format 20 kolom untuk diisi dan diimpor kembali

### Assign Pembimbing ke Siswa
1. Buka menu "Data Siswa"
2. Centang siswa yang akan di-assign (minimal 1 siswa)
3. Klik tombol "Assign Pembimbing"
4. Pilih pembimbing dari dropdown
5. Klik "Assign Pembimbing"
6. Sistem akan otomatis membatasi maksimal 10 siswa per pembimbing

### Melihat Data Pembimbing Terpisah
1. Login sebagai Hubin
2. Akses: `hubin/view/data-pembimbing`
3. Atau tambahkan menu "Data Pembimbing" di sidebar
4. Lihat jumlah siswa yang di-assign ke setiap pembimbing

### Menggunakan QR Code Verification
1. Setelah siswa disetujui pengajuan PKL
2. Hubin membuat ID Card untuk siswa
3. ID Card akan otomatis dilengkapi dengan QR Code
4. QR Code dapat di-scan untuk verifikasi keaslian ID Card
5. Informasi yang terverifikasi: nama siswa, kelas, dan status PKL

### Menggunakan Pagination & Pencarian
1. Di halaman data user/siswa/pengajuan
2. Gunakan kolom pencarian untuk mencari data spesifik
3. Gunakan pagination untuk navigasi data besar
4. Filter data berdasarkan kriteria yang tersedia
5. Tampilkan data per halaman (10, 25, 50, atau 100 data)



## ⚠️ Catatan Penting

- Pastikan folder `uploads/import/` memiliki permission write (777)
- Pastikan Composer sudah terinstall sebelum menjalankan `composer install`
- Database harus di-import dengan file `db_sipkl.sql` (sudah termasuk semua tabel baru)
- Password default untuk user siswa yang dibuat via import adalah: `siswa123`
- Sistem pengelompokan membatasi maksimal 10 siswa per pembimbing
- Saat menambah user siswa, data siswa otomatis dibuat di tabel `tb_siswa`
- Saat menambah user pembimbing, data pembimbing otomatis dibuat di tabel `tb_pembimbing`
- QR Code akan otomatis dibuat saat ID Card di-generate
- Pastikan koneksi internet tersedia untuk generate QR Code
- Library PhpSpreadsheet diperlukan untuk fitur export/import Excel

## 🔧 Troubleshooting

### Error: vendor/autoload.php not found
**Solusi:**
1. Jalankan manual: `composer install` atau `php composer.phar install` di Command Prompt
2. Pastikan folder `vendor` ada di root project setelah install
3. Pastikan Composer sudah terinstall dengan benar
4. Cek apakah file `composer.json` ada di root project

### Error: Table doesn't exist
**Solusi:**
1. Pastikan sudah mengimport file `db_sipkl.sql` yang lengkap
2. Cek apakah tabel `tb_pembimbing` dan `tb_pengelompokan` sudah dibuat
3. Pastikan tidak ada error saat import database
4. Verifikasi di phpMyAdmin bahwa semua tabel sudah ada

### Import tidak berfungsi
**Solusi:**
1. Pastikan folder `uploads/import/` ada dan memiliki permission write
2. Pastikan file Excel menggunakan format yang benar (lihat bagian Import Excel)
3. Cek ukuran file (maksimal 5MB)
4. Pastikan PhpSpreadsheet sudah terinstall via composer
5. Cek error log di `application/logs/` untuk detail error

### Export tidak berfungsi
**Solusi:**
1. Pastikan PhpSpreadsheet sudah terinstall via composer
2. Jalankan `CHECK_SYSTEM.ps1` untuk verifikasi instalasi
3. Cek error log di `application/logs/`
4. Pastikan folder `assets/` memiliki permission write

### QR Code tidak muncul
**Solusi:**
1. Pastikan koneksi internet tersedia
2. Cek library `Qr_generator.php` sudah ada di `application/libraries/`
3. Verifikasi bahwa data siswa lengkap (nama, kelas, id)
4. Cek error log di `application/logs/` untuk detail error

### Pagination tidak berfungsi
**Solusi:**
1. Pastikan library Pagination CodeIgniter sudah terload
2. Cek konfigurasi pagination di controller
3. Verifikasi bahwa data total_rows dihitung dengan benar
4. Pastikan URI segment sesuai dengan konfigurasi

### Auto-create data tidak berfungsi
**Solusi:**
1. Pastikan tabel `tb_pembimbing` dan `tb_pengelompokan` sudah ada
2. Cek error log di `application/logs/`
3. Pastikan foreign key constraints sudah benar
4. Verifikasi model `M_pembimbing.php` dan `M_pengelompokan.php` ada

### Cara Cek Sistem Lengkap
Untuk mengecek apakah semua komponen sudah terinstall:
1. Pastikan folder `vendor` ada dan berisi `autoload.php`
2. Pastikan folder `vendor/phpoffice/phpspreadsheet` ada
3. Pastikan database sudah di-import dengan `db_sipkl.sql`
4. Cek di phpMyAdmin bahwa semua tabel sudah ada
5. Verifikasi library `Qr_generator.php` ada di `application/libraries/`

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi:
- **Email:** smk@itikurih-hibarna.sch.id
- **Telpon:** 022-5957900

---

## 📄 License

© 2026 SMK ITIKURIH HIBARNA - All Rights Reserved

---

## 🎓 Credits

Dikembangkan untuk SMK ITIKURIH HIBARNA  
Sistem Pendataan Siswa Prakerin (SiPKL) v2.5 (Latest)
