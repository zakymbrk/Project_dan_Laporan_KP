<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/pengumuman') ?>">Pengumuman</a></li>
        <li class="breadcrumb-item active">Edit Pengumuman</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Pengumuman</h2>
</div>

<?php if(!empty($this->session->flashdata('error_message'))){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $this->session->flashdata('error_message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Form Card -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Form Edit Pengumuman
        </h5>
    </div>
    <div class="card-body">
        <?php if(!isset($pengumuman) || !$pengumuman): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            Pengumuman tidak ditemukan.
        </div>
        <?php else: ?>
        <form action="<?php echo base_url('hubin/update_pengumuman') ?>" method="post">
            <input type="hidden" name="pengumuman_id" value="<?php echo $pengumuman->pengumuman_id; ?>">
            
            <div class="mb-3">
                <label for="judul" class="form-label">
                    <i class="fas fa-heading me-2"></i>Judul Pengumuman <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="judul" name="judul" 
                       placeholder="Masukkan judul pengumuman" required 
                       value="<?php echo set_value('judul', $pengumuman->judul); ?>">
                <?php echo form_error('judul', '<small class="text-danger">', '</small>'); ?>
            </div>
            
            <div class="mb-3">
                <label for="isi" class="form-label">
                    <i class="fas fa-align-left me-2"></i>Isi Pengumuman <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="isi" name="isi" rows="12" 
                          placeholder="Masukkan isi pengumuman (dapat menyertakan emoji dan format teks)" required 
                          style="font-family: 'Segoe UI Emoji', 'Segoe UI', sans-serif;"><?php echo set_value('isi', htmlspecialchars($pengumuman->isi, ENT_QUOTES, 'UTF-8')); ?></textarea>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Anda dapat menyertakan emoji 😊🎉📢 dan format teks seperti <strong>tebal</strong>, <em>miring</em>, atau <u>garis bawah</u>
                </div>
                <?php echo form_error('isi', '<small class="text-danger">', '</small>'); ?>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo base_url('hubin/view/pengumuman') ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Pengumuman
                </button>
            </div>
            </form>
        <?php endif; ?>
    </div>
</div>

