<?php 
$userdata = $this->session->userdata('userdata');
$search = isset($search) ? $search : ($this->input->get('username') ? $this->input->get('username') : '');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Data Siswa</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Data Siswa</h2>
</div>

<?php if($getuser->num_rows() == 0 && !isset($_GET['username'])){ ?>
<div class="alert alert-warning" role="alert">
    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Data Siswa Kosong</h6>
    <p class="mb-1">Jika Anda baru saja melakukan import data, silakan periksa:</p>
    <ul class="mb-0">
        <li>Pesan error setelah proses import (mungkin muncul di atas)</li>
        <li>Format file yang diimport (harus memiliki 20 kolom sesuai template)</li>
        <li>Data wajib yang harus diisi: Nama Siswa*, Kelas*, Username*, Password*</li>
        <li>Keunikan data seperti Username, NIS, NISN, dan Email</li>
    </ul>
    <hr>
    <small class="text-muted">Catatan: Gunakan fitur Export untuk mendapatkan template kosong yang dapat Anda isi dan import kembali.</small>
</div>
<?php } ?>


<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if(!empty($this->session->flashdata('new_user_username'))): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi Akun Baru</h5>
        <hr>
        <p class="mb-2"><strong>Akun siswa berhasil dibuat!</strong></p>
        <p class="mb-1"><strong>Nama:</strong> <?php echo $this->session->flashdata('new_user_nama'); ?></p>
        <p class="mb-1"><strong>Username:</strong> <code><?php echo $this->session->flashdata('new_user_username'); ?></code></p>
        <p class="mb-0"><strong>Password:</strong> <code><?php echo $this->session->flashdata('new_user_password'); ?></code></p>
        <hr>
        <p class="mb-0 small"><i class="fas fa-exclamation-triangle me-1"></i>Harap catat dan berikan informasi ini kepada siswa yang bersangkutan.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(!empty($this->session->flashdata('reset_password_username'))): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Password Berhasil Direset</h5>
        <hr>
        <p class="mb-2"><strong>Password telah direset!</strong></p>
        <p class="mb-1"><strong>Username:</strong> <code><?php echo $this->session->flashdata('reset_password_username'); ?></code></p>
        <p class="mb-0"><strong>Password Baru:</strong> <code><?php echo $this->session->flashdata('reset_password_new'); ?></code></p>
        <hr>
        <p class="mb-0 small"><i class="fas fa-exclamation-triangle me-1"></i>Harap berikan password baru ini kepada user yang bersangkutan.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Action Buttons -->
<div class="d-flex flex-wrap gap-3 mb-4">
    <a href="<?php echo base_url('hubin/view/tambah-siswa') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        <span>Tambah Data Siswa</span>
    </a>
    
    <!-- Export Button -->
    <a href="<?php echo base_url('export_excel/export_data_siswa') ?>" class="btn btn-success" id="export-all-btn">
        <i class="fas fa-file-excel me-2"></i>Export Data
    </a>
    <a href="#" class="btn btn-success" id="export-selected-btn" onclick="exportSelected(event)" style="display:none;">
        <i class="fas fa-file-excel me-2"></i>Export Terpilih (<span id="export-count">0</span>)
    </a>
    
    <!-- Import Button -->
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-upload"></i>
        <span>Import Data Siswa</span>
    </button>
    
    <button type="button" class="btn btn-danger" id="delete-selected-btn" onclick="deleteSelected()" style="display:none;">
        <i class="fas fa-trash"></i>
        <span>Hapus Terpilih (<span id="delete-count">0</span>)</span>
    </button>
</div>

<!-- Card -->
<div class="card fade-in">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-graduation-cap me-2"></i>Daftar Siswa
        </h5>
        <div class="d-flex align-items-center flex-grow-1 mx-4">
            <form action="" method="get" class="d-flex w-100">
                <input type="search" value="<?php echo $search ?>" name="username" 
                       placeholder="Cari NIS, nama, kelas..." class="form-control me-2" required>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div>
            <span class="badge bg-primary">
                <i class="fas fa-graduation-cap me-1"></i>Total: <?php echo isset($total_rows) ? $total_rows : 0; ?> Siswa
            </span>
        </div>
    </div>
    <div class="card-body">
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">
                            <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                        </th>
                        <th>#</th>
                        <th>NIS/NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas & Jurusan</th>
                        <th>Telepon</th>
                        <th>Perusahaan (DUDI)</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if($getuser->num_rows() > 0):
                        foreach($getuser->result() as $show):
                    ?>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="row-checkbox" value="<?php echo $show->siswa_id; ?>" data-user-code="<?php echo $show->user_code; ?>">
                        </td>
                        <td><?php echo $no++ ?></td>
                        <td>
                            <strong><?php echo !empty($show->siswa_nis) ? $show->siswa_nis : '-'; ?></strong>
                            <?php if(!empty($show->siswa_nisn)): ?>
                                <br><small class="text-muted">NISN: <?php echo $show->siswa_nisn; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo !empty($show->siswa_nama) ? $show->siswa_nama : $show->nama_lengkap; ?></strong>
                            <br><small class="text-muted">@<?php echo $show->username; ?></small>
                        </td>
                        <td>
                            <?php echo !empty($show->siswa_kelas) ? $show->siswa_kelas : '-'; ?>
                            <?php if(!empty($show->siswa_jurusan)): ?>
                                <br><small class="text-muted"><?php echo $show->siswa_jurusan; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo !empty($show->telepon) ? $show->telepon : '-'; ?>
                        </td>
                        <td>
                            <?php if(!empty($show->dudi_nama)): ?>
                                <?php echo $show->dudi_nama; ?>
                                <?php if($show->dudi_is_mitra !== null): ?>
                                    <?php if($show->dudi_is_mitra == 1): ?>
                                        <span class="badge bg-success ms-1">Mitra</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning ms-1">Non-Mitra</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $status = !empty($show->status_pengajuan) ? $show->status_pengajuan : 'belum_ada';
                            switch($status) {
                                case 'disetujui':
                                    echo '<span class="badge bg-success">Disetujui</span>';
                                    break;
                                case 'menunggu':
                                    echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                    break;
                                case 'ditolak':
                                    echo '<span class="badge bg-danger">Ditolak</span>';
                                    break;
                                case 'selesai':
                                    echo '<span class="badge bg-info">Selesai</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-secondary">Belum Ada</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('hubin/detail_biodata/'.$show->user_code) ?>" 
                                   class="btn btn-sm btn-secondary" title="Biodata Lengkap">
                                    <i class="fas fa-id-card"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-single-btn" 
                                        onclick="deleteSingle('<?php echo $show->user_code; ?>')" 
                                        title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                <?php if(isset($config) && $config['total_rows'] > 0): ?>
                    Menampilkan <?php echo isset($offset) ? $offset + 1 : 1; ?> - <?php echo isset($offset) && isset($config) ? min($offset + $config['per_page'], $config['total_rows']) : $config['total_rows']; ?> dari <?php echo $config['total_rows']; ?> data
                <?php else: ?>
                    Menampilkan 0 dari 0 data
                <?php endif; ?>
            </div>
            <nav>
                <?php echo $this->pagination->create_links(); ?>
            </nav>
        </div>
    </div>
</div>

<script>
// Select all checkboxes
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateActionButton();
}

