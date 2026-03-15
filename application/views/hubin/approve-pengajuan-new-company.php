<?php
// This view handles the approval of student-proposed companies
if(!isset($siswa)):
?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle me-2"></i>
    Data pengajuan tidak ditemukan.
</div>
<?php else: ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/pengajuan') ?>">Pengajuan PKL</a></li>
        <li class="breadcrumb-item active">Approve Pengajuan</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-alt me-2"></i>Approve Pengajuan PKL - Perusahaan Ajukan Siswa</h2>
</div>

<div class="row">
    <!-- Siswa Info -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Siswa
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="40%" class="fw-bold bg-light">Nama Siswa</td>
                                <td><?php echo $siswa->siswa_nama ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Kelas</td>
                                <td><?php echo $siswa->siswa_kelas ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Jurusan</td>
                                <td><?php echo $siswa->siswa_jurusan ? $siswa->siswa_jurusan : '-' ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Telepon</td>
                                <td><?php echo $siswa->siswa_telepon ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Alamat</td>
                                <td><?php echo isset($siswa->alamat) ? $siswa->alamat : (isset($siswa->siswa_alamat) ? $siswa->siswa_alamat : '-') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Proposed Company Info -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>Informasi Perusahaan Diajukan
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="40%" class="fw-bold bg-light">Nama Perusahaan</td>
                                <td><?php echo $siswa->other_dudi_nama ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Status</td>
                                <td>
                                    <span class="badge bg-primary">Perusahaan Diajukan Siswa</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Decision Form -->
                <div class="mt-4">
                    <h6 class="mb-3">Keputusan:</h6>
                    
                    <form action="<?php echo base_url('hubin/process_proposed_company') ?>" method="post">
                        <input type="hidden" name="siswa_code" value="<?php echo $siswa->siswa_code ?>">
                        <input type="hidden" name="proposed_company_name" value="<?php echo $siswa->other_dudi_nama ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Tindakan:</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="action" id="add_as_mitra" value="add_as_mitra" checked>
                                <label class="form-check-label" for="add_as_mitra">
                                    Tambahkan sebagai <strong>Perusahaan Mitra</strong> dan setujui pengajuan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="action" id="add_as_nonmitra" value="add_as_nonmitra">
                                <label class="form-check-label" for="add_as_nonmitra">
                                    Tambahkan sebagai <strong>Perusahaan Non-Mitra</strong> dan setujui pengajuan
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="action" id="reject" value="reject">
                                <label class="form-check-label" for="reject">
                                    <strong>Tolak</strong> pengajuan dan jangan tambahkan perusahaan
                                </label>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Perusahaan yang disetujui akan langsung ditambahkan ke sistem tanpa perlu input data tambahan.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Proses Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php endif; ?>