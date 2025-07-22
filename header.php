<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Buah-Buahan Baper' ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<header>
    <h1><?= isset($page_heading) ? htmlspecialchars($page_heading) : 'Buah-Buahan Baper' ?></h1>
    <nav style="display:flex; align-items:center; justify-content:center; max-width:1000px; margin:auto; min-height:48px; gap:32px;">
        <a href="index.php">Home</a>
        <a href="daftar_buah.php">Daftar Buah</a>
        <a href="keranjang.php">Keranjang</a>
    </nav>
</header>
