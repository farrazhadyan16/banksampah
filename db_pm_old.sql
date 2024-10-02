-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Sep 2024 pada 06.46
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
-- Database: `db_pm_old`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dompet`
--

CREATE TABLE `dompet` (
  `id` int(11) NOT NULL,
  `id_user` int(100) DEFAULT NULL,
  `uang` decimal(11,2) NOT NULL DEFAULT 0.00,
  `emas` decimal(65,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `dompet`
--

INSERT INTO `dompet` (`id`, `id_user`, `uang`, `emas`) VALUES
(19, 14, 4048000.06, 6.5007),
(20, 19, 559000.00, 1.6785),
(23, NULL, 0.00, 0.0000),
(24, NULL, 0.00, 0.0000),
(25, NULL, 0.00, 0.0000),
(26, NULL, 0.00, 0.0000),
(27, NULL, 0.00, 0.0000),
(28, NULL, 0.00, 0.0000),
(29, 25, 38000.00, 0.1049),
(30, 22, 0.00, 0.0000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jual_sampah`
--

CREATE TABLE `jual_sampah` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `id_sampah` varchar(200) NOT NULL,
  `jumlah_kg` decimal(65,2) NOT NULL,
  `harga_nasabah` decimal(11,2) NOT NULL,
  `jumlah_rp` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `jual_sampah`
--

