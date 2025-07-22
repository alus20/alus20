--
-- Struktur tabel untuk tabel `pembelian`
--
CREATE TABLE IF NOT EXISTS `pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur tabel untuk tabel `pembelian_detail`
--
CREATE TABLE IF NOT EXISTS `pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_id` int(11) NOT NULL,
  `buah_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pembelian_id` (`pembelian_id`),
  KEY `buah_id` (`buah_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
