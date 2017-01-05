-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2017 at 04:39 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test-laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `nusers`
--

CREATE TABLE `nusers` (
  `id` int(10) UNSIGNED NOT NULL,
  `facebook_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `first` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nusers`
--

INSERT INTO `nusers` (`id`, `facebook_id`, `username`, `name`, `first`, `middle`, `last`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(26, '105098569996659', NULL, 'Nguyễn Sanh Toàn', '', '', '', 'stoannguyen2205@gmail.com', '', 't8XzH5Ltv3kKcQSYPV0Xw5RvP1oOplbtOqyr0i5J0QUALPpjvygVVCoPUeEz', '2017-01-04 20:36:57', '2017-01-04 20:37:19'),
(27, '742357812595949', NULL, 'Nguyen Sanh Toan', '', '', '', 'nguoidatinhkhongyeu@yahoo.com.vn', '', NULL, '2017-01-04 20:37:25', '2017-01-04 20:37:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nusers`
--
ALTER TABLE `nusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `facebook_id` (`facebook_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nusers`
--
ALTER TABLE `nusers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
