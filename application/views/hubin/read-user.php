<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Detail User</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user me-2"></i>Detail User</h2>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>Informasi User
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="30%" class="fw-bold bg-light">Username</td>
                        <td><?php echo $user->username ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Nama Lengkap</td>
                        <td><?php echo $user->nama_lengkap ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Password</td>
                        <td>
                            <code><?php 
                            // Show password if it's a new user from flashdata
                            $password_display = '*** (Password terenkripsi)';
                            if($this->session->flashdata('new_user_username') == $user->username){
                                $password_display = $this->session->flashdata('new_user_password');
                            }
                            echo $password_display;
                            ?></code>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">Level</td>
                        <td>
                            <span class="badge bg-info"><?php echo $user->group_name ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
                
        <div class="mt-4">
        </div>
    </div>
</div>

