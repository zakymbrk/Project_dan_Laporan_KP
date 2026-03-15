<?php
$userdata = $this->session->userdata('userdata');

// Check if student has a pembimbing assigned
if (!$pembimbing) {
    // Show message that student doesn't have a pembimbing yet
    ?>
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('siswa/view') ?>"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Kontak Pembimbing</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-address-book me-2"></i>Kontak Pembimbing</h2>
        </div>

        <!-- Alert for no pembimbing -->
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Belum memiliki guru pembimbing!</strong> Anda belum memiliki guru pembimbing PKL yang ditugaskan. Silakan hubungi Hubin untuk informasi lebih lanjut.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Info Card -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-user-graduate fa-3x text-muted"></i>
                </div>
                <h4 class="card-title">Status Pembimbing</h4>
                <p class="card-text text-muted">
                    Saat ini Anda belum memiliki guru pembimbing yang ditugaskan. 
                    Guru pembimbing akan ditugaskan setelah pengajuan PKL Anda disetujui.
                </p>
                <a href="<?php echo base_url('siswa/view/pengajuan') ?>" class="btn btn-primary">
                    <i class="fas fa-file-alt me-2"></i>Cek Status Pengajuan
                </a>
            </div>
        </div>
    </div>
    <?php
} else {
    // Show pembimbing contact information
    ?>
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('siswa/view') ?>"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Kontak Pembimbing</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-address-book me-2"></i>Kontak Pembimbing</h2>
        </div>

        <!-- Pembimbing Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Detail Informasi Pembimbing
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                                    <tr>
                                        <td width="30%" class="fw-bold bg-light">Nama Lengkap</td>
                                        <td><?php echo isset($pembimbing->pembimbing_nama) && $pembimbing->pembimbing_nama ? $pembimbing->pembimbing_nama : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">NIP</td>
                                        <td><?php echo isset($pembimbing->pembimbing_nip) && !empty($pembimbing->pembimbing_nip) ? $pembimbing->pembimbing_nip : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Tempat Tugas</td>
                                        <td><?php echo isset($pembimbing->tempat_tugas) && !empty($pembimbing->tempat_tugas) ? $pembimbing->tempat_tugas : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Alamat</td>
                                        <td>
                                            <?php if(isset($pembimbing->alamat) && !empty($pembimbing->alamat)): ?>
                                                <?php echo nl2br(htmlspecialchars($pembimbing->alamat)) ?>
                                            <?php elseif(isset($pembimbing->pembimbing_alamat) && !empty($pembimbing->pembimbing_alamat)): ?>
                                                <?php echo nl2br(htmlspecialchars($pembimbing->pembimbing_alamat)) ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Tempat Lahir</td>
                                        <td><?php echo isset($pembimbing->tempat_lahir) && !empty($pembimbing->tempat_lahir) ? $pembimbing->tempat_lahir : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Tanggal Lahir</td>
                                        <td><?php echo isset($pembimbing->tanggal_lahir) && !empty($pembimbing->tanggal_lahir) ? date('d F Y', strtotime($pembimbing->tanggal_lahir)) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Jenis Kelamin</td>
                                        <td>
                                            <?php 
                                            if(isset($pembimbing->jenis_kelamin) && !empty($pembimbing->jenis_kelamin)) {
                                                // Handle both old format ('L'/'P') and new format ('Laki-laki'/'Perempuan')
                                                if ($pembimbing->jenis_kelamin == 'L' || $pembimbing->jenis_kelamin == 'Laki-laki') {
                                                    echo 'Laki-laki';
                                                } elseif ($pembimbing->jenis_kelamin == 'P' || $pembimbing->jenis_kelamin == 'Perempuan') {
                                                    echo 'Perempuan';
                                                } else {
                                                    echo $pembimbing->jenis_kelamin; // Display as-is if it's something else
                                                }
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Pendidikan Terakhir</td>
                                        <td><?php echo isset($pembimbing->pendidikan_terakhir) && !empty($pembimbing->pendidikan_terakhir) ? $pembimbing->pendidikan_terakhir : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Jurusan Keahlian</td>
                                        <td><?php echo isset($pembimbing->jurusan_keahlian) && !empty($pembimbing->jurusan_keahlian) ? $pembimbing->jurusan_keahlian : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Jabatan</td>
                                        <td><?php echo isset($pembimbing->jabatan) && !empty($pembimbing->jabatan) ? $pembimbing->jabatan : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Tahun Masuk</td>
                                        <td><?php echo isset($pembimbing->tahun_masuk) && !empty($pembimbing->tahun_masuk) ? $pembimbing->tahun_masuk : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Status Kepegawaian</td>
                                        <td>
                                            <?php 
                                            if(isset($pembimbing->status_kepegawaian) && !empty($pembimbing->status_kepegawaian)) {
                                                $status_labels = [
                                                    'PNS' => 'Pegawai Negeri Sipil',
                                                    'CPNS' => 'Calon Pegawai Negeri Sipil',
                                                    'Honorer' => 'Honorer',
                                                    'Kontrak' => 'Kontrak'
                                                ];
                                                echo isset($status_labels[$pembimbing->status_kepegawaian]) ? $status_labels[$pembimbing->status_kepegawaian] : $pembimbing->status_kepegawaian;
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Email</td>
                                        <td>
                                            <?php if(isset($pembimbing->pembimbing_email) && !empty($pembimbing->pembimbing_email)): ?>
                                                <a href="mailto:<?php echo $pembimbing->pembimbing_email ?>"><?php echo $pembimbing->pembimbing_email ?></a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Telepon</td>
                                        <td>
                                            <?php if(isset($pembimbing->pembimbing_telepon) && !empty($pembimbing->pembimbing_telepon)): ?>
                                                <a href="tel:<?php echo $pembimbing->pembimbing_telepon ?>"><?php echo $pembimbing->pembimbing_telepon ?></a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Jumlah Siswa Bimbingan</td>
                                        <td>
                                            <?php 
                                            // Count students assigned to this pembimbing
                                            $this->db->where('pembimbing_id', $pembimbing->pembimbing_id);
                                            $jumlah_siswa = $this->db->count_all_results('tb_pengelompokan');
                                            echo $jumlah_siswa . ' Siswa';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Status Ketersediaan</td>
                                        <td>
                                            <?php 
                                            $this->db->where('pembimbing_id', $pembimbing->pembimbing_id);
                                            $jumlah_siswa = $this->db->count_all_results('tb_pengelompokan');
                                            if($jumlah_siswa >= 20) {
                                                echo '<span class="badge bg-success">Penuh</span>';
                                            } elseif($jumlah_siswa > 0) {
                                                echo '<span class="badge bg-warning">Tersedia</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Kosong</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contact Actions -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Kontak</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <?php 
                    $contact_phone = isset($pembimbing->pembimbing_telepon) && $pembimbing->pembimbing_telepon ? $pembimbing->pembimbing_telepon : '';
                    if ($contact_phone): 
                    ?>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $contact_phone) ?>" class="btn btn-success btn-lg w-100" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php 
                    $contact_email = isset($pembimbing->pembimbing_email) && $pembimbing->pembimbing_email ? $pembimbing->pembimbing_email : '';
                    if ($contact_email): 
                    ?>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <a href="mailto:<?php echo $contact_email ?>" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-envelope me-2"></i>Email
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <!-- <div class="col-md-4">
                        <button type="button" class="btn btn-info btn-lg w-100" data-bs-toggle="modal" data-bs-target="#chatModal">
                            <i class="fas fa-comments me-2"></i>Pesan
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Pesan ke Guru Pembimbing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda kepada pembimbing..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Fitur pesan langsung akan segera tersedia. Untuk saat ini, silakan hubungi pembimbing melalui telepon atau email.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" disabled>Kirim Pesan</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>