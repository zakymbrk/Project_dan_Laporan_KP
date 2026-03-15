<?php if(validation_errors()) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/data-user') ?>">Data User</a></li>
        <li class="breadcrumb-item active">Edit Biodata Pembimbing</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Edit Biodata Pembimbing</h2>
</div>

<form action="<?php echo site_url('hubin/update_biodata'); ?>" method="post" enctype="multipart/form-data">
    <div class="card fade-in">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Form Edit Biodata Pembimbing
            </h5>
        </div>
        <div class="card-body">
            <input type="hidden" name="user_code" value="<?php echo $user->user_code ?>">
            <input type="hidden" name="tipe_user" value="pembimbing">
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="fw-bold bg-light">Nama Pembimbing</td>
                            <td>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                       value="<?php echo $user->nama_lengkap ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Username</td>
                            <td>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo $user->username ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Email</td>
                            <td>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Masukkan Email" value="<?php echo $user->email ? $user->email : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Telepon</td>
                            <td>
                                <input type="text" class="form-control" id="telepon" name="telepon" 
                                       placeholder="Masukkan Telepon" value="<?php echo $user->telepon ? $user->telepon : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Tempat Lahir</td>
                            <td>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                       placeholder="Masukkan Tempat Lahir" value="<?php echo $user->tempat_lahir ? $user->tempat_lahir : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Tanggal Lahir</td>
                            <td>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                       value="<?php echo $user->tanggal_lahir ? $user->tanggal_lahir : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Jenis Kelamin</td>
                            <td>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" <?php echo ($user->jenis_kelamin == 'L' || $user->jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?php echo ($user->jenis_kelamin == 'P' || $user->jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Alamat</td>
                            <td>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                          placeholder="Masukkan Alamat"><?php echo $user->alamat ? $user->alamat : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">NIP Pembimbing</td>
                            <td>
                                <input type="text" class="form-control" id="pembimbing_nip" name="pembimbing_nip" 
                                       placeholder="Masukkan NIP Pembimbing" value="<?php echo isset($biodata->pembimbing_nip) ? $biodata->pembimbing_nip : $user->nip_nim; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Telepon Pembimbing</td>
                            <td>
                                <input type="text" class="form-control" id="pembimbing_telepon" name="pembimbing_telepon" 
                                       placeholder="Masukkan Telepon Pembimbing" value="<?php echo isset($biodata->pembimbing_telepon) ? $biodata->pembimbing_telepon : $user->telepon; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Email Pembimbing</td>
                            <td>
                                <input type="email" class="form-control" id="pembimbing_email" name="pembimbing_email" 
                                       placeholder="Masukkan Email Pembimbing" value="<?php echo isset($biodata->pembimbing_email) ? $biodata->pembimbing_email : $user->email; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Jabatan</td>
                            <td>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" 
                                       placeholder="Masukkan Jabatan" value="<?php echo isset($biodata->jabatan) ? $biodata->jabatan : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Pendidikan Terakhir</td>
                            <td>
                                <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" 
                                       placeholder="Masukkan Pendidikan Terakhir" value="<?php echo isset($biodata->pendidikan_terakhir) ? $biodata->pendidikan_terakhir : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Jurusan Keahlian</td>
                            <td>
                                <input type="text" class="form-control" id="jurusan_keahlian" name="jurusan_keahlian" 
                                       placeholder="Masukkan Jurusan Keahlian" value="<?php echo isset($biodata->jurusan_keahlian) ? $biodata->jurusan_keahlian : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Tahun Masuk</td>
                            <td>
                                <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" 
                                       placeholder="Masukkan Tahun Masuk" min="1950" max="<?php echo date('Y'); ?>" 
                                       value="<?php echo isset($biodata->tahun_masuk) ? $biodata->tahun_masuk : ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Status Kepegawaian</td>
                            <td>
                                <select class="form-select" id="status_kepegawaian" name="status_kepegawaian">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="PNS" <?php echo (isset($biodata->status_kepegawaian) && $biodata->status_kepegawaian == 'PNS') ? 'selected' : ''; ?>>PNS</option>
                                    <option value="Honorer" <?php echo (isset($biodata->status_kepegawaian) && $biodata->status_kepegawaian == 'Honorer') ? 'selected' : ''; ?>>Honorer</option>
                                    <option value="Kontrak" <?php echo (isset($biodata->status_kepegawaian) && $biodata->status_kepegawaian == 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-bold bg-light">Tempat Tugas</td>
                            <td>
                                <select class="form-control" id="tempat_tugas" name="tempat_tugas">
                                    <option value="">-- Pilih Tempat Tugas --</option>
                                    <option value="TKJ" <?php echo (isset($biodata->tempat_tugas) && $biodata->tempat_tugas == 'TKJ') ? 'selected' : ''; ?>>Teknik Komputer Jaringan</option>
                                    <option value="Perbankan" <?php echo (isset($biodata->tempat_tugas) && $biodata->tempat_tugas == 'Perbankan') ? 'selected' : ''; ?>>Perbankan</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Alamat Pembimbing</td>
                            <td>
                                <textarea class="form-control" id="pembimbing_alamat" name="pembimbing_alamat" rows="3" 
                                          placeholder="Masukkan Alamat Pembimbing"><?php echo isset($biodata->pembimbing_alamat) ? $biodata->pembimbing_alamat : $user->alamat; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Foto Profil</td>
                            <td>
                                <?php if($user->foto_profil): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Foto Saat Ini:</label><br>
                                        <img src="<?php echo base_url('uploads/profil/'.$user->foto_profil); ?>" 
                                             alt="Foto Profil" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                <?php endif; ?>
                                
                                <label for="foto_profil" class="form-label">
                                    Upload Foto Baru
                                </label>
                                <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                                       accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Update Biodata
                </button>
            </div>
        </div>
    </div>
</form>