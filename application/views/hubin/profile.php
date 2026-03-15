<?php 
$userdata = $this->session->userdata('userdata');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profile Saya</h2>
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

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card fade-in">
            <div class="card-body text-center">
                <div class="profile-avatar mb-3" style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                    <?php if(isset($user->foto_profil) && $user->foto_profil && file_exists('./uploads/profil/'.$user->foto_profil)){ ?>
                        <img src="<?php echo base_url('uploads/profil/'.$user->foto_profil) ?>" 
                             alt="Foto Profil" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php } else { ?>
                        <img src="<?php echo base_url('assets/img/logo-sekolah.png') ?>" 
                             alt="Logo Sekolah" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php } ?>
                </div>
                <h4 class="mb-1"><?php echo $user->nama_lengkap ?></h4>
                <p class="text-muted mb-2"><?php echo $user->group_name ?></p>
                <span class="badge bg-success">
                    <i class="fas fa-circle me-1"></i>Aktif
                </span>
            </div>
        </div>
        
        <div class="card fade-in mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Akun</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Username:</td>
                        <td><strong><?php echo $user->username ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Level:</td>
                        <td><span class="badge bg-info"><?php echo $user->group_name ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
            </div>
            <div class="card-body">
                <?php if(validation_errors()) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo validation_errors(); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <form action="<?php echo site_url('hubin/update_profile'); ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user me-2"></i>Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo $user->username ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">
                                <i class="fas fa-id-card me-2"></i>Nama Lengkap
                            </label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?php echo $user->nama_lengkap ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="foto_profil" class="form-label">
                                <i class="fas fa-image me-2"></i>Foto Profil
                            </label>
                            <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                                   accept="image/*">
                            <small class="form-text text-muted">
                                Format: JPG, PNG, GIF (Maksimal 2MB)
                            </small>
                            <?php if(isset($user->foto_profil) && $user->foto_profil && file_exists('./uploads/profil/'.$user->foto_profil)){ ?>
                                <div class="mt-2">
                                    <small class="text-muted">Foto saat ini:</small><br>
                                    <img src="<?php echo base_url('uploads/profil/'.$user->foto_profil) ?>" 
                                         alt="Foto Profil" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-top: 10px;">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="<?php echo base_url('hubin/view/change-password') ?>" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

