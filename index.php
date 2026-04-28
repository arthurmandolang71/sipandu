<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPANDU - Dinas Kependudukan dan Pencatatan Sipil Manado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0984e3;
            --dark: #2d3436;
        }
        body { font-family: 'Outfit', sans-serif; background-color: #ffffff; color: var(--dark); }
        .hero-section {
            background: linear-gradient(135deg, #0984e3 0%, #6c5ce7 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
            border-radius: 0 0 50px 50px;
        }
        .search-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .btn-check-status {
            background-color: var(--primary);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 12px;
            color: white;
        }
        .btn-check-status:hover {
            background-color: #0773c5;
            color: white;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 600;
        }
        .footer {
            background: #f8f9fa;
            padding: 50px 0;
            margin-top: 100px;
        }
    </style>
</head>
<body>

    <!-- Header / Nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100">
        <div class="container py-3">
            <a class="navbar-brand fw-bold fs-3" href="index.php">SIPANDU</a>
            <a href="auth/login.php" class="btn btn-outline-light btn-sm px-4 rounded-pill">Login Staff</a>
        </div>
    </nav>

    <!-- Hero -->
    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Lacak Pengaduan Anda</h1>
            <p class="lead mb-0 opacity-75">Sistem Pengaduan Terpadu Disdukcapil Kota Manado</p>
        </div>
    </header>

    <!-- Search Section -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="search-box">
                    <form action="index.php" method="GET">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input type="text" name="kode" class="form-control form-control-lg border-0 bg-light rounded-3" 
                                       placeholder="Masukkan Kode Pengaduan (Contoh: PENG-2026...)" 
                                       value="<?php echo $_GET['kode'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-check-status w-100 shadow-sm">
                                    <i class="fas fa-search me-2"></i> Periksa Status
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
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <?php if ($res) : ?>
                    <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                <span class="text-muted small text-uppercase fw-bold ls-1">Informasi Pengaduan</span>
                                <h2 class="fw-bold mb-0"><?php echo $res['id_pengaduan']; ?></h2>
                            </div>
                            <div class="bg-<?php 
                                echo ($res['nama_status'] == 'Selesai' ? 'success' : ($res['nama_status'] == 'Diproses' ? 'info' : 'warning')); 
                                ?> bg-opacity-10 text-<?php 
                                echo ($res['nama_status'] == 'Selesai' ? 'success' : ($res['nama_status'] == 'Diproses' ? 'info' : 'warning')); 
                                ?> status-badge">
                                <i class="fas fa-circle fa-xs me-2"></i> <?php echo strtoupper($res['nama_status']); ?>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100">
                                    <p class="text-muted small mb-1">Nama Masyarakat</p>
                                    <p class="fw-bold mb-3"><?php echo $res['nama_masyarakat']; ?></p>
                                    
                                    <p class="text-muted small mb-1">Bidang Penanganan</p>
                                    <p class="fw-bold mb-3"><?php echo $res['nama_bidang']; ?></p>

                                    <p class="text-muted small mb-1">Tanggal Pengaduan</p>
                                    <p class="fw-bold mb-0"><?php echo date('d F Y, H:i', strtotime($res['tanggal_jam_pengaduan'])); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100">
                                    <p class="text-muted small mb-1">Isi Pengaduan</p>
                                    <p class="mb-0 italic"><?php echo nl2br($res['uraian_pengaduan']); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($res['nama_status'] == 'Selesai') : ?>
                        <div class="mt-4 p-4 border border-success border-dashed rounded-4">
                            <div class="d-flex align-items-center mb-2 text-success">
                                <i class="fas fa-check-circle fa-lg me-2"></i>
                                <h5 class="fw-bold mb-0">Solusi / Penyelesaian</h5>
                            </div>
                            <p class="mb-2"><?php echo nl2br($res['uraian_penyelesaian']); ?></p>
                            <small class="text-muted">Selesai pada: <?php echo date('d M Y, H:i', strtotime($res['tanggal_jam_selesai'])); ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger text-center p-5 rounded-4 border-0">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h4 class="fw-bold">Kode Tidak Ditemukan</h4>
                        <p class="mb-0">Mohon periksa kembali kode pengaduan yang Anda masukkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="fw-bold mb-1">Dinas Kependudukan dan Pencatatan Sipil Kota Manado</p>
            <p class="text-muted small mb-0">&copy; 2026 SIPANDU. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
