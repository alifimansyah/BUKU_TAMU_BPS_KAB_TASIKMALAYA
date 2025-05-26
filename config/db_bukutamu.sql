-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Bulan Mei 2025 pada 10.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bukutamu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_login`
--

CREATE TABLE `log_login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `waktu_login` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_login`
--

INSERT INTO `log_login` (`id`, `username`, `waktu_login`) VALUES
(65, 'adminbps', '2025-05-19 11:58:00'),
(66, 'adminbps', '2025-05-24 02:49:45'),
(67, 'adminbps', '2025-05-24 03:26:49'),
(68, 'adminbps', '2025-05-26 07:40:54'),
(69, 'adminbps', '2025-05-26 07:48:45'),
(70, 'adminbps', '2025-05-26 12:36:46'),
(71, 'adminbps', '2025-05-26 15:32:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tamu_umum`
--

CREATE TABLE `tamu_umum` (
  `No_Pengunjung` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `Jenis_Tamu` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `instansi` varchar(200) NOT NULL,
  `nama_instansi` varchar(100) NOT NULL,
  `keperluan` varchar(225) NOT NULL,
  `keterangan_keperluan` varchar(200) NOT NULL,
  `No_wa_Aktif` varchar(100) NOT NULL,
  `kesan pelayanan` varchar(150) NOT NULL,
  `petugas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tamu_umum`
--

INSERT INTO `tamu_umum` (`No_Pengunjung`, `tanggal`, `Jenis_Tamu`, `nama`, `instansi`, `nama_instansi`, `keperluan`, `keterangan_keperluan`, `No_wa_Aktif`, `kesan pelayanan`, `petugas`) VALUES
(14, '2025-05-05 20:52:00', 'Dinas', 'UQON', 'Dinas', 'DINSOS', 'MENELITI', '', '87654345677', 'baik', ''),
(15, '2025-05-05 21:01:00', 'Umum', 'OZAN', 'Perusahaan', 'HMI', 'MENELITI', '', '87655456678', 'bagus', ''),
(16, '2025-05-06 05:09:00', 'umum', 'ALI', 'Lembaga', 'UNIVERSITAS PERJUANGAN', 'MENELITI', '', '837338399292', '', ''),
(17, '2025-05-06 05:12:00', 'umum', 'ALIF', 'Organisasi', 'UNIVERSITAS PERJUANGAN', 'DATA MASYARAKAT', '', '87655456678', '', ''),
(18, '2025-05-06 05:17:00', 'PST', 'SUGRO', 'Organisasi', 'HMI', 'MENELITI', '', '87655456678', '', ''),
(19, '2025-05-08 07:45:00', 'PST', 'OZAN', 'Perusahaan', 'PERIKANAN', 'MENELITI', '', '87574647464', '', ''),
(20, '2025-05-08 07:52:00', 'PST', 'UQON', 'Organisasi', 'HMI', 'DATA MASYARAKAT', '', '8777777777', '', ''),
(21, '2025-05-08 08:05:00', 'umum', 'UQON', 'Dinas', 'UNIVERSITAS PERJUANGAN', 'MENELITI', '', '87646466363', '', ''),
(22, '2025-05-08 08:08:00', 'umum', 'SUGRO', 'Organisasi', 'PERIKANAN', 'MENELITI', '', '87574647464', '4', ''),
(23, '2025-05-08 08:10:00', 'PST', 'UJANG', 'Perusahaan', 'DINSOS', 'MENELITI', '', '87574647464', '5 = Baik Sekali', ''),
(24, '2025-05-08 08:12:00', 'PST', 'UJANG', 'Organisasi', 'HMI', 'DATA', '', '87654345677', '3 🙂Cukup', ''),
(25, '2025-05-08 08:23:00', 'PST', 'SRI WAHYUNI', 'Lembaga', 'UNIVERSITAS GALUH', 'MENCARI DATA PERTANIAN', '', '875637376464', '3 🙂Cukup', ''),
(26, '2025-05-14 06:00:52', 'umum', 'UQON', 'Lembaga', 'HMI', 'MENCARI DATA PERTANIAN', '', '837338399292', '2 😐 Kurang Baik', ''),
(27, '2025-05-14 19:11:42', 'umum', 'ALIF', 'Organisasi', 'INDOJAYA', 'DATA', '', '87646466363', '5 😍 Baik Sekali', ''),
(28, '2025-05-15 03:09:04', 'umum', 'ALI', 'Organisasi', 'UNIVERSITAS PERJUANGAN', 'DATA MASYARAKAT', '', '86666643323', '4 😊 Baik', ''),
(29, '0000-00-00 00:00:00', 'umum', 'UQON', 'Swasta', 'HMI', 'KONSULTASI STATISTIK', '', '837338399292', '2 😐 Kurang Baik', ''),
(30, '0000-00-00 00:00:00', 'umum', 'MUHAMMAD SUMHA', 'Pendidikan', 'UNIVERSITAS SILIWANGI', 'PERPUSTAKAAN', '', '876544667', '3 🙂 Cukup', ''),
(31, '0000-00-00 00:00:00', 'PST', 'YUSOFF', 'Swasta', 'JAYABAYA', 'KONSULTASI STATISTIK', '', '87646466363', '2 😐 Kurang Baik', ''),
(32, '0000-00-00 00:00:00', 'umum', 'SAHARA', 'Perorangan', 'MASSYARAKAT', 'KONSULTASI STATISTIK', '', '8777777777', '4 😊 Baik', ''),
(33, '0000-00-00 00:00:00', 'umum', 'ALI', 'Swasta', 'JAYABAYA', 'PERPUSTAKAAN', '', '87654345677', '5 😍 Baik Sekali', ''),
(34, '0000-00-00 00:00:00', 'umum', 'SUGRO', 'Pendidikan', 'UNIVERSITAS PERJUANGAN', 'KONSULTASI STATISTIK', '', '87654345677', '4 😊 Baik', ''),
(36, '0000-00-00 00:00:00', 'umum', 'ALIF', 'Pemerintah', 'UNIVERSITAS PERJUANGAN', 'PERPUSTAKAAN', '', '87646466363', '4 😊 Baik', ''),
(37, '0000-00-00 00:00:00', 'umum', 'ALIF', 'Swasta', 'PERIKANAN', 'KONSULTASI STATISTIK', '', '876544252626', '5 😍 Baik Sekali', ''),
(38, '0000-00-00 00:00:00', 'PST', 'OZAN', 'Pendidikan', 'UNIVERSITAS PERJUANGAN', 'KONSULTASI STATISTIK', '', '87646466363', '4 😊 Baik', ''),
(39, '0000-00-00 00:00:00', 'umum', 'UQON', 'Swasta', 'PERIKANAN', 'KONSULTASI STATISTIK', '', '837338399292', '3 🙂 Cukup', ''),
(40, '0000-00-00 00:00:00', 'umum', 'UQON', 'Pemerintah', 'JAYABAYA', '', 'PERPUSTAKAAN', '8777777777', '2 😐 Kurang Baik', ''),
(41, '0000-00-00 00:00:00', 'umum', 'ALI', 'Swasta', 'UNIVERSITAS PERJUANGAN', 'PERPUSTAKAAN', 'SDFADFDS', '837338399292', '4 😊 Baik', ''),
(42, '2025-05-16 15:58:31', 'umum', 'UJANG', 'Swasta', 'GOWAK', 'KONSULTASI STATISTIK', 'LSKFAKSDJ', '9834759384534', '3 🙂 Cukup', ''),
(43, '2025-05-16 16:10:53', 'umum', 'ALIF', 'Pemerintah', 'DINSOS', 'KONSULTASI STATISTIK', 'KJHGHJGJ', '8938475345', '2 😐 Kurang Baik', 'alif imansyah'),
(44, '2025-05-18 19:43:43', 'Instansi', 'UQON', 'Swasta', 'UNIVERSITAS PERJUANGAN', 'Konsultasi Statistik', 'hsssjdjh', '8773737337', '2 😐 Kurang Baik', ''),
(45, '2025-05-19 11:30:04', 'umum', 'UJANG', 'Pemerintah', 'JAYABAYA', 'KONSULTASI STATISTIK', 'MENCARI DATA PERTANIAN', '876544252626', '3 🙂 Cukup', 'alif imansyah'),
(46, '2025-05-23 00:00:00', 'Instansi', 'UQON', 'Swasta', 'PERIKANAN', '', 'BAIKKK', '87574647464', '3 🙂 Cukup', 'ADMIN PST KAB TASIKMALAYA'),
(47, '2025-05-26 00:00:00', 'Umum', 'UJANG', 'Pendidikan', 'JAYABAYA', '', 'MEMINTA', '8737373377', '4 😊 Baik', 'alif imansyah'),
(48, '2025-05-26 12:29:29', 'PST', 'ALI', 'Pendidikan', 'UNIVERSITAS PERJUANGAN', 'PENGADUAN', 'HFDTFXTSTDTFC GVYVYVY UGUGYU', '87574647464', '4 😊 Baik', 'alif imansyah'),
(49, '2025-05-26 15:29:01', 'umum', 'UJANG', 'Swasta', 'DINSOS', 'REKOMENDASI KEGIATAN STATISTIK', 'MENDBDJDJNJ', '87646466363', '', 'alif imansyah'),
(50, '2025-05-26 15:37:37', 'umum', 'ALIF', 'Pemerintah', 'DINSOS', 'KONSULTASI STATISTIK', 'KLJK', '837338399292', '', 'ADMIN PST KAB TASIKMALAYA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_operator`
--

CREATE TABLE `tb_operator` (
  `NIP` varchar(10) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `Nama` varchar(30) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `Hari` varchar(10) NOT NULL,
  `Role` varchar(10) NOT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_operator`
--

INSERT INTO `tb_operator` (`NIP`, `username`, `password`, `Nama`, `foto`, `Hari`, `Role`, `Status`) VALUES
('11111111', 'Gilang', 'admin123', 'Gilang Abdul Aziz', '', '', 'Petugas PS', 0),
('11122233', 'adminbps', '0192023a7bbd73250516f069df18b500', 'ADMIN PST KAB TASIKMALAYA', '', '', 'adminPST', 1),
('123456789', 'Irwan', 'admin123', 'irwan s,kom', '', 'selasa', '', 0),
('156564', 'alif', '827ccb0eea8a706c4c34a16891f84e7b', 'alif imansyah', 'uploads/Unper.png', 'Selasa', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `log_login`
--
ALTER TABLE `log_login`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tamu_umum`
--
ALTER TABLE `tamu_umum`
  ADD PRIMARY KEY (`No_Pengunjung`);

--
-- Indeks untuk tabel `tb_operator`
--
ALTER TABLE `tb_operator`
  ADD PRIMARY KEY (`NIP`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `log_login`
--
ALTER TABLE `log_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT untuk tabel `tamu_umum`
--
ALTER TABLE `tamu_umum`
  MODIFY `No_Pengunjung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
