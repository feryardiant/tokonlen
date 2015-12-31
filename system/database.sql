--
-- Table structure for table `tbl_banner`
--

DROP TABLE IF EXISTS `tbl_banner`;
CREATE TABLE `tbl_banner` (
  `id_banner` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) unsigned NOT NULL,
  `tgl_input` date NOT NULL,
  `judul` varchar(100) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `url` varchar(100) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `tipe` enum('slide','konten','samping') DEFAULT NULL,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_banner`
--

INSERT INTO `tbl_banner` VALUES
  (1,1,'2015-04-12','Produk Unggulan','Keterangan Slide 1','#','slide-produk-unggulan.gif','2015-04-12','2015-05-12',1,'slide'),
  (2,1,'2015-04-12','Promo Berhadiah','Keterangan Slide 2','#','slide-promo-menarik.gif','2015-04-12','2015-05-12',1,'slide'),
  (3,1,'2015-04-12','Kredit Tanpa Angsuran :v','Keterangan Slide 3','#','slide-promo-kredit.gif','2015-04-12','2015-05-12',1,'slide');

--
-- Table structure for table `tbl_halaman`
--

DROP TABLE IF EXISTS `tbl_halaman`;
CREATE TABLE `tbl_halaman` (
  `id_halaman` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) unsigned NOT NULL,
  `tgl_input` date NOT NULL,
  `judul` varchar(255) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `konten` text NOT NULL,
  PRIMARY KEY (`id_halaman`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_halaman`
--

INSERT INTO `tbl_halaman` VALUES
  (1,1,'2015-04-12','Profil','profil','Isi halaman (Dalam pengembangan)'),
  (2,1,'2015-04-12','Ketentuan','ketentuan','Isi halaman (Dalam pengembangan)'),
  (3,1,'2015-04-12','Hubungi Kami','kontak','Isi halaman (Dalam pengembangan)');

--
-- Table structure for table `tbl_order`
--

DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE `tbl_order` (
  `id_order` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) unsigned NOT NULL,
  `id_pelanggan` int(11) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `produk` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `belanja` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `kurir`  varchar(50) DEFAULT '',
  `ongkir` int(11) DEFAULT '0',
  `bayar` int(11) NOT NULL,
  `resi` varchar(40) DEFAULT NULL,
  `pembayaran` varchar(50) DEFAULT '',
  `kembali` int(11) NOT NULL,
  `potongan` int(11) DEFAULT '0',
  PRIMARY KEY (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_order`
--

--
-- Table structure for table `tbl_pelanggan`
--

DROP TABLE IF EXISTS `tbl_pelanggan`;
CREATE TABLE `tbl_pelanggan` (
  `id_pelanggan` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) unsigned NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(20) NOT NULL,
  `telp` varchar(16) NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_pelanggan`
--

INSERT INTO `tbl_pelanggan` VALUES
  (1,2,'Pelanggan Ganteng','Alamat Lengkap Pelanggan','Semarang','0987654321');

--
-- Table structure for table `tbl_pengguna`
--

DROP TABLE IF EXISTS `tbl_pengguna`;
CREATE TABLE `tbl_pengguna` (
  `id_pengguna` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `aktif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` VALUES
  (1,'admin','admin@email.com','81dc9bdb52d04dc20036dbd8313ed055',1,1),
  (2,'pelanggan','pelanggan@email.com','81dc9bdb52d04dc20036dbd8313ed055',0,1);

--
-- Table structure for table `tbl_produk`
--

DROP TABLE IF EXISTS `tbl_produk`;
CREATE TABLE `tbl_produk` (
  `id_produk` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) unsigned NOT NULL,
  `id_pengguna` int(11) unsigned NOT NULL,
  `tgl_input` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `berat` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` VALUES
  (1,1,1,'2015-04-12','Nama Produk 1','products.png','2015-04-12',20,10000,700,0,'Keterangan lengkap mengenai produk 1'),
  (2,2,1,'2015-04-12','Nama Produk 2','products.png','2015-04-12',20,20000,600,18000,'Keterangan lengkap mengenai produk 2'),
  (3,5,1,'2015-04-12','Nama Produk 3','products.png','2015-04-12',20,50000,500,0,'Keterangan lengkap mengenai produk 3'),
  (4,3,1,'2015-04-12','Nama Produk 4','products.png','2015-04-12',20,30000,700,28000,'Keterangan lengkap mengenai produk 4'),
  (5,4,1,'2015-04-12','Nama Produk 5','products.png','2015-04-12',20,40000,800,0,'Keterangan lengkap mengenai produk 5'),
  (6,1,1,'2015-04-12','Nama Produk 6','products.png','2015-04-12',20,10000,1200,0,'Keterangan lengkap mengenai produk 6'),
  (7,2,1,'2015-04-12','Nama Produk 7','products.png','2015-04-12',20,20000,500,19000,'Keterangan lengkap mengenai produk 7'),
  (8,4,1,'2015-04-12','Nama Produk 8','products.png','2015-04-12',20,40000,800,0,'Keterangan lengkap mengenai produk 8'),
  (9,5,1,'2015-04-12','Nama Produk 9','products.png','2015-04-12',20,50000,700,0,'Keterangan lengkap mengenai produk 9'),
  (10,2,1,'2015-04-12','Nama Produk 10','products.png','2015-04-12',20,20000,500,0,'Keterangan lengkap mengenai produk 10'),
  (11,1,1,'2015-04-12','Nama Produk 11','products.png','2015-04-12',20,10000,1200,0,'Keterangan lengkap mengenai produk 11'),
  (12,3,1,'2015-04-12','Nama Produk 12','products.png','2015-04-12',20,30000,800,27000,'Keterangan lengkap mengenai produk 12'),
  (13,2,1,'2015-04-12','Nama Produk 13','products.png','2015-04-12',20,20000,700,0,'Keterangan lengkap mengenai produk 13'),
  (14,3,1,'2015-04-12','Nama Produk 14','products.png','2015-04-12',20,30000,1200,25000,'Keterangan lengkap mengenai produk 14'),
  (15,5,1,'2015-04-12','Nama Produk 15','products.png','2015-04-12',20,50000,700,0,'Keterangan lengkap mengenai produk 15'),
  (16,1,1,'2015-04-12','Nama Produk 16','products.png','2015-04-12',20,10000,800,0,'Keterangan lengkap mengenai produk 16'),
  (17,3,1,'2015-04-12','Nama Produk 17','products.png','2015-04-12',20,30000,500,0,'Keterangan lengkap mengenai produk 17'),
  (18,4,1,'2015-04-12','Nama Produk 18','products.png','2015-04-12',20,40000,500,0,'Keterangan lengkap mengenai produk 18'),
  (19,5,1,'2015-04-12','Nama Produk 19','products.png','2015-04-12',20,50000,1200,0,'Keterangan lengkap mengenai produk 19'),
  (20,2,1,'2015-04-12','Nama Produk 20','products.png','2015-04-12',20,20000,500,0,'Keterangan lengkap mengenai produk 20');

--
-- Table structure for table `tbl_kategori`
--

DROP TABLE IF EXISTS `tbl_kategori`;
CREATE TABLE `tbl_kategori` (
  `id_kategori` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` VALUES
  (1,'Motif 1','motif-1',''),
  (2,'Motif 2','motif-2',''),
  (3,'Motif 3','motif-3',''),
  (4,'Motif 4','motif-4',''),
  (5,'Motif 5','motif-5','');

--
--  Table Relation
--

ALTER TABLE tbl_banner
ADD CONSTRAINT banner_author FOREIGN KEY (id_pengguna)
  REFERENCES tbl_pengguna (id_pengguna) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tbl_halaman
ADD CONSTRAINT halaman_author FOREIGN KEY (id_pengguna)
  REFERENCES tbl_pengguna (id_pengguna) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tbl_pelanggan
ADD CONSTRAINT pelanggan_login FOREIGN KEY (id_pengguna)
  REFERENCES tbl_pengguna (id_pengguna) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tbl_order
ADD CONSTRAINT order_author FOREIGN KEY (id_pengguna)
  REFERENCES tbl_pengguna (id_pengguna) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT order_pelanggan FOREIGN KEY (id_pelanggan)
  REFERENCES tbl_pelanggan (id_pelanggan) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tbl_produk
ADD CONSTRAINT produk_author FOREIGN KEY (id_pengguna)
  REFERENCES tbl_pengguna (id_pengguna) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT produk_kategori FOREIGN KEY (id_kategori)
  REFERENCES tbl_kategori (id_kategori) ON DELETE CASCADE ON UPDATE CASCADE;
