-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2024 at 10:08 AM
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
-- Database: `c3e`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(3) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `position`, `email`, `status`, `time_in`, `time_out`, `password`, `contact_number`, `last_name`, `first_name`) VALUES
(2, 'super admin', 'admin@gmail.com', NULL, NULL, NULL, '123', '123456789', 'cj', 'de leon'),
(3, 'hr', 'hr@gmail.com', NULL, NULL, NULL, '123', '123456789', 'admin', 'hr'),
(4, 'supervisor', 'supervisor@gmail.com', NULL, NULL, NULL, '123', '123456789', 'admin', 'supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `file_leave`
--

CREATE TABLE `file_leave` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `duration_from` date DEFAULT NULL,
  `duration_to` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(255) DEFAULT 'pending',
  `position` varchar(255) NOT NULL DEFAULT 'supervisor',
  `current_position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_leave`
--

INSERT INTO `file_leave` (`id`, `name`, `date`, `type`, `duration_from`, `duration_to`, `reason`, `status`, `position`, `current_position`) VALUES
(1, 'amiel', '2024-03-07', 'personal', '2024-03-08', '2024-03-30', 'pagod na', 'pending', 'Super Admin', 'hr'),
(27, '444', '2024-03-08', 'sick', '2024-03-23', '1212-12-11', '444444', 'pending', 'hr', NULL),
(28, '53252321', '2024-03-12', 'sick', '2020-09-08', '2022-12-09', '443213', 'pending', 'rejected', NULL),
(30, '53252321', '2024-03-12', 'sick', '2020-09-08', '2022-12-09', '443213', 'pending', 'hr', NULL),
(31, 'teast weltjsnd', '2024-03-08', 'sick', '2024-03-10', '2024-03-07', 'r3qwrewqrwrtwerwerew', 'pending', 'hr', '3');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_leave`
--
ALTER TABLE `file_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `file_leave`
--
ALTER TABLE `file_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
