<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!($_SESSION['is_admin'] ?? 0)) {
    echo "Akses ditolak.";
    exit;
}

$errors = [];

// Tambah buah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = intval($_POST['harga'] ?? 0);
    $stok = intval($_POST['stok'] ?? 0);
    $gambar = 'placeholder.png';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $newName = uniqid('buah_', true) . '.' . $ext;
            if (!is_dir('images')) mkdir('images');
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'images/' . $newName);
            $gambar = $newName;
        } else {
            $errors[] = "Format gambar tidak didukung.";
        }
    }

    if (!$nama || !$harga) {
        $errors[] = "Nama dan harga wajib diisi.";
    } elseif (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO buah (nama, deskripsi, harga, gambar, stok) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $harga, $gambar, $stok]);
    }
}

// Hapus buah
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $pdo->prepare("DELETE FROM buah WHERE id = ?")->execute([$id]);
}

// Ambil data buah
$stmt = $pdo->query("SELECT * FROM buah");
$buah = $stmt->fetchAll();

// Ambil data pembelian (laporan)
$stmt = $pdo->query("SELECT p.*, u.username FROM pembelian p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.tanggal DESC");
$laporan = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            min-height: 100vh;
        }
        .admin-container {
            max-width: 900px;
            margin: 48px auto;
            background: rgba(255,255,255,0.85);
            border-radius: 32px;
            box-shadow: 0 8px 40px 0 rgba(255, 180, 80, 0.18), 0 1.5px 0 #fffbe6;
            padding: 40px 36px 36px 36px;
            backdrop-filter: blur(2.5px);
        }
        .admin-container h2 {
            text-align: center;
            color: #ff4e50;
            margin-bottom: 28px;
            letter-spacing: 1px;
            font-size: 2.1em;
            font-weight: 800;
            text-shadow: 0 2px 12px #fff7e6, 0 0 0 #fff;
        }
        .admin-container h2::before {
            content: '\1F34E';
            margin-right: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            background: rgba(255,251,230,0.92);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 12px #f9d42333;
        }
        th, td {
            padding: 13px 10px;
            border-bottom: 1px solid #ffe7c7;
            text-align: center;
        }
        th {
            background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%);
            color: #fff;
        }
        .admin-container form {
            margin-bottom: 24px;
        }
        .admin-container label {
            display: block;
            margin-bottom: 8px;
            color: #ff4e50;
            font-weight: 600;
        }
        .admin-container input, .admin-container textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #f9d423;
            margin-bottom: 12px;
            font-size: 1.05em;
            background: rgba(255,251,230,0.92);
            color: #ff4e50;
        }
        .admin-container button {
            background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%);
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 12px;
            font-size: 1.08em;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 2px 8px #ffe0c3;
        }
        .admin-container button:hover {
            background: linear-gradient(90deg, #f9d423 0%, #ff4e50 100%);
            transform: scale(1.04);
        }
        .admin-container ul {
            padding-left: 20px;
            margin-bottom: 18px;
        }
        .admin-container ul li {
            color: #e74c3c;
        }
        a {
            color: #ff4e50;
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
            color: #f9d423;
        }
        .admin-container > div > a {
            color: #fff !important;
            background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%);
            padding: 7px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .admin-container > div > a:hover {
            background: linear-gradient(90deg, #f9d423 0%, #ff4e50 100%);
            color: #fff !important;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div style="display:flex; justify-content: flex-end; margin-bottom: 10px;">
            <a href="index.php">Logout</a>
        </div>
        <h2>Dashboard Admin</h2>

        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" id="form-buah">
            <label>Nama Buah:
                <input type="text" name="nama" required>
            </label>
            <label>Manfaat:
                <textarea name="deskripsi"></textarea>
            </label>
            <label>Harga:
                <input type="number" name="harga" required>
            </label>
            <label>Stok:
                <input type="number" name="stok" min="0" required>
            </label>
            <label>Upload Gambar:
                <input type="file" name="gambar" accept="image/*" id="input-gambar">
            </label>
            <div style="text-align:center;margin-bottom:14px;">
                <img id="preview-gambar" src="images/placeholder.png" alt="Preview Gambar" style="max-width:120px;max-height:120px;border-radius:10px;border:1px solid #eee;display:inline-block;">
            </div>
            <button type="submit" name="tambah">Tambah Buah</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($buah as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= htmlspecialchars($b['nama']) ?></td>
                    <td><?= htmlspecialchars($b['deskripsi']) ?></td>
                    <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                    <td><?= (int)$b['stok'] ?></td>
                    <td>
                        <img src="images/<?= htmlspecialchars($b['gambar']) ?>" alt="Gambar" style="max-width:60px;max-height:60px;border-radius:8px;border:1px solid #eee;">
                    </td>
                    <td>
                        <a href="edit_buah.php?id=<?= $b['id'] ?>">Edit</a> |
                        <a href="?hapus=<?= $b['id'] ?>" onclick="return confirm('Hapus buah ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div style="margin-top:38px;">
            <h2 style="font-size:1.4em; color:#27ae60;">Laporan Pembelian</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($laporan as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username'] ?: '-') ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                        <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $row['metode_pembayaran'])) ?></td>
                        <td><a href="cetak_struk.php?id=<?= $row['id'] ?>" target="_blank">Cetak Struk</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div style="text-align:center; margin-top:18px;">
            <a href="index.php">&larr; Kembali ke Beranda</a>
        </div>

        <script>
            const inputGambar = document.getElementById('input-gambar');
            const previewGambar = document.getElementById('preview-gambar');

            inputGambar.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewGambar.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        </script>
    </div>
</body>
</html>
