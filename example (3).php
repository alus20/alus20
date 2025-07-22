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

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $user_id = $_SESSION['user_id'];

    // Validasi input
    if ($product_id <= 0 || $quantity <= 0) {
        $errors[] = "Data produk atau jumlah beli tidak valid.";
    } else {
        // cek produk dan stok
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            $errors[] = "Produk tidak ditemukan.";
        } elseif ($quantity > $product['stock']) {
            $errors[] = "Jumlah beli melebihi stok yang tersedia.";
        } else {
            // Hitung total harga
            $total_price = $product['price'] * $quantity;

            try {
                // mulai transaksi db
                $pdo->beginTransaction();

                // simpan transaksi
                $stmt = $pdo->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $product_id, $quantity, $total_price]);

                // update stok produk
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$quantity, $product_id]);

                $pdo->commit();

                $success = "Transaksi berhasil! Anda membeli $quantity buah " . htmlspecialchars($product['name']) . ".";
            } catch (Exception $e) {
                $pdo->rollBack();
                $errors[] = "Terjadi kesalahan saat memproses transaksi: " . $e->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>
