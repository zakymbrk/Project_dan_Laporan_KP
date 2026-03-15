<?php 
$userdata = $this->session->userdata('userdata');
$this->db->where('user_id', $userdata['id']);
$siswa = $this->db->get('tb_siswa')->row();

// Initialize variables to prevent undefined variable errors
$dudi = null;
$pembimbing = null;

if($siswa && $siswa->dudi_id){
    $this->db->where('dudi_id', $siswa->dudi_id);
    $dudi = $this->db->get('tb_dudi')->row();
    
    // Get pembimbing using proper relationship
    if($siswa->siswa_id) {
        $pembimbing = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
    }
} elseif($siswa && $siswa->other_dudi_nama) {
    // For students with other/unregistered companies - use student-filled data
    $dudi = (object) [
        'dudi_nama' => $siswa->other_dudi_nama,
        'dudi_alamat' => $siswa->other_dudi_alamat ? $siswa->other_dudi_alamat : '-',
        'dudi_telepon' => $siswa->other_dudi_telepon ? $siswa->other_dudi_telepon : '-',
        'dudi_email' => $siswa->other_dudi_email ? $siswa->other_dudi_email : '-',
        'dudi_pic' => $siswa->other_dudi_pic ? $siswa->other_dudi_pic : '-',
        'dudi_nip_pic' => $siswa->other_dudi_nip_pic ? $siswa->other_dudi_nip_pic : '-',
        'dudi_instruktur' => $siswa->other_dudi_instruktur ? $siswa->other_dudi_instruktur : '-',
        'dudi_nip_instruktur' => $siswa->other_dudi_nip_instruktur ? $siswa->other_dudi_nip_instruktur : '-'
    ];
    
    // Get pembimbing using proper relationship
    if($siswa->siswa_id) {
        $pembimbing = $this->M_pengelompokan->get_pembimbing_by_siswa($siswa->siswa_id);
    }
}
?>

<div class="card-mobile">
    <h5 class="mb-3"><i class="fas fa-building me-2 text-primary"></i>Detail Penempatan PKL</h5>
    
    <?php if($siswa && $siswa->status_pengajuan == 'disetujui'){ ?>
        <div class="info-card mb-3">
            <div class="info-title">Nama Siswa</div>
            <div class="info-value"><?php echo $siswa->siswa_nama ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Kelas</div>
            <div class="info-value"><?php echo $siswa->siswa_kelas ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Jurusan</div>
            <div class="info-value"><?php echo $siswa->siswa_jurusan ? $siswa->siswa_jurusan : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Periode PKL</div>
            <div class="info-value"><?php echo $siswa->periode ? $siswa->periode : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Guru Pembimbing</div>
            <div class="info-value"><?php echo $pembimbing ? (isset($pembimbing->user_nama) ? $pembimbing->user_nama : $pembimbing->pembimbing_nama) : '-' ?></div>
        </div>
        
        <?php if($dudi){ ?>
        <hr>
        <h6 class="mb-3"><i class="fas fa-building me-2"></i>Informasi DUDI</h6>
        
        <div class="info-card mb-3">
            <div class="info-title">Nama Perusahaan</div>
            <div class="info-value"><?php echo $dudi->dudi_nama ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">PIC</div>
            <div class="info-value"><?php echo isset($dudi->dudi_pic) ? $dudi->dudi_pic : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">NIP PIC</div>
            <div class="info-value"><?php echo isset($dudi->dudi_nip_pic) ? $dudi->dudi_nip_pic : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Instruktur</div>
            <div class="info-value"><?php echo isset($dudi->dudi_instruktur) ? $dudi->dudi_instruktur : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">NIP Instruktur</div>
            <div class="info-value"><?php echo isset($dudi->dudi_nip_instruktur) ? $dudi->dudi_nip_instruktur : '-' ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Telepon</div>
            <div class="info-value"><?php echo $dudi->dudi_telepon ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Email</div>
            <div class="info-value"><?php echo $dudi->dudi_email ?></div>
        </div>
        
        <div class="info-card mb-3">
            <div class="info-title">Alamat</div>
            <div class="info-value"><?php echo $dudi->dudi_alamat ?></div>
        </div>
        
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Pengajuan PKL Anda belum disetujui atau belum ada penempatan.
        </div>
    <?php } ?>
</div>

