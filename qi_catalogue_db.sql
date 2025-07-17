-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 08, 2025 at 06:07 AM
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
-- Database: `qi_catalogue_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'hi o', '2025-07-03 09:11:54'),
(2, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'oo', '2025-07-03 09:23:51'),
(3, 'suggestions ', 'abc@gmail.com', 'ok suggestions ', '2025-07-03 09:25:09'),
(4, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'We value your input! Please use the form below to send us your questions, comments, or suggestions regarding the National Quality Infrastructure Catalogue.\r\n\r\n', '2025-07-04 05:07:28'),
(5, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'We value your input! Please use the form below to send us your questions, comments, or suggestions regarding the National Quality Infrastructure Catalogue.\r\n\r\n', '2025-07-04 05:08:13'),
(6, NULL, NULL, NULL, '2025-07-04 05:08:18'),
(7, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'The content reflects Sri Lanka\'s regulatory framework as described in the PTB QI Catalogue (2018 version), covering import/export controls and domestic regulatory bodies across 38 distinct product categories and functional areas.\r\n\r\n', '2025-07-04 05:09:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
