<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/assign-pembimbing') ?>">Assign Pembimbing</a></li>
        <li class="breadcrumb-item active">Detail Siswa</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Detail Siswa</h2>
</div>

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
                        <td class="fw-bold bg-light">NIS / Nomor Induk Siswa</td>
                        <td><?php echo $siswa->siswa_nis ? $siswa->siswa_nis : '-' ?></td>
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
                        <td><?php echo isset($siswa->telepon) ? $siswa->telepon : $siswa->siswa_telepon ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Alamat</td>
                        <td><?php echo isset($siswa->alamat) ? $siswa->alamat : (isset($siswa->siswa_alamat) ? $siswa->siswa_alamat : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Email</td>
                        <td><?php echo isset($siswa->email) ? $siswa->email : '-' ?></td>
                    </tr>
                    <?php 
                    $dudi = null;
                    if($siswa->dudi_id){
                        $this->db->where('dudi_id', $siswa->dudi_id);
                        $dudi = $this->db->get('tb_dudi')->row();
                    }
                    ?>
                    <tr>
                        <td class="fw-bold bg-light">Perusahaan (DUDI)</td>
                        <td><?php echo $dudi ? $dudi->dudi_nama : '-' ?></td>
                    </tr>
                    <?php if($dudi){ ?>
                    <tr>
                        <td class="fw-bold bg-light">Status Mitra</td>
                        <td>
                            <?php if($dudi->is_mitra == 1): ?>
                                <span class="badge bg-success">Mitra</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Non-Mitra</span>
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
                        <td class="fw-bold bg-light">Email Perusahaan</td>
                        <td><?php echo $dudi->dudi_email ? $dudi->dudi_email : '-' ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="fw-bold bg-light">Status Pengajuan</td>
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
                        <td class="fw-bold bg-light">Periode PKL</td>
                        <td><?php echo $siswa->periode ?></td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

        <!-- Print Area for table content only -->
        <div id="printArea" class="d-none d-print-block">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="fw-bold">Nama Siswa</td>
                            <td><?php echo $siswa->siswa_nama ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">NIS / Nomor Induk Siswa</td>
                            <td><?php echo $siswa->siswa_nis ? $siswa->siswa_nis : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kelas</td>
                            <td><?php echo $siswa->siswa_kelas ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jurusan</td>
                            <td><?php echo $siswa->siswa_jurusan ? $siswa->siswa_jurusan : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Telepon</td>
                            <td><?php echo isset($siswa->telepon) ? $siswa->telepon : $siswa->siswa_telepon ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Alamat</td>
                            <td><?php echo isset($siswa->alamat) ? $siswa->alamat : (isset($siswa->siswa_alamat) ? $siswa->siswa_alamat : '-') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email</td>
                            <td><?php echo isset($siswa->email) ? $siswa->email : '-' ?></td>
                        </tr>
                        <?php 
                        $dudi = null;
                        if($siswa->dudi_id){
                            $this->db->where('dudi_id', $siswa->dudi_id);
                            $dudi = $this->db->get('tb_dudi')->row();
                        }
                        ?>
                        <tr>
                            <td class="fw-bold">Perusahaan (DUDI)</td>
                            <td><?php echo $dudi ? $dudi->dudi_nama : '-' ?></td>
                        </tr>
                        <?php if($dudi){ ?>
                        <tr>
                            <td class="fw-bold">Status Mitra</td>
                            <td>
                                <?php if($dudi->is_mitra == 1): ?>
                                    <span class=""><?php echo $dudi->dudi_nama ?></span>
                                <?php else: ?>
                                    <span class=""><?php echo $dudi->dudi_nama ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Alamat Perusahaan</td>
                            <td><?php echo $dudi->dudi_alamat ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Telepon Perusahaan</td>
                            <td><?php echo $dudi->dudi_telepon ? $dudi->dudi_telepon : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email Perusahaan</td>
                            <td><?php echo $dudi->dudi_email ? $dudi->dudi_email : '-' ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="fw-bold">Status Pengajuan</td>
                            <td>
                                <?php echo ucfirst($siswa->status_pengajuan); ?>
                            </td>
                        </tr>
                        <?php if($siswa->periode){ ?>
                        <tr>
                            <td class="fw-bold">Periode PKL</td>
                            <td><?php echo $siswa->periode ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex flex-wrap gap-3 justify-content-between">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo base_url('hubin/view/id-card-siswa/' . $siswa->siswa_id) ?>" class="btn btn-success">
                    <i class="fas fa-id-card"></i>
                    <span>Cetak ID Card</span>
                </a>
                <button type="button" onclick="printDiv('printArea');" class="btn btn-primary">
                    <i class="fas fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>
</div>