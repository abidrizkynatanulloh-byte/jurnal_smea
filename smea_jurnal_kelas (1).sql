-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2026 at 06:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smea_jurnal_kelas`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_siswa`
--

CREATE TABLE `absensi_siswa` (
  `id_absensi` bigint NOT NULL,
  `id_pertemuan` bigint NOT NULL,
  `nis_siswa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Hadir','Sakit','Izin','Alpa','Dispensasi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `dicatat_oleh` int DEFAULT NULL,
  `dicatat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_siswa`
--

INSERT INTO `absensi_siswa` (`id_absensi`, `id_pertemuan`, `nis_siswa`, `status`, `keterangan`, `dicatat_oleh`, `dicatat_pada`, `diperbarui_pada`) VALUES
(1, 1, '000001', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(2, 1, '000002', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(3, 1, '000003', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(4, 1, '000004', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(5, 1, '000005', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(6, 1, '000006', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(7, 1, '000007', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(8, 1, '000008', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(9, 1, '000009', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(10, 1, '000010', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(11, 1, '000011', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(12, 1, '000012', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(13, 1, '000013', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(14, 1, '000014', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(15, 1, '000015', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(16, 1, '000016', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(17, 1, '000017', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(18, 1, '000018', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(19, 1, '000019', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(20, 1, '000020', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(21, 1, '000021', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(22, 1, '000022', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(23, 1, '000023', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(24, 1, '000024', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(25, 1, '000025', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(26, 1, '000026', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(27, 1, '000027', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(28, 1, '000028', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(29, 1, '000029', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(30, 1, '000030', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(31, 1, '000031', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(32, 2, '000001', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(33, 2, '000002', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(34, 2, '000003', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(35, 2, '000004', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(36, 2, '000005', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(37, 2, '000006', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(38, 2, '000007', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(39, 2, '000008', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(40, 2, '000009', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(41, 2, '000010', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(42, 2, '000011', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(43, 2, '000012', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(44, 2, '000013', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(45, 2, '000014', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(46, 2, '000015', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(47, 2, '000016', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(48, 2, '000017', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(49, 2, '000018', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(50, 2, '000019', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(51, 2, '000020', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(52, 2, '000021', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(53, 2, '000022', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(54, 2, '000023', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(55, 2, '000024', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(56, 2, '000025', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(57, 2, '000026', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(58, 2, '000027', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(59, 2, '000028', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(60, 2, '000029', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(61, 2, '000030', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(62, 2, '000031', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(63, 3, '000001', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(64, 3, '000002', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(65, 3, '000003', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(66, 3, '000004', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(67, 3, '000005', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(68, 3, '000006', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(69, 3, '000007', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(70, 3, '000008', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(71, 3, '000009', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(72, 3, '000010', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(73, 3, '000011', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(74, 3, '000012', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(75, 3, '000013', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(76, 3, '000014', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(77, 3, '000015', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(78, 3, '000016', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(79, 3, '000017', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(80, 3, '000018', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(81, 3, '000019', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(82, 3, '000020', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(83, 3, '000021', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(84, 3, '000022', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(85, 3, '000023', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(86, 3, '000024', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(87, 3, '000025', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(88, 3, '000026', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(89, 3, '000027', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(90, 3, '000028', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(91, 3, '000029', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(92, 3, '000030', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(93, 3, '000031', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(94, 4, '000001', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(95, 4, '000002', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(96, 4, '000003', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(97, 4, '000004', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(98, 4, '000005', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(99, 4, '000006', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(100, 4, '000007', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(101, 4, '000008', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(102, 4, '000009', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(103, 4, '000010', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(104, 4, '000011', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(105, 4, '000012', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(106, 4, '000013', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(107, 4, '000014', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(108, 4, '000015', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(109, 4, '000016', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(110, 4, '000017', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(111, 4, '000018', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(112, 4, '000019', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(113, 4, '000020', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(114, 4, '000021', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(115, 4, '000022', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(116, 4, '000023', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(117, 4, '000024', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(118, 4, '000025', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(119, 4, '000026', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(120, 4, '000027', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(121, 4, '000028', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(122, 4, '000029', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(123, 4, '000030', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11'),
(124, 4, '000031', 'Hadir', NULL, NULL, '2026-08-24 05:34:11', '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_alerts`
--

CREATE TABLE `attendance_alerts` (
  `id_alert` bigint NOT NULL,
  `nis_siswa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pertemuan` bigint DEFAULT NULL,
  `jenis_alert` enum('KETIDAKHADIRAN_MENDADAK','BELUM_ABSEN','TERLAMBAT_KEMBALI','KEBERADAAN_TIDAK_DIketahui') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` enum('Rendah','Sedang','Tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sedang',
  `status` enum('Terbuka','Diverifikasi','Selesai','Diabaikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Terbuka',
  `terdeteksi_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `diverifikasi_oleh` int DEFAULT NULL,
  `diverifikasi_pada` datetime DEFAULT NULL,
  `hasil_verifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id_log` bigint NOT NULL,
  `id_pengguna` int DEFAULT NULL COMMENT 'NULL jika aksi otomatis sistem',
  `aksi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel_terkait` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_terkait` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `waktu` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Append-only - jangan pernah UPDATE/DELETE dari sisi aplikasi';

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id_log`, `id_pengguna`, `aksi`, `tabel_terkait`, `id_terkait`, `keterangan`, `waktu`) VALUES
(1, 4, 'Menyetujui izin siswa', 'izin_siswa', 1, 'Disetujui oleh Waka Kesiswaan piket hari itu', '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int NOT NULL,
  `nip` char(18) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('tu','guru','piket') NOT NULL DEFAULT 'guru',
  `no_hp` varchar(15) DEFAULT NULL,
  `kode_mapel` varchar(10) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nip`, `nama_guru`, `password`, `role`, `no_hp`, `kode_mapel`, `deleted_at`) VALUES
(1, '9900001', 'Muto\'atul Khosi\'ah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(2, '9900002', 'Ilham Sungeidi, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PJOK', NULL),
(3, '9900003', 'Yani, S.Pd.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(4, '9900004', 'Yustin Febrini, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'SEJ', NULL),
(5, '9900005', 'Anang Prasetyo, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'SBUD', NULL),
(6, '9900006', 'Khuriyatul Kamila, S.Si', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'IPAS', NULL),
(7, '9900007', 'Rifkotin Na\'imah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DTKI', NULL),
(8, '9900008', 'Endang Ary Handayani, S.T., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KKA', NULL),
(9, '9900009', 'Sri Kusumastuti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DTKI', NULL),
(10, '9900010', 'Muashofah, M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(11, '9900011', 'Lutfia Marsalina, S.Pd.I, M.Pd.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(12, '9900012', 'Arvia Rienetasary, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(13, '9900013', 'Wiwik Yuniarsih, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PPKN', NULL),
(14, '9900014', 'Yuni Jiastuti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(15, '9900015', 'Fajar Luthfianto, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'SEJ', NULL),
(16, '9900016', 'Ista Nofasari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'IPAS', NULL),
(17, '9900017', 'Fitria Renytasari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(18, '9900018', 'Mufatiroh, S.Ag', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(19, '9900019', 'Winartin, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(20, '9900020', 'Widodo, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(21, '9900021', 'Zainul Arifin, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PJOK', NULL),
(22, '9900022', 'Indriati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'IPAS', NULL),
(23, '9900023', 'Rizki Putri Wulandari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BJAW', NULL),
(24, '9900024', 'Elysa Yuli Nur\'aini, S.Si', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(25, '9900025', 'Kurnila Putri Islamawati, S.Kom', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'INF', NULL),
(26, '9900026', 'Badrus Sulaiman, S.Pd.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KRPL', NULL),
(27, '9900027', 'Ruly Dwi Setyaningrum, S.Kom', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DPPLG', NULL),
(28, '9900028', 'Elyana Frisca Monica, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KKA', NULL),
(29, '9900029', 'Abdul Rohman, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PPKN', NULL),
(30, '9900030', 'Umi Kulsum, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(31, '9900031', 'Fitri Amaliyah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'IPAS', NULL),
(32, '9900032', 'Fajar Wahyu Pratiwi, S.S', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(33, '9900033', 'Laili Ermawati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(34, '9900034', 'Isti Mufadah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(35, '9900035', 'Listyana Hartati, S.Kom., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KKA', NULL),
(36, '9900036', 'Siswanti Purwaningsih, S.T., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'INF', NULL),
(37, '9900037', 'Muhammad Fajar Assidiqi, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BJAW', NULL),
(38, '9900038', 'Nishfu Laili, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(39, '9900039', 'Basuki Sarjono, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(40, '9900040', 'Sri Rahayu, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(41, '9900041', 'Siti Munawaroh, S.Kom.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KKA', NULL),
(42, '9900042', 'Sinta Lestari, S.Pd.I', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(43, '9900043', 'Pipit Ambarwati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAKL', NULL),
(44, '9900044', 'Ratih Dian Irawati, SE', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DPM', NULL),
(45, '9900045', 'Arif Setyobudi, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(46, '9900046', 'Dian Mawarti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(47, '9900047', 'Anisa Kusumawati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(48, '9900048', 'Angga Widhy Wirawan, S.Pd.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'SBUD', NULL),
(49, '9900049', 'Agus Fahruddy, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PJOK', NULL),
(50, '9900050', 'Eko Saputro, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(51, '9900051', 'Retno Widyastuti, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DPM', NULL),
(52, '9900052', 'Erna Qoriah, S.E.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(53, '9900053', 'Dwi Rini Manfaati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(54, '9900054', 'Fitria Diah Ayu Hartati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(55, '9900055', 'Bella Prakoso, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PJOK', NULL),
(56, '9900056', 'Sri Subekti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'IPAS', NULL),
(57, '9900057', 'Siti Khoiriyah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(58, '9900058', 'Niken Hari Isnaini, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(59, '9900059', 'Purwati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(60, '9900060', 'Dwi Kuswanto, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DMPLB', NULL),
(61, '9900061', 'Komariyah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(62, '9900062', 'Rulik Indrawati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(63, '9900063', 'Veronica Damay Pristiani, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(64, '9900064', 'Tutut Sriatin, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MPMP', NULL),
(65, '9900065', 'Kurnila Putri Islamawati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KRPL', NULL),
(66, '9900066', 'Peni Wulandari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DMPLB', NULL),
(67, '9900067', 'Ary Sunaryo, S.T., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'INF', NULL),
(68, '9900068', 'Lilik Suratmi, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DMPLB', NULL),
(69, '9900069', 'Nur Eko Wahyudi, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(70, '9900070', 'Rindang Rejeki, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DMPLB', NULL),
(71, '9900071', 'Siti Maisaroh, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(72, '9900072', 'Alfinu Farikh Abdillah, S.Pd.I', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(73, '9900073', 'Shinta Indyar Shanty Susanto, S.Kom', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'INF', NULL),
(74, '9900074', 'Endang Safitri, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(75, '9900075', 'Atih Wilupi, S.E., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KAK', NULL),
(76, '9900076', 'Yuli Ratnasari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MPAK', NULL),
(77, '9900077', 'Agustina Mardika Rini, S.Pd.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAKL', NULL),
(78, '9900078', 'Indayah, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KAK', NULL),
(79, '9900079', 'Andri Retno Yuli Astuti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(80, '9900080', 'Dwi Nova Setyandari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(81, '9900081', 'Astra Bella Flamboyan, S.Psi', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(82, '9900082', 'Titin Sukmasari, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAKL', NULL),
(83, '9900083', 'Setiyo Winarko, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAKL', NULL),
(84, '9900084', 'Septiani, S.Pd.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAKL', NULL),
(85, '9900085', 'Risqi Nur Imama, S.Tr.Par', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DULP', NULL),
(86, '9900086', 'Dra. Hanik Pangestuti', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'PAIBP', NULL),
(87, '9900087', 'Danang Anjar Hymawanto, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KDKV', NULL),
(88, '9900088', 'Agus Pramono, S.Sn', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DDKV', NULL),
(89, '9900089', 'Khoyrotun Hisani, S.Sn', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BJAW', NULL),
(90, '9900090', 'Erna Rinawati, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BIND', NULL),
(91, '9900091', 'Endik Kuswantoro, S.Kom., M.T', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KKA', NULL),
(92, '9900092', 'Ajeng Okvitasari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MTK', NULL),
(93, '9900093', 'Benny Mamora, S.Kom', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DBP', NULL),
(94, '9900094', 'Winarsih, S.Pd, M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(95, '9900095', 'Sa\'ad Wazis Hiedayat, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KPSPT', NULL),
(96, '9900096', 'Agus Muharyanto, M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BING', NULL),
(97, '9900097', 'Tuhu Eries Kudori, S.Sn', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'INF', NULL),
(98, '9900098', 'Rika Okta Maulida, S.Ds.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAN', NULL),
(99, '9900099', 'Dhuana Putri Puspitasary, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'DAN', NULL),
(100, '9900100', 'Mega Mahardika, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KMP', NULL),
(101, '9900101', 'Baskoro, S.Si', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(102, '9900102', 'Ayu Puspitorini, ST', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KTKI', NULL),
(103, '9900103', 'Diana Hartanti, S.T., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KTKI', NULL),
(104, '9900104', 'Hendro Suwignyo, ST', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MPRPL', NULL),
(105, '9900105', 'Sulistyowati, SS', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BJPN', NULL),
(106, '9900106', 'Niken Dewi Hastika, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KBD', NULL),
(107, '9900107', 'Luluk Munfarida, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KBD', NULL),
(108, '9900108', 'Listyana Hartati, S.Kom.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KTKJ', NULL),
(109, '9900109', 'Andri Krisdianto, SE.,M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'MPBD', NULL),
(110, '9900110', 'Nurul Azizah, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KBD', NULL),
(111, '9900111', 'Nur Eko Wahyuningsih, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(112, '9900112', 'Andri Krisdianto, SE., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KBD', NULL),
(113, '9900113', 'Agung Yulianto, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KBD', NULL),
(114, '9900114', 'Dra. Anik Indriani', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'SEJ', NULL),
(115, '9900115', 'Martiin, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KMP', NULL),
(116, '9900116', 'Dra. Susakti Yuharini', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(117, '9900117', 'Veronica Damay Rulitasari, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(118, '9900118', 'Sunarti, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KMP', NULL),
(119, '9900119', 'Titik Samsistini, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KMP', NULL),
(120, '9900120', 'Kasmi, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KAK', NULL),
(121, '9900121', 'Dyah Esti Rahayu, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KAK', NULL),
(122, '9900122', 'Ninik Sriwidayati, S.Pd., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(123, '9900123', 'Nur Nastutisari, S.ST.Par.', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(124, '9900124', 'Niken Hari Pratiwi, S.Psi., M.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'BK', NULL),
(125, '9900125', 'Istiana Suhartati, S.T', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KDKV', NULL),
(126, '9900126', 'Mas\'an Widodo, S.Pd. M.T', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(127, '9900127', 'Joko Priyanto, S.Kom', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KPSPT', NULL),
(128, '9900128', 'Siti Umiharsih, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(129, '9900129', 'Andika Christian Sasmita, S.ST', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KIK', NULL),
(130, '9900130', 'Erwan Septiyono, S.Pd', '$2y$12$9cp/z3RySxrE7k31TLcbj..n.xXTOdvKhV./02coct/f806Z.pwMq', 'guru', NULL, 'KAN', NULL),
(131, '111', 'Admin Tata Usaha', '$2y$12$aQY8Yht4zodqYc6rrLhpuug4hVkZ6tOGN4dy5eiW9hdLgJTr6Hlku', 'tu', NULL, NULL, NULL),
(132, '222', 'Petugas Piket', '$2y$12$KKKtgzUv2cBtPx.jCjwZXeJ./XntZBZcUwUxCmYuAh/mARli9r8qe', 'piket', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `izin_guru`
--

CREATE TABLE `izin_guru` (
  `id_izin_guru` int NOT NULL,
  `id_guru` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `kategori` enum('Sakit','Izin','Dinas','Tugas Luar','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti_pendukung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'path/nama file surat keterangan, jika ada',
  `kelas_terdampak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu Kurikulum','Ditolak Kurikulum','Menunggu SDM','Ditolak SDM','Menunggu Kepala Sekolah','Ditolak Kepala Sekolah','Disetujui') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Kurikulum',
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `izin_guru`
--

INSERT INTO `izin_guru` (`id_izin_guru`, `id_guru`, `tanggal_mulai`, `tanggal_selesai`, `kategori`, `alasan`, `bukti_pendukung`, `kelas_terdampak`, `status`, `dibuat_pada`) VALUES
(1, 63, '2026-08-24', '2026-08-24', 'Sakit', 'Demam tinggi disertai surat keterangan dokter', NULL, 'Seluruh jadwal mengajar tanggal 24 Agustus 2026', 'Disetujui', '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `izin_guru_persetujuan`
--

CREATE TABLE `izin_guru_persetujuan` (
  `id_persetujuan` int NOT NULL,
  `id_izin_guru` int NOT NULL,
  `tahap` enum('Kurikulum','SDM','Kepala Sekolah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_penyetuju` int NOT NULL COMMENT 'FK guru pemegang kewenangan tahap tsb',
  `keputusan` enum('Disetujui','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `diputuskan_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log tiap tahap; kolom status di izin_guru adalah ringkasan cepat dari log ini';

--
-- Dumping data for table `izin_guru_persetujuan`
--

INSERT INTO `izin_guru_persetujuan` (`id_persetujuan`, `id_izin_guru`, `tahap`, `id_penyetuju`, `keputusan`, `alasan_penolakan`, `diputuskan_pada`) VALUES
(1, 1, 'Kurikulum', 36, 'Disetujui', NULL, '2026-08-23 07:10:00'),
(2, 1, 'SDM', 127, 'Disetujui', NULL, '2026-08-23 09:30:00'),
(3, 1, 'Kepala Sekolah', 116, 'Disetujui', NULL, '2026-08-23 16:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `izin_siswa`
--

CREATE TABLE `izin_siswa` (
  `id_izin` int NOT NULL,
  `nis_siswa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru_piket` int NOT NULL COMMENT 'Guru piket yang mencatat pengajuan',
  `kategori` enum('Keperluan Keluarga','Keperluan Kesehatan','Kegiatan Sekolah','Dispensasi','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `perkiraan_kembali` datetime DEFAULT NULL,
  `status` enum('Menunggu Persetujuan','Disetujui','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Persetujuan',
  `id_penyetuju` int DEFAULT NULL COMMENT 'FK guru - Waka kesiswaan piket hari itu',
  `alasan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `diputuskan_pada` timestamp NULL DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `izin_siswa`
--

INSERT INTO `izin_siswa` (`id_izin`, `nis_siswa`, `id_guru_piket`, `kategori`, `alasan`, `waktu_mulai`, `perkiraan_kembali`, `status`, `id_penyetuju`, `alasan_penolakan`, `diputuskan_pada`, `dibuat_pada`) VALUES
(1, '000001', 3, 'Keperluan Keluarga', 'Ada urusan keluarga mendadak', '2026-08-24 10:30:00', NULL, 'Disetujui', 8, NULL, '2026-08-24 10:35:00', '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `id_kelas` int NOT NULL,
  `id_guru` int NOT NULL,
  `id_ruangan` int NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_mulai` int NOT NULL,
  `jam_selesai` int NOT NULL,
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_kelas`, `id_guru`, `id_ruangan`, `hari`, `jam_mulai`, `jam_selesai`, `kode_mapel`) VALUES
(1, 1, 1, 1, 'Senin', 2, 3, 'BING'),
(2, 1, 2, 2, 'Senin', 4, 6, 'PJOK'),
(3, 1, 3, 1, 'Senin', 7, 8, 'BIND'),
(4, 1, 4, 1, 'Senin', 9, 10, 'BJAW'),
(5, 1, 5, 1, 'Selasa', 1, 2, 'SBUD'),
(6, 1, 3, 1, 'Selasa', 3, 4, 'BIND'),
(7, 1, 6, 1, 'Selasa', 5, 7, 'IPAS'),
(8, 1, 7, 1, 'Selasa', 8, 10, 'DTKI'),
(9, 1, 8, 3, 'Rabu', 1, 2, 'KKA'),
(10, 1, 9, 1, 'Rabu', 3, 5, 'DTKI'),
(11, 1, 10, 1, 'Rabu', 6, 8, 'PAIBP'),
(12, 1, 11, 4, 'Rabu', 9, 10, 'INF'),
(13, 1, 12, 1, 'Kamis', 1, 2, 'MTK'),
(14, 1, 13, 1, 'Kamis', 3, 4, 'PPKN'),
(15, 1, 6, 1, 'Kamis', 5, 7, 'IPAS'),
(16, 1, 9, 1, 'Kamis', 8, 10, 'DTKI'),
(17, 1, 7, 1, 'Jumat', 2, 4, 'DTKI'),
(18, 1, 14, 1, 'Jumat', 5, 5, 'BK'),
(19, 1, 12, 1, 'Jumat', 6, 7, 'MTK'),
(20, 1, 11, 4, 'Jumat', 8, 9, 'INF'),
(21, 1, 15, 1, 'Jumat', 10, 11, 'SEJ'),
(22, 1, 1, 1, 'Jumat', 12, 13, 'BING'),
(23, 2, 3, 5, 'Senin', 2, 3, 'BIND'),
(24, 2, 12, 5, 'Senin', 4, 5, 'MTK'),
(25, 2, 13, 5, 'Senin', 6, 7, 'PPKN'),
(26, 2, 9, 5, 'Senin', 8, 10, 'DTKI'),
(27, 2, 14, 5, 'Selasa', 1, 1, 'BK'),
(28, 2, 7, 5, 'Selasa', 2, 4, 'DTKI'),
(29, 2, 11, 4, 'Selasa', 5, 6, 'INF'),
(30, 2, 16, 5, 'Selasa', 7, 8, 'SEJ'),
(31, 2, 8, 3, 'Selasa', 9, 10, 'KKA'),
(32, 2, 12, 5, 'Rabu', 1, 2, 'MTK'),
(33, 2, 5, 5, 'Rabu', 3, 4, 'SBUD'),
(34, 2, 17, 5, 'Rabu', 5, 6, 'BING'),
(35, 2, 11, 4, 'Rabu', 7, 8, 'INF'),
(36, 2, 4, 5, 'Rabu', 9, 10, 'BJAW'),
(37, 2, 7, 5, 'Kamis', 1, 3, 'DTKI'),
(38, 2, 17, 5, 'Kamis', 4, 5, 'BING'),
(39, 2, 3, 5, 'Kamis', 6, 7, 'BIND'),
(40, 2, 6, 5, 'Kamis', 8, 10, 'IPAS'),
(41, 2, 18, 5, 'Jumat', 2, 4, 'PAIBP'),
(42, 2, 2, 2, 'Jumat', 5, 7, 'PJOK'),
(43, 2, 9, 5, 'Jumat', 8, 10, 'DTKI'),
(44, 2, 6, 5, 'Jumat', 11, 13, 'IPAS'),
(45, 3, 19, 6, 'Senin', 2, 3, 'BIND'),
(46, 3, 20, 6, 'Senin', 4, 4, 'BK'),
(47, 3, 21, 2, 'Senin', 5, 7, 'PJOK'),
(48, 3, 22, 7, 'Senin', 8, 10, 'IPAS'),
(49, 3, 22, 7, 'Selasa', 1, 3, 'IPAS'),
(50, 3, 23, 7, 'Selasa', 4, 5, 'BJAW'),
(51, 3, 17, 7, 'Selasa', 6, 7, 'BING'),
(52, 3, 18, 7, 'Selasa', 8, 10, 'PAIBP'),
(53, 3, 5, 7, 'Rabu', 1, 2, 'SBUD'),
(54, 3, 24, 7, 'Rabu', 3, 4, 'MTK'),
(55, 3, 25, 6, 'Rabu', 5, 6, 'INF'),
(56, 3, 26, 6, 'Rabu', 7, 10, 'DPPLG'),
(57, 3, 17, 7, 'Kamis', 1, 2, 'BING'),
(58, 3, 24, 7, 'Kamis', 3, 4, 'MTK'),
(59, 3, 4, 7, 'Kamis', 5, 6, 'SEJ'),
(60, 3, 27, 6, 'Kamis', 7, 10, 'DPPLG'),
(61, 3, 27, 6, 'Jumat', 2, 5, 'DPPLG'),
(62, 3, 28, 6, 'Jumat', 6, 7, 'KKA'),
(63, 3, 29, 7, 'Jumat', 8, 9, 'PPKN'),
(64, 3, 25, 6, 'Jumat', 10, 11, 'INF'),
(65, 3, 19, 6, 'Jumat', 12, 13, 'BIND'),
(66, 4, 27, 8, 'Senin', 2, 5, 'DPPLG'),
(67, 4, 30, 7, 'Senin', 6, 7, 'BIND'),
(68, 4, 31, 9, 'Senin', 8, 10, 'IPAS'),
(69, 4, 28, 8, 'Selasa', 1, 4, 'INF'),
(70, 4, 28, 8, 'Selasa', 5, 6, 'KKA'),
(71, 4, 4, 9, 'Selasa', 7, 8, 'SEJ'),
(72, 4, 29, 9, 'Selasa', 9, 10, 'PPKN'),
(73, 4, 32, 9, 'Rabu', 1, 2, 'BING'),
(74, 4, 32, 9, 'Rabu', 3, 4, 'BING'),
(75, 4, 24, 9, 'Rabu', 5, 6, 'MTK'),
(76, 4, 27, 8, 'Rabu', 7, 10, 'DPPLG'),
(77, 4, 26, 8, 'Kamis', 1, 4, 'DPPLG'),
(78, 4, 18, 9, 'Kamis', 5, 7, 'PAIBP'),
(79, 4, 31, 9, 'Kamis', 8, 10, 'IPAS'),
(80, 4, 30, 8, 'Jumat', 2, 3, 'BIND'),
(81, 4, 20, 8, 'Jumat', 4, 4, 'BK'),
(82, 4, 21, 2, 'Jumat', 5, 7, 'PJOK'),
(83, 4, 33, 9, 'Jumat', 8, 9, 'BJAW'),
(84, 4, 5, 9, 'Jumat', 10, 11, 'SBUD'),
(85, 4, 24, 9, 'Jumat', 12, 13, 'MTK'),
(86, 5, 31, 10, 'Senin', 2, 4, 'IPAS'),
(87, 5, 34, 10, 'Senin', 5, 6, 'BING'),
(88, 5, 35, 3, 'Senin', 7, 8, 'KKA'),
(89, 5, 35, 3, 'Senin', 9, 10, 'DTJKT'),
(90, 5, 36, 3, 'Selasa', 1, 4, 'INF'),
(91, 5, 31, 10, 'Selasa', 5, 7, 'IPAS'),
(92, 5, 37, 10, 'Selasa', 8, 9, 'BJAW'),
(93, 5, 38, 10, 'Selasa', 10, 10, 'BK'),
(94, 5, 39, 10, 'Rabu', 1, 2, 'MTK'),
(95, 5, 40, 10, 'Rabu', 3, 4, 'BIND'),
(96, 5, 8, 3, 'Rabu', 5, 6, 'DTJKT'),
(97, 5, 39, 10, 'Rabu', 7, 8, 'MTK'),
(98, 5, 34, 10, 'Rabu', 9, 10, 'BING'),
(99, 5, 16, 10, 'Kamis', 1, 2, 'SEJ'),
(100, 5, 40, 10, 'Kamis', 3, 4, 'BIND'),
(101, 5, 13, 10, 'Kamis', 5, 6, 'PPKN'),
(102, 5, 41, 3, 'Kamis', 7, 10, 'DTJKT'),
(103, 5, 2, 2, 'Jumat', 2, 4, 'PJOK'),
(104, 5, 5, 10, 'Jumat', 5, 6, 'SBUD'),
(105, 5, 36, 3, 'Jumat', 7, 10, 'DTJKT'),
(106, 5, 42, 10, 'Jumat', 11, 13, 'PAIBP'),
(107, 6, 41, 3, 'Senin', 2, 5, 'DTJKT'),
(108, 6, 42, 11, 'Senin', 6, 8, 'PAIBP'),
(109, 6, 39, 11, 'Senin', 9, 10, 'MTK'),
(110, 6, 40, 11, 'Selasa', 1, 2, 'BIND'),
(111, 6, 2, 2, 'Selasa', 3, 5, 'PJOK'),
(112, 6, 8, 3, 'Selasa', 6, 7, 'DTJKT'),
(113, 6, 31, 11, 'Selasa', 8, 10, 'IPAS'),
(114, 6, 13, 11, 'Rabu', 1, 2, 'PPKN'),
(115, 6, 38, 11, 'Rabu', 3, 3, 'BK'),
(116, 6, 31, 11, 'Rabu', 4, 6, 'IPAS'),
(117, 6, 35, 3, 'Rabu', 7, 8, 'DTJKT'),
(118, 6, 39, 11, 'Rabu', 9, 10, 'MTK'),
(119, 6, 36, 3, 'Kamis', 1, 4, 'INF'),
(120, 6, 40, 11, 'Kamis', 5, 6, 'BIND'),
(121, 6, 5, 11, 'Kamis', 7, 8, 'SBUD'),
(122, 6, 34, 11, 'Kamis', 9, 10, 'BING'),
(123, 6, 36, 3, 'Jumat', 2, 5, 'DTJKT'),
(124, 6, 4, 11, 'Jumat', 6, 7, 'SEJ'),
(125, 6, 37, 11, 'Jumat', 8, 9, 'BJAW'),
(126, 6, 34, 11, 'Jumat', 10, 11, 'BING'),
(127, 6, 35, 3, 'Jumat', 12, 13, 'KKA'),
(128, 7, 43, 12, 'Senin', 2, 3, 'SEJ'),
(129, 7, 44, 12, 'Senin', 4, 7, 'DPM'),
(130, 7, 45, 12, 'Senin', 8, 9, 'BIND'),
(131, 7, 46, 12, 'Senin', 10, 10, 'BK'),
(132, 7, 42, 12, 'Selasa', 1, 3, 'PAIBP'),
(133, 7, 45, 12, 'Selasa', 4, 5, 'BIND'),
(134, 7, 22, 12, 'Selasa', 6, 8, 'IPAS'),
(135, 7, 33, 12, 'Selasa', 9, 10, 'MTK'),
(136, 7, 41, 13, 'Rabu', 1, 2, 'KKA'),
(137, 7, 1, 12, 'Rabu', 3, 4, 'BING'),
(138, 7, 47, 12, 'Rabu', 5, 8, 'DPM'),
(139, 7, 48, 12, 'Rabu', 9, 10, 'SBUD'),
(140, 7, 22, 12, 'Kamis', 1, 3, 'IPAS'),
(141, 7, 49, 2, 'Kamis', 4, 6, 'PJOK'),
(142, 7, 1, 12, 'Kamis', 7, 8, 'BING'),
(143, 7, 47, 12, 'Kamis', 9, 10, 'PPKN'),
(144, 7, 50, 13, 'Jumat', 2, 5, 'INF'),
(145, 7, 33, 12, 'Jumat', 6, 7, 'MTK'),
(146, 7, 51, 12, 'Jumat', 8, 11, 'DPM'),
(147, 7, 23, 12, 'Jumat', 12, 13, 'BJAW'),
(148, 8, 22, 14, 'Senin', 2, 4, 'IPAS'),
(149, 8, 51, 14, 'Senin', 5, 8, 'DPM'),
(150, 8, 47, 14, 'Senin', 9, 10, 'PPKN'),
(151, 8, 52, 14, 'Selasa', 1, 2, 'BJAW'),
(152, 8, 41, 13, 'Selasa', 3, 4, 'KKA'),
(153, 8, 33, 14, 'Selasa', 5, 6, 'MTK'),
(154, 8, 44, 14, 'Selasa', 7, 10, 'DPM'),
(155, 8, 42, 14, 'Rabu', 1, 3, 'PAIBP'),
(156, 8, 22, 14, 'Rabu', 4, 6, 'IPAS'),
(157, 8, 50, 13, 'Rabu', 7, 10, 'INF'),
(158, 8, 47, 14, 'Kamis', 1, 4, 'DPM'),
(159, 8, 53, 14, 'Kamis', 5, 6, 'BING'),
(160, 8, 43, 14, 'Kamis', 7, 8, 'SEJ'),
(161, 8, 45, 14, 'Kamis', 9, 10, 'BIND'),
(162, 8, 46, 14, 'Jumat', 2, 2, 'BK'),
(163, 8, 53, 14, 'Jumat', 3, 4, 'BING'),
(164, 8, 49, 2, 'Jumat', 5, 7, 'PJOK'),
(165, 8, 45, 14, 'Jumat', 8, 9, 'BIND'),
(166, 8, 48, 14, 'Jumat', 10, 11, 'SBUD'),
(167, 8, 33, 14, 'Jumat', 12, 13, 'MTK'),
(168, 9, 54, 15, 'Senin', 2, 4, 'PAIBP'),
(169, 9, 55, 2, 'Senin', 5, 7, 'PJOK'),
(170, 9, 56, 15, 'Senin', 8, 10, 'IPAS'),
(171, 9, 50, 15, 'Selasa', 1, 2, 'MTK'),
(172, 9, 51, 15, 'Selasa', 3, 6, 'DPM'),
(173, 9, 53, 15, 'Selasa', 7, 8, 'BING'),
(174, 9, 57, 15, 'Selasa', 9, 10, 'BIND'),
(175, 9, 47, 15, 'Rabu', 1, 4, 'DPM'),
(176, 9, 4, 15, 'Rabu', 5, 6, 'SEJ'),
(177, 9, 37, 15, 'Rabu', 7, 8, 'BJAW'),
(178, 9, 57, 15, 'Rabu', 9, 10, 'BIND'),
(179, 9, 29, 15, 'Kamis', 1, 2, 'PPKN'),
(180, 9, 56, 15, 'Kamis', 3, 5, 'IPAS'),
(181, 9, 48, 15, 'Kamis', 6, 7, 'SBUD'),
(182, 9, 53, 15, 'Kamis', 8, 9, 'BING'),
(183, 9, 58, 15, 'Kamis', 10, 10, 'BK'),
(184, 9, 44, 15, 'Jumat', 2, 5, 'DPM'),
(185, 9, 50, 13, 'Jumat', 6, 9, 'INF'),
(186, 9, 50, 15, 'Jumat', 10, 11, 'MTK'),
(187, 9, 41, 13, 'Jumat', 12, 13, 'KKA'),
(188, 10, 56, 16, 'Senin', 2, 4, 'IPAS'),
(189, 10, 15, 16, 'Senin', 5, 6, 'SEJ'),
(190, 10, 48, 16, 'Senin', 7, 8, 'SBUD'),
(191, 10, 59, 16, 'Senin', 9, 10, 'BIND'),
(192, 10, 60, 16, 'Selasa', 1, 4, 'DMPLB'),
(193, 10, 61, 16, 'Selasa', 5, 6, 'BING'),
(194, 10, 59, 16, 'Selasa', 7, 8, 'BIND'),
(195, 10, 62, 16, 'Selasa', 9, 10, 'MTK'),
(196, 10, 61, 16, 'Rabu', 1, 2, 'BING'),
(197, 10, 37, 16, 'Rabu', 3, 4, 'BJAW'),
(198, 10, 56, 16, 'Rabu', 5, 7, 'IPAS'),
(199, 10, 63, 16, 'Rabu', 8, 8, 'BK'),
(200, 10, 25, 17, 'Rabu', 9, 10, 'INF'),
(201, 10, 64, 16, 'Kamis', 1, 2, 'PPKN'),
(202, 10, 62, 16, 'Kamis', 3, 4, 'MTK'),
(203, 10, 65, 17, 'Kamis', 5, 6, 'KKA'),
(204, 10, 60, 16, 'Kamis', 7, 10, 'DMPLB'),
(205, 10, 21, 2, 'Jumat', 2, 4, 'PJOK'),
(206, 10, 60, 16, 'Jumat', 5, 8, 'DMPLB'),
(207, 10, 54, 16, 'Jumat', 9, 11, 'PAIBP'),
(208, 10, 25, 17, 'Jumat', 12, 13, 'INF'),
(209, 11, 38, 18, 'Senin', 2, 2, 'BK'),
(210, 11, 64, 18, 'Senin', 3, 4, 'PPKN'),
(211, 11, 37, 18, 'Senin', 5, 6, 'BJAW'),
(212, 11, 61, 18, 'Senin', 7, 8, 'BING'),
(213, 11, 15, 18, 'Senin', 9, 10, 'SEJ'),
(214, 11, 61, 18, 'Selasa', 1, 2, 'BING'),
(215, 11, 62, 18, 'Selasa', 3, 4, 'MTK'),
(216, 11, 66, 18, 'Selasa', 5, 8, 'DMPLB'),
(217, 11, 65, 17, 'Selasa', 9, 10, 'KKA'),
(218, 11, 48, 18, 'Rabu', 1, 2, 'SBUD'),
(219, 11, 21, 2, 'Rabu', 3, 5, 'PJOK'),
(220, 11, 62, 18, 'Rabu', 6, 7, 'MTK'),
(221, 11, 56, 18, 'Rabu', 8, 10, 'IPAS'),
(222, 11, 67, 17, 'Kamis', 1, 4, 'INF'),
(223, 11, 59, 18, 'Kamis', 5, 6, 'BIND'),
(224, 11, 66, 18, 'Kamis', 7, 10, 'DMPLB'),
(225, 11, 56, 18, 'Jumat', 2, 4, 'IPAS'),
(226, 11, 54, 18, 'Jumat', 5, 7, 'PAIBP'),
(227, 11, 59, 18, 'Jumat', 8, 9, 'BIND'),
(228, 11, 66, 18, 'Jumat', 10, 13, 'DMPLB'),
(229, 12, 21, 2, 'Senin', 2, 4, 'PJOK'),
(230, 12, 61, 19, 'Senin', 5, 6, 'BING'),
(231, 12, 68, 19, 'Senin', 7, 10, 'DMPLB'),
(232, 12, 45, 19, 'Selasa', 1, 2, 'BIND'),
(233, 12, 61, 19, 'Selasa', 3, 4, 'BING'),
(234, 12, 26, 20, 'Selasa', 5, 6, 'KKA'),
(235, 12, 68, 19, 'Selasa', 7, 10, 'DMPLB'),
(236, 12, 45, 19, 'Rabu', 1, 2, 'BIND'),
(237, 12, 39, 19, 'Rabu', 3, 4, 'MTK'),
(238, 12, 6, 19, 'Rabu', 5, 7, 'IPAS'),
(239, 12, 54, 19, 'Rabu', 8, 10, 'PAIBP'),
(240, 12, 6, 19, 'Kamis', 1, 3, 'IPAS'),
(241, 12, 39, 19, 'Kamis', 4, 5, 'MTK'),
(242, 12, 69, 19, 'Kamis', 6, 6, 'BK'),
(243, 12, 26, 20, 'Kamis', 7, 10, 'INF'),
(244, 12, 6, 19, 'Jumat', 2, 3, 'PPKN'),
(245, 12, 4, 19, 'Jumat', 4, 5, 'SEJ'),
(246, 12, 23, 19, 'Jumat', 6, 7, 'BJAW'),
(247, 12, 68, 19, 'Jumat', 8, 11, 'DMPLB'),
(248, 12, 48, 19, 'Jumat', 12, 13, 'SBUD'),
(249, 13, 6, 21, 'Senin', 2, 4, 'IPAS'),
(250, 13, 70, 21, 'Senin', 5, 8, 'DMPLB'),
(251, 13, 50, 21, 'Senin', 9, 10, 'MTK'),
(252, 13, 21, 2, 'Selasa', 1, 3, 'PJOK'),
(253, 13, 1, 21, 'Selasa', 4, 5, 'BING'),
(254, 13, 45, 21, 'Selasa', 6, 7, 'BIND'),
(255, 13, 6, 21, 'Selasa', 8, 10, 'IPAS'),
(256, 13, 26, 20, 'Rabu', 1, 2, 'KKA'),
(257, 13, 70, 21, 'Rabu', 3, 6, 'DMPLB'),
(258, 13, 45, 21, 'Rabu', 7, 8, 'BIND'),
(259, 13, 15, 21, 'Rabu', 9, 10, 'SEJ'),
(260, 13, 1, 21, 'Kamis', 1, 2, 'BING'),
(261, 13, 71, 21, 'Kamis', 3, 3, 'BK'),
(262, 13, 72, 21, 'Kamis', 4, 6, 'PAIBP'),
(263, 13, 50, 21, 'Kamis', 7, 8, 'MTK'),
(264, 13, 48, 21, 'Kamis', 9, 10, 'SBUD'),
(265, 13, 26, 20, 'Jumat', 2, 5, 'INF'),
(266, 13, 29, 21, 'Jumat', 6, 7, 'PPKN'),
(267, 13, 23, 21, 'Jumat', 8, 9, 'BJAW'),
(268, 13, 70, 21, 'Jumat', 10, 13, 'DMPLB'),
(269, 14, 11, 22, 'Senin', 2, 3, 'MTK'),
(270, 14, 17, 22, 'Senin', 4, 5, 'BING'),
(271, 14, 69, 22, 'Senin', 6, 6, 'BK'),
(272, 14, 15, 22, 'Senin', 7, 8, 'SEJ'),
(273, 14, 73, 23, 'Senin', 9, 10, 'INF'),
(274, 14, 48, 22, 'Selasa', 1, 2, 'SBUD'),
(275, 14, 43, 22, 'Selasa', 3, 5, 'DAKL'),
(276, 14, 56, 22, 'Selasa', 6, 8, 'IPAS'),
(277, 14, 73, 23, 'Selasa', 9, 10, 'INF'),
(278, 14, 17, 22, 'Rabu', 1, 2, 'BING'),
(279, 14, 49, 2, 'Rabu', 3, 5, 'PJOK'),
(280, 14, 74, 22, 'Rabu', 6, 7, 'BIND'),
(281, 14, 43, 22, 'Rabu', 8, 10, 'DAKL'),
(282, 14, 74, 22, 'Kamis', 1, 2, 'BIND'),
(283, 14, 31, 22, 'Kamis', 3, 4, 'BJAW'),
(284, 14, 75, 22, 'Kamis', 5, 6, 'DAKL'),
(285, 14, 76, 22, 'Kamis', 7, 8, 'PPKN'),
(286, 14, 11, 22, 'Kamis', 9, 10, 'MTK'),
(287, 14, 73, 23, 'Jumat', 2, 3, 'KKA'),
(288, 14, 72, 22, 'Jumat', 4, 6, 'PAIBP'),
(289, 14, 56, 22, 'Jumat', 7, 9, 'IPAS'),
(290, 14, 77, 24, 'Jumat', 10, 13, 'DAKL'),
(291, 15, 17, 25, 'Senin', 2, 3, 'BING'),
(292, 15, 78, 25, 'Senin', 4, 6, 'DAKL'),
(293, 15, 73, 23, 'Senin', 7, 8, 'INF'),
(294, 15, 74, 25, 'Senin', 9, 10, 'BIND'),
(295, 15, 73, 23, 'Selasa', 1, 2, 'INF'),
(296, 15, 17, 25, 'Selasa', 3, 4, 'BING'),
(297, 15, 49, 2, 'Selasa', 5, 7, 'PJOK'),
(298, 15, 11, 25, 'Selasa', 8, 9, 'MTK'),
(299, 15, 69, 25, 'Selasa', 10, 10, 'BK'),
(300, 15, 56, 25, 'Rabu', 1, 3, 'IPAS'),
(301, 15, 72, 25, 'Rabu', 4, 6, 'PAIBP'),
(302, 15, 77, 24, 'Rabu', 7, 10, 'DAKL'),
(303, 15, 78, 25, 'Kamis', 1, 3, 'DAKL'),
(304, 15, 11, 25, 'Kamis', 4, 5, 'MTK'),
(305, 15, 56, 25, 'Kamis', 6, 8, 'IPAS'),
(306, 15, 73, 23, 'Kamis', 9, 10, 'KKA'),
(307, 15, 76, 25, 'Jumat', 2, 3, 'PPKN'),
(308, 15, 74, 25, 'Jumat', 4, 5, 'BIND'),
(309, 15, 31, 25, 'Jumat', 6, 7, 'BJAW'),
(310, 15, 48, 25, 'Jumat', 8, 9, 'SBUD'),
(311, 15, 43, 25, 'Jumat', 10, 11, 'DAKL'),
(312, 15, 15, 25, 'Jumat', 12, 13, 'SEJ'),
(313, 16, 49, 2, 'Senin', 2, 4, 'PJOK'),
(314, 16, 43, 26, 'Senin', 5, 6, 'DAKL'),
(315, 16, 27, 24, 'Senin', 7, 8, 'KKA'),
(316, 16, 67, 24, 'Senin', 9, 10, 'INF'),
(317, 16, 67, 24, 'Selasa', 1, 2, 'INF'),
(318, 16, 48, 26, 'Selasa', 3, 4, 'SBUD'),
(319, 16, 29, 26, 'Selasa', 5, 6, 'PPKN'),
(320, 16, 74, 26, 'Selasa', 7, 8, 'BIND'),
(321, 16, 79, 26, 'Selasa', 9, 10, 'BING'),
(322, 16, 72, 26, 'Rabu', 1, 3, 'PAIBP'),
(323, 16, 80, 26, 'Rabu', 4, 5, 'MTK'),
(324, 16, 81, 26, 'Rabu', 6, 6, 'BK'),
(325, 16, 82, 23, 'Rabu', 7, 10, 'DAKL'),
(326, 16, 43, 26, 'Kamis', 1, 2, 'SEJ'),
(327, 16, 23, 26, 'Kamis', 3, 4, 'BJAW'),
(328, 16, 83, 26, 'Kamis', 5, 7, 'DAKL'),
(329, 16, 16, 26, 'Kamis', 8, 10, 'IPAS'),
(330, 16, 16, 26, 'Jumat', 2, 4, 'IPAS'),
(331, 16, 79, 26, 'Jumat', 5, 6, 'BING'),
(332, 16, 83, 26, 'Jumat', 7, 9, 'DAKL'),
(333, 16, 80, 26, 'Jumat', 10, 11, 'MTK'),
(334, 16, 74, 26, 'Jumat', 12, 13, 'BIND'),
(335, 17, 48, 27, 'Senin', 2, 3, 'SBUD'),
(336, 17, 81, 27, 'Senin', 4, 4, 'BK'),
(337, 17, 16, 27, 'Senin', 5, 7, 'IPAS'),
(338, 17, 10, 27, 'Senin', 8, 10, 'PAIBP'),
(339, 17, 43, 27, 'Selasa', 1, 2, 'SEJ'),
(340, 17, 67, 24, 'Selasa', 3, 4, 'INF'),
(341, 17, 80, 27, 'Selasa', 5, 6, 'MTK'),
(342, 17, 79, 27, 'Selasa', 7, 8, 'BING'),
(343, 17, 43, 27, 'Selasa', 9, 10, 'DAKL'),
(344, 17, 84, 27, 'Rabu', 1, 3, 'DAKL'),
(345, 17, 23, 27, 'Rabu', 4, 5, 'BJAW'),
(346, 17, 57, 27, 'Rabu', 6, 7, 'BIND'),
(347, 17, 16, 27, 'Rabu', 8, 10, 'IPAS'),
(348, 17, 49, 2, 'Kamis', 1, 3, 'PJOK'),
(349, 17, 84, 27, 'Kamis', 4, 6, 'DAKL'),
(350, 17, 79, 27, 'Kamis', 7, 8, 'BING'),
(351, 17, 29, 27, 'Kamis', 9, 10, 'PPKN'),
(352, 17, 67, 24, 'Jumat', 2, 3, 'INF'),
(353, 17, 80, 27, 'Jumat', 4, 5, 'MTK'),
(354, 17, 57, 27, 'Jumat', 6, 7, 'BIND'),
(355, 17, 27, 24, 'Jumat', 8, 9, 'KKA'),
(356, 17, 82, 23, 'Jumat', 10, 13, 'DAKL'),
(357, 18, 16, 28, 'Senin', 2, 4, 'IPAS'),
(358, 18, 36, 29, 'Senin', 5, 8, 'INF'),
(359, 18, 33, 28, 'Senin', 9, 10, 'BJAW'),
(360, 18, 18, 28, 'Selasa', 1, 3, 'PAIBP'),
(361, 18, 55, 2, 'Selasa', 4, 6, 'PJOK'),
(362, 18, 85, 28, 'Selasa', 7, 8, 'DULP'),
(363, 18, 17, 28, 'Selasa', 9, 10, 'BING'),
(364, 18, 40, 28, 'Rabu', 1, 2, 'BIND'),
(365, 18, 85, 28, 'Rabu', 3, 6, 'DULP'),
(366, 18, 80, 28, 'Rabu', 7, 8, 'MTK'),
(367, 18, 17, 28, 'Rabu', 9, 10, 'BING'),
(368, 18, 40, 28, 'Kamis', 1, 2, 'BIND'),
(369, 18, 85, 28, 'Kamis', 3, 6, 'DULP'),
(370, 18, 13, 28, 'Kamis', 7, 8, 'PPKN'),
(371, 18, 15, 28, 'Kamis', 9, 10, 'SEJ'),
(372, 18, 58, 28, 'Jumat', 2, 2, 'BK'),
(373, 18, 85, 28, 'Jumat', 3, 4, 'DULP'),
(374, 18, 48, 28, 'Jumat', 5, 6, 'SBUD'),
(375, 18, 73, 29, 'Jumat', 7, 8, 'KKA'),
(376, 18, 16, 28, 'Jumat', 9, 11, 'IPAS'),
(377, 18, 80, 28, 'Jumat', 12, 13, 'MTK'),
(378, 19, 86, 30, 'Senin', 2, 4, 'PAIBP'),
(379, 19, 6, 30, 'Senin', 5, 7, 'IPAS'),
(380, 19, 87, 30, 'Senin', 8, 10, 'DDKV'),
(381, 19, 69, 30, 'Selasa', 1, 1, 'BK'),
(382, 19, 32, 30, 'Selasa', 2, 3, 'BING'),
(383, 19, 88, 30, 'Selasa', 4, 6, 'DDKV'),
(384, 19, 89, 30, 'Selasa', 7, 8, 'BJAW'),
(385, 19, 90, 30, 'Selasa', 9, 10, 'BIND'),
(386, 19, 62, 30, 'Rabu', 1, 2, 'MTK'),
(387, 19, 2, 2, 'Rabu', 3, 5, 'PJOK'),
(388, 19, 89, 30, 'Rabu', 6, 8, 'DDKV'),
(389, 19, 91, 31, 'Rabu', 9, 10, 'KKA'),
(390, 19, 24, 31, 'Kamis', 1, 2, 'INF'),
(391, 19, 32, 30, 'Kamis', 3, 4, 'BING'),
(392, 19, 5, 30, 'Kamis', 5, 6, 'SBUD'),
(393, 19, 4, 30, 'Kamis', 7, 8, 'SEJ'),
(394, 19, 13, 30, 'Kamis', 9, 10, 'PPKN'),
(395, 19, 88, 30, 'Jumat', 2, 4, 'DDKV'),
(396, 19, 62, 30, 'Jumat', 5, 6, 'MTK'),
(397, 19, 6, 30, 'Jumat', 7, 9, 'IPAS'),
(398, 19, 24, 31, 'Jumat', 10, 11, 'INF'),
(399, 19, 90, 30, 'Jumat', 12, 13, 'BIND'),
(400, 20, 91, 31, 'Senin', 2, 3, 'KKA'),
(401, 20, 4, 32, 'Senin', 4, 5, 'SEJ'),
(402, 20, 88, 32, 'Senin', 6, 8, 'DDKV'),
(403, 20, 13, 32, 'Senin', 9, 10, 'PPKN'),
(404, 20, 31, 32, 'Selasa', 1, 3, 'IPAS'),
(405, 20, 90, 32, 'Selasa', 4, 5, 'BIND'),
(406, 20, 32, 32, 'Selasa', 6, 7, 'BING'),
(407, 20, 86, 32, 'Selasa', 8, 10, 'PAIBP'),
(408, 20, 24, 31, 'Rabu', 1, 2, 'INF'),
(409, 20, 14, 32, 'Rabu', 3, 3, 'BK'),
(410, 20, 88, 32, 'Rabu', 4, 6, 'DDKV'),
(411, 20, 92, 32, 'Rabu', 7, 8, 'MTK'),
(412, 20, 90, 32, 'Rabu', 9, 10, 'BIND'),
(413, 20, 32, 32, 'Kamis', 1, 2, 'BING'),
(414, 20, 2, 2, 'Kamis', 3, 5, 'PJOK'),
(415, 20, 24, 31, 'Kamis', 6, 7, 'INF'),
(416, 20, 87, 32, 'Kamis', 8, 10, 'DDKV'),
(417, 20, 5, 32, 'Jumat', 2, 3, 'SBUD'),
(418, 20, 89, 32, 'Jumat', 4, 5, 'BJAW'),
(419, 20, 89, 32, 'Jumat', 6, 8, 'DDKV'),
(420, 20, 92, 32, 'Jumat', 9, 10, 'MTK'),
(421, 20, 31, 32, 'Jumat', 11, 13, 'IPAS'),
(422, 21, 55, 2, 'Senin', 2, 4, 'PJOK'),
(423, 21, 29, 33, 'Senin', 5, 6, 'PPKN'),
(424, 21, 93, 4, 'Senin', 7, 10, 'DBP'),
(425, 21, 93, 4, 'Selasa', 1, 4, 'DBP'),
(426, 21, 5, 33, 'Selasa', 5, 6, 'SBUD'),
(427, 21, 50, 33, 'Selasa', 7, 8, 'MTK'),
(428, 21, 19, 33, 'Selasa', 9, 10, 'BIND'),
(429, 21, 86, 33, 'Rabu', 1, 3, 'PAIBP'),
(430, 21, 16, 33, 'Rabu', 4, 6, 'IPAS'),
(431, 21, 1, 33, 'Rabu', 7, 8, 'BING'),
(432, 21, 19, 33, 'Rabu', 9, 10, 'BIND'),
(433, 21, 28, 4, 'Kamis', 1, 2, 'KKA'),
(434, 21, 28, 4, 'Kamis', 3, 6, 'INF'),
(435, 21, 89, 33, 'Kamis', 7, 8, 'BJAW'),
(436, 21, 50, 33, 'Kamis', 9, 10, 'MTK'),
(437, 21, 4, 33, 'Jumat', 2, 3, 'SEJ'),
(438, 21, 1, 33, 'Jumat', 4, 5, 'BING'),
(439, 21, 16, 33, 'Jumat', 6, 8, 'IPAS'),
(440, 21, 94, 33, 'Jumat', 9, 9, 'BK'),
(441, 21, 93, 4, 'Jumat', 10, 13, 'DBP'),
(442, 22, 28, 4, 'Senin', 2, 5, 'INF'),
(443, 22, 1, 34, 'Senin', 6, 7, 'BING'),
(444, 22, 16, 34, 'Senin', 8, 10, 'IPAS'),
(445, 22, 16, 34, 'Selasa', 1, 3, 'IPAS'),
(446, 22, 4, 34, 'Selasa', 4, 5, 'SEJ'),
(447, 22, 94, 34, 'Selasa', 6, 6, 'BK'),
(448, 22, 95, 4, 'Selasa', 7, 10, 'DBP'),
(449, 22, 95, 4, 'Rabu', 1, 4, 'DBP'),
(450, 22, 55, 2, 'Rabu', 5, 7, 'PJOK'),
(451, 22, 86, 34, 'Rabu', 8, 10, 'PAIBP'),
(452, 22, 30, 34, 'Kamis', 1, 2, 'BIND'),
(453, 22, 33, 34, 'Kamis', 3, 4, 'MTK'),
(454, 22, 29, 34, 'Kamis', 5, 6, 'PPKN'),
(455, 22, 37, 34, 'Kamis', 7, 8, 'BJAW'),
(456, 22, 1, 34, 'Kamis', 9, 10, 'BING'),
(457, 22, 28, 4, 'Jumat', 2, 3, 'KKA'),
(458, 22, 95, 4, 'Jumat', 4, 7, 'DBP'),
(459, 22, 5, 34, 'Jumat', 8, 9, 'SBUD'),
(460, 22, 33, 34, 'Jumat', 10, 11, 'MTK'),
(461, 22, 30, 34, 'Jumat', 12, 13, 'BIND'),
(462, 23, 4, 35, 'Senin', 2, 3, 'SEJ'),
(463, 23, 5, 35, 'Senin', 4, 5, 'SBUD'),
(464, 23, 71, 35, 'Senin', 6, 6, 'BK'),
(465, 23, 57, 35, 'Senin', 7, 8, 'BIND'),
(466, 23, 96, 35, 'Senin', 9, 10, 'BING'),
(467, 23, 10, 35, 'Selasa', 1, 3, 'PAIBP'),
(468, 23, 57, 35, 'Selasa', 4, 5, 'BIND'),
(469, 23, 97, 36, 'Selasa', 6, 7, 'INF'),
(470, 23, 98, 36, 'Selasa', 8, 10, 'DAN'),
(471, 23, 99, 36, 'Rabu', 1, 3, 'DAN'),
(472, 23, 100, 35, 'Rabu', 4, 5, 'PPKN'),
(473, 23, 12, 35, 'Rabu', 6, 7, 'MTK'),
(474, 23, 22, 35, 'Rabu', 8, 10, 'IPAS'),
(475, 23, 96, 35, 'Kamis', 1, 2, 'BING'),
(476, 23, 12, 35, 'Kamis', 3, 4, 'MTK'),
(477, 23, 55, 2, 'Kamis', 5, 7, 'PJOK'),
(478, 23, 99, 36, 'Kamis', 8, 10, 'DAN'),
(479, 23, 22, 35, 'Jumat', 2, 4, 'IPAS'),
(480, 23, 37, 35, 'Jumat', 5, 6, 'BJAW'),
(481, 23, 98, 36, 'Jumat', 7, 9, 'DAN'),
(482, 23, 91, 36, 'Jumat', 10, 11, 'KKA'),
(483, 23, 97, 36, 'Jumat', 12, 13, 'INF'),
(484, 24, 10, 37, 'Senin', 2, 4, 'PAIBP'),
(485, 24, 91, 36, 'Senin', 5, 6, 'KKA'),
(486, 24, 4, 37, 'Senin', 7, 8, 'SEJ'),
(487, 24, 92, 37, 'Senin', 9, 10, 'MTK'),
(488, 24, 55, 2, 'Selasa', 1, 3, 'PJOK'),
(489, 24, 92, 37, 'Selasa', 4, 5, 'MTK'),
(490, 24, 100, 37, 'Selasa', 6, 7, 'PPKN'),
(491, 24, 96, 37, 'Selasa', 8, 9, 'BING'),
(492, 24, 71, 37, 'Selasa', 10, 10, 'BK'),
(493, 24, 22, 37, 'Rabu', 1, 3, 'IPAS'),
(494, 24, 57, 37, 'Rabu', 4, 5, 'BIND'),
(495, 24, 5, 37, 'Rabu', 6, 7, 'SBUD'),
(496, 24, 98, 36, 'Rabu', 8, 10, 'DAN'),
(497, 24, 57, 37, 'Kamis', 1, 2, 'BIND'),
(498, 24, 96, 37, 'Kamis', 3, 4, 'BING'),
(499, 24, 99, 36, 'Kamis', 5, 7, 'DAN'),
(500, 24, 98, 38, 'Kamis', 8, 10, 'DAN'),
(501, 24, 37, 37, 'Jumat', 2, 3, 'BJAW'),
(502, 24, 98, 36, 'Jumat', 4, 5, 'INF'),
(503, 24, 22, 37, 'Jumat', 6, 8, 'IPAS'),
(504, 24, 99, 38, 'Jumat', 9, 11, 'DAN'),
(505, 24, 98, 38, 'Jumat', 12, 13, 'INF'),
(506, 25, 101, 39, 'Senin', 2, 3, 'KTKI'),
(507, 25, 102, 39, 'Senin', 4, 6, 'KTKI'),
(508, 25, 103, 39, 'Senin', 7, 10, 'KTKI'),
(509, 25, 23, 39, 'Selasa', 1, 2, 'BJPN'),
(510, 25, 96, 39, 'Selasa', 3, 4, 'BING'),
(511, 25, 101, 39, 'Selasa', 5, 7, 'KIK'),
(512, 25, 39, 39, 'Selasa', 8, 10, 'MTK'),
(513, 25, 4, 39, 'Rabu', 1, 2, 'BJAW'),
(514, 25, 7, 39, 'Rabu', 3, 4, 'KTKI'),
(515, 25, 101, 39, 'Rabu', 5, 6, 'KIK'),
(516, 25, 9, 39, 'Rabu', 7, 8, 'MPTKI'),
(517, 25, 9, 39, 'Rabu', 9, 10, 'PPKN'),
(518, 25, 3, 39, 'Kamis', 1, 3, 'BIND'),
(519, 25, 101, 39, 'Kamis', 4, 5, 'SEJ'),
(520, 25, 2, 2, 'Kamis', 6, 7, 'PJOK'),
(521, 25, 18, 39, 'Kamis', 8, 10, 'PAIBP'),
(522, 25, 102, 39, 'Jumat', 2, 4, 'KTKI'),
(523, 25, 103, 39, 'Jumat', 5, 8, 'KTKI'),
(524, 25, 96, 39, 'Jumat', 9, 10, 'BING'),
(525, 25, 14, 39, 'Jumat', 11, 12, 'BK'),
(526, 26, 2, 2, 'Senin', 2, 3, 'PJOK'),
(527, 26, 39, 40, 'Senin', 4, 6, 'MTK'),
(528, 26, 23, 40, 'Senin', 7, 8, 'BJPN'),
(529, 26, 61, 40, 'Senin', 9, 10, 'BING'),
(530, 26, 101, 40, 'Selasa', 1, 2, 'KIK'),
(531, 26, 101, 40, 'Selasa', 3, 4, 'KTKI'),
(532, 26, 7, 40, 'Selasa', 5, 6, 'KTKI'),
(533, 26, 9, 40, 'Selasa', 7, 8, 'MPTKI'),
(534, 26, 9, 40, 'Selasa', 9, 10, 'PPKN'),
(535, 26, 3, 40, 'Rabu', 1, 3, 'BIND'),
(536, 26, 102, 40, 'Rabu', 4, 6, 'KTKI'),
(537, 26, 103, 40, 'Rabu', 7, 10, 'KTKI'),
(538, 26, 18, 40, 'Kamis', 1, 3, 'PAIBP'),
(539, 26, 103, 40, 'Kamis', 4, 7, 'KTKI'),
(540, 26, 102, 40, 'Kamis', 8, 10, 'KTKI'),
(541, 26, 101, 40, 'Jumat', 2, 3, 'SEJ'),
(542, 26, 101, 40, 'Jumat', 4, 6, 'KIK'),
(543, 26, 61, 40, 'Jumat', 7, 8, 'BING'),
(544, 26, 14, 40, 'Jumat', 9, 10, 'BK'),
(545, 26, 4, 40, 'Jumat', 11, 12, 'BJAW'),
(546, 27, 18, 7, 'Senin', 2, 4, 'PAIBP'),
(547, 27, 20, 6, 'Senin', 5, 6, 'BK'),
(548, 27, 65, 6, 'Senin', 7, 10, 'KRPL'),
(549, 27, 65, 6, 'Selasa', 1, 4, 'KRPL'),
(550, 27, 73, 6, 'Selasa', 5, 7, 'KRPL'),
(551, 27, 47, 6, 'Selasa', 8, 10, 'KIK'),
(552, 27, 73, 6, 'Rabu', 1, 4, 'KRPL'),
(553, 27, 34, 7, 'Rabu', 5, 6, 'BING'),
(554, 27, 52, 7, 'Rabu', 7, 8, 'SEJ'),
(555, 27, 33, 7, 'Rabu', 9, 10, 'BJAW'),
(556, 27, 73, 6, 'Kamis', 1, 3, 'KRPL'),
(557, 27, 104, 6, 'Kamis', 4, 5, 'MPRPL'),
(558, 27, 21, 2, 'Kamis', 6, 7, 'PJOK'),
(559, 27, 30, 7, 'Kamis', 8, 10, 'BIND'),
(560, 27, 47, 7, 'Jumat', 2, 3, 'KIK'),
(561, 27, 13, 7, 'Jumat', 4, 5, 'PPKN'),
(562, 27, 105, 7, 'Jumat', 6, 7, 'BJPN'),
(563, 27, 34, 6, 'Jumat', 8, 9, 'BING'),
(564, 27, 11, 7, 'Jumat', 10, 12, 'MTK'),
(565, 28, 47, 9, 'Senin', 2, 4, 'KIK'),
(566, 28, 32, 9, 'Senin', 5, 6, 'BING'),
(567, 28, 26, 8, 'Senin', 7, 10, 'KRPL'),
(568, 28, 11, 9, 'Selasa', 1, 3, 'MTK'),
(569, 28, 32, 9, 'Selasa', 4, 5, 'BING'),
(570, 28, 21, 2, 'Selasa', 6, 7, 'PJOK'),
(571, 28, 26, 8, 'Selasa', 8, 10, 'KRPL'),
(572, 28, 65, 8, 'Rabu', 1, 4, 'KRPL'),
(573, 28, 104, 8, 'Rabu', 5, 6, 'MPRPL'),
(574, 28, 13, 9, 'Rabu', 7, 8, 'PPKN'),
(575, 28, 52, 9, 'Rabu', 9, 10, 'SEJ'),
(576, 28, 105, 9, 'Kamis', 1, 2, 'BJPN'),
(577, 28, 20, 9, 'Kamis', 3, 4, 'BK'),
(578, 28, 33, 8, 'Kamis', 5, 6, 'BJAW'),
(579, 28, 65, 8, 'Kamis', 7, 10, 'KRPL'),
(580, 28, 19, 9, 'Jumat', 2, 4, 'BIND'),
(581, 28, 18, 9, 'Jumat', 5, 7, 'PAIBP'),
(582, 28, 26, 8, 'Jumat', 8, 10, 'KRPL'),
(583, 28, 47, 8, 'Jumat', 11, 12, 'KIK'),
(584, 29, 105, 41, 'Senin', 2, 3, 'BJPN'),
(585, 29, 40, 41, 'Senin', 4, 6, 'BIND'),
(586, 29, 41, 41, 'Senin', 7, 10, 'KTKJ'),
(587, 29, 34, 41, 'Selasa', 1, 2, 'BING'),
(588, 29, 106, 41, 'Selasa', 3, 5, 'KIK'),
(589, 29, 2, 2, 'Selasa', 6, 7, 'PJOK'),
(590, 29, 36, 41, 'Selasa', 8, 10, 'KTKJ'),
(591, 29, 101, 41, 'Rabu', 1, 2, 'SEJ'),
(592, 29, 34, 41, 'Rabu', 3, 4, 'BING'),
(593, 29, 35, 41, 'Rabu', 5, 6, 'MPTKJ'),
(594, 29, 8, 41, 'Rabu', 7, 8, 'KTKJ'),
(595, 29, 107, 41, 'Rabu', 9, 10, 'BJAW'),
(596, 29, 86, 41, 'Kamis', 1, 3, 'PAIBP'),
(597, 29, 38, 41, 'Kamis', 4, 5, 'BK'),
(598, 29, 108, 41, 'Kamis', 6, 10, 'KTKJ'),
(599, 29, 11, 41, 'Jumat', 2, 4, 'MTK'),
(600, 29, 67, 41, 'Jumat', 5, 8, 'KTKJ'),
(601, 29, 31, 41, 'Jumat', 9, 10, 'PPKN'),
(602, 29, 106, 41, 'Jumat', 11, 12, 'KIK'),
(603, 30, 107, 42, 'Senin', 2, 3, 'BJAW'),
(604, 30, 8, 43, 'Senin', 4, 5, 'KTKJ'),
(605, 30, 86, 42, 'Senin', 6, 8, 'PAIBP'),
(606, 30, 34, 42, 'Senin', 9, 10, 'BING'),
(607, 30, 24, 42, 'Selasa', 1, 3, 'MTK'),
(608, 30, 52, 42, 'Selasa', 4, 5, 'KIK'),
(609, 30, 108, 43, 'Selasa', 6, 10, 'KTKJ'),
(610, 30, 2, 2, 'Rabu', 1, 2, 'PJOK'),
(611, 30, 35, 43, 'Rabu', 3, 4, 'MPTKJ'),
(612, 30, 38, 42, 'Rabu', 5, 6, 'BK'),
(613, 30, 67, 43, 'Rabu', 7, 10, 'KTKJ'),
(614, 30, 101, 42, 'Kamis', 1, 2, 'SEJ'),
(615, 30, 34, 42, 'Kamis', 3, 4, 'BING'),
(616, 30, 36, 43, 'Kamis', 5, 7, 'KTKJ'),
(617, 30, 52, 42, 'Kamis', 8, 10, 'KIK'),
(618, 30, 31, 42, 'Jumat', 2, 3, 'PPKN'),
(619, 30, 40, 42, 'Jumat', 4, 6, 'BIND'),
(620, 30, 41, 43, 'Jumat', 7, 10, 'KTKJ'),
(621, 30, 105, 42, 'Jumat', 11, 12, 'BJPN'),
(622, 31, 37, 44, 'Senin', 2, 3, 'BJAW'),
(623, 31, 52, 44, 'Senin', 4, 6, 'KIK'),
(624, 31, 109, 44, 'Senin', 7, 10, 'MPBD'),
(625, 31, 29, 44, 'Selasa', 1, 2, 'PPKN'),
(626, 31, 53, 44, 'Selasa', 3, 4, 'BING'),
(627, 31, 110, 44, 'Selasa', 5, 7, 'KBD'),
(628, 31, 42, 44, 'Selasa', 8, 10, 'PAIBP'),
(629, 31, 52, 44, 'Rabu', 1, 2, 'KIK'),
(630, 31, 12, 44, 'Rabu', 3, 5, 'MTK'),
(631, 31, 49, 2, 'Rabu', 6, 7, 'PJOK'),
(632, 31, 106, 44, 'Rabu', 8, 10, 'KBD'),
(633, 31, 111, 44, 'Kamis', 1, 2, 'BK'),
(634, 31, 112, 44, 'Kamis', 3, 4, 'KBD'),
(635, 31, 113, 44, 'Kamis', 5, 8, 'KBD'),
(636, 31, 106, 44, 'Kamis', 9, 10, 'KBD'),
(637, 31, 110, 44, 'Jumat', 2, 3, 'KBD'),
(638, 31, 107, 44, 'Jumat', 4, 5, 'KBD'),
(639, 31, 114, 44, 'Jumat', 6, 7, 'SEJ'),
(640, 31, 90, 44, 'Jumat', 8, 10, 'BIND'),
(641, 31, 53, 44, 'Jumat', 11, 12, 'BING'),
(642, 32, 113, 45, 'Senin', 2, 5, 'KBD'),
(643, 32, 114, 45, 'Senin', 6, 7, 'SEJ'),
(644, 32, 90, 45, 'Senin', 8, 10, 'BIND'),
(645, 32, 12, 45, 'Selasa', 1, 3, 'MTK'),
(646, 32, 109, 45, 'Selasa', 4, 7, 'MPBD'),
(647, 32, 110, 45, 'Selasa', 8, 10, 'KBD'),
(648, 32, 49, 2, 'Rabu', 1, 2, 'PJOK'),
(649, 32, 106, 45, 'Rabu', 3, 4, 'KBD'),
(650, 32, 52, 45, 'Rabu', 5, 6, 'KIK'),
(651, 32, 112, 45, 'Rabu', 7, 8, 'KBD'),
(652, 32, 53, 45, 'Rabu', 9, 10, 'BING'),
(653, 32, 52, 45, 'Kamis', 1, 3, 'KIK'),
(654, 32, 106, 45, 'Kamis', 4, 6, 'KBD'),
(655, 32, 29, 45, 'Kamis', 7, 8, 'PPKN'),
(656, 32, 37, 45, 'Kamis', 9, 10, 'BJAW'),
(657, 32, 107, 45, 'Jumat', 2, 3, 'KBD'),
(658, 32, 42, 45, 'Jumat', 4, 6, 'PAIBP'),
(659, 32, 53, 45, 'Jumat', 7, 8, 'BING'),
(660, 32, 46, 45, 'Jumat', 9, 10, 'BK'),
(661, 32, 107, 45, 'Jumat', 11, 12, 'KBD'),
(662, 33, 114, 46, 'Senin', 2, 3, 'SEJ'),
(663, 33, 106, 46, 'Senin', 4, 7, 'MPBD'),
(664, 33, 52, 46, 'Senin', 8, 10, 'KIK'),
(665, 33, 46, 46, 'Selasa', 1, 2, 'BK'),
(666, 33, 107, 46, 'Selasa', 3, 4, 'KBD'),
(667, 33, 113, 46, 'Selasa', 5, 8, 'KBD'),
(668, 33, 107, 46, 'Selasa', 9, 10, 'BJAW'),
(669, 33, 106, 46, 'Rabu', 1, 2, 'KBD'),
(670, 33, 52, 46, 'Rabu', 3, 4, 'KIK'),
(671, 33, 110, 46, 'Rabu', 5, 7, 'KBD'),
(672, 33, 30, 46, 'Rabu', 8, 10, 'BIND'),
(673, 33, 53, 46, 'Kamis', 1, 2, 'BING'),
(674, 33, 55, 2, 'Kamis', 3, 4, 'PJOK'),
(675, 33, 92, 46, 'Kamis', 5, 7, 'MTK'),
(676, 33, 110, 46, 'Kamis', 8, 10, 'KBD'),
(677, 33, 54, 46, 'Jumat', 2, 4, 'PAIBP'),
(678, 33, 53, 46, 'Jumat', 5, 6, 'BING'),
(679, 33, 47, 46, 'Jumat', 7, 8, 'KBD'),
(680, 33, 107, 46, 'Jumat', 9, 10, 'KBD'),
(681, 33, 110, 46, 'Jumat', 11, 12, 'PPKN'),
(682, 34, 115, 17, 'Senin', 2, 5, 'KMP'),
(683, 34, 59, 47, 'Senin', 6, 8, 'BIND'),
(684, 34, 64, 47, 'Senin', 9, 10, 'MPMP'),
(685, 34, 115, 17, 'Selasa', 1, 2, 'KMP'),
(686, 34, 115, 17, 'Selasa', 3, 5, 'KMP'),
(687, 34, 54, 47, 'Selasa', 6, 8, 'PAIBP'),
(688, 34, 61, 47, 'Selasa', 9, 10, 'BING'),
(689, 34, 116, 47, 'Rabu', 1, 2, 'KIK'),
(690, 34, 115, 17, 'Rabu', 3, 6, 'KMP'),
(691, 34, 29, 47, 'Rabu', 7, 8, 'PPKN'),
(692, 34, 105, 47, 'Rabu', 9, 10, 'BJPN'),
(693, 34, 21, 2, 'Kamis', 1, 2, 'PJOK'),
(694, 34, 114, 47, 'Kamis', 3, 4, 'SEJ'),
(695, 34, 116, 47, 'Kamis', 5, 7, 'KIK'),
(696, 34, 33, 47, 'Kamis', 8, 10, 'MTK'),
(697, 34, 115, 17, 'Jumat', 2, 3, 'KMP'),
(698, 34, 115, 17, 'Jumat', 4, 6, 'KMP'),
(699, 34, 117, 47, 'Jumat', 7, 8, 'BK'),
(700, 34, 61, 47, 'Jumat', 9, 10, 'BING'),
(701, 34, 37, 47, 'Jumat', 11, 12, 'BJAW'),
(702, 35, 116, 48, 'Senin', 2, 3, 'KIK'),
(703, 35, 33, 48, 'Senin', 4, 6, 'MTK'),
(704, 35, 118, 17, 'Senin', 7, 10, 'KMP'),
(705, 35, 54, 48, 'Selasa', 1, 3, 'PAIBP'),
(706, 35, 21, 2, 'Selasa', 4, 5, 'PJOK'),
(707, 35, 118, 17, 'Selasa', 6, 8, 'KMP'),
(708, 35, 64, 48, 'Selasa', 9, 10, 'MPMP'),
(709, 35, 118, 17, 'Rabu', 1, 2, 'KMP'),
(710, 35, 105, 48, 'Rabu', 3, 4, 'BJPN'),
(711, 35, 29, 48, 'Rabu', 5, 6, 'PPKN'),
(712, 35, 118, 17, 'Rabu', 7, 8, 'KMP'),
(713, 35, 61, 48, 'Rabu', 9, 10, 'BING'),
(714, 35, 116, 48, 'Kamis', 1, 3, 'KIK'),
(715, 35, 37, 48, 'Kamis', 4, 5, 'BJAW'),
(716, 35, 114, 48, 'Kamis', 6, 7, 'SEJ'),
(717, 35, 118, 17, 'Kamis', 8, 10, 'KMP'),
(718, 35, 59, 48, 'Jumat', 2, 4, 'BIND'),
(719, 35, 61, 48, 'Jumat', 5, 6, 'BING'),
(720, 35, 118, 17, 'Jumat', 7, 10, 'KMP'),
(721, 35, 71, 48, 'Jumat', 11, 12, 'BK'),
(722, 36, 62, 49, 'Senin', 2, 4, 'MTK'),
(723, 36, 105, 49, 'Senin', 5, 6, 'BJPN'),
(724, 36, 119, 20, 'Senin', 7, 10, 'KMP'),
(725, 36, 116, 49, 'Selasa', 1, 3, 'KIK'),
(726, 36, 74, 49, 'Selasa', 4, 6, 'BIND'),
(727, 36, 119, 20, 'Selasa', 7, 8, 'KMP'),
(728, 36, 119, 20, 'Selasa', 9, 10, 'KMP'),
(729, 36, 21, 2, 'Rabu', 1, 2, 'PJOK'),
(730, 36, 119, 20, 'Rabu', 3, 6, 'KMP'),
(731, 36, 79, 49, 'Rabu', 7, 8, 'BING'),
(732, 36, 29, 49, 'Rabu', 9, 10, 'PPKN'),
(733, 36, 54, 49, 'Kamis', 1, 3, 'PAIBP'),
(734, 36, 119, 20, 'Kamis', 4, 6, 'KMP'),
(735, 36, 117, 49, 'Kamis', 7, 8, 'BK'),
(736, 36, 64, 49, 'Kamis', 9, 10, 'MPMP'),
(737, 36, 114, 49, 'Jumat', 2, 3, 'SEJ'),
(738, 36, 23, 49, 'Jumat', 4, 5, 'BJAW'),
(739, 36, 116, 49, 'Jumat', 6, 7, 'KIK'),
(740, 36, 119, 20, 'Jumat', 8, 10, 'KMP'),
(741, 36, 79, 49, 'Jumat', 11, 12, 'BING'),
(742, 37, 100, 20, 'Senin', 2, 5, 'KMP'),
(743, 37, 117, 50, 'Senin', 6, 7, 'BK'),
(744, 37, 62, 50, 'Senin', 8, 10, 'MTK'),
(745, 37, 100, 20, 'Selasa', 1, 4, 'KMP'),
(746, 37, 114, 50, 'Selasa', 5, 6, 'SEJ'),
(747, 37, 1, 50, 'Selasa', 7, 8, 'BING'),
(748, 37, 23, 50, 'Selasa', 9, 10, 'BJAW'),
(749, 37, 1, 50, 'Rabu', 1, 2, 'BING'),
(750, 37, 116, 50, 'Rabu', 3, 5, 'KIK'),
(751, 37, 21, 2, 'Rabu', 6, 7, 'PJOK'),
(752, 37, 100, 20, 'Rabu', 8, 10, 'KMP'),
(753, 37, 100, 20, 'Kamis', 1, 3, 'KMP'),
(754, 37, 64, 50, 'Kamis', 4, 5, 'MPMP'),
(755, 37, 105, 50, 'Kamis', 6, 7, 'BJPN'),
(756, 37, 72, 50, 'Kamis', 8, 10, 'PAIBP'),
(757, 37, 29, 50, 'Jumat', 2, 3, 'PPKN'),
(758, 37, 116, 50, 'Jumat', 4, 5, 'KIK'),
(759, 37, 100, 20, 'Jumat', 6, 7, 'KMP'),
(760, 37, 74, 50, 'Jumat', 8, 10, 'BIND'),
(761, 37, 100, 20, 'Jumat', 11, 12, 'KMP'),
(762, 38, 120, 51, 'Senin', 2, 3, 'KAK'),
(763, 38, 114, 51, 'Senin', 4, 5, 'SEJ'),
(764, 38, 50, 51, 'Senin', 6, 8, 'MTK'),
(765, 38, 37, 51, 'Senin', 9, 10, 'BJAW'),
(766, 38, 49, 2, 'Selasa', 1, 2, 'PJOK'),
(767, 38, 81, 51, 'Selasa', 3, 4, 'BK'),
(768, 38, 78, 24, 'Selasa', 5, 10, 'KAK'),
(769, 38, 96, 51, 'Rabu', 1, 2, 'BING'),
(770, 38, 13, 51, 'Rabu', 3, 4, 'PPKN'),
(771, 38, 105, 51, 'Rabu', 5, 6, 'BJPN'),
(772, 38, 84, 51, 'Rabu', 7, 10, 'KAK'),
(773, 38, 72, 51, 'Kamis', 1, 3, 'PAIBP'),
(774, 38, 77, 51, 'Kamis', 4, 5, 'KAK'),
(775, 38, 120, 24, 'Kamis', 6, 10, 'KIK'),
(776, 38, 96, 51, 'Jumat', 2, 3, 'BING'),
(777, 38, 121, 24, 'Jumat', 4, 7, 'KAK'),
(778, 38, 19, 51, 'Jumat', 8, 10, 'BIND'),
(779, 38, 76, 51, 'Jumat', 11, 12, 'MPAK'),
(780, 39, 121, 24, 'Senin', 2, 5, 'KAK'),
(781, 39, 72, 52, 'Senin', 6, 8, 'PAIBP'),
(782, 39, 105, 52, 'Senin', 9, 10, 'BJPN'),
(783, 39, 114, 52, 'Selasa', 1, 2, 'SEJ'),
(784, 39, 49, 2, 'Selasa', 3, 4, 'PJOK'),
(785, 39, 76, 52, 'Selasa', 5, 6, 'MPAK'),
(786, 39, 76, 52, 'Selasa', 7, 10, 'KAK'),
(787, 39, 43, 24, 'Rabu', 1, 6, 'KAK'),
(788, 39, 96, 52, 'Rabu', 7, 8, 'BING'),
(789, 39, 37, 52, 'Rabu', 9, 10, 'BJAW'),
(790, 39, 120, 24, 'Kamis', 1, 5, 'KIK'),
(791, 39, 19, 52, 'Kamis', 6, 8, 'BIND'),
(792, 39, 83, 52, 'Kamis', 9, 10, 'KAK'),
(793, 39, 13, 52, 'Jumat', 2, 3, 'PPKN'),
(794, 39, 92, 52, 'Jumat', 4, 6, 'MTK'),
(795, 39, 96, 52, 'Jumat', 7, 8, 'BING'),
(796, 39, 120, 52, 'Jumat', 9, 10, 'KAK'),
(797, 39, 94, 52, 'Jumat', 11, 12, 'BK'),
(798, 40, 72, 53, 'Senin', 2, 4, 'PAIBP'),
(799, 40, 49, 2, 'Senin', 5, 6, 'PJOK'),
(800, 40, 75, 53, 'Senin', 7, 10, 'KAK'),
(801, 40, 19, 53, 'Selasa', 1, 3, 'BIND'),
(802, 40, 122, 23, 'Selasa', 4, 8, 'KIK'),
(803, 40, 83, 53, 'Selasa', 9, 10, 'KAK'),
(804, 40, 75, 23, 'Rabu', 1, 6, 'KAK'),
(805, 40, 76, 53, 'Rabu', 7, 8, 'MPAK'),
(806, 40, 23, 53, 'Rabu', 9, 10, 'BJAW'),
(807, 40, 114, 53, 'Kamis', 1, 2, 'SEJ'),
(808, 40, 105, 53, 'Kamis', 3, 4, 'BJPN'),
(809, 40, 121, 23, 'Kamis', 5, 8, 'KAK'),
(810, 40, 79, 53, 'Kamis', 9, 10, 'BING'),
(811, 40, 12, 53, 'Jumat', 2, 4, 'MTK'),
(812, 40, 78, 53, 'Jumat', 5, 6, 'KAK'),
(813, 40, 13, 53, 'Jumat', 7, 8, 'PPKN'),
(814, 40, 79, 53, 'Jumat', 9, 10, 'BING'),
(815, 40, 111, 53, 'Jumat', 11, 12, 'BK'),
(816, 41, 122, 23, 'Senin', 2, 6, 'KIK'),
(817, 41, 105, 54, 'Senin', 7, 8, 'BJPN'),
(818, 41, 79, 54, 'Senin', 9, 10, 'BING'),
(819, 41, 76, 54, 'Selasa', 1, 2, 'MPAK'),
(820, 41, 114, 54, 'Selasa', 3, 4, 'SEJ'),
(821, 41, 111, 54, 'Selasa', 5, 6, 'BK'),
(822, 41, 75, 54, 'Selasa', 7, 10, 'KAK'),
(823, 41, 23, 54, 'Rabu', 1, 2, 'BJAW'),
(824, 41, 79, 54, 'Rabu', 3, 4, 'BING'),
(825, 41, 13, 54, 'Rabu', 5, 6, 'PPKN'),
(826, 41, 122, 54, 'Rabu', 7, 8, 'KAK'),
(827, 41, 78, 54, 'Rabu', 9, 10, 'KAK'),
(828, 41, 82, 23, 'Kamis', 1, 4, 'KAK'),
(829, 41, 57, 54, 'Kamis', 5, 7, 'BIND'),
(830, 41, 39, 54, 'Kamis', 8, 10, 'MTK'),
(831, 41, 49, 2, 'Jumat', 2, 3, 'PJOK'),
(832, 41, 84, 23, 'Jumat', 4, 9, 'KAK'),
(833, 41, 10, 54, 'Jumat', 10, 12, 'PAIBP'),
(834, 42, 59, 29, 'Senin', 2, 4, 'BIND'),
(835, 42, 85, 55, 'Senin', 5, 8, 'KULW'),
(836, 42, 17, 29, 'Senin', 9, 10, 'BING'),
(837, 42, 17, 29, 'Selasa', 1, 2, 'BING'),
(838, 42, 85, 55, 'Selasa', 3, 6, 'KULW'),
(839, 42, 123, 55, 'Selasa', 7, 8, 'KIK'),
(840, 42, 52, 29, 'Selasa', 9, 10, 'SEJ'),
(841, 42, 105, 29, 'Rabu', 1, 2, 'BJPN'),
(842, 42, 123, 29, 'Rabu', 3, 5, 'KIK'),
(843, 42, 123, 29, 'Rabu', 6, 8, 'KULW'),
(844, 42, 92, 29, 'Rabu', 9, 10, 'BJAW'),
(845, 42, 55, 2, 'Kamis', 1, 2, 'PJOK'),
(846, 42, 80, 29, 'Kamis', 3, 5, 'MTK'),
(847, 42, 123, 29, 'Kamis', 6, 8, 'KULW'),
(848, 42, 105, 29, 'Kamis', 9, 10, 'BJPN'),
(849, 42, 10, 29, 'Jumat', 2, 4, 'PAIBP'),
(850, 42, 85, 55, 'Jumat', 5, 8, 'KULW'),
(851, 42, 124, 29, 'Jumat', 9, 10, 'BK'),
(852, 42, 13, 29, 'Jumat', 11, 12, 'PPKN'),
(853, 43, 32, 56, 'Senin', 2, 3, 'BING'),
(854, 43, 87, 31, 'Senin', 4, 6, 'KDKV'),
(855, 43, 125, 31, 'Senin', 7, 10, 'KDKV'),
(856, 43, 88, 56, 'Selasa', 1, 3, 'KDKV'),
(857, 43, 89, 56, 'Selasa', 4, 6, 'KDKV'),
(858, 43, 126, 56, 'Selasa', 7, 8, 'KIK'),
(859, 43, 22, 56, 'Selasa', 9, 10, 'SEJ'),
(860, 43, 81, 56, 'Rabu', 1, 2, 'BK'),
(861, 43, 89, 31, 'Rabu', 3, 5, 'KDKV'),
(862, 43, 87, 31, 'Rabu', 6, 7, 'KDKV'),
(863, 43, 126, 57, 'Rabu', 8, 10, 'KIK'),
(864, 43, 2, 2, 'Kamis', 1, 2, 'PJOK'),
(865, 43, 89, 56, 'Kamis', 3, 4, 'BJAW'),
(866, 43, 74, 56, 'Kamis', 5, 7, 'BIND'),
(867, 43, 24, 56, 'Kamis', 8, 10, 'MTK'),
(868, 43, 91, 31, 'Jumat', 2, 5, 'MPDKV'),
(869, 43, 32, 56, 'Jumat', 6, 7, 'BING'),
(870, 43, 42, 56, 'Jumat', 8, 10, 'PAIBP'),
(871, 43, 29, 56, 'Jumat', 11, 12, 'PPKN'),
(872, 44, 81, 58, 'Senin', 2, 3, 'BK'),
(873, 44, 89, 58, 'Senin', 4, 6, 'KDKV'),
(874, 44, 89, 58, 'Senin', 7, 8, 'BJAW'),
(875, 44, 29, 58, 'Senin', 9, 10, 'PPKN'),
(876, 44, 89, 31, 'Selasa', 1, 3, 'KDKV'),
(877, 44, 125, 31, 'Selasa', 4, 7, 'KDKV'),
(878, 44, 87, 31, 'Selasa', 8, 10, 'KDKV'),
(879, 44, 87, 58, 'Rabu', 1, 2, 'KDKV'),
(880, 44, 74, 58, 'Rabu', 3, 5, 'BIND'),
(881, 44, 2, 2, 'Rabu', 6, 7, 'PJOK'),
(882, 44, 24, 58, 'Rabu', 8, 10, 'MTK'),
(883, 44, 126, 58, 'Kamis', 1, 3, 'KIK'),
(884, 44, 22, 58, 'Kamis', 4, 5, 'SEJ'),
(885, 44, 32, 58, 'Kamis', 6, 7, 'BING'),
(886, 44, 42, 58, 'Kamis', 8, 10, 'PAIBP'),
(887, 44, 126, 58, 'Jumat', 2, 3, 'KIK'),
(888, 44, 32, 58, 'Jumat', 4, 5, 'BING'),
(889, 44, 91, 31, 'Jumat', 6, 9, 'MPDKV'),
(890, 44, 88, 58, 'Jumat', 10, 12, 'KDKV'),
(891, 45, 30, 59, 'Senin', 2, 4, 'BIND'),
(892, 45, 93, 60, 'Senin', 5, 6, 'KPSPT'),
(893, 45, 127, 61, 'Senin', 7, 10, 'KPSPT'),
(894, 45, 95, 60, 'Selasa', 1, 2, 'KPSPT'),
(895, 45, 95, 60, 'Selasa', 3, 4, 'KPSPT'),
(896, 45, 127, 61, 'Selasa', 5, 6, 'KPSPT'),
(897, 45, 127, 61, 'Selasa', 7, 8, 'KPSPT'),
(898, 45, 94, 59, 'Selasa', 9, 10, 'BK'),
(899, 45, 97, 60, 'Rabu', 1, 4, 'KPSPT'),
(900, 45, 32, 59, 'Rabu', 5, 6, 'BING'),
(901, 45, 31, 59, 'Rabu', 7, 8, 'PPKN'),
(902, 45, 128, 59, 'Rabu', 9, 10, 'KIK'),
(903, 45, 97, 60, 'Kamis', 1, 4, 'MPPSPT'),
(904, 45, 86, 59, 'Kamis', 5, 7, 'PAIBP'),
(905, 45, 92, 59, 'Kamis', 8, 10, 'MTK'),
(906, 45, 32, 59, 'Jumat', 2, 3, 'BING'),
(907, 45, 114, 59, 'Jumat', 4, 5, 'SEJ'),
(908, 45, 55, 2, 'Jumat', 6, 7, 'PJOK'),
(909, 45, 52, 59, 'Jumat', 8, 9, 'BJAW'),
(910, 45, 128, 59, 'Jumat', 10, 12, 'KIK'),
(911, 46, 50, 62, 'Senin', 2, 4, 'MTK'),
(912, 46, 128, 62, 'Senin', 5, 6, 'KIK'),
(913, 46, 97, 60, 'Senin', 7, 10, 'KPSPT'),
(914, 46, 86, 62, 'Selasa', 1, 3, 'PAIBP'),
(915, 46, 128, 62, 'Selasa', 4, 6, 'KIK'),
(916, 46, 114, 62, 'Selasa', 7, 8, 'SEJ'),
(917, 46, 1, 62, 'Selasa', 9, 10, 'BING'),
(918, 46, 127, 61, 'Rabu', 1, 4, 'KPSPT'),
(919, 46, 97, 60, 'Rabu', 5, 8, 'MPPSPT'),
(920, 46, 13, 62, 'Rabu', 9, 10, 'PPKN'),
(921, 46, 127, 61, 'Kamis', 1, 2, 'KPSPT'),
(922, 46, 127, 61, 'Kamis', 3, 4, 'KPSPT'),
(923, 46, 95, 60, 'Kamis', 5, 6, 'KPSPT'),
(924, 46, 95, 60, 'Kamis', 7, 8, 'KPSPT'),
(925, 46, 93, 60, 'Kamis', 9, 10, 'KPSPT'),
(926, 46, 55, 2, 'Jumat', 2, 3, 'PJOK'),
(927, 46, 94, 62, 'Jumat', 4, 5, 'BK'),
(928, 46, 30, 62, 'Jumat', 6, 8, 'BIND'),
(929, 46, 1, 62, 'Jumat', 9, 10, 'BING'),
(930, 46, 52, 62, 'Jumat', 11, 12, 'BJAW'),
(931, 47, 45, 63, 'Senin', 2, 4, 'BIND'),
(932, 47, 96, 63, 'Senin', 5, 6, 'BING'),
(933, 47, 129, 36, 'Senin', 7, 8, 'KAN'),
(934, 47, 91, 36, 'Senin', 9, 10, 'MPAN'),
(935, 47, 130, 38, 'Selasa', 1, 3, 'KAN'),
(936, 47, 98, 38, 'Selasa', 4, 7, 'KAN'),
(937, 47, 99, 38, 'Selasa', 8, 10, 'KAN'),
(938, 47, 91, 38, 'Rabu', 1, 3, 'KAN'),
(939, 47, 129, 38, 'Rabu', 4, 6, 'KIK'),
(940, 47, 4, 63, 'Rabu', 7, 8, 'SEJ'),
(941, 47, 6, 63, 'Rabu', 9, 10, 'PPKN'),
(942, 47, 130, 38, 'Kamis', 1, 3, 'KAN'),
(943, 47, 129, 38, 'Kamis', 4, 5, 'KIK'),
(944, 47, 10, 37, 'Kamis', 6, 8, 'PAIBP'),
(945, 47, 96, 37, 'Kamis', 9, 10, 'BING'),
(946, 47, 129, 36, 'Jumat', 2, 3, 'MPAN'),
(947, 47, 55, 2, 'Jumat', 4, 5, 'PJOK'),
(948, 47, 80, 63, 'Jumat', 6, 8, 'MTK'),
(949, 47, 71, 63, 'Jumat', 9, 10, 'BK'),
(950, 47, 92, 63, 'Jumat', 11, 12, 'BJAW'),
(951, 48, 99, 38, 'Senin', 2, 4, 'KAN'),
(952, 48, 98, 38, 'Senin', 5, 8, 'KAN'),
(953, 48, 129, 38, 'Senin', 9, 10, 'KAN'),
(954, 48, 129, 36, 'Selasa', 1, 3, 'KIK'),
(955, 48, 91, 36, 'Selasa', 4, 5, 'MPAN'),
(956, 48, 96, 63, 'Selasa', 6, 7, 'BING'),
(957, 48, 45, 63, 'Selasa', 8, 10, 'BIND'),
(958, 48, 107, 63, 'Rabu', 1, 2, 'BJAW'),
(959, 48, 55, 2, 'Rabu', 3, 4, 'PJOK'),
(960, 48, 91, 36, 'Rabu', 5, 7, 'KAN'),
(961, 48, 130, 38, 'Rabu', 8, 10, 'KAN'),
(962, 48, 71, 63, 'Kamis', 1, 2, 'BK'),
(963, 48, 10, 63, 'Kamis', 3, 5, 'PAIBP'),
(964, 48, 80, 63, 'Kamis', 6, 8, 'MTK'),
(965, 48, 4, 63, 'Kamis', 9, 10, 'SEJ'),
(966, 48, 130, 38, 'Jumat', 2, 4, 'KAN'),
(967, 48, 129, 38, 'Jumat', 5, 6, 'MPAN'),
(968, 48, 129, 38, 'Jumat', 7, 8, 'KIK'),
(969, 48, 13, 37, 'Jumat', 9, 10, 'PPKN'),
(970, 48, 96, 37, 'Jumat', 11, 12, 'BING');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_piket_guru`
--

CREATE TABLE `jadwal_piket_guru` (
  `tanggal` date NOT NULL,
  `id_guru` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Satu guru piket per hari; ubah PK jika sekolah butuh lebih dari satu';

--
-- Dumping data for table `jadwal_piket_guru`
--

INSERT INTO `jadwal_piket_guru` (`tanggal`, `id_guru`) VALUES
('2026-08-24', 3),
('2026-08-25', 17),
('2026-08-26', 40),
('2026-08-27', 74),
('2026-08-28', 90);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_piket_guru_detail`
--

CREATE TABLE `jadwal_piket_guru_detail` (
  `id_piket` bigint NOT NULL,
  `tanggal` date NOT NULL,
  `id_guru` int NOT NULL,
  `shift` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_piket_kesiswaan`
--

CREATE TABLE `jadwal_piket_kesiswaan` (
  `tanggal` date NOT NULL,
  `id_guru` int NOT NULL COMMENT 'Harus salah satu guru pemegang kewenangan Kesiswaan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_piket_kesiswaan`
--

INSERT INTO `jadwal_piket_kesiswaan` (`tanggal`, `id_guru`) VALUES
('2026-08-24', 8),
('2026-08-25', 8),
('2026-08-26', 8),
('2026-08-27', 8),
('2026-08-28', 8);

-- --------------------------------------------------------

--
-- Table structure for table `jam_pelajaran`
--

CREATE TABLE `jam_pelajaran` (
  `jam_ke` int NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_pelajaran`
--

INSERT INTO `jam_pelajaran` (`jam_ke`, `waktu_mulai`, `waktu_selesai`) VALUES
(1, '07:00:00', '07:40:00'),
(2, '07:40:00', '08:20:00'),
(3, '08:20:00', '09:00:00'),
(4, '09:00:00', '09:40:00'),
(5, '10:00:00', '10:40:00'),
(6, '10:40:00', '11:20:00'),
(7, '11:20:00', '12:00:00'),
(8, '13:00:00', '13:40:00'),
(9, '13:40:00', '14:20:00'),
(10, '14:20:00', '15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_detail_ketidakhadiran`
--

CREATE TABLE `jurnal_detail_ketidakhadiran` (
  `id_detail` int NOT NULL,
  `id_jurnal` int NOT NULL,
  `id_siswa` varchar(10) NOT NULL,
  `keterangan` enum('Sakit','Izin','Alpa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurnal_detail_ketidakhadiran`
--

INSERT INTO `jurnal_detail_ketidakhadiran` (`id_detail`, `id_jurnal`, `id_siswa`, `keterangan`) VALUES
(1, 5, '000014', 'Izin');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_dokumentasi`
--

CREATE TABLE `jurnal_dokumentasi` (
  `id_dokumentasi` bigint NOT NULL,
  `id_jurnal` int NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_file` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ukuran_byte` bigint DEFAULT NULL,
  `diunggah_oleh` int DEFAULT NULL,
  `diunggah_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_mengajar`
--

CREATE TABLE `jurnal_mengajar` (
  `id_jurnal` int NOT NULL,
  `id_jadwal` int NOT NULL,
  `tanggal` date NOT NULL,
  `materi` text,
  `status_kehadiran_guru` enum('Hadir','Izin','Sakit','Tanpa Keterangan') NOT NULL DEFAULT 'Hadir',
  `catatan` text,
  `foto_kegiatan` varchar(255) DEFAULT NULL,
  `surat_izin` varchar(255) DEFAULT NULL,
  `dicatat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurnal_mengajar`
--

INSERT INTO `jurnal_mengajar` (`id_jurnal`, `id_jadwal`, `tanggal`, `materi`, `status_kehadiran_guru`, `catatan`, `foto_kegiatan`, `surat_izin`, `dicatat_pada`) VALUES
(1, 556, '2026-07-29', 'Pengenalan konsep Object-Oriented Programming (OOP): class, object, attribute, dan method beserta implementasi sederhana menggunakan Java.', 'Hadir', 'Pembelajaran berjalan dengan baik. Siswa aktif berdiskusi dan praktik membuat class sederhana.', NULL, NULL, '2026-07-29 01:16:52'),
(2, 557, '2026-07-29', 'Reading Comprehension: Understanding Descriptive and Procedure Text serta latihan vocabulary dan pronunciation.', 'Hadir', 'Sebagian besar siswa mampu memahami isi bacaan dan aktif menjawab pertanyaan.', NULL, NULL, '2026-07-29 01:16:52'),
(3, 558, '2026-07-29', 'Perkembangan Nasionalisme Indonesia pada Masa Pergerakan Nasional serta tokoh-tokoh penting.', 'Hadir', 'Diskusi kelas berlangsung aktif. Siswa mampu menghubungkan materi dengan kondisi bangsa saat ini.', NULL, NULL, '2026-07-29 01:16:52'),
(4, 559, '2026-07-29', 'Teks Pidhato Basa Jawa: struktur, unggah-ungguh basa, dan praktik menyusun pidato sederhana.', 'Hadir', 'Siswa mengikuti pembelajaran dengan baik dan mampu menyusun kerangka pidato sederhana.', NULL, NULL, '2026-07-29 01:16:52'),
(5, 557, '2026-08-06', 'bunga melati', 'Hadir', NULL, 'jurnal/foto/DGGBpVPLTCAWbXf3l9Jt65zUHa0sVjHFWIaAIF2w.png', NULL, '2026-08-06 02:47:28'),
(6, 241, '2026-08-06', 'ngantukl', 'Hadir', NULL, 'jurnal/foto/EcKZeLzOnxUCMpytGsnKMGHPNQMdBrkONQurvCyT.jpg', NULL, '2026-08-06 03:14:45');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(30) NOT NULL,
  `wali_kelas` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jumlah_siswa` int NOT NULL DEFAULT '36'
) ;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `wali_kelas`, `jumlah_siswa`) VALUES
(1, 'X TKI 1', '9900045', 36),
(2, 'X TKI 2', '9900078', 36),
(3, 'X RPL 1', '9900097', 36),
(4, 'X RPL 2', '9900096', 36),
(5, 'X TKJ 1', '9900010', 36),
(6, 'X TKJ 2', '9900011', 36),
(7, 'X BD 1', '9900048', 36),
(8, 'X BD 2', '9900023', 36),
(9, 'X BD 3', '9900033', 36),
(10, 'X MP 1', '9900040', 36),
(11, 'X MP 2', '9900071', 36),
(12, 'X MP 3', '9900074', 36),
(13, 'X MP 4', '9900061', 36),
(14, 'X AK 1', '9900082', 36),
(15, 'X AK 2', '9900011', 36),
(16, 'X AK 3', '9900015', 36),
(17, 'X AK 4', '9900091', 36),
(18, 'X ULW', '9900058', 36),
(19, 'X DKV 1', '9900091', 36),
(20, 'X DKV 2', '9900042', 36),
(21, 'X PSPT 1', '9900001', 36),
(22, 'X PSPT 2', '9900032', 36),
(23, 'X AN 1', '9900083', 36),
(24, 'X AN 2', '9900056', 36),
(25, 'XI TKI 1', '9900074', 36),
(26, 'XI TKI 2', '9900098', 36),
(27, 'XI RPL 1', '9900105', 31),
(28, 'XI RPL 2', '9900097', 36),
(29, 'XI TKJ 1', '9900028', 36),
(30, 'XI TKJ 2', '9900017', 36),
(31, 'XI BD 1', '9900034', 36),
(32, 'XI BD 2', '9900010', 36),
(33, 'XI BD 3', '9900059', 36),
(34, 'XI MP 1', '9900059', 36),
(35, 'XI MP 2', '9900013', 36),
(36, 'XI MP 3', '9900093', 36),
(37, 'XI MP 4', '9900037', 36),
(38, 'XI AK 1', '9900092', 36),
(39, 'XI AK 2', '9900057', 36),
(40, 'XI AK 3', '9900040', 36),
(41, 'XI AK 4', '9900069', 36),
(42, 'XI ULW', '9900089', 36),
(43, 'XI DKV 1', '9900009', 36),
(44, 'XI DKV 2', '9900073', 36),
(45, 'XI PSPT 1', '9900075', 36),
(46, 'XI PSPT 2', '9900005', 36),
(47, 'XI AN 1', '9900086', 36),
(48, 'XI AN 2', '9900075', 36);

-- --------------------------------------------------------

--
-- Table structure for table `kewenangan_pimpinan`
--

CREATE TABLE `kewenangan_pimpinan` (
  `id_guru` int NOT NULL,
  `jenis_kewenangan` enum('Kurikulum','Kesiswaan','SDM','Kepala Sekolah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mulai_berlaku` date NOT NULL,
  `selesai_berlaku` date DEFAULT NULL COMMENT 'NULL = masih aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kewenangan_pimpinan`
--

INSERT INTO `kewenangan_pimpinan` (`id_guru`, `jenis_kewenangan`, `mulai_berlaku`, `selesai_berlaku`) VALUES
(8, 'Kesiswaan', '2026-07-14', NULL),
(36, 'Kurikulum', '2026-07-14', NULL),
(116, 'Kepala Sekolah', '2026-07-14', NULL),
(127, 'SDM', '2026-07-14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_mapel` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`kode_mapel`, `nama_mapel`) VALUES
('BIND', 'Bahasa Indonesia'),
('BING', 'Bahasa Inggris'),
('BJAW', 'Bahasa Jawa'),
('BJPN', 'Bahasa Jepang'),
('BK', 'BK'),
('DAKL', 'Dasar AKL'),
('DAN', 'Dasar AN'),
('DBP', 'Dasar BP'),
('DDKV', 'Dasar DKV'),
('DMPLB', 'Dasar MPLB'),
('DPM', 'Dasar PM'),
('DPPLG', 'Dasar PPLG'),
('DTJKT', 'Dasar TJKT'),
('DTKI', 'Dasar TKI'),
('DULP', 'Dasar ULP'),
('INF', 'Informatika'),
('IPAS', 'IPAS'),
('KAK', 'Konsentrasi AK'),
('KAN', 'Konsentrasi AN'),
('KBD', 'Konsentrasi BD'),
('KDKV', 'Konsentrasi DKV'),
('KIK', 'Kreativitas, Inovasi, dan Kewirausahaan'),
('KKA', 'Koding dan Kecerdasan Artifisial'),
('KMP', 'Konsentrasi MP'),
('KPSPT', 'Konsentrasi PSPT'),
('KRPL', 'Konsentrasi RPL'),
('KTKI', 'Konsentrasi TKI'),
('KTKJ', 'Konsentrasi TKJ'),
('KULW', 'Konsentrasi ULW'),
('MPAK', 'Mapel Pilihan AK'),
('MPAN', 'Mapel Pilihan AN'),
('MPBD', 'Mapel Pilihan BD'),
('MPDKV', 'Mapel Pilihan DKV'),
('MPMP', 'Mapel Pilihan MP'),
('MPPSPT', 'Mapel Pilihan PSPT'),
('MPRPL', 'Mapel Pilihan RPL'),
('MPTKI', 'Mapel Pilihan TKI'),
('MPTKJ', 'Mapel Pilihan TKJ'),
('MTK', 'Matematika'),
('PAIBP', 'Pendidikan Agama Islam dan Budi Pekerti'),
('PJOK', 'PJOK'),
('PPKN', 'Pendidikan Pancasila'),
('SBUD', 'Seni Budaya'),
('SEJ', 'Sejarah');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `monitoring_kelas`
--

CREATE TABLE `monitoring_kelas` (
  `id_monitoring` bigint NOT NULL,
  `id_pertemuan` bigint NOT NULL,
  `status` enum('Normal','Guru Terlambat','Guru Tidak Hadir','Kelas Kosong','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `diperiksa_oleh` int NOT NULL,
  `diperiksa_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `catatan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `judul` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('Info','Peringatan','Persetujuan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Info',
  `tabel_terkait` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_terkait` int DEFAULT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_pengguna`, `judul`, `pesan`, `jenis`, `tabel_terkait`, `id_terkait`, `dibaca`, `dibuat_pada`) VALUES
(1, 2, 'Izin Siswa Disetujui', 'Izin siswa ABID RIZKY NATANULLOH (000001) telah disetujui, siap divalidasi di gerbang.', 'Persetujuan', 'izin_siswa', 1, 0, '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

CREATE TABLE `orang_tua` (
  `id_ortu` int NOT NULL,
  `nama_ortu` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orang_tua`
--

INSERT INTO `orang_tua` (`id_ortu`, `nama_ortu`, `no_hp`) VALUES
(1, 'Wali dari ABID RIZKY NATANULLOH', '081234560001'),
(2, 'Wali dari KEYLLA PRISCYLIA PUTRI HARIANSYAH', '081234560002');

-- --------------------------------------------------------

--
-- Table structure for table `ortu_siswa`
--

CREATE TABLE `ortu_siswa` (
  `id_ortu` int NOT NULL,
  `nis_siswa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many: satu ortu bisa berelasi ke lebih dari satu siswa (kakak-adik)';

--
-- Dumping data for table `ortu_siswa`
--

INSERT INTO `ortu_siswa` (`id_ortu`, `nis_siswa`) VALUES
(1, '000001'),
(2, '000027');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggaran_siswa`
--

CREATE TABLE `pelanggaran_siswa` (
  `id_pelanggaran` bigint NOT NULL,
  `nis_siswa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` enum('Ringan','Sedang','Berat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ringan',
  `kejadian_pada` datetime NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dicatat_oleh` int NOT NULL,
  `status` enum('Tercatat','Ditindaklanjuti','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tercatat',
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','guru_piket','bk','waka','kepala_sekolah','satpam','orang_tua') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru` int DEFAULT NULL,
  `id_ortu` int DEFAULT NULL,
  `nama_tampilan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dipakai untuk role tanpa id_guru/id_ortu (admin, satpam)',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password_hash`, `role`, `id_guru`, `id_ortu`, `nama_tampilan`, `aktif`, `dibuat_pada`) VALUES
(1, 'admin_tu', '$2y$10$contohHashPasswordAdmin', 'admin', NULL, NULL, 'Bu Erna (TU)', 1, '2026-08-24 05:34:11'),
(2, 'satpam01', '$2y$10$contohHashPasswordSatpam', 'satpam', NULL, NULL, 'Pak Yanto', 1, '2026-08-24 05:34:11'),
(3, 'guru.0003', '$2y$10$contohHashPassword', 'guru', 3, NULL, NULL, 1, '2026-08-24 05:34:11'),
(4, 'guru.0008', '$2y$10$contohHashPassword', 'guru', 8, NULL, NULL, 1, '2026-08-24 05:34:11'),
(5, 'guru.0014', '$2y$10$contohHashPassword', 'guru', 14, NULL, NULL, 1, '2026-08-24 05:34:11'),
(6, 'guru.0036', '$2y$10$contohHashPassword', 'guru', 36, NULL, NULL, 1, '2026-08-24 05:34:11'),
(7, 'guru.0105', '$2y$10$contohHashPassword', 'guru', 105, NULL, NULL, 1, '2026-08-24 05:34:11'),
(8, 'guru.0116', '$2y$10$contohHashPassword', 'guru', 116, NULL, NULL, 1, '2026-08-24 05:34:11'),
(9, 'guru.0127', '$2y$10$contohHashPassword', 'guru', 127, NULL, NULL, 1, '2026-08-24 05:34:11'),
(10, 'ortu.000001', '$2y$10$contohHashPassword', 'orang_tua', NULL, 1, NULL, 1, '2026-08-24 05:34:11'),
(11, 'ortu.000027', '$2y$10$contohHashPassword', 'orang_tua', NULL, 2, NULL, 1, '2026-08-24 05:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `pertemuan`
--

CREATE TABLE `pertemuan` (
  `id_pertemuan` bigint NOT NULL,
  `id_jadwal` int NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai_aktual` datetime DEFAULT NULL,
  `waktu_selesai_aktual` datetime DEFAULT NULL,
  `status` enum('Terjadwal','Berlangsung','Selesai','Dibatalkan','Guru Tidak Hadir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Terjadwal',
  `dibuka_oleh` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pertemuan`
--

INSERT INTO `pertemuan` (`id_pertemuan`, `id_jadwal`, `tanggal`, `waktu_mulai_aktual`, `waktu_selesai_aktual`, `status`, `dibuka_oleh`, `catatan`, `dibuat_pada`) VALUES
(1, 556, '2026-07-29', NULL, NULL, 'Selesai', NULL, NULL, '2026-07-29 01:16:52'),
(2, 557, '2026-07-29', NULL, NULL, 'Selesai', NULL, NULL, '2026-07-29 01:16:52'),
(3, 558, '2026-07-29', NULL, NULL, 'Selesai', NULL, NULL, '2026-07-29 01:16:52'),
(4, 559, '2026-07-29', NULL, NULL, 'Selesai', NULL, NULL, '2026-07-29 01:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int NOT NULL,
  `nama_ruangan` varchar(60) NOT NULL,
  `jenis_ruangan` enum('Kelas Biasa','Lab','Ruang Praktik','Lainnya') NOT NULL DEFAULT 'Kelas Biasa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `jenis_ruangan`) VALUES
(1, 'Lab. KI 1', 'Lab'),
(2, 'Lapangan', 'Ruang Praktik'),
(3, 'Lab. TKJ 3', 'Lab'),
(4, 'Lab. Broadcast', 'Lab'),
(5, 'R 10', 'Kelas Biasa'),
(6, 'Lab. RPL 1', 'Lab'),
(7, 'R 57', 'Kelas Biasa'),
(8, 'Lab. RPL 2', 'Lab'),
(9, 'R 58', 'Kelas Biasa'),
(10, 'R 32', 'Kelas Biasa'),
(11, 'R 33', 'Kelas Biasa'),
(12, 'R 23', 'Kelas Biasa'),
(13, 'Lab. PM Belakang', 'Lab'),
(14, 'R 24', 'Kelas Biasa'),
(15, 'R 25', 'Kelas Biasa'),
(16, 'R 11', 'Kelas Biasa'),
(17, 'Lab. AP BAWAH', 'Lab'),
(18, 'R 12', 'Kelas Biasa'),
(19, 'R 13', 'Kelas Biasa'),
(20, 'Lab. AP ATAS', 'Lab'),
(21, 'R 14', 'Kelas Biasa'),
(22, 'R 1', 'Kelas Biasa'),
(23, 'Lab. AK ATAS', 'Lab'),
(24, 'Lab. AK BAWAH', 'Lab'),
(25, 'R 2', 'Kelas Biasa'),
(26, 'R 3', 'Kelas Biasa'),
(27, 'R 4', 'Kelas Biasa'),
(28, 'R 22', 'Kelas Biasa'),
(29, 'Lab. UPW 2 (Ticketing)', 'Lab'),
(30, 'R 15', 'Kelas Biasa'),
(31, 'Lab. DKV Komputer', 'Lab'),
(32, 'R 16', 'Kelas Biasa'),
(33, 'R 55', 'Kelas Biasa'),
(34, 'R 56', 'Kelas Biasa'),
(35, 'R 31', 'Kelas Biasa'),
(36, 'Lab. AN COE 2', 'Lab'),
(37, 'R 60', 'Kelas Biasa'),
(38, 'Lab. AN COE 1', 'Lab'),
(39, 'R 65', 'Kelas Biasa'),
(40, 'R 66', 'Kelas Biasa'),
(41, 'Lab. TKJ 1', 'Lab'),
(42, 'R 34', 'Kelas Biasa'),
(43, 'Lab. TKJ 2 (FO)', 'Lab'),
(44, 'R 26', 'Kelas Biasa'),
(45, 'R 61', 'Kelas Biasa'),
(46, 'Lab. BD Depan', 'Lab'),
(47, 'R 6', 'Kelas Biasa'),
(48, 'R 7', 'Kelas Biasa'),
(49, 'R 8', 'Kelas Biasa'),
(50, 'R 9', 'Kelas Biasa'),
(51, 'R 5', 'Kelas Biasa'),
(52, 'R 62', 'Kelas Biasa'),
(53, 'R 63', 'Kelas Biasa'),
(54, 'R 64', 'Kelas Biasa'),
(55, 'Lab. UPW 1 (Guiding)', 'Lab'),
(56, 'R 17', 'Kelas Biasa'),
(57, 'Lab. DKV Produksi', 'Lab'),
(58, 'R 18', 'Kelas Biasa'),
(59, 'R 40', 'Kelas Biasa'),
(60, 'Lab. PSPT Editing', 'Lab'),
(61, 'Lab. PSPT Studio', 'Lab'),
(62, 'R 41', 'Kelas Biasa'),
(63, 'R 59', 'Kelas Biasa');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` varchar(10) NOT NULL,
  `nisn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `ttl` varchar(255) DEFAULT NULL,
  `alamat` text,
  `id_kelas` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nisn`, `nama_siswa`, `ttl`, `alamat`, `id_kelas`, `deleted_at`) VALUES
('000001', '0094537732', 'ABID RIZKY NATANULLOH', NULL, NULL, 27, NULL),
('000002', '0095072637', 'AHMAD SANIM SIBTU YAHYA', NULL, NULL, 27, NULL),
('000003', '3096341242', 'AHMAD YAZRIL RIDHO FAJRIYA', NULL, NULL, 27, NULL),
('000004', '0094998661', 'AISIVA PUJIANSARI', NULL, NULL, 27, NULL),
('000005', '0099896424', 'AIZA HAYU PRAMUDYA', NULL, NULL, 27, NULL),
('000006', '0109611324', 'ALBERT FACHREZY WIDODO', NULL, NULL, 27, NULL),
('000007', '3090842341', 'ALMA LIATUL NURHALIZA', NULL, NULL, 27, NULL),
('000008', '3092681045', 'ALVARO ALGOZHALI', NULL, NULL, 27, NULL),
('000009', '0099166409', 'ARIEL WIJAYA SAPUTRA', NULL, NULL, 27, NULL),
('000010', '0096026768', 'AS SYIFA ACINTYA TANITH A.P', NULL, NULL, 27, NULL),
('000011', '0104537363', 'ASTITI FEBIANI SAMPURNA', NULL, NULL, 27, NULL),
('000012', '0093467860', 'ASYIFA NUR DWI PURWANTI', NULL, NULL, 27, NULL),
('000013', '0102439750', 'AWALISHA JUNY PURIPUTRI', NULL, NULL, 27, NULL),
('000014', '0098068449', 'AZIZ ARIANSYAH', NULL, NULL, 27, NULL),
('000015', '0093374852', 'DANESWARA PUWA HADI GAUTAMA', NULL, NULL, 27, NULL),
('000016', '0095119290', 'DEDI PERMANA', NULL, NULL, 27, NULL),
('000017', '0081359465', 'DIMAS SAIFUL', NULL, NULL, 27, NULL),
('000018', '0093469584', 'DITA PUTRI CAHYANI', NULL, NULL, 27, NULL),
('000019', '0097007164', 'ELGA BINTANG CAPUTRA', NULL, NULL, 27, NULL),
('000020', '0092003573', 'FACHRIZA ADITYA ALRIFQI', NULL, NULL, 27, NULL),
('000021', '0097600928', 'FANDY AHMAD RIYANTO', NULL, NULL, 27, NULL),
('000022', '3092000334', 'FARA AZILA TRISNA PUTRI', NULL, NULL, 27, NULL),
('000023', '0097530366', 'FELISA PUTRI MAHARANI', NULL, NULL, 27, NULL),
('000024', '0096084978', 'ILHAM WICAKSONO', NULL, NULL, 27, NULL),
('000025', '0082075177', 'IRFAN FANI SETIAWAN', NULL, NULL, 27, NULL),
('000026', '0094537974', 'ISTIQOMAH', NULL, NULL, 27, NULL),
('000027', '3098897489', 'KEYLLA PRISCYLIA PUTRI HARIANSYAH', NULL, NULL, 27, NULL),
('000028', '3091930944', 'KHANZA HAMIDA KHUMAIROH', NULL, NULL, 27, NULL),
('000029', '0099016906', 'KHAYARA MUKHBITA RAMADHINI SYAHPUTRA', NULL, NULL, 27, NULL),
('000030', '3092068422', 'MARCHA SUKMA KINANTI', NULL, NULL, 27, NULL),
('000031', '0106558253', 'MARDIANSYAH FANI PRATAMA', NULL, NULL, 27, NULL),
('1234567891', '0097007160', 'Dewangga', NULL, NULL, 42, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `nip_2` (`nip`),
  ADD KEY `guru_ibfk_1` (`kode_mapel`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD UNIQUE KEY `id_kelas` (`id_kelas`,`hari`,`jam_mulai`),
  ADD UNIQUE KEY `id_guru` (`id_guru`,`hari`,`jam_mulai`),
  ADD KEY `id_ruangan` (`id_ruangan`),
  ADD KEY `idx_jadwal_guru_hari` (`id_guru`,`hari`),
  ADD KEY `idx_jadwal_kelas_hari` (`id_kelas`,`hari`),
  ADD KEY `jadwal_ibfk_3` (`kode_mapel`);

--
-- Indexes for table `jam_pelajaran`
--
ALTER TABLE `jam_pelajaran`
  ADD PRIMARY KEY (`jam_ke`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal_detail_ketidakhadiran`
--
ALTER TABLE `jurnal_detail_ketidakhadiran`
  ADD PRIMARY KEY (`id_detail`),
  ADD UNIQUE KEY `id_jurnal` (`id_jurnal`,`id_siswa`),
  ADD KEY `jurnal_detail_ketidakhadiran_ibfk_2` (`id_siswa`);

--
-- Indexes for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD PRIMARY KEY (`id_jurnal`),
  ADD UNIQUE KEY `id_jadwal` (`id_jadwal`,`tanggal`),
  ADD KEY `idx_jurnal_tanggal` (`tanggal`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `nama_kelas` (`nama_kelas`),
  ADD KEY `wali_kelas` (`wali_kelas`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`kode_mapel`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `nama_ruangan` (`nama_ruangan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`),
  ADD UNIQUE KEY `nis` (`nisn`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_detail_ketidakhadiran`
--
ALTER TABLE `jurnal_detail_ketidakhadiran`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  MODIFY `id_jurnal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_4` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_detail_ketidakhadiran`
--
ALTER TABLE `jurnal_detail_ketidakhadiran`
  ADD CONSTRAINT `jurnal_detail_ketidakhadiran_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal_mengajar` (`id_jurnal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_detail_ketidakhadiran_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`nis`);

--
-- Constraints for table `jurnal_mengajar`
--
ALTER TABLE `jurnal_mengajar`
  ADD CONSTRAINT `jurnal_mengajar_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas`) REFERENCES `guru` (`nip`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
