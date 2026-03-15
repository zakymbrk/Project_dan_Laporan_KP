<?php 
$search = $this->input->get('dudi_nama') ? $this->input->get('dudi_nama') : '';

// Stats dan data sudah dimuat di controller
// $stats, $all_dudi_with_student_data, $dudi_mitra, $dudi_non_mitra
// sudah tersedia dari controller

// Use the synchronized data from controller
$all_dudi_data = isset($all_dudi_with_student_data) ? $all_dudi_with_student_data : array();

// Filter data based on search
if($search) {
    $filtered_data = array();
    foreach($all_dudi_data as $dudi) {
        if(stripos($dudi->dudi_nama, $search) !== false) {
            $filtered_data[] = $dudi;
        }
    }
    $all_dudi_data = $filtered_data;
}

// Pagination
$total_rows = count($all_dudi_data);
$config['base_url'] = base_url('hubin/view/data-dudi');
$config['total_rows'] = $total_rows;
$config['per_page'] = 10;
$config['uri_segment'] = 4;
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = TRUE;
$config['query_string_segment'] = 'page';
$config['reuse_query_string'] = TRUE;

$this->pagination->initialize($config);
$page = ($this->input->get('page')) ? $this->input->get('page') : 1;
$offset = ($page - 1) * $config['per_page'];

// Apply pagination to the data
$getdudi_data = array_slice($all_dudi_data, $offset, $config['per_page']);

// Get data untuk tab-tab
$dudi_mitra = $this->M_dudi->get_dudi_mitra();
$dudi_non_mitra = $this->M_dudi->get_dudi_non_mitra();
// $dudi_pengajuan = $this->M_dudi->get_dudi_pengajuan(); // Removed
// $dudi_siswa = $this->M_dudi->get_dudi_siswa(); // Removed
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Data DUDI</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-building me-2"></i>Data DUDI Terpadu</h2>
</div>



<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Action Bar -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <!-- Left: Tambah DUDI -->
    <div>
        <a href="<?php echo base_url('hubin/view/tambah-dudi') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah DUDI
        </a>
    </div>
    
    <!-- Center: Export and Search -->
    <div class="d-flex gap-2 flex-grow-1 justify-content-center" style="max-width: 900px;">
        <!-- Export Button -->
        <a href="<?php echo base_url('export_excel/export_data_dudi') ?>" class="btn btn-success" id="export-all-btn">
            <i class="fas fa-file-excel me-2"></i>Export Data
        </a>
        <a href="#" class="btn btn-success" id="export-selected-btn" onclick="exportSelected(event)" style="display:none;">
            <i class="fas fa-file-excel me-2"></i>Export Terpilih (<span id="export-count">0</span>)
        </a>
                    
        <!-- Search Form -->
        <form action="" method="get" class="d-flex flex-grow-1">
            <div class="input-group w-100">
                <input type="search" value="<?php echo $search ?>" name="dudi_nama" 
                       placeholder="Cari nama DUDI (mitra/non-mitra)..." class="form-control" required>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Right: Delete Selected -->
    <div>
        <button type="button" class="btn btn-danger" id="delete-selected-btn" onclick="deleteSelected()" style="display:none;">
            <i class="fas fa-trash me-2"></i>Hapus Terpilih (<span id="delete-count">0</span>)
        </button>
    </div>
</div>

