-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2018 at 12:16 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mail_desk`
--

-- --------------------------------------------------------

--
-- Table structure for table `vs_courier`
--

CREATE TABLE `vs_courier` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `to_type` varchar(255) NOT NULL,
  `to_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vs_courier`
--

INSERT INTO `vs_courier` (`id`, `from_name`, `to_type`, `to_name`, `created_at`) VALUES
(1, 'intellect', 'External', 'asas', '2018-12-24 16:41:39'),
(2, 'intellect', 'Internal', 'test', '2018-12-24 16:42:35'),
(3, 'intellect', 'Internal', 'sasa', '2018-12-24 16:43:38'),
(4, 'intellect', 'Internal', 'radhe', '2018-12-24 16:45:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vs_courier`
--
ALTER TABLE `vs_courier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vs_courier`
--
ALTER TABLE `vs_courier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
