<?php 
if(!isset($pengumuman) || !$pengumuman){
    redirect('hubin/view/pengumuman');
}

// Get creator
$creator = null;
if($pengumuman->created_by){
    $this->db->where('id', $pengumuman->created_by);
    $creator = $this->db->get('tb_user')->row();
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/pengumuman') ?>">Pengumuman</a></li>
        <li class="breadcrumb-item active">Detail Pengumuman</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Detail Pengumuman</h2>
</div>

<!-- Card -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-bullhorn me-2"></i><?php echo $pengumuman->judul; ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <p class="text-muted mb-2">
                <i class="fas fa-calendar me-2"></i>
                <strong>Tanggal:</strong> <?php echo date('l, d F Y', strtotime($pengumuman->created_at)); ?>
            </p>
            <?php if($creator){ ?>
            <p class="text-muted mb-2">
                <i class="fas fa-user me-2"></i>
                <strong>Dibuat oleh:</strong> <?php echo $creator->nama_lengkap; ?>
            </p>
            <?php } ?>
            <?php if($pengumuman->updated_at){ ?>
            <p class="text-muted mb-0">
                <i class="fas fa-edit me-2"></i>
                <strong>Diperbarui:</strong> <?php echo date('l, d F Y H:i', strtotime($pengumuman->updated_at)); ?>
            </p>
            <?php } ?>
        </div>
        
        <hr>
        
        <div class="mt-4" style="font-family: 'Segoe UI Emoji', 'Segoe UI', sans-serif; white-space: pre-wrap;">
            <?php 
            // Convert basic markdown-like formatting to HTML
            $content = htmlspecialchars($pengumuman->isi, ENT_QUOTES, 'UTF-8');
            $content = nl2br($content);
            // Convert **bold** to <strong>bold</strong>
            $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
            // Convert *italic* to <em>italic</em>
            $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
            // Convert _underline_ to <u>underline</u>
            $content = preg_replace('/_(.*?)_/', '<u>$1</u>', $content);
            echo $content;
            ?>
        </div>
    </div>
</div>

