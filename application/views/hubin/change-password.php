<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/profile') ?>">Profile</a></li>
        <li class="breadcrumb-item active">Ubah Password</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-key me-2"></i>Ubah Password</h2>
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

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Form Ubah Password</h5>
            </div>
            <div class="card-body">
                <?php if(validation_errors()) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo validation_errors(); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <form action="<?php echo site_url('hubin/change_password'); ?>" method="post">
                    <div class="mb-3">
                        <label for="password_lama" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password Lama
                        </label>
                        <input type="password" class="form-control" id="password_lama" name="password_lama" 
                               placeholder="Masukkan Password Lama" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_baru" class="form-label">
                            <i class="fas fa-key me-2"></i>Password Baru
                        </label>
                        <input type="password" class="form-control" id="password_baru" name="password_baru" 
                               placeholder="Masukkan Password Baru (Min. 6 karakter)" required>
                        <small class="form-text text-muted">Password minimal 6 karakter</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_konfirmasi" class="form-label">
                            <i class="fas fa-check-double me-2"></i>Konfirmasi Password Baru
                        </label>
                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" 
                               placeholder="Konfirmasi Password Baru" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card fade-in mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Tips Keamanan Password</h6>
                <ul class="list-unstyled mb-0 small">
                    <li><i class="fas fa-check text-success me-2"></i>Gunakan kombinasi huruf, angka, dan karakter khusus</li>
                    <li><i class="fas fa-check text-success me-2"></i>Jangan gunakan password yang mudah ditebak</li>
                    <li><i class="fas fa-check text-success me-2"></i>Ganti password secara berkala</li>
                    <li><i class="fas fa-check text-success me-2"></i>Jangan bagikan password Anda kepada siapapun</li>
                </ul>
            </div>
        </div>
    </div>
</div>

