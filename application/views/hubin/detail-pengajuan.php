<?php
// Models are loaded in controller
// $this->load->model('M_siswa');
// $this->load->model('M_dudi');

// Get siswa data by code
$siswa = null;
$dudi = null;

if(isset($siswa_code)){
    // Get siswa data
    $this->db->where('siswa_code', $siswa_code);
    $this->db->select('tb_siswa.*, tb_user.alamat as alamat, tb_user.telepon as telepon_user, tb_user.email as email_user');
    $this->db->from('tb_siswa');
    $this->db->join('tb_user', 'tb_user.id = tb_siswa.user_id', 'left');
    $siswa = $this->db->get()->row();
    
    if($siswa && $siswa->dudi_id){
        // Get dudi data
        $this->db->where('dudi_id', $siswa->dudi_id);
        $dudi = $this->db->get('tb_dudi')->row();
    }
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/pengajuan') ?>">Pengajuan PKL</a></li>
        <li class="breadcrumb-item active">Detail Pengajuan</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Pengajuan PKL</h2>
</div>

<?php if(!$siswa): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle me-2"></i>
    Data pengajuan tidak ditemukan.
</div>
<?php else: ?>

<div class="row">
    <!-- Siswa Info -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Siswa
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="30%" class="fw-bold bg-light">Nama Siswa</td>
                                <td><?php echo $siswa->siswa_nama ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Kelas</td>
                                <td><?php echo $siswa->siswa_kelas ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Jurusan</td>
                                <td><?php echo $siswa->siswa_jurusan ? $siswa->siswa_jurusan : '-' ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Telepon</td>
                                <td><?php echo isset($siswa->telepon_user) ? $siswa->telepon_user : $siswa->siswa_telepon ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Alamat</td>
                                <td><?php echo isset($siswa->alamat) ? $siswa->alamat : (isset($siswa->siswa_alamat) ? $siswa->siswa_alamat : '-') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pengajuan Info -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Informasi Pengajuan
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width="30%" class="fw-bold bg-light">Status Pengajuan</td>
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
                            </tr>
                            <?php if($siswa->periode){ ?>
                            <tr>
                                <td class="fw-bold bg-light">Periode</td>
                                <td><?php echo $siswa->periode ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($siswa->tanggal_mulai){ ?>
                            <tr>
                                <td class="fw-bold bg-light">Tanggal Mulai</td>
                                <td><?php echo date('d F Y', strtotime($siswa->tanggal_mulai)) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($siswa->tanggal_selesai){ ?>
                            <tr>
                                <td class="fw-bold bg-light">Tanggal Selesai</td>
                                <td><?php echo date('d F Y', strtotime($siswa->tanggal_selesai)) ?></td>
                            </tr>
                            <?php } ?>
                            <?php if($siswa->other_dudi_nama): ?>
                            <tr>
                                <td class="fw-bold bg-light">Perusahaan (DUDI)</td>
                                <td>
                                    <span class="text-primary fw-bold">[PERUSAHAAN AJUKAN SISWA]</span> <?php echo $siswa->other_dudi_nama ?>
                                    <br><small class="text-muted">Perusahaan ini diajukan oleh siswa dan perlu ditambahkan ke sistem</small>
                                </td>
                            </tr>
                            <?php elseif($dudi): ?>
                            <tr>
                                <td class="fw-bold bg-light">Perusahaan (DUDI)</td>
                                <td>
                                    <?php echo $dudi->dudi_nama ?>
                                    <?php if($dudi->is_mitra): ?>
                                        <span class="badge bg-success ms-2">Mitra</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning ms-2">Non-Mitra</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Alamat Perusahaan</td>
                                <td><?php echo $dudi->dudi_alamat ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Telepon Perusahaan</td>
                                <td><?php echo $dudi->dudi_telepon ? $dudi->dudi_telepon : '-' ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold bg-light">Status Perusahaan</td>
                                <td>
                                    <?php if($dudi->is_mitra): ?>
                                        <span class="badge bg-success">Mitra (Rekomendasi Sekolah)</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Non-Mitra (Ajukan Siswa)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Uploaded Files Section -->
                <div class="mt-4">
                    <h6 class="mb-3"><i class="fas fa-paperclip me-2 text-primary"></i>Berkas yang Diajukan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <?php if($siswa->surat_permohonan): ?>
                                <tr>
                                    <td width="30%" class="fw-bold">Surat Permohonan:</td>
                                    <td>
                                        <a href="<?php echo base_url('uploads/pengajuan/'.$siswa->surat_permohonan) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>Lihat Berkas
                                        </a>
                                        <small class="text-muted ms-2">(<?php echo $siswa->surat_permohonan ?>)</small>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td width="30%" class="fw-bold">Surat Permohonan:</td>
                                    <td><span class="text-muted">Tidak ada berkas</span></td>
                                </tr>
                                <?php endif; ?>
                                

                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <?php if($siswa->status_pengajuan == 'menunggu'): ?>
                <div class="mt-3">
                    <a href="<?php echo base_url('hubin/approve_pengajuan/'.$siswa->siswa_code) ?>" 
                       onclick="return confirm('Setujui pengajuan ini?')" 
                       class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Setujui
                    </a>
                    <a href="<?php echo base_url('hubin/tolak_pengajuan/'.$siswa->siswa_code) ?>" 
                       onclick="return confirm('Tolak pengajuan ini?')" 
                       class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Tolak
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>