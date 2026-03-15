<?php 
$search = $this->input->get('nama_siswa') ? $this->input->get('nama_siswa') : '';

// Get all students with their pembimbing information
$this->db->select('tb_siswa.*, tb_user.nama_lengkap as user_nama, tb_dudi.dudi_nama, tb_dudi.is_mitra as dudi_is_mitra, tb_pembimbing.pembimbing_nama as pembimbing_nama, tb_pembimbing.pembimbing_id as pembimbing_id, tb_pengelompokan.pembimbing_id as kelompok_pembimbing_id');
$this->db->from('tb_siswa');
$this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
$this->db->join('tb_dudi', 'tb_dudi.dudi_id = tb_siswa.dudi_id', 'left');
$this->db->join('tb_pengelompokan', 'tb_pengelompokan.siswa_id = tb_siswa.siswa_id', 'left');
$this->db->join('tb_pembimbing', 'tb_pembimbing.pembimbing_id = tb_pengelompokan.pembimbing_id', 'left');
$this->db->where('tb_siswa.status_pengajuan', 'disetujui'); // Hanya siswa yang disetujui

if($search){
    $this->db->like('tb_siswa.siswa_nama', $search);
}

$this->db->order_by('CASE WHEN tb_pengelompokan.pembimbing_id IS NULL THEN 0 ELSE 1 END', '', FALSE);
$this->db->order_by('tb_pembimbing.pembimbing_nama', 'ASC');
$this->db->order_by('tb_siswa.siswa_nama', 'ASC');
$all_siswa = $this->db->get()->result();

// Group students by assignment status
$grouped_siswa = array();

foreach($all_siswa as $siswa){
    // Check if student is assigned through tb_pengelompokan
    $is_assigned = !empty($siswa->kelompok_pembimbing_id);
    
    if($is_assigned) {
        $group_key = 'assigned';
        $group_name = 'Sudah Di-Assign Guru Pembimbing';
    } else {
        $group_key = 'unassigned';
        $group_name = 'Belum Di-Assign Guru Pembimbing';
    }
    
    if(!isset($grouped_siswa[$group_key])){
        $grouped_siswa[$group_key] = array(
            'group_key' => $group_key,
            'group_name' => $group_name,
            'siswa' => array()
        );
    }
    $grouped_siswa[$group_key]['siswa'][] = $siswa;
}

$total_siswa = count($all_siswa);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Assign Pembimbing</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-tie me-2"></i>Assign Pembimbing</h2>
</div>

