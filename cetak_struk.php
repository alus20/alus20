<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    die('Akses ditolak.');
}

$id = intval($_GET['id'] ?? 0);

// Ambil pembelian & user
$stmt = $pdo->prepare("SELECT p.*, u.username FROM pembelian p LEFT JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$pembelian = $stmt->fetch();

if (!$pembelian)
    die('Struk tidak ditemukan.');
if (!($_SESSION['is_admin'] ?? 0) && $pembelian['user_id'] != $_SESSION['user_id']) {
    die('Akses ditolak.');
}

// Ambil detail buah
$stmt = $pdo->prepare("SELECT pd.*, b.nama FROM pembelian_detail pd JOIN buah b ON pd.buah_id = b.id WHERE pembelian_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Simpan rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    foreach ($_POST['rating'] as $buah_id => $nilai) {
        $buah_id = intval($buah_id);
        $nilai = intval($nilai);
        if ($nilai >= 1 && $nilai <= 5) {
            $stmt = $pdo->prepare("INSERT INTO review_buah (user_id, buah_id, pembelian_id, rating, review, tanggal) VALUES (?, ?, ?, ?, '-', NOW())");
            $stmt->execute([$_SESSION['user_id'], $buah_id, $id, $nilai]);
        }
    }
    echo "<script>alert('Terima kasih! Rating berhasil disimpan.');window.location.href='cetak_struk.php?id=$id';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>🍎 Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background buah.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #27ae60;
        }

        p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #eee;
        }

        th {
            background: linear-gradient(90deg, #ff4e50, #f9d423);
            color: white;
        }

        .success {
            color: #27ae60;
            font-weight: bold;
            margin-top: 20px;
            font-size: 1.2em;
        }

        .metode-info {
            margin-top: 15px;
        }

        .qris-img {
            max-width: 200px;
            margin: 10px auto;
            display: block;
        }

        button {
            margin: 10px 5px 0 5px;
            background: linear-gradient(90deg, #27ae60, #f9d423);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .rating-form {
            margin-top: 30px;
            text-align: left;
        }

        .rating-form h3 {
            color: #f39c12;
        }

        .rating-form label {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🍎 Struk Pembelian</h2>
        <p><b>No.:</b> #<?= $pembelian['id'] ?></p>
        <p><b>Nama:</b> <?= htmlspecialchars($pembelian['username']) ?></p>
        <p><b>Tanggal:</b> <?= date('d-m-Y H:i', strtotime($pembelian['tanggal'])) ?></p>
        <p><b>Metode:</b> <?= htmlspecialchars(strtoupper($pembelian['metode_pembayaran'])) ?></p>
        <p><b>Pengiriman:</b> <?= htmlspecialchars(ucfirst($pembelian['pengiriman'])) ?></p>

        <?php if ($pembelian['pengiriman'] === 'diantar'): ?>
            <p><b>Alamat:</b> <?= htmlspecialchars($pembelian['alamat_pengiriman']) ?></p>
            <p><b>Telepon:</b> <?= htmlspecialchars($pembelian['telepon_pengiriman']) ?></p>
        <?php elseif ($pembelian['pengiriman'] === 'dijemput'): ?>
            <p><b>Alamat Toko:</b> Jl. Contoh No. 123, Kota Buah</p>
        <?php endif; ?>

        <?php if ($pembelian['metode_pembayaran'] === 'qris'): ?>
            <div class="metode-info">
                <img src="qris.jpg" alt="QRIS" class="qris-img">
                <p>Silakan scan barcode QRIS untuk pembayaran.</p>
            </div>
        <?php elseif ($pembelian['metode_pembayaran'] === 'transfer'): ?>
            <div class="metode-info">
                <p>Transfer ke Rekening BCA: <strong>888999123456</strong></p>
            </div>
        <?php elseif ($pembelian['metode_pembayaran'] === 'debit'): ?>
            <div class="metode-info">
                <p>Silakan lakukan pembayaran di kasir (Debit).</p>
            </div>
        <?php elseif ($pembelian['metode_pembayaran'] === 'kredit'): ?>
            <div class="metode-info">
                <p>Silakan lakukan pembayaran di kasir (Kredit).<br>Biaya admin Rp 2.500 sudah ditambahkan.</p>
            </div>
        <?php elseif ($pembelian['metode_pembayaran'] === 'cash'): ?>
            <div class="metode-info">
                <p>Silakan lakukan pembayaran tunai di kasir.</p>
            </div>
        <?php endif; ?>

        <table>
            <tr>
                <th>Buah</th>
                <th>Harga/Kg</th>
                <th>Berat (Kg)</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $total = 0;
            foreach ($items as $i):
                // Pastikan pakai kolom 'berat' yang betul
                $berat = isset($i['berat']) ? floatval($i['berat']) : 0;
                $sub = $berat * $i['harga'];
                $total += $sub;
                ?>
                <tr>
                    <td><?= htmlspecialchars($i['nama']) ?></td>
                    <td>Rp <?= number_format($i['harga'], 0, ',', '.') ?></td>
                    <td><?= number_format($berat, 2) ?> Kg</td>
                    <td>Rp <?= number_format($sub, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ($pembelian['metode_pembayaran'] === 'kredit'): ?>
                <tr>
                    <td colspan="3">Biaya Admin</td>
                    <td>Rp 2.500</td>
                </tr>
                <?php $total += 2500; ?>
            <?php endif; ?>

            <tr>
                <td colspan="3"><b>Total</b></td>
                <td><b>Rp <?= number_format($total, 0, ',', '.') ?></b></td>
            </tr>
        </table>



        <div class="success">✅ Pembayaran Berhasil!</div>
        <button onclick="window.print()">Cetak Struk</button>
        <button onclick="window.location.href='index.php'">🏠 Kembali ke Beranda</button>

        <!-- Form Rating -->
        <form method="post" class="rating-form">
            <h3>⭐ Beri Rating Buah:</h3>
            <?php foreach ($items as $i): ?>
                <p><b><?= htmlspecialchars($i['nama']) ?></b>:
                    <?php for ($r = 1; $r <= 5; $r++): ?>
                        <label><input type="radio" name="rating[<?= $i['buah_id'] ?>]" value="<?= $r ?>"> <?= $r ?></label>
                    <?php endfor; ?>
                </p>
            <?php endforeach; ?>
            <button type="submit">Kirim Rating</button>
        </form>
    </div>
</body>

</html>