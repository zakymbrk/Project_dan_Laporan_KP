<div class="card-mobile">
    <h5 class="mb-3"><i class="fas fa-key me-2 text-primary"></i>Ubah Password</h5>
    
    <?php if(!empty($this->session->flashdata('message'))){ ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php } ?>
    
    <?php if(!empty($this->session->flashdata('error_message'))){ ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error_message'); ?>
        </div>
    <?php } ?>
    
    <?php if(validation_errors()){ ?>
        <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
        </div>
    <?php } ?>
    
    <form action="<?php echo base_url('siswa/change_password') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Password Lama</label>
            <input type="password" name="password_lama" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password_baru" class="form-control" minlength="6" required>
            <small class="text-muted">Minimal 6 karakter</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_konfirmasi" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-mobile">
            <i class="fas fa-save me-2"></i>Ubah Password
        </button>
        <a href="<?php echo base_url('siswa/view/profile') ?>" class="btn btn-secondary btn-mobile">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </form>
</div>

