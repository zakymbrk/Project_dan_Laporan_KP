<?php 
$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();
?>

<div class="card-mobile">
    <h5 class="mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Status Pengajuan PKL</h5>
    
    <?php if($siswa){ ?>
        <div class="info-card mb-3">
            <div class="info-title">Nama Siswa</div>
            <div class="info-value"><?php echo $siswa->siswa_nama ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Kelas</div>
            <div class="info-value"><?php echo $siswa->siswa_kelas ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Status Pengajuan</div>
            <div>
                <?php 
                $badge_class = 'badge-info';
                if($siswa->status_pengajuan == 'disetujui') $badge_class = 'badge-success';
                if($siswa->status_pengajuan == 'ditolak') $badge_class = 'badge-danger';
                if($siswa->status_pengajuan == 'menunggu') $badge_class = 'badge-warning';
                ?>
                <span class="badge-status <?php echo $badge_class; ?>">
                    <?php echo ucfirst($siswa->status_pengajuan); ?>
                </span>
            </div>
        </div>
        

        
        <?php if($siswa->other_dudi_nama){ ?>
        <div class="info-card mb-3">
            <div class="info-title">Perusahaan (DUDI)</div>
            <div class="info-value">
                <span class="text-primary fw-bold">[PERUSAHAAN BARU]</span> <?php echo $siswa->other_dudi_nama ?>
                <br><small class="text-muted">Perlu ditambahkan ke sistem</small>
                <br><a href="<?php echo base_url('siswa/view/detail-perusahaan') ?>" class="btn btn-sm btn-outline-primary mt-2">Lengkapi Detail Perusahaan</a>
            </div>
        </div>
        <?php } elseif($siswa->dudi_id){ 
            $this->db->where('dudi_id', $siswa->dudi_id);
            $dudi = $this->db->get('tb_dudi')->row();
        ?>
        <div class="info-card mb-3">
            <div class="info-title">Perusahaan (DUDI)</div>
            <div class="info-value"><?php echo $dudi ? $dudi->dudi_nama : '-' ?></div>
        </div>
        <?php } ?>
        
        <?php if($siswa->surat_permohonan && file_exists('./uploads/pengajuan/'.$siswa->surat_permohonan)){ ?>
        <div class="info-card mb-3">
            <div class="info-title">Surat Permohonan/Pernyataan</div>
            <div>
                <a href="<?php echo base_url('uploads/pengajuan/'.$siswa->surat_permohonan) ?>" 
                   target="_blank" 
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-pdf me-1"></i>Lihat Dokumen
                </a>
            </div>
        </div>
        <?php } ?>
        
        
        

        
        <?php if($siswa->status_pengajuan == 'draft' || $siswa->status_pengajuan == 'ditolak' || $siswa->status_pengajuan == 'disetujui'){ ?>
        <div style="margin-top: 20px; position: relative; z-index: 9999;">
            <button onclick="window.location.href='<?php echo base_url('siswa/view/buat-pengajuan'); ?>'" 
                    style="background-color: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; pointer-events: auto !important; box-shadow: 0 4px 8px rgba(0,123,255,0.3);">
                <i class="fas fa-edit" style="margin-right: 8px;"></i>
                <?php 
                if($siswa->status_pengajuan == 'ditolak') {
                    echo 'Ajukan Ulang';
                } elseif($siswa->status_pengajuan == 'disetujui') {
                    echo 'Ajukan Ulang (Pengajuan Diproses Hubin)';
                } else {
                    echo 'Buat/Edit Pengajuan';
                }
                ?>
            </button>
        </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Anda belum membuat pengajuan PKL.
        </div>
        <a href="<?php echo base_url('siswa/view/buat-pengajuan') ?>" class="btn btn-primary btn-mobile">
            <i class="fas fa-plus me-2"></i>Buat Pengajuan PKL
        </a>
    <?php } ?>
</div>

