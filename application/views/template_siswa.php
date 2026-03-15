<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SiPKL - <?php echo ucfirst($content); ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/logo.png') ?>">
    <!-- Force CSS Reload -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* CSS Version: 3.0 - Enhanced Menu */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            padding-bottom: 20px;
        }
        
        .header-siswa {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-siswa .logo {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .header-siswa .user-info {
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .header-siswa .school-info {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-top: 2px;
        }
        
        .content-wrapper {
            padding: 20px;
            max-width: 100%;
        }
        
        .card-mobile {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 20px;
        }
        
        .menu-item {
            text-align: center;
            padding: 25px 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            text-decoration: none;
            color: #2c3e50;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 140px;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 20px;
        }
        
        .menu-item:hover::before {
            opacity: 1;
        }
        
        .menu-item:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 12px 35px rgba(0,0,0,0.25);
            color: #667eea;
            text-decoration: none;
            border-color: #667eea;
        }
        
        .menu-item:active {
            transform: translateY(-5px) scale(0.98);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, #f0f2ff 0%, #e6e9ff 100%);
        }
        
        .menu-item i {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }
        
        .menu-item:hover i {
            transform: scale(1.2) rotate(5deg);
            color: #4a6cf7;
        }
        
        .menu-item span {
            font-size: 1rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.4;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .menu-item:hover span {
            color: #667eea;
            transform: translateY(-2px);
        }
        
        .menu-item span {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .btn-mobile {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            margin-top: 10px;
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .info-card .info-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-card .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        
        .badge-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        
        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        
        .profile-avatar-header {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-img-header {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-placeholder-header {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .user-name-header {
            font-size: 1rem;
            font-weight: 600;
            color: white;
            line-height: 1.2;
        }
        
        .user-class-header {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.9);
            margin-top: 2px;
        }
        
        .header-menu-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-menu-icon {
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            position: relative;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .header-menu-icon:hover {
            color: rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.1);
        }
        
        .header-menu-icon.dropdown-toggle::after {
            display: none;
        }
        
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .dropdown-menu-header {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 10px;
        }
        
        .dropdown-item-header {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .dropdown-item-header i {
            width: 20px;
            color: #667eea;
        }
        
        .dropdown-item-header:hover {
            background: #f8f9fa;
            color: #667eea;
        }
        
        /* Mobile Optimization */
        @media (max-width: 576px) {
            .header-siswa {
                padding: 12px 15px;
            }
            
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin: 20px;
                padding: 12px;
                background: #f1f3f5;
                border-radius: 15px;
            }
            
            .menu-item {
                padding: 22px 16px;
                min-height: 120px;
                background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
                border: 2px solid #e9ecef;
            }
            
            .menu-item i {
                font-size: 2rem;
                margin-bottom: 12px;
            }
            
            .menu-item:hover {
                transform: translateY(-8px) scale(1.02);
                border-color: #667eea;
            }
            
            .menu-item span {
                font-size: 0.85rem;
            }
            
            .header-siswa .logo {
                font-size: 1.3rem;
            }
            
            .content-wrapper {
                padding: 15px;
            }
            
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
            
            .menu-item {
                padding: 15px 8px;
                min-height: 90px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            
            .menu-item i {
                font-size: 1.8rem;
                margin-bottom: 8px;
            }
            
            .menu-item span {
                font-size: 0.8rem;
                line-height: 1.3;
            }
            
            .card-mobile {
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .btn-mobile {
                padding: 12px;
                font-size: 1rem;
            }
            
            .info-card {
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .profile-avatar-header {
                width: 40px;
                height: 40px;
            }
            
            .user-name-header {
                font-size: 0.9rem;
            }
            
            .user-class-header {
                font-size: 0.75rem;
            }
            
            .header-menu-icon {
                font-size: 1.1rem;
                padding: 6px;
            }
        }
        
        /* Extra Small Devices (iPhone SE, etc) */
        @media (max-width: 375px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin: 12px;
                padding: 6px;
            }
            
            .menu-item {
                padding: 15px 10px;
                min-height: 90px;
            }
            
            .menu-item i {
                font-size: 1.6rem;
                margin-bottom: 8px;
            }
            
            .menu-item span {
                font-size: 0.8rem;
            }
            
            .menu-item {
                padding: 12px 5px;
                min-height: 80px;
            }
            
            .menu-item i {
                font-size: 1.6rem;
            }
            
            .menu-item span {
                font-size: 0.75rem;
            }
            
            .content-wrapper {
                padding: 12px;
            }
        }
        
        /* Touch-friendly enhancements */
        .menu-item {
            -webkit-tap-highlight-color: transparent;
            tap-highlight-color: transparent;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            position: relative;
            overflow: hidden;
        }
        
        .menu-item:active {
            transform: scale(0.95);
            background: #f8f9fa;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 15px;
        }
        
        .menu-item:hover::before {
            opacity: 1;
        }
        
        .menu-item:active {
            transform: scale(0.95);
            background: #f0f0f0;
        }
        
        /* Prevent zoom on input focus */
        input, textarea, select {
            font-size: 16px;
        }
        
        /* Animation for menu items loading */
        @keyframes menuAppear {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .menu-item {
            animation: menuAppear 0.6s ease-out forwards;
        }
        
        .menu-item:nth-child(1) { animation-delay: 0.1s; }
        .menu-item:nth-child(2) { animation-delay: 0.2s; }
        .menu-item:nth-child(3) { animation-delay: 0.3s; }
        .menu-item:nth-child(4) { animation-delay: 0.4s; }
        
        /* Better scrollbar for mobile */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-siswa">
        <?php 
        $userdata = $this->session->userdata('userdata');
        $this->db->where('user_id', $userdata['id']);
        $siswa = $this->db->get('tb_siswa')->row();
        $foto_profil = isset($userdata['foto_profil']) ? $userdata['foto_profil'] : null;
        
        // Get jumlah notifikasi (pengumuman baru dalam 7 hari terakhir)
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
        $notifikasi_count = $this->db->get('tb_pengumuman')->num_rows();
        ?>
        <div class="d-flex align-items-center">
            <?php if(uri_string() != 'siswa/view' && uri_string() != 'siswa/view/home'){ ?>
            <a href="<?php echo base_url('siswa/view') ?>" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <?php } ?>
            <div class="d-flex align-items-center flex-grow-1">
                <div class="profile-avatar-header me-3">
                    <?php if($foto_profil && file_exists('./uploads/profil/'.$foto_profil)){ ?>
                        <img src="<?php echo base_url('uploads/profil/'.$foto_profil) ?>" 
                             alt="Foto Profil" 
                             class="profile-img-header">
                    <?php } else { ?>
                        <div class="profile-placeholder-header">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php } ?>
                </div>
                <div class="flex-grow-1">
                    <div class="user-name-header"><?php echo $userdata['nama_lengkap']; ?></div>
                    <?php if($siswa){ ?>
                    <div class="user-class-header">
                        <?php echo $siswa->siswa_kelas ? $siswa->siswa_kelas : 'Kelas'; ?> | 
                        <?php echo $siswa->siswa_jurusan ? $siswa->siswa_jurusan : 'Jurusan'; ?>
                </div>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Menu Kanan -->
            <div class="header-menu-right">
                <!-- Profil -->
                <a href="<?php echo base_url('siswa/view/profile') ?>" class="header-menu-icon" title="Profil">
                    <i class="fas fa-user"></i>
                </a>
                
                <!-- Beranda -->
                <a href="<?php echo base_url('siswa/view') ?>" class="header-menu-icon" title="Beranda">
                    <i class="fas fa-home"></i>
                </a>
                
                <!-- Notifikasi -->
                <div class="dropdown">
                    <a href="#" class="header-menu-icon dropdown-toggle" data-bs-toggle="dropdown" title="Notifikasi">
                        <i class="fas fa-bell"></i>
                        <?php if($notifikasi_count > 0){ ?>
                        <span class="notification-badge"><?php echo $notifikasi_count > 9 ? '9+' : $notifikasi_count; ?></span>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-header">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php 
                        // Pengumuman baru
                        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
                        $this->db->order_by('created_at', 'DESC');
                        $this->db->limit(5);
                        $notif_pengumuman = $this->db->get('tb_pengumuman')->result();
                        
                        $total_notif = count($notif_pengumuman);
                        
                        if($total_notif > 0){
                            foreach($notif_pengumuman as $notif){ ?>
                            <li>
                                <a class="dropdown-item dropdown-item-header" href="<?php echo base_url('siswa/view/detail-pengumuman/'.$notif->pengumuman_id) ?>">
                                    <i class="fas fa-bullhorn"></i>
                                    <div>
                                        <div style="font-weight: 600;"><?php echo $notif->judul; ?></div>
                                        <small class="text-muted"><?php echo date('d M Y', strtotime($notif->created_at)); ?></small>
                                    </div>
                                </a>
                            </li>
                            <?php } 
                        } else { ?>
                        <li>
                            <a class="dropdown-item dropdown-item-header" href="#">
                                <i class="fas fa-check-circle"></i>
                                <div>Tidak ada notifikasi baru</div>
                            </a>
                        </li>
                        <?php } ?>
                        <li><hr class="dropdown-divider"></li>

                    </ul>
                </div>
                
                <!-- Menu Lainnya -->
                <div class="dropdown">
                    <a href="#" class="header-menu-icon dropdown-toggle" data-bs-toggle="dropdown" title="Menu">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-header">
                        <li>
                            <a class="dropdown-item dropdown-item-header" href="<?php echo base_url('siswa/view/profile') ?>">
                                <i class="fas fa-user"></i>
                                <div>Profil</div>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item dropdown-item-header" href="<?php echo base_url('siswa/view') ?>">
                                <i class="fas fa-home"></i>
                                <div>Beranda</div>
                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item dropdown-item-header" href="<?php echo base_url('siswa/view/kontak-pembimbing') ?>">
                                <i class="fas fa-address-book"></i>
                                <div>Kontak Pembimbing</div>
                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item dropdown-item-header" href="<?php echo base_url('siswa/view/change-password') ?>">
                                <i class="fas fa-key"></i>
                                <div>Ubah Password</div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item dropdown-item-header text-danger" href="<?php echo base_url('auth/logout') ?>" onclick="return confirm('Yakin ingin logout?')">
                                <i class="fas fa-sign-out-alt"></i>
                                <div>Logout</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-wrapper">
        <?php $this->load->view($content);?>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto dismiss alerts
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>

