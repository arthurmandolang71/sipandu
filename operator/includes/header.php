<?php
// operator/includes/header.php
session_start();
ob_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'operator') {
    header("Location: ../auth/login.php");
    exit;
}
require_once '../config/database.php';

// Get Bidang Name
$stmtB = $pdo->prepare("SELECT nama_bidang FROM tb_bidang WHERE id_bidang = ?");
$stmtB->execute([$_SESSION['id_bidang']]);
$bidang_name = $stmtB->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator SIPANDU - Disdukcapil Manado</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        .navbar-top {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--light-border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .main-content { padding: 2rem; }
        .sidebar { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
        .nav-link.active { background: linear-gradient(135deg, var(--secondary) 0%, #0284c7 100%) !important; box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4) !important; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse min-vh-100 position-fixed">
            <div class="position-sticky pt-4">
                <div class="px-4 mb-5">
                    <div class="d-flex align-items-center text-white">
                        <i class="fas fa-headset fa-2x me-3 text-info"></i>
                        <div>
                            <h4 class="brand-font mb-0 text-white">SIPANDU</h4>
                            <p class="small text-white-50 mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">OPERATOR PANEL</p>
                        </div>
                    </div>
                </div>
                
                <p class="px-4 text-white-50 small fw-bold text-uppercase ls-1 mb-2" style="font-size: 0.7rem;">Menu Utama</p>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a class="nav-link text-white-50 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-tasks"></i> Tugas Pengaduan
                        </a>
                    </li>
                </ul>

                <hr class="mx-4 text-white-50 border-secondary">
                <ul class="nav flex-column mb-5">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../auth/logout.php" style="background: rgba(244, 63, 94, 0.1);">
                            <i class="fas fa-sign-out-alt text-danger"></i> Keluar Sistem
                        </a>
                    </li>
                </ul>

                <!-- Bidang Info Box -->
                <div class="mx-3 mt-auto">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10 text-center">
                        <span class="small text-white-50 d-block mb-1">Menangani Bidang:</span>
                        <span class="small fw-bold text-info"><?php echo $bidang_name; ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content (Offset for fixed sidebar) -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-0" style="margin-left: 16.666667%;">
            <header class="navbar-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 brand-font text-info">Operator Workspace</h5>
                </div>
                <div class="user-info d-flex align-items-center bg-light px-3 py-2 rounded-pill border border-light-border">
                    <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <span class="small fw-600 text-dark"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </header>
            <div class="main-content">
