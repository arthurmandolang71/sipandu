<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.9) 100%), url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2000&auto=format&fit=crop') center/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--dark);
            margin-bottom: 5px;
            font-weight: 800;
        }

        .login-header p {
            color: var(--dark-muted);
            font-size: 0.95rem;
        }

        .form-floating > .form-control {
            border: 1px solid var(--light-border);
            border-radius: var(--radius-sm);
            background-color: #f8fafc;
        }
        
        .form-floating > .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-login {
            background: var(--gradient-primary);
            border: none;
            padding: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: var(--radius-sm);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
            transition: all var(--transition-bounce);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(79, 70, 229, 0.3);
        }

        .back-link {
            color: rgba(255, 255, 255, 0.7);
            transition: color var(--transition-fast);
        }
        .back-link:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="mb-3 d-inline-block bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="fas fa-user-shield fa-2x"></i>
                </div>
                <h1 class="brand-font">SIPANDU</h1>
                <p>Login Akses Staf Disdukcapil</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center py-3 border-0 bg-danger bg-opacity-10 text-danger" role="alert" style="border-radius: var(--radius-sm);">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        <strong>Login Gagal!</strong><br>
                        <span class="small">Username atau password salah.</span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
                    <label for="username"><i class="fas fa-user me-2 text-muted"></i>Username</label>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                    Masuk ke Sistem <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </form>
        </div>
        
        <div class="mt-4 text-center">
            <a href="../index.php" class="text-decoration-none small back-link">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
