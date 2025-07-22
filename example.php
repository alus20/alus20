<?php
// koneksi ke database
$host = 'localhost';
$db   = 'db_penjualan_buah';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username)) {
        $errors[] = "Username harus diisi.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Password konfirmasi tidak cocok.";
    }

    if (!$errors) {
        // cek username sudah ada atau belum
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Username sudah dipakai.";
        } else {
            // simpan pengguna baru
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $password_hash, $email])) {
                echo "Registrasi berhasil. <a href='login.php'>Login di sini</a>.";
                exit;
            } else {
                $errors[] = "Gagal menyimpan data pengguna.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Pengguna</title>
    <style>
        body {
            background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            padding: 32px 40px 24px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            min-width: 340px;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            text-align: center;
            color: #f76b1c;
            margin-bottom: 24px;
        }
        form label {
            display: block;
            margin-bottom: 12px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 4px;
            margin-bottom: 16px;
            font-size: 1em;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border: 1.5px solid #f76b1c;
            outline: none;
            box-shadow: 0 0 0 2px #fda08544;
        }
        button[type="submit"] {
            width: 100%;
            background: linear-gradient(90deg, #f76b1c 0%, #fad961 100%);
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 2px 8px rgba(247,107,28,0.08);
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, #fad961 0%, #f76b1c 100%);
            transform: translateY(-2px) scale(1.03);
        }
        ul {
            padding-left: 20px;
            margin-bottom: 18px;
        }
        ul li {
            color: #e74c3c;
            font-size: 0.98em;
        }
        a {
            color: #f76b1c;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Registrasi</h2>
        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialchars($error)?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" action="">
            <label>Username:
                <input type="text" name="username" value="<?=htmlspecialchars($_POST['username'] ?? '')?>" required>
            </label>
            <label>Email:
                <input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" required>
            </label>
            <label>Password:
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password:
                <input type="password" name="password_confirm" required>
            </label>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
