<?php 
$search = $this->input->get('nama_siswa') ? $this->input->get('nama_siswa') : '';

// Count total rows with search
if($search){
    $this->db->like('siswa_nama', $search);
}
// Hanya tampilkan pengajuan yang belum disetujui (menunggu dan ditolak)
$this->db->where('status_pengajuan !=', 'draft');
$this->db->where('status_pengajuan !=', 'disetujui');
$total_rows = $this->db->get('tb_siswa')->num_rows();

// No pagination - show all records

// Get all data without pagination
if($search){
    $this->db->like('siswa_nama', $search);
}
// Hanya tampilkan pengajuan yang belum disetujui (menunggu dan ditolak)
$this->db->where('tb_siswa.status_pengajuan !=', 'draft');
$this->db->where('tb_siswa.status_pengajuan !=', 'disetujui');
$this->db->select('tb_siswa.*, tb_dudi.dudi_nama, tb_dudi.is_mitra as dudi_is_mitra');
$this->db->from('tb_siswa');
$this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
$this->db->order_by('tb_siswa.created_at', 'DESC');
$getsiswa = $this->db->get();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active"><i class="fas fa-file-check me-1"></i>Verifikasi Pengajuan</li>
    </ol>
</nav>

<?php 
// Count pending submissions
$pending_count = $this->db->where('status_pengajuan', 'menunggu')->get('tb_siswa')->num_rows();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-check me-2"></i>Verifikasi Pengajuan</h2>
</div>

<!-- Notification Banner for Pending Submissions -->
<?php if($pending_count > 0): ?>
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-clock me-2"></i>
    <strong>Ada <?php echo $pending_count ?> pengajuan PKL menunggu!</strong> Silakan tinjau dan proses pengajuan dari siswa.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Card -->
