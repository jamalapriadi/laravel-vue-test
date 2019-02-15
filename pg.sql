-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.13 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_penjualan.bank
DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `kd_bank` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd_bank`),
  UNIQUE KEY `nm` (`nm`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.bank: 3 rows
DELETE FROM `bank`;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
INSERT INTO `bank` (`kd_bank`, `nm`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(21, 'Mandiri', 'Active', '2019-02-02 05:48:44', '2019-02-02 05:48:44', NULL),
	(22, 'BCA', 'Active', '2019-02-02 07:13:19', '2019-02-02 07:13:19', NULL),
	(23, 'Test Bank', 'Active', '2019-02-13 21:11:03', '2019-02-13 21:11:07', '2019-02-13 21:11:07');
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.brg
DROP TABLE IF EXISTS `brg`;
CREATE TABLE IF NOT EXISTS `brg` (
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='hargab adalah harga beli, hargap adalah harga pokok';

-- Dumping data for table db_penjualan.brg: 4 rows
DELETE FROM `brg`;
/*!40000 ALTER TABLE `brg` DISABLE KEYS */;
INSERT INTO `brg` (`kd`, `nm`, `kelompok_id`, `merk_id`, `status`, `satuan`, `pcs`, `hrgb`, `hrgp`, `jual`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('BRG001', 'Barang 1', 1, 1, 'Ak', 'PCS', 123, 234, 234, 1000, '2019-02-06 17:41:53', '2019-02-06 18:10:05', NULL),
	('BRG002', 'Jenis 1', 1, 1, 'Ak', 'PCS', 123, 234, 234, 2000, '2019-02-06 17:41:53', '2019-02-06 18:10:05', NULL),
	('BRG003', 'Aqua', 1, 1, 'Ak', 'PCS', 123, 234, 234, 3000, '2019-02-06 17:41:53', '2019-02-06 18:10:05', NULL),
	('BRG004', 'Barang 2', 1, 1, 'Ak', 'PCS', 123, 234, 234, 4000, '2019-02-06 17:41:53', '2019-02-06 18:10:05', NULL);
/*!40000 ALTER TABLE `brg` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.customer
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.customer: 4 rows
DELETE FROM `customer`;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`kd`, `nm`, `nm_toko`, `alamat`, `alias`, `kota_id`, `tlpn`, `nmtk`, `kontak`, `fax`, `plafon`, `top`, `npwp`, `nik`, `jenis`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('CST001', 'Jamal Apriadi', 'Maju Jaya', 'Jakarta', 'jamal', 1, '1234', '23', '234', '234', 234, 234, '23432', '1234', 'Wk', 'jamal.apriadi', NULL, '2019-02-05 23:18:09', '2019-02-13 21:23:45', NULL),
	('CST002', 'Wiwik Putri', NULL, 'Pangkah', 'wiwi', 1, '123', '234', '234', '234', 123, 234, '1234', '1234', 'sdf', 'jamal.apriadi', NULL, '2019-02-05 23:19:12', '2019-02-05 23:19:12', NULL),
	('CST003', 'Dwi Maulidia', NULL, 'Debong', '234', 1, '234', '234', '234', '234', 234, 234, '234', '234', '234', 'jamal.apriadi', NULL, '2019-02-05 23:19:45', '2019-02-05 23:23:35', NULL),
	('CS0005', 'Nazar', 'Larva', 'T', 'Zulmi', 1, '234', NULL, NULL, NULL, 234, 23, '234534', '234', NULL, 'jamal.apriadi', NULL, '2019-02-13 21:24:42', '2019-02-13 21:24:42', NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.ket
DROP TABLE IF EXISTS `ket`;
CREATE TABLE IF NOT EXISTS `ket` (
  `no_hp` varchar(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pin` varchar(9) NOT NULL,
  `npwp` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`no_hp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.ket: 1 rows
DELETE FROM `ket`;
/*!40000 ALTER TABLE `ket` DISABLE KEYS */;
INSERT INTO `ket` (`no_hp`, `email`, `pin`, `npwp`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('082242783450', 'wqr3h2@gmail.com', '8ew8t', 'ewnjwjg9', NULL, NULL, NULL);
/*!40000 ALTER TABLE `ket` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.klmpk
DROP TABLE IF EXISTS `klmpk`;
CREATE TABLE IF NOT EXISTS `klmpk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nm` (`nm`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.klmpk: 1 rows
DELETE FROM `klmpk`;
/*!40000 ALTER TABLE `klmpk` DISABLE KEYS */;
INSERT INTO `klmpk` (`id`, `nm`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Kelompok 1', '2019-02-06 16:33:37', '2019-02-06 16:33:37', NULL);
/*!40000 ALTER TABLE `klmpk` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.kota
DROP TABLE IF EXISTS `kota`;
CREATE TABLE IF NOT EXISTS `kota` (
  `kd_kota` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nm` varchar(25) NOT NULL,
  `jenis` enum('Luar Kota','Dalam Kota') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd_kota`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.kota: 5 rows
DELETE FROM `kota`;
/*!40000 ALTER TABLE `kota` DISABLE KEYS */;
INSERT INTO `kota` (`kd_kota`, `nm`, `jenis`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Palu', 'Dalam Kota', NULL, NULL, NULL),
	(2, 'Jakarta', 'Luar Kota', '2019-02-02 07:51:38', '2019-02-02 07:58:09', NULL),
	(3, 'Jakarta', 'Luar Kota', '2019-02-02 07:51:49', '2019-02-02 07:55:33', '2019-02-02 07:55:33'),
	(4, 'Bandung', 'Luar Kota', '2019-02-02 07:52:37', '2019-02-02 07:52:37', NULL),
	(5, 'Surabaya', 'Dalam Kota', '2019-02-02 07:53:08', '2019-02-02 07:57:50', NULL);
/*!40000 ALTER TABLE `kota` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.lokasi
DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE IF NOT EXISTS `lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(25) DEFAULT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lokasi` (`lokasi`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.lokasi: 1 rows
DELETE FROM `lokasi`;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
INSERT INTO `lokasi` (`id`, `lokasi`, `nm`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Lokasi A', 'Nama A', '2019-02-05 22:55:20', '2019-02-05 22:55:20', NULL);
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.merk
DROP TABLE IF EXISTS `merk`;
CREATE TABLE IF NOT EXISTS `merk` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) NOT NULL,
  `persen` int(3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nm` (`nm`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.merk: 1 rows
DELETE FROM `merk`;
/*!40000 ALTER TABLE `merk` DISABLE KEYS */;
INSERT INTO `merk` (`id`, `nm`, `persen`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Merk 1', 5, '2019-02-06 16:42:42', '2019-02-06 16:42:42', NULL);
/*!40000 ALTER TABLE `merk` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.mutasi
DROP TABLE IF EXISTS `mutasi`;
CREATE TABLE IF NOT EXISTS `mutasi` (
  `no_mutasi` varchar(20) NOT NULL,
  `lokasil` varchar(15) NOT NULL,
  `lokasib` varchar(15) NOT NULL,
  `ket` varchar(250) NOT NULL,
  `tgl` date NOT NULL,
  `perusahaan` varchar(50) NOT NULL,
  PRIMARY KEY (`no_mutasi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.mutasi: 0 rows
DELETE FROM `mutasi`;
/*!40000 ALTER TABLE `mutasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `mutasi` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `no_order` varchar(20) NOT NULL,
  `kd_picking` varchar(50) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `ket` varchar(150) DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL,
  `perusahaan_id` varchar(50) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`no_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='tgljt adalah tgl jatuh tempo, akan keisi jika kd_trans yang dipilih adalah kredit,\r\nformat no_order nanti nya adalah 19-000001 dan seterusnya,\r\nangka 19 adalah tahun sekarang';

-- Dumping data for table db_penjualan.orders: 1 rows
DELETE FROM `orders`;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.perusahaan
DROP TABLE IF EXISTS `perusahaan`;
CREATE TABLE IF NOT EXISTS `perusahaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.perusahaan: 1 rows
DELETE FROM `perusahaan`;
/*!40000 ALTER TABLE `perusahaan` DISABLE KEYS */;
INSERT INTO `perusahaan` (`id`, `nama`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'paturaw', NULL, NULL, NULL);
/*!40000 ALTER TABLE `perusahaan` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.picking
DROP TABLE IF EXISTS `picking`;
CREATE TABLE IF NOT EXISTS `picking` (
  `kd_picking` varchar(20) NOT NULL,
  `no_po` varchar(50) DEFAULT NULL,
  `ket` varchar(250) DEFAULT NULL,
  `kd_trans` varchar(15) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `tglj` date DEFAULT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd_picking`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.picking: 1 rows
DELETE FROM `picking`;
/*!40000 ALTER TABLE `picking` DISABLE KEYS */;
/*!40000 ALTER TABLE `picking` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.po
DROP TABLE IF EXISTS `po`;
CREATE TABLE IF NOT EXISTS `po` (
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`no_po`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.po: 4 rows
DELETE FROM `po`;
/*!40000 ALTER TABLE `po` DISABLE KEYS */;
INSERT INTO `po` (`no_po`, `customer_id`, `ket`, `tgl`, `lokasi_id`, `perusahaan_id`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('PO-TLG-19-000001', 'CST001', NULL, '2019-02-10', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-10 17:26:23', '2019-02-10 17:26:23', NULL),
	('PO-TLG-19-000002', 'CST002', 'Testing aja yaa', '2019-02-12', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-12 14:44:39', '2019-02-12 14:44:39', NULL),
	('PO-TLG-19-000003', 'CST002', 'keterangannya', '2019-02-13', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-13 20:36:58', '2019-02-13 20:36:58', NULL),
	('PO-TLG-19-000004', 'CST002', 'k', '2019-02-13', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-13 20:38:35', '2019-02-13 20:38:35', NULL),
	('PO-TLG-19-000005', 'CST001', 'test dari nama lokasi', '2019-02-14', 1, '1', 'jamal.apriadi', 'jamal.apriadi', '2019-02-14 20:32:16', '2019-02-14 20:32:16', NULL);
/*!40000 ALTER TABLE `po` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.prgrm
DROP TABLE IF EXISTS `prgrm`;
CREATE TABLE IF NOT EXISTS `prgrm` (
  `nmr` varchar(20) NOT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `awprriod` date DEFAULT NULL,
  `akpriod` date DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nmr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.prgrm: 1 rows
DELETE FROM `prgrm`;
/*!40000 ALTER TABLE `prgrm` DISABLE KEYS */;
INSERT INTO `prgrm` (`nmr`, `nm`, `awprriod`, `akpriod`, `insert_user`, `update_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('PRG-TLG-19-000001', 'PROGRAM PERTAMA', '2019-02-10', '2019-02-10', 'jamal.apriadi', 'jamal.apriadi', '2019-02-10 03:12:17', '2019-02-10 03:12:17', NULL);
/*!40000 ALTER TABLE `prgrm` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rak
DROP TABLE IF EXISTS `rak`;
CREATE TABLE IF NOT EXISTS `rak` (
  `kd` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) NOT NULL,
  `lokasi_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rak: 2 rows
DELETE FROM `rak`;
/*!40000 ALTER TABLE `rak` DISABLE KEYS */;
INSERT INTO `rak` (`kd`, `nm`, `lokasi_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Rak as', 1, '2019-02-13 21:02:00', '2019-02-13 21:02:00', NULL),
	(2, 'Rak 2', 1, '2019-02-13 21:02:00', '2019-02-13 21:02:00', NULL);
/*!40000 ALTER TABLE `rak` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rmutasi
DROP TABLE IF EXISTS `rmutasi`;
CREATE TABLE IF NOT EXISTS `rmutasi` (
  `no_mutasi` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `rakl` varchar(15) NOT NULL,
  `rakb` varchar(15) NOT NULL,
  `dos` int(7) NOT NULL,
  `pcs` int(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rmutasi: 0 rows
DELETE FROM `rmutasi`;
/*!40000 ALTER TABLE `rmutasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `rmutasi` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rorder
DROP TABLE IF EXISTS `rorder`;
CREATE TABLE IF NOT EXISTS `rorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_order` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(20) DEFAULT NULL,
  `dos` int(4) DEFAULT NULL,
  `pcs` int(4) DEFAULT NULL,
  `hrg` int(8) DEFAULT NULL,
  `diskon_persen` int(7) DEFAULT NULL,
  `diskon_rupiah` int(7) DEFAULT NULL,
  `jumlah` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rorder: 2 rows
DELETE FROM `rorder`;
/*!40000 ALTER TABLE `rorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `rorder` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rpicking
DROP TABLE IF EXISTS `rpicking`;
CREATE TABLE IF NOT EXISTS `rpicking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_picking` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(11) DEFAULT NULL,
  `kd_rak` varchar(25) DEFAULT NULL,
  `pdos` int(5) DEFAULT NULL,
  `ppcs` int(5) DEFAULT NULL,
  `dos` int(5) DEFAULT NULL,
  `pcs` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rpicking: 2 rows
DELETE FROM `rpicking`;
/*!40000 ALTER TABLE `rpicking` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpicking` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rpo
DROP TABLE IF EXISTS `rpo`;
CREATE TABLE IF NOT EXISTS `rpo` (
  `no_po` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `dos` int(5) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rpo: 8 rows
DELETE FROM `rpo`;
/*!40000 ALTER TABLE `rpo` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `rpo` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rprogram
DROP TABLE IF EXISTS `rprogram`;
CREATE TABLE IF NOT EXISTS `rprogram` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nmr_program` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(20) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `point` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_penjualan.rprogram: ~2 rows (approximately)
DELETE FROM `rprogram`;
/*!40000 ALTER TABLE `rprogram` DISABLE KEYS */;
INSERT INTO `rprogram` (`id`, `nmr_program`, `kd_brg`, `qty`, `point`) VALUES
	(3, 'PRG-TLG-19-000001', 'BRG001', 1, 3),
	(4, 'PRG-TLG-19-000001', 'BRG002', 2, 4);
/*!40000 ALTER TABLE `rprogram` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rstoring
DROP TABLE IF EXISTS `rstoring`;
CREATE TABLE IF NOT EXISTS `rstoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_storing` varchar(20) DEFAULT NULL,
  `kd_brg` varchar(11) DEFAULT NULL,
  `rak_id` int(11) DEFAULT NULL,
  `dos` int(5) DEFAULT NULL,
  `pcs` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rstoring: 0 rows
DELETE FROM `rstoring`;
/*!40000 ALTER TABLE `rstoring` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `rstoring` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rterima
DROP TABLE IF EXISTS `rterima`;
CREATE TABLE IF NOT EXISTS `rterima` (
  `no_terima` varchar(20) NOT NULL,
  `kd_brg` varchar(15) NOT NULL,
  `nm_brg` varchar(50) NOT NULL,
  `rak` varchar(15) NOT NULL,
  `pcs` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rterima: 0 rows
DELETE FROM `rterima`;
/*!40000 ALTER TABLE `rterima` DISABLE KEYS */;
/*!40000 ALTER TABLE `rterima` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.sales
DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(100) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nm` (`nm`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.sales: 1 rows
DELETE FROM `sales`;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` (`id`, `nm`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Jamal Sales', 'Active', '2019-02-10 18:15:02', '2019-02-10 18:15:02', NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.stok
DROP TABLE IF EXISTS `stok`;
CREATE TABLE IF NOT EXISTS `stok` (
  `kd_brg` varchar(20) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `rak_id` int(11) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.stok: 0 rows
DELETE FROM `stok`;
/*!40000 ALTER TABLE `stok` DISABLE KEYS */;
INSERT INTO `stok` (`kd_brg`, `lokasi_id`, `rak_id`, `pcs`) VALUES
	('BRG001', 1, 1, 16),
	('BRG002', 1, 2, 1);
/*!40000 ALTER TABLE `stok` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.storing
DROP TABLE IF EXISTS `storing`;
CREATE TABLE IF NOT EXISTS `storing` (
  `no_storing` varchar(20) NOT NULL,
  `no_ref` varchar(15) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`no_storing`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.storing: 0 rows
DELETE FROM `storing`;
/*!40000 ALTER TABLE `storing` DISABLE KEYS */;
INSERT INTO `storing` (`no_storing`, `no_ref`, `tgl`, `lokasi_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('STR-TLG-19-000001', 'Ref0001', '2019-02-14', 1, '2019-02-14 20:02:06', '2019-02-14 20:02:06', NULL),
	('STR-TLG-19-000002', '34', '2019-02-14', 1, '2019-02-14 20:02:48', '2019-02-14 20:02:48', NULL),
	('STR-TLG-19-000003', '45', '2019-02-14', 1, '2019-02-14 20:07:35', '2019-02-14 20:07:35', NULL),
	('STR-TLG-19-000004', 'df', '2019-02-14', 1, '2019-02-14 20:23:40', '2019-02-14 20:23:40', NULL),
	('STR-TLG-19-000005', '34', '2019-02-14', 1, '2019-02-14 22:09:54', '2019-02-14 22:09:54', NULL),
	('STR-TLG-19-000006', 'sd', '2019-02-14', 1, '2019-02-14 22:11:55', '2019-02-14 22:11:55', NULL),
	('STR-TLG-19-000007', 'asd', '2019-02-14', 1, '2019-02-14 22:13:01', '2019-02-14 22:13:01', NULL),
	('STR-TLG-19-000008', 'asd', '2019-02-14', 1, '2019-02-14 22:13:33', '2019-02-14 22:13:33', NULL),
	('STR-TLG-19-000009', 'asd', '2019-02-14', 1, '2019-02-14 22:16:27', '2019-02-14 22:16:27', NULL),
	('STR-TLG-19-000010', 'asd', '2019-02-14', 1, '2019-02-14 22:17:04', '2019-02-14 22:17:04', NULL);
/*!40000 ALTER TABLE `storing` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.suplier
DROP TABLE IF EXISTS `suplier`;
CREATE TABLE IF NOT EXISTS `suplier` (
  `kd` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `kota_id` int(11) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `nm_perusahaan` varchar(50) NOT NULL,
  `kontak` varchar(25) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.suplier: 0 rows
DELETE FROM `suplier`;
/*!40000 ALTER TABLE `suplier` DISABLE KEYS */;
/*!40000 ALTER TABLE `suplier` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.terima
DROP TABLE IF EXISTS `terima`;
CREATE TABLE IF NOT EXISTS `terima` (
  `no_trima` varchar(20) NOT NULL,
  `sup` varchar(50) NOT NULL,
  `no_sjln` varchar(25) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  PRIMARY KEY (`no_trima`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.terima: 0 rows
DELETE FROM `terima`;
/*!40000 ALTER TABLE `terima` DISABLE KEYS */;
/*!40000 ALTER TABLE `terima` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `lvl` varchar(15) DEFAULT NULL,
  `perusahaan_id` int(11) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `remember_token` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.user: 1 rows
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `password`, `lvl`, `perusahaan_id`, `active`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'jamal.apriadi', '$2y$10$lRTHzn8Bp7o1bERqA0HH.OOtOJYUhrhsg4aHxTQz4bcbpvpp2I19K', 'Admin', 1, 'Y', 'wOvJIAGElT8cXSJtwbCPZqQlvbfvSIk4h8GxgZShiIR1HmRRSsOLJKuNJPx0', NULL, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
