<?php 
$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();

// Get all pengumuman
$this->db->order_by('created_at', 'DESC');
$pengumuman = $this->db->get('tb_pengumuman')->result();

// Get student's pembimbing if assigned
$pembimbing = null;
if($siswa && $siswa->status_pengajuan == 'disetujui'){
    $this->load->model('M_pengelompokan');
    $pembimbing = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
}
?>

<div class="card-mobile">
    <h5 class="mb-3"><i class="fas fa-bullhorn me-2 text-primary"></i>Informasi Terkini</h5>
    
    <!-- Student Info Card -->
    <?php if($siswa): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h6><i class="fas fa-user me-2 text-primary"></i>Profil Siswa</h6>
            <hr>
            <div class="row">
                <div class="col-5">
                    <p class="mb-1"><small>Nama</small></p>
                    <p class="mb-1"><small>Kelas</small></p>
                    <p class="mb-1"><small>Telepon</small></p>
                    <p class="mb-1"><small>Alamat</small></p>
                    <p class="mb-1"><small>Status</small></p>
                    <?php if($siswa->status_pengajuan == 'disetujui'): ?>
                        <p class="mb-1"><small>Pembimbing</small></p>
                        <?php if($pembimbing): ?>
                            <p class="mb-1"><small>Kontak Pembimbing</small></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-7">
                    <p class="mb-1"><strong>: <?php echo $siswa->siswa_nama; ?></strong></p>
                    <p class="mb-1"><strong>: <?php echo $siswa->siswa_kelas; ?></strong></p>
                    <p class="mb-1"><strong>: <?php echo $siswa->siswa_telepon; ?></strong></p>
                    <p class="mb-1"><strong>: <?php echo isset($siswa->alamat) ? $siswa->alamat : (isset($siswa->siswa_alamat) ? $siswa->siswa_alamat : '-'); ?></strong></p>
                    <p class="mb-1"><strong>: 
                        <span class="badge bg-<?php echo $siswa->status_pengajuan == 'disetujui' ? 'success' : ($siswa->status_pengajuan == 'ditolak' ? 'danger' : ($siswa->status_pengajuan == 'menunggu' ? 'warning' : 'secondary')); ?>">
                            <?php echo ucfirst($siswa->status_pengajuan); ?>
                        </span>
                    </strong></p>
                    <?php if($siswa->status_pengajuan == 'disetujui'): ?>
                        <p class="mb-1"><strong>: 
                            <?php if($pembimbing): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <?php 
                                        $pembimbing_nama = isset($pembimbing->user_nama) && $pembimbing->user_nama ? 
                                                        $pembimbing->user_nama : 
                                                        (isset($pembimbing->pembimbing_nama) ? $pembimbing->pembimbing_nama : 'Tidak ada');
                                        echo $pembimbing_nama;
                                    ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Menunggu penugasan
                                </span>
                            <?php endif; ?>
                        </strong></p>
                        <?php if($pembimbing): ?>
                            <p class="mb-1"><strong>: 
                                <a href="<?php echo base_url('siswa/view/kontak-pembimbing') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-address-book me-1"></i>Lihat Kontak
                                </a>
                            </strong></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Pengumuman Terbaru -->
    <div class="card">
        <div class="card-body">
            <h6><i class="fas fa-info-circle me-2 text-primary"></i>Pengumuman Terbaru</h6>
            <hr>
            <?php if($pengumuman): ?>
                <?php foreach(array_slice($pengumuman, 0, 5) as $p): ?>
                    <div class="mb-3">
                        <h6 class="mb-1"><?php echo $p->judul; ?></h6>
                        <small class="text-muted">
                            <?php echo date('d M Y', strtotime($p->created_at)); ?> | 
                            <?php 
                            $this->db->where('id', $p->created_by);
                            $creator = $this->db->get('tb_user')->row();
                            echo $creator ? $creator->nama_lengkap : 'Admin';
                            ?>
                        </small>
                        <p class="mt-1 mb-0"><?php echo character_limiter(strip_tags($p->isi), 100); ?></p>
                        <a href="<?php echo base_url('siswa/view/detail-pengumuman/'.$p->pengumuman_id) ?>" class="btn btn-sm btn-outline-primary mt-2">Selengkapnya</a>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center mb-0">Tidak ada pengumuman terbaru</p>
            <?php endif; ?>
        </div>
    </div>
</div>