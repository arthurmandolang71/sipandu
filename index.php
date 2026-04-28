<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Lacak Pengaduan</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(14, 165, 233, 0.95) 100%), url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2000&auto=format&fit=crop') center/cover;
            color: white;
            padding: 120px 0 160px;
            position: relative;
            overflow: hidden;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
        }
        .hero-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 2px, transparent 2px);
            background-size: 30px 30px;
            opacity: 0.5;
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .search-box-wrapper {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .search-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .footer {
            background: var(--light);
            padding: 60px 0 40px;
            margin-top: 100px;
            border-top: 1px solid var(--light-border);
        }
    </style>
</head>
<body>

    <!-- Nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 100;">
        <div class="container py-3">
            <a class="navbar-brand brand-font fs-3 text-white" href="index.php">
                <i class="fas fa-shield-alt me-2"></i>SIPANDU
            </a>
            <a href="auth/login.php" class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill fw-bold">Login Staff <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </nav>

    <!-- Hero -->
    <header class="hero-section text-center">
        <div class="hero-pattern"></div>
        <div class="container hero-content">
            <span class="badge bg-white bg-opacity-20 text-white mb-3 px-3 py-2 rounded-pill ls-1" style="backdrop-filter: blur(5px);">DISDUKCAPIL KOTA MANADO</span>
            <h1 class="display-3 mb-3" style="font-weight: 800; letter-spacing: -1px;">Lacak Pengaduan Anda</h1>
            <p class="lead mb-0 fw-light opacity-75 mx-auto" style="max-width: 600px;">Sistem terpadu untuk memantau status penyelesaian aduan Anda secara real-time dan transparan.</p>
        </div>
    </header>

    <!-- Search Section -->
    <div class="container search-box-wrapper">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                <div class="search-box">
                    <form action="index.php" method="GET">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <input type="text" name="kode" class="form-control form-control-lg border-0 bg-light py-3 px-4" 
                                       style="border-radius: 12px; font-size: 1.1rem;"
                                       placeholder="Kode (Cth: PENG-2026...)" 
                                       value="<?php echo $_GET['kode'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius: 12px; font-size: 1.1rem;">
                                    <i class="fas fa-search me-2"></i> Lacak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Result Section -->
        <?php 
        if (isset($_GET['kode'])) : 
            require_once 'config/database.php';
            $kode = $_GET['kode'];
            $stmt = $pdo->prepare("SELECT p.*, b.nama_bidang, s.nama_status 
                                  FROM tb_pengaduan p 
                                  JOIN tb_bidang b ON p.id_bidang = b.id_bidang 
                                  JOIN tb_status s ON p.id_status = s.id_status 
                                  WHERE p.id_pengaduan = ?");
            $stmt->execute([$kode]);
            $res = $stmt->fetch();
        ?>
        <div class="row justify-content-center mt-5 pt-3">
            <div class="col-md-10">
                <?php if ($res) : ?>
                    <!-- Animated fade-in card -->
                    <div class="card p-4 p-md-5" style="animation: slideUp 0.5s ease-out forwards; opacity: 0; transform: translateY(20px);">
                        <style>@keyframes slideUp { to { opacity: 1; transform: translateY(0); } }</style>
                        
                        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3 pb-4 border-bottom">
                            <div>
                                <span class="text-muted small text-uppercase fw-bold ls-1 d-block mb-1">Status Laporan</span>
                                <h2 class="brand-font text-primary mb-0"><?php echo $res['id_pengaduan']; ?></h2>
                            </div>
                            
                            <?php 
                                $statusClass = '';
                                if($res['nama_status'] == 'Selesai') $statusClass = 'success';
                                elseif($res['nama_status'] == 'Diproses') $statusClass = 'info';
                                else $statusClass = 'warning';
                            ?>
                            <div class="badge badge-soft-<?php echo $statusClass; ?> px-4 py-2 fs-6">
                                <i class="fas fa-circle fa-xs me-2 animation-pulse"></i> <?php echo strtoupper($res['nama_status']); ?>
                            </div>
                        </div>

                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="bg-light p-4 rounded-4 h-100 border border-light-border">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3">
                                            <i class="fas fa-user text-primary fa-fw"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted small mb-0 fw-600 text-uppercase ls-1">Pelapor</p>
                                            <p class="fw-bold mb-0 fs-5 text-dark"><?php echo $res['nama_masyarakat']; ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3">
                                            <i class="fas fa-building text-info fa-fw"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted small mb-0 fw-600 text-uppercase ls-1">Bidang Penanganan</p>
                                            <p class="fw-bold mb-0 text-dark"><?php echo $res['nama_bidang']; ?></p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3">
                                            <i class="fas fa-clock text-warning fa-fw"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted small mb-0 fw-600 text-uppercase ls-1">Waktu Laporan</p>
                                            <p class="fw-bold mb-0 text-dark"><?php echo date('d F Y, H:i', strtotime($res['tanggal_jam_pengaduan'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-4 rounded-4 h-100 border border-light-border">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-comment-alt text-secondary me-2"></i>
                                        <p class="text-muted small mb-0 fw-600 text-uppercase ls-1">Isi Pengaduan</p>
                                    </div>
                                    <p class="mb-0 text-dark lh-lg"><?php echo nl2br($res['uraian_pengaduan']); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($res['nama_status'] == 'Selesai') : ?>
                        <div class="mt-5 p-5 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 position-relative overflow-hidden">
                            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                                <i class="fas fa-check-circle fa-10x"></i>
                            </div>
                            <div class="position-relative z-index-1">
                                <div class="d-flex align-items-center mb-3 text-success">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <h4 class="brand-font mb-0">Solusi / Penyelesaian</h4>
                                </div>
                                <p class="mb-3 fs-5 text-dark lh-base"><?php echo nl2br($res['uraian_penyelesaian']); ?></p>
                                <hr class="border-success opacity-25">
                                <p class="text-muted small mb-0 fw-500">
                                    <i class="fas fa-calendar-check me-1"></i> Diselesaikan pada: <?php echo date('d F Y, H:i', strtotime($res['tanggal_jam_selesai'])); ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="card border-0 p-5 text-center" style="animation: slideUp 0.5s ease-out forwards; opacity: 0;">
                        <div class="mx-auto bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <h3 class="brand-font mb-2">Kode Tidak Ditemukan</h3>
                        <p class="text-muted mb-0">Maaf, kami tidak dapat menemukan pengaduan dengan kode <strong><?php echo htmlspecialchars($kode); ?></strong>.<br>Mohon periksa kembali kode yang Anda masukkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <h5 class="brand-font mb-2">Disdukcapil Kota Manado</h5>
            <p class="text-muted small mb-0">&copy; 2026 SIPANDU - Sistem Pengaduan Terpadu. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
