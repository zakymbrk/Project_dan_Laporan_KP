<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/data-dudi') ?>">Data DUDI</a></li>
        <li class="breadcrumb-item active">Tambah DUDI</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-building me-2"></i>Tambah DUDI</h2>
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

<!-- Card -->
<div class="card fade-in">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-plus me-2"></i>Tambah Data DUDI
        </h5>
    </div>
    <div class="card-body">
        <form action="<?php echo site_url('hubin/tambah_dudi') ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dudi_nama" class="form-label">Nama DUDI <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dudi_nama" name="dudi_nama" 
                               value="<?php echo set_value('dudi_nama') ?>" required>
                        <?php echo form_error('dudi_nama', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="dudi_alamat" name="dudi_alamat" rows="3" required><?php echo set_value('dudi_alamat') ?></textarea>
                        <?php echo form_error('dudi_alamat', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="dudi_telepon" name="dudi_telepon" 
                               value="<?php echo set_value('dudi_telepon') ?>">
                        <?php echo form_error('dudi_telepon', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="dudi_email" name="dudi_email" 
                               value="<?php echo set_value('dudi_email') ?>">
                        <?php echo form_error('dudi_email', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dudi_pic" class="form-label">Penanggung Jawab (PIC)</label>
                        <input type="text" class="form-control" id="dudi_pic" name="dudi_pic" 
                               value="<?php echo set_value('dudi_pic') ?>">
                        <?php echo form_error('dudi_pic', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_nip_pic" class="form-label">NIP PIC</label>
                        <input type="text" class="form-control" id="dudi_nip_pic" name="dudi_nip_pic" 
                               value="<?php echo set_value('dudi_nip_pic') ?>">
                        <?php echo form_error('dudi_nip_pic', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_instruktur" class="form-label">Instruktur</label>
                        <input type="text" class="form-control" id="dudi_instruktur" name="dudi_instruktur" 
                               value="<?php echo set_value('dudi_instruktur') ?>">
                        <?php echo form_error('dudi_instruktur', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dudi_nip_instruktur" class="form-label">NIP Instruktur</label>
                        <input type="text" class="form-control" id="dudi_nip_instruktur" name="dudi_nip_instruktur" 
                               value="<?php echo set_value('dudi_nip_instruktur') ?>">
                        <?php echo form_error('dudi_nip_instruktur', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="is_mitra" class="form-label">Status Mitra</label>
                        <select class="form-select" id="is_mitra" name="is_mitra">
                            <option value="1" <?php echo set_value('is_mitra', 1) == '1' ? 'selected' : ''; ?>>Mitra</option>
                            <option value="0" <?php echo set_value('is_mitra') == '0' ? 'selected' : ''; ?>>Non-Mitra</option>
                        </select>
                        <?php echo form_error('is_mitra', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Data
                </button>
                <a href="<?php echo base_url('hubin/view/data-dudi') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Batalkan
                </a>
            </div>
        </form>
    </div>
</div>