<?php if(!empty($this->session->flashdata('error_message'))){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $this->session->flashdata('error_message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Action Bar -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <!-- Left: Assign Selected Button -->
    <div>
        <button type="button" class="btn btn-primary" id="assign-selected-btn" onclick="openAssignModal()" style="display:none;">
            <i class="fas fa-user-plus me-2"></i>Assign Terpilih
        </button>
    </div>
    
    <!-- Center: Export and Search -->
    <div class="d-flex gap-2 flex-grow-1 justify-content-center" style="max-width: 800px;">
        <!-- Export Button -->
        <a href="<?php echo base_url('export_excel/export_assign_pembimbing') ?>" class="btn btn-success" title="Export Data">
            <i class="fas fa-file-excel"></i> Export Data
        </a>
                    
        <!-- Search Form -->
        <form action="" method="get" class="d-flex flex-grow-1">
            <div class="input-group w-100">
                <input type="search" value="<?php echo $search ?>" name="nama_siswa" 
                       placeholder="Cari nama siswa..." class="form-control" required>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Right: Delete Selected Button -->
    <div>
        <button type="button" class="btn btn-danger" id="delete-selected-btn" onclick="deleteSelected()" style="display:none;">
            <i class="fas fa-trash me-2"></i>Hapus Terpilih (<span id="delete-count">0</span>)
        </button>
    </div>
</div>



<!-- Grouped Tables by Assignment Status -->
<div class="mb-4 mt-4">
    <!-- Unassigned Students Table -->
    <div class="card border-warning">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i>
                Belum Di-Assign Guru Pembimbing
                <span class="badge bg-light text-dark ms-2">
                    <?php echo isset($grouped_siswa['unassigned']) ? count($grouped_siswa['unassigned']['siswa']) : 0; ?> Siswa
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="3%">
                                <input type="checkbox" id="select-all-unassigned" onchange="toggleSelectAll('unassigned')">
                            </th>
                            <th width="5%">#</th>
                            <th>Nama Siswa</th>
                            <th>NIS/NISN</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Periode</th>
                            <th width="25%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(isset($grouped_siswa['unassigned']) && !empty($grouped_siswa['unassigned']['siswa'])):
                            $no = 1;
                            foreach($grouped_siswa['unassigned']['siswa'] as $show){
                        ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="siswa-checkbox group-unassigned" value="<?php echo $show->siswa_code; ?>" data-siswa-code="<?php echo $show->siswa_code; ?>">
                            </td>
                            <td><?php echo $no++ ?></td>
                            <td><strong><?php echo $show->siswa_nama ?></strong></td>
                            <td>
                                <?php if($show->siswa_nis): ?>
                                    <div>NIS: <?php echo $show->siswa_nis ?></div>
                                <?php endif; ?>
                                <?php if($show->siswa_nisn): ?>
                                    <div>NISN: <?php echo $show->siswa_nisn ?></div>
                                <?php endif; ?>
                                <?php if(!$show->siswa_nis && !$show->siswa_nisn): ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo $show->siswa_kelas ?></td>
                            <td><?php echo $show->siswa_jurusan ? $show->siswa_jurusan : '-' ?></td>
                            <td>
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
                            <td>
                                <?php echo $show->periode ? $show->periode : '-' ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            onclick="openAssignModalSingle('<?php echo $show->siswa_code; ?>', '<?php echo addslashes($show->siswa_nama); ?>')" 
                                            title="Assign Guru Pembimbing">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-single-btn" 
                                            onclick="deleteSingle('<?php echo $show->siswa_code; ?>')" 
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        else:
                        ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                                Semua siswa telah di-assign guru pembimbing
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Students Table -->
<div class="mb-4 mt-4">
    <!-- Assigned Students Table -->
    <div class="card border-success">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-check me-2"></i>
                Sudah Di-Assign Guru Pembimbing
                <span class="badge bg-light text-dark ms-2">
                    <?php echo isset($grouped_siswa['assigned']) ? count($grouped_siswa['assigned']['siswa']) : 0; ?> Siswa
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="3%">
                                <input type="checkbox" id="select-all-assigned" onchange="toggleSelectAll('assigned')">
                            </th>
                            <th width="5%">#</th>
                            <th>Nama Siswa</th>
                            <th>NIS/NISN</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Periode</th>
                            <th>Guru Pembimbing</th>
                            <th width="25%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(isset($grouped_siswa['assigned']) && !empty($grouped_siswa['assigned']['siswa'])):
                            $no = 1;
                            foreach($grouped_siswa['assigned']['siswa'] as $show){
                        ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="siswa-checkbox group-assigned" value="<?php echo $show->siswa_code; ?>" data-siswa-code="<?php echo $show->siswa_code; ?>">
                            </td>
                            <td><?php echo $no++ ?></td>
                            <td><strong><?php echo $show->siswa_nama ?></strong></td>
                            <td>
                                <?php if($show->siswa_nis): ?>
                                    <div>NIS: <?php echo $show->siswa_nis ?></div>
                                <?php endif; ?>
                                <?php if($show->siswa_nisn): ?>
                                    <div>NISN: <?php echo $show->siswa_nisn ?></div>
                                <?php endif; ?>
                                <?php if(!$show->siswa_nis && !$show->siswa_nisn): ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo $show->siswa_kelas ?></td>
                            <td><?php echo $show->siswa_jurusan ? $show->siswa_jurusan : '-' ?></td>
                            <td>
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
                            <td>
                                <?php echo $show->periode ? $show->periode : '-' ?>
                            </td>
                            <td>
                                <?php 
                                // Use pembimbing name from main query
                                echo $show->pembimbing_nama ? $show->pembimbing_nama : '-';
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo site_url('hubin/unassign_pembimbing/'.$show->siswa_code) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin unassign guru pembimbing dari siswa ini?')" 
                                       class="btn btn-sm btn-warning" title="Unassign Guru Pembimbing">
                                        <i class="fas fa-user-minus"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-single-btn" 
                                            onclick="deleteSingle('<?php echo $show->siswa_code; ?>')" 
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        else:
                        ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada siswa yang di-assign guru pembimbing
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
// Toggle select all for each group
function toggleSelectAll(groupId) {
    const selectAll = document.getElementById('select-all-' + groupId);
    const checkboxes = document.querySelectorAll('.group-' + groupId);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateActionButton();
}

// Update button visibility and count
function updateActionButton() {
    const checkboxes = document.querySelectorAll('.siswa-checkbox:checked');
    const count = checkboxes.length;
    const assignBtn = document.getElementById('assign-selected-btn');
    const deleteBtn = document.getElementById('delete-selected-btn');
    const deleteButtons = document.querySelectorAll('.delete-single-btn');
    
    if (count > 0) {
        // Show batch buttons, hide individual delete buttons
        assignBtn.style.display = 'block';
        deleteBtn.style.display = 'block';
        
        // Hide all individual delete buttons
        deleteButtons.forEach(btn => {
            btn.style.display = 'none';
        });
    } else {
        // Hide batch buttons, show individual delete buttons
        assignBtn.style.display = 'none';
        deleteBtn.style.display = 'none';
        
        // Show all individual delete buttons
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }
}

// Delete single student
function deleteSingle(siswaCode) {
    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
        window.location.href = '<?php echo base_url("hubin/hapus_siswa/") ?>' + siswaCode;
    }
}

// Delete selected students
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.siswa-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih siswa yang akan dihapus!');
        return;
    }
    
    const siswaCodes = Array.from(checkboxes).map(cb => cb.getAttribute('data-siswa-code'));
    const count = siswaCodes.length;
    
    if (confirm('Apakah Anda yakin ingin menghapus ' + count + ' siswa terpilih?\nTindakan ini tidak dapat dibatalkan!')) {
        // Delete students one by one using the existing hapus_siswa endpoint
        let deletedCount = 0;
        let failedCount = 0;
        
        // Use Promise.all to handle multiple deletions
        const deletePromises = siswaCodes.map(code => {
            return fetch('<?php echo base_url("hubin/hapus_siswa") ?>/' + code, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({siswa_code: code})
            })
            .then(response => response.ok ? deletedCount++ : failedCount++)
            .catch(error => {
                console.error('Error deleting siswa:', error);
                failedCount++;
            });
        });
        
        Promise.all(deletePromises).then(() => {
            let message = 'Berhasil menghapus ' + deletedCount + ' siswa';
            if (failedCount > 0) {
                message += ' (' + failedCount + ' gagal dihapus)';
            }
            alert(message);
            window.location.reload();
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Individual checkbox
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            // Update group checkbox state
            const groupId = this.className.match(/group-(\w+)/);
            if(groupId) {
                const groupCb = document.querySelector('#select-all-' + groupId[1]);
                if(groupCb) {
                    const groupSiswa = document.querySelectorAll('.group-' + groupId[1]);
                    groupCb.checked = Array.from(groupSiswa).every(c => c.checked);
                }
            }
        });
    });
});