<div class="card fade-in">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-file-check me-2"></i>Daftar Pengajuan
        </h5>
        <div class="d-flex align-items-center flex-grow-1 mx-4">
            <form action="" method="get" class="d-flex w-100">
                <input type="search" value="<?php echo $search ?>" name="nama_siswa" 
                       placeholder="Cari nama siswa..." class="form-control me-2" required>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Export Buttons -->
            <a href="<?php echo base_url('export_excel/export_data_pengajuan_pkl') ?>" class="btn btn-success btn-sm" id="export-all-btn" title="Export Semua Data">
                <i class="fas fa-file-excel"></i> Export Data
            </a>
            <a href="#" class="btn btn-success btn-sm" id="export-selected-btn" onclick="exportSelected(event)" style="display:none;">
                <i class="fas fa-file-excel"></i> Export Terpilih (<span id="export-count">0</span>)
            </a>
            <span class="badge bg-primary">
                <i class="fas fa-file-check me-1"></i>Total: <?php echo $getsiswa->num_rows() ?> Pengajuan
            </span>
        </div>
    </div>
    <div class="card-body">

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Perusahaan</th>
                        <th>Status</th>
                        <th width="25%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if($getsiswa->num_rows() > 0){
                        foreach($getsiswa->result() as $show){
                    ?>
                    <tr>
                        <td><input type="checkbox" class="select-item" value="<?php echo $show->siswa_id ?>" data-siswa-code="<?php echo $show->siswa_code ?>"></td>
                        <td><?php echo $no++ ?></td>
                        <td><strong><?php echo $show->siswa_nama ?></strong></td>
                        <td><?php echo $show->siswa_kelas ?></td>
                        <td>
                            <?php if($show->other_dudi_nama): ?>
                                <span class="text-primary fw-bold">[PERUSAHAAN AJUKAN SISWA]</span> <?php echo $show->other_dudi_nama ?>
                                <br>
                                <a href="<?php echo base_url('hubin/detail_perusahaan_ajukan_siswa/'.$show->siswa_code) ?>" 
                                   class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-building me-1"></i>Lihat Detail Perusahaan
                                </a>
                            <?php else: ?>
                                <?php if($show->dudi_nama): ?>
                                    <?php echo $show->dudi_nama ?>
                                    <?php if($show->dudi_is_mitra !== null): ?>
                                        <?php if($show->dudi_is_mitra == 1): ?>
                                            <span class="badge bg-success ms-1">Mitra</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning ms-1">Non-Mitra</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo '-'; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $badge_class = 'bg-info';
                            if($show->status_pengajuan == 'disetujui') $badge_class = 'bg-success';
                            if($show->status_pengajuan == 'ditolak') $badge_class = 'bg-danger';
                            if($show->status_pengajuan == 'menunggu') $badge_class = 'bg-warning';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($show->status_pengajuan); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('hubin/view/detail-pengajuan/'.$show->siswa_code) ?>" 
                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-search me-1"></i>Lihat
                                </a>
                                <?php if($show->status_pengajuan == 'menunggu'){ ?>
                                <a href="<?php echo base_url('hubin/approve_pengajuan/'.$show->siswa_code) ?>" 
                                   onclick="return confirm('Setujui pengajuan ini?')" 
                                   class="btn btn-sm btn-success" title="Setujui">
                                    <i class="fas fa-check-circle me-1"></i>Setujui
                                </a>
                                <a href="<?php echo base_url('hubin/tolak_pengajuan/'.$show->siswa_code) ?>" 
                                   onclick="return confirm('Tolak pengajuan ini?')" 
                                   class="btn btn-sm btn-danger" title="Tolak">
                                    <i class="fas fa-times-circle me-1"></i>Tolak
                                </a>
                                <?php } ?>
                                <a href="<?php echo base_url('hubin/hapus_pengajuan/'.$show->siswa_code) ?>" 
                                   onclick="return confirm('Hapus pengajuan ini? Data yang dihapus tidak dapat dikembalikan!')" 
                                   class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash-alt me-1"></i>Hapus
                                </a>
                            </div>
                            <div class="mt-1">
                                <?php 
                                $file_count = 0;
                                if($show->surat_permohonan) $file_count++;
                                if($show->surat_balasan) $file_count++;
                                if($file_count > 0) {
                                    echo '<span class="badge bg-primary mt-1" title="Jumlah berkas yang diunggah">';
                                    echo '<i class="fas fa-paperclip me-1"></i>' . $file_count . ' Berkas';
                                    echo '</span>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Belum ada pengajuan PKL yang masuk
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        


    </div>
</div>

<script>
// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.select-item');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateActionButton();
}

// Update button visibility and count
function updateActionButton() {
    const checkboxes = document.querySelectorAll('.select-item:checked');
    const count = checkboxes.length;
    const exportAllBtn = document.getElementById('export-all-btn');
    const exportSelectedBtn = document.getElementById('export-selected-btn');
    const exportCount = document.getElementById('export-count');
    
    if (count > 0) {
        // Hide export all, show export selected
        exportAllBtn.style.display = 'none';
        exportSelectedBtn.style.display = 'inline-block';
        exportCount.textContent = count;
    } else {
        // Show export all, hide export selected
        exportAllBtn.style.display = 'inline-block';
        exportSelectedBtn.style.display = 'none';
    }
}

// Export selected submissions
function exportSelected(event) {
    event.preventDefault();
    const checkboxes = document.querySelectorAll('.select-item:checked');
    if (checkboxes.length === 0) {
        alert('Pilih pengajuan yang akan diekspor!');
        return;
    }
    
    const submissionIds = Array.from(checkboxes).map(cb => cb.value);
    const count = submissionIds.length;
    
    // Redirect to export with selected IDs
    window.location.href = '<?php echo base_url("export_excel/export_data_pengajuan_pkl") ?>?selected=' + encodeURIComponent(submissionIds.join(','));
}

// Add event listeners to row checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.select-item');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateActionButton);
    });
    
    // Initialize button state on page load
    updateActionButton();
});
</script>