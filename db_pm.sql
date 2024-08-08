-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2024 at 03:12 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `login_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`login_id`, `username`, `password`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'design', 'e10adc3949ba59abbe56e057f20f883e'),
(6, 'program', 'e10adc3949ba59abbe56e057f20f883e'),
(7, 'checker', 'e10adc3949ba59abbe56e057f20f883e'),
(8, 'nesting', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `admin_akses`
--

CREATE TABLE `admin_akses` (
  `login_id` int(11) NOT NULL,
  `akses_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_akses`
--

INSERT INTO `admin_akses` (`login_id`, `akses_id`) VALUES
(1, 'design'),
(1, 'nesting'),
(3, 'design'),
(6, 'program'),
(7, 'checker'),
(1, 'program'),
(1, 'checker'),
(8, 'nesting'),
(1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `data_monitoring`
--

CREATE TABLE `data_monitoring` (
  `id` varchar(11) NOT NULL,
  `no_jo` varchar(255) DEFAULT NULL,
  `tgl_jo` date DEFAULT NULL,
  `nama_project` varchar(255) NOT NULL,
  `kode_gbj` varchar(255) DEFAULT NULL,
  `nilai_harga` decimal(10,2) DEFAULT NULL,
  `nama_panel` varchar(255) NOT NULL,
  `tipe_jenis` varchar(10) DEFAULT NULL,
  `tipe_fswm` varchar(4) DEFAULT NULL,
  `qty_unit` int(5) DEFAULT NULL,
  `qty_cell` int(5) DEFAULT NULL,
  `warna` varchar(255) DEFAULT NULL,
  `nomor_wo` int(255) DEFAULT NULL,
  `nomor_seri` int(255) DEFAULT NULL,
  `size_panel_height` int(11) NOT NULL,
  `size_panel_width` int(11) NOT NULL,
  `size_panel_deep` int(11) NOT NULL,
  `mh_std` date DEFAULT NULL,
  `mh_aktual` date DEFAULT NULL,
  `tgl_submit_dwg_for_approval` date DEFAULT NULL,
  `tgl_approved` date DEFAULT NULL,
  `tgl_release_dwg_softcopy` date DEFAULT NULL,
  `tgl_release_dwg_hardcopy` date NOT NULL,
  `breakdown` date NOT NULL,
  `busbar` date NOT NULL,
  `target_ppc` date NOT NULL,
  `target_eng` date NOT NULL,
  `design_pic` varchar(11) NOT NULL,
  `design_start` date NOT NULL,
  `design_end` date NOT NULL,
  `nesting_pic` varchar(11) NOT NULL,
  `nesting_start` date NOT NULL,
  `nesting_end` date NOT NULL,
  `program_pic` varchar(11) NOT NULL,
  `program_start` date NOT NULL,
  `program_end` date NOT NULL,
  `checker_pic` varchar(11) NOT NULL,
  `checker_start` date NOT NULL,
  `checker_end` date NOT NULL,
  `tgl_box_selesai` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `tgl_terbit_wo` date DEFAULT NULL,
  `plan_start_produksi` date DEFAULT NULL,
  `aktual_start_produksi` date DEFAULT NULL,
  `plan_fg_wo` date DEFAULT NULL,
  `aktual_fg_wo` date DEFAULT NULL,
  `progress` float DEFAULT NULL,
  `desc_progress` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `delivery_no` int(255) DEFAULT NULL,
  `delivery_tgl` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_monitoring`
--

