<?php
session_start();
require_once 'koneksi.php';

$errors = [];

// Buat CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Handle POST jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Token tidak valid. Silakan refresh halaman.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Validasi form
        if (strlen($username) < 3) {
            $errors[] = "Username minimal 3 karakter.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter.";
        }
        if ($password !== $password_confirm) {
            $errors[] = "Konfirmasi password tidak cocok.";
        }

        // Cek username duplikat
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "Username sudah digunakan.";
            }
        }

        // Insert ke DB jika semua valid
        if (empty($errors)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $password_hash, $email])) {
                header('Location: register.php?register=success');
                exit;
            } else {
                $errors[] = "Gagal menyimpan data pengguna.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="index.css">
    <style>
        @media (max-width: 500px) {
            .register-container {
                max-width: 98vw;
                padding: 18px 6vw 18px 6vw;
                margin: 30px auto 0 auto;
            }
            .register-container h2 {
                font-size: 1.3em;
            }
        }
        .input-error {
            border-color: #e74c3c !important;
            background: #ffeaea !important;
        }
        .input-error-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #e74c3c;
            font-size: 1.2em;
            pointer-events: none;
        }
        .input-group {
            position: relative;
            width: 100%;
        }
        .spinner {
            display: none;
            margin: 0 auto 18px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ff4e50;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: url('background buah.jpg') center center/cover no-repeat fixed;
            min-height: 100vh;
        }
        .register-container {
            max-width: 390px;
            margin: 70px auto 0 auto;
            background: rgba(255,255,255,0.98);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(255, 180, 80, 0.15);
            padding: 36px 32px 36px 32px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .register-container h2 {
            text-align: center;
            color: #ff4e50;
            margin-bottom: 28px;
            letter-spacing: 1px;
            font-size: 2em;
            font-weight: 800;
            text-shadow: 0 2px 12px #fff7e6;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .register-container h2::before {
            content: '\1F34E';
            font-size: 1.1em;
            margin-right: 6px;
        }
        .register-container form {
            width: 100%;
        }
        .register-container label {
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: #ff4e50;
            font-weight: 600;
            margin-bottom: 18px;
            width: 100%;
        }
        .register-container input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #f9d423;
            border-radius: 10px;
            font-size: 1.05em;
            background: #fffbe6;
            color: #ff4e50;
            margin: 0;
            box-sizing: border-box;
        }
        .register-container button {
            width: 100%;
            background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%);
            color: #fff;
            border: none;
            padding: 13px 0;
            border-radius: 12px;
            font-size: 1.13em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 2px 8px #ffe0c3;
        }
        .register-container button:hover {
            background: linear-gradient(90deg, #f9d423 0%, #ff4e50 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .register-container ul {
            padding-left: 20px;
            margin-bottom: 18px;
        }
        .register-container ul li {
            color: #e74c3c;
            font-size: 0.98em;
        }
        a {
            color: #ff4e50;
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register Pengguna</h2>
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" action="" id="registerForm" autocomplete="off" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
            <div class="input-group">
                <label>Username:
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required autofocus maxlength="32">
                    <span class="input-error-icon" id="icon-username" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="input-group">
                <label>Email:
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required maxlength="64">
                    <span class="input-error-icon" id="icon-email" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="input-group">
                <label>Password:
                    <input type="password" name="password" id="password" required minlength="6">
                    <span class="input-error-icon" id="icon-password" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="input-group">
                <label>Konfirmasi Password:
                    <input type="password" name="password_confirm" id="password_confirm" required minlength="6">
                    <span class="input-error-icon" id="icon-password2" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="spinner" id="spinner"></div>
            <button type="submit">Daftar</button>
        </form>
        <p style="text-align:center;margin-top:18px;">Sudah punya akun? <a href="login.php">Login</a></p>
        <script>
        const form = document.getElementById('registerForm');
        const spinner = document.getElementById('spinner');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const password2 = document.getElementById('password_confirm');
        const iconUsername = document.getElementById('icon-username');
        const iconEmail = document.getElementById('icon-email');
        const iconPassword = document.getElementById('icon-password');
        const iconPassword2 = document.getElementById('icon-password2');

        form.addEventListener('submit', function(e) {
            let valid = true;

            username.classList.remove('input-error');
            email.classList.remove('input-error');
            password.classList.remove('input-error');
            password2.classList.remove('input-error');
            iconUsername.style.display = 'none';
            iconEmail.style.display = 'none';
            iconPassword.style.display = 'none';
            iconPassword2.style.display = 'none';

            if (username.value.trim().length < 3) {
                username.classList.add('input-error');
                iconUsername.style.display = 'block';
                valid = false;
            }
            if (!/^\S+@\S+\.\S+$/.test(email.value.trim())) {
                email.classList.add('input-error');
                iconEmail.style.display = 'block';
                valid = false;
            }
            if (password.value.length < 6) {
                password.classList.add('input-error');
                iconPassword.style.display = 'block';
                valid = false;
            }
            if (password.value !== password2.value) {
                password2.classList.add('input-error');
                iconPassword2.style.display = 'block';
                valid = false;
            }
            if (!valid) {
                e.preventDefault();
                return;
            }
            spinner.style.display = 'block';
        });

        if (window.location.search.includes('register=success')) {
            setTimeout(function() {
                alert('Registrasi berhasil! Silakan login.');
            }, 200);
        }
        </script>
    </div>
</body>
</html>
