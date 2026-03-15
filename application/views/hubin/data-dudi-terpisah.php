<?php 
// Load data from controller
$dudi_mitra = isset($dudi_mitra) ? $dudi_mitra : array();
$dudi_non_mitra = isset($dudi_non_mitra) ? $dudi_non_mitra : array();
$dudi_pengajuan = isset($dudi_pengajuan) ? $dudi_pengajuan : array();
$dudi_siswa = isset($dudi_siswa) ? $dudi_siswa : array();
$stats = isset($stats) ? $stats : array();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Data DUDI Terpisah</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-building me-2"></i>Data DUDI Terpisah</h2>
</div>

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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Mitra</h5>
                        <h2><?php echo isset($stats['mitra']) ? $stats['mitra'] : 0; ?></h2>
                    </div>
                    <i class="fas fa-handshake fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Non-Mitra</h5>
                        <h2><?php echo isset($stats['non_mitra']) ? $stats['non_mitra'] : 0; ?></h2>
                    </div>
                    <i class="fas fa-building fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Pengajuan</h5>
                        <h2><?php echo isset($stats['pengajuan']) ? $stats['pengajuan'] : 0; ?></h2>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total</h5>
                        <h2><?php echo isset($stats['total']) ? $stats['total'] : 0; ?></h2>
                    </div>
                    <i class="fas fa-list fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs" id="dudiTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="mitra-tab" data-bs-toggle="tab" data-bs-target="#mitra" type="button" role="tab">
            <i class="fas fa-handshake"></i> DUDI Mitra
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="non-mitra-tab" data-bs-toggle="tab" data-bs-target="#non-mitra" type="button" role="tab">
            <i class="fas fa-building"></i> DUDI Non-Mitra
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pengajuan-tab" data-bs-toggle="tab" data-bs-target="#pengajuan" type="button" role="tab">
            <i class="fas fa-clock"></i> DUDI Pengajuan
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="dudiTabContent">
    
    <!-- DUDI Mitra Tab -->
    <div class="tab-pane fade show active" id="mitra" role="tabpanel" aria-labelledby="mitra-tab">
        <div class="card shadow mt-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Daftar DUDI Mitra</h5>
            </div>
            <div class="card-body">
                <?php if($dudi_mitra && $dudi_mitra->num_rows() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama DUDI</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>PIC</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($dudi_mitra->result() as $dudi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($dudi->dudi_nama); ?></td>
                                    <td><?php echo htmlspecialchars(substr($dudi->dudi_alamat, 0, 50)) . (strlen($dudi->dudi_alamat) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo !empty($dudi->dudi_telepon) ? htmlspecialchars($dudi->dudi_telepon) : '-'; ?></td>
                                    <td><?php echo !empty($dudi->dudi_pic) ? htmlspecialchars($dudi->dudi_pic) : '-'; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('hubin/detail_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-info me-1" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/edit_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/ubah_status_dudi/' . $dudi->dudi_code . '/non_mitra'); ?>" class="btn btn-sm btn-secondary" title="Jadikan Non-Mitra"
                                           onclick="return confirm('Ubah status menjadi non-mitra?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada data DUDI mitra.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- DUDI Non-Mitra Tab -->
    <div class="tab-pane fade" id="non-mitra" role="tabpanel" aria-labelledby="non-mitra-tab">
        <div class="card shadow mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Daftar DUDI Non-Mitra</h5>
            </div>
            <div class="card-body">
                <?php if($dudi_non_mitra && $dudi_non_mitra->num_rows() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama DUDI</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>PIC</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($dudi_non_mitra->result() as $dudi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($dudi->dudi_nama); ?></td>
                                    <td>
                                        <?php 
                                        // Check if we have student-filled data for alamat
                                        if($dudi->sumber_data == 'siswa' && $dudi->display_alamat !== NULL) {
                                            echo htmlspecialchars(substr($dudi->display_alamat, 0, 50)) . (strlen($dudi->display_alamat) > 50 ? '...' : '');
                                        } elseif($dudi->sumber_data == 'siswa' && $dudi->display_alamat === NULL) {
                                            echo '<span class="text-warning">Belum diisi oleh siswa</span>';
                                        } else {
                                            echo htmlspecialchars(substr($dudi->dudi_alamat, 0, 50)) . (strlen($dudi->dudi_alamat) > 50 ? '...' : '');
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // Check if we have student-filled data for telepon
                                        if($dudi->sumber_data == 'siswa' && $dudi->display_telepon !== NULL) {
                                            echo htmlspecialchars($dudi->display_telepon);
                                        } elseif($dudi->sumber_data == 'siswa' && $dudi->display_telepon === NULL) {
                                            echo '<span class="text-warning">Belum diisi oleh siswa</span>';
                                        } else {
                                            echo !empty($dudi->dudi_telepon) ? htmlspecialchars($dudi->dudi_telepon) : '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // Check if we have student-filled data for PIC
                                        if($dudi->sumber_data == 'siswa' && $dudi->display_pic !== NULL) {
                                            echo htmlspecialchars($dudi->display_pic);
                                        } elseif($dudi->sumber_data == 'siswa' && $dudi->display_pic === NULL) {
                                            echo '<span class="text-warning">Belum diisi oleh siswa</span>';
                                        } else {
                                            echo !empty($dudi->dudi_pic) ? htmlspecialchars($dudi->dudi_pic) : '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('hubin/detail_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/edit_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/hapus_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus DUDI ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada data DUDI non-mitra.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- DUDI Pengajuan Tab -->
    <div class="tab-pane fade" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan-tab">
        <div class="card shadow mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Daftar DUDI Pengajuan</h5>
            </div>
            <div class="card-body">
                <?php if($dudi_pengajuan && $dudi_pengajuan->num_rows() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama DUDI</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>PIC</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($dudi_pengajuan->result() as $dudi): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($dudi->dudi_nama); ?></td>
                                    <td><?php echo htmlspecialchars(substr($dudi->dudi_alamat, 0, 50)) . (strlen($dudi->dudi_alamat) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo !empty($dudi->dudi_telepon) ? htmlspecialchars($dudi->dudi_telepon) : '-'; ?></td>
                                    <td><?php echo !empty($dudi->dudi_pic) ? htmlspecialchars($dudi->dudi_pic) : '-'; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('hubin/detail_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-info me-1" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/edit_dudi/' . $dudi->dudi_code); ?>" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/ubah_status_dudi/' . $dudi->dudi_code . '/mitra'); ?>" class="btn btn-sm btn-success me-1" title="Setujui"
                                           onclick="return confirm('Setujui dan ubah status menjadi mitra?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="<?php echo base_url('hubin/ubah_status_dudi/' . $dudi->dudi_code . '/non_mitra'); ?>" class="btn btn-sm btn-secondary me-1" title="Tolak"
                                           onclick="return confirm('Tolak dan ubah status menjadi non-mitra?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Belum ada data DUDI pengajuan.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</div>