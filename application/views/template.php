<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiPKL - <?php echo ucfirst($content); ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/logo.png') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-custom.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard-custom.css') ?>">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Modern Sidebar */
        .sidebar {
            background: #1a1a1a;
            min-height: calc(100vh - 56px);
            padding: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #fff;
            padding-left: 25px;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border-left-color: #fff;
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }
        
        /* Modern Navbar */
        .navbar-custom {
            background: #1a1a1a !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 12px 0;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff !important;
        }
        
        /* Modern Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            margin-bottom: 25px;
        }
        
        .card:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        
        /* Ensure main content area is clickable */
        main {
            pointer-events: auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 18px 25px;
            font-weight: 600;
            border: none;
        }
        
        /* Modern Buttons */
        .btn {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
        }
        
        /* Modern Tables */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 18px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        /* Profile Section */
        .sidebar-profile {
            padding: 25px 20px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-profile img {
            border: 3px solid rgba(255,255,255,0.3);
        }
        
        /* Main Content */
        main {
            background: #f5f7fa;
            padding: 30px;
            min-height: calc(100vh - 56px);
        }
        
        /* Stat Cards */
        .stat-card-modern {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
            border-left: 4px solid #1a1a1a;
        }
        
        .stat-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }
        
        .stat-card-modern .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            float: right;
            margin-top: -10px;
            color: #1a1a1a;
        }
        
        .stat-card-modern .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 10px 0;
        }
        
        .stat-card-modern .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php 
// Load helper and calculate pending submissions for Hubin
$this->load->helper('view');
$pending_count = 0;
if($this->session->userdata('userdata')['level'] == 1){ // Hubin 
    $pending_count = $this->db->where('status_pengajuan', 'menunggu')->get('tb_siswa')->num_rows();
}
?>

<!-- Notification Banner for Hubin -->
<?php if($this->session->userdata('userdata')['level'] == 1 && $pending_count > 0): ?>
<div class="alert alert-warning alert-dismissible fade show text-center py-2 mb-0 rounded-0" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Ada <?php echo $pending_count ?> pengajuan PKL menunggu!</strong> Silakan tinjau dan proses pengajuan dari siswa.
    <a href="<?php echo base_url('hubin/view/pengajuan') ?>" class="alert-link fw-bold ms-2">Lihat Pengajuan</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-top: -0.25rem;"></button>
</div>
<?php endif; ?>

