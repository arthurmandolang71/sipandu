<?php
// operator/includes/header.php
session_start();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #1e272e; color: white; padding-top: 20px; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); font-weight: 500; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; text-align: center; }
        .main-content { padding: 30px; }
        .navbar-top { background: white; border-bottom: 1px solid #dee2e6; padding: 15px 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .btn-success { background-color: #05c46b; border: none; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow">
            <div class="position-sticky">
                <div class="px-4 mb-4">
                    <h4 class="fw-bold text-white mb-0">SIPANDU</h4>
                    <p class="small text-white-50">Operator Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tasks"></i> Tugas Pengaduan
                        </a>
                    </li>
                    <hr class="mx-3 text-white-50">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../auth/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </li>
                </ul>
                <div class="mt-5 px-4 text-white-50">
                    <small>Bidang:</small><br>
                    <span class="small fw-bold text-white"><?php echo $bidang_name; ?></span>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-0">
            <header class="navbar-top d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-600">Disdukcapil Kota Manado</h5>
                <div class="user-info d-flex align-items-center">
                    <span class="me-3 small fw-500"><?php echo $_SESSION['nama_user']; ?></span>
                    <i class="fas fa-user-circle fa-lg text-secondary"></i>
                </div>
            </header>
            <div class="main-content">
