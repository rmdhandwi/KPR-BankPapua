-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2025 at 06:34 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saw_kpr`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('kpr_bankpapua_cache_admin123|127.0.0.1', 'i:1;', 1752581297),
('kpr_bankpapua_cache_admin123|127.0.0.1:timer', 'i:1752581297;', 1752581297);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
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
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` bigint UNSIGNED NOT NULL,
  `nama_kriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` int NOT NULL,
  `jenis` enum('Benefit','Cost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Benefit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `jenis`, `created_at`, `updated_at`) VALUES
(1, 'Penghasilan', 20, 'Cost', '2025-07-16 02:56:43', '2025-07-16 03:49:37'),
(2, 'Riwayat Kredit', 20, 'Benefit', '2025-07-16 02:57:01', '2025-07-16 02:57:01'),
(3, 'Jumlah Tanggungan', 30, 'Benefit', '2025-07-16 02:57:19', '2025-07-16 02:57:19'),
(4, 'Kelengkapan Berkas', 10, 'Benefit', '2025-07-16 02:57:33', '2025-07-16 03:49:31'),
(5, 'Riwayat Pinjol', 20, 'Benefit', '2025-07-16 02:57:47', '2025-07-16 02:57:47');

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
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_01_161305_create_kriterias_table', 1),
(5, '2025_06_16_153758_create_rumahs_table', 1),
(6, '2025_07_12_023540_create_nasabahs_table', 1),
(7, '2025_07_12_035751_create_subkriterias_table', 1),
(8, '2025_07_12_041137_create_perhitungans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` bigint UNSIGNED NOT NULL,
  `id_rumah` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_ktp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_npwp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_perusahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_perusahaan` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan` enum('< Rp. 7.500.000','Rp. 7.600.000–8.000.000','Rp. 9.000.000-10.000.000','Rp. 10.000.000-15.000.000','> Rp. 15.000.000') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_tlp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kewarganegaraan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` enum('Belum Menikah','Sudah Menikah','Cerai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Menikah',
  `jml_tanggungan` enum('1 Orang','2-5 Orang','> 5 Orang') COLLATE utf8mb4_unicode_ci DEFAULT '1 Orang',
  `riwayat_pinjol` enum('Sedang Meminjam','Pernah Meminjam','Belum Pernah Meminjam') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pernah Meminjam',
  `riwayat_kredit` enum('Kredit Macet','Belum Pernah Kredit','Kredit Lancar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kredit Macet',
  `NPWP` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `KTP` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `surat_nikah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `spt_tahunan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kartu_keluarga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelengkapan_berkas` enum('Tidak Lengkap','Lengkap') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `id_rumah`, `nama_lengkap`, `no_ktp`, `no_kk`, `no_npwp`, `tempat_lahir`, `tgl_lahir`, `pekerjaan`, `nama_perusahan`, `alamat_perusahaan`, `email`, `penghasilan`, `no_tlp`, `kewarganegaraan`, `status_perkawinan`, `jml_tanggungan`, `riwayat_pinjol`, `riwayat_kredit`, `NPWP`, `KTP`, `surat_nikah`, `spt_tahunan`, `kartu_keluarga`, `kelengkapan_berkas`, `created_at`, `updated_at`) VALUES
(1, 2, 'Ahmad Syarifuddin', '5656565656565665', '6565656565656565', 'Fugiat esse et impe', 'Ab consequatur dolo', '2025-07-04', 'Harum molestiae sed', 'Veritatis exercitati', 'Sit itaque consequun', 'ahmadsyarifuddin@mailinator.com', 'Rp. 9.000.000-10.000.000', '232323232', 'WNI', 'Belum Menikah', '> 5 Orang', 'Sedang Meminjam', 'Belum Pernah Kredit', NULL, NULL, NULL, NULL, NULL, 'Lengkap', '2025-07-16 03:05:49', '2025-07-18 01:44:10'),
(2, 1, 'Ruben Mansawan', '6534534654656456', '4564565465464565', '4536546356', 'Ratione atque sunt n', '2025-07-04', 'Laboriosam voluptas', 'Ut aut similique dic', 'Dolor ab pariatur I', 'rubenmansawan@mailinator.com', 'Rp. 10.000.000-15.000.000', '3423423423434323', 'WNI', 'Cerai', '1 Orang', 'Belum Pernah Meminjam', 'Belum Pernah Kredit', NULL, NULL, NULL, NULL, NULL, 'Lengkap', '2025-07-16 03:07:41', '2025-07-17 23:21:24'),
(3, 2, 'Putri Novi Azalea', '3454353453545435', '3454353453454535', 'Eu dolores soluta ex', 'Duis excepturi repre', '2025-07-02', 'Ducimus in hic minu', 'Et laborum irure vel', 'Asperiores inventore', 'putrinoviazalea@mailinator.com', 'Rp. 9.000.000-10.000.000', '4342342342342344', 'WNI', 'Cerai', '2-5 Orang', 'Belum Pernah Meminjam', 'Belum Pernah Kredit', NULL, NULL, NULL, NULL, NULL, 'Lengkap', '2025-07-16 03:19:42', '2025-07-17 23:21:32'),
(4, 3, 'Marthen Dogopia', '4356445345345345', '5345454534534545', 'hdujshdus', 'dhushdguhsud', '2025-07-04', 'Autem nisi qui sit n', 'Velit odit ut nihil', 'Omnis duis officiis', 'marthendogopia@mailinator.com', '< Rp. 7.500.000', '3455345453453453', 'WNI', 'Sudah Menikah', '2-5 Orang', 'Belum Pernah Meminjam', 'Kredit Macet', NULL, NULL, NULL, NULL, NULL, 'Tidak Lengkap', '2025-07-16 03:23:14', '2025-07-17 23:21:53'),
(5, 2, 'Isak Pigai', '3453453453454534', '3425353453454353', 'Dolor aliqua Doloru', 'Fugit adipisci recu', '2025-07-12', 'Distinctio Ducimus', 'Laudantium enim max', 'Velit dolorum esse i', 'isakpigai@mailinator.com', 'Rp. 7.600.000–8.000.000', '3453454534543534', 'WNI', 'Belum Menikah', '> 5 Orang', 'Belum Pernah Meminjam', 'Kredit Lancar', NULL, NULL, NULL, NULL, NULL, 'Lengkap', '2025-07-16 03:26:37', '2025-07-17 23:22:30'),
(7, 2, 'Consequatur Elit a', '3453454534534534', '5234543534534534', 'Sint sit tempor nost', 'Debitis dolores omni', '2025-07-03', 'Sequi autem beatae r', 'Ipsa expedita lorem', 'Nesciunt laudantium', 'jowop@mailinator.com', 'Rp. 9.000.000-10.000.000', 'Ad hic dolor blandit', 'Numquam repudiandae', 'Sudah Menikah', '2-5 Orang', 'Pernah Meminjam', 'Kredit Macet', NULL, NULL, NULL, NULL, NULL, 'Lengkap', '2025-07-16 03:27:48', '2025-07-16 03:28:07');

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
-- Table structure for table `perhitungan`
--

CREATE TABLE `perhitungan` (
  `id_perhitungan` bigint UNSIGNED NOT NULL,
  `id_nasabah` bigint UNSIGNED NOT NULL,
  `skor_akhir` decimal(8,2) NOT NULL,
  `status_kelayakan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_hitung` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perhitungan`
--

INSERT INTO `perhitungan` (`id_perhitungan`, `id_nasabah`, `skor_akhir`, `status_kelayakan`, `tgl_hitung`, `created_at`, `updated_at`) VALUES
(1, 2, 0.92, 'Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02'),
(2, 3, 0.73, 'Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02'),
(3, 5, 0.66, 'Tidak Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02'),
(4, 7, 0.57, 'Tidak Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02'),
(5, 4, 0.52, 'Tidak Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02'),
(6, 1, 0.45, 'Tidak Layak', '2025-07-18', '2025-07-18 05:26:02', '2025-07-18 05:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `rumah`
--

CREATE TABLE `rumah` (
  `id_rumah` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas_bangunan` int NOT NULL,
  `luas_tanah` int NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `karakteristik` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rumah`
--

INSERT INTO `rumah` (`id_rumah`, `nama`, `tipe`, `luas_bangunan`, `luas_tanah`, `harga`, `karakteristik`, `created_at`, `updated_at`) VALUES
(1, 'Ipsum rerum amet re', 'tipe 1', 122, 121, 12.00, 'A ea accusamus eos e', '2025-07-13 09:35:47', '2025-07-13 19:03:04'),
(2, 'Odit dolore saepe qu', 'tipe 2', 12, 21, 21.00, 'Enim saepe aut volup', '2025-07-13 09:44:26', '2025-07-13 09:44:26'),
(3, 'Rem consequatur Et', 'Consequatur Nisi co', 1231, 123122, 1323.00, 'Fugiat distinctio', '2025-07-13 18:55:34', '2025-07-13 18:55:34');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('312wMUVgRRiqrNFVu1hRXtluOVbFhyEL9oKSkv4q', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYXRqdlNSYVhPNzFFY2oyMVJzUHpLanYyZkRvZFpLNzNRRFJhbG9SRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wZXJoaXR1bmdhbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1752816372);

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_subkriteria` bigint UNSIGNED NOT NULL,
  `nama_subkriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` int NOT NULL,
  `id_kriteria` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_subkriteria`, `nama_subkriteria`, `bobot`, `id_kriteria`, `created_at`, `updated_at`) VALUES
(1, '< Rp. 7.500.000', 5, 1, '2025-07-16 02:59:04', '2025-07-16 02:59:04'),
(2, 'Rp. 7.600.000–8.000.000', 4, 1, '2025-07-16 02:59:19', '2025-07-16 02:59:19'),
(3, 'Rp. 9.000.000-10.000.000', 3, 1, '2025-07-16 02:59:33', '2025-07-16 02:59:33'),
(4, 'Rp. 10.000.000-15.000.000', 2, 1, '2025-07-16 02:59:43', '2025-07-16 02:59:43'),
(5, '> Rp. 15.000.000', 1, 1, '2025-07-16 02:59:53', '2025-07-16 02:59:53'),
(6, 'Kredit Macet', 1, 2, '2025-07-16 03:00:41', '2025-07-16 03:00:41'),
(7, 'Belum Pernah Kredit', 3, 2, '2025-07-16 03:00:55', '2025-07-16 03:00:55'),
(8, 'Kredit Lancar', 5, 2, '2025-07-16 03:01:03', '2025-07-16 03:01:03'),
(9, '1 Orang', 5, 3, '2025-07-16 03:01:26', '2025-07-16 03:01:26'),
(11, '> 5 orang', 1, 3, '2025-07-16 03:02:02', '2025-07-16 03:02:02'),
(12, '2-5 Orang', 3, 3, '2025-07-16 03:02:41', '2025-07-16 03:02:53'),
(13, 'Tidak Lengkap', 1, 4, '2025-07-16 03:03:17', '2025-07-16 03:03:17'),
(14, 'Lengkap', 5, 4, '2025-07-16 03:03:32', '2025-07-16 03:03:32'),
(15, 'Sedang Meminjam', 1, 5, '2025-07-16 03:04:30', '2025-07-16 03:04:30'),
(16, 'Pernah Meminjam', 3, 5, '2025-07-16 03:04:42', '2025-07-16 03:04:42'),
(17, 'Belum Pernah Meminjam', 5, 5, '2025-07-16 03:04:57', '2025-07-16 03:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Kredit', 'adminkredit', 'adminkredit@gmail.com', NULL, '$2y$12$u8TMzQ4GNOGWh.rWIsshme8.v.uHSKa78RNdr5CoK.8WGsGaOrJZq', 1, NULL, '2025-07-13 05:56:45', '2025-07-13 06:10:34'),
(2, 'Developer Rumah', 'developer', 'developer@gmail.com', NULL, '$2y$12$R.Y/MqXtBQhtwKu9bgWCk.spF5bkT7c09rxXGY7aR4q9bnGNsppMC', 2, NULL, '2025-07-13 05:58:35', '2025-07-13 06:51:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`),
  ADD UNIQUE KEY `nasabah_no_ktp_unique` (`no_ktp`),
  ADD UNIQUE KEY `nasabah_email_unique` (`email`),
  ADD KEY `nasabah_id_rumah_foreign` (`id_rumah`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `perhitungan`
--
ALTER TABLE `perhitungan`
  ADD PRIMARY KEY (`id_perhitungan`),
  ADD KEY `perhitungan_id_nasabah_foreign` (`id_nasabah`);

--
-- Indexes for table `rumah`
--
ALTER TABLE `rumah`
  ADD PRIMARY KEY (`id_rumah`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_subkriteria`),
  ADD KEY `sub_kriteria_id_kriteria_foreign` (`id_kriteria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
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
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id_nasabah` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `perhitungan`
--
ALTER TABLE `perhitungan`
  MODIFY `id_perhitungan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rumah`
--
ALTER TABLE `rumah`
  MODIFY `id_rumah` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_subkriteria` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD CONSTRAINT `nasabah_id_rumah_foreign` FOREIGN KEY (`id_rumah`) REFERENCES `rumah` (`id_rumah`) ON DELETE CASCADE;

--
-- Constraints for table `perhitungan`
--
ALTER TABLE `perhitungan`
  ADD CONSTRAINT `perhitungan_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE;

--
-- Constraints for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_id_kriteria_foreign` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
