<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pendataan Siswa Prakerin - SiPKL</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/logo-sekolah.png'); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%),
                        url('<?php echo base_url('assets/img/sekolah.jpg') ?>') center center no-repeat;
            background-size: cover;
            background-attachment: fixed;
            position: relative;
            padding: 20px;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?php echo base_url('assets/img/sekolah.jpg') ?>') center center no-repeat;
            background-size: cover;
            background-attachment: fixed;
            z-index: 0;
            opacity: 0.3;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.7) 0%, rgba(118, 75, 162, 0.7) 100%);
            z-index: 1;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-sekolah {
            width: 120px;
            height: 120px;
            margin: 0 auto 25px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            padding: 10px;
        }
        
        .logo-sekolah img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .logo-sekolah i {
            font-size: 4rem;
            color: #667eea;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-title h2 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        
        .login-title p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }
        
        .login-title .subtitle {
            color: #999;
            font-size: 0.85rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .form-label i {
            margin-right: 8px;
            color: #667eea;
            width: 18px;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border-left: 4px solid #667eea;
        }
        
        .info-box h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-box p {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.6;
            margin: 0;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .logo-sekolah {
                width: 100px;
                height: 100px;
            }
            
            .login-title h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="text-center mb-4">
                <div class="logo-sekolah">
                    <img src="<?php echo base_url('assets/img/logo-sekolah.png') ?>" 
                         alt="Logo SMK ITIKURIH HIBARNA" 
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-graduation-cap\'></i>';">
                </div>
                <div class="login-title">
                    <h2><strong>Si</strong>PKL</h2>
                    <p>Sistem Pendataan Siswa Prakerin</p>
                    <p class="subtitle">SMK ITIKURIH HIBARNA</p>
                </div>
            </div>
            
            <?php if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo validation_errors(); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } ?>
            
            <?php if(!empty($this->session->flashdata('message'))){ ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo $this->session->flashdata('message'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } ?>
            
            <form action="<?php echo base_url('auth/do_login'); ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i>Username
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           placeholder="Masukkan Username" 
                           required 
                           autofocus>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan Password" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-arrow-right"></i>
                    Masuk
                </button>
            </form>
            
            <div class="info-box">
                <h6>
                    <i class="fas fa-info-circle"></i>
                    Informasi Sistem
                </h6>
                <p>
                    Sistem ini digunakan untuk mengelola data Praktik Kerja Lapangan (PKL) siswa SMK ITIKURIH HIBARNA. 
                    Silakan hubungi Hubin jika Anda mengalami kesulitan dalam mengakses sistem.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
