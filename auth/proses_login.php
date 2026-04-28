<?php
// auth/proses_login.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id']   = $user['id_user'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['nama_user'] = $user['nama_user'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['id_bidang'] = $user['id_bidang'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../operator/index.php");
            }
            exit;
        } else {
            // Login fail
            header("Location: login.php?error=invalid");
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit;
}
?>