INSERT INTO `data_monitoring` (`id`, `no_jo`, `tgl_jo`, `nama_project`, `kode_gbj`, `nilai_harga`, `nama_panel`, `tipe_jenis`, `tipe_fswm`, `qty_unit`, `qty_cell`, `warna`, `nomor_wo`, `nomor_seri`, `size_panel_height`, `size_panel_width`, `size_panel_deep`, `mh_std`, `mh_aktual`, `tgl_submit_dwg_for_approval`, `tgl_approved`, `tgl_release_dwg_softcopy`, `tgl_release_dwg_hardcopy`, `breakdown`, `busbar`, `target_ppc`, `target_eng`, `design_pic`, `design_start`, `design_end`, `nesting_pic`, `nesting_start`, `nesting_end`, `program_pic`, `program_start`, `program_end`, `checker_pic`, `checker_start`, `checker_end`, `tgl_box_selesai`, `due_date`, `tgl_terbit_wo`, `plan_start_produksi`, `aktual_start_produksi`, `plan_fg_wo`, `aktual_fg_wo`, `progress`, `desc_progress`, `status`, `delivery_no`, `delivery_tgl`, `keterangan`) VALUES
('1', '005-CBD/MH/I/2021', '2024-01-26', 'Fatmawati City Centre Jakarta', '5PM.B005.00.21.087', '5.00', ' PP - KGR.LD/AP.T4', 'WM ID', '', 0, 0, '', 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL, 16, '', '', 0, '0000-00-00', ''),
('2', '005-T3.CBD/MH/I/2021', '2023-11-29', 'Fatmawati City Centre Jakarta', '5PM.B005.T3.21.007', '30.95', ' DP-PUMP.B1/AP.T3', '', 'FS', 0, 0, '', 0, 23, 53, 10, 2, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', 'DAWEI', '2024-03-02', '2024-03-09', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL, 0, '', '', 0, '0000-00-00', ''),
('3', '005-T3.CBD/MH/I/2021', '2024-02-15', 'Fatmawati City Centre Jakarta', '5PM.B005.T3.21.023', '5.39', ' LP - STAIR / AP.T3', '', '', 0, 0, '', 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 'ST', '2024-02-28', '2024-03-05', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL, 0, '', '', 0, '0000-00-00', ''),
('4', '005-T3.CBD/MH/I/2021', '2024-03-03', 'Fatmawati City Centre Jakarta', '5PM.B005.T3.21.038', '30.95', '', '', 'FS', 0, 0, 'Hitam', 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', 'AE', '2024-02-29', '2024-03-03', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL, 0, '', '', 0, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `konsesi`
--

CREATE TABLE `konsesi` (
  `id_konsesi` varchar(5) NOT NULL,
  `jo` varchar(255) DEFAULT NULL,
  `wo` varchar(255) DEFAULT NULL,
  `nama_project` varchar(255) DEFAULT NULL,
  `nama_panel` varchar(255) DEFAULT NULL,
  `unit` int(5) DEFAULT NULL,
  `jenis` varchar(5) DEFAULT NULL,
  `no_rpb` int(11) NOT NULL,
  `no_po` int(11) NOT NULL,
  `kode_material` varchar(50) NOT NULL,
  `konsesi` varchar(255) DEFAULT NULL,
  `jumlah` int(5) DEFAULT NULL,
  `no_lkpj` varchar(10) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `tgl_matrial_dtg` date DEFAULT NULL,
  `tgl_pasang` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `konsesi`
--

INSERT INTO `konsesi` (`id_konsesi`, `jo`, `wo`, `nama_project`, `nama_panel`, `unit`, `jenis`, `no_rpb`, `no_po`, `kode_material`, `konsesi`, `jumlah`, `no_lkpj`, `status`, `tgl_matrial_dtg`, `tgl_pasang`, `keterangan`) VALUES
('1', '049/BLD/2017', 'DPS-0400416', 'MENARA KOMPAS', 'PUTM', 1, 'TM', 0, 0, '', 'HANDLE VCB DRAWOUT', 1, '134', 'Open', '0000-00-00', '0000-00-00', 'Dikirim Project koordinator');

-- --------------------------------------------------------

--
-- Table structure for table `master_akses`
--

CREATE TABLE `master_akses` (
  `akses_id` varchar(10) NOT NULL,
  `nama` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `master_akses`
--

INSERT INTO `master_akses` (`akses_id`, `nama`) VALUES
('admin', 'Admin'),
('checker', 'Checker'),
('design', 'Design'),
('nesting', 'Nesting'),
('program', 'Program');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `admin_akses`
--
ALTER TABLE `admin_akses`
  ADD KEY `akses_id` (`akses_id`),
  ADD KEY `login_id` (`login_id`);

--
-- Indexes for table `data_monitoring`
--
ALTER TABLE `data_monitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konsesi`
--
ALTER TABLE `konsesi`
  ADD PRIMARY KEY (`id_konsesi`);

--
-- Indexes for table `master_akses`
--
ALTER TABLE `master_akses`
  ADD PRIMARY KEY (`akses_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_akses`
--
ALTER TABLE `admin_akses`
  ADD CONSTRAINT `admin_akses_ibfk_1` FOREIGN KEY (`akses_id`) REFERENCES `master_akses` (`akses_id`),
  ADD CONSTRAINT `admin_akses_ibfk_2` FOREIGN KEY (`login_id`) REFERENCES `admin` (`login_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
