<?php
require_once 'koneksi.php';

$buah = [];
$db_error = '';
// Ambil parameter filter/sort/paging
$q = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? '';
$min_harga = isset($_GET['min_harga']) ? (int)$_GET['min_harga'] : '';
$max_harga = isset($_GET['max_harga']) ? (int)$_GET['max_harga'] : '';
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 8;
$where = [];
$params = [];
if ($q !== '') {
    $where[] = "(nama LIKE ? OR deskripsi LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}
if ($min_harga !== '' && $min_harga >= 0) {
    $where[] = "harga >= ?";
    $params[] = $min_harga;
}
if ($max_harga !== '' && $max_harga > 0) {
    $where[] = "harga <= ?";
    $params[] = $max_harga;
}
$where_sql = $where ? ("WHERE " . implode(' AND ', $where)) : '';
$order_sql = '';
if ($sort === 'harga_asc') $order_sql = 'ORDER BY harga ASC';
elseif ($sort === 'harga_desc') $order_sql = 'ORDER BY harga DESC';
elseif ($sort === 'rating_desc') $order_sql = 'ORDER BY (SELECT AVG(rating) FROM review_buah WHERE buah_id=buah.id) DESC';
else $order_sql = 'ORDER BY id DESC';
// Hitung total buah
$total_buah = 0;
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM buah $where_sql");
    $stmt->execute($params);
    $total_buah = (int)$stmt->fetchColumn();
} catch (PDOException $e) {}
// Paging
$offset = ($page-1)*$per_page;
// Query buah
try {
    $sql = "SELECT * FROM buah $where_sql $order_sql LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $buah = $stmt->fetchAll();
} catch (PDOException $e) {
    $db_error = 'Gagal mengambil data buah. Silakan coba beberapa saat lagi.';
}

// Ambil rating rata-rata dan jumlah review untuk semua buah
$rating_map = [];
try {
    $stmt = $pdo->prepare("SELECT buah_id, AVG(rating) as avg_rating, COUNT(*) as jml_review FROM review_buah GROUP BY buah_id");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row) {
        $rating_map[$row['buah_id']] = $row;
    }
} catch (PDOException $e) {}

// Ambil review terbaru untuk semua buah
$review_map = [];
try {
    $stmt = $pdo->prepare("SELECT r.*, u.username FROM review_buah r JOIN users u ON r.user_id = u.id ORDER BY r.tanggal DESC");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row) {
        $review_map[$row['buah_id']][] = $row;
    }
} catch (PDOException $e) {}

