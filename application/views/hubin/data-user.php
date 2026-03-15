<?php 
$userdata = $this->session->userdata('userdata');
$search = $this->input->get('username') ? $this->input->get('username') : '';

// Note: All user data is now provided by the controller with pagination
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Data Pengguna</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Data Siswa</h2>
</div>

<?php if(!empty($this->session->flashdata('message'))){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $this->session->flashdata('message'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if(!empty($this->session->flashdata('new_user_username'))){ ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi Akun Baru</h5>
        <hr>
        <p class="mb-2"><strong>Akun siswa berhasil dibuat!</strong></p>
        <p class="mb-1"><strong>Nama:</strong> <?php echo $this->session->flashdata('new_user_nama'); ?></p>
        <p class="mb-1"><strong>Username:</strong> <code><?php echo $this->session->flashdata('new_user_username'); ?></code></p>
        <p class="mb-0"><strong>Password:</strong> <code><?php echo $this->session->flashdata('new_user_password'); ?></code></p>
        <hr>
        <p class="mb-0 small"><i class="fas fa-exclamation-triangle me-1"></i>Harap catat dan berikan informasi ini kepada siswa yang bersangkutan.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if(!empty($this->session->flashdata('reset_password_username'))){ ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Password Berhasil Direset</h5>
        <hr>
        <p class="mb-2"><strong>Password telah direset!</strong></p>
        <p class="mb-1"><strong>Username:</strong> <code><?php echo $this->session->flashdata('reset_password_username'); ?></code></p>
        <p class="mb-0"><strong>Password Baru:</strong> <code><?php echo $this->session->flashdata('reset_password_new'); ?></code></p>
        <hr>
        <p class="mb-0 small"><i class="fas fa-exclamation-triangle me-1"></i>Harap berikan password baru ini kepada user yang bersangkutan.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<?php if(validation_errors()){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo validation_errors(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php } ?>

<!-- Card -->
<div class="card fade-in">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-graduation-cap me-2"></i>Daftar Siswa
        </h5>
        <div>
            <span class="badge bg-primary">
                <i class="fas fa-users me-1"></i>Total: <?php echo $total_rows ?> User
            </span>
        </div>
    </div>
    <div class="card-body">
        <!-- Action Bar -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="<?php echo base_url('hubin/view/tambah-user') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah User
                </a>
            </div>
            <div class="col-md-6">
                <form action="" method="get" class="d-flex">
                    <input type="search" value="<?php echo $search ?>" name="username" 
                           placeholder="Cari nama siswa atau username..." class="form-control me-2" required>
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Username</th>
                        <th>Nama Siswa</th>
                        <th>Identitas Lengkap</th>
                        <th>Password</th>
                        <th>Kelas</th>
                        <th width="30%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if($getuser->num_rows() > 0){
                        foreach($getuser->result() as $show){
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><strong><?php echo $show->username ?></strong></td>
                        <td><?php echo $show->nama_lengkap ?></td>
                        <td>
                            <?php 
                            // Tampilkan indikator identitas lengkap
                            $has_complete_identity = (
                                !empty($show->email) && 
                                !empty($show->telepon) && 
                                !empty($show->alamat)
                            );
                            
                            if($has_complete_identity){
                                echo '<span class="badge bg-success"><i class="fas fa-check"></i> Lengkap</span>';
                            } else {
                                echo '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> Belum Lengkap</span>';
                            }
                            ?>
                            <br>
                            <small class="text-muted">
                                Email: <?php echo !empty($show->email) ? '✓' : '✗'; ?> | 
                                Telepon: <?php echo !empty($show->telepon) ? '✓' : '✗'; ?> | 
                                Alamat: <?php echo !empty($show->alamat) ? '✓' : '✗'; ?>
                            </small>
                        </td>
                        <td>
                            <code><?php 
                            // Show password if it's a new user from flashdata
                            $password_display = '***';
                            if($this->session->flashdata('new_user_username') == $show->username){
                                $password_display = $this->session->flashdata('new_user_password');
                            }
                            echo $password_display;
                            ?></code>
                            <?php if($password_display == '***'){ ?>
                                <small class="text-muted d-block">Password terenkripsi</small>
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            // Get student class from tb_siswa table
                            $this->db->select('siswa_kelas');
                            $this->db->where('user_id', $show->id);
                            $siswa_data = $this->db->get('tb_siswa')->row();
                            echo isset($siswa_data->siswa_kelas) ? $siswa_data->siswa_kelas : '-';
                            ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('hubin/read_user/'.$show->user_code) ?>" 
                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="<?php echo base_url('hubin/ubah_password_user/'.$show->user_code) ?>" 
                                   class="btn btn-sm btn-success" title="Ubah Password">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="<?php echo site_url('hubin/reset_password_user/'.$show->user_code) ?>" 
                                   onclick="return confirm('Reset password menjadi username123?')" 
                                   class="btn btn-sm btn-secondary" title="Reset Password">
                                    <i class="fas fa-redo"></i>
                                </a>
                                <a href="<?php echo site_url('hubin/hapus_user/'.$show->user_code) ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                                   class="btn btn-sm btn-danger" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                <?php if($config['total_rows'] > 0): ?>
                    Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $config['per_page'], $config['total_rows']); ?> dari <?php echo $config['total_rows']; ?> siswa
                <?php else: ?>
                    Menampilkan 0 dari 0 siswa
                <?php endif; ?>
            </div>
            <nav>
                <?php echo $this->pagination->create_links(); ?>
            </nav>
        </div>
    </div>
</div>
