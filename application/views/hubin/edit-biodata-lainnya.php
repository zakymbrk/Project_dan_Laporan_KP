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
        <li class="breadcrumb-item active">Edit Biodata User</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-circle me-2"></i>Form Edit Biodata User</h2>
</div>

<form action="<?php echo site_url('hubin/update_biodata'); ?>" method="post" enctype="multipart/form-data">
    <div class="card fade-in">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Biodata User
            </h5>
        </div>
        <div class="card-body">
            <input type="hidden" name="user_code" value="<?php echo $user->user_code ?>">
            <input type="hidden" name="tipe_user" value="lainnya">
            
            <!-- Informasi Akun -->
            <div class="section mb-4">
                <h5 class="mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Informasi Akun</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Masukkan Username" value="<?php echo $user->username ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="nama_lengkap" class="form-label">
                            <i class="fas fa-id-card me-2"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                               placeholder="Masukkan Nama Lengkap" value="<?php echo $user->nama_lengkap ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Masukkan Email" value="<?php echo $user->email ? $user->email : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="telepon" class="form-label">
                            <i class="fas fa-phone me-2"></i>Telepon
                        </label>
                        <input type="text" class="form-control" id="telepon" name="telepon" 
                               placeholder="Masukkan Telepon" value="<?php echo $user->telepon ? $user->telepon : ''; ?>">
                    </div>
                </div>
            </div>
            
            <!-- Informasi Identitas -->
            <div class="section mb-4">
                <h5 class="mb-3"><i class="fas fa-address-card me-2 text-primary"></i>Informasi Identitas</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Tempat Lahir
                        </label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                               placeholder="Masukkan Tempat Lahir" value="<?php echo $user->tempat_lahir ? $user->tempat_lahir : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">
                            <i class="fas fa-calendar me-2"></i>Tanggal Lahir
                        </label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                               value="<?php echo $user->tanggal_lahir ? $user->tanggal_lahir : ''; ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">
                            <i class="fas fa-venus-mars me-2"></i>Jenis Kelamin
                        </label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" <?php echo ($user->jenis_kelamin == 'L' || $user->jenis_kelamin == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo ($user->jenis_kelamin == 'P' || $user->jenis_kelamin == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="alamat" class="form-label">
                        <i class="fas fa-home me-2"></i>Alamat
                    </label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                              placeholder="Masukkan Alamat"><?php echo $user->alamat ? $user->alamat : ''; ?></textarea>
                </div>
            </div>
            
            <!-- Foto Profil -->
            <div class="section mb-4">
                <h5 class="mb-3"><i class="fas fa-camera me-2 text-primary"></i>Foto Profil</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?php if($user->foto_profil): ?>
                            <div class="mb-3">
                                <label class="form-label">Foto Saat Ini:</label><br>
                                <img src="<?php echo base_url('uploads/profil/'.$user->foto_profil); ?>" 
                                     alt="Foto Profil" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        <?php endif; ?>
                        
                        <label for="foto_profil" class="form-label">
                            <i class="fas fa-upload me-2"></i>Upload Foto Baru
                        </label>
                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                               accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Update Biodata
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>