// Update button visibility and count
function updateActionButton() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const count = checkboxes.length;
    const deleteBtn = document.getElementById('delete-selected-btn');
    const selectedCount = document.getElementById('selected-count');
    const deleteCount = document.getElementById('delete-count');
    const deleteButtons = document.querySelectorAll('.delete-single-btn');
    const exportAllBtn = document.getElementById('export-all-btn');
    const exportSelectedBtn = document.getElementById('export-selected-btn');
    const exportCount = document.getElementById('export-count');
    
    if (count > 0) {
        // Show batch delete button and export selected button
        deleteBtn.style.display = 'block';
        deleteCount.textContent = count;
        
        // Hide export all, show export selected
        if(exportAllBtn) exportAllBtn.style.display = 'none';
        if(exportSelectedBtn) {
            exportSelectedBtn.style.display = 'inline-block';
            exportCount.textContent = count;
        }
        
        // Hide all individual delete buttons
        deleteButtons.forEach(btn => {
            btn.style.display = 'none';
        });
    } else {
        // Hide batch delete button and export selected button
        deleteBtn.style.display = 'none';
        
        // Show export all, hide export selected
        if(exportAllBtn) exportAllBtn.style.display = 'inline-block';
        if(exportSelectedBtn) exportSelectedBtn.style.display = 'none';
        
        // Show all individual delete buttons
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }
}

// Delete single student
function deleteSingle(userCode) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = '<?php echo base_url("hubin/hapus_user/") ?>' + userCode;
    }
}

// Delete selected students
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih siswa yang akan dihapus!');
        return;
    }
    
    const userCodes = Array.from(checkboxes).map(cb => cb.getAttribute('data-user-code'));
    const count = userCodes.length;
    
    if (confirm('Apakah Anda yakin ingin menghapus ' + count + ' siswa terpilih?\nTindakan ini tidak dapat dibatalkan!')) {
        // Delete students one by one using the existing hapus_user endpoint
        let deletedCount = 0;
        let failedCount = 0;
        
        // Use Promise.all to handle multiple deletions
        const deletePromises = userCodes.map(code => {
            return fetch('<?php echo base_url("hubin/hapus_user") ?>/' + code, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({user_code: code})
            })
            .then(response => response.ok ? deletedCount++ : failedCount++)
            .catch(error => {
                console.error('Error deleting user:', error);
                failedCount++;
            });
        });
        
        Promise.all(deletePromises).then(() => {
            let message = 'Berhasil menghapus ' + deletedCount + ' user';
            if (failedCount > 0) {
                message += ' (' + failedCount + ' gagal dihapus)';
            }
            alert(message);
            window.location.reload();
        });
    }
}

// Add event listeners to row checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateActionButton);
    });
});

// Export selected students
function exportSelected(event) {
    event.preventDefault();
    
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih siswa yang akan diexport!');
        return;
    }
    
    const studentCodes = Array.from(checkboxes).map(cb => cb.getAttribute('data-siswa-code'));
    const count = studentCodes.length;
    
    // Redirect to export URL with selected codes - direct download
    window.location.href = '<?php echo base_url("export_excel/export_data_siswa") ?>?selected=' + encodeURIComponent(studentCodes.join(','));
}
</script>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Data Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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

                <form action="<?php echo base_url('export_excel/import_daftar_siswa') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file_excel_modal" class="form-label fw-bold">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" id="file_excel_modal" name="file_excel" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format file yang didukung: .xlsx, .xls, atau .csv (Max 5MB)</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-upload me-2"></i>Import Data
                        </button>
                        <a href="<?php echo base_url('hubin/download_template?type=excel') ?>" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Download Template Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>