// Cek buah yang sudah dibeli user login (untuk hak review)
$user_bought = [];
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT pd.buah_id FROM pembelian_detail pd JOIN pembelian p ON pd.pembelian_id = p.id WHERE p.user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        foreach ($stmt->fetchAll() as $row) {
            $user_bought[$row['buah_id']] = true;
        }
    } catch (PDOException $e) {}
}
?>
<?php 
$page_title = 'Daftar Buah - Buah-Buahan Baper';
$page_heading = 'Daftar Buah';
include 'header.php';
?>

    <main style="max-width: 1000px; margin: 38px auto; background: #fff; border-radius: 28px; box-shadow: 0 10px 36px rgba(44,62,80,0.13); padding: 38px 32px 32px 32px; backdrop-filter: blur(2.5px);">
        <form method="get" action="" style="margin-bottom:24px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; background:#fffbe6; border-radius:12px; padding:18px 18px 8px 18px; box-shadow:0 2px 8px #ffe0c3;" aria-label="Form pencarian dan filter buah">
            <div style="display:flex; flex-direction:column;">
                <label for="q" style="font-weight:600; color:#222;">Cari Buah</label>
                <input type="text" id="q" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Nama/deskripsi..." style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; min-width:120px;">
            </div>
            <div style="display:flex; flex-direction:column;">
                <label for="min_harga" style="font-weight:600; color:#222;">Harga Min</label>
                <input type="number" id="min_harga" name="min_harga" value="<?=htmlspecialchars($min_harga)?>" min="0" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; width:90px;">
            </div>
            <div style="display:flex; flex-direction:column;">
                <label for="max_harga" style="font-weight:600; color:#222;">Harga Max</label>
                <input type="number" id="max_harga" name="max_harga" value="<?=htmlspecialchars($max_harga)?>" min="0" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc; width:90px;">
            </div>
            <div style="display:flex; flex-direction:column;">
                <label for="sort" style="font-weight:600; color:#222;">Urutkan</label>
                <select id="sort" name="sort" style="padding:6px 10px; border-radius:6px; border:1px solid #ccc;">
                    <option value="">Terbaru</option>
                    <option value="harga_asc" <?=($sort==='harga_asc'?'selected':'')?>>Harga Termurah</option>
                    <option value="harga_desc" <?=($sort==='harga_desc'?'selected':'')?>>Harga Termahal</option>
                    <option value="rating_desc" <?=($sort==='rating_desc'?'selected':'')?>>Rating Tertinggi</option>
                </select>
            </div>
            <button type="submit" style="background:#ff4e50; color:#fff; border-radius:8px; border:none; font-weight:700; padding:8px 18px; margin-top:18px;">Cari/Filter</button>
        </form>
        <?php if ($db_error): ?>
            <div style="color:#b30000; background:#fff3f3; border:2px solid #e74c3c; border-radius:8px; font-size:1.2em; font-weight:700; margin:32px 0; text-align:center; padding:16px 0;">
                <?=htmlspecialchars($db_error)?></div>
        <?php endif; ?>
        <div class="buah-list">
            <?php if (empty($buah) && !$db_error): ?>
                <div style="font-size:1.5em; color:#b30000; font-weight:700; margin-top:40px;">Belum ada buah yang tersedia.</div>
            <?php elseif (!$db_error): ?>
                <?php foreach ($buah as $b): ?>
                    <div class="buah-card" style="background:#fffbe6; border-radius:20px; box-shadow:0 4px 18px rgba(44,62,80,0.10); border:2px solid #f9d423; margin-bottom:18px;">
                        <a href="detail.php?id=<?=htmlspecialchars($b['id'])?>">
                            <img src="images/<?=htmlspecialchars($b['gambar'] ?? 'placeholder.png') ?>" alt="<?=htmlspecialchars($b['nama'])?>" style="border-radius:12px; border:2px solid #ff4e50; max-width:160px; max-height:120px; background:#fff;">
                        </a>
                        <h3><a href="detail.php?id=<?=htmlspecialchars($b['id'])?>" style="color:#ff4e50;text-decoration:none;"> <?=htmlspecialchars($b['nama'])?></a></h3>
                        <p style="color:#222; font-size:1.08em;"><?=htmlspecialchars($b['deskripsi'])?></p>
                        <div class="harga" style="color:#222; font-weight:700; font-size:1.15em;">Rp <?=number_format($b['harga'],0,',','.')?></div>
                        <div class="stok" style="color:#222; font-weight:600;">
                            Stok: <span<?=isset($b['stok']) && (int)$b['stok'] <= 5 ? ' style="color:#b30000;font-weight:bold;"' : ''?>><?=isset($b['stok']) ? (int)$b['stok'] : 0?></span>
                            <?php if (isset($b['stok']) && (int)$b['stok'] > 0 && (int)$b['stok'] <= 5): ?>
                                <span style="color:#b30000; font-weight:bold; margin-left:8px;">&#9888; Stok Hampir Habis!</span>
                            <?php endif; ?>
                        </div>
                        <div class="rating" style="margin:8px 0 4px 0;">
                            <?php 
                            $avg = isset($rating_map[$b['id']]['avg_rating']) ? round($rating_map[$b['id']]['avg_rating'],1) : null;
                            $jml = isset($rating_map[$b['id']]['jml_review']) ? (int)$rating_map[$b['id']]['jml_review'] : 0;
                            ?>
                            <span style="color:#f39c12; font-size:1.1em; font-weight:700;">
                                <?php if ($avg): ?>
                                    <?=str_repeat('★', (int)round($avg))?><span style="color:#bbb;"><?=str_repeat('★', 5-(int)round($avg))?></span>
                                    (<?=number_format($avg,1)?>/5 dari <?=$jml?> review)
                                <?php else: ?>
                                    Belum ada rating
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && isset($user_bought[$b['id']])): ?>
                            <form method="post" action="review_buah.php" style="margin-bottom:8px;" aria-label="Form review buah <?=htmlspecialchars($b['nama'])?>">
                                <input type="hidden" name="id_buah" value="<?=htmlspecialchars($b['id'])?>">
                                <label for="rating-<?=$b['id']?>" style="font-weight:600; color:#222;">Beri Rating:</label>
                                <select name="rating" id="rating-<?=$b['id']?>" required style="margin:0 8px;">
                                    <option value="">Pilih</option>
                                    <?php for($i=5;$i>=1;$i--): ?><option value="<?=$i?>"><?=$i?> ★</option><?php endfor; ?>
                                </select>
                                <label for="review-<?=$b['id']?>" class="sr-only">Review</label>
                                <input type="text" name="review" id="review-<?=$b['id']?>" maxlength="255" placeholder="Tulis review singkat..." style="width:180px;">
                                <button type="submit" style="background:#f39c12; color:#fff; border-radius:6px; border:none; font-weight:700; padding:6px 14px;">Kirim</button>
                            </form>
                        <?php endif; ?>
                        <?php if (!empty($review_map[$b['id']])): ?>
                            <div class="review-list" style="margin-bottom:8px;">
                                <?php foreach(array_slice($review_map[$b['id']],0,2) as $rv): ?>
                                    <div style="font-size:0.98em; color:#333; background:#fff7e6; border-radius:6px; margin-bottom:4px; padding:6px 10px;">
                                        <span style="color:#f39c12; font-weight:700;"><?=str_repeat('★', (int)$rv['rating'])?><span style="color:#bbb;"><?=str_repeat('★', 5-(int)$rv['rating'])?></span></span>
                                        <span style="font-weight:600; color:#222; margin-left:6px;"><?=htmlspecialchars($rv['username'])?></span>:
                                        <?=htmlspecialchars($rv['review'])?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php $is_admin = $_SESSION['is_admin'] ?? 0; ?>
                        <?php if ($is_admin): ?>
                            <button type="button" disabled style="background:#ccc; color:#333; border:2px solid #aaa;">Admin tidak bisa menambah</button>
                        <?php elseif (isset($b['stok']) && (int)$b['stok'] === 0): ?>
                            <div class="kosong" style="color:#b30000; font-weight:700;">Buah kosong</div>
                            <button type="button" disabled style="background:#ccc; color:#333; border:2px solid #aaa;">Tambah ke Keranjang</button>
                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <form method="post" action="tambah_keranjang.php" aria-label="Tambah ke Keranjang untuk <?=htmlspecialchars($b['nama'])?>">
                                <input type="hidden" name="id_buah" value="<?=htmlspecialchars($b['id'])?>">
                                <button type="submit" style="background:#ff4e50; color:#fff; border:2px solid #f9d423; border-radius:8px; font-weight:700; padding:8px 18px;">Tambah ke Keranjang</button>
                            </form>
                        <?php else: ?>
                            <button type="button" disabled style="background:#ccc; color:#333; border:2px solid #aaa;">Login untuk menambah</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- Pagination -->
        <?php if ($total_buah > $per_page): ?>
            <nav aria-label="Navigasi halaman" style="margin:32px 0; text-align:center;">
                <?php $total_page = ceil($total_buah/$per_page); ?>
                <ul style="display:inline-flex; gap:6px; list-style:none; padding:0; margin:0;">
                <?php for($i=1;$i<=$total_page;$i++): ?>
                    <li>
                        <a href="?<?=http_build_query(array_merge($_GET,["page"=>$i]))?>" style="padding:7px 14px; border-radius:6px; border:1.5px solid #ff4e50; background:<?=$i==$page?'#ff4e50':'#fffbe6'?>; color:<?=$i==$page?'#fff':'#ff4e50'?>; font-weight:700; text-decoration:none; display:inline-block; min-width:32px; text-align:center;" aria-current="<?=$i==$page?'page':'false'?>">
                            <?=$i?>
                        </a>
                    </li>
                <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </main>

<?php include 'footer.php'; ?>

