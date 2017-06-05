-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2017 at 10:18 AM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webapp_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departmentss` (
  `id` int(10) UNSIGNED NOT NULL,
  `dep_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dep_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departmentss` (`id`, `dep_code`, `dep_name`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DP145', 'Testing', 2, '2016-12-03 05:48:24', '2016-12-03 07:38:28', '2016-12-03 07:38:28'),
(2, 'DP1245', 'Testing', 2, '2016-12-03 05:48:37', '2016-12-03 07:42:27', '2016-12-03 07:42:27'),
(3, 'DP1478', 'Designing', 2, '2016-12-03 05:52:34', '2016-12-05 14:50:08', '2016-12-05 14:50:08'),
(4, 'DP258', 'PHP', 2, '2016-12-03 06:52:23', '2016-12-05 14:50:01', '2016-12-05 14:50:01'),
(5, 'DP2555', 'Dot Net', 2, '2016-12-03 15:32:59', '2016-12-05 14:49:56', '2016-12-05 14:49:56'),
(6, 'DP001', 'Department5', 1, '2016-12-05 14:53:26', '2016-12-05 14:53:26', NULL),
(7, 'DP002', 'Department 2', 1, '2016-12-05 14:53:44', '2016-12-05 14:53:44', NULL),
(8, 'DP003', 'Department 3', 1, '2016-12-05 14:53:56', '2016-12-05 14:53:56', NULL),
(9, 'DP004', 'Department 4', 1, '2016-12-05 14:54:07', '2016-12-05 14:54:07', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departments_created_by_index` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