// Assign Modal Functions
let currentSiswaCode = null;
let currentSiswaName = null;

// Open modal for single student assignment
function openAssignModalSingle(siswaCode, siswaName) {
    currentSiswaCode = siswaCode;
    currentSiswaName = siswaName;
    
    // Update modal title
    document.getElementById('assignModalLabel').innerHTML = 'Assign Guru Pembimbing untuk <strong>' + siswaName + '</strong>';
    
    // Load available pembimbing
    loadAvailablePembimbing();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
    modal.show();
}

// Open modal for batch assignment
function openAssignModal() {
    const checkboxes = document.querySelectorAll('.siswa-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Pilih minimal 1 siswa untuk di-assign!');
        return;
    }
    
    currentSiswaCode = Array.from(checkboxes).map(cb => cb.getAttribute('data-siswa-code')).join(',');
    currentSiswaName = checkboxes.length + ' siswa terpilih';
    
    // Update modal title
    document.getElementById('assignModalLabel').innerHTML = 'Assign Guru Pembimbing untuk <strong>' + currentSiswaName + '</strong>';
    
    // Load available pembimbing
    loadAvailablePembimbing();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
    modal.show();
}

function loadAvailablePembimbing() {
    fetch('<?php echo base_url("hubin/get_available_pembimbing") ?>')
        .then(response => response.json())
        .then(data => {
            const pembimbingList = document.getElementById('pembimbingList');
            
            if (data.success && data.pembimbing && data.pembimbing.length > 0) {
                let html = '';
                data.pembimbing.forEach((pembimbing, index) => {
                    const maxSlots = 10;
                    const availableSlots = maxSlots - (parseInt(pembimbing.jumlah_siswa) || 0);
                    
                    // All pembimbing can be assigned (no active/inactive status)
                    const canSelect = availableSlots > 0;
                    const slotsInfo = availableSlots > 0 
                        ? `<span class="badge bg-primary ms-2">${availableSlots} slot tersedia</span>`
                        : `<span class="badge bg-danger ms-2">Penuh</span>`;
                    
                    html += `
                        <div class="form-check border p-2 mb-2 rounded ${!canSelect ? 'text-muted' : ''}" style="${!canSelect ? 'opacity: 0.6;' : ''}">
                            <input class="form-check-input pembimbing-radio" type="radio" name="pembimbing_id" value="${pembimbing.pembimbing_id}" id="pembimbing_${pembimbing.pembimbing_id}" ${!canSelect ? 'disabled' : ''}>
                            <label class="form-check-label w-100 ${!canSelect ? 'text-muted' : ''}" for="pembimbing_${pembimbing.pembimbing_id}">
                                <strong>${pembimbing.pembimbing_nama}</strong>
                                ${slotsInfo}<br>
                                <small class="text-muted">
                                    ${pembimbing.pembimbing_nip ? 'NIP: ' + pembimbing.pembimbing_nip : '-'} | 
                                    ${pembimbing.tempat_tugas || 'Tempat tugas tidak tersedia'} | 
                                    Siswa Bimbingan: ${pembimbing.jumlah_siswa || 0}/${maxSlots}
                                </small>
                            </label>
                        </div>
                    `;
                });
                pembimbingList.innerHTML = html;
            } else {
                const message = data.message || 'Tidak ada guru pembimbing tersedia';
                pembimbingList.innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        <strong>${message}</strong><br>
                        <small>
                            Untuk menambahkan pembimbing:<br>
                            1. Buka menu <strong>Data Pembimbing</strong><br>
                            2. Klik tombol <strong>Tambah Pembimbing</strong><br>
                            3. Isi data dan pastikan status kepegawaian "Aktif"
                        </small>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading pembimbing:', error);
            document.getElementById('pembimbingList').innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                    <strong>Gagal memuat data pembimbing</strong><br>
                    <small>Silakan coba lagi atau periksa koneksi Anda</small>
                </div>
            `;
        });
}

function confirmAssign() {
    const selectedPembimbing = document.querySelector('.pembimbing-radio:checked');
    
    if (!selectedPembimbing) {
        alert('Pilih guru pembimbing terlebih dahulu!');
        return;
    }
    
    // Check if pembimbing has available slots
    if (selectedPembimbing.disabled) {
        alert('Guru pembimbing ini telah mencapai batas maksimum siswa. Pilih pembimbing lain.');
        return;
    }
    
    const pembimbingId = selectedPembimbing.value;
    
    // Send AJAX request to assign pembimbing (no confirmation dialog)
    fetch('<?php echo base_url("hubin/assign_pembimbing") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'pembimbing_id': pembimbingId,
            'siswa_codes': currentSiswaCode
        })
    })
    .then(response => response.text())
    .then(data => {
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('assignModal'));
        modal.hide();
        
        // Reload page to show updated data
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat assign guru pembimbing. Silakan coba lagi.');
    });
}
</script>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Assign Guru Pembimbing
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-user-tie me-2"></i>Daftar Guru Pembimbing Tersedia
                    </label>
                    <div id="pembimbingList" style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat data pembimbing...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmAssign()">
                    <i class="fas fa-check me-2"></i>Assign Guru Pembimbing
                </button>
            </div>
        </div>
    </div>
</div>

