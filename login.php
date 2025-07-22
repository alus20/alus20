<?php
session_start();
require_once 'koneksi.php';
$errors = [];
// Rate limiting: max 5 failed attempts per 10 minutes
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}
// Clean up old attempts
$_SESSION['login_attempts'] = array_filter(
    $_SESSION['login_attempts'],
    function($ts) { return $ts > time() - 600; }
);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Token tidak valid. Silakan refresh halaman.';
    } elseif (count($_SESSION['login_attempts']) >= 5) {
        $errors[] = 'Terlalu banyak percobaan login gagal. Coba lagi dalam beberapa menit.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($password)) {
            $errors[] = "Username dan password wajib diisi.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
                $_SESSION['nama_pelanggan'] = isset($user['nama']) && $user['nama'] ? $user['nama'] : $user['username'];
                $_SESSION['login_attempts'] = [];
                if ($_SESSION['is_admin']) {
                    header('Location: admin.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $errors[] = "Username atau password salah.";
                $_SESSION['login_attempts'][] = time();
            }
        }
    }
}
// Generate CSRF token for form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="index.css">
    <style>
        @media (max-width: 500px) {
            .login-container {
                max-width: 98vw;
                padding: 18px 6vw 18px 6vw;
                margin: 30px auto 0 auto;
            }
            .login-container h2 {
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
        .login-container {
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
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .login-container h2 {
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
        .login-container h2::before {
            content: '\1F34E'; /* emoji apel merah */
            font-size: 1.1em;
            margin-right: 6px;
        }
        .login-container form {
            width: 100%;
        }
        .login-container label {
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: #ff4e50;
            font-weight: 600;
            margin-bottom: 18px;
            width: 100%;
        }
        .login-container input {
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
        .login-container button {
            width: 100%;
        }
        .login-container button {
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
        .login-container button:hover {
            background: linear-gradient(90deg, #f9d423 0%, #ff4e50 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .login-container ul {
            padding-left: 20px;
            margin-bottom: 18px;
        }
        .login-container ul li {
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
    <div class="login-container">
        <h2>Login Pengguna</h2>
        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" action="" autocomplete="off" id="loginForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8')?>">
            <div class="input-group">
                <label>Username:
                    <input type="text" name="username" id="username" required autofocus maxlength="32">
                    <span class="input-error-icon" id="icon-username" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="input-group">
                <label>Password:
                    <input type="password" name="password" id="password" required maxlength="64">
                    <span class="input-error-icon" id="icon-password" style="display:none;">&#9888;</span>
                </label>
            </div>
            <div class="spinner" id="spinner"></div>
            <button type="submit">Login</button>
        </form>
</div>
<script>
// Spinner & client-side validation with error icon
const form = document.getElementById('loginForm');
const spinner = document.getElementById('spinner');
const username = document.getElementById('username');
const password = document.getElementById('password');
const iconUsername = document.getElementById('icon-username');
const iconPassword = document.getElementById('icon-password');

form.addEventListener('submit', function(e) {
    let valid = true;
    // Reset
    username.classList.remove('input-error');
    password.classList.remove('input-error');
    iconUsername.style.display = 'none';
    iconPassword.style.display = 'none';

    if (username.value.trim().length < 3) {
        username.classList.add('input-error');
        iconUsername.style.display = 'block';
        valid = false;
    }
    if (password.value.length < 6) {
        password.classList.add('input-error');
        iconPassword.style.display = 'block';
        valid = false;
    }
    if (!valid) {
        e.preventDefault();
        return;
    }
    spinner.style.display = 'block';
});
</script>
        <p style="text-align:center;margin-top:18px;">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>
