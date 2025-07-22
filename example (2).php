<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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

// ambil daftar produk
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Katalog Buah</title>
</head>
<body>
    <h2>Selamat datang, <?=htmlspecialchars($_SESSION['username'])?></h2>
    <h3>Katalog Buah</h3>
    <a href="logout.php">Logout</a> | <a href="transaksi.php">Lihat Transaksi</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Buah</th>
                <th>Harga (Rp)</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Beli</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?=htmlspecialchars($product['name'])?></td>
                <td><?=number_format($product['price'], 2, ',', '.')?></td>
                <td><?=htmlspecialchars($product['stock'])?></td>
                <td><?=htmlspecialchars($product['description'])?></td>
                <td>
                    <?php if ($product['stock'] > 0): ?>
                        <form method="post" action="transaksi.php">
                            <input type="hidden" name="product_id" value="<?=$product['id']?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?=$product['stock']?>" required>
                            <button type="submit">Beli</button>
                        </form>
                    <?php else: ?>
                        <em>Stok habis</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
            <tr><td colspan="5">Tidak ada produk tersedia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
