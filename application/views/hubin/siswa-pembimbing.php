<?php
// Models are loaded in controller
// $this->load->model('M_pembimbing');
// $this->load->model('M_pengelompokan');

// Get pembimbing data by code
$pembimbing = null;
$siswa_list = array();

if(isset($pembimbing_code)){
    // Get pembimbing data
    $this->db->where('pembimbing_code', $pembimbing_code);
    $pembimbing = $this->db->get('tb_pembimbing')->row();
    
    if($pembimbing){
        // Get siswa assigned to this pembimbing
        $siswa_query = $this->M_pengelompokan->get_siswa_by_pembimbing($pembimbing->pembimbing_id);
        $siswa_list = $siswa_query->result();
    }
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/data-pembimbing') ?>">Data Pembimbing</a></li>
        <li class="breadcrumb-item active">Daftar Siswa Bimbingan</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Daftar Siswa Bimbingan</h2>
</div>

<?php if(!$pembimbing): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle me-2"></i>
    Data pembimbing tidak ditemukan.
</div>
<?php else: ?>

<div class="row">
    <!-- Pembimbing Info -->
    <div class="col-md-12">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>Informasi Pembimbing
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="20%" class="fw-bold bg-light">Nama Pembimbing</td>
                                <td><?php echo $pembimbing->pembimbing_nama ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Jumlah Siswa Bimbingan</td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo count($siswa_list); ?> Siswa
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Status</td>
                                <td>
                                    <?php 
                                    $jumlah_siswa = count($siswa_list);
                                    $status_class = $jumlah_siswa >= 20 ? 'bg-success' : ($jumlah_siswa > 0 ? 'bg-warning' : 'bg-secondary');
                                    $status_text = $jumlah_siswa >= 20 ? 'Penuh' : ($jumlah_siswa > 0 ? 'Tersedia' : 'Kosong');
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Siswa List -->
<div class="card fade-in mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Siswa Bimbingan
        </h5>
    </div>
    <div class="card-body">
        <?php if(!empty($siswa_list)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Status Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($siswa_list as $index => $siswa): ?>
                    <tr>
                        <td><?php echo $index + 1 ?></td>
                        <td><?php echo $siswa->siswa_nama ?></td>
                        <td><?php echo $siswa->siswa_kelas ?></td>
                        <td><?php echo $siswa->siswa_jurusan ?></td>
                        <td>
                            <?php 
                            $badge_class = 'bg-info';
                            if($siswa->status_pengajuan == 'disetujui') $badge_class = 'bg-success';
                            if($siswa->status_pengajuan == 'ditolak') $badge_class = 'bg-danger';
                            if($siswa->status_pengajuan == 'menunggu') $badge_class = 'bg-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($siswa->status_pengajuan); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('hubin/view/detail-siswa/'.$siswa->siswa_code) ?>" 
                               class="btn btn-sm btn-info" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            Belum ada siswa yang dibimbing
        </div>
        <?php endif; ?>
    </div>
</div>



<?php endif; ?>