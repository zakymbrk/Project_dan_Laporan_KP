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
        <li class="breadcrumb-item active">Edit User</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit User</h2>
</div>

<form action="<?php echo site_url('hubin/update_data_user'); ?>" method="post">
    <div class="card fade-in">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Data User
            </h5>
        </div>
        <div class="card-body">
            <input type="hidden" name="user_code" value="<?php echo $user->user_code ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user me-2"></i>Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan Username" value="<?php echo $user->username ?>" autofocus required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">
                        <i class="fas fa-id-card me-2"></i>Nama Lengkap
                    </label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                           placeholder="Masukkan Nama Lengkap" value="<?php echo $user->nama_lengkap ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="level" class="form-label">
                        <i class="fas fa-shield-alt me-2"></i>Level
                    </label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="" disabled>-- Pilih Level --</option>
                        <?php
                        $level = $this->db->get('tb_group');
                        foreach($level->result() as $row){
                            $selected = ($row->group_id == $user->level) ? 'selected' : '';
                        ?>
                            <option <?php echo $selected; ?> value="<?php echo $row->group_id ?>">
                                <?php echo $row->group_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

