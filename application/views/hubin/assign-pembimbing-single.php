<?php
if (!isset($siswa)) {
    redirect('hubin/view/pengajuan');
    return;
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/pengajuan') ?>">Pengajuan PKL</a></li>
        <li class="breadcrumb-item active">Assign Pembimbing</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-tie me-2"></i>Assign Pembimbing untuk Siswa</h2>
</div>

<?php if ($this->session->flashdata('error_message')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $this->session->flashdata('error_message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Student Info Card -->
<div class="card fade-in mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Siswa</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%" class="fw-bold">Nama Siswa</td>
                        <td>: <?php echo $siswa->siswa_nama; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NIS</td>
                        <td>: <?php echo $siswa->siswa_nis ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kelas</td>
                        <td>: <?php echo $siswa->siswa_kelas; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%" class="fw-bold">Status Pengajuan</td>
                        <td>: 
                            <?php 
                            $status_badge = 'bg-warning';
                            if($siswa->status_pengajuan == 'disetujui') $status_badge = 'bg-success';
                            if($siswa->status_pengajuan == 'ditolak') $status_badge = 'bg-danger';
                            if($siswa->status_pengajuan == 'menunggu') $status_badge = 'bg-warning';
                            ?>
                            <span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst($siswa->status_pengajuan); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Perusahaan</td>
                        <td>: <?php echo $siswa->dudi_id ? $siswa->dudi_nama : 'Belum ditentukan'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Periode</td>
                        <td>: <?php echo $siswa->periode ?: '-'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Assignment Form -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Assign Guru Pembimbing</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('hubin/assign_single_student_pembimbing'); ?>" method="post">
            <input type="hidden" name="siswa_code" value="<?php echo $siswa->siswa_code; ?>">
            
            <div class="mb-3">
                <label for="pembimbing_id" class="form-label">
                    <i class="fas fa-user-tie me-2"></i>Pilih Guru Pembimbing <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="pembimbing_id" name="pembimbing_id" required>
                    <option value="" disabled selected>-- Pilih Guru Pembimbing --</option>
                    <?php foreach($pembimbing_list as $pembimbing): ?>
                        <option value="<?php echo $pembimbing->pembimbing_id; ?>">
                            <?php echo $pembimbing->pembimbing_nama; ?> (<?php echo $pembimbing->pembimbing_nip ?: 'NIP tidak tersedia'; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Siswa ini telah disetujui pengajuan PKL-nya dan sekarang perlu diassign ke guru pembimbing.
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Pengajuan
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-check me-2"></i>Assign Pembimbing
                </button>
            </div>
        </form>
    </div>
</div>