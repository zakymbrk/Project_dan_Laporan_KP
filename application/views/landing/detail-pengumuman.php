<?php 
if(!isset($pengumuman) || !$pengumuman){
    // Redirect to home or show error page
    header('Location: ' . base_url());
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pengumuman->judul; ?> - Pengumuman SiPKL SMK ITIKURIH HIBARNA</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/logo.png') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color:rgb(183, 187, 192);
            --dark-color: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .detail-container {
            padding: 100px 0 50px;
            min-height: 100vh;
        }
        
        .detail-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }
        
        .detail-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            position: relative;
        }
        
        .detail-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        }
        
        .detail-content {
            padding: 2rem;
        }
        
        .pengumuman-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .pengumuman-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
        }
        
        .meta-item i {
            margin-right: 0.5rem;
        }
        
        .pengumuman-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }
        
        .pengumuman-body p {
            margin-bottom: 1.5rem;
        }
        
        .back-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .share-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .share-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .share-btn:hover {
            transform: translateY(-3px);
        }
        
        .share-btn.whatsapp { background: #25D366; }
        .share-btn.facebook { background: #1877F2; }
        .share-btn.twitter { background: #1DA1F2; }
        
        @media (max-width: 768px) {
            .detail-container {
                padding: 80px 0 30px;
            }
            
            .detail-content {
                padding: 1.5rem;
            }
            
            .pengumuman-title {
                font-size: 1.5rem;
            }
            
            .pengumuman-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <i class="fas fa-graduation-cap me-2"></i><strong>Si</strong>PKL
            </a>
            <div class="d-flex">
                <a href="<?php echo base_url(); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- Detail Container -->
    <section class="detail-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="detail-card">
                        <div class="detail-header">
                            <h1 class="pengumuman-title"><?php echo isset($pengumuman->judul) ? $pengumuman->judul : 'Pengumuman'; ?></h1>
                            <div class="pengumuman-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo isset($pengumuman->created_at) ? date('l, d F Y', strtotime($pengumuman->created_at)) : date('l, d F Y'); ?>
                                </div>
                                <?php if(isset($pengumuman->creator_name) && $pengumuman->creator_name): ?>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <?php echo $pengumuman->creator_name; ?>
                                </div>
                                <?php endif; ?>
                                <div class="meta-item">
                                    <i class="fas fa-tag"></i>
                                    Pengumuman
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-content">
                            <div class="pengumuman-body" style="font-family: 'Segoe UI Emoji', 'Segoe UI', sans-serif; white-space: pre-wrap;">
                                <?php 
                                if(isset($pengumuman->isi) && $pengumuman->isi) {
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
                                } else {
                                    echo 'Tidak ada isi pengumuman.';
                                }
                                ?>
                            </div>
                            
                            <div class="share-section">
                                <h5 class="share-title">Bagikan Pengumuman:</h5>
                                <div class="share-buttons">
                                    <?php 
                                    $pengumuman_id = isset($pengumuman->pengumuman_id) ? $pengumuman->pengumuman_id : '1';
                                    $judul = isset($pengumuman->judul) ? $pengumuman->judul : 'Pengumuman';
                                    $url = base_url('home/detail_pengumuman/' . $pengumuman_id);
                                    ?>
                                    <a href="https://wa.me/?text=<?php echo urlencode($judul . ' - ' . $url); ?>" 
                                       target="_blank" class="share-btn whatsapp" title="Bagikan di WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>" 
                                       target="_blank" class="share-btn facebook" title="Bagikan di Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($judul . ' - ' . $url); ?>" 
                                       target="_blank" class="share-btn twitter" title="Bagikan di Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="<?php echo base_url(); ?>" class="back-btn">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>