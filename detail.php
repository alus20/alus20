<?php
require_once 'koneksi.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM buah WHERE id = ?");
$stmt->execute([$id]);
$buah = $stmt->fetch();
if (!$buah) {
    echo "Data buah tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buah - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding: 32px 28px; text-align: center; }
        .detail-container img { width: 160px; height: 160px; object-fit: cover; border-radius: 50%; background: #fff3e0; margin-bottom: 18px; }
        .detail-container h2 { color: #f76b1c; margin-bottom: 10px; }
        .detail-container .harga { color: #e67e22; font-weight: bold; font-size: 1.2em; margin: 12px 0; }
        .detail-container p { color: #555; font-size: 1.05em; }
        .detail-container form { margin-top: 18px; }
        .detail-container button { background: linear-gradient(90deg, #f76b1c 0%, #fad961 100%); color: #fff; border: none; padding: 10px 0; border-radius: 8px; font-size: 1em; font-weight: bold; cursor: pointer; width: 100%; transition: background 0.2s, transform 0.1s; }
        .detail-container button:hover { background: linear-gradient(90deg, #fad961 0%, #f76b1c 100%); transform: scale(1.04); }
        .detail-container a { display: inline-block; margin-top: 18px; color: #f76b1c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="detail-container">
        <img src="images/<?=htmlspecialchars($buah['gambar'] ?? 'placeholder.png')?>" alt="<?=htmlspecialchars($buah['nama'])?>">
        <h2><?=htmlspecialchars($buah['nama'])?></h2>
        <div class="harga">Rp <?=number_format($buah['harga'],0,',','.')?></div>
        <p><?=htmlspecialchars($buah['deskripsi'])?></p>
        <?php $is_admin = $_SESSION['is_admin'] ?? 0; ?>
        <?php if ($is_admin): ?>
            <button type="button" disabled>Admin tidak bisa menambah</button>
        <?php else: ?>
        <form method="post" action="keranjang.php">
            <input type="hidden" name="id_buah" value="<?=$buah['id']?>">
            <button type="submit">Tambah ke Keranjang</button>
        </form>
        <?php endif; ?>
        <a href="index.php">&larr; Kembali ke Daftar Buah</a>
    </div>
</body>
</html>
