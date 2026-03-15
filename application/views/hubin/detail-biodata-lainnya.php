<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Detail Biodata User</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user me-2"></i>Detail Biodata User</h2>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>Informasi User
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="30%" class="fw-bold bg-light">Nama Lengkap</td>
                        <td><?php echo $biodata->nama_lengkap ? $biodata->nama_lengkap : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Username</td>
                        <td><?php echo $password_info['username'] ? $password_info['username'] : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Email</td>
                        <td><?php echo $biodata->email ? $biodata->email : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Telepon</td>
                        <td><?php echo $biodata->telepon ? $biodata->telepon : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Alamat</td>
                        <td><?php echo $biodata->alamat ? $biodata->alamat : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Tempat Lahir</td>
                        <td><?php echo $biodata->tempat_lahir ? $biodata->tempat_lahir : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Tanggal Lahir</td>
                        <td><?php echo $biodata->tanggal_lahir ? date('d F Y', strtotime($biodata->tanggal_lahir)) : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Jenis Kelamin</td>
                        <td>
                            <?php 
                            if(isset($biodata->jenis_kelamin) && !empty($biodata->jenis_kelamin)) {
                                // Handle both old format ('L'/'P') and new format ('Laki-laki'/'Perempuan')
                                if ($biodata->jenis_kelamin == 'L' || $biodata->jenis_kelamin == 'Laki-laki') {
                                    echo 'Laki-laki';
                                } elseif ($biodata->jenis_kelamin == 'P' || $biodata->jenis_kelamin == 'Perempuan') {
                                    echo 'Perempuan';
                                } else {
                                    echo $biodata->jenis_kelamin; // Display as-is if it's something else
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Level/User Group</td>
                        <td>
                            <span class="badge bg-primary"><?php echo $password_info['group_name'] ? $password_info['group_name'] : '-' ?></span>
                            <?php if(isset($password_info['is_active']) && $password_info['is_active'] == 1): ?>
                                <span class="badge bg-success ms-2">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger ms-2">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Status Aktif</td>
                        <td>
                            <?php if($biodata->active == '1'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">User Code</td>
                        <td><?php echo $biodata->user_code ? $biodata->user_code : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Tanggal Dibuat</td>
                        <td><?php echo $biodata->created_at ? date('d F Y H:i:s', strtotime($biodata->created_at)) : '-' ?></td>
                    </tr>
                    <?php if($biodata->updated_at): ?>
                    <tr>
                        <td class="fw-bold bg-light">Terakhir Diubah</td>
                        <td><?php echo date('d F Y H:i:s', strtotime($biodata->updated_at)) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Print Area for table content only -->
        <div id="printArea" class="d-none d-print-block">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="fw-bold">Nama Lengkap</td>
                            <td><?php echo $biodata->nama_lengkap ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Username</td>
                            <td><?php echo $biodata->username ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email</td>
                            <td><?php echo $biodata->email ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Level</td>
                            <td><?php echo $biodata->group_name ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jenis Kelamin</td>
                            <td><?php echo $biodata->jenis_kelamin ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tempat Lahir</td>
                            <td><?php echo $biodata->tempat_lahir ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Lahir</td>
                            <td><?php echo $biodata->tanggal_lahir ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Agama</td>
                            <td><?php echo $biodata->agama ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Alamat</td>
                            <td><?php echo $biodata->alamat ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nomor Telepon</td>
                            <td><?php echo $biodata->telepon ?></td>
                        </tr>
                        <?php if($biodata->created_at): ?>
                        <tr>
                            <td class="fw-bold">Tanggal Dibuat</td>
                            <td><?php echo date('d F Y H:i:s', strtotime($biodata->created_at)) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($biodata->updated_at): ?>
                        <tr>
                            <td class="fw-bold">Terakhir Diubah</td>
                            <td><?php echo date('d F Y H:i:s', strtotime($biodata->updated_at)) ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <div class="btn-group" role="group">
                <button type="button" onclick="printDiv('printArea');" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                
                <?php if($biodata->level != 1): // Don't allow password reset for admin users ?>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-lock me-2"></i>Ubah Password
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('hubin/change_password_from_detail/' . $user->user_code) ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="fas fa-lock me-2"></i>Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password_baru_lainnya" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_baru_lainnya" name="password_baru" required minlength="6">
                        <div class="form-text">Minimal 6 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_konfirmasi_lainnya" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_konfirmasi_lainnya" name="password_konfirmasi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>