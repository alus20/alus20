<?php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Proses update metode pembayaran jika form disubmit
$id = intval($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metode_pembayaran'])) {
    $metode = $_POST['metode_pembayaran'];
    $allowed = ['Transfer Bank', 'E-Wallet', 'COD'];
    if (in_array($metode, $allowed)) {
        $stmt = $pdo->prepare("UPDATE pembelian SET metode_pembayaran = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$metode, $id, $_SESSION['user_id']]);
    }
}
$stmt = $pdo->prepare("SELECT * FROM pembelian WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$pembelian = $stmt->fetch();
if (!$pembelian) {
    echo "Data pembelian tidak ditemukan.";
    exit;
}
$stmt = $pdo->prepare("SELECT pd.*, b.nama, b.gambar FROM pembelian_detail pd JOIN buah b ON pd.buah_id = b.id WHERE pd.pembelian_id = ?");
$stmt->execute([$id]);
$detail = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pembelian - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding: 32px 28px; }
        .detail-container h2 { text-align: center; color: #f76b1c; margin-bottom: 22px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #eee; text-align: center; }
        th { background: #fdf6e3; color: #f76b1c; }
        .total { font-weight: bold; color: #e67e22; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; background: #fff3e0; }
    </style>
</head>
<body>
    <div class="detail-container">
        <h2>Detail Pembelian #<?=$pembelian['id']?></h2>
        <p>Tanggal: <?=date('d-m-Y H:i', strtotime($pembelian['tanggal']))?></p>
        <div style="margin-bottom:18px;">
        <?php
        // Ambil value terakhir yang dipilih jika form disubmit, atau default kosong
        $selected_metode = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metode_pembayaran'])) {
            $selected_metode = $_POST['metode_pembayaran'];
        }
        ?>
        <?php if (!empty($pembelian['metode_pembayaran'])): ?>
            <strong>Metode Pembayaran:</strong> <?=htmlspecialchars($pembelian['metode_pembayaran'])?>
        <?php else: ?>
            <form method="post" style="margin-bottom:0;">
                <label for="metode_pembayaran"><strong>Pilih Metode Pembayaran:</strong></label>
                <select name="metode_pembayaran" id="metode_pembayaran" required style="padding:7px 12px; border-radius:7px; border:1px solid #f9d423; margin:0 8px;">
                    <option value="" <?=($selected_metode==''?'selected':'')?>>-- Pilih --</option>
                    <option value="Transfer Bank" <?=($selected_metode=='Transfer Bank'?'selected':'')?>>Transfer Bank</option>
                    <option value="E-Wallet" <?=($selected_metode=='E-Wallet'?'selected':'')?>>E-Wallet</option>
                    <option value="COD" <?=($selected_metode=='COD'?'selected':'')?>>COD (Bayar di Tempat)</option>
                </select>
                <button type="submit" style="background:linear-gradient(90deg,#ff4e50,#f9d423);color:#fff;border:none;padding:7px 18px;border-radius:7px;font-weight:bold;cursor:pointer;">Simpan</button>
            </form>
        <?php endif; ?>
        </div>
        <table>
            <tr>
                <th>Buah</th>
                <th>Gambar</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php $total = 0; foreach ($detail as $item): $subtotal = $item['qty'] * $item['harga']; $total += $subtotal; ?>
                <tr>
                    <td><?=htmlspecialchars($item['nama'])?></td>
                    <td><img src="images/<?=htmlspecialchars($item['gambar'] ?? 'placeholder.png')?>" alt="<?=htmlspecialchars($item['nama'])?>"></td>
                    <td>Rp <?=number_format($item['harga'],0,',','.')?></td>
                    <td><?=$item['qty']?></td>
                    <td>Rp <?=number_format($subtotal,0,',','.')?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="total">Total</td>
                <td class="total">Rp <?=number_format($total,0,',','.')?></td>
            </tr>
        </table>
        <div style="text-align:center;margin-top:18px;">
            <a href="riwayat.php">&larr; Kembali ke Riwayat</a>
        </div>
    </div>
</body>
</html>
