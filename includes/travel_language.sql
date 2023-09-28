-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2023 at 06:09 PM
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
-- Database: `travelc`
--

-- --------------------------------------------------------

--
-- Table structure for table `travel_language`
--

CREATE TABLE `travel_language` (
  `lang_id` int(11) NOT NULL,
  `lang_title` varchar(255) NOT NULL,
  `lang_added` datetime NOT NULL DEFAULT current_timestamp(),
  `land_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `lang_status` enum('Active','Inactive','Deleted') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_language`
--

INSERT INTO `travel_language` (`lang_id`, `lang_title`, `lang_added`, `land_updated`, `lang_status`) VALUES
(1, 'Assamese', '2023-04-18 07:19:31', '2023-04-18 07:19:31', 'Active'),
(2, 'Bengali', '2023-04-18 07:19:31', '2023-04-18 07:19:31', 'Active'),
(3, 'Hindi', '2023-04-18 10:52:08', '2023-04-18 10:52:08', 'Active'),
(4, 'English', '2023-04-18 10:52:08', '2023-04-18 10:52:08', 'Active'),
(5, 'Gujrati', '2023-04-18 10:52:35', '2023-04-18 10:52:35', 'Active'),
(6, 'Nepali', '2023-04-18 10:52:35', '2023-04-18 10:52:35', 'Active'),
(7, 'Odia', '2023-04-18 10:53:30', '2023-04-18 10:53:30', 'Active'),
(8, 'Punjabi', '2023-04-18 10:53:30', '2023-04-18 10:53:30', 'Active'),
(9, 'Marathi', '2023-04-18 10:54:15', '2023-04-18 10:54:15', 'Active'),
(10, 'Tamil', '2023-04-18 10:54:15', '2023-04-18 10:54:15', 'Active'),
(11, 'Telegu', '2023-04-18 10:54:29', '2023-04-18 10:54:29', 'Active'),
(12, 'Urdu', '2023-04-18 10:54:29', '2023-04-18 10:54:29', 'Active'),
(13, 'Sindhi', '2023-04-18 10:54:50', '2023-04-18 10:54:50', 'Active'),
(14, 'Santhali', '2023-04-18 10:54:50', '2023-04-18 10:54:50', 'Active'),
(15, 'Maithili', '2023-04-18 10:55:35', '2023-04-18 10:55:35', 'Active'),
(16, 'Kankani', '2023-04-18 10:55:35', '2023-04-18 10:55:35', 'Active'),
(17, 'Meitei', '2023-04-18 10:56:05', '2023-04-18 10:56:05', 'Active'),
(18, 'Malayalam', '2023-04-18 10:56:05', '2023-04-18 10:56:05', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `travel_language`
--
ALTER TABLE `travel_language`
  ADD PRIMARY KEY (`lang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `travel_language`
--
ALTER TABLE `travel_language`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
