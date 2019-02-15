-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2019 at 03:46 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE `bank` (
  `kd_bank` int(11) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`kd_bank`, `nm`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(21, 'Mandiri', 'Active', '2019-02-01 22:48:44', '2019-02-01 22:48:44', NULL),
(22, 'BCA', 'Active', '2019-02-02 00:13:19', '2019-02-02 00:13:19', NULL),
(23, 'Test Bank', 'Active', '2019-02-13 14:11:03', '2019-02-13 14:11:07', '2019-02-13 14:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `brg`
--

DROP TABLE IF EXISTS `brg`;
CREATE TABLE `brg` (
  `kd` varchar(20) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `kelompok_id` int(11) NOT NULL,
  `merk_id` int(11) NOT NULL,
  `status` varchar(15) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `pcs` int(5) NOT NULL,
  `hrgb` int(12) NOT NULL,
  `hrgp` int(12) NOT NULL,
  `jual` int(12) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='hargab adalah harga beli, hargap adalah harga pokok';

--
-- Dumping data for table `brg`
--

INSERT INTO `brg` (`kd`, `nm`, `kelompok_id`, `merk_id`, `status`, `satuan`, `pcs`, `hrgb`, `hrgp`, `jual`, `created_at`, `updated_at`, `deleted_at`) VALUES
('BRG001', 'Barang 1', 1, 1, 'Ak', 'PCS', 123, 234, 234, 1000, '2019-02-06 10:41:53', '2019-02-06 11:10:05', NULL),
('BRG002', 'Jenis 1', 1, 1, 'Ak', 'PCS', 123, 234, 234, 2000, '2019-02-06 10:41:53', '2019-02-06 11:10:05', NULL),
('BRG003', 'Aqua', 1, 1, 'Ak', 'PCS', 123, 234, 234, 3000, '2019-02-06 10:41:53', '2019-02-06 11:10:05', NULL),
('BRG004', 'Barang 2', 1, 1, 'Ak', 'PCS', 123, 234, 234, 4000, '2019-02-06 10:41:53', '2019-02-06 11:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `kd` varchar(50) NOT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `nm_toko` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `alias` varchar(25) DEFAULT NULL,
  `kota_id` int(11) DEFAULT NULL,
  `tlpn` varchar(15) DEFAULT NULL,
  `nmtk` varchar(50) DEFAULT NULL,
  `kontak` varchar(25) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `plafon` int(11) DEFAULT NULL,
  `top` int(11) DEFAULT NULL,
  `npwp` varchar(25) DEFAULT NULL,
  `nik` varchar(25) DEFAULT NULL,
  `jenis` varchar(25) DEFAULT NULL,
  `insert_user` varchar(25) DEFAULT NULL,
  `update_user` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`kd`, `nm`, `nm_toko`, `alamat`, `alias`, `kota_id`, `tlpn`, `nmtk`, `kontak`, `fax`, `plafon`, `top`, `npwp`, `nik`, `jenis`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
('CST001', 'Jamal Apriadi', 'Maju Jaya', 'Jakarta', 'jamal', 1, '1234', '23', '234', '234', 234, 5, '23432', '1234', 'Wk', 'jamal.apriadi', NULL, '2019-02-05 16:18:09', '2019-02-13 14:23:45', NULL),
('CST002', 'Wiwik Putri', NULL, 'Pangkah', 'wiwi', 1, '123', '234', '234', '234', 123, 234, '1234', '1234', 'sdf', 'jamal.apriadi', NULL, '2019-02-05 16:19:12', '2019-02-05 16:19:12', NULL),
('CST003', 'Dwi Maulidia', NULL, 'Debong', '234', 1, '234', '234', '234', '234', 234, 234, '234', '234', '234', 'jamal.apriadi', NULL, '2019-02-05 16:19:45', '2019-02-05 16:23:35', NULL),
('CS0005', 'Nazar', 'Larva', 'T', 'Zulmi', 1, '234', NULL, NULL, NULL, 234, 23, '234534', '234', NULL, 'jamal.apriadi', NULL, '2019-02-13 14:24:42', '2019-02-13 14:24:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ket`
--

DROP TABLE IF EXISTS `ket`;
CREATE TABLE `ket` (
  `no_hp` varchar(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pin` varchar(9) NOT NULL,
  `npwp` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ket`
--

INSERT INTO `ket` (`no_hp`, `email`, `pin`, `npwp`, `created_at`, `updated_at`, `deleted_at`) VALUES
('082242783450', 'wqr3h2@gmail.com', '8ew8t', 'ewnjwjg9', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `klmpk`
--

DROP TABLE IF EXISTS `klmpk`;
CREATE TABLE `klmpk` (
  `id` int(11) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `klmpk`
--

INSERT INTO `klmpk` (`id`, `nm`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kelompok 1', '2019-02-06 09:33:37', '2019-02-06 09:33:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

DROP TABLE IF EXISTS `kota`;
CREATE TABLE `kota` (
  `kd_kota` int(11) UNSIGNED NOT NULL,
  `nm` varchar(25) NOT NULL,
  `jenis` enum('Luar Kota','Dalam Kota') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`kd_kota`, `nm`, `jenis`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Palu', 'Dalam Kota', NULL, NULL, NULL),
(2, 'Jakarta', 'Luar Kota', '2019-02-02 00:51:38', '2019-02-02 00:58:09', NULL),
(3, 'Jakarta', 'Luar Kota', '2019-02-02 00:51:49', '2019-02-02 00:55:33', '2019-02-02 00:55:33'),
(4, 'Bandung', 'Luar Kota', '2019-02-02 00:52:37', '2019-02-02 00:52:37', NULL),
(5, 'Surabaya', 'Dalam Kota', '2019-02-02 00:53:08', '2019-02-02 00:57:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE `lokasi` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(25) DEFAULT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `lokasi`, `nm`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Lokasi A', 'Nama A', '2019-02-05 15:55:20', '2019-02-05 15:55:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merk`
--

DROP TABLE IF EXISTS `merk`;
CREATE TABLE `merk` (
  `id` int(3) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `persen` int(3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `merk`
--

INSERT INTO `merk` (`id`, `nm`, `persen`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Merk 1', 5, '2019-02-06 09:42:42', '2019-02-06 09:42:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

DROP TABLE IF EXISTS `mutasi`;
CREATE TABLE `mutasi` (
  `no_mutasi` varchar(20) NOT NULL,
  `lokasil` varchar(15) NOT NULL,
  `lokasib` varchar(15) NOT NULL,
  `ket` varchar(250) NOT NULL,
  `tgl` date NOT NULL,
  `perusahaan` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `no_order` varchar(20) NOT NULL,
  `kd_picking` varchar(50) DEFAULT NULL,
  `kd_trans` varchar(15) DEFAULT NULL,
  `tgljt` date DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `ket` varchar(150) DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `perusahaan_id` varchar(50) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='tgljt adalah tgl jatuh tempo, akan keisi jika kd_trans yang dipilih adalah kredit,\r\nformat no_order nanti nya adalah 19-000001 dan seterusnya,\r\nangka 19 adalah tahun sekarang';

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`no_order`, `kd_picking`, `kd_trans`, `tgljt`, `tgl`, `ket`, `sales_id`, `perusahaan_id`, `total`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
('19-000001', 'PCK-TLG-19-000001', 'Tunai', '2019-02-15', '2019-02-15', NULL, NULL, '1', 5000, 'jamal.apriadi', 'jamal.apriadi', '2019-02-15 01:43:58', '2019-02-15 01:43:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

DROP TABLE IF EXISTS `perusahaan`;
CREATE TABLE `perusahaan` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id`, `nama`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'paturaw', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `picking`
--

DROP TABLE IF EXISTS `picking`;
CREATE TABLE `picking` (
  `kd_picking` varchar(20) NOT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `ket` varchar(250) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `picking`
--

INSERT INTO `picking` (`kd_picking`, `no_po`, `ket`, `tgl`, `perusahaan_id`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
('PCK-TLG-19-000001', 'PO-TLG-19-000001', NULL, '2019-02-15', 1, 'jamal.apriadi', 'jamal.apriadi', '2019-02-15 00:14:57', '2019-02-15 00:14:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `po`
--

DROP TABLE IF EXISTS `po`;
CREATE TABLE `po` (
  `no_po` varchar(20) NOT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `ket` varchar(150) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `perusahaan_id` varchar(50) DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `po`
--

INSERT INTO `po` (`no_po`, `customer_id`, `ket`, `tgl`, `lokasi_id`, `perusahaan_id`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
('PO-TLG-19-000001', 'CST001', NULL, '2019-02-10', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-10 10:26:23', '2019-02-10 10:26:23', NULL),
('PO-TLG-19-000002', 'CST002', 'Testing aja yaa', '2019-02-12', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-12 07:44:39', '2019-02-12 07:44:39', NULL),
('PO-TLG-19-000003', 'CST002', 'keterangannya', '2019-02-13', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-13 13:36:58', '2019-02-13 13:36:58', NULL),
('PO-TLG-19-000004', 'CST002', 'k', '2019-02-13', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-13 13:38:35', '2019-02-13 13:38:35', NULL),
('PO-TLG-19-000005', 'CST001', 'test dari nama lokasi', '2019-02-14', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-14 13:32:16', '2019-02-14 13:32:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prgrm`
--

DROP TABLE IF EXISTS `prgrm`;
CREATE TABLE `prgrm` (
  `nmr` varchar(20) NOT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `awprriod` date DEFAULT NULL,
  `akpriod` date DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prgrm`
--

INSERT INTO `prgrm` (`nmr`, `nm`, `awprriod`, `akpriod`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
('PRG-TLG-19-000001', 'PROGRAM PERTAMA', '2019-02-10', '2019-02-10', 'jamal.apriadi', 'jamal.apriadi', '2019-02-09 20:12:17', '2019-02-09 20:12:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rak`
--

DROP TABLE IF EXISTS `rak`;
CREATE TABLE `rak` (
  `kd` int(11) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `lokasi_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rak`
--

INSERT INTO `rak` (`kd`, `nm`, `lokasi_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Rak as', 1, '2019-02-13 14:02:00', '2019-02-13 14:02:00', NULL),
(2, 'Rak 2', 1, '2019-02-13 14:02:00', '2019-02-13 14:02:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rmutasi`
--

DROP TABLE IF EXISTS `rmutasi`;
CREATE TABLE `rmutasi` (
  `no_mutasi` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `rakl` varchar(15) NOT NULL,
  `rakb` varchar(15) NOT NULL,
  `dos` int(7) NOT NULL,
  `pcs` int(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rorder`
--

DROP TABLE IF EXISTS `rorder`;
CREATE TABLE `rorder` (
  `id` int(11) NOT NULL,
  `no_order` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(20) DEFAULT NULL,
  `dos` int(4) DEFAULT NULL,
  `pcs` int(4) DEFAULT NULL,
  `hrg` int(8) DEFAULT NULL,
  `diskon_persen` int(7) DEFAULT NULL,
  `diskon_rupiah` int(7) DEFAULT NULL,
  `jumlah` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rorder`
--

INSERT INTO `rorder` (`id`, `no_order`, `kd_brg`, `dos`, `pcs`, `hrg`, `diskon_persen`, `diskon_rupiah`, `jumlah`) VALUES
(1, '19-000001', 'BRG003', 0, 1, 3000, 0, 0, 1),
(2, '19-000001', 'BRG001', 0, 4, 1000, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rpicking`
--

DROP TABLE IF EXISTS `rpicking`;
CREATE TABLE `rpicking` (
  `id` int(11) NOT NULL,
  `kd_picking` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(11) DEFAULT NULL,
  `kd_rak` varchar(25) DEFAULT NULL,
  `pdos` int(5) DEFAULT NULL,
  `ppcs` int(5) DEFAULT NULL,
  `dos` int(5) DEFAULT NULL,
  `pcs` int(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rpicking`
--

INSERT INTO `rpicking` (`id`, `kd_picking`, `kd_brg`, `kd_rak`, `pdos`, `ppcs`, `dos`, `pcs`) VALUES
(1, 'PCK-TLG-19-000001', 'BRG003', '1', 3, 1, 0, 1),
(2, 'PCK-TLG-19-000001', 'BRG001', '1', 2, 4, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rpo`
--

DROP TABLE IF EXISTS `rpo`;
CREATE TABLE `rpo` (
  `no_po` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `dos` int(5) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rpo`
--

INSERT INTO `rpo` (`no_po`, `kd_brg`, `dos`, `pcs`) VALUES
('PO-TLG-19-000001', 'BRG003', 3, 1),
('PO-TLG-19-000001', 'BRG001', 2, 4),
('PO-TLG-19-000002', 'BRG001', 1, 3),
('PO-TLG-19-000002', 'BRG002', 3, 4),
('PO-TLG-19-000003', 'BRG002', 3, 1),
('PO-TLG-19-000003', 'BRG003', 2, 1),
('PO-TLG-19-000004', 'BRG001', 0, 0),
('PO-TLG-19-000004', 'BRG002', 2, 3),
('PO-TLG-19-000005', 'BRG001', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rprogram`
--

DROP TABLE IF EXISTS `rprogram`;
CREATE TABLE `rprogram` (
  `id` int(11) NOT NULL,
  `nmr_program` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(20) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `point` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rprogram`
--

INSERT INTO `rprogram` (`id`, `nmr_program`, `kd_brg`, `qty`, `point`) VALUES
(3, 'PRG-TLG-19-000001', 'BRG001', 1, 3),
(4, 'PRG-TLG-19-000001', 'BRG002', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rstoring`
--

DROP TABLE IF EXISTS `rstoring`;
CREATE TABLE `rstoring` (
  `id` int(11) NOT NULL,
  `no_storing` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(11) DEFAULT NULL,
  `rak_id` int(11) DEFAULT NULL,
  `dos` int(5) DEFAULT NULL,
  `pcs` int(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rstoring`
--

INSERT INTO `rstoring` (`id`, `no_storing`, `kd_brg`, `rak_id`, `dos`, `pcs`) VALUES
(1, 'STR-TLG-19-000001', 'BRG001', 1, 2, 3),
(2, 'STR-TLG-19-000001', 'BRG002', 2, 1, 4),
(3, 'STR-TLG-19-000002', 'BRG001', 1, 2, 4),
(4, 'STR-TLG-19-000003', 'BRG002', 1, 2, 1),
(5, 'STR-TLG-19-000004', 'BRG001', 1, 23, 2),
(6, 'STR-TLG-19-000004', 'BRG002', 2, 2, 1),
(7, 'STR-TLG-19-000005', 'BRG001', 1, 10, 10),
(8, 'STR-TLG-19-000006', 'BRG001', 1, 2, 3),
(9, 'STR-TLG-19-000007', 'BRG001', 1, 2, 4),
(10, 'STR-TLG-19-000008', 'BRG001', 1, 23, 12),
(11, 'STR-TLG-19-000009', 'BRG001', 1, 1, 12),
(12, 'STR-TLG-19-000010', 'BRG001', 1, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rterima`
--

DROP TABLE IF EXISTS `rterima`;
CREATE TABLE `rterima` (
  `no_terima` varchar(20) NOT NULL,
  `kd_brg` varchar(15) NOT NULL,
  `nm_brg` varchar(50) NOT NULL,
  `rak` varchar(15) NOT NULL,
  `pcs` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `nm` varchar(100) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `nm`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Jamal Sales', 'Active', '2019-02-10 11:15:02', '2019-02-10 11:15:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

DROP TABLE IF EXISTS `stok`;
CREATE TABLE `stok` (
  `kd_brg` varchar(20) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `rak_id` int(11) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok`
--

INSERT INTO `stok` (`kd_brg`, `lokasi_id`, `rak_id`, `pcs`) VALUES
('BRG001', 1, 1, 12),
('BRG002', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `storing`
--

DROP TABLE IF EXISTS `storing`;
CREATE TABLE `storing` (
  `no_storing` varchar(20) NOT NULL,
  `no_ref` varchar(15) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storing`
--

INSERT INTO `storing` (`no_storing`, `no_ref`, `tgl`, `lokasi_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
('STR-TLG-19-000001', 'Ref0001', '2019-02-14', 1, '2019-02-14 13:02:06', '2019-02-14 13:02:06', NULL),
('STR-TLG-19-000002', '34', '2019-02-14', 1, '2019-02-14 13:02:48', '2019-02-14 13:02:48', NULL),
('STR-TLG-19-000003', '45', '2019-02-14', 1, '2019-02-14 13:07:35', '2019-02-14 13:07:35', NULL),
('STR-TLG-19-000004', 'df', '2019-02-14', 1, '2019-02-14 13:23:40', '2019-02-14 13:23:40', NULL),
('STR-TLG-19-000005', '34', '2019-02-14', 1, '2019-02-14 15:09:54', '2019-02-14 15:09:54', NULL),
('STR-TLG-19-000006', 'sd', '2019-02-14', 1, '2019-02-14 15:11:55', '2019-02-14 15:11:55', NULL),
('STR-TLG-19-000007', 'asd', '2019-02-14', 1, '2019-02-14 15:13:01', '2019-02-14 15:13:01', NULL),
('STR-TLG-19-000008', 'asd', '2019-02-14', 1, '2019-02-14 15:13:33', '2019-02-14 15:13:33', NULL),
('STR-TLG-19-000009', 'asd', '2019-02-14', 1, '2019-02-14 15:16:27', '2019-02-14 15:16:27', NULL),
('STR-TLG-19-000010', 'asd', '2019-02-14', 1, '2019-02-14 15:17:04', '2019-02-14 15:17:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suplier`
--

DROP TABLE IF EXISTS `suplier`;
CREATE TABLE `suplier` (
  `kd` int(11) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `kota_id` int(11) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `nm_perusahaan` varchar(50) NOT NULL,
  `kontak` varchar(25) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terima`
--

DROP TABLE IF EXISTS `terima`;
CREATE TABLE `terima` (
  `no_trima` varchar(20) NOT NULL,
  `sup` varchar(50) NOT NULL,
  `no_sjln` varchar(25) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `lvl` varchar(15) DEFAULT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `remember_token` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `lvl`, `perusahaan_id`, `active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'jamal.apriadi', '$2y$10$lRTHzn8Bp7o1bERqA0HH.OOtOJYUhrhsg4aHxTQz4bcbpvpp2I19K', 'Admin', 1, 'Y', 'wOvJIAGElT8cXSJtwbCPZqQlvbfvSIk4h8GxgZShiIR1HmRRSsOLJKuNJPx0', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`kd_bank`),
  ADD UNIQUE KEY `nm` (`nm`);

--
-- Indexes for table `brg`
--
ALTER TABLE `brg`
  ADD PRIMARY KEY (`kd`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`kd`);

--
-- Indexes for table `ket`
--
ALTER TABLE `ket`
  ADD PRIMARY KEY (`no_hp`);

--
-- Indexes for table `klmpk`
--
ALTER TABLE `klmpk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nm` (`nm`);

--
-- Indexes for table `kota`
--
ALTER TABLE `kota`
  ADD PRIMARY KEY (`kd_kota`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lokasi` (`lokasi`);

--
-- Indexes for table `merk`
--
ALTER TABLE `merk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nm` (`nm`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`no_mutasi`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`no_order`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `picking`
--
ALTER TABLE `picking`
  ADD PRIMARY KEY (`kd_picking`);

--
-- Indexes for table `po`
--
ALTER TABLE `po`
  ADD PRIMARY KEY (`no_po`);

--
-- Indexes for table `prgrm`
--
ALTER TABLE `prgrm`
  ADD PRIMARY KEY (`nmr`);

--
-- Indexes for table `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`kd`);

--
-- Indexes for table `rorder`
--
ALTER TABLE `rorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rpicking`
--
ALTER TABLE `rpicking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rprogram`
--
ALTER TABLE `rprogram`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rstoring`
--
ALTER TABLE `rstoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nm` (`nm`);

--
-- Indexes for table `storing`
--
ALTER TABLE `storing`
  ADD PRIMARY KEY (`no_storing`);

--
-- Indexes for table `suplier`
--
ALTER TABLE `suplier`
  ADD PRIMARY KEY (`kd`);

--
-- Indexes for table `terima`
--
ALTER TABLE `terima`
  ADD PRIMARY KEY (`no_trima`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `kd_bank` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `klmpk`
--
ALTER TABLE `klmpk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kota`
--
ALTER TABLE `kota`
  MODIFY `kd_kota` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `merk`
--
ALTER TABLE `merk`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rak`
--
ALTER TABLE `rak`
  MODIFY `kd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rorder`
--
ALTER TABLE `rorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rpicking`
--
ALTER TABLE `rpicking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rprogram`
--
ALTER TABLE `rprogram`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `rstoring`
--
ALTER TABLE `rstoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `suplier`
--
ALTER TABLE `suplier`
  MODIFY `kd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
