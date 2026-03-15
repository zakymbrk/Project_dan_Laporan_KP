<?php 
$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();

// Check if student has selected "other" company
if(!$siswa || !$siswa->other_dudi_nama) {
    redirect('siswa/view/home');
}
?>

<div class="card-mobile">
    <h5 class="mb-3"><i class="fas fa-building me-2 text-primary"></i>Detail Perusahaan PKL</h5>
    
    <div class="alert alert-info mb-3">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Perusahaan Baru</strong><br>
        Anda telah memilih perusahaan yang belum terdaftar dalam sistem. Silakan lengkapi informasi perusahaan berikut.
    </div>
    
    <?php if(validation_errors()){ ?>
        <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
        </div>
    <?php } ?>
    
    <form action="<?php echo base_url('siswa/simpan_detail_perusahaan') ?>" method="post">
        <input type="hidden" name="siswa_id" value="<?php echo $siswa->siswa_id; ?>">
        
        <div class="mb-3">
            <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" name="dudi_nama" class="form-control" 
                   value="<?php echo $siswa->other_dudi_nama; ?>" required readonly>
            <small class="text-muted">Nama perusahaan tidak dapat diubah</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="dudi_telepon" class="form-control" 
                   placeholder="Masukkan Nomor Telepon">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Alamat <span class="text-danger">*</span></label>
            <textarea name="dudi_alamat" class="form-control" rows="3" 
                      placeholder="Masukkan Alamat DUDI" required></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="dudi_email" class="form-control" 
                   placeholder="Masukkan Email">
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">PIC (Person In Charge)</label>
                <input type="text" name="dudi_pic" class="form-control" 
                       placeholder="Masukkan Nama PIC">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">NIP PIC</label>
                <input type="text" name="dudi_nip_pic" class="form-control" 
                       placeholder="Masukkan NIP PIC">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Instruktur</label>
                <input type="text" name="dudi_instruktur" class="form-control" 
                       placeholder="Masukkan Nama Instruktur">
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">NIP Instruktur</label>
                <input type="text" name="dudi_nip_instruktur" class="form-control" 
                       placeholder="Masukkan NIP Instruktur">
            </div>
        </div>
        

        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-mobile flex-grow-1">
                <i class="fas fa-save me-2"></i>Simpan Detail
            </button>
            <a href="<?php echo base_url('siswa/view/home') ?>" class="btn btn-secondary btn-mobile">
                <i class="fas fa-times me-2"></i>Batal
            </a>
        </div>
    </form>
</div>

