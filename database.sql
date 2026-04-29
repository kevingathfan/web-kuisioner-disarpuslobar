-- Database Export: monitoring_perpus_db
SET FOREIGN_KEY_CHECKS=0;



-- Structure for kategori_bagian
DROP TABLE IF EXISTS `kategori_bagian`;
CREATE TABLE `kategori_bagian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_kuesioner` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` int NOT NULL DEFAULT '0',
  `numbering_style` enum('numeric','roman','none') NOT NULL DEFAULT 'numeric',
  `manual_label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for kategori_bagian
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('1', 'IPLM', 'DATA JENIS PERPUSTAKAAN', '1', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('2', 'IPLM', 'DATA DEMOGRAFI', '2', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('3', 'IPLM', 'DIMENSI KOLEKSI', '3', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('4', 'IPLM', 'DIMENSI TENAGA PERPUSTAKAAN', '4', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('5', 'IPLM', 'DIMENSI PELAYANAN', '5', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('6', 'IPLM', 'DIMENSI PENYELENGGARAAN', '6', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('7', 'TKM', 'DATA DEMOGRAFI', '1', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('8', 'TKM', 'KEBIASAAN MEMBACA', '2', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('9', 'TKM', 'PRA MEMBACA', '3', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('10', 'TKM', 'SAAT MEMBACA', '4', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('11', 'TKM', 'PASCA MEMBACA', '5', 'numeric', NULL, '2026-02-10 13:16:17');
INSERT INTO `kategori_bagian` (`id`, `jenis_kuesioner`, `name`, `position`, `numbering_style`, `manual_label`, `created_at`) VALUES ('12', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', '6', 'numeric', NULL, '2026-02-10 13:16:17');


-- Structure for libraries
DROP TABLE IF EXISTS `libraries`;
CREATE TABLE `libraries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `lokasi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kategori` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for libraries
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('1', 'PERPUSTAKAAN BABUSSALAM', 'Perpustakaan Desa', 'BABUSSALAM', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('2', 'PERPUSTAKAAN DESA BANYU URIP', 'Perpustakaan Desa', 'BANYU URIP', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('3', 'PERPUSTAKAAN DASAN TAPEN', 'Perpustakaan Desa', 'DASAN TAPEN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('4', 'PERPUSTAKAAN GAPUK', 'Perpustakaan Desa', 'GAPUK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('5', 'PERPUSTAKAAN GERUNG UTARA', 'Perpustakaan Desa', 'GERUNG UTARA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('6', 'PERPUSTAKAAN KEBON AYU', 'Perpustakaan Desa', 'KEBON AYU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('7', 'PERPUSTAKAAN MESANGGOK', 'Perpustakaan Desa', 'MESANGGOK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('8', 'PERPUSTAKAAN TEMPOS', 'Perpustakaan Desa', 'TEMPOS', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('9', 'PERPUSTAKAAN JATI SELA', 'Perpustakaan Desa', 'JATISELA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('10', 'PERPUSTAKAAN KEKERI', 'Perpustakaan Desa', 'KEKERI', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('11', 'PERPUSTAKAAN DASAN BARU', 'Perpustakaan Desa', 'DASAN BARU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('12', 'PERPUSTAKAAN GELOGOR', 'Perpustakaan Desa', 'GELOGOR', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('13', 'PERPUSTAKAAN DESA JAGARAGA INDAH', 'Perpustakaan Desa', 'JAGARAGA INDAH', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('14', 'PERPUSTAKAAN KEDIRI', 'Perpustakaan Desa', 'KEDIRI', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('15', 'PERPUSTAKAAN KEDIRI SELATAN', 'Perpustakaan Desa', 'KEDIRI SELATAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('16', 'PERPUSTAKAAN LELEDE', 'Perpustakaan Desa', 'LELEDE', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('17', 'PERPUSTAKAAN OMBE BARU', 'Perpustakaan Desa', 'OMBE BARU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('18', 'PERPUSTAKAAN RUMAK', 'Perpustakaan Desa', 'RUMAK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('19', 'PERPUSTAKAAN GIRI SASAK', 'Perpustakaan Desa', 'GIRI SASAK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('20', 'PERPUSTAKAAN JAGARAGA', 'Perpustakaan Desa', 'JAGARAGA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('21', 'PERPUSTAKAAN DESA KURIPAN', 'Perpustakaan Desa', 'KURIPAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('22', 'PERPUSTAKAAN KURIPAN UTARA', 'Perpustakaan Desa', 'KURIPAN UTARA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('23', 'PERPUSTAKAAN BAGIK POLAK', 'Perpustakaan Desa', 'BAGIK POLAK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('24', 'PERPUSTAKAAN BAJUR', 'Perpustakaan Desa', 'BAJUR', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('25', 'PERPUSTAKAAN BENGKEL', 'Perpustakaan Desa', 'BENGKEL', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('27', 'PERPUSTAKAAN KURANJI DALANG', 'Perpustakaan Desa', 'KURANJI DALANG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('28', 'PERPUSTAKAAN LABUAPI', 'Perpustakaan Desa', 'LABUAPI', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('29', 'PERPUSTAKAAN TELAGAWARU', 'Perpustakaan Desa', 'TELAGAWARU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('30', 'PERPUSTAKAAN LABUAN TERENG', 'Perpustakaan Desa', 'LABUAN TERENG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('31', 'PERPUSTAKAAN  LEMBAR SELATAN', 'Perpustakaan Desa', 'LEMBAR SELATAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('32', 'PERPUSTAKAAN JEMBATAN GANTUNG', 'Perpustakaan Desa', 'JEMBATAN GANTUNG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('33', 'PERPUSTAKAAN JEMBATAN KEMBAR TIMUR', 'Perpustakaan Desa', 'JEMBATAN KEMBAR TIMUR', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('34', 'PERPUSTAKAAN BATU KUMBUNG', 'Perpustakaan Desa', 'BATU KUMBUNG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('35', 'PERPUSTAKAAN BATU MEKAR', 'Perpustakaan Desa', 'BATU MEKAR', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('36', 'PERPUSTAKAAN BUGBUG', 'Perpustakaan Desa', 'BUG-BUG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('37', 'PERPUSTAKAAN DASAN GERIA', 'Perpustakaan Desa', 'DASAN GERIA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('38', 'PERPUSTAKAAN LANGKO', 'Perpustakaan Desa', 'LANGKO', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('39', 'PERPUSTAKAAN SARIBAYE', 'Perpustakaan Desa', 'SARIBAYE', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('40', 'PERPUSTAKAAN SIGERONGAN', 'Perpustakaan Desa', 'SIGERONGAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('41', 'PERPUSTAKAAN KARANG BAYAN', 'Perpustakaan Desa', 'KARANG BAYAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('42', 'PERPUSTAKAAN BADRAIN', 'Perpustakaan Desa', 'BADRAIN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('43', 'PERPUSTAKAAN BATU KUTA', 'Perpustakaan Desa', 'BATU KUTA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('44', 'PERPUSTAKAAN DASAN TERENG', 'Perpustakaan Desa', 'DASAN TERENG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('45', 'PERPUSTAKAAN GOLONG', 'Perpustakaan Desa', 'GOLONG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('46', 'PERPUSTAKAAN GERIMAX INDAH', 'Perpustakaan Desa', 'GERIMAX INDAH', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('47', 'PERPUSTAKAAN KERU', 'Perpustakaan Desa', 'KERU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('48', 'PERPUSTAKAAN KRAMA JAYA', 'Perpustakaan Desa', 'KRAMA JAYA', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('49', 'PERPUSTAKAAN LEBAH SEMPAGE', 'Perpustakaan Desa', 'LEBAH SEMPAGE', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('50', 'PERPUSTAKAAN MEKARSARI', 'Perpustakaan Desa', 'MEKARSARI', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('51', 'PERPUSTAKAAN NYIUR LEMBANG', 'Perpustakaan Desa', 'NYIUR LEMBANG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('52', 'PERPUSTAKAAN SELAT', 'Perpustakaan Desa', 'SELAT', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('53', 'PERPUSTAKAAN SESAOT', 'Perpustakaan Desa', 'SESAOT', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('55', 'PERPUSTAKAAN DESA PERESAK', 'Perpustakaan Desa', 'PERESAK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('56', 'PERPUSTAKAAN DESA SEDAU', 'Perpustakaan Desa', 'SEDAU', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('57', 'PERPUSTAKAAN DESA SEMBUNG', 'Perpustakaan Desa', 'SEMBUNG', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('58', 'PERPUSTAKAAN DESA SURANADI', 'Perpustakaan Desa', 'SURANADI', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('59', 'PERPUSTAKAAN BATU PUTIH', 'Perpustakaan Desa', 'BATU PUTIH', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('60', 'PERPUSTAKAAN BUWUN MAS', 'Perpustakaan Desa', 'BUWUN MAS', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('61', 'PERPUSTAKAAN SEKOTONG TENGAH', 'Perpustakaan Desa', 'SEKOTONG TENGAH', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('62', 'PERPUSTAKAAN PENIMBUNG', 'Perpustakaan Desa', 'PENIMBUNGAN', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('63', 'PERPUSTAKAAN RANJOK', 'Perpustakaan Desa', 'RANJOK', '2026-01-29 10:55:23', 'Umum');
INSERT INTO `libraries` (`id`, `nama`, `jenis`, `lokasi`, `created_at`, `kategori`) VALUES ('64', 'SDN 9 MATARAM', 'Perpustakaan SD', NULL, '2026-02-11 11:37:12', 'Sekolah');


-- Structure for master_kategori
DROP TABLE IF EXISTS `master_kategori`;
CREATE TABLE `master_kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) NOT NULL,
  `sub_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for master_kategori
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('1', 'Umum', 'Perpustakaan Desa');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('5', 'Sekolah', 'Perpustakaan SD');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('6', 'Sekolah', 'Perpustakaan SMP');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('7', 'Sekolah', 'Perpustakaan SMA');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('8', 'Sekolah', 'Perpustakaan SMK');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('11', 'Khusus', 'Perpustakaan Rumah Ibadah');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('12', 'Khusus', 'Perpustakaan Pondok Pesantren');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('14', 'Umum', 'Perpustakaan Komunitas/taman Baca Masyarakat');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('15', 'Umum', 'Perpustakaan Daerah');
INSERT INTO `master_kategori` (`id`, `kategori`, `sub_kategori`) VALUES ('16', 'Sekolah', 'Perpustakaan TK');


-- Structure for master_pertanyaan
DROP TABLE IF EXISTS `master_pertanyaan`;
CREATE TABLE `master_pertanyaan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_kuesioner` varchar(10) NOT NULL,
  `kategori_bagian` varchar(100) DEFAULT NULL,
  `teks_pertanyaan` text NOT NULL,
  `tipe_input` varchar(20) NOT NULL,
  `opsi_jawaban` text,
  `urutan` int DEFAULT '0',
  `is_active` int DEFAULT '1',
  `keterangan` text,
  `pilihan_opsi` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for master_pertanyaan
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('2', 'IPLM', 'DATA JENIS PERPUSTAKAAN', 'Subjenis Perpustakaan', 'text', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('3', 'IPLM', 'DATA JENIS PERPUSTAKAAN', 'Jumlah Guru dan Tenaga Kependidikan ', 'number', NULL, '2', '1', '(Diisi jika Perpustakaan Sekolah, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('4', 'IPLM', 'DATA JENIS PERPUSTAKAAN', 'Jumlah Siswa', 'number', NULL, '3', '1', '(Diisi jika Perpustakaan Sekolah, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('5', 'IPLM', 'DATA JENIS PERPUSTAKAAN', 'Jumlah Karyawan', 'number', NULL, '4', '1', '(Diisi jika Perpustakaan Khusus, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('6', 'IPLM', 'DATA DEMOGRAFI', 'Nomor Pokok Perpustakaan (NPP) ', 'text', NULL, '1', '1', '(Jika belum memiliki NPP, isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('7', 'IPLM', 'DATA DEMOGRAFI', 'Nomor Pokok Sekolah Nasional (NPSN)', 'text', NULL, '2', '1', '(Diisi jika Perpustakaan Sekolah, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('8', 'IPLM', 'DATA DEMOGRAFI', 'Nama Institusi/Sekolah/OPD/TBM/Lainnya', 'text', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('9', 'IPLM', 'DATA DEMOGRAFI', 'Nama Perpustakaan', 'text', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('10', 'IPLM', 'DATA DEMOGRAFI', 'Alamat Institusi/Sekolah/OPD/TBM/Lainnya', 'textarea', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('11', 'IPLM', 'DATA DEMOGRAFI', 'Provinsi Asal', 'text', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('12', 'IPLM', 'DATA DEMOGRAFI', 'Kabupaten/Kota Asal', 'text', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('13', 'IPLM', 'DATA DEMOGRAFI', 'Nama Lengkap Pengisi Kuesioner', 'text', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('14', 'IPLM', 'DATA DEMOGRAFI', 'Kontak Pengisi Kuesioner (Whatsapp Aktif)', 'text', NULL, '9', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('15', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Judul Koleksi Tercetak', 'number', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('16', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Eksemplar Koleksi Tercetak', 'number', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('17', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Judul Koleksi Digital', 'number', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('18', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Eksemplar Koleksi Digital', 'number', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('19', 'IPLM', 'DIMENSI KOLEKSI', 'Penambahan Jumlah Judul Koleksi Tercetak dalam 1 Tahun Terakhir', 'number', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('20', 'IPLM', 'DIMENSI KOLEKSI', 'Penambahan Jumlah Eksemplar Koleksi Tercetak dalam 1 Tahun Terakhir', 'number', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('21', 'IPLM', 'DIMENSI KOLEKSI', 'Penambahan Jumlah Judul Koleksi Digital dalam 1 Tahun Terakhir', 'number', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('22', 'IPLM', 'DIMENSI KOLEKSI', 'Penambahan Jumlah Eksemplar Koleksi Digital dalam 1 Tahun Terakhir', 'number', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('23', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Anggaran Pengembangan Koleksi dari Dana BOS ', 'number', NULL, '9', '1', '(Diisi jika Perpustakaan Sekolah, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('24', 'IPLM', 'DIMENSI KOLEKSI', 'Jumlah Anggaran Pengembangan Koleksi dari Dana Non BOS ', 'number', NULL, '10', '1', '(Diisi jika Perpustakaan Sekolah, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('25', 'IPLM', 'DIMENSI KOLEKSI', 'Total Jumlah Anggaran Pengembangan Koleksi Tercetak dan Digital dalam 1 Tahun Terakhir', 'number', NULL, '11', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('26', 'IPLM', 'DIMENSI TENAGA PERPUSTAKAAN', 'Jumlah Tenaga Perpustakaan Memiliki Kualifikasi Pendidikan Ilmu Perpustakaan (Orang)', 'number', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('27', 'IPLM', 'DIMENSI TENAGA PERPUSTAKAAN', 'Jumlah Tenaga Perpustakaan Tidak Memiliki Kualifikasi Pendidikan Ilmu Perpustakaan (Orang)', 'number', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('28', 'IPLM', 'DIMENSI TENAGA PERPUSTAKAAN', 'Jumlah Tenaga Perpustakaan yang Mengikuti PKB/Diklat dalam 1 Tahun Terakhir (Orang)', 'number', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('29', 'IPLM', 'DIMENSI TENAGA PERPUSTAKAAN', 'Jumlah Anggaran Pengembangan Keprofesian (Diklat) Tenaga dalam 1 Tahun Terakhir', 'number', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('30', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Peserta Kegiatan Literasi/Budaya Baca dalam 1 Tahun Terakhir (Orang)', 'number', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('31', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Pemustaka (Luring/Daring) dalam 1 Tahun Terakhir (Orang)', 'number', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('32', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Pemustaka yang Menggunakan Fasilitas TIK dalam 1 Tahun Terakhir (Orang)', 'number', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('33', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Judul Koleksi Tercetak Yang Dimanfaatkan Dalam 1 Tahun Terakhir', 'number', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('34', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Eksemplar Koleksi Tercetak yang Dimanfaatkan dalam 1 Tahun Terakhir', 'number', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('35', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Judul Koleksi Digital yang Dimanfaatkan dalam 1 Tahun Terakhir', 'number', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('36', 'IPLM', 'DIMENSI PELAYANAN', 'Jumlah Eksemplar Koleksi Digital yang Dimanfaatkan dalam 1 Tahun Terakhir', 'number', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('37', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Kegiatan Penguatan Budaya Baca dalam 1 Tahun Terakhir', 'number', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('38', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Kegiatan Kolaborasi/Kerja Sama dengan Pihak Eksternal dalam 1 Tahun Terakhir', 'number', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('39', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Variasi Layanan yang Tersedia (Fisik Dan Digital)', 'number', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('40', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Dokumen Kebijakan dan Prosedur Pelayanan Perpustakaan', 'number', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('41', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Peraturan Daerah Tentang Perpustakaan ', 'number', NULL, '5', '1', '(Diisi jika Perpustakaan Umum Provinsi/Kabupaten/Kota, perpustakaan lainnya isi dengan 0)', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('42', 'IPLM', 'DIMENSI PENYELENGGARAAN', 'Jumlah Anggaran Peningkatan Pelayanan dan Pengelolaan dalam 1 Tahun Terakhir', 'number', NULL, '6', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('43', 'TKM', 'DATA DEMOGRAFI', 'Usia (Tahun)', 'select', NULL, '1', '1', '', '15-28 Tahun, 29-42 Tahun, 43-56 Tahun, Lebih dari 56 Tahun');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('44', 'TKM', 'DATA DEMOGRAFI', 'Jenis Kelamin (L/P)', 'radio', NULL, '2', '1', '', 'Laki-laki, Perempuan');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('45', 'TKM', 'DATA DEMOGRAFI', 'Pendidikan Terakhir', 'text', NULL, '3', '1', 'SD Tidak Tamat,\\r\\nSD/MI,\\r\\nSMP/MTs,\\r\\nSMA/SMK/MA,\\r\\nDiploma-D1/D2/D3,\\r\\nSarjana-D4/S1,\\r\\nMagister-S2\\r\\nDoktor-S3\\r\\n', NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('46', 'TKM', 'DATA DEMOGRAFI', 'Pekerjaan', 'text', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('47', 'TKM', 'DATA DEMOGRAFI', 'Provinsi Asal', 'text', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('48', 'TKM', 'DATA DEMOGRAFI', 'Kabupaten/Kota Asal', 'text', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('50', 'TKM', 'KEBIASAAN MEMBACA', 'Tujuan utama membaca bagi saya adalah (Pengetahuan/Tugas/Kesenangan/Isi Waktu)', 'text', NULL, '2', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('51', 'TKM', 'KEBIASAAN MEMBACA', 'Berapa jarak rumah Anda ke perpustakaan terdekat?', 'text', NULL, '3', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('52', 'TKM', 'PRA MEMBACA', 'Saya membaca buku karena saya merasa senang saat membaca', 'likert', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('53', 'TKM', 'PRA MEMBACA', 'Saya membaca buku yang menarik minat saya tanpa paksaan orang lain', 'likert', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('54', 'TKM', 'PRA MEMBACA', 'Saya membaca untuk mencapai tujuan tertentu (misal: menambah pengetahuan)', 'likert', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('55', 'TKM', 'PRA MEMBACA', 'Saya membaca untuk memahami informasi penting sebelum mengambil keputusan', 'likert', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('56', 'TKM', 'PRA MEMBACA', 'Saya percaya bahwa saya dapat memahami teks walaupun topiknya baru bagi saya', 'likert', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('57', 'TKM', 'PRA MEMBACA', 'Saya merasa percaya diri saat menceritakan kembali isi bacaan', 'likert', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('58', 'TKM', 'PRA MEMBACA', 'Saya membaca karena didorong oleh orang lain', 'likert', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('59', 'TKM', 'PRA MEMBACA', 'Saya membaca untuk memenuhi tugas atau kewajiban', 'likert', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('60', 'TKM', 'PRA MEMBACA', 'Saya memiliki koleksi buku di rumah yang selalu tersedia untuk dibaca', 'likert', NULL, '9', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('61', 'TKM', 'PRA MEMBACA', 'Saya dapat mengunduh e-book dengan mudah dari internet kalau mau membaca', 'likert', NULL, '10', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('62', 'TKM', 'PRA MEMBACA', 'Dalam sebulan terakhir, berapa buku tercetak yang Anda baca?', 'text', NULL, '11', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('63', 'TKM', 'PRA MEMBACA', 'Dalam sebulan terakhir, berapa buku digital yang Anda baca?', 'text', NULL, '12', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('64', 'TKM', 'PRA MEMBACA', 'Berapa lama durasi Anda membaca buku tercetak sekali duduk?', 'text', NULL, '13', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('65', 'TKM', 'PRA MEMBACA', 'Berapa lama durasi Anda membaca buku digital sekali duduk?', 'text', NULL, '14', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('66', 'TKM', 'PRA MEMBACA', 'Saya memiliki pencahayaan yang cukup saat membaca', 'likert', NULL, '15', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('67', 'TKM', 'PRA MEMBACA', 'Saya mudah menemukan tempat yang nyaman untuk membaca', 'likert', NULL, '16', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('68', 'TKM', 'PRA MEMBACA', 'Saya sering melihat anggota keluarga membaca sehingga saya terinspirasi', 'likert', NULL, '17', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('69', 'TKM', 'PRA MEMBACA', 'Saya dan keluarga sering membaca bersama', 'likert', NULL, '18', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('70', 'TKM', 'PRA MEMBACA', 'Tokoh publik (Influencer, Duta Baca, dll) merekomendasikan bacaan yang sesuai minat saya', 'likert', NULL, '19', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('71', 'TKM', 'PRA MEMBACA', 'Tokoh publik memberikan dorongan dan arahan agar saya membaca', 'likert', NULL, '20', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('72', 'TKM', 'PRA MEMBACA', 'Saya sering berdiskusi dengan teman tentang buku yang kami baca', 'likert', NULL, '21', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('74', 'TKM', 'PRA MEMBACA', 'Saya mengikuti kegiatan literasi (misal: klub buku, workshop)', 'likert', NULL, '22', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('75', 'TKM', 'PRA MEMBACA', 'Saya sering berpartisipasi dalam tantangan membaca (reading challenge)', 'likert', NULL, '23', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('76', 'TKM', 'PRA MEMBACA', 'Orang di lingkungan saya menghargai kebiasaan membaca', 'likert', NULL, '24', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('77', 'TKM', 'PRA MEMBACA', 'Media lokal mempromosikan kegiatan membaca', 'likert', NULL, '25', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('78', 'TKM', 'PRA MEMBACA', 'Acara budaya di tempat saya sering melibatkan kegiatan membaca', 'likert', NULL, '26', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('79', 'TKM', 'PRA MEMBACA', 'Saya menjadi anggota/pengurus organisasi pembaca/literasi', 'likert', NULL, '27', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('80', 'TKM', 'PRA MEMBACA', 'Saya membantu penyelenggaraan acara literasi di lingkungan saya', 'likert', NULL, '28', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('81', 'TKM', 'PRA MEMBACA', 'Saya menggunakan perpustakaan digital untuk membaca materi budaya', 'likert', NULL, '29', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('82', 'TKM', 'PRA MEMBACA', 'Saya mengikuti tur atau acara budaya yang melibatkan kegiatan literasi', 'likert', NULL, '30', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('83', 'TKM', 'SAAT MEMBACA', 'Saya dapat mempertahankan fokus saat membaca hingga selesai', 'likert', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('84', 'TKM', 'SAAT MEMBACA', 'Saya memilih tempat tenang untuk membaca', 'likert', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('85', 'TKM', 'SAAT MEMBACA', 'Saya membuat catatan atau menandai bagian penting dalam teks', 'likert', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('86', 'TKM', 'SAAT MEMBACA', 'Saya membuat ringkasan setelah selesai membaca', 'likert', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('87', 'TKM', 'SAAT MEMBACA', 'Saya sering bertanya pada diri sendiri untuk memeriksa pemahaman', 'likert', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('88', 'TKM', 'SAAT MEMBACA', 'Saya berdiskusi tentang teks yang saya baca dengan orang lain setelah membaca', 'likert', NULL, '6', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('89', 'TKM', 'SAAT MEMBACA', 'Saya dapat menilai diri sendiri tingkat pemahaman saya tentang isi bacaan', 'likert', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('90', 'TKM', 'SAAT MEMBACA', 'Saya meminta bantuan orang lain jika menemukan bagian sulit', 'likert', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('91', 'TKM', 'SAAT MEMBACA', 'Saya tertarik membaca opini/pendapat orang lain tentang isi sebuah buku', 'likert', NULL, '9', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('92', 'TKM', 'SAAT MEMBACA', 'Saya tertarik untuk bertukar pikiran terkait buku yang saya baca dengan orang lain', 'likert', NULL, '10', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('93', 'TKM', 'SAAT MEMBACA', 'Saya suka membaca bersama teman atau kelompok', 'likert', NULL, '11', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('94', 'TKM', 'SAAT MEMBACA', 'Saya merencanakan waktu membaca bersama', 'likert', NULL, '12', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('95', 'TKM', 'SAAT MEMBACA', 'Saya membahas arti kata sulit dalam teks bersama teman', 'likert', NULL, '13', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('96', 'TKM', 'SAAT MEMBACA', 'Saya mencatat dan mencari arti kata sulit dalam kamus/ensiklopedia', 'likert', NULL, '14', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('97', 'TKM', 'SAAT MEMBACA', 'Saya menggunakan bacaan untuk memecahkan masalah sehari-hari', 'likert', NULL, '15', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('98', 'TKM', 'SAAT MEMBACA', 'Saya menulis laporan berdasarkan hasil baca', 'likert', NULL, '16', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('99', 'TKM', 'PASCA MEMBACA', 'Setelah membaca, saya merasa pengetahuan saya bertambah', 'likert', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('100', 'TKM', 'PASCA MEMBACA', 'Saya merasa menjadi lebih senang setelah saya membaca', 'likert', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('101', 'TKM', 'PASCA MEMBACA', 'Membaca membantu saya berkomunikasi lebih baik dengan orang lain', 'likert', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('102', 'TKM', 'PASCA MEMBACA', 'Saya membaca untuk memahami perspektif orang lain', 'likert', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('103', 'TKM', 'PASCA MEMBACA', 'Membaca membuat saya lebih memahami perasaan diri sendiri atau tokoh', 'likert', NULL, '5', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('104', 'TKM', 'PASCA MEMBACA', 'Saya termotivasi setelah membaca cerita inspiratif', 'likert', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('105', 'TKM', 'PASCA MEMBACA', 'Saya percaya mampu meningkatkan kemampuan membaca', 'likert', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('106', 'TKM', 'PASCA MEMBACA', 'Saya percaya diri saat membaca topik baru', 'likert', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('107', 'TKM', 'PASCA MEMBACA', 'Saya menganggap membaca sebagai kegiatan bernilai dan bermanfaat', 'likert', NULL, '9', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('108', 'TKM', 'PASCA MEMBACA', 'Saya bersedia meluangkan waktu membaca setiap hari', 'likert', NULL, '10', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('109', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya datang ke perpustakaan untuk mencari informasi terpercaya', 'likert', NULL, '1', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('110', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya mencari bahan bacaan di perpustakaan untuk minat pribadi', 'likert', NULL, '2', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('111', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya datang ke perpustakaan setiap bulan selama 3 bulan terakhir', 'likert', NULL, '3', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('112', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya menggunakan fasilitas perpustakaan lebih dari satu kali dalam 3 bulan terakhir', 'likert', NULL, '4', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('113', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Pustakawan membantu saya menemukan bahan bacaan yang sesuai', 'likert', NULL, '5', '1', '', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('114', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya merasa nyaman bertanya kepada pustakawan', 'likert', NULL, '6', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('115', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya melihat katalog online untuk menemukan materi', 'likert', NULL, '7', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('116', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya meminjam atau mengunduh buku dari koleksi perpustakaan', 'likert', NULL, '8', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('117', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya membagikan informasi perpustakaan kepada teman/rekan kerja', 'likert', NULL, '9', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('118', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya mengintegrasikan bacaan perpustakaan dalam presentasi', 'likert', NULL, '10', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('119', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya sering merekomendasikan buku perpustakaan kepada orang lain', 'likert', NULL, '11', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('120', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya menulis ulasan singkat tentang buku yang saya baca', 'likert', NULL, '12', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('121', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya puas dengan kualitas layanan perpustakaan yang saya gunakan', 'likert', NULL, '13', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('122', 'TKM', 'INTERAKSI DENGAN PERPUSTAKAAN', 'Saya akan merekomendasikan perpustakaan ini kepada orang lain', 'likert', NULL, '14', '1', NULL, NULL);
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('123', 'TKM', 'DATA DEMOGRAFI', 'Kontak pengisi kuisioner', 'number', NULL, '7', '1', 'no whatsapp aktif', '');
INSERT INTO `master_pertanyaan` (`id`, `jenis_kuesioner`, `kategori_bagian`, `teks_pertanyaan`, `tipe_input`, `opsi_jawaban`, `urutan`, `is_active`, `keterangan`, `pilihan_opsi`) VALUES ('147', 'TKM', 'KEBIASAAN MEMBACA', 'Saya memiliki/menyediakan waktu khusus untuk membaca', 'radio', NULL, '1', '1', '', 'Ya,Tidak');


-- Structure for password_reset_email_logs
DROP TABLE IF EXISTS `password_reset_email_logs`;
CREATE TABLE `password_reset_email_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `error_message` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token_hash` varchar(255) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for password_reset_email_logs
INSERT INTO `password_reset_email_logs` (`id`, `email`, `status`, `error_message`, `created_at`, `token_hash`, `expires_at`) VALUES ('37', 'kevinmuammargathfan@gmail.com', 'sent', NULL, '2026-04-29 19:54:27', '76a514b7329e669ca1b0bc63ee2aaf91b932167ba477e760296c864881e26421', '2026-04-29 20:54:26');


-- Structure for password_reset_logs
DROP TABLE IF EXISTS `password_reset_logs`;
CREATE TABLE `password_reset_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for password_reset_logs
INSERT INTO `password_reset_logs` (`id`, `user_id`, `ip_address`, `created_at`) VALUES ('4', '9', '::1', '2026-04-29 19:56:47');


-- Structure for password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for pengaduan
DROP TABLE IF EXISTS `pengaduan`;
CREATE TABLE `pengaduan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL,
  `pesan` text,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for report_periods
DROP TABLE IF EXISTS `report_periods`;
CREATE TABLE `report_periods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for report_verifications
DROP TABLE IF EXISTS `report_verifications`;
CREATE TABLE `report_verifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_id` int NOT NULL,
  `catatan` text,
  `verified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `report_verifications_report_id_fkey` (`report_id`),
  CONSTRAINT `report_verifications_report_id_fkey` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for reports
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `library_id` int NOT NULL,
  `period_id` int NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reports_library_id_period_id_jenis_key` (`library_id`,`period_id`,`jenis`),
  KEY `reports_period_id_fkey` (`period_id`),
  CONSTRAINT `reports_period_id_fkey` FOREIGN KEY (`period_id`) REFERENCES `report_periods` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for reports_iplm
DROP TABLE IF EXISTS `reports_iplm`;
CREATE TABLE `reports_iplm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_id` int NOT NULL,
  `jumlah_buku` int DEFAULT NULL,
  `jumlah_pengunjung` int DEFAULT NULL,
  `jumlah_kegiatan_literasi` int DEFAULT NULL,
  `jumlah_tenaga_perpustakaan` int DEFAULT NULL,
  `skor_total` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_iplm_report_id_fkey` (`report_id`),
  CONSTRAINT `reports_iplm_report_id_fkey` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for reports_tkm
DROP TABLE IF EXISTS `reports_tkm`;
CREATE TABLE `reports_tkm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_id` int NOT NULL,
  `jumlah_pembaca` int DEFAULT NULL,
  `jumlah_buku_dibaca` int DEFAULT NULL,
  `rata_waktu_membaca` int DEFAULT NULL,
  `skor_total` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_tkm_report_id_fkey` (`report_id`),
  CONSTRAINT `reports_tkm_report_id_fkey` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- Structure for settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_autofill_jenis_id', '1');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_autofill_nama_id', '9');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_autofill_subjenis_id', '2');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_end', '2026-02-02T21:26');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_kontak_pertanyaan_id', '14');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_mode', 'manual');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_numbering_style', 'roman');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('iplm_start', '2026-02-02T21:25');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('status_iplm', 'buka');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('status_tkm', 'buka');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('tkm_end', '2026-02-10 18:56');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('tkm_mode', 'manual');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('tkm_numbering_style', 'roman');
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES ('tkm_start', '2026-02-10 18:55');


-- Structure for trans_detail
DROP TABLE IF EXISTS `trans_detail`;
CREATE TABLE `trans_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `header_id` int NOT NULL,
  `pertanyaan_id` int NOT NULL,
  `jawaban` text,
  PRIMARY KEY (`id`),
  KEY `trans_detail_header_id_fkey` (`header_id`),
  KEY `trans_detail_pertanyaan_id_fkey` (`pertanyaan_id`),
  CONSTRAINT `trans_detail_header_id_fkey` FOREIGN KEY (`header_id`) REFERENCES `trans_header` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trans_detail_pertanyaan_id_fkey` FOREIGN KEY (`pertanyaan_id`) REFERENCES `master_pertanyaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=694 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for trans_detail
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('448', '9', '43', '29-42 Tahun');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('449', '9', '44', 'Laki-laki');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('450', '9', '45', 'ijda');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('451', '9', '46', 'iio');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('452', '9', '47', 'uh');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('453', '9', '48', 'ih');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('454', '9', '123', '98098');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('456', '9', '50', 'hui');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('457', '9', '51', 'h');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('458', '9', '52', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('459', '9', '53', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('460', '9', '54', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('461', '9', '55', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('462', '9', '56', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('463', '9', '57', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('464', '9', '58', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('465', '9', '59', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('466', '9', '60', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('467', '9', '61', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('468', '9', '62', ' 89u');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('469', '9', '63', '8u');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('470', '9', '64', '87');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('471', '9', '65', '807');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('472', '9', '66', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('473', '9', '67', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('474', '9', '68', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('475', '9', '69', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('476', '9', '70', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('477', '9', '71', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('478', '9', '72', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('480', '9', '74', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('481', '9', '75', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('482', '9', '76', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('483', '9', '77', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('484', '9', '78', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('485', '9', '79', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('486', '9', '80', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('487', '9', '81', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('488', '9', '82', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('489', '9', '83', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('490', '9', '84', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('491', '9', '85', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('492', '9', '86', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('493', '9', '87', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('494', '9', '88', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('495', '9', '89', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('496', '9', '90', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('497', '9', '91', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('498', '9', '92', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('499', '9', '93', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('500', '9', '94', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('501', '9', '95', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('502', '9', '96', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('503', '9', '97', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('504', '9', '98', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('505', '9', '99', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('506', '9', '100', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('507', '9', '101', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('508', '9', '102', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('509', '9', '103', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('510', '9', '104', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('511', '9', '105', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('512', '9', '106', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('513', '9', '107', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('514', '9', '108', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('515', '9', '109', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('516', '9', '110', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('517', '9', '111', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('518', '9', '112', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('519', '9', '113', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('520', '9', '114', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('521', '9', '115', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('522', '9', '116', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('523', '9', '117', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('524', '9', '118', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('525', '9', '119', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('526', '9', '120', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('527', '9', '121', '1');
INSERT INTO `trans_detail` (`id`, `header_id`, `pertanyaan_id`, `jawaban`) VALUES ('528', '9', '122', '1');


-- Structure for trans_header
DROP TABLE IF EXISTS `trans_header`;
CREATE TABLE `trans_header` (
  `id` int NOT NULL AUTO_INCREMENT,
  `library_id` int DEFAULT NULL,
  `jenis_kuesioner` varchar(10) NOT NULL,
  `periode_bulan` varchar(2) DEFAULT NULL,
  `periode_tahun` varchar(4) DEFAULT NULL,
  `tanggal_isi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for trans_header
INSERT INTO `trans_header` (`id`, `library_id`, `jenis_kuesioner`, `periode_bulan`, `periode_tahun`, `tanggal_isi`) VALUES ('9', NULL, 'TKM', '02', '2026', '2026-02-09 11:11:27');


-- Structure for users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('super','admin') NOT NULL DEFAULT 'admin',
  `password` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_key` (`email`),
  UNIQUE KEY `users_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data for users
INSERT INTO `users` (`id`, `nama`, `email`, `role`, `password`, `created_at`, `is_primary`) VALUES ('11', 'roman', 'roman@roman.com', 'admin', '$2y$10$ZQ.gPQSfiZjcQVzf1u9PwOn1xM/51oRHBMzzAN8S2aGhW7SpXkn02', '2026-04-29 19:53:03', '0');
INSERT INTO `users` (`id`, `nama`, `email`, `role`, `password`, `created_at`, `is_primary`) VALUES ('13', 'kevin', 'kevinmuammargathfan@gmail.com', 'super', '$2y$10$85JqwFdBLxW9Um.s2R2hye0rjuvzejWheAWM8GIKw5u8ef141Ebxq', '2026-04-29 20:00:05', '1');

SET FOREIGN_KEY_CHECKS=1;
