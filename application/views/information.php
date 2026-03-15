<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php 
            $group_name = strtolower($this->session->userdata('userdata')['group_name']);
            echo base_url($group_name.'/view'); 
        ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active">Information</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h2>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tentang Aplikasi</h5>
            </div>
            <div class="card-body">
                <h4 class="text-primary mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>Sistem Pendataan Siswa Prakerin (SiPKL)
                </h4>
                <p class="lead">
                    Aplikasi web berbasis CodeIgniter untuk mengelola data siswa yang sedang melakukan Praktik Kerja Industri (Prakerin).
                </p>
                
                <hr>
                
                <h5 class="mb-3"><i class="fas fa-list me-2"></i>Fitur Aplikasi:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Manajemen Data User (CRUD)
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Manajemen Data Siswa (CRUD)
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Pencarian Data dengan Filter
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Pagination untuk Data Tables
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Dashboard dengan Charts & Statistik
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Export Data ke Excel
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Print Laporan
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Profile Management
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Ubah Password
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>Multi Level User (Hubin, Pembimbing & Siswa)
                            </li>
                        </ul>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">
                                <i class="fas fa-building me-2"></i>SMK ITIKURIH HIBARNA
                            </h6>
                            <p class="mb-0">
                                Sistem ini dikembangkan untuk memudahkan pengelolaan data siswa Prakerin di SMK ITIKURIH HIBARNA.<br>
                                <strong>Alamat:</strong> Jalan Raya Laswi No. 782 Ciparay Kab. Bandung [40381]<br>
                                <strong>Telpon:</strong> 022-5957900<br>
                                <strong>Email:</strong> smk@itikurih-hibarna.sch.id
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-success mb-3">
                            <h6 class="alert-heading">
                                <i class="fas fa-code me-2"></i>Teknologi
                            </h6>
                            <p class="mb-0 small">
                                <strong>Framework:</strong> CodeIgniter 3<br>
                                <strong>Frontend:</strong> Bootstrap 5, Chart.js<br>
                                <strong>Database:</strong> MySQL/MariaDB
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mb-0">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Versi Aplikasi
                    </h6>
                    <p class="mb-0">
                        <strong>SiPKL v2.0</strong> - Sistem Pendataan Siswa Prakerin dengan fitur lengkap dan tampilan profesional.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