INSERT INTO `jual_sampah` (`no`, `id_transaksi`, `id_sampah`, `jumlah_kg`, `harga_nasabah`, `jumlah_rp`) VALUES
(2, 'TRANS2024000097', 'S003', 1.00, 1200.00, 1300.00),
(4, 'TRANS2024000099', 'S009', 3.00, 6000.00, 5700.00),
(5, 'TRANS2024000108', 'S008', 1.00, 2000.00, 3000.00),
(6, 'TRANS2024000111', 'S009', 1.00, 2000.00, 2200.00),
(7, 'TRANS2024000112', 'S003', 10.00, 12000.00, 13000.00),
(8, 'TRANS2024000112', 'S009', 1.00, 2000.00, 2200.00),
(10, 'TRANS2024000115', 'S003', 4.00, 4800.00, 5200.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_sampah`
--

CREATE TABLE `kategori_sampah` (
  `id` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `kategori_sampah`
--

INSERT INTO `kategori_sampah` (`id`, `name`, `created_at`) VALUES
('KS01', 'plastik', 1663061754),
('KS02', 'kertas', 1663061762),
('KS03', 'logam', 1663061767),
('KS04', 'lain-lain', 1663062026),
('KS05', 'kat xx', 1663151087),
('KS06', 'kat yy', 1663151392),
('KS08', 'minyak jelantah', 0),
('KS09', 'limbah ART', 0),
('KS10', 'kaleng ', 0),
('KS11', 'kardus', 0),
('KS13', 'besi', 0),
('KS14', 'pakaian', 0),
('KS15', 'kaca', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pindah_saldo`
--

CREATE TABLE `pindah_saldo` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `jenis_konversi` enum('konversi_uang','konversi_emas') NOT NULL,
  `jumlah` float NOT NULL,
  `harga_jual_emas` int(11) NOT NULL DEFAULT 0,
  `harga_beli_emas` int(11) NOT NULL DEFAULT 0,
  `hasil_konversi` decimal(65,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `pindah_saldo`
--

INSERT INTO `pindah_saldo` (`no`, `id_transaksi`, `jenis_konversi`, `jumlah`, `harga_jual_emas`, `harga_beli_emas`, `hasil_konversi`) VALUES
(26, 'TRANS2024000092', 'konversi_uang', 1000, 0, 1213637, 0.0008),
(27, 'TRANS2024000093', 'konversi_emas', 0.002, 1243978, 0, 2487.9560),
(28, 'TRANS2024000102', 'konversi_uang', 91500, 0, 1216144, 0.0752);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sampah`
--

CREATE TABLE `sampah` (
  `id` varchar(200) NOT NULL,
  `id_kategori` varchar(200) NOT NULL,
  `jenis` varchar(40) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_pusat` int(11) DEFAULT 0,
  `jumlah` decimal(65,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `sampah`
--

INSERT INTO `sampah` (`id`, `id_kategori`, `jenis`, `harga`, `harga_pusat`, `jumlah`) VALUES
('S003', 'KS03', 'logam jenis a', 1430, 1300, 124.00),
('S004', 'KS04', 'lainnya jenis a', 1200, 1300, 10.00),
('S005', 'KS05', 'xx jenis 1', 1000, 1200, 0.00),
('S008', 'KS01', 'plastik botol aqua', 2000, 3000, 40.00),
('S009', 'KS02', 'kertas karton', 2000, 2200, 57.00),
('S010', 'KS09', 'sampah sabun', 2000, 2100, 0.00),
('S011', 'KS10', 'kaleng minuman', 2000, 1900, 0.00),
('S012', 'KS11', 'kardus makanan', 2000, 1900, 8.00),
('S013', 'KS13', 'besi tua', 3000, 2900, 28.00),
('S015', 'KS01', 'plastik hitam', 4000, 6000, 0.00),
('S016', 'KS15', 'kaca cermin', 2000, 2400, 0.10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setor_sampah`
--

CREATE TABLE `setor_sampah` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `id_sampah` varchar(200) NOT NULL,
  `jumlah_kg` decimal(65,2) NOT NULL,
  `jumlah_rp` decimal(11,2) NOT NULL,
  `jumlah_emas` decimal(65,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `setor_sampah`
--

INSERT INTO `setor_sampah` (`no`, `id_transaksi`, `id_sampah`, `jumlah_kg`, `jumlah_rp`, `jumlah_emas`) VALUES
(82194, 'TRANS2024000001', 'S009', 12.00, 24000.00, 0.0000),
(82195, 'TRANS2024000098', 'S003', 6.00, 7200.00, 0.0000),
(82197, 'TRANS2024000100', 'S013', 25.00, 75000.00, 0.0000),
(82199, 'TRANS2024000100', 'S012', 2.00, 4000.00, 0.0000),
(82200, 'TRANS2024000104', 'S009', 19.00, 38000.00, 0.0000),
(82201, 'TRANS2024000105', 'S008', 40.00, 80000.00, 0.0000),
(82202, 'TRANS2024000109', 'S003', 10.00, 12000.00, 0.0000),
(82203, 'TRANS2024000109', 'S009', 11.00, 22000.00, 0.0000),
(82205, 'TRANS2024000109', 'S012', 2.00, 4000.00, 0.0000),
(82206, 'TRANS2024000110', 'S012', 4.00, 8000.00, 0.0000),
(82207, 'TRANS2024000110', 'S003', 5.00, 6000.00, 0.0000),
(82213, 'TRANS2024000125', 'S003', 10.00, 12000.00, 0.0096),
(82214, 'TRANS2024000127', 'S003', 100.00, 120000.00, 0.0964),
(82215, 'TRANS2024000129', 'S016', 0.10, 200.00, 0.0002),
(82216, 'TRANS2024000129', 'S008', 1.00, 2000.00, 0.0016),
(82217, 'TRANS2024000129', 'S013', 3.00, 9000.00, 0.0072);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarik_saldo`
--

CREATE TABLE `tarik_saldo` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `jenis_saldo` enum('tarik_uang','tarik_emas') NOT NULL,
  `jumlah_tarik` decimal(65,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tarik_saldo`
--

INSERT INTO `tarik_saldo` (`no`, `id_transaksi`, `jenis_saldo`, `jumlah_tarik`) VALUES
(37, 'TRANS2024000094', 'tarik_uang', 40000.0000),
(38, 'TRANS2024000095', 'tarik_emas', 1.0000),
(39, 'TRANS2024000096', 'tarik_uang', 100000.0000),
(40, 'TRANS2024000106', 'tarik_uang', 200.0000),
(41, 'TRANS2024000107', 'tarik_emas', 0.0000),
(42, 'TRANS2024000113', 'tarik_uang', 11745.0000),
(43, 'TRANS2024000126', 'tarik_uang', 100000.0000),
(44, 'TRANS2024000128', 'tarik_uang', 50000.0000),
(45, 'TRANS2024000131', 'tarik_uang', 200000.0000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `no` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `id_user` int(100) NOT NULL,
  `jenis_transaksi` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`no`, `id`, `id_user`, `jenis_transaksi`, `date`, `time`) VALUES
(91, 'TRANS2024000001', 19, 'setor_sampah', '2024-07-29', '10:05:14'),
(92, 'TRANS2024000092', 19, 'pindah_saldo', '2024-08-29', '10:08:55'),
(93, 'TRANS2024000093', 19, 'pindah_saldo', '2024-08-29', '10:14:28'),
(94, 'TRANS2024000094', 19, 'tarik_saldo', '2024-08-29', '10:19:36'),
(95, 'TRANS2024000095', 19, 'tarik_saldo', '2024-08-29', '10:20:07'),
(96, 'TRANS2024000096', 19, 'tarik_saldo', '2024-08-29', '10:22:14'),
(97, 'TRANS2024000097', 16, 'jual_sampah', '2024-08-29', '10:22:32'),
(98, 'TRANS2024000098', 19, 'setor_sampah', '2024-08-29', '14:32:07'),
(99, 'TRANS2024000099', 16, 'jual_sampah', '2024-08-29', '14:37:39'),
(100, 'TRANS2024000100', 25, 'setor_sampah', '2024-09-03', '08:23:13'),
(101, 'TRANS2024000101', 25, 'pindah_saldo', '2024-09-03', '08:36:04'),
(102, 'TRANS2024000102', 25, 'pindah_saldo', '2024-09-03', '08:38:42'),
(103, 'TRANS2024000103', 22, 'setor_sampah', '2024-09-03', '13:20:36'),
(104, 'TRANS2024000104', 25, 'setor_sampah', '2024-09-03', '13:32:49'),
(105, 'TRANS2024000105', 19, 'setor_sampah', '2024-09-04', '10:23:26'),
(106, 'TRANS2024000106', 19, 'tarik_saldo', '2024-09-04', '10:25:36'),
(107, 'TRANS2024000107', 19, 'tarik_saldo', '2024-09-04', '10:26:00'),
(108, 'TRANS2024000108', 16, 'jual_sampah', '2024-09-04', '10:26:22'),
(109, 'TRANS2024000109', 19, 'setor_sampah', '2024-09-04', '14:43:26'),
(110, 'TRANS2024000110', 19, 'setor_sampah', '2024-09-05', '10:56:33'),
(111, 'TRANS2024000111', 16, 'jual_sampah', '2024-09-05', '10:58:40'),
(112, 'TRANS2024000112', 16, 'jual_sampah', '2024-09-09', '11:29:14'),
(113, 'TRANS2024000113', 19, 'tarik_saldo', '2024-09-16', '22:56:24'),
(114, 'TRANS2024000114', 16, 'jual_sampah', '2024-09-16', '23:07:14'),
(115, 'TRANS2024000115', 16, 'jual_sampah', '2024-09-16', '23:08:01'),
(116, 'TRANS2024000116', 25, 'setor_sampah', '2024-09-16', '23:09:01'),
(118, 'TRANS2024000117', 25, 'setor_sampah', '2024-09-16', '23:10:40'),
(119, 'TRANS2024000119', 19, 'setor_sampah', '2024-09-16', '23:26:54'),
(120, 'TRANS2024000120', 19, 'setor_sampah', '2024-09-16', '23:30:49'),
(121, 'TRANS2024000121', 19, 'setor_sampah', '2024-09-16', '23:31:31'),
(122, 'TRANS2024000122', 25, 'setor_sampah', '2024-09-16', '23:34:40'),
(123, 'TRANS2024000123', 25, 'setor_sampah', '2024-09-16', '23:35:40'),
(124, 'TRANS2024000124', 25, 'setor_sampah', '2024-09-17', '08:12:22'),
(125, 'TRANS2024000125', 25, 'setor_sampah', '2024-09-17', '08:18:04'),
(126, 'TRANS2024000126', 25, 'tarik_saldo', '2024-09-17', '08:21:10'),
(127, 'TRANS2024000127', 19, 'setor_sampah', '2024-09-17', '08:35:18'),
(128, 'TRANS2024000128', 25, 'tarik_saldo', '2024-09-17', '08:54:18'),
(130, 'TRANS2024000129', 25, 'setor_sampah', '2024-09-17', '10:12:17'),
(131, 'TRANS2024000131', 25, 'tarik_saldo', '2024-09-17', '10:19:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_tb`
--

CREATE TABLE `transaksi_tb` (
  `nomor` int(11) NOT NULL,
  `id_trans` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `kategori_id` varchar(11) NOT NULL,
  `jenis_id` varchar(20) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_tb`
--

INSERT INTO `transaksi_tb` (`nomor`, `id_trans`, `user_id`, `tanggal`, `waktu`, `kategori_id`, `jenis_id`, `jumlah`, `harga`) VALUES
(127, 'TRANS2024000001', 19, '2024-08-20', '14:20:00', 'KS01', 'S007', 2, 2000),
(149, 'TRANS2024000128', 19, '2024-08-24', '19:46:00', 'KS01', 'S008', 31, 62000),
(150, 'TRANS2024000150', 14, '2024-08-24', '01:12:00', 'KS06', 'S006', 1, 1000),
(151, 'TRANS2024000151', 14, '2024-08-24', '01:35:00', 'KS01', 'S007', 3, 3000),
(152, 'TRANS2024000151', 14, '2024-08-24', '01:35:00', 'KS01', 'S008', 1, 2000),
(153, 'TRANS2024000151', 14, '2024-08-24', '01:35:00', 'KS05', 'S005', 5, 5000),
(154, 'TRANS2024000154', 14, '2024-08-25', '18:16:00', 'KS02', 'S009', 1, 2000),
(155, 'TRANS2024000155', 19, '2024-08-25', '18:17:00', 'KS02', 'S009', 1, 2000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(200) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `notelp` varchar(200) DEFAULT NULL,
  `nik` varchar(200) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tgl_lahir` date NOT NULL,
  `kelamin` enum('Pria','Wanita') NOT NULL,
  `role` enum('superadmin','admin','nasabah') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `nama`, `notelp`, `nik`, `alamat`, `tgl_lahir`, `kelamin`, `role`, `created_at`, `status`) VALUES
(12, 'superadminn', 'superadminn', 'superadminn', 'superadminn', '1234567891', '1111111111', 'walkot', '2024-08-09', 'Pria', 'superadmin', '2024-08-09 03:03:30', 0),
(13, 'adminn', 'admin', 'adminn', 'adminn', '1234567892', '11111111111111112', 'walkot', '2024-08-09', 'Pria', 'admin', '2024-08-21 18:10:00', 0),
(14, 'nasabahh', 'nasabah1', 'nasabahh', 'nasabahh', '08545635636', '2222222222222222', 'walkota', '2024-08-09', 'Pria', 'nasabah', '2024-08-26 01:53:56', 0),
(16, NULL, 'admin1', '$2y$10$BPZokWUf/a5yxZmQQisy0eh8zJGH3cGX759ncDnB2dUofUPmzo.t.', 'admin1', NULL, NULL, NULL, '0000-00-00', 'Pria', 'admin', '2024-08-09 03:06:30', 0),
(18, NULL, 'superadmin1', '$2y$10$qN/MzRFZmxWyXXi22kP7sObElymNXMxIUa6xV9PHl3vCRNdKD2216', 'superadmin1', NULL, NULL, NULL, '0000-00-00', 'Pria', 'superadmin', '2024-08-09 03:09:15', 0),
(19, 'mardhyah@gmail.com', 'dhyah', '$2y$10$wpkViCyovh0ylNzVEL2rM.RLejy8UPxQ6VV.qQAItWzES0KO1GHVm', 'mardhyahh', '', '1111111111111111', 'walkot1', '2024-08-01', 'Wanita', 'nasabah', '2024-09-06 03:27:34', 0),
(21, NULL, 'mardhyah', '$2y$10$vRHpDHIejyiBJeK7jO75m.5tenjJolUAqwQdL5hh.svblgSb1l9jO', 'mardhyah fathania', NULL, NULL, NULL, '0000-00-00', 'Pria', 'nasabah', '2024-09-06 04:27:50', 1),
(22, 'test4@gmail.com', 'nasabah2', '$2y$10$MDMlbhMj3tJQB3/CVjZ.gemVh8Tnvy1E5vM1iLhaFmn2.6viafwp6', 'dhyah2', '084578324832', '3333333333333333', 'sungai sapih', '2024-06-21', '', 'nasabah', '2024-09-06 04:26:38', 1),
(24, 'test5@gmail.com', 'fathania1', '$2y$10$gzs5eU.B8MfA0pyPnvJS0uRMRU4JvOXygf91F8oAbRQaC2V/LJE6K', 'fathania', '085456356366', '5555555555555555', 'walkot', '2024-08-14', 'Pria', 'nasabah', '2024-09-02 07:23:05', 0),
(25, 'test7@gmail.com', 'agus1', '$2y$10$0r28ZpzlFdxsCdyKZ22tiOJDZsbTlAkvwj2nllhJTUwcatxAsjBCS', 'agus', '08643756474', '1301060111830003', 'padang', '2024-09-03', 'Pria', 'nasabah', '2024-09-03 01:07:03', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dompet`
--
ALTER TABLE `dompet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `jual_sampah`
--
ALTER TABLE `jual_sampah`
  ADD PRIMARY KEY (`no`),
  ADD KEY `jual_sampah_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `jual_sampah_id_sampah_foreign` (`id_sampah`);

--
-- Indeks untuk tabel `kategori_sampah`
--
ALTER TABLE `kategori_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  ADD PRIMARY KEY (`no`),
  ADD KEY `pindah_saldo_id_transaksi_foreign` (`id_transaksi`);

--
-- Indeks untuk tabel `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis` (`jenis`),
  ADD KEY `sampah_id_kategori_foreign` (`id_kategori`);

--
-- Indeks untuk tabel `setor_sampah`
--
ALTER TABLE `setor_sampah`
  ADD PRIMARY KEY (`no`),
  ADD KEY `setor_sampah_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `setor_sampah_id_sampah_foreign` (`id_sampah`);

--
-- Indeks untuk tabel `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  ADD PRIMARY KEY (`no`),
  ADD KEY `tarik_saldo_id_transaksi_foreign` (`id_transaksi`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id_user_foreign` (`id_user`),
  ADD KEY `no` (`no`);

--
-- Indeks untuk tabel `transaksi_tb`
--
ALTER TABLE `transaksi_tb`
  ADD PRIMARY KEY (`nomor`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `notelp` (`notelp`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dompet`
--
ALTER TABLE `dompet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `jual_sampah`
--
ALTER TABLE `jual_sampah`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `setor_sampah`
--
ALTER TABLE `setor_sampah`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82218;

--
-- AUTO_INCREMENT untuk tabel `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT untuk tabel `transaksi_tb`
--
ALTER TABLE `transaksi_tb`
  MODIFY `nomor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dompet`
--
ALTER TABLE `dompet`
  ADD CONSTRAINT `dompet_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jual_sampah`
--
ALTER TABLE `jual_sampah`
  ADD CONSTRAINT `jual_sampah_id_sampah_foreign` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jual_sampah_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  ADD CONSTRAINT `pindah_saldo_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sampah`
--
ALTER TABLE `sampah`
  ADD CONSTRAINT `sampah_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `setor_sampah`
--
ALTER TABLE `setor_sampah`
  ADD CONSTRAINT `setor_sampah_id_sampah_foreign` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `setor_sampah_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  ADD CONSTRAINT `tarik_saldo_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
