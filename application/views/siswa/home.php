<?php 

$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();

// Get pengumuman terbaru
$pengumuman = $this->db->order_by('created_at', 'DESC')->limit(3)->get('tb_pengumuman')->result();

// Load model for pengelompokan
$this->load->model('M_pengelompokan');

// Get pembimbing information
$pembimbing_info = null;
if($siswa && $siswa->siswa_id) {
    $pembimbing_info = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
}

// Get data DUDI jika siswa sudah punya penempatan
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



// Get pembimbing information if application is approved
$pembimbing = null;
if($siswa && $siswa->status_pengajuan == 'disetujui'){
    $pembimbing = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
}
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-0 fs-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Siswa</h1>
        <p class="text-muted mb-0 small" id="current-date"><?php echo date("l, d F Y"); ?></p>
    </div>
    <div class="d-flex align-items-center">
        <div class="me-2">
            <span class="badge bg-primary small">
                <i class="fas fa-clock me-1"></i><span id="current-time"><?php echo date("H:i:s"); ?></span>
            </span>
        </div>
    </div>
</div>

<!-- Notification for Pembimbing Assignment -->
<?php if(isset($mentor_assignment_notification)): ?>
    <?php echo $mentor_assignment_notification; ?>
<?php else: ?>
    <?php if($siswa && $siswa->status_pengajuan == 'disetujui'): ?>
        <?php if($pembimbing_info): ?>
        <!-- Mentor assignment notification already shown in this session, so we don't show it again -->
        <?php else: ?>
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-clock me-2"></i>
            <strong>Menunggu Penugasan Pembimbing</strong> 
            Pengajuan PKL Anda sudah disetujui. Guru pembimbing akan segera ditugaskan kepada Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>




        
        
        
        
        



