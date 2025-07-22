<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$is_admin = $_SESSION['is_admin'] ?? 0;
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM buah WHERE id IN ($ids)");
    while ($row = $stmt->fetch()) {
        $row['qty'] = $cart[$row['id']];
        $row['subtotal'] = $row['qty'] * $row['harga'];
        $items[] = $row;
        $total += $row['subtotal'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($is_admin) {
        $_SESSION['msg'] = 'Admin tidak dapat melakukan transaksi.';
        header('Location: keranjang.php');
        exit;
    }

    if (isset($_POST['Tambah_Berat']) && isset($_POST['Berat'])) {
        foreach ($_POST['Berat'] as $id => $qty) {
            $id = intval($id);
            $qty = max(0.25, floatval($qty));
            $_SESSION['cart'][$id] = $qty;
        }
        $_SESSION['msg'] = 'Berat berhasil ditambahkan.';
        header('Location: keranjang.php');
        exit;
    }

    if (isset($_POST['checkout'])) {
        if (empty($_SESSION['cart'])) {
            $_SESSION['msg'] = 'Keranjang kosong.';
            header('Location: keranjang.php');
            exit;
        }
        $_SESSION['show_payment'] = true;
        header('Location: keranjang.php');
        exit;
    }

    if (isset($_POST['after_checkout']) && isset($_POST['metode'])) {
        $metode = $_POST['metode'];
        $pengiriman = $_POST['pengiriman'] ?? '';
        $alamat_pengiriman = $_POST['alamat_pengiriman'] ?? null;
        $telepon_pengiriman = $_POST['telepon_pengiriman'] ?? null;

        $_SESSION['metode_terpilih'] = $metode;

        $user_id = $_SESSION['user_id'];
        $cart = $_SESSION['cart'];
        $ids = implode(',', array_map('intval', array_keys($cart)));
        $stmt = $pdo->query("SELECT * FROM buah WHERE id IN ($ids)");
        $items = [];
        $total = 0;
        while ($row = $stmt->fetch()) {
            $row['qty'] = $cart[$row['id']];
            $row['subtotal'] = $row['qty'] * $row['harga'];
            $items[] = $row;
            $total += $row['subtotal'];
        }

        if ($metode === 'kredit') {
            $total += 2500;
        }

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO pembelian (user_id, total, metode_pembayaran, tanggal, pengiriman, alamat_pengiriman, telepon_pengiriman) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute([$user_id, $total, $metode, $pengiriman, $alamat_pengiriman, $telepon_pengiriman]);
        $pembelian_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO pembelian_detail (pembelian_id, buah_id, berat , harga) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$pembelian_id, $item['id'], $item['qty'], $item['harga']]);
            $pdo->prepare("UPDATE buah SET stok = stok - ? WHERE id = ?")->execute([$item['qty'], $item['id']]);
        }
        $pdo->commit();

        $_SESSION['cart'] = [];
        $_SESSION['last_pembelian_id'] = $pembelian_id;
        $_SESSION['show_payment'] = false;
        $_SESSION['msg'] = 'Pembayaran berhasil!';
        header('Location: keranjang.php');
        exit;
    }
}

