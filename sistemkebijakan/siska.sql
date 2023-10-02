-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2023 at 08:37 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siska`
--

-- --------------------------------------------------------

--
-- Table structure for table `indikator_kinerja`
--

CREATE TABLE `indikator_kinerja` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `kode_iks` varchar(255) DEFAULT NULL,
  `iks` varchar(400) DEFAULT NULL,
  `rasional` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indikator_kinerja`
--

INSERT INTO `indikator_kinerja` (`id`, `proposal_id`, `kode_iks`, `iks`, `rasional`) VALUES
(1, 82, '1.008.01', 'mercapai kerjasama untuk setiap program studi sejumlah minimal: 1 kerjasama dengan mitra tingkat internasional, 12 kerjasama dengan mitra tingkat nasional, 12 kerjasama dengan mitra tingkat  ', 'Adanya kerjasama yang mendukung nation economic development. ');

-- --------------------------------------------------------

--
-- Table structure for table `instruksi`
--

CREATE TABLE `instruksi` (
  `idinstruksi` int(11) NOT NULL,
  `namainstruksi` varchar(50) NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `file` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruksi`
--

INSERT INTO `instruksi` (`idinstruksi`, `namainstruksi`, `deskripsi`, `file`, `tanggal`, `status`) VALUES
(13, 'Instruksi Keselamatan Pekerja', 'Pelaporan kecelakaan kerja dan pencegahan resiko', 'Latihan Tipe Data.pdf', '2023-09-01 15:23:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kebijakan`
--

CREATE TABLE `kebijakan` (
  `idkebijakan` int(11) NOT NULL,
  `namakebijakan` varchar(50) NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `file` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kebijakan`
--

INSERT INTO `kebijakan` (`idkebijakan`, `namakebijakan`, `deskripsi`, `file`, `tanggal`, `status`) VALUES
(31, 'Kebijakan Penerimaan Mahasiswa Baru', 'Akademik', 'SP_602_672020102_3.pdf', '2023-09-02 10:51:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `tujuan_kegiatan` text DEFAULT NULL,
  `dasar_pelaksanaan` text DEFAULT NULL,
  `waktu_pelaksanaan` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `tempat_pelaksanaan` varchar(255) DEFAULT NULL,
  `peserta_kegiatan` text DEFAULT NULL,
  `target_luaran` text DEFAULT NULL,
  `total_anggaran` varchar(255) DEFAULT NULL,
  `unit_pelaksana` varchar(255) DEFAULT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `penutup` varchar(50) DEFAULT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `verif_daku` int(11) NOT NULL,
  `verif_lpm` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id`, `nama_kegiatan`, `tujuan_kegiatan`, `dasar_pelaksanaan`, `waktu_pelaksanaan`, `waktu_selesai`, `tempat_pelaksanaan`, `peserta_kegiatan`, `target_luaran`, `total_anggaran`, `unit_pelaksana`, `sumber_dana`, `penutup`, `lampiran`, `status`, `verif_daku`, `verif_lpm`, `username`) VALUES
(82, 'Memenuhi Undangan dari Universiti Teknologi Malaysia (UTM) President Forum ', 'Menghadiri 13th UNIVERSITI TEKNOLOGI MALAYSIA (UTM) ', 'a. Program Kerja Fakultas Teknologi lnformasi Tahun 2023 b. Permintaan dari WR KK ', '2023-09-07 15:05:00', '2023-09-10 15:05:00', 'Johar Bahru, Malaysia ', 'Prof. Ir. Daniel H.F Manongga, M.Sc., Ph.D. ', 'Jumlah peserta yang mengikuti kegiatan : 1 orang , Jumlah target persentase keberhasilan peserta yang mengikuti kegiatan : 100%', 'Rp. 15.000.000', 'FTI UKSW ', 'Perjalanan Dinas ', 'uksw.png', 'P744_Proposal Dekan FTI ke Malaysia 7 - 10 Agustus 2023.pdf', 0, 0, 0, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `prosedur`
--

CREATE TABLE `prosedur` (
  `idprosedur` int(11) NOT NULL,
  `namaprosedur` varchar(50) NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `file` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prosedur`
--

INSERT INTO `prosedur` (`idprosedur`, `namaprosedur`, `deskripsi`, `file`, `tanggal`, `status`) VALUES
(14, 'Prosedur Pengelolaan Aset dan Inventaris', 'Pengelolaan', 'SR_MBKM.pdf', '2023-09-08 15:23:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rencana_anggaran`
--

CREATE TABLE `rencana_anggaran` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `jenis_pengeluaran` varchar(255) DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `kurs` int(11) DEFAULT NULL,
  `kode_anggaran` varchar(255) DEFAULT NULL,
  `subtotal` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rencana_anggaran`
--

INSERT INTO `rencana_anggaran` (`id`, `proposal_id`, `jenis_pengeluaran`, `satuan`, `jumlah`, `harga_satuan`, `kurs`, `kode_anggaran`, `subtotal`) VALUES
(49, 82, 'Uang makan ', 'Hari ', 4, 5, 15500, '5.1.02.02.04.', 'Rp.1.162.50'),
(50, 82, 'transport', 'Hari ', 5, 5, 15500, '5.1.02.02.04', 'Rp.1.162.50');

-- --------------------------------------------------------

--
-- Table structure for table `standar`
--

CREATE TABLE `standar` (
  `idstandar` int(11) NOT NULL,
  `namastandar` varchar(50) NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `file` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `standar`
--

INSERT INTO `standar` (`idstandar`, `namastandar`, `deskripsi`, `file`, `tanggal`, `status`) VALUES
(11, 'Standar Pengelolaan Fasilitas kampus', 'Fasilitas', 'SP_602_672020102_3.pdf', '2023-09-15 15:06:00', 1),
(13, 'Standar Etika dan Integritas', 'Etika', 'SP_602_672020102_3.pdf', '2023-10-06 10:27:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`) VALUES
(33, 'superadmin', 'superadmin', 'superadmin@gmail.com', 'superadmin'),
(34, 'admin', 'admin', 'admin@gmail.com', 'admin'),
(43, 'user', 'user', 'user@gmail.com', 'user'),
(48, 'a', 'a', 'a@gmail.com', 'user'),
(50, 'admin1', 'admin1', 'admin1@gmail.com', 'admin'),
(52, 'lpm', 'lpm', NULL, 'lpm'),
(53, 'daku', 'daku', NULL, 'daku');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `indikator_kinerja`
--
ALTER TABLE `indikator_kinerja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `instruksi`
--
ALTER TABLE `instruksi`
  ADD PRIMARY KEY (`idinstruksi`);

--
-- Indexes for table `kebijakan`
--
ALTER TABLE `kebijakan`
  ADD PRIMARY KEY (`idkebijakan`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prosedur`
--
ALTER TABLE `prosedur`
  ADD PRIMARY KEY (`idprosedur`);

--
-- Indexes for table `rencana_anggaran`
--
ALTER TABLE `rencana_anggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `standar`
--
ALTER TABLE `standar`
  ADD PRIMARY KEY (`idstandar`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `indikator_kinerja`
--
ALTER TABLE `indikator_kinerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `instruksi`
--
ALTER TABLE `instruksi`
  MODIFY `idinstruksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kebijakan`
--
ALTER TABLE `kebijakan`
  MODIFY `idkebijakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `prosedur`
--
ALTER TABLE `prosedur`
  MODIFY `idprosedur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rencana_anggaran`
--
ALTER TABLE `rencana_anggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `standar`
--
ALTER TABLE `standar`
  MODIFY `idstandar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `indikator_kinerja`
--
ALTER TABLE `indikator_kinerja`
  ADD CONSTRAINT `indikator_kinerja_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`);

--
-- Constraints for table `rencana_anggaran`
--
ALTER TABLE `rencana_anggaran`
  ADD CONSTRAINT `rencana_anggaran_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
