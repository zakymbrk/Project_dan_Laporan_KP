<?php 
$userdata = $this->session->userdata('userdata');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Export Data Siswa</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-excel me-2"></i>Export Data Siswa</h2>
    <a href="<?php echo base_url('hubin/view/daftar-siswa') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Info Card -->
<div class="card fade-in">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Petunjuk Export</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Fungsi Export:</h6>
            <ul class="mb-0">
                <li><strong>Export Semua Data:</strong> Download semua data siswa dalam format Excel (.xlsx)</li>
                <li><strong>Export Sebagai Template:</strong> File yang diunduh dapat digunakan sebagai template untuk import kembali</li>
                <li>Data yang diexport mencakup: NIS, NISN, Nama, Kelas, Jurusan, Telepon, Email, Alamat, dan informasi PKL</li>
            </ul>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-download me-2"></i>Export Semua Data</h6>
                    </div>
                    <div class="card-body text-center">
                        <p>Download semua data siswa ke dalam file Excel</p>
                        <a href="<?php echo base_url('export_excel/export_data_siswa_template') ?>" class="btn btn-success btn-lg">
                            <i class="fas fa-file-excel me-2"></i>Export ke XLSX
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>Butuh Bantuan?</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>File export dapat digunakan sebagai template import</li>
                            <li>Edit data sesuai kebutuhan</li>
                            <li>Gunakan fitur Import untuk mengupload kembali</li>
                            <li>Pastikan format data sesuai dengan template</li>
                        </ul>
                        <hr>
                        <a href="<?php echo base_url('hubin/view/import-siswa') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-upload me-2"></i>Ke Halaman Import
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting:</h6>
            <ul>
                <li>Pastikan semua data wajib diisi saat melakukan import kembali</li>
                <li>Username, NIS, NISN, dan Email harus unik</li>
                <li>Password minimal 6 karakter</li>
                <li>Format tanggal: YYYY-MM-DD (contoh: 2024-01-15)</li>
                <li>Jenis Kelamin: L untuk Laki-laki, P untuk Perempuan</li>
            </ul>
        </div>
    </div>
</div>
