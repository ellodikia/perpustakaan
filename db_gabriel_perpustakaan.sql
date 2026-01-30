-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 11:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gabriel_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama_siswa`, `no_telp`, `alamat`) VALUES
(1, 'Gabriel', '08567563366', 'Jl Perjuangan'),
(2, 'Rizzy', '0879452434', 'Jl. Karya Tani');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default_book.png',
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `stok_tersedia` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `pengarang`, `gambar`, `penerbit`, `tahun_terbit`, `stok`, `stok_tersedia`) VALUES
(1, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '1769741131_sapienss.jpg', NULL, NULL, 10, 9),
(2, 'Atomic Habits', 'James Clear', '1769742195_atomichabits.jpg', NULL, NULL, 10, 9),
(3, 'Sophies World', 'Jostein Gaarder', '1769742273_dunia_shopie.webp', NULL, NULL, 10, 10),
(4, 'The Psychology of Money', 'Morgan Housel', '1769742394_The Psychology of Money.jpg', NULL, NULL, 10, 9),
(5, 'The Secret History of the World ', 'Jonathan Black', '1769742766_sejarah dunia.jpg', NULL, NULL, 10, 10),
(6, 'Laskar Pelangi', 'Andrea Hirata', '1769744054_Laskar Pelangi.jpg', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `level` enum('admin','siswa') NOT NULL,
  `id_anggota` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `level`, `id_anggota`) VALUES
(2, 'admin', '$2y$10$VHGeSbLX9wU4uPb4AiwVUedPPINOEXrwJGtYTkjbK5BdFbXeLm2A.', 'admin', NULL),
(3, 'gabrielganteng', '$2y$10$P/XFK.gEdsuMTmrADSRQYOe7he9XqmiB2ADArm1sHHRCvL7wQHllq', 'siswa', 1),
(4, 'rizzy123', '$2y$10$87hqUtvaX3lu5Ync8IDz1u13YhYo47ws/idft.bylZbHVVB3Dt2tu', 'siswa', 2),
(5, 'sandrakyut', '$2y$10$5IsJTGP298EwYlSTL8WZL.bflEJP7IIBRLFJ0p8ZGLioh.vUqAEju', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_anggota` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_anggota`, `id_buku`, `tanggal_pinjam`, `jatuh_tempo`, `tanggal_kembali`, `status`) VALUES
(1, 1, 1, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(2, 1, 1, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(3, 1, 1, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(4, 1, 1, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(5, 1, 2, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(6, 1, 2, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan'),
(7, 1, 1, '2026-01-30', '2026-02-06', NULL, 'dipinjam'),
(8, 1, 2, '2026-01-30', '2026-02-06', NULL, 'dipinjam'),
(9, 1, 4, '2026-01-30', '2026-02-06', NULL, 'dipinjam'),
(10, 2, 6, '2026-01-30', '2026-02-06', '2026-01-30', 'dikembalikan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_login_anggota` (`id_anggota`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_buku` (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
