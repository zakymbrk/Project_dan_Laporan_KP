<?php 
$this->load->helper('view');
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Ubah Password</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-key me-2"></i>Ubah Password User</h2>
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

<?php if(validation_errors()){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Card -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-key me-2"></i>Ubah Password untuk: <?php echo $user->nama_lengkap; ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Anda sedang mengubah password untuk user <strong><?php echo $user->nama_lengkap; ?></strong> 
            (<?php echo $user->group_name; ?>) dengan username <strong><?php echo $user->username; ?></strong>
        </div>
        
        <form action="<?php echo base_url('hubin/proses_ubah_password_user') ?>" method="post">
            <input type="hidden" name="user_code" value="<?php echo $user->user_code; ?>">
            
            <div class="mb-3">
                <label for="password_baru" class="form-label">Password Baru <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_baru" name="password_baru" 
                       placeholder="Masukkan password baru (minimal 6 karakter)" required minlength="6">
                <small class="text-muted">Password minimal 6 karakter</small>
            </div>
            
            <div class="mb-3">
                <label for="password_konfirmasi" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" 
                       placeholder="Masukkan ulang password baru" required minlength="6">
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?php echo base_url('hubin/view/daftar-siswa') ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('password_baru').addEventListener('input', function() {
    var confirm = document.getElementById('password_konfirmasi');
    if(this.value !== confirm.value && confirm.value !== '') {
        confirm.setCustomValidity('Password tidak cocok');
    } else {
        confirm.setCustomValidity('');
    }
});

document.getElementById('password_konfirmasi').addEventListener('input', function() {
    var password = document.getElementById('password_baru');
    if(this.value !== password.value) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});
</script>