$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
$last_id = $_SESSION['last_pembelian_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <style>
        body {
            background: url("background buah.jpg") center center/cover no-repeat fixed;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 48px auto;
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #ff4e50; }
        .msg { background: #d4edda; color: #155724; padding: 12px; margin-bottom: 16px; border-radius: 8px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #eee; padding: 10px; text-align: center; }
        th { background: #ff4e50; color: #fff; }
        .total { font-weight: bold; }
        button { background: #ff4e50; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; }
        button:hover { background: #f76b1c; }
        .info-pembayaran, .info-pengiriman { margin-top: 20px; background: #fffbe6; padding: 20px; border-radius: 10px; }
        .qris-img { max-width: 200px; display: block; margin: 12px 0; }
        input[type="number"], input[type="text"], input[type="tel"] { width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #ccc; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <h2>🍎 Keranjang Belanja</h2>
    <?php if ($msg): ?><div class="msg"><?=$msg?></div><?php endif; ?>

    <?php if ($items): ?>
        <form method="post">
            <table>
                <tr><th>Nama</th><th>Harga/Kg</th><th>Berat (Kg)</th><th>Subtotal</th></tr>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?=htmlspecialchars($item['nama'])?></td>
                    <td>Rp <?=number_format($item['harga'])?></td>
                    <td>
                        <input type="number" name="Berat[<?=$item['id']?>]" value="<?=$item['qty']?>" min="0.25" step="0.25">
                    </td>
                    <td>Rp <?=number_format($item['subtotal'])?></td>
                </tr>
                <?php endforeach; ?>
                <tr><td colspan="3" class="total">Total</td><td class="total">Rp <?=number_format($total)?></td></tr>
            </table>
            <button type="submit" name="Tambah_Berat">Tambah Berat</button>
            <button type="submit" name="checkout">Checkout</button>
        </form>
    <?php else: ?><p>Keranjang kosong.</p><?php endif; ?>

    <?php if (!empty($_SESSION['show_payment'])): ?>
        <h3>Pilih Metode Pembayaran</h3>
        <form method="post">
            <label><input type="radio" name="metode" value="cash" required> Cash</label>
            <label><input type="radio" name="metode" value="transfer"> Transfer</label>
            <label><input type="radio" name="metode" value="qris"> QRIS</label>
            <label><input type="radio" name="metode" value="debit"> Debit</label>
            <label><input type="radio" name="metode" value="kredit"> Kredit</label>

            <h3>Pengiriman</h3>
            <label><input type="radio" name="pengiriman" value="diantar" required> Diantar</label>
            <label><input type="radio" name="pengiriman" value="dijemput"> Dijemput</label>

            <div class="info-pengiriman" id="info-pengiriman"></div>
            <div class="info-pembayaran" id="info-pembayaran"></div>

            <button type="submit" name="after_checkout">Bayar</button>
        </form>
    <?php endif; ?>

    <?php if ($last_id): ?>
        <p style="text-align:center;"><a href="cetak_struk.php?id=<?=$last_id?>" target="_blank">🧾 Cetak Struk</a></p>
        <?php unset($_SESSION['last_pembelian_id']); ?>
    <?php endif; ?>
</div>

<script>
    const metodeRadios = document.querySelectorAll('input[name="metode"]');
    const pengirimanRadios = document.querySelectorAll('input[name="pengiriman"]');
    const infoPembayaran = document.getElementById('info-pembayaran');
    const infoPengiriman = document.getElementById('info-pengiriman');

    metodeRadios.forEach(r => {
        r.addEventListener('change', () => {
            let val = r.value;
            let html = "";
            if (val === 'qris') {
                html = "<img src='qris.jpg' alt='QRIS' class='qris-img'>";
            } else if (val === 'transfer') {
                html = "<p>Nomor Rekening BCA: <strong>123456789 A/N Buah - Buah Baper</strong></p>";
            } else if (val === 'debit' || val === 'cash') {
                html = "<p>Silakan bayar di kasir lalu cetak struk.</p>";
            } else if (val === 'kredit') {
                html = "<p>Silakan bayar di kasir. Biaya admin Rp 2.500 ditambahkan.</p>";
                
            }
            infoPembayaran.innerHTML = html;
        });
    });

    pengirimanRadios.forEach(r => {
        r.addEventListener('change', () => {
            let val = r.value;
            let html = "";
            if (val === 'diantar') {
                html = "<p>Masukkan alamat dan nomor telepon:</p>" +
                    "<input type='text' name='alamat_pengiriman' placeholder='Alamat lengkap' required>" +
                    "<input type='tel' name='telepon_pengiriman' placeholder='Nomor telepon' required>";
            } else if (val === 'dijemput') {
                html = "<p>Silakan ambil di Toko Buah: <strong>Jl.In aja dulu No. 2002, Kota Pekanbaru,Riau</strong></p>";
            }
            infoPengiriman.innerHTML = html;
        });
    });
</script>
</body>
</html>
