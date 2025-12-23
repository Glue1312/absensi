-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 09:17 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `jarak` double DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `tanggal`, `jam_masuk`, `latitude`, `longitude`, `jarak`, `jam_pulang`) VALUES
(1, 1, '2025-12-18', '03:37:55', -7.6915897, 110.6231712, 51.643055357869, NULL),
(4, 3, '2025-12-18', '15:09:27', -7.6914903, 110.6232032, 46.705544318929, '15:09:43'),
(5, 6, '2025-12-18', '15:16:09', -7.6914979, 110.6231984, 47.259465346581, '15:16:21'),
(6, 3, '2025-12-19', '08:27:05', -7.6915179, 110.6232083, 46.310672574205, '14:32:20'),
(7, 7, '2025-12-19', '09:16:41', -7.6914938, 110.6232139, 45.536777352254, '09:16:50'),
(8, 6, '2025-12-19', '09:17:21', -7.6914858, 110.6231987, 47.193564521501, NULL),
(9, 9, '2025-12-19', '14:26:45', -7.6915135, 110.623204, 46.742899344802, '14:27:00'),
(10, 1, '2025-12-19', '14:33:25', -7.6915112, 110.6231996, 47.207767578248, NULL),
(11, 1, '2025-12-23', '10:06:18', -7.6915097, 110.6231698, 51.91287437632, '14:29:49'),
(12, 3, '2025-12-23', '10:06:43', -7.6915097, 110.6231698, 51.91287437632, '10:08:49'),
(13, 6, '2025-12-23', '13:38:38', -7.6915374, 110.6231636, 52.379391926018, NULL),
(14, 7, '2025-12-23', '15:04:49', -7.6914897, 110.6231951, 49.423346632171, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
