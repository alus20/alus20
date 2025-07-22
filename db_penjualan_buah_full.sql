--
-- Database: db_penjualan_buah
--

-- Tabel: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: buah
CREATE TABLE IF NOT EXISTS `buah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: pembelian
CREATE TABLE IF NOT EXISTS `pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: pembelian_detail
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

-- Contoh data buah
INSERT INTO `buah` (`nama`, `deskripsi`, `harga`, `gambar`) VALUES
('Apel Fuji', 'Apel segar dari Jepang, manis dan renyah.', 25000, 'apel.jpg'),
('Jeruk Sunkist', 'Jeruk impor dengan rasa segar dan kaya vitamin C.', 18000, 'jeruk.jpg'),
('Pisang Cavendish', 'Pisang kuning, cocok untuk sarapan.', 12000, 'pisang.jpg'),
('Mangga Harum Manis', 'Mangga lokal dengan aroma harum dan rasa manis.', 22000, 'mangga.jpg');
