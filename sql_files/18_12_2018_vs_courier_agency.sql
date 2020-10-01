-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2018 at 07:33 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Table structure for table `vs_courier_agency`
--

CREATE TABLE `vs_courier_agency` (
  `agency_id` int(11) UNSIGNED NOT NULL,
  `agency_code` varchar(100) NOT NULL,
  `agency_sap_code` varchar(255) DEFAULT NULL,
  `agency_name` varchar(255) NOT NULL,
  `agency_address` varchar(255) NOT NULL,
  `agency_person_name` varchar(255) NOT NULL,
  `agency_mobile_number` varchar(15) NOT NULL,
  `agency_email_address` varchar(100) NOT NULL,
  `agency_delivery_locations` varchar(255) NOT NULL,
  `agency_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not Active; 1 = Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vs_courier_agency`
--
ALTER TABLE `vs_courier_agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vs_courier_agency`
--
ALTER TABLE `vs_courier_agency`
  MODIFY `agency_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
