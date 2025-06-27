<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BPS Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/bg-bps.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
        }
        .logo {
            width: 90px;
            display: block;
            margin: 0 auto 15px;
        }
        .password-toggle {
            cursor: pointer;
            background: transparent;
            border: none;
            outline: none;
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .error-icon {
            color: #dc3545;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container p-4 mx-auto <?php echo isset($_SESSION['login_error']) ? 'shake' : ''; ?>">
            <div class="text-center mb-3">
                <img src="images/logo_BPS.png" alt="Logo BPS" class="logo">
                <h4 class="fw-bold">Login Admin</h4>
                <p class="text-muted">Login untuk kelola Sistem Buku Tamu Digital</p>
            </div>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__headShake">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle error-icon"></i>
                        <div><?= $_SESSION['login_error'] ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form action="proses_login_admin.php" method="POST" id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="username" required
                            value="<?= isset($_SESSION['login_username']) ? htmlspecialchars($_SESSION['login_username']) : ''; ?>">
                        <?php unset($_SESSION['login_username']); ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="passwordInput" required>
                        <button type="button" class="input-group-text password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('passwordInput');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Remove shake class after animation
        const loginContainer = document.querySelector('.login-container');
        if (loginContainer.classList.contains('shake')) {
            setTimeout(() => {
                loginContainer.classList.remove('shake');
            }, 500);
        }

        // Auto focus
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.querySelector('input[name="username"]');
            const passwordInput = document.getElementById('passwordInput');
            
            if (!usernameInput.value) {
                usernameInput.focus();
            } else {
                passwordInput.focus();
            }
        });
    </script>
</body>
</html>