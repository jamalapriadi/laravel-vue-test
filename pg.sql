-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.13 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_penjualan.bank
CREATE TABLE IF NOT EXISTS `bank` (
  `kd_bank` varchar(5) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd_bank`),
  UNIQUE KEY `nm` (`nm`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.bank: 1 rows
DELETE FROM `bank`;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
INSERT INTO `bank` (`kd_bank`, `nm`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('021', 'mandiri', 'Aktiv', NULL, NULL, NULL);
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.brg
CREATE TABLE IF NOT EXISTS `brg` (
  `kd` varchar(20) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `kelompok` varchar(50) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `pcs` int(5) NOT NULL,
  `hrgb` int(12) NOT NULL,
  `hrgp` int(12) NOT NULL,
  `jual` int(12) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='hargab adalah harga beli, hargap adalah harga pokok';

-- Dumping data for table db_penjualan.brg: 0 rows
DELETE FROM `brg`;
/*!40000 ALTER TABLE `brg` DISABLE KEYS */;
/*!40000 ALTER TABLE `brg` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `kd` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `alias` varchar(25) DEFAULT NULL,
  `kota` varchar(25) DEFAULT NULL,
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
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.customer: 2 rows
DELETE FROM `customer`;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`kd`, `nm`, `alamat`, `alias`, `kota`, `tlpn`, `nmtk`, `kontak`, `fax`, `plafon`, `top`, `npwp`, `nik`, `jenis`, `insert_user`, `update_user`, `created_at`, `updated_at`) VALUES
	(1, 'abu', 'asklkg', 'fglew', 'hwqr', '93498', '938eji', '2545', '3248', 0, 0, 'hf', 'ireur', 'iefhj', NULL, NULL, NULL, NULL),
	(2, 'askfj', 'fjwek', 'jeigj', 'ifjeigj', 'igjeig', 'igeig', 'gieighei', 'giejigjei', 0, 0, 'giejigjei', 'ijegi', 'gije', NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.ket
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
	(1, 'una', NULL, NULL, NULL);
/*!40000 ALTER TABLE `klmpk` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.kota
CREATE TABLE IF NOT EXISTS `kota` (
  `kd_kota` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nm` varchar(25) NOT NULL,
  `jenis` enum('Luar Kota','Dalam Kota') NOT NULL,
  PRIMARY KEY (`kd_kota`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.kota: 1 rows
DELETE FROM `kota`;
/*!40000 ALTER TABLE `kota` DISABLE KEYS */;
INSERT INTO `kota` (`kd_kota`, `nm`, `jenis`) VALUES
	(1, 'Palu', 'Dalam Kota');
/*!40000 ALTER TABLE `kota` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.lokasi
CREATE TABLE IF NOT EXISTS `lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(25) DEFAULT NULL,
  `nm` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lokasi` (`lokasi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.lokasi: 0 rows
DELETE FROM `lokasi`;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.merk
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
	(1, 'danone', 3, NULL, NULL, NULL);
/*!40000 ALTER TABLE `merk` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.mutasi
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

-- Dumping structure for table db_penjualan.order
CREATE TABLE IF NOT EXISTS `order` (
  `no_order` varchar(20) NOT NULL,
  `cust` varchar(50) NOT NULL,
  `ket` varchar(150) NOT NULL,
  `salesman` varchar(25) NOT NULL,
  `kd_picking` varchar(15) NOT NULL,
  `kd_trans` enum('Kredit','Tunai') NOT NULL,
  `tgl` date NOT NULL,
  `tgljt` date NOT NULL,
  `lokasi` varchar(25) NOT NULL,
  `perusahaan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`no_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='tgljt adalah tgl jatuh tempo, akan keisi jika kd_trans yang dipilih adalah kredit,\r\nformat no_order nanti nya adalah 19-000001 dan seterusnya,\r\nangka 19 adalah tahun sekarang';

-- Dumping data for table db_penjualan.order: 0 rows
DELETE FROM `order`;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.perusahaan
CREATE TABLE IF NOT EXISTS `perusahaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.perusahaan: 1 rows
DELETE FROM `perusahaan`;
/*!40000 ALTER TABLE `perusahaan` DISABLE KEYS */;
INSERT INTO `perusahaan` (`id`, `nama`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'paturaw', NULL, NULL, NULL);
/*!40000 ALTER TABLE `perusahaan` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.picking
CREATE TABLE IF NOT EXISTS `picking` (
  `kd_picking` varchar(20) NOT NULL,
  `no_po` int(11) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `ket` varchar(250) NOT NULL,
  `sales` varchar(50) NOT NULL,
  `kd_trans` varchar(15) NOT NULL,
  `tgl` date NOT NULL,
  `tglj` date NOT NULL,
  `lokasi` varchar(25) NOT NULL,
  `perusahaan` varchar(50) NOT NULL,
  PRIMARY KEY (`kd_picking`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.picking: 0 rows
DELETE FROM `picking`;
/*!40000 ALTER TABLE `picking` DISABLE KEYS */;
/*!40000 ALTER TABLE `picking` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.po
CREATE TABLE IF NOT EXISTS `po` (
  `no_po` varchar(20) NOT NULL,
  `cust` varchar(50) NOT NULL,
  `ket` varchar(150) NOT NULL,
  `tgl` date NOT NULL,
  `perusahaan` varchar(50) NOT NULL,
  PRIMARY KEY (`no_po`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.po: 0 rows
DELETE FROM `po`;
/*!40000 ALTER TABLE `po` DISABLE KEYS */;
/*!40000 ALTER TABLE `po` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.prgrm
CREATE TABLE IF NOT EXISTS `prgrm` (
  `nmr` varchar(20) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `awprriod` date NOT NULL,
  `akpriod` date NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `qty` int(5) NOT NULL,
  `poin` int(5) NOT NULL,
  PRIMARY KEY (`nmr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.prgrm: 0 rows
DELETE FROM `prgrm`;
/*!40000 ALTER TABLE `prgrm` DISABLE KEYS */;
/*!40000 ALTER TABLE `prgrm` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rak
CREATE TABLE IF NOT EXISTS `rak` (
  `kd` varchar(25) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `lokasi` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rak: 0 rows
DELETE FROM `rak`;
/*!40000 ALTER TABLE `rak` DISABLE KEYS */;
/*!40000 ALTER TABLE `rak` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rmutasi
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
CREATE TABLE IF NOT EXISTS `rorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_order` varchar(20) NOT NULL,
  `kd_brg` varchar(20) NOT NULL,
  `dos` int(4) NOT NULL,
  `pcs` int(4) NOT NULL,
  `hrg` int(8) NOT NULL,
  `persen` int(3) NOT NULL,
  `diskon` int(7) NOT NULL,
  `jumlah` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rorder: 0 rows
DELETE FROM `rorder`;
/*!40000 ALTER TABLE `rorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `rorder` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rpicking
CREATE TABLE IF NOT EXISTS `rpicking` (
  `kd` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `nm_brg` varchar(50) NOT NULL,
  `kd_rak` varchar(25) NOT NULL,
  `pdos` int(5) NOT NULL,
  `ppcs` int(5) NOT NULL,
  `dos` int(5) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rpicking: 0 rows
DELETE FROM `rpicking`;
/*!40000 ALTER TABLE `rpicking` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpicking` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rpo
CREATE TABLE IF NOT EXISTS `rpo` (
  `no_po` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `dos` int(5) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rpo: 0 rows
DELETE FROM `rpo`;
/*!40000 ALTER TABLE `rpo` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpo` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rstoring
CREATE TABLE IF NOT EXISTS `rstoring` (
  `no_storing` varchar(20) NOT NULL,
  `kd_brg` varchar(11) NOT NULL,
  `nm_brg` varchar(50) NOT NULL,
  `rak` varchar(15) NOT NULL,
  `dos` int(5) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.rstoring: 0 rows
DELETE FROM `rstoring`;
/*!40000 ALTER TABLE `rstoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `rstoring` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.rterima
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
	(1, 'udin', 'Tidak Aktif', NULL, NULL, NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.stok
CREATE TABLE IF NOT EXISTS `stok` (
  `kd_brg` varchar(20) NOT NULL,
  `lokasi` varchar(25) NOT NULL,
  `rak` varchar(25) NOT NULL,
  `pcs` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.stok: 0 rows
DELETE FROM `stok`;
/*!40000 ALTER TABLE `stok` DISABLE KEYS */;
/*!40000 ALTER TABLE `stok` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.storing
CREATE TABLE IF NOT EXISTS `storing` (
  `no_storing` varchar(20) NOT NULL,
  `no_ref` varchar(15) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi` varchar(15) NOT NULL,
  PRIMARY KEY (`no_storing`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.storing: 0 rows
DELETE FROM `storing`;
/*!40000 ALTER TABLE `storing` DISABLE KEYS */;
/*!40000 ALTER TABLE `storing` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.suplier
CREATE TABLE IF NOT EXISTS `suplier` (
  `kd` varchar(20) NOT NULL,
  `nm` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `kota` varchar(25) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `nm_perusahaan` varchar(50) NOT NULL,
  `kontak` varchar(25) NOT NULL,
  `fax` varchar(15) NOT NULL,
  PRIMARY KEY (`kd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.suplier: 2 rows
DELETE FROM `suplier`;
/*!40000 ALTER TABLE `suplier` DISABLE KEYS */;
INSERT INTO `suplier` (`kd`, `nm`, `alamat`, `kota`, `telepon`, `nm_perusahaan`, `kontak`, `fax`) VALUES
	('1', 'aneka maju', 'otista', 'palu', '045151353', 'lksdnlg', '3420it', '34i398'),
	('2', 'dwijd', 'rwqr', 'rhqiuhu', 'hqwhui', 'hehiuh', 'hueifhg', 'gheuihge');
/*!40000 ALTER TABLE `suplier` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.terima
CREATE TABLE IF NOT EXISTS `terima` (
  `no_trima` varchar(20) NOT NULL,
  `sup` varchar(50) NOT NULL,
  `no_sjln` varchar(25) NOT NULL,
  `tgl` date NOT NULL,
  `lokasi` varchar(15) NOT NULL,
  PRIMARY KEY (`no_trima`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.terima: 0 rows
DELETE FROM `terima`;
/*!40000 ALTER TABLE `terima` DISABLE KEYS */;
/*!40000 ALTER TABLE `terima` ENABLE KEYS */;

-- Dumping structure for table db_penjualan.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `lvl` varchar(15) DEFAULT NULL,
  `perusahaan` varchar(50) DEFAULT NULL,
  `remember_token` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table db_penjualan.user: 1 rows
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `password`, `lvl`, `perusahaan`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'jamal.apriadi', '$2y$10$lRTHzn8Bp7o1bERqA0HH.OOtOJYUhrhsg4aHxTQz4bcbpvpp2I19K', 'Admin', 'paturaw', NULL, NULL, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
