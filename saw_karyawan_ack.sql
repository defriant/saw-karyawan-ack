-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jan 2022 pada 15.06
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saw_karyawan_bpm`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id` varchar(5) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` varchar(5) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `bobot` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama`, `bobot`, `created_at`, `updated_at`) VALUES
('1C331', 'Kehadiran', 5, '2021-12-31 08:55:10', '2022-01-03 09:55:54'),
('2P68D', 'Tanggung Jawab', 5, '2021-12-31 08:55:18', '2022-01-02 04:18:31'),
('3K3VA', 'Kualitas Kerja', 4, '2021-12-31 08:55:27', '2022-01-03 13:32:14'),
('4VRJ9', 'Kerja Sama', 6, '2021-12-31 08:55:41', '2022-01-03 12:23:20'),
('5P98C', 'Komunikasi', 4, '2021-12-31 08:55:49', '2021-12-31 09:33:07'),
('6VRMG', 'Kedisiplinan', 4, '2021-12-31 08:55:58', '2021-12-31 09:33:49'),
('73V1R', 'Inisiatif', 5, '2022-01-01 12:08:55', '2022-01-03 10:09:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `n_kriteria`
--

CREATE TABLE `n_kriteria` (
  `id` varchar(5) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `bobot` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `n_kriteria`
--

INSERT INTO `n_kriteria` (`id`, `nama`, `bobot`, `created_at`, `updated_at`) VALUES
('1C331', 'Kehadiran', '0.15', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('2P68D', 'Tanggung Jawab', '0.15', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('3K3VA', 'Kualitas Kerja', '0.12', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('4VRJ9', 'Kerja Sama', '0.18', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('5P98C', 'Komunikasi', '0.12', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('6VRMG', 'Kedisiplinan', '0.12', '2022-01-03 13:32:14', '2022-01-03 13:32:14'),
('73V1R', 'Inisiatif', '0.15', '2022-01-03 13:32:14', '2022-01-03 13:32:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `n_penilaian`
--

CREATE TABLE `n_penilaian` (
  `id` bigint(20) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `id_karyawan` varchar(10) NOT NULL,
  `id_kriteria` varchar(10) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id` bigint(20) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `id_karyawan` varchar(10) NOT NULL,
  `id_kriteria` varchar(10) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id` varchar(5) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nilai` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id`, `nama`, `nilai`, `created_at`, `updated_at`) VALUES
('1EP7P', 'Sangat Bagus', 5, '2022-01-03 12:02:04', '2022-01-03 13:33:13'),
('2J59K', 'Bagus', 4, '2022-01-03 12:06:05', '2022-01-03 13:33:16'),
('386WJ', 'Cukup', 3, '2022-01-03 12:57:31', '2022-01-03 13:33:18'),
('4PVJR', 'Kurang', 2, '2022-01-03 12:57:37', '2022-01-03 13:33:21'),
('58P2Z', 'Sangat Kurang', 1, '2022-01-03 13:33:09', '2022-01-03 13:33:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', NULL, '$2y$10$sQqRoWMPBqXxILIZn3sWUuKczpfEyuJYvVugviSU8nVDrR07nNFKe', NULL, '2021-12-30 12:12:52', '2021-12-30 12:12:52');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `n_kriteria`
--
ALTER TABLE `n_kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `n_penilaian`
--
ALTER TABLE `n_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=377;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
