<?php
// user_action.php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? 0)) {
    header('Location: login.php');
    exit;
}
$id = intval($_GET['id'] ?? 0);
$aksi = $_GET['aksi'] ?? '';
if ($id > 0 && in_array($aksi, ['nonaktif','aktif','hapus'])) {
    if ($aksi === 'nonaktif') {
        $pdo->prepare("UPDATE users SET aktif=0 WHERE id=? AND is_admin=0")->execute([$id]);
    } elseif ($aksi === 'aktif') {
        $pdo->prepare("UPDATE users SET aktif=1 WHERE id=? AND is_admin=0")->execute([$id]);
    } elseif ($aksi === 'hapus') {
        $pdo->prepare("DELETE FROM users WHERE id=? AND is_admin=0")->execute([$id]);
    }
}
header('Location: admin_dashboard.php');
exit;
