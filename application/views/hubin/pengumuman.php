<?php 
$pengumuman = $this->db->order_by('created_at', 'DESC')->get('tb_pengumuman')->result();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Pengumuman</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Pengumuman</h2>
    <a href="<?php echo base_url('hubin/view/tambah-pengumuman') ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pengumuman
    </a>
</div>

<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<div class="row g-4">
    <?php if($pengumuman){ ?>
        <?php foreach($pengumuman as $p){ ?>
        <div class="col-md-6">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i><?php echo $p->judul ?></h5>
                    <div>
                        <a href="<?php echo base_url('hubin/view/edit-pengumuman/'.$p->pengumuman_id) ?>" 
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?php echo base_url('hubin/hapus_pengumuman/'.$p->pengumuman_id) ?>" 
                           onclick="return confirm('Hapus pengumuman ini?')" 
                           class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-calendar me-2"></i><?php echo date('l, d F Y', strtotime($p->created_at)); ?>
                    </p>
                    <p><?php echo strlen($p->isi) > 200 ? substr($p->isi, 0, 200) . '...' : $p->isi; ?></p>
                    <a href="<?php echo base_url('hubin/view/detail-pengumuman/'.$p->pengumuman_id) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php } else { ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <p>Belum ada pengumuman. Silakan tambah pengumuman baru.</p>
            </div>
        </div>
    <?php } ?>
</div>

