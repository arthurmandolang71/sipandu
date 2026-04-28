<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIPANDU - Disdukcapil Kota Manado</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            background: white;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #636e72;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #0984e3;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #74b9ff;
        }
        .form-control {
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h1>SIPANDU</h1>
            <p>Sistem Pengaduan Terpadu Disdukcapil Manado</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2" role="alert" style="font-size: 0.85rem;">
                Username atau password salah!
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label small fw-bold">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label small fw-bold">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 shadow-sm">Masuk ke Dashboard</button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="../index.php" class="text-decoration-none small text-muted">← Kembali ke Beranda</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