<!-- DUDI Mitra Table -->
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Daftar DUDI Mitra</h5>
    </div>
    <div class="card-body">
        <form id="form_mitra" method="post">
            <input type="hidden" name="status_filter" value="mitra">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="table_mitra">
                    <thead class="table-success">
                        <tr>
                            <th width="5%" class="text-center">
                                <input type="checkbox" id="select-all-mitra" onchange="toggleSelectAll('mitra')">
                            </th>
                            <th width="5%">No</th>
                            <th>Nama DUDI</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>PIC</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if($dudi_mitra->num_rows() > 0):
                            foreach($dudi_mitra->result() as $show): 
                        ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="<?php echo $show->dudi_id; ?>" data-dudi-code="<?php echo $show->dudi_code; ?>" data-table="mitra">
                            </td>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($show->dudi_nama) ?></strong></td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_alamat') && $show->sumber_data == 'siswa' && isset($show->display_alamat) && !empty($show->display_alamat)) {
                                    echo htmlspecialchars(substr($show->display_alamat, 0, 50)) . (strlen($show->display_alamat) > 50 ? '...' : '');
                                } else {
                                    echo htmlspecialchars(substr($show->dudi_alamat, 0, 50)) . (strlen($show->dudi_alamat) > 50 ? '...' : '');
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_telepon') && $show->sumber_data == 'siswa' && isset($show->display_telepon) && !empty($show->display_telepon)) {
                                    echo htmlspecialchars($show->display_telepon);
                                } else {
                                    echo !empty($show->dudi_telepon) ? htmlspecialchars($show->dudi_telepon) : '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_pic') && $show->sumber_data == 'siswa' && isset($show->display_pic) && !empty($show->display_pic)) {
                                    echo htmlspecialchars($show->display_pic);
                                } else {
                                    echo !empty($show->dudi_pic) ? htmlspecialchars($show->dudi_pic) : '-';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo base_url('hubin/detail_dudi/' . $show->dudi_code) ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-single-btn" 
                                            onclick="deleteSingle('<?php echo $show->dudi_code; ?>')" 
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
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Tidak ada data DUDI mitra
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- DUDI Non-Mitra Table -->
<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-building me-2"></i>Daftar DUDI Non-Mitra</h5>
    </div>
    <div class="card-body">
        <form id="form_non_mitra" method="post">
            <input type="hidden" name="status_filter" value="non_mitra">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="table_non_mitra">
                    <thead class="table-info">
                        <tr>
                            <th width="5%" class="text-center">
                                <input type="checkbox" id="select-all-non-mitra" onchange="toggleSelectAll('non_mitra')">
                            </th>
                            <th width="5%">No</th>
                            <th>Nama DUDI</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>PIC</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if($dudi_non_mitra->num_rows() > 0):
                            foreach($dudi_non_mitra->result() as $show): 
                        ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="<?php echo $show->dudi_id; ?>" data-dudi-code="<?php echo $show->dudi_code; ?>" data-table="non_mitra">
                            </td>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($show->dudi_nama) ?></strong></td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_alamat') && $show->sumber_data == 'siswa' && isset($show->display_alamat) && !empty($show->display_alamat)) {
                                    echo htmlspecialchars(substr($show->display_alamat, 0, 50)) . (strlen($show->display_alamat) > 50 ? '...' : '');
                                } else {
                                    echo htmlspecialchars(substr($show->dudi_alamat, 0, 50)) . (strlen($show->dudi_alamat) > 50 ? '...' : '');
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_telepon') && $show->sumber_data == 'siswa' && isset($show->display_telepon) && !empty($show->display_telepon)) {
                                    echo htmlspecialchars($show->display_telepon);
                                } else {
                                    echo !empty($show->dudi_telepon) ? htmlspecialchars($show->dudi_telepon) : '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(property_exists($show, 'display_pic') && $show->sumber_data == 'siswa' && isset($show->display_pic) && !empty($show->display_pic)) {
                                    echo htmlspecialchars($show->display_pic);
                                } else {
                                    echo !empty($show->dudi_pic) ? htmlspecialchars($show->dudi_pic) : '-';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo base_url('hubin/detail_dudi/' . $show->dudi_code) ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-single-btn" 
                                            onclick="deleteSingle('<?php echo $show->dudi_code; ?>')" 
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
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Tidak ada data DUDI non-mitra
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>



<!-- Rekomendasi Siswa Tab -->

<script>
// Select all checkboxes for mitra table
function toggleSelectAll(table) {
    const selectAll = document.getElementById('select-all-' + table);
    const checkboxes = document.querySelectorAll('.row-checkbox[data-table="' + table + '"]');
    
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
    const deleteCount = document.getElementById('delete-count');
    const deleteButtons = document.querySelectorAll('.delete-single-btn');
    const exportAllBtn = document.getElementById('export-all-btn');
    const exportSelectedBtn = document.getElementById('export-selected-btn');
    const exportCount = document.getElementById('export-count');
    
    if (count > 0) {
        // Show batch delete button, hide individual delete buttons
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
        // Hide batch delete button, show individual delete buttons
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

// Delete single DUDI
function deleteSingle(dudiCode) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        // Use AJAX to delete single DUDI
        fetch('<?php echo base_url("hubin/hapus_dudi/") ?>' + dudiCode, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Data berhasil dihapus');
                window.location.reload();
            } else {
                alert('Gagal menghapus data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback to traditional method
            window.location.href = '<?php echo base_url("hubin/hapus_dudi/") ?>' + dudiCode;
        });
    }
}

// Delete selected DUDI
function deleteSelected() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih DUDI yang akan dihapus!');
        return;
    }
    
    const dudiCodes = Array.from(checkboxes).map(cb => cb.getAttribute('data-dudi-code'));
    const count = dudiCodes.length;
    
    if (confirm('Apakah Anda yakin ingin menghapus ' + count + ' DUDI terpilih?\nTindakan ini tidak dapat dibatalkan!')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("hubin/batch_delete_dudi"); ?>';
        
        dudiCodes.forEach(code => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_dudi[]';
            input.value = code;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Export selected DUDI
function exportSelected(event) {
    event.preventDefault();
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih DUDI yang akan diekspor!');
        return;
    }
    
    const dudiIds = Array.from(checkboxes).map(cb => cb.value);
    const count = dudiIds.length;
    
    // Redirect to export with selected IDs
    window.location.href = '<?php echo base_url("export_excel/export_data_dudi") ?>?selected=' + encodeURIComponent(dudiIds.join(','));
}

// Add event listeners to row checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateActionButton);
    });
});
</script>
