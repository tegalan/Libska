DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(25) NOT NULL,
  `aksi` text NOT NULL,
  `tgl` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_anggota`;
CREATE TABLE IF NOT EXISTS `tbl_anggota` (
  `no_induk` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jurusan` varchar(10) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `denda` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`no_induk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_buku`;
CREATE TABLE IF NOT EXISTS `tbl_buku` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `kd_buku` varchar(20) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pengarang` varchar(50) NOT NULL,
  `thn_terbit` varchar(5) NOT NULL DEFAULT '-',
  `penerbit` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `status` tinyint(10) NOT NULL,
  `peminjam` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`no`),
  UNIQUE KEY `kd_buku` (`kd_buku`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_config`;
CREATE TABLE IF NOT EXISTS `tbl_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(250) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_kas`;
CREATE TABLE IF NOT EXISTS `tbl_kas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `ket` text NOT NULL,
  `masuk` int(11) NOT NULL DEFAULT '0',
  `keluar` int(11) NOT NULL DEFAULT '0',
  `saldo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tbl_peminjaman`;
CREATE TABLE IF NOT EXISTS `tbl_peminjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa` varchar(50) NOT NULL,
  `buku` varchar(50) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_tempo` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `kembali` tinyint(1) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tbl_pustakawan`;
CREATE TABLE IF NOT EXISTS `tbl_pustakawan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(25) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kunci` varchar(50) NOT NULL,
  `level` varchar(25) NOT NULL DEFAULT 'Pustakawan',
  `login` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tbl_telat`;
CREATE TABLE IF NOT EXISTS `tbl_telat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buku` varchar(50) DEFAULT NULL,
  `induk` int(10) NOT NULL,
  `siswa` varchar(200) DEFAULT NULL,
  `tanggal` varchar(25) DEFAULT NULL,
  `judul` varchar(300) DEFAULT NULL,
  `kunci` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
