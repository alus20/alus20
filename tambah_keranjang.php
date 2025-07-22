<?php
session_start();
require_once 'koneksi.php';
if (!isset($_POST['id_buah'])) {
    header('Location: index.php');
    exit;
}
$id_buah = intval($_POST['id_buah']);
if ($id_buah < 1) {
    header('Location: index.php');
    exit;
}
// Cek stok buah
$stmt = $pdo->prepare("SELECT stok FROM buah WHERE id = ?");
$stmt->execute([$id_buah]);
$row = $stmt->fetch();
if (!$row || (int)$row['stok'] <= 0) {
    $_SESSION['keranjang_berhasil'] = false;
    $_SESSION['keranjang_error'] = 'Stok buah kosong, tidak bisa ditambahkan ke keranjang.';
    header('Location: index.php');
    exit;
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_SESSION['cart'][$id_buah])) {
    $_SESSION['cart'][$id_buah]++;
} else {
    $_SESSION['cart'][$id_buah] = 1;
}
$_SESSION['keranjang_berhasil'] = true;
unset($_SESSION['keranjang_error']);
header('Location: index.php');
exit;
