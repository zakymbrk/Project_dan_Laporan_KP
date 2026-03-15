<?php 
// Load helper
$this->load->helper('view');

// Statistik Umum
$jumlah_siswa = $this->db->get('tb_siswa')->num_rows();
$jumlah_user = $this->db->get('tb_user')->num_rows();
$jumlah_pembimbing = $this->db->where('level', 3)->get('tb_user')->num_rows();
$jumlah_dudi = $this->db->get('tb_dudi')->num_rows();

// Statistik Pengajuan
$pengajuan_menunggu = $this->db->where('status_pengajuan', 'pending')->get('tb_siswa')->num_rows();
$pengajuan_disetujui = $this->db->where('status_pengajuan', 'disetujui')->get('tb_siswa')->num_rows();
$pengajuan_ditolak = $this->db->where('status_pengajuan', 'ditolak')->get('tb_siswa')->num_rows();

// Siswa belum di-assign pembimbing
$this->db->select('tb_siswa.siswa_id');
$this->db->from('tb_siswa');
$this->db->join('tb_pengelompokan', 'tb_pengelompokan.siswa_id = tb_siswa.siswa_id', 'left');
$this->db->where('tb_pengelompokan.siswa_id IS NULL');
$this->db->where('tb_siswa.status_pengajuan', 'disetujui');
$siswa_belum_assign = $this->db->get()->num_rows();

// Get pengumuman terbaru
$pengumuman = $this->db->order_by('created_at', 'DESC')->limit(3)->get('tb_pengumuman')->result();

// Get recent pengajuan
$this->db->select('tb_siswa.*, tb_user.nama_lengkap');
$this->db->from('tb_siswa');
$this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id');
$this->db->where('tb_siswa.status_pengajuan', 'pending');
$this->db->order_by('tb_siswa.created_at', 'DESC');
$this->db->limit(5);
$recent_pengajuan = $this->db->get()->result();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-0"><i class="fas fa-tachometer-alt me-3"></i>Dashboard Pengajuan PKL</h1>
        <p class="text-muted mb-0" id="current-date"><?php echo date("l, d F Y"); ?></p>
    </div>
    <div class="d-flex align-items-center">
        <div class="me-3">
            <span class="badge bg-primary">
                <i class="fas fa-clock me-1"></i><span id="current-time"><?php echo date("H:i:s"); ?></span>
            </span>
        </div>
    </div>
</div>

<!-- Notification Banner for Pending Submissions -->
<?php if($pengajuan_menunggu > 0): ?>
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Ada <?php echo $pengajuan_menunggu ?> pengajuan PKL menunggu!</strong> Silakan tinjau dan proses pengajuan dari siswa.
    <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="alert-link fw-bold">Lihat Pengajuan</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Quick Action Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="text-decoration-none">
            <div class="card border-0 rounded-lg shadow-sm h-100 text-center card-highlight-hover">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                    <h4 class="mb-1 fw-bold text-dark"><?php echo $pengajuan_menunggu ?></h4>
                    <p class="mb-0 text-muted">Pengajuan Menunggu</p>
                    <small class="text-success fw-semibold">Tinjau sekarang <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="<?php echo base_url('hubin/view/assign-pembimbing') ?>" class="text-decoration-none">
            <div class="card border-0 rounded-lg shadow-sm h-100 text-center card-highlight-hover">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <h4 class="mb-1 fw-bold text-dark"><?php echo $jumlah_siswa ?></h4>
                    <p class="mb-0 text-muted">Total Siswa</p>
                    <small class="text-success fw-semibold">Lihat data <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="<?php echo base_url('hubin/view/data-dudi') ?>" class="text-decoration-none">
            <div class="card border-0 rounded-lg shadow-sm h-100 text-center card-highlight-hover">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                    <h4 class="mb-1 fw-bold text-dark"><?php echo $jumlah_dudi ?></h4>
                    <p class="mb-0 text-muted">Perusahaan</p>
                    <small class="text-success fw-semibold">Kelola DUDI <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="<?php echo base_url('hubin/view/data-pembimbing') ?>" class="text-decoration-none">
            <div class="card border-0 rounded-lg shadow-sm h-100 text-center card-highlight-hover">
                <div class="card-body p-4">
                    <div class="icon-wrapper bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <h4 class="mb-1 fw-bold text-dark"><?php echo $jumlah_pembimbing ?></h4>
                    <p class="mb-0 text-muted">Pembimbing</p>
                    <small class="text-success fw-semibold">Kelola pembimbing <i class="fas fa-arrow-right ms-1"></i></small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Recent Submissions -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-lg shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-contract me-2 text-primary"></i>Pengajuan Terbaru</h5>
                    <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                <?php if($recent_pengajuan): ?>
                    <?php foreach($recent_pengajuan as $pengajuan): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?php echo $pengajuan->siswa_nama ?></h6>
                                <p class="small text-muted mb-1">
                                    <i class="fas fa-user me-1"></i><?php echo $pengajuan->nama_lengkap ?>
                                </p>
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('d M Y H:i', strtotime($pengajuan->created_at)); ?>
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning">Menunggu</span>
                                <br>
                                <a href="<?php echo base_url('hubin/view/detail-pengajuan/'.$pengajuan->siswa_code) ?>" 
                                   class="btn btn-sm btn-primary mt-2">Proses</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                        <p class="text-muted">Belum ada pengajuan terbaru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Statistics Overview -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-lg shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Statistik</h5>
            </div>
            <div class="card-body">
                <div class="stat-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Disetujui</span>
                        <span class="fw-bold text-success"><?php echo $pengajuan_disetujui ?></span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?php echo $jumlah_siswa > 0 ? ($pengajuan_disetujui/$jumlah_siswa)*100 : 0 ?>%"></div>
                    </div>
                </div>
                
                <div class="stat-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Ditolak</span>
                        <span class="fw-bold text-danger"><?php echo $pengajuan_ditolak ?></span>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: <?php echo $jumlah_siswa > 0 ? ($pengajuan_ditolak/$jumlah_siswa)*100 : 0 ?>%"></div>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Belum Assign Pembimbing</span>
                        <span class="fw-bold text-warning"><?php echo $siswa_belum_assign ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Announcements -->
        <?php if($pengumuman): ?>
        <div class="card border-0 rounded-lg shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-bullhorn me-2 text-info"></i>Pengumuman</h5>
            </div>
            <div class="card-body">
                <?php foreach($pengumuman as $p): ?>
                <div class="mb-3 pb-3 border-bottom">
                    <h6 class="mb-1"><?php echo $p->judul ?></h6>
                    <p class="small text-muted mb-2">
                        <?php echo date('d M Y', strtotime($p->created_at)); ?>
                    </p>
                    <p class="small mb-0">
                        <?php echo substr($p->isi, 0, 80); ?>...
                    </p>
                </div>
                <?php endforeach; ?>
                <a href="<?php echo base_url('hubin/view/pengumuman') ?>" class="btn btn-sm btn-outline-info w-100">Lihat Semua</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.card-highlight-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}

.icon-wrapper {
    transition: all 0.3s ease;
}

.card-highlight-hover:hover .icon-wrapper {
    transform: scale(1.1);
}
</style>

<script>
// Update time every second
setInterval(function() {
    var now = new Date();
    var timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('current-time').textContent = timeString;
}, 1000);

// Update date
var today = new Date();
var dateString = today.toLocaleDateString('id-ID', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
});
document.getElementById('current-date').textContent = dateString;
</script>