<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php 
                $level = $this->session->userdata('userdata')['level'];
                if($level == 1) echo base_url('hubin/view');
                elseif($level == 3) echo base_url('siswa/view');
            ?>">
                <i class="fas fa-graduation-cap me-2"></i><strong>Si</strong>PKL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu removed as per user request -->
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <div class="sidebar-profile text-center" style="cursor: pointer;" onclick="window.location.href='<?php 
                        $level = $this->session->userdata('userdata')['level'];
                        if($level == 1) echo base_url('hubin/view/profile');
                        elseif($level == 3) echo base_url('siswa/view/profile');
                    ?>'">
                        <?php 
                        $foto_profil = isset($this->session->userdata('userdata')['foto_profil']) ? $this->session->userdata('userdata')['foto_profil'] : null;
                        if($foto_profil && file_exists('./uploads/profil/'.$foto_profil)){ ?>
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center overflow-hidden" style="width: 70px; height: 70px; border: 3px solid rgba(255,255,255,0.3); transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.border='3px solid rgba(255,255,255,0.6)';" onmouseout="this.style.transform='scale(1)'; this.style.border='3px solid rgba(255,255,255,0.3)';">
                                <img src="<?php echo base_url('uploads/profil/'.$foto_profil) ?>" 
                                     alt="Foto Profil" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php } else { ?>
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: rgba(255,255,255,0.1); border: 3px solid rgba(255,255,255,0.3); transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.border='3px solid rgba(255,255,255,0.6)';" onmouseout="this.style.transform='scale(1)'; this.style.border='3px solid rgba(255,255,255,0.3)';">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        <?php } ?>
                        <p class="text-white mt-3 mb-1 fw-bold" style="font-size: 1rem;"><?php echo $this->session->userdata('userdata')['nama_lengkap'] ?></p>
                        <small class="text-white-50 d-block mb-2">
                            <?php echo $this->session->userdata('userdata')['group_name']; ?>
                        </small>
                        
                    </div>
                    
                    <ul class="nav flex-column" style="padding: 0 10px;">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_string() == strtolower($this->session->userdata('userdata')['group_name']).'/view') ? 'active' : ''; ?>" 
                               href="<?php 
                                $level = $this->session->userdata('userdata')['level'];
                                if($level == 1) echo base_url('hubin/view');
                                elseif($level == 3) echo base_url('siswa/view');
                            ?>">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>

                        <?php if($this->session->userdata('userdata')['level'] == 1){ // Hubin ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'daftar-siswa') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/daftar-siswa') ?>">
                                <i class="fas fa-graduation-cap me-2"></i> Data Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'data-pembimbing') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/data-pembimbing') ?>">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Data Pembimbing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'assign-pembimbing') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/assign-pembimbing') ?>">
                                <i class="fas fa-user-tie me-2"></i> Assign Pembimbing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'data-dudi') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/data-dudi') ?>">
                                <i class="fas fa-industry me-2"></i> Data DUDI
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'pengajuan') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/pengajuan') ?>">
                                <i class="fas fa-file-contract me-2"></i> Pengajuan PKL
                                <?php if($pending_count > 0): ?>
                                    <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7em;">
                                        <?php echo $pending_count ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'pengumuman') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('hubin/view/pengumuman') ?>">
                                <i class="fas fa-bullhorn me-2"></i> Informasi & Pengumuman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('auth/logout') ?>" style="color: #ff6b6b;">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>

                        <?php } ?>
                        

                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php $this->load->view($content);?>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">
                <strong>SiPKL</strong> v2.0 - Sistem Pendataan Siswa Prakerin SMK ITIKURIH HIBARNA
                <span class="ms-2">© <?php echo date('Y'); ?></span>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Print function for targeted printing of content
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            
            // Add print styles programmatically
            var style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = `
                @page {
                    size: A4;
                    margin: 15mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 15mm;
                    background: white !important;
                    color: black !important;
                    font-size: 12pt;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 0;
                }
                td, th {
                    border: 1px solid #000;
                    padding: 8px;
                    background: white !important;
                    color: black !important;
                }
                .fw-bold {
                    font-weight: bold;
                }
            `;
            document.head.appendChild(style);
            
            window.print();
            
            document.body.innerHTML = originalContents;
            location.reload(); // Reload to restore original content
        }

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Ensure no modals are stuck open with backdrops
        document.addEventListener('DOMContentLoaded', function() {
            // Close any open modals on page load
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(function(modal) {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            });
            
            // Remove any lingering modal backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.remove();
            });
            
            // Ensure body doesn't have modal-open class
            document.body.classList.remove('modal-open');
        });
        
        // Add click event listener to detect if clicks are being blocked
        document.addEventListener('click', function(e) {
            // Check if click is being prevented by overlays
            const overlays = document.querySelectorAll('.modal-backdrop, .loading-overlay, .preloader');
            if (overlays.length > 0) {
                console.log('Potential click-blocking elements detected:', overlays);
            }
            
            // Debug semua klik
            console.log('Click detected on:', e.target);
            console.log('Click path:', e.composedPath());
        }, true); // Use capture phase to detect events early
        
        // Ensure sidebar collapse works properly to prevent layout issues
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure sidebar is properly initialized
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                // Ensure no unintended display:block is applied incorrectly
                sidebar.style.overflow = 'auto';
            }
            
            // Check for any elements that might be blocking clicks
            const body = document.body;
            body.style.pointerEvents = 'auto';
        });
        
        // Final check to ensure page is interactive
        window.addEventListener('load', function() {
            // Remove any potential loading classes
            document.body.classList.remove('loading');
            document.body.style.cursor = 'default';
        });
    </script>
    <!-- Floating Action Button -->
    <button type="button" class="btn-float btn btn-primary" onclick="scrollToTop()" style="z-index: 1020;">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <script>
    // Scroll to top function
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Show/hide floating button based on scroll position
    window.addEventListener('scroll', function() {
        const floatBtn = document.querySelector('.btn-float');
        if (window.pageYOffset > 300) {
            floatBtn.style.opacity = '1';
            floatBtn.style.transform = 'scale(1)';
        } else {
            floatBtn.style.opacity = '0';
            floatBtn.style.transform = 'scale(0.8)';
        }
    });
    
    // Initialize button state
    document.addEventListener('DOMContentLoaded', function() {
        const floatBtn = document.querySelector('.btn-float');
        floatBtn.style.opacity = '0';
        floatBtn.style.transform = 'scale(0.8)';
        floatBtn.style.transition = 'all 0.3s ease';
    });
    </script>
</body>
</html>
