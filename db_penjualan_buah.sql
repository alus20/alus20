-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jul 2025 pada 08.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan_buah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buah`
--

CREATE TABLE `buah` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buah`
--

INSERT INTO `buah` (`id`, `nama`, `deskripsi`, `harga`, `gambar`, `stok`) VALUES
(14, 'anggur', 'Kaya antioksidan, Menjaga kesehatan jantung, Mencegah kanker, Baik untuk kesehatan otak', 40000, 'buah_687e9b0100e0a7.94324608.jpeg', 20),
(16, 'semangka', 'Menghidrasi tubuh karena kandungan airnya tinggi, membantu pencernaan, kaya likopen (antioksidan).', 9000, 'buah_687e9bffa2ee53.48213480.jpeg', 20),
(17, 'nanas', 'Baik untuk pencernaan, kaya vitamin C.', 10000, 'buah_687e9ca7c80e28.87100678.jpeg', 20),
(18, 'Pisang', 'Energi cepat, serat tinggi, baik untuk jantung.', 14000, 'buah_687e9d42919508.57395458.jpeg', 20),
(19, 'Mangga', 'Vitamin A & C, daya tahan tubuh & kulit.', 16000, 'buah_687e9da2766415.74572546.jpeg', 20),
(20, 'Jeruk', 'Tinggi vitamin C, mendukung imun.', 12000, 'buah_687e9dda5bf6c8.41985281.jpg', 20),
(21, 'Melon', 'Menyegarkan, sumber vitamin A & antioksidan.', 14000, 'buah_687e9e393682d3.50065635.jpeg', 20),
(22, 'Stroberi lokal', 'Kaya antioksidan, baik untuk kulit.', 37000, 'buah_687e9ea8022e20.10376210.jpeg', 20),
(23, 'Kelapa muda', 'Elektrolit alami, menghidrasi, segar.', 10000, 'buah_687e9f04a8a9b7.84823763.jpeg', 20),
(24, 'Jambu air', 'Rendah kalori, bantu hidrasi & kulit sehat.', 10000, 'buah_687e9f6fcdf982.77074655.jpeg', 20),
(25, 'Pepaya', 'Lancarkan pencernaan, vitamin A & C.', 8000, 'buah_687e9fd4287441.83883376.jpeg', 20),
(26, 'Belimbing', 'Serat tinggi, vitamin C & antioksidan.', 9000, 'buah_687ea02b0a1ca4.25550684.jpeg', 20),
(27, 'Duku', 'Tinggi vitamin C & serat, rasa manis segar.', 16000, 'buah_687ea1142a8590.91771087.webp', 20),
(28, 'Sirsak', 'Serat tinggi, bantu pencernaan & daya tahan tubuh.', 9000, 'buah_687ea2dd64bab2.87483949.jpeg', 20),
(29, 'Sawo', 'Manis legit, sumber energi alami.', 9000, 'buah_687ea3693ff199.08397439.jpeg', 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` int(11) NOT NULL,
  `metode` varchar(20) NOT NULL,
  `metode_pembayaran` varchar(32) DEFAULT NULL,
  `pengiriman` varchar(20) DEFAULT NULL,
  `alamat_pengiriman` varchar(255) DEFAULT NULL,
  `telepon_pengiriman` varchar(20) DEFAULT NULL,
  `biaya_admin` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id`, `user_id`, `tanggal`, `total`, `metode`, `metode_pembayaran`, `pengiriman`, `alamat_pengiriman`, `telepon_pengiriman`, `biaya_admin`) VALUES
