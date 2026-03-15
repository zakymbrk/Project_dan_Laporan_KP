<?php 
$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();

// Get data DUDI if available
$dudi = null;
if($siswa && $siswa->dudi_id){
    $this->db->where('dudi_id', $siswa->dudi_id);
    $dudi = $this->db->get('tb_dudi')->row();
} elseif($siswa && $siswa->other_dudi_nama) {
    // For students with other/unregistered companies
    $dudi = (object) [
        'dudi_nama' => $siswa->other_dudi_nama
    ];
}
?>

<div class="card-mobile">
    <h5 class="mb-4">
        <i class="fas fa-<?php echo ($siswa && $siswa->status_pengajuan == 'disetujui') ? 'check-circle text-success' : 'check-circle text-success'; ?> me-2"></i>
        <?php 
        if($siswa && $siswa->status_pengajuan == 'disetujui') {
            echo 'Pengajuan Berhasil Disetujui';
        } else {
            echo 'Pengajuan Berhasil Dikirim';
        }
        ?>
    </h5>
    
    <?php if($siswa && $siswa->status_pengajuan == 'disetujui'): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Pengajuan PKL Anda Berhasil Disetujui</strong><br>
            Selamat! Pengajuan PKL Anda telah disetujui. Anda tetap bisa mengajukan ulang untuk mengganti perusahaan atau periode.
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Pengajuan PKL Anda Sedang Diproses</strong><br>
            Tim kami sedang meninjau pengajuan PKL Anda. Silakan tunggu konfirmasi lebih lanjut.
        </div>
    <?php endif; ?>
    
    <div class="info-card mb-4">
        <h6 class="info-title"><i class="fas fa-file-alt me-2"></i>Informasi Pengajuan</h6>
        <div class="row">
            <div class="col-5 info-title">Status Pengajuan</div>
            <div class="col-7 info-value">
                <?php if($siswa && $siswa->status_pengajuan == 'disetujui'): ?>
                    <span class="badge bg-success">Berhasil Disetujui</span>
                <?php elseif($siswa && $siswa->status_pengajuan == 'ditolak'): ?>
                    <span class="badge bg-danger">Ditolak</span>
                <?php else: ?>
                    <span class="badge bg-warning">Sedang Diproses</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-5 info-title">Tanggal Pengajuan</div>
            <div class="col-7 info-value"><?php echo $siswa && $siswa->created_at ? date('d M Y, H:i', strtotime($siswa->created_at)) : date('d M Y, H:i'); ?></div>
        </div>
        <div class="row mt-2">
            <div class="col-5 info-title">Tempat PKL</div>
            <div class="col-7 info-value"><?php echo $dudi ? $dudi->dudi_nama : '-'; ?></div>
        </div>
    </div>
    
    <div class="card p-3 bg-light">
        <h6 class="mb-3"><i class="fas fa-sync-alt me-2"></i>Status Selanjutnya</h6>
        <?php if($siswa && $siswa->status_pengajuan == 'disetujui'): ?>
            <ul class="mb-0">
                <li class="mb-2">Pengajuan Anda telah disetujui</li>
                <li class="mb-2">Pembimbing akan segera ditugaskan kepada Anda</li>
                <li>Anda dapat melihat detail penempatan di halaman Beranda</li>
            </ul>
        <?php else: ?>
            <ul class="mb-0">
                <li class="mb-2">Tim kami akan meninjau pengajuan Anda</li>
                <li class="mb-2">Anda akan menerima notifikasi saat status diperbarui</li>
                <li>Apabila disetujui, pembimbing akan ditugaskan kepada Anda</li>
            </ul>
        <?php endif; ?>
    </div>
    
    <div class="d-flex gap-2 mt-4">
        <a href="<?php echo base_url('siswa/view/home') ?>" class="btn btn-primary btn-mobile flex-grow-1">
            <i class="fas fa-home me-2"></i>Beranda
        </a>
        <?php if($siswa && $siswa->status_pengajuan == 'ditolak'): ?>
            <a href="<?php echo base_url('siswa/view/buat-pengajuan') ?>" class="btn btn-success btn-mobile">
                <i class="fas fa-redo me-2"></i>Ajukan Ulang
            </a>
        <?php elseif($siswa && ($siswa->status_pengajuan == 'disetujui' || $siswa->status_pengajuan != 'menunggu')): ?>
            <a href="<?php echo base_url('siswa/view/buat-pengajuan') ?>" class="btn <?php echo ($siswa->status_pengajuan == 'disetujui') ? 'btn-success' : 'btn-outline-secondary'; ?> btn-mobile">
                <i class="fas fa-edit me-2"></i>
                <?php 
                if($siswa->status_pengajuan == 'disetujui') {
                    echo 'Ajukan Ulang (Pengajuan Diproses Hubin)';
                } else {
                    echo 'Edit Pengajuan';
                }
                ?>
            </a>
        <?php endif; ?>
    </div>
</div>