<?php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? 0)) {
    die('Akses ditolak.');
}
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM buah WHERE id = ?");
$stmt->execute([$id]);
$buah = $stmt->fetch();
if (!$buah) {
    die('<div style="text-align:center;margin:48px auto;font-size:1.3em;color:#e74c3c;font-family:sans-serif;">Data buah tidak ditemukan.</div>');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = intval($_POST['harga'] ?? 0);
    $stok = intval($_POST['stok'] ?? 0);
    $gambar = $buah['gambar'];
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (in_array($ext, $allowed)) {
            $newName = uniqid('buah_', true) . '.' . $ext;
            if (!is_dir('images')) mkdir('images');
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'images/' . $newName);
            $gambar = $newName;
        } else {
            $errors[] = "Format gambar tidak didukung.";
        }
    } else if (!empty($_POST['gambar_text'])) {
        $gambar = $_POST['gambar_text'];
    }
    if (!$nama || !$harga) {
        $errors[] = "Nama dan harga wajib diisi.";
    } else if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE buah SET nama=?, deskripsi=?, harga=?, gambar=?, stok=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $harga, $gambar, $stok, $id]);
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buah - Buah-Buahan Baper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #fffbe6 0%, #eaffd0 60%, #f9d423 100%);
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        }
        .edit-container {
            max-width: 480px;
            margin: 48px auto;
            background: rgba(255,255,255,0.98);
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(255, 180, 80, 0.13);
            padding: 32px 28px;
        }
        h2 {
            text-align: center;
            color: #ff4e50;
            margin-bottom: 24px;
            font-size: 1.7em;
            font-weight: 800;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ff4e50;
            font-weight: 600;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #f9d423;
            margin-bottom: 12px;
            font-size: 1.05em;
            background: #fffbe6;
            color: #ff4e50;
        }
        button {
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
        button:hover {
            background: linear-gradient(90deg, #f9d423 0%, #ff4e50 100%);
            transform: scale(1.04);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #27ae60;
            font-weight: bold;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .preview-img {
            max-width: 120px;
            max-height: 120px;
            border-radius: 10px;
            border: 1px solid #eee;
            display: block;
            margin: 0 auto 14px auto;
        }
        ul { padding-left: 20px; margin-bottom: 18px; }
        ul li { color: #e74c3c; font-size: 0.98em; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Buah</h2>
        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialchars($error)?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Nama Buah:
                <input type="text" name="nama" value="<?=htmlspecialchars($buah['nama'])?>" required>
            </label>
            <label>Manfaat:
                <textarea name="deskripsi"><?=htmlspecialchars($buah['deskripsi'])?></textarea>
            </label>
            <label>Harga:
                <input type="number" name="harga" value="<?=htmlspecialchars($buah['harga'])?>" required>
            </label>
            <label>Stok:
                <input type="number" name="stok" min="0" value="<?=htmlspecialchars($buah['stok'])?>" required>
            </label>
            <label>Upload Gambar:
                <input type="file" name="gambar" accept="image/*" id="input-gambar">
            </label>
            <label>Atau Nama File Gambar (opsional):
                <input type="text" name="gambar_text" placeholder="Misal: apel.jpg" id="input-gambar-text" value="<?=htmlspecialchars($buah['gambar'])?>">
            </label>
            <img id="preview-gambar" src="images/<?=htmlspecialchars($buah['gambar'])?>" alt="Preview Gambar" class="preview-img">
            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
        <a href="admin.php" class="back-link">&larr; Kembali ke Dashboard</a>
    </div>
    <script>
    // Preview gambar saat upload file
    const inputGambar = document.getElementById('input-gambar');
    const inputGambarText = document.getElementById('input-gambar-text');
    const previewGambar = document.getElementById('preview-gambar');
    inputGambar.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                previewGambar.src = ev.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
    inputGambarText.addEventListener('input', function(e) {
        if (this.value.trim() !== '') {
            previewGambar.src = 'images/' + this.value.trim();
        } else {
            previewGambar.src = 'images/placeholder.png';
        }
    });
    </script>
</body>
</html>
