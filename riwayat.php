<?php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT p.*, 
           (SELECT AVG(rating) FROM pembelian_detail WHERE pembelian_id = p.id AND rating IS NOT NULL) AS rata_rating 
    FROM pembelian p 
    WHERE user_id = ? 
    ORDER BY tanggal DESC
");
$stmt->execute([$user_id]);
$pembelian = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .riwayat-container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding: 32px 28px; }
        .riwayat-container h2 { text-align: center; color: #f76b1c; margin-bottom: 22px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #eee; text-align: center; }
        th { background: #fdf6e3; color: #f76b1c; }
        .total { font-weight: bold; color: #e67e22; }
        .detail-link { color: #f76b1c; text-decoration: none; }
        .detail-link:hover { text-decoration: underline; }
        .star { color: gold; }
    </style>
</head>
<body>
    <div class="riwayat-container">
        <h2>Riwayat Pembelian</h2>
        <?php if ($pembelian): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Rating</th>
                    <th>Detail</th>
                </tr>
                <?php foreach ($pembelian as $row): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=date('d-m-Y H:i', strtotime($row['tanggal']))?></td>
                        <td>Rp <?=number_format($row['total'],0,',','.')?></td>
                        <td>
                            <?php if ($row['rata_rating']): ?>
                                <?php
                                $bintang = round($row['rata_rating']);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $bintang ? '<span class="star">&#9733;</span>' : '<span class="star" style="color:#ccc;">&#9733;</span>';
                                }
                                ?>
                            <?php else: ?>
                                Belum ada rating
                            <?php endif; ?>
                        </td>
                        <td><a class="detail-link" href="riwayat_detail.php?id=<?=$row['id']?>">Lihat</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center;">Belum ada pembelian.</p>
        <?php endif; ?>
        <div style="text-align:center;margin-top:18px;">
            <a href="index.php">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