<!-- Stats Overview -->
<div class="row g-3 mb-4">

    <?php 
    // Get student data for status display
    $this->db->where('user_id', $userdata['id']);
    $student_data = $this->db->get('tb_siswa')->row();
    
    // Get DUDI info if available
    $dudi_info = null;
    if($student_data && $student_data->dudi_id) {
        $this->db->where('dudi_id', $student_data->dudi_id);
        $dudi_info = $this->db->get('tb_dudi')->row();
    }
    
    // Get pembimbing info if available
    $pembimbing_info = null;
    if($student_data && $student_data->status_pengajuan == 'disetujui') {
        $this->load->model('M_pengelompokan');
        $pembimbing_info = $this->M_pengelompokan->get_pembimbing_by_siswa($student_data->siswa_id);
    }
    ?>

    <div class="col-md-4">
        <div class="card border-0 rounded-lg shadow-sm h-100 stat-card-modern">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Status Pengajuan</h6>
                        <h3 class="mb-1 fw-bold fs-5">
                            <?php 
                            if($student_data && $student_data->status_pengajuan) {
                                $status = $student_data->status_pengajuan;
                                if($status == 'disetujui') {
                                    echo '<span class="text-success">Disetujui</span>';
                                } elseif($status == 'ditolak') {
                                    echo '<span class="text-danger">Ditolak</span>';
                                } elseif($status == 'menunggu') {
                                    echo '<span class="text-warning">Menunggu</span>';
                                } else {
                                    echo '<span class="text-secondary">Belum</span>';
                                }
                            } else {
                                echo '<span class="text-secondary">-</span>';
                            }
                            ?>
                        </h3>
                        <small class="text-muted">Status permohonan PKL</small>
                    </div>
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($student_data && $student_data->status_pengajuan == 'disetujui' && $dudi_info) { ?>
    <div class="col-md-4">
        <div class="card border-0 rounded-lg shadow-sm h-100 stat-card-modern">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Penempatan</h6>
                        <h3 class="mb-1 fw-bold fs-5">
                            <?php 
                            if($dudi_info) {
                                echo '<span>' . (strlen($dudi_info->dudi_nama) > 12 ? substr($dudi_info->dudi_nama, 0, 12) . '...' : $dudi_info->dudi_nama) . '</span>';
                            } else {
                                echo '<span class="text-secondary">-</span>';
                            }
                            ?>
                        </h3>
                        <small class="text-muted">Tempat PKL</small>
                    </div>
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle p-2">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="col-md-4">
        <div class="card border-0 rounded-lg shadow-sm h-100 stat-card-modern">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Penempatan</h6>
                        <h3 class="mb-1 fw-bold fs-5">
                            <span class="text-secondary">-</span>
                        </h3>
                        <small class="text-muted">Tempat PKL</small>
                    </div>
                    <div class="icon-wrapper bg-secondary bg-opacity-10 text-secondary rounded-circle p-2">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="col-md-4">
        <div class="card border-0 rounded-lg shadow-sm h-100 stat-card-modern">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Guru Pembimbing</h6>
                        <h3 class="mb-1 fw-bold fs-5">
                            <?php 
                            if($pembimbing_info) {
                                $pembimbing_nama = isset($pembimbing_info->user_nama) && $pembimbing_info->user_nama ? 
                                                  $pembimbing_info->user_nama : 
                                                  (isset($pembimbing_info->pembimbing_nama) ? $pembimbing_info->pembimbing_nama : '-');
                                echo '<span class="text-success">' . (strlen($pembimbing_nama) > 12 ? substr($pembimbing_nama, 0, 12) . '...' : $pembimbing_nama) . '</span>';
                            } else {
                                echo '<span class="text-secondary">-</span>';
                            }
                            ?>
                        </h3>
                        <small class="text-muted">
                            <?php if($pembimbing_info): ?>
                                <i class="fas fa-check-circle text-success me-1"></i>Pembimbing sudah ditugaskan
                            <?php else: ?>
                                <i class="fas fa-clock text-warning me-1"></i>Menunggu penugasan
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="icon-wrapper <?php echo $pembimbing_info ? 'bg-success' : 'bg-warning'; ?> bg-opacity-10 <?php echo $pembimbing_info ? 'text-success' : 'text-warning'; ?> rounded-circle p-2">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Grid -->
<div class="card border-0 rounded-lg shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fs-6"><i class="fas fa-th-large me-2"></i>Menu Utama</h5>
    </div>
    <div class="card-body">
        <div class="menu-grid">
            <a href="<?php echo base_url('siswa/view/id-card') ?>" class="menu-item">
                <i class="fas fa-id-card"></i>
                <span>ID Card</span>
            </a>
            <a href="<?php echo base_url('siswa/view/kontak-pembimbing') ?>" class="menu-item">
                <i class="fas fa-address-book"></i>
                <span>Info Pembimbing</span>
            </a>
            <?php 
            // Check if student already has a record
            $this->db->where('user_id', $userdata['id']);
            $existing_siswa = $this->db->get('tb_siswa')->row();
                    
            if($existing_siswa && $existing_siswa->status_pengajuan == 'menunggu') {
                // If waiting for approval, show pengajuan status page
            ?>
            <a href="<?php echo base_url('siswa/view/pengajuan') ?>" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Status Pengajuan</span>
            </a>
            <?php 
            } else {
                // If no application yet, rejected, draft, or approved, show buat pengajuan
                // Students with approved applications can also re-apply
            ?>
            <a href="<?php echo base_url('siswa/view/buat-pengajuan') ?>" class="menu-item">
                <i class="fas fa-plus-circle"></i>
                <span>
                    <?php echo ($existing_siswa && $existing_siswa->status_pengajuan == 'disetujui') ? 'Ajukan Ulang' : 'Buat Pengajuan'; ?>
                </span>
            </a>
            <?php } ?>
            <a href="<?php echo base_url('siswa/view/penempatan') ?>" class="menu-item">
                <i class="fas fa-building"></i>
                <span>Penempatan</span>
            </a>
        </div>
    </div>
</div>

<!-- Informasi Section -->
<?php if($pengumuman){ ?>
<div class="card border-0 rounded-lg shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fs-6"><i class="fas fa-bullhorn me-2 text-primary"></i>Pengumuman Terkini</h5>
    </div>
    <div class="card-body">
        <?php foreach($pengumuman as $p){ ?>
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0 mt-1">
                    <i class="fas fa-info-circle text-primary fa-lg"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 fs-6 fw-bold"><?php echo $p->judul ?></h6>
                    <p class="small text-muted mb-2">
                        <?php echo date('d M Y', strtotime($p->created_at)); ?> | 
                        <?php 
                        $this->db->where('id', $p->created_by);
                        $creator = $this->db->get('tb_user')->row();
                        echo $creator ? $creator->nama_lengkap : 'Admin';
                        ?>
                    </p>
                    <p class="mb-2 small">
                        <?php echo substr($p->isi, 0, 100); ?>...
                    </p>
                    <a href="<?php echo base_url('siswa/view/detail-pengumuman/'.$p->pengumuman_id) ?>" class="btn btn-sm btn-outline-primary px-3 py-1">
                        <i class="fas fa-arrow-right me-1"></i>Selengkapnya
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>


<!-- Warning Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <h5 class="mt-3 mb-2">Peringatan</h5>
                <p id="warning-message">Anda berada di luar radius kantor, perbarui lokasi Anda</p>
                <button type="button" class="btn btn-warning mt-3" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>


// Real-time clock update
function updateClock() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
    document.getElementById('current-time').textContent = timeStr;
    document.getElementById('current-time-display').textContent = timeStr;
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call

// Function to check for pembimbing assignment updates
function checkPembimbingAssignment() {
    // Only check if student is approved but doesn't have pembimbing info displayed
    <?php if($siswa && $siswa->status_pengajuan == 'disetujui' && !$pembimbing_info): ?>
    fetch('<?php echo base_url("siswa/check_pembimbing_assignment"); ?>')
        .then(response => response.json())
        .then(data => {
            if(data.has_pembimbing) {
                // Show notification that pembimbing has been assigned
                // Only show this notification if it hasn't been shown in the current session
                // We'll rely on the server-side notification instead to ensure it only shows once
            }
        })
        .catch(error => {
            console.log('Error checking pembimbing assignment:', error);
        });
    <?php endif; ?>
}

// Check for pembimbing assignment every 30 seconds
setInterval(checkPembimbingAssignment, 30000);


</script>

