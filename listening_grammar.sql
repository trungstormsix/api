-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2017 at 11:15 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocoder_education`
--

-- --------------------------------------------------------

--
-- Table structure for table `listening_grammar`
--

CREATE TABLE `listening_grammar` (
  `dialog_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `ex` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `listening_grammar`
--

INSERT INTO `listening_grammar` (`dialog_id`, `lesson_id`, `ex`) VALUES
(219, 6, 'I''ll tell you about my family.'),
(219, 11, 'She teaches Asian religions.'),
(16, 200, 'd'),
(16, 138, 'df');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listening_grammar`
--
ALTER TABLE `listening_grammar`
  ADD KEY `dialog_id` (`dialog_id`),
  ADD KEY `lesson_id` (`lesson_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
