<?php if(validation_errors()) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Tambah User</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>Form Tambah User</h2>
</div>

<form action="<?php echo site_url('hubin/tambah_user'); ?>" method="post">
    <div class="card fade-in">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2"></i>Data User Baru
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user me-2"></i>Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan Username" autofocus required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">
                        <i class="fas fa-id-card me-2"></i>Nama Lengkap
                    </label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                           placeholder="Masukkan Nama Lengkap" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="level" class="form-label">
                        <i class="fas fa-shield-alt me-2"></i>Level
                    </label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="" disabled selected>-- Pilih Level --</option>
                        <?php
                        $level = $this->db->get('tb_group');
                        foreach($level->result() as $row){
                        ?>
                            <option value="<?php echo $row->group_id ?>"><?php echo $row->group_name ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Masukkan Password" required>
                </div>
            </div>



            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>



