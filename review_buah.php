<?php
require_once 'koneksi.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buah = (int)($_POST['id_buah'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');
    $user_id = $_SESSION['user_id'];
    // Cek validasi rating
    if ($id_buah && $rating >= 1 && $rating <= 5) {
        // Cek apakah user pernah beli buah ini
        $stmt = $pdo->prepare("SELECT 1 FROM pembelian_detail pd JOIN pembelian p ON pd.pembelian_id = p.id WHERE pd.buah_id = ? AND p.user_id = ? LIMIT 1");
        $stmt->execute([$id_buah, $user_id]);
        if ($stmt->fetch()) {
            // Insert/update review
            $stmt2 = $pdo->prepare("INSERT INTO review_buah (buah_id, user_id, rating, review, tanggal) VALUES (?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE rating=VALUES(rating), review=VALUES(review), tanggal=NOW()");
            $stmt2->execute([$id_buah, $user_id, $rating, $review]);
        }
    }
}
header('Location: daftar_buah.php');
exit;
