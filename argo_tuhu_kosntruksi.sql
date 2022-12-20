-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.20 - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5145
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for argo_tuhu_konstruksi
CREATE DATABASE IF NOT EXISTS `argo_tuhu_konstruksi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `argo_tuhu_konstruksi`;

-- Dumping structure for table argo_tuhu_konstruksi.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `kategori` enum('bahan','alat') NOT NULL DEFAULT 'bahan',
  `satuan_id` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.barang: ~9 rows (approximately)
DELETE FROM `barang`;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` (`id`, `nama`, `kategori`, `satuan_id`, `updated_at`) VALUES
	(1, 'Oli 10', 'bahan', 1, '2017-01-09 14:18:26'),
	(2, 'Oli 40', 'bahan', 1, '2017-01-09 14:18:28'),
	(3, 'Oli 90', 'bahan', 1, '2017-01-09 14:18:29'),
	(4, 'Oil Heater', 'bahan', 1, '2017-01-09 14:18:31'),
	(5, 'Selenoid Valve', 'bahan', 3, '2017-01-09 14:18:57'),
	(6, 'Gerindra', 'alat', 7, '2017-01-09 14:19:48'),
	(7, 'Genset', 'alat', 7, '2017-01-09 14:19:56'),
	(8, 'Welding machine', 'alat', 7, '2017-01-09 14:19:58'),
	(9, 'Bor tangan', 'alat', 7, '2017-01-09 14:20:00'),
	(10, 'Tespen', 'alat', 3, '2017-01-09 14:20:25');
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.ci_sessions
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.ci_sessions: ~16 rows (approximately)
DELETE FROM `ci_sessions`;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
	('084deb3e82f30a1424bf5d5d38299ce22ce9c461', '::1', 1483946465, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438333934363436353B),
	('0c7cc8d1bfab2e17bb45846b1b0dec05703e5d6f', '127.0.0.1', 1485416383, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353431323539373B757365725F69647C733A313A2234223B757365725F757365726E616D657C733A31303A226C6F67697374696B2062223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2062223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2232223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('362cf0eccc770dda367e60b7ceeadbeba892a802', '::1', 1485416386, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353431313433363B757365725F69647C733A313A2232223B757365725F757365726E616D657C733A31303A226C6F67697374696B2061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B7365735F636F6E74656E74737C613A313A7B733A33323A223531333038323339333534353364653130623933623834646532616136343732223B613A353A7B733A323A226964223B733A31333A2235383839613763323935623666223B733A393A22626172616E675F6964223B733A313A2236223B733A333A22717479223B693A31303B733A31303A226B65746572616E67616E223B733A303A22223B733A353A22726F776964223B733A33323A223531333038323339333534353364653130623933623834646532616136343732223B7D7D),
	('365bd334341e56596b29124c08ffd43aca92cacd', '::1', 1485842115, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353834313839343B757365725F69647C733A313A2232223B757365725F757365726E616D657C733A31303A226C6F67697374696B2061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('3a8f3883257be605d272e4e36c081d92958279aa', '::1', 1483921715, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438333932313731353B),
	('40b571e92736d0ebb50497de39b353f54b73b582', '127.0.0.1', 1484788634, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438343730373137353B757365725F69647C733A313A2231223B757365725F757365726E616D657C733A31303A2270726F64756B73692061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E2070726F64756B73692061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A2270726F64756B7369223B),
	('6e8ef6fefef211723ec85a25ef22dd32366488d1', '::1', 1484898211, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438343831373334343B757365725F69647C733A313A2232223B757365725F757365726E616D657C733A31303A226C6F67697374696B2061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('768d677626034b832f2a93b375b006147f47a48d', '127.0.0.1', 1485842132, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353834313833333B757365725F69647C733A313A2234223B757365725F757365726E616D657C733A31303A226C6F67697374696B2062223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2062223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2232223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('8c6bb99d84dde915b6b29510213fa163d74ab395', '::1', 1485333309, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353333333139333B757365725F69647C733A313A2232223B757365725F757365726E616D657C733A31303A226C6F67697374696B2061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('8d6d781938604502c072af695c4e0190df6f3cd3', '127.0.0.1', 1484898011, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438343839353331393B757365725F69647C733A313A2234223B757365725F757365726E616D657C733A31303A226C6F67697374696B2062223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2062223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2232223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B),
	('b96c1ddd9be069c4a904b60d58f7c3cd4d40b7ba', '127.0.0.1', 1484818008, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438343831383030383B),
	('e17ff84e01dbe24a185a486b4b48ff2d87b1b9e1', '127.0.0.1', 1485411880, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353431313838303B),
	('e5c795c4469c8b84335d266c7c18111cfc71c8df', '127.0.0.1', 1485411880, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353431313838303B),
	('e616cb4e35b1c51a489e22d8ce1746dc98f21484', '127.0.0.1', 1484818008, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438343831383030383B),
	('f1697189752bc6ecbbd09de33972c5d6acab9832', '::1', 1485111088, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438353131313038383B),
	('f6c2bd1c4b82b60767a18007830537fe3417726b', '::1', 1483934846, _binary 0x5F5F63695F6C6173745F726567656E65726174657C693A313438333933343139333B757365725F69647C733A313A2232223B757365725F757365726E616D657C733A31303A226C6F67697374696B2061223B757365725F6E616D616C656E676B61707C733A31373A2262616769616E206C6F67697374696B2061223B757365725F6C6576656C7C733A353A226B61626167223B757365725F677564616E677C733A313A2231223B757365725F6A61626174616E7C733A383A226C6F67697374696B223B);
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.gudang
CREATE TABLE IF NOT EXISTS `gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `lokasi` text NOT NULL,
  `user_produksi` int(11) NOT NULL,
  `user_logistik` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.gudang: ~2 rows (approximately)
DELETE FROM `gudang`;
/*!40000 ALTER TABLE `gudang` DISABLE KEYS */;
INSERT INTO `gudang` (`id`, `nama`, `lokasi`, `user_produksi`, `user_logistik`, `updated_at`) VALUES
	(1, 'Gudang A', '<p>\r\n	Kab x Kec Y Desa Z</p>\r\n', 1, 2, '2016-12-26 13:48:19'),
	(2, 'Gudang B', '<p>\r\n	Kab A Kec B Desa C</p>\r\n', 3, 4, '2016-12-26 13:48:19');
/*!40000 ALTER TABLE `gudang` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.permintaan
CREATE TABLE IF NOT EXISTS `permintaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` enum('peminjaman','pembelian','pemakaian','pengembalian') NOT NULL,
  `tgl` date NOT NULL,
  `dari_gudang` int(11) NOT NULL,
  `dari_user` int(11) NOT NULL,
  `tujuan_gudang` int(11) NOT NULL,
  `tujuan_user` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `tgl_batas` date NOT NULL,
  `current_user` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='dari,ke => gudang_id';

-- Dumping data for table argo_tuhu_konstruksi.permintaan: ~0 rows (approximately)
DELETE FROM `permintaan`;
/*!40000 ALTER TABLE `permintaan` DISABLE KEYS */;
INSERT INTO `permintaan` (`id`, `jenis`, `tgl`, `dari_gudang`, `dari_user`, `tujuan_gudang`, `tujuan_user`, `status`, `tgl_batas`, `current_user`, `updated_at`) VALUES
	(1, 'peminjaman', '2017-01-31', 1, 1, 2, 3, '205', '2017-01-31', 4, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `permintaan` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.permintaan_detail
CREATE TABLE IF NOT EXISTS `permintaan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.permintaan_detail: ~0 rows (approximately)
DELETE FROM `permintaan_detail`;
/*!40000 ALTER TABLE `permintaan_detail` DISABLE KEYS */;
INSERT INTO `permintaan_detail` (`id`, `permintaan_id`, `barang_id`, `qty`, `keterangan`) VALUES
	(1, 1, 2, 2, ''),
	(2, 1, 10, 2, '');
/*!40000 ALTER TABLE `permintaan_detail` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.satuan: ~8 rows (approximately)
DELETE FROM `satuan`;
/*!40000 ALTER TABLE `satuan` DISABLE KEYS */;
INSERT INTO `satuan` (`id`, `nama`, `updated_at`) VALUES
	(1, 'liter', '2016-12-26 13:48:08'),
	(2, 'tabung', '2016-12-26 13:48:08'),
	(3, 'buah', '2016-12-26 13:48:08'),
	(4, 'meter', '2016-12-26 13:48:08'),
	(5, 'pack', '2016-12-26 13:48:08'),
	(6, 'lonjor', '2016-12-26 13:48:08'),
	(7, 'set', '2016-12-26 13:48:08'),
	(8, 'roll', '2016-12-26 13:48:08');
/*!40000 ALTER TABLE `satuan` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.sirkulasi_barang
CREATE TABLE IF NOT EXISTS `sirkulasi_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gudang_id` int(11) NOT NULL,
  `jenis` enum('keluar','masuk') NOT NULL,
  `nomor` varchar(50) NOT NULL,
  `nomor_permintaan` varchar(50) NOT NULL,
  `user_pembuat` int(11) NOT NULL,
  `pengembalian` enum('Y','N') NOT NULL DEFAULT 'N',
  `catatan` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor` (`nomor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='pemeriksa = user pemeriksa';

-- Dumping data for table argo_tuhu_konstruksi.sirkulasi_barang: ~0 rows (approximately)
DELETE FROM `sirkulasi_barang`;
/*!40000 ALTER TABLE `sirkulasi_barang` DISABLE KEYS */;
INSERT INTO `sirkulasi_barang` (`id`, `tgl`, `gudang_id`, `jenis`, `nomor`, `nomor_permintaan`, `user_pembuat`, `pengembalian`, `catatan`) VALUES
	(1, '2017-01-31 12:50:59', 2, 'keluar', 'BK-0000000001', '1', 4, 'N', NULL),
	(2, '2017-01-31 12:52:15', 1, 'masuk', 'BM-0000000002', '1', 2, 'N', NULL),
	(3, '2017-01-31 12:53:49', 1, 'keluar', 'BK-0000000003', '1', 2, 'Y', NULL),
	(4, '2017-01-31 12:54:32', 2, 'masuk', 'BM-0000000004', '1', 4, 'Y', NULL),
	(5, '2017-01-31 12:55:13', 1, 'keluar', 'BK-0000000005', '1', 2, 'Y', NULL),
	(6, '2017-01-31 12:55:25', 2, 'masuk', 'BM-0000000006', '1', 4, 'Y', NULL);
/*!40000 ALTER TABLE `sirkulasi_barang` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.sirkulasi_barang_detail
CREATE TABLE IF NOT EXISTS `sirkulasi_barang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sirkulasi_barang_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.sirkulasi_barang_detail: ~0 rows (approximately)
DELETE FROM `sirkulasi_barang_detail`;
/*!40000 ALTER TABLE `sirkulasi_barang_detail` DISABLE KEYS */;
INSERT INTO `sirkulasi_barang_detail` (`id`, `sirkulasi_barang_id`, `barang_id`, `qty`, `keterangan`) VALUES
	(1, 1, 2, 2, ''),
	(2, 1, 10, 2, ''),
	(3, 2, 2, 2, ''),
	(4, 2, 10, 2, ''),
	(5, 3, 10, 1, ''),
	(6, 4, 10, 1, ''),
	(7, 5, 10, 1, ''),
	(8, 6, 10, 1, '');
/*!40000 ALTER TABLE `sirkulasi_barang_detail` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.stok
CREATE TABLE IF NOT EXISTS `stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gudang_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty` smallint(6) NOT NULL,
  `qty_piutang` smallint(6) NOT NULL,
  `qty_hutang` smallint(6) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gudang_id_item_id` (`gudang_id`,`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='qty = stok dalam gudang';

-- Dumping data for table argo_tuhu_konstruksi.stok: ~0 rows (approximately)
DELETE FROM `stok`;
/*!40000 ALTER TABLE `stok` DISABLE KEYS */;
INSERT INTO `stok` (`id`, `gudang_id`, `barang_id`, `qty`, `qty_piutang`, `qty_hutang`, `updated_at`) VALUES
	(1, 1, 1, 10, 0, 0, '2017-01-31 12:48:15'),
	(2, 1, 6, 10, 0, 0, '2017-01-31 12:48:26'),
	(3, 2, 2, 8, 0, 0, '2017-01-31 12:50:59'),
	(4, 2, 10, 10, 0, 0, '2017-01-31 12:55:25'),
	(7, 1, 2, 2, 0, 0, '2017-01-31 12:52:15'),
	(8, 1, 10, 0, 0, 0, '2017-01-31 12:55:13');
/*!40000 ALTER TABLE `stok` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.stok_opname
CREATE TABLE IF NOT EXISTS `stok_opname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gudang_id` int(11) NOT NULL,
  `tgl` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty` smallint(6) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.stok_opname: ~0 rows (approximately)
DELETE FROM `stok_opname`;
/*!40000 ALTER TABLE `stok_opname` DISABLE KEYS */;
INSERT INTO `stok_opname` (`id`, `gudang_id`, `tgl`, `user_id`, `barang_id`, `qty`, `keterangan`) VALUES
	(1, 1, '2017-01-31 12:48:15', 2, 1, 10, ''),
	(2, 1, '2017-01-31 12:48:26', 2, 6, 10, ''),
	(3, 2, '2017-01-31 12:49:09', 4, 2, 10, ''),
	(4, 2, '2017-01-31 12:49:24', 4, 10, 10, '');
/*!40000 ALTER TABLE `stok_opname` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.tracking_permintaan
CREATE TABLE IF NOT EXISTS `tracking_permintaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `permintaan_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `catatan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='status => tracking_status';

-- Dumping data for table argo_tuhu_konstruksi.tracking_permintaan: ~0 rows (approximately)
DELETE FROM `tracking_permintaan`;
/*!40000 ALTER TABLE `tracking_permintaan` DISABLE KEYS */;
INSERT INTO `tracking_permintaan` (`id`, `tgl`, `user_id`, `permintaan_id`, `status`, `catatan`) VALUES
	(1, '2017-01-31 12:50:10', 1, 1, '600', ''),
	(2, '2017-01-31 12:50:27', 3, 1, '100', ''),
	(3, '2017-01-31 12:50:53', 4, 1, '200', '');
/*!40000 ALTER TABLE `tracking_permintaan` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.tracking_status
CREATE TABLE IF NOT EXISTS `tracking_status` (
  `kode` varchar(10) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `css_class` varchar(50) NOT NULL,
  `glyphs` varchar(50) NOT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.tracking_status: ~10 rows (approximately)
DELETE FROM `tracking_status`;
/*!40000 ALTER TABLE `tracking_status` DISABLE KEYS */;
INSERT INTO `tracking_status` (`kode`, `keterangan`, `css_class`, `glyphs`) VALUES
	('100', 'Permintaan disetujui', 'alert alert-success', 'glyphicon glyphicon-ok-sign'),
	('110', 'Permintaan ditolak', 'alert alert-danger', 'glyphicon glyphicon-remove-sign'),
	('200', 'Barang permintaan diproses', 'alert alert-warning', 'glyphicon glyphicon-hourglass'),
	('205', 'Barang permintaan menunggu dikirimkan', 'alert alert-warning', 'glyphicon glyphicon-hourglass'),
	('210', 'Barang permintaan telah dikirimkan / keluar gudang', 'alert alert-info', 'glyphicon glyphicon-info-sign'),
	('220', 'Barang permintaan telah diterima / masuk gudang', 'alert alert-info', 'glyphicon glyphicon-info-sign'),
	('230', 'Barang permintaan diretur', 'alert alert-danger', 'glyphicon glyphicon-share-alt'),
	('400', 'Menghubungi supplier untuk pengadaan barang', 'alert alert-info', 'glyphicon glyphicon-phone-alt'),
	('420', 'Pembelian barang telah dilakukan pada supplier', 'alert alert-info', 'glyphicon glyphicon-shopping-cart'),
	('500', 'Menunggu tanggapan', 'alert alert-warning', 'glyphicon glyphicon-hourglass'),
	('600', 'Permintaan dibuat & menunggu tanggapan', 'alert alert-info', 'glyphicon glyphicon-hourglass'),
	('700', 'Menunggu persetujuan pimpinan', 'alert alert-info', 'glyphicon glyphicon-hourglass');
/*!40000 ALTER TABLE `tracking_status` ENABLE KEYS */;

-- Dumping structure for table argo_tuhu_konstruksi.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `telepon` varchar(50) NOT NULL,
  `level` enum('kabag','pimpinan') NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table argo_tuhu_konstruksi.user: ~5 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `password`, `nama_lengkap`, `telepon`, `level`, `updated_at`) VALUES
	(1, 'produksi a', '74a5547e9d5f83b8001663420cf2d47d', 'bagian produksi a', '1234567890', 'kabag', '2016-12-30 22:24:37'),
	(2, 'logistik a', '419d671d2595870a9a710cfd7367fa80', 'bagian logistik a', '1234567890', 'kabag', '2016-12-30 22:24:37'),
	(3, 'produksi b', '36a990a6ec37d9cfc93e253effc06c1d', 'bagian produksi b', '1234567890', 'kabag', '2016-12-30 22:24:37'),
	(4, 'logistik b', 'c43187dc462c566e345c96e9f896a158', 'bagian logistik b', '1234567890', 'kabag', '2016-12-30 22:24:37'),
	(5, 'pimpinan', '90973652b88fe07d05a4304f0a945de8', 'pimpinan', '1234567890', 'pimpinan', '2016-12-29 22:51:41');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for trigger argo_tuhu_konstruksi.sirkulasi_barang_detail_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `sirkulasi_barang_detail_before_insert` BEFORE INSERT ON `sirkulasi_barang_detail` FOR EACH ROW BEGIN
  DECLARE m_jenis BOOL DEFAULT 0;
  DECLARE m_kategori BOOL DEFAULT 0;
  DECLARE m_nomor_permintaan INT;
  DECLARE m_gudang_id INT;
  DECLARE m_jenis_barang BOOL DEFAULT 0;
  DECLARE m_pengembalian BOOL DEFAULT 0;
  
  SET @m_jenis = (SELECT IF(jenis = 'masuk',1,0) AS jenis FROM sirkulasi_barang WHERE id = NEW.sirkulasi_barang_id);
  SET @m_gudang_id = (SELECT gudang_id FROM sirkulasi_barang WHERE id = NEW.sirkulasi_barang_id);
  SET @m_nomor_permintaan = (SELECT nomor_permintaan FROM sirkulasi_barang WHERE id = NEW.sirkulasi_barang_id);  
  SET @m_kategori = (SELECT jenis FROM permintaan WHERE id = @m_nomor_permintaan);  
  SET @m_jenis_barang = (SELECT IF(kategori = 'alat',1,0) AS jenis_barang FROM barang WHERE id = NEW.barang_id);
  SET @m_pengembalian = (SELECT IF(pengembalian = 'Y',1,0) AS is_pengembalian FROM sirkulasi_barang WHERE id = NEW.sirkulasi_barang_id);

   	 
  IF (@m_jenis = 1) THEN
     -- masuk
     IF (@m_kategori = 'peminjaman') THEN
     		IF(@m_pengembalian = 1) THEN
     			-- jika peminjaman dan pengembalian masuk, maka stok gudang bertambah dan piutang berkurang
     			IF (@m_jenis_barang = 1) THEN
     				-- stok bertambah , piutang berkurang
     				INSERT INTO stok(gudang_id,barang_id,qty,qty_piutang) VALUES(@m_gudang_id,NEW.barang_id,qty + NEW.qty,qty_piutang - NEW.qty) ON DUPLICATE KEY UPDATE qty = qty + NEW.qty, qty_piutang = qty_piutang - NEW.qty;
     			END IF;
     		ELSE
			  	-- jika bukan pengembalian, maka itu berarti barang masuk ke gudang peminta. stok gudang bertambah, hutang bertambah	
			  	IF (@m_jenis_barang = 1) THEN
			  		-- stok gudang alat bertambah, hutang bertambah
			  		INSERT INTO stok(gudang_id,barang_id,qty,qty_hutang) VALUES(@m_gudang_id,NEW.barang_id,qty + NEW.qty,ABS(qty_hutang) - NEW.qty) ON DUPLICATE KEY UPDATE qty = qty + NEW.qty, qty_hutang = ABS(qty_hutang) - NEW.qty;
			  	ELSE
			  		-- stok gudang bertambah, hutang tetap karena bahan tidak dianggap hutang
			  		INSERT INTO stok(gudang_id,barang_id,qty) VALUES(@m_gudang_id,NEW.barang_id,qty + NEW.qty) ON DUPLICATE KEY UPDATE qty = qty + NEW.qty;

			  	END IF;
     		END IF;
     ELSE
     		-- selain permintaan, berlaku normal
     		INSERT INTO stok(gudang_id,barang_id,qty) VALUES(@m_gudang_id,NEW.barang_id,qty + NEW.qty) ON DUPLICATE KEY UPDATE qty = qty + NEW.qty;
     END IF;
     
  	  
  ELSE
    -- keluar
    -- jika permintaan keluar akibat dari peminjaman, maka stok qty tetap namun qty_piutang bertambah    
       
	 IF (@m_kategori = 'peminjaman') THEN
	 	 IF(@m_pengembalian = 1) THEN
	 	 	-- keluar untuk mengembalikan
	 	 	IF (@m_jenis_barang = 1) THEN
	 	 		-- alat, diasumsikan bahwa yang di kembaliakn selalu hanya alat
	 	 		-- jika keluar dan itu pengambalian, maka qty berkurang dan hutang berkurang
	 	 		INSERT INTO stok(gudang_id,barang_id,qty, qty_hutang) VALUES(@m_gudang_id,NEW.barang_id,qty - NEW.qty, ABS(qty_hutang) - NEW.qty) ON DUPLICATE KEY UPDATE qty = qty - NEW.qty, qty_hutang = ABS(qty_hutang) - NEW.qty;  		 	 	 
	 	 		
	 	 	END IF;
	 	 	
		 ELSE
		   -- keluar bukan pengambalian
		   IF (@m_jenis_barang = 1) THEN
		    	--  alat
		    	-- jika barang itu alat, maka stok berkurang dan piutang bertambah	 
			 	INSERT INTO stok(gudang_id,barang_id,qty, qty_piutang) VALUES(@m_gudang_id,NEW.barang_id,qty - NEW.qty, qty_piutang + NEW.qty) ON DUPLICATE KEY UPDATE qty = qty - NEW.qty, qty_piutang = qty_piutang + NEW.qty;  
		 
		    ELSE
		      -- bahan
				-- jika barang itu bahan maka qty piutang tetap dan stok qty berkurang
				INSERT INTO stok(gudang_id,barang_id,qty) VALUES(@m_gudang_id,NEW.barang_id,qty - NEW.qty) ON DUPLICATE KEY UPDATE qty = qty - NEW.qty;  	 
		    END IF;		 
			 
		 END IF; 	
	    
		 
	 ELSE
	    -- jika bukan peminjaman, maka qty berkurang
	    INSERT INTO stok(gudang_id,barang_id,qty) VALUES(@m_gudang_id,NEW.barang_id,qty - NEW.qty) ON DUPLICATE KEY UPDATE qty = qty - NEW.qty;  	 
	 END IF;
	 
	 
  END IF;
  
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger argo_tuhu_konstruksi.stok_opname_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `stok_opname_before_insert` BEFORE INSERT ON `stok_opname` FOR EACH ROW BEGIN
  INSERT INTO stok (gudang_id,barang_id,qty) VALUES(NEW.gudang_id,NEW.barang_id,NEW.qty) ON DUPLICATE KEY UPDATE qty = NEW.qty;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
