<?php
// admin_dashboard.php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? 0)) {
    header('Location: login.php');
    exit;
}

// Statistik penjualan
$total_penjualan = $pdo->query("SELECT COUNT(*) FROM pembelian")->fetchColumn();
$total_pendapatan = $pdo->query("SELECT SUM(total) FROM pembelian")->fetchColumn();
$penjualan_per_bulan = $pdo->query("SELECT DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(total) as pendapatan, COUNT(*) as transaksi FROM pembelian GROUP BY bulan ORDER BY bulan DESC LIMIT 12")->fetchAll();

// Data user
$users = $pdo->query("SELECT id, username, email, is_admin, aktif FROM users ORDER BY id DESC")->fetchAll();

// Data pembelian untuk export
$pembelian = $pdo->query("SELECT p.*, u.username FROM pembelian p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.tanggal DESC LIMIT 100")->fetchAll();

function escape($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Statistik & Manajemen</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container { max-width: 1100px; margin: 40px auto; background: rgba(255,255,255,0.97); border-radius: 24px; box-shadow: 0 8px 32px #f9d42333; padding: 36px 32px; }
        h2 { color: #ff4e50; text-align: center; margin-bottom: 24px; }
        .stat-box { display: flex; gap: 32px; justify-content: center; margin-bottom: 32px; }
        .stat { background: #fffbe6; border-radius: 14px; box-shadow: 0 2px 8px #ffe0c3; padding: 24px 32px; text-align: center; font-size: 1.2em; font-weight: bold; color: #ff4e50; }
        .section { margin-bottom: 38px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #ffe7c7; text-align: center; }
        th { background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%); color: #fff; }
        .btn { background: #ff4e50; color: #fff; border: none; border-radius: 8px; padding: 7px 16px; font-weight: bold; cursor: pointer; margin: 0 2px; }
        .btn:hover { background: #f9d423; color: #ff4e50; }
        .export-btn { background: #27ae60; color: #fff; margin-bottom: 12px; }
        .back-link { display: inline-block; background: #27ae60; color: #fff; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 28px; }
        .back-link:hover { background: #2ecc71; }
        @media (max-width: 700px) { .stat-box { flex-direction: column; gap: 16px; } .dashboard-container { padding: 12px 2vw; } }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Dashboard Admin</h2>
    <div class="stat-box">
        <div class="stat">Total Penjualan<br><?=escape($total_penjualan)?></div>
        <div class="stat">Total Pendapatan<br>Rp <?=number_format($total_pendapatan,0,',','.')?></div>
    </div>
    <div class="section">
        <canvas id="chartPenjualan" height="80"></canvas>
    </div>
    <div class="section">
        <h3>Manajemen User</h3>
        <table>
            <tr><th>ID</th><th>Username</th><th>Email</th><th>Admin</th><th>Status</th><th>Aksi</th></tr>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?=escape($u['id'])?></td>
                <td><?=escape($u['username'])?></td>
                <td><?=escape($u['email'])?></td>
                <td><?=($u['is_admin']?'Ya':'Tidak')?></td>
                <td><?=($u['aktif']?'Aktif':'Nonaktif')?></td>
                <td>
                    <?php if (!$u['is_admin']): ?>
                        <?php if ($u['aktif']): ?>
                            <a class="btn" href="user_action.php?id=<?=$u['id']?>&aksi=nonaktif" onclick="return confirm('Nonaktifkan user ini?')">Nonaktifkan</a>
                        <?php else: ?>
                            <a class="btn" href="user_action.php?id=<?=$u['id']?>&aksi=aktif" onclick="return confirm('Aktifkan user ini?')">Aktifkan</a>
                        <?php endif; ?>
                        <a class="btn" href="user_action.php?id=<?=$u['id']?>&aksi=hapus" onclick="return confirm('Hapus user ini?')">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="section">
        <h3>Export Data Pembelian</h3>
        <form method="post" action="export_excel.php">
            <button type="submit" class="btn export-btn">Export ke Excel/CSV</button>
        </form>
        <table>
            <tr><th>ID</th><th>User</th><th>Tanggal</th><th>Total</th><th>Metode</th></tr>
            <?php foreach ($pembelian as $row): ?>
            <tr>
                <td><?=escape($row['id'])?></td>
                <td><?=escape($row['username'])?></td>
                <td><?=escape($row['tanggal'])?></td>
                <td>Rp <?=number_format($row['total'],0,',','.')?></td>
                <td><?=escape($row['metode_pembayaran'])?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div style="text-align:center; margin-top:28px;">
        <a href="index.php" class="back-link">&larr; Kembali ke Halaman Utama</a>
    </div>
</div>
<script>
const ctx = document.getElementById('chartPenjualan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            <?php foreach (array_reverse($penjualan_per_bulan) as $b) { echo "'".escape($b['bulan'])."',"; } ?>
        ],
        datasets: [{
            label: 'Pendapatan',
            data: [<?php foreach (array_reverse($penjualan_per_bulan) as $b) { echo (int)$b['pendapatan'].","; } ?>],
            backgroundColor: '#ff4e50',
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
</body>
</html>
