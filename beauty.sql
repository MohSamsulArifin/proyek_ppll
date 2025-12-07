-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Nov 29, 2025 at 08:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beauty`
--

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id_orders` int(10) NOT NULL,
  `id_barang` int(10) NOT NULL,
  `harga_satuan` int(99) NOT NULL,
  `qty` int(99) NOT NULL,
  `rating` int(1) DEFAULT 0,
  `ulasan` longtext DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jasa_kirim`
--

CREATE TABLE `jasa_kirim` (
  `id_jasa` int(10) NOT NULL,
  `nama_jasa` varchar(99) NOT NULL,
  `harga_jasa` int(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jasa_kirim`
--

INSERT INTO `jasa_kirim` (`id_jasa`, `nama_jasa`, `harga_jasa`) VALUES
(1, 'Sicepat', 24000),
(2, 'JNE', 25000),
(3, 'J&T', 23000);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `qty` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_barang`, `qty`) VALUES
(1, 5, 4, 1),
(2, 5, 2, 1),
(3, 5, 6, 1),
(4, 5, 12, 1),
(5, 5, 16, 1),
(6, 5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `metode`
--

CREATE TABLE `metode` (
  `id_metode` int(10) NOT NULL,
  `nama_metode` varchar(99) NOT NULL,
  `harga_admin` int(10) NOT NULL,
  `va` bigint(99) NOT NULL,
  `qr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metode`
--

INSERT INTO `metode` (`id_metode`, `nama_metode`, `harga_admin`, `va`, `qr`) VALUES
(1, 'Bank BNI', 2000, 8241002201150001, 'assets/img/qr_others.jpg'),
(2, 'Bank BRI', 2000, 888100876543214, 'assets/img/qr_others.jpg'),
(3, 'Bank Mandiri', 2000, 89630011000001, 'assets/img/qr_others.jpg'),
(4, 'Bank BTN', 2000, 934590010000000013, 'assets/img/qr_others.jpg'),
(5, 'Dana', 2000, 85806203920, 'assets/img/qr_dana.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_orders` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_jasa` int(10) NOT NULL,
  `id_metode` int(10) NOT NULL,
  `tanggal` datetime NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `konfirmasi` varchar(99) NOT NULL,
  `status` varchar(99) NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(10) NOT NULL,
  `username` varchar(99) NOT NULL,
  `password` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` int(10) NOT NULL,
  `nama_barang` varchar(99) NOT NULL,
  `harga` int(99) NOT NULL,
  `img` varchar(99) NOT NULL,
  `kategori` varchar(99) NOT NULL,
  `merk` varchar(99) NOT NULL,
  `keterangan` varchar(5000) NOT NULL,
  `stok` int(10) NOT NULL,
  `terjual` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `nama_barang`, `harga`, `img`, `kategori`, `merk`, `keterangan`, `stok`, `terjual`) VALUES
(24, 'MS Glow Whitening Day Cream', 75000, 'msglow_daycream.jpg', 'Skincare', 'MS Glow', 'Krim siang pencerah untuk kulit wajah', 80, 5),
(25, 'Somethinc Copy Paste Tinted Sunscreen SPF50++', 99000, 'somethinc_ss.jpg', 'Sunscreen', 'Somethinc', 'Sunscreen dengan tint ringan untuk melindungi dari UV', 70, 12),
(26, 'Make Over Intense Matte Lip Cream', 105000, 'makeover_lip.jpg', 'Makeup', 'Make Over', 'Lip cream matte tahan lama dan nyaman', 65, 20);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengeluaran`
--

CREATE TABLE `tb_pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_pengeluaran` varchar(100) DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pengeluaran`
--

INSERT INTO `tb_pengeluaran` (`id_pengeluaran`, `tanggal`, `nama_pengeluaran`, `harga`, `qty`, `total`) VALUES
(1, '2024-01-10', 'Beli kabel LAN', 50000, 2, 100000),
(2, '2025-11-29', 'Make Over Intense Matte Lip Cream', 80769, 5, 403846);

-- --------------------------------------------------------

--
-- Table structure for table `tb_rate`
--

CREATE TABLE `tb_rate` (
  `id_user` int(10) NOT NULL,
  `id_barang` int(10) NOT NULL,
  `rating` int(10) NOT NULL,
  `ulasan` longtext NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_refund`
--

CREATE TABLE `tb_refund` (
  `id_refund` int(11) NOT NULL,
  `id_orders` int(11) NOT NULL,
  `alasan` text DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Menunggu','Diterima','Ditolak') DEFAULT 'Menunggu',
  `status_refund` enum('Menunggu','Diproses','Diterima','Ditolak') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(10) NOT NULL,
  `username` varchar(99) NOT NULL,
  `nama` varchar(99) NOT NULL,
  `alamat` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `password` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `nama`, `alamat`, `email`, `password`) VALUES
(4, 'herapuspanita', 'Hera Puspanita', 'Jl. Gresik', 'hera@gmail.com', '123456'),
(5, 'dina', 'dinaputri', 'sidoarjo', 'dinakecil26@gmail.com', '$2y$10$Yu2WTwG6b07Q7uW9t1PBTOwE4V4.1qs9agDbAHo9eF5479nGCfoty');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id_orders`,`id_barang`),
  ADD KEY `id_orders` (`id_orders`,`id_barang`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `jasa_kirim`
--
ALTER TABLE `jasa_kirim`
  ADD PRIMARY KEY (`id_jasa`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD UNIQUE KEY `id_user` (`id_user`,`id_barang`);

--
-- Indexes for table `metode`
--
ALTER TABLE `metode`
  ADD PRIMARY KEY (`id_metode`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`),
  ADD KEY `id_user` (`id_user`,`id_jasa`,`id_metode`),
  ADD KEY `id_jasa` (`id_jasa`),
  ADD KEY `id_metode` (`id_metode`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `tb_pengeluaran`
--
ALTER TABLE `tb_pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indexes for table `tb_rate`
--
ALTER TABLE `tb_rate`
  ADD PRIMARY KEY (`id_user`,`id_barang`),
  ADD KEY `id_user` (`id_user`,`id_barang`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tb_refund`
--
ALTER TABLE `tb_refund`
  ADD PRIMARY KEY (`id_refund`),
  ADD KEY `fk_refund_orders` (`id_orders`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jasa_kirim`
--
ALTER TABLE `jasa_kirim`
  MODIFY `id_jasa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `metode`
--
ALTER TABLE `metode`
  MODIFY `id_metode` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id_barang` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_pengeluaran`
--
ALTER TABLE `tb_pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_refund`
--
ALTER TABLE `tb_refund`
  MODIFY `id_refund` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `details_ibfk_1` FOREIGN KEY (`id_orders`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `details_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_jasa`) REFERENCES `jasa_kirim` (`id_jasa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`id_metode`) REFERENCES `metode` (`id_metode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_rate`
--
ALTER TABLE `tb_rate`
  ADD CONSTRAINT `tb_rate_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_rate_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_refund`
--
ALTER TABLE `tb_refund`
  ADD CONSTRAINT `fk_refund_orders` FOREIGN KEY (`id_orders`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
