<?php 
$userdata = $this->session->userdata('userdata');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Import Data Siswa</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-upload me-2"></i>Import Data Siswa</h2>
    <a href="<?php echo base_url('hubin/view/daftar-siswa') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if(!empty($this->session->flashdata('error_message'))){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $this->session->flashdata('error_message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Import Form Card -->
<div class="card fade-in">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Upload File Excel/CSV</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('export_excel/import_daftar_siswa') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="file_excel" class="form-label fw-bold">Pilih File Excel/XLSX/CSV</label>
                <input type="file" class="form-control form-control-lg" id="file_excel" name="file_excel" accept=".xlsx,.xls,.csv" required>
                <div class="form-text">Format file yang didukung: .xlsx, .xls, atau .csv (Max 5MB)</div>
            </div>
            
            <div class="alert alert-warning">
                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian:</h6>
                <ul class="mb-0">
                    <li>Pastikan format file sesuai dengan template yang disediakan</li>
                    <li>Data wajib diisi: Nama Siswa*, Kelas*, Username*, Password*</li>
                    <li>Username, NIS, NISN, dan Email harus unik (tidak boleh sama dengan data yang sudah ada)</li>
                    <li>Password minimal 6 karakter</li>
                    <li>Format tanggal: YYYY-MM-DD (contoh: 2024-01-15)</li>
                    <li>Jenis Kelamin: L untuk Laki-laki, P untuk Perempuan</li>
                </ul>
            </div>
            
            <div class="d-grid gap-2 d-md-block">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-upload me-2"></i>Import Data
                </button>
                <button type="reset" class="btn btn-secondary btn-lg">
                    <i class="fas fa-redo me-2"></i>Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template Download Card -->
<div class="card fade-in mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-download me-2"></i>Download Template</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6><i class="fas fa-file-excel me-2"></i>Template Import Siswa</h6>
                <p class="text-muted">Download template Excel yang dapat Anda edit untuk import data siswa. Template ini berisi struktur kolom yang benar dengan contoh data.</p>
                <ul class="mb-3">
                    <li><strong>Nama Siswa *</strong> - Wajib diisi</li>
                    <li>NIS - Jika diisi harus unik</li>
                    <li>NISN - Jika diisi harus unik</li>
                    <li><strong>Jenis Kelamin *</strong> - L atau P</li>
                    <li>Tempat Lahir</li>
                    <li>Tanggal Lahir (YYYY-MM-DD)</li>
                    <li><strong>Kelas *</strong> - Wajib diisi</li>
                    <li>Jurusan</li>
                    <li>Sekolah</li>
                    <li>Telepon Siswa</li>
                    <li><strong>Username *</strong> - Harus unik</li>
                    <li><strong>Password *</strong> - Minimal 6 karakter</li>
                    <li>Email - Jika diisi harus unik</li>
                    <li>Alamat Lengkap</li>
                    <li>Perusahaan (DUDI)</li>
                    <li>Tanggal Mulai PKL (YYYY-MM-DD)</li>
                    <li>Tanggal Selesai PKL (YYYY-MM-DD)</li>
                    <li>Lama Pelaksanaan (hari)</li>
                    <li>Status Pengajuan (draft/menunggu/disetujui/ditolak/selesai)</li>
                    <li>Periode PKL</li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <div class="card border-success">
                    <div class="card-body">
                        <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                        <h6>Template XLSX</h6>
                        <a href="<?php echo base_url('export_excel/export_data_siswa_template') ?>" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Format Details Card -->
<div class="card fade-in mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Detail Format Kolom</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Kolom</th>
                        <th width="15%">Wajib Diisi</th>
                        <th width="30%">Keterangan</th>
                        <th width="30%">Contoh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Nama Siswa</strong></td>
                        <td><span class="badge bg-danger">Ya</span></td>
                        <td>Nama lengkap siswa</td>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>NIS</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Nomor Induk Siswa (harus unik jika diisi)</td>
                        <td>12345</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>NISN</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Nomor Induk Siswa Nasional (harus unik jika diisi)</td>
                        <td>0012345678</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td><span class="badge bg-danger">Ya</span></td>
                        <td>L untuk Laki-laki, P untuk Perempuan</td>
                        <td>L atau P</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Tempat Lahir</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Tempat lahir siswa</td>
                        <td>Jakarta</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Tanggal Lahir</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Format: YYYY-MM-DD</td>
                        <td>2005-01-15</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><strong>Kelas</strong></td>
                        <td><span class="badge bg-danger">Ya</span></td>
                        <td>Kelas siswa saat ini</td>
                        <td>XII TKJ 1</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Jurusan</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Jurusan siswa</td>
                        <td>Teknik Komputer dan Jaringan</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Sekolah</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Nama sekolah</td>
                        <td>SMK Negeri 1</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Telepon Siswa</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Nomor telepon siswa</td>
                        <td>08123456789</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td><strong>Username</strong></td>
                        <td><span class="badge bg-danger">Ya</span></td>
                        <td>Username untuk login (harus unik)</td>
                        <td>john.doe</td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td><strong>Password</strong></td>
                        <td><span class="badge bg-danger">Ya</span></td>
                        <td>Password untuk login (minimal 6 karakter)</td>
                        <td>password123</td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>Email</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Email siswa (harus unik jika diisi)</td>
                        <td>john@example.com</td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>Alamat Lengkap</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Alamat lengkap siswa</td>
                        <td>Jl. Contoh No. 123</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>Perusahaan (DUDI)</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Nama tempat PKL</td>
                        <td>PT. Contoh Perusahaan</td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td>Tanggal Mulai PKL</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Format: YYYY-MM-DD</td>
                        <td>2024-01-15</td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td>Tanggal Selesai PKL</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Format: YYYY-MM-DD</td>
                        <td>2024-06-15</td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <td>Lama Pelaksanaan</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Dalam hari</td>
                        <td>90</td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td>Status Pengajuan</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>draft/menunggu/disetujui/ditolak/selesai</td>
                        <td>menunggu</td>
                    </tr>
                    <tr>
                        <td>20</td>
                        <td>Periode PKL</td>
                        <td><span class="badge bg-warning">Tidak</span></td>
                        <td>Periode pelaksanaan PKL</td>
                        <td>2024/2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
