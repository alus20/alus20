<?php
session_start();

// Koneksi database
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
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tentang Kami - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- Top bar akun & login/logout -->
<div style="width:100vw; position:relative; min-height:0;">
    <div style="position:absolute; left:0; top:0; padding:18px 32px; z-index:20; font-size:1.45em; font-weight:700; color:#f76b1c;">
        <?php if (isset($_SESSION['user_id'])): ?>
            👤 <?=htmlspecialchars($_SESSION['nama_pelanggan'] ?? $_SESSION['username'] ?? 'Akun')?>
        <?php endif; ?>
    </div>
    <div style="position:absolute; right:0; top:0; padding:18px 32px; z-index:20; font-size:1.45em; font-weight:700;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" style="color:#f76b1c; text-decoration:none; font-weight:700;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color:#f76b1c; text-decoration:none; font-weight:700;">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- Header & Navbar -->
<header style="margin-top:48px;">
    <h1>Buah-Buahan Baper</h1>
    <nav style="display:flex; align-items:center; justify-content:center; max-width:1000px; margin:auto; min-height:48px; gap:32px;">
        <a href="index.php">Home</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="daftar_buah.php">Daftar Buah</a>
        <a href="keranjang.php">Keranjang</a>
    </nav>
</header>

<!-- Konten Tentang Kami -->
<div class="main-content" style="flex:1; padding:40px 32px 0 32px;">
  <div class="text" style="background:#fff; border-radius:22px; box-shadow:0 6px 32px rgba(44,62,80,0.13); padding:56px 48px; max-width:1000px; margin:auto; margin-bottom:44px;">
    <h2 style="font-size:2.8em; font-weight:900; color:#f76b1c; margin-bottom:22px; text-align:center; text-shadow:0 4px 18px #ffe0c3;">Tentang Kami</h2>
    <div style="font-size:1.2em; color:#333; line-height:1.8; text-align:justify;">
      <p>Buah-Buahan Baper adalah toko buah online yang menyediakan berbagai macam buah segar berkualitas dengan harga terjangkau.
      Kami berdiri sejak 2025 dengan visi menjadi penyedia buah segar nomor satu di kota Anda. Kami berkomitmen untuk memberikan layanan terbaik, produk berkualitas, serta pengalaman belanja yang menyenangkan bagi setiap pelanggan.</p>
      <p>Kami percaya bahwa konsumsi buah segar setiap hari dapat meningkatkan kesehatan dan kebahagiaan. Untuk itu, Buah-Buahan Baper selalu menjaga kualitas, kebersihan, dan kecepatan pengiriman agar buah tetap segar sampai di tangan Anda.</p>
      <p>Terima kasih telah mempercayai kami sebagai pilihan Anda. Selamat berbelanja dan nikmati kesegaran buah dari Buah-Buahan Baper!</p>
    </div>
  </div>
</div>

<footer>
    <p>&copy; <?=date('Y')?> Buah-Buahan Baper</p>
</footer>

</body>
</html>
