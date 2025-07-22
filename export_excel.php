<?php
// export_excel.php
session_start();
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || !($_SESSION['is_admin'] ?? 0)) {
    header('Location: login.php');
    exit;
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=pembelian.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','User','Tanggal','Total','Metode']);
$stmt = $pdo->query("SELECT p.id, u.username, p.tanggal, p.total, p.metode_pembayaran FROM pembelian p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.tanggal DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['username'],
        $row['tanggal'],
        $row['total'],
        $row['metode_pembayaran']
    ]);
}
fclose($output);
exit;