(1, 2, '2025-07-17 00:40:37', 164000, '', NULL, NULL, NULL, NULL, 0.00),
(2, 2, '2025-07-17 00:55:33', 12000, '', NULL, NULL, NULL, NULL, 0.00),
(3, 2, '2025-07-17 01:00:36', 12000, '', NULL, NULL, NULL, NULL, 0.00),
(4, 2, '2025-07-17 01:03:01', 12000, '', NULL, NULL, NULL, NULL, 0.00),
(5, 2, '2025-07-17 01:04:14', 12000, '', NULL, NULL, NULL, NULL, 0.00),
(6, 2, '2025-07-17 13:51:42', 18000, '', NULL, NULL, NULL, NULL, 0.00),
(7, 1, '2025-07-17 14:02:46', 12000, '', NULL, NULL, NULL, NULL, 0.00),
(8, 1, '2025-07-17 14:03:03', 144000, '', NULL, NULL, NULL, NULL, 0.00),
(9, 1, '2025-07-17 14:06:36', 24000, '', NULL, NULL, NULL, NULL, 0.00),
(10, 1, '2025-07-17 15:04:04', 10, '', NULL, NULL, NULL, NULL, 0.00),
(11, 3, '2025-07-17 18:28:02', 10, '', NULL, NULL, NULL, NULL, 0.00),
(12, 3, '2025-07-17 18:28:59', 10, '', NULL, NULL, NULL, NULL, 0.00),
(13, 3, '2025-07-17 18:32:38', 10, '', NULL, NULL, NULL, NULL, 0.00),
(14, 3, '2025-07-17 18:32:56', 10, '', NULL, NULL, NULL, NULL, 0.00),
(15, 3, '2025-07-17 18:36:58', 10, '', NULL, NULL, NULL, NULL, 0.00),
(16, 3, '2025-07-17 18:37:18', 10, '', NULL, NULL, NULL, NULL, 0.00),
(17, 3, '2025-07-17 18:40:02', 10, '', NULL, NULL, NULL, NULL, 0.00),
(18, 3, '2025-07-17 18:40:30', 10, '', NULL, NULL, NULL, NULL, 0.00),
(19, 3, '2025-07-17 18:41:52', 10, '', NULL, NULL, NULL, NULL, 0.00),
(20, 3, '2025-07-17 18:42:05', 10, '', NULL, NULL, NULL, NULL, 0.00),
(21, 3, '2025-07-17 18:44:29', 10, '', NULL, NULL, NULL, NULL, 0.00),
(22, 3, '2025-07-17 18:44:47', 10, '', NULL, NULL, NULL, NULL, 0.00),
(23, 3, '2025-07-17 18:46:43', 10, '', NULL, NULL, NULL, NULL, 0.00),
(24, 3, '2025-07-17 18:49:47', 10, '', NULL, NULL, NULL, NULL, 0.00),
(25, 3, '2025-07-17 18:51:21', 10, '', NULL, NULL, NULL, NULL, 0.00),
(26, 3, '2025-07-17 18:51:40', 10, '', NULL, NULL, NULL, NULL, 0.00),
(27, 3, '2025-07-17 23:34:51', 10, '', NULL, NULL, NULL, NULL, 0.00),
(28, 3, '2025-07-17 23:37:58', 10, '', NULL, NULL, NULL, NULL, 0.00),
(29, 3, '2025-07-17 23:38:14', 10, '', NULL, NULL, NULL, NULL, 0.00),
(30, 3, '2025-07-17 23:42:48', 10, '', NULL, NULL, NULL, NULL, 0.00),
(31, 3, '2025-07-17 23:45:47', 10, '', NULL, NULL, NULL, NULL, 0.00),
(32, 3, '2025-07-17 23:57:01', 70, '', NULL, NULL, NULL, NULL, 0.00),
(33, 3, '2025-07-17 23:57:47', 10, '', NULL, NULL, NULL, NULL, 0.00),
(34, 3, '2025-07-17 23:58:07', 10, '', NULL, NULL, NULL, NULL, 0.00),
(35, 3, '2025-07-17 23:58:31', 10, '', NULL, NULL, NULL, NULL, 0.00),
(36, 3, '2025-07-17 23:58:45', 10, '', NULL, NULL, NULL, NULL, 0.00),
(37, 3, '2025-07-18 00:01:09', 10, '', NULL, NULL, NULL, NULL, 0.00),
(38, 3, '2025-07-18 00:01:24', 10, '', NULL, NULL, NULL, NULL, 0.00),
(39, 3, '2025-07-18 00:03:59', 10, '', NULL, NULL, NULL, NULL, 0.00),
(40, 3, '2025-07-18 00:04:05', 10, '', NULL, NULL, NULL, NULL, 0.00),
(41, 3, '2025-07-18 00:04:06', 10, '', NULL, NULL, NULL, NULL, 0.00),
(42, 3, '2025-07-18 00:04:12', 10, '', NULL, NULL, NULL, NULL, 0.00),
(43, 3, '2025-07-18 00:04:19', 10, '', NULL, NULL, NULL, NULL, 0.00),
(44, 3, '2025-07-18 00:04:23', 10, '', NULL, NULL, NULL, NULL, 0.00),
(45, 3, '2025-07-18 00:04:23', 10, '', NULL, NULL, NULL, NULL, 0.00),
(46, 3, '2025-07-18 00:07:44', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(47, 3, '2025-07-18 00:07:59', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(48, 3, '2025-07-18 00:08:02', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(49, 3, '2025-07-18 00:08:47', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(50, 3, '2025-07-18 00:08:49', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(51, 3, '2025-07-18 00:08:50', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(52, 3, '2025-07-18 00:08:50', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(53, 3, '2025-07-18 00:08:50', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(54, 3, '2025-07-18 00:08:50', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(55, 3, '2025-07-18 00:10:57', 20020, '', NULL, NULL, NULL, NULL, 0.00),
(56, 3, '2025-07-18 00:11:29', 20, '', NULL, NULL, NULL, NULL, 0.00),
(57, 3, '2025-07-18 00:13:48', 20, '', NULL, NULL, NULL, NULL, 0.00),
(58, 3, '2025-07-18 00:13:57', 20, '', NULL, NULL, NULL, NULL, 0.00),
(59, 3, '2025-07-18 00:14:05', 20, '', NULL, NULL, NULL, NULL, 0.00),
(60, 3, '2025-07-18 00:15:01', 20, '', NULL, NULL, NULL, NULL, 0.00),
(61, 3, '2025-07-18 00:15:12', 20, '', NULL, NULL, NULL, NULL, 0.00),
(62, 3, '2025-07-18 00:15:15', 20, '', NULL, NULL, NULL, NULL, 0.00),
(63, 3, '2025-07-18 00:15:29', 20, '', NULL, NULL, NULL, NULL, 0.00),
(64, 3, '2025-07-18 00:15:34', 20, '', NULL, NULL, NULL, NULL, 0.00),
(65, 3, '2025-07-18 00:15:41', 20, '', NULL, NULL, NULL, NULL, 0.00),
(66, 3, '2025-07-18 00:15:48', 20, '', NULL, NULL, NULL, NULL, 0.00),
(67, 3, '2025-07-18 00:16:00', 20, '', NULL, NULL, NULL, NULL, 0.00),
(68, 3, '2025-07-18 00:16:28', 20, '', NULL, NULL, NULL, NULL, 0.00),
(69, 3, '2025-07-18 00:16:33', 20, '', NULL, NULL, NULL, NULL, 0.00),
(70, 3, '2025-07-18 00:16:46', 20, '', NULL, NULL, NULL, NULL, 0.00),
(71, 3, '2025-07-18 00:17:04', 20, '', NULL, NULL, NULL, NULL, 0.00),
(72, 3, '2025-07-18 00:17:40', 20, '', NULL, NULL, NULL, NULL, 0.00),
(73, 3, '2025-07-18 00:20:04', 20, '', NULL, NULL, NULL, NULL, 0.00),
(74, 3, '2025-07-18 00:20:43', 20, '', NULL, NULL, NULL, NULL, 0.00),
(75, 3, '2025-07-18 00:22:31', 20, '', NULL, NULL, NULL, NULL, 0.00),
(76, 3, '2025-07-18 00:22:45', 20, '', NULL, NULL, NULL, NULL, 0.00),
(77, 3, '2025-07-18 00:23:07', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(78, 3, '2025-07-18 00:26:06', 80, '', NULL, NULL, NULL, NULL, 0.00),
(79, 3, '2025-07-18 00:26:40', 60000, '', NULL, NULL, NULL, NULL, 0.00),
(80, 1, '2025-07-18 00:30:16', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(81, 1, '2025-07-18 00:32:35', 20, '', NULL, NULL, NULL, NULL, 0.00),
(82, 1, '2025-07-18 00:35:53', 20, '', NULL, NULL, NULL, NULL, 0.00),
(83, 4, '2025-07-18 11:24:15', 40, '', NULL, NULL, NULL, NULL, 0.00),
(84, 1, '2025-07-18 16:44:19', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(85, 1, '2025-07-18 16:45:55', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(86, 1, '2025-07-18 16:47:33', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(87, 1, '2025-07-18 16:47:56', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(88, 1, '2025-07-18 16:48:28', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(89, 1, '2025-07-18 16:50:58', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(90, 1, '2025-07-18 16:52:34', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(91, 1, '2025-07-18 16:53:52', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(92, 1, '2025-07-18 16:54:08', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(93, 1, '2025-07-18 16:56:52', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(94, 1, '2025-07-18 18:20:13', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(95, 5, '2025-07-19 09:38:00', 60000, '', NULL, NULL, NULL, NULL, 0.00),
(96, 6, '2025-07-19 10:58:05', 40000, '', NULL, NULL, NULL, NULL, 0.00),
(97, 1, '2025-07-19 17:43:12', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(98, 1, '2025-07-19 21:54:03', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(99, 1, '2025-07-19 21:55:36', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(100, 1, '2025-07-19 22:33:10', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(101, 4, '2025-07-19 22:36:52', 20000, '', NULL, NULL, NULL, NULL, 0.00),
(102, 2, '2025-07-19 23:06:22', 20000, 'transfer', NULL, NULL, NULL, NULL, 0.00),
(103, 1, '2025-07-20 09:18:12', 20000, 'transfer', NULL, NULL, NULL, NULL, 0.00),
(104, 1, '2025-07-20 11:12:48', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(105, 1, '2025-07-20 15:08:14', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(106, 1, '2025-07-20 15:09:56', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(107, 1, '2025-07-20 18:33:37', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(108, 8, '2025-07-20 18:54:30', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(109, 1, '2025-07-21 01:41:46', 20000, 'cash', NULL, NULL, NULL, NULL, 0.00),
(110, 1, '2025-07-21 01:43:08', 40000, 'transfer', NULL, NULL, NULL, NULL, 0.00),
(111, 1, '2025-07-21 01:48:43', 20000, '', 'cash', NULL, NULL, NULL, 0.00),
(112, 1, '2025-07-21 01:52:06', 20000, '', 'cash', NULL, NULL, NULL, 0.00),
(113, 1, '2025-07-21 01:57:25', 20000, '', 'transfer', NULL, NULL, NULL, 0.00),
(114, 1, '2025-07-21 01:58:47', 20000, '', 'transfer', NULL, NULL, NULL, 0.00),
(115, 1, '2025-07-21 02:03:46', 20000, '', 'cash', NULL, NULL, NULL, 0.00),
(116, 1, '2025-07-21 02:04:40', 20000, '', 'qris', NULL, NULL, NULL, 0.00),
(117, 1, '2025-07-21 02:08:36', 20000, '', 'transfer', NULL, NULL, NULL, 0.00),
(118, 1, '2025-07-21 02:10:58', 22500, '', 'kredit', NULL, NULL, NULL, 0.00),
(119, 1, '2025-07-21 02:12:52', 22500, '', 'kredit', NULL, NULL, NULL, 0.00),
(120, 1, '2025-07-21 02:13:23', 20000, '', 'transfer', NULL, NULL, NULL, 0.00),
(121, 1, '2025-07-21 02:14:52', 20000, '', 'transfer', NULL, NULL, NULL, 0.00),
(122, 1, '2025-07-21 02:16:54', 22500, '', 'kredit', NULL, NULL, NULL, 0.00),
(123, 1, '2025-07-21 02:17:24', 22500, '', 'kredit', NULL, NULL, NULL, 0.00),
(124, 1, '2025-07-21 02:20:13', 22500, '', 'kredit', NULL, NULL, NULL, 0.00),
(125, 9, '2025-07-21 02:28:24', 80000, '', 'qris', NULL, NULL, NULL, 0.00),
(126, 9, '2025-07-21 02:31:22', 32500, '', 'kredit', NULL, NULL, NULL, 0.00),
(127, 9, '2025-07-21 02:49:01', 30000, '', 'qris', NULL, NULL, NULL, 0.00),
(128, 9, '2025-07-21 02:54:45', 30000, '', 'transfer', NULL, NULL, NULL, 0.00),
(129, 9, '2025-07-21 03:00:00', 32500, '', 'kredit', NULL, NULL, NULL, 0.00),
(130, 9, '2025-07-21 03:35:42', 60000, '', 'qris', NULL, NULL, NULL, 0.00),
(131, 1, '2025-07-21 03:43:39', 30000, '', 'qris', NULL, NULL, NULL, 0.00),
(132, 10, '2025-07-21 11:02:32', 90000, '', 'qris', NULL, NULL, NULL, 0.00),
(133, 1, '2025-07-21 13:46:35', 30000, '', 'qris', 'diantar', 'jl.pirdaus', '0892683098', 0.00),
(134, 1, '2025-07-21 13:53:08', 30000, '', 'qris', 'dijemput', NULL, NULL, 0.00),
(135, 1, '2025-07-21 14:20:29', 12500, '', 'qris', 'dijemput', NULL, NULL, 0.00),
(136, 1, '2025-07-21 14:23:32', 25000, '', 'qris', 'dijemput', NULL, NULL, 0.00),
(137, 1, '2025-07-21 16:36:21', 12500, '', 'cash', 'diantar', 'jl.pirdaus', '0892683098', 0.00),
(138, 1, '2025-07-21 16:38:01', 30000, '', 'kredit', 'dijemput', NULL, NULL, 0.00),
(139, 1, '2025-07-21 16:39:43', 40000, '', 'transfer', 'dijemput', NULL, NULL, 0.00),
(140, 1, '2025-07-21 16:51:07', 35000, '', 'kredit', 'dijemput', NULL, NULL, 0.00),
(143, 1, '2025-07-21 17:05:38', 42500, '', 'kredit', 'dijemput', NULL, NULL, 0.00),
(144, 1, '2025-07-21 17:14:23', 37500, '', 'kredit', 'dijemput', NULL, NULL, 0.00),
(145, 1, '2025-07-21 17:15:28', 55000, '', 'kredit', 'dijemput', NULL, NULL, 0.00),
(146, 1, '2025-07-21 17:18:22', 42500, '', 'kredit', 'dijemput', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id` int(11) NOT NULL,
  `pembelian_id` int(11) NOT NULL,
  `buah_id` int(11) NOT NULL,
  `berat` decimal(10,2) DEFAULT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id`, `pembelian_id`, `buah_id`, `berat`, `harga`) VALUES
(1, 1, 2, 6.00, 18000),
(2, 1, 3, 1.00, 12000),
(3, 1, 4, 2.00, 22000),
(4, 2, 3, 1.00, 12000),
(5, 3, 3, 1.00, 12000),
(6, 4, 3, 1.00, 12000),
(7, 5, 3, 1.00, 12000),
(8, 6, 2, 1.00, 18000),
(9, 7, 3, 1.00, 12000),
(10, 8, 2, 8.00, 18000),
(11, 9, 3, 2.00, 12000),
(12, 10, 7, 1.00, 10),
(13, 11, 7, 1.00, 10),
(14, 12, 7, 1.00, 10),
(15, 13, 7, 1.00, 10),
(16, 14, 7, 1.00, 10),
(17, 15, 7, 1.00, 10),
(18, 16, 7, 1.00, 10),
(19, 17, 7, 1.00, 10),
(20, 18, 7, 1.00, 10),
(21, 19, 7, 1.00, 10),
(22, 20, 7, 1.00, 10),
(23, 21, 7, 1.00, 10),
(24, 22, 7, 1.00, 10),
(25, 23, 7, 1.00, 10),
(26, 24, 7, 1.00, 10),
(27, 25, 7, 1.00, 10),
(28, 26, 7, 1.00, 10),
(29, 27, 7, 1.00, 10),
(30, 28, 7, 1.00, 10),
(31, 29, 7, 1.00, 10),
(32, 30, 7, 1.00, 10),
(33, 31, 7, 1.00, 10),
(34, 32, 7, 7.00, 10),
(35, 33, 7, 1.00, 10),
(36, 34, 7, 1.00, 10),
(37, 35, 7, 1.00, 10),
(38, 36, 7, 1.00, 10),
(39, 37, 7, 1.00, 10),
(40, 38, 7, 1.00, 10),
(41, 39, 7, 1.00, 10),
(42, 40, 7, 1.00, 10),
(43, 41, 7, 1.00, 10),
(44, 42, 7, 1.00, 10),
(45, 43, 7, 1.00, 10),
(46, 44, 7, 1.00, 10),
(47, 45, 7, 1.00, 10),
(48, 46, 12, 1.00, 20000),
(49, 47, 12, 1.00, 20000),
(50, 48, 12, 1.00, 20000),
(51, 49, 12, 1.00, 20000),
(52, 50, 12, 1.00, 20000),
(53, 51, 12, 1.00, 20000),
(54, 52, 12, 1.00, 20000),
(55, 53, 12, 1.00, 20000),
(56, 54, 12, 1.00, 20000),
(57, 55, 11, 1.00, 20),
(58, 55, 12, 1.00, 20000),
(59, 56, 11, 1.00, 20),
(60, 57, 11, 1.00, 20),
(61, 58, 11, 1.00, 20),
(62, 59, 11, 1.00, 20),
(63, 60, 11, 1.00, 20),
(64, 61, 11, 1.00, 20),
(65, 62, 11, 1.00, 20),
(66, 63, 11, 1.00, 20),
(67, 64, 11, 1.00, 20),
(68, 65, 11, 1.00, 20),
(69, 66, 11, 1.00, 20),
(70, 67, 11, 1.00, 20),
(71, 68, 11, 1.00, 20),
(72, 69, 11, 1.00, 20),
(73, 70, 11, 1.00, 20),
(74, 71, 11, 1.00, 20),
(75, 72, 11, 1.00, 20),
(76, 73, 11, 1.00, 20),
(77, 74, 11, 1.00, 20),
(78, 75, 11, 1.00, 20),
(79, 76, 11, 1.00, 20),
(80, 77, 12, 1.00, 20000),
(81, 78, 11, 4.00, 20),
(82, 79, 12, 3.00, 20000),
(83, 80, 12, 1.00, 20000),
(84, 81, 11, 1.00, 20),
(85, 82, 11, 1.00, 20),
(86, 83, 11, 2.00, 20),
(87, 84, 12, 1.00, 20000),
(88, 85, 12, 1.00, 20000),
(89, 86, 12, 1.00, 20000),
(90, 87, 12, 1.00, 20000),
(91, 88, 12, 1.00, 20000),
(92, 89, 12, 1.00, 20000),
(93, 90, 12, 1.00, 20000),
(94, 91, 12, 1.00, 20000),
(95, 92, 12, 1.00, 20000),
(96, 93, 12, 1.00, 20000),
(97, 94, 12, 1.00, 20000),
(98, 95, 12, 3.00, 20000),
(99, 96, 12, 2.00, 20000),
(100, 97, 12, 1.00, 20000),
(101, 98, 12, 1.00, 20000),
(102, 99, 12, 1.00, 20000),
(103, 100, 13, 1.00, 20000),
(104, 101, 13, 1.00, 20000),
(105, 102, 13, 1.00, 20000),
(106, 103, 13, 1.00, 20000),
(107, 104, 13, 1.00, 20000),
(108, 105, 13, 1.00, 20000),
(109, 106, 13, 1.00, 20000),
(110, 107, 13, 1.00, 20000),
(111, 108, 13, 1.00, 20000),
(112, 109, 13, 1.00, 20000),
(113, 110, 13, 2.00, 20000),
(114, 111, 13, 1.00, 20000),
(115, 112, 13, 1.00, 20000),
(116, 113, 13, 1.00, 20000),
(117, 114, 13, 1.00, 20000),
(118, 115, 13, 1.00, 20000),
(119, 116, 13, 1.00, 20000),
(120, 117, 13, 1.00, 20000),
(121, 118, 13, 1.00, 20000),
(122, 119, 13, 1.00, 20000),
(123, 120, 13, 1.00, 20000),
(124, 121, 13, 1.00, 20000),
(125, 122, 13, 1.00, 20000),
(126, 123, 13, 1.00, 20000),
(127, 124, 13, 1.00, 20000),
(128, 125, 13, 4.00, 20000),
(129, 126, 14, 1.00, 30000),
(130, 127, 14, 1.00, 30000),
(131, 128, 14, 1.00, 30000),
(132, 129, 14, 1.00, 30000),
(133, 130, 14, 2.00, 30000),
(134, 131, 14, 1.00, 30000),
(135, 132, 14, 3.00, 30000),
(136, 133, 14, 1.00, 30000),
(137, 134, 14, 1.00, 30000),
(138, 135, 15, 0.00, 50000),
(139, 136, 15, 1.00, 50000),
(140, 137, 15, 0.00, 50000),
(141, 138, 14, 1.00, 30000),
(142, 138, 15, 0.00, 50000),
(143, 139, 14, 1.00, 30000),
(144, 139, 15, 1.00, 50000),
(145, 140, 14, 0.00, 30000),
(146, 140, 15, 1.00, 50000),
(147, 143, 14, 0.50, 30000),
(148, 143, 15, 0.50, 50000),
(149, 144, 14, 0.75, 30000),
(150, 144, 15, 0.25, 50000),
(151, 145, 14, 0.50, 30000),
(152, 145, 15, 0.75, 50000),
(153, 146, 14, 0.50, 30000),
(154, 146, 15, 0.50, 50000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buah_id` int(11) NOT NULL,
  `pembelian_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `buah_id`, `pembelian_id`, `rating`, `created_at`) VALUES
(1, 9, 14, 127, 5, '2025-07-21 02:49:07'),
(2, 9, 14, 128, 5, '2025-07-21 02:54:50'),
(3, 9, 14, 129, 5, '2025-07-21 03:00:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `review_buah`
--

CREATE TABLE `review_buah` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buah_id` int(11) NOT NULL,
  `pembelian_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(255) DEFAULT '-',
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `review_buah`
--

INSERT INTO `review_buah` (`id`, `user_id`, `buah_id`, `pembelian_id`, `rating`, `review`, `tanggal`) VALUES
(1, 9, 14, 130, 5, '-', '2025-07-21 03:43:01'),
(2, 1, 14, 131, 5, '-', '2025-07-21 03:43:45'),
(3, 10, 14, 132, 4, '-', '2025-07-21 11:02:42'),
(4, 1, 14, 133, 5, '-', '2025-07-21 13:50:00'),
(5, 1, 14, 134, 5, '-', '2025-07-21 13:53:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `aktif`) VALUES
(1, 'alus', '$2y$10$FhrzET1P5wNsZPUgUhUzB.T4D78Y84tqlnzF6rPnPg34QVSRloHGq', 'aluspermadani91@gmail.com', 0, 1),
(2, 'kiko', '$2y$10$FnWfcs8r.dQZRacTW9aSzu4XDc2W7m0CSJuFVr1eIy1gyYIFSHi5.', 'dsada@gmail.com', 0, 1),
(3, 'aluspermadaniii', '$2y$10$Cr6RZ.1ZhjuCLLI2oIQKUObObv7eAjuSVPcsaRxWel/OBIrrehhgS', 'dsada1@gmail.com', 0, 1),
(4, 'admin', '$2y$10$E73WD2AgnHpS2HMVOzVvSeXRzqoBpqthw7SU/JdjCoAsGj6fYq0lO', 'admin@gmail.com', 1, 1),
(6, 'monica', '$2y$10$6JO.9ehuFequnrlQzUYtae2PHmMI5EOvvit7raRnfNHSD97vxCUOe', 'uk1808@iryze.cloud', 0, 1),
(7, 'zana', '$2y$10$12LrOIc5wow3td4DM3mG3ei0FjTa6tyi/1aRkVwP04t05oPbA9lLe', 'zanakyut@gmail.com', 0, 1),
(8, 'aluss', '$2y$10$ViG.FUHAVZ4ArFTq7O3wyOnArIgK1kswANxMyRlS.trDUODD7PUxq', 'apopo@gmail.com', 0, 1),
(9, 'auci', '$2y$10$pDWzCzFB6u.Hho5KaMFn6OBvyFdtMpwtOCcSA4XRnmPoex3USsGLe', 'auci@gmail.com', 0, 1),
(10, 'ari', '$2y$10$BhLRFgCq/jahO467Y6CdoOUKfqVvTh70IAtzP2CZm6lvcD9Mdga5K', 'ari@gmail.com', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buah`
--
ALTER TABLE `buah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelian_id` (`pembelian_id`),
  ADD KEY `buah_id` (`buah_id`);

--
-- Indeks untuk tabel `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `review_buah`
--
ALTER TABLE `review_buah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `buah_id` (`buah_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buah`
--
ALTER TABLE `buah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT untuk tabel `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `review_buah`
--
ALTER TABLE `review_buah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `review_buah`
--
ALTER TABLE `review_buah`
  ADD CONSTRAINT `review_buah_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `review_buah_ibfk_2` FOREIGN KEY (`buah_id`) REFERENCES `buah` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
