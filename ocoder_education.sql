-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2016 at 10:42 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.5.33

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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_09_21_082240_entrust_setup_tables', 2),
('2016_09_21_095206_create_videoyoutubes_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create-post', 'Create Posts', 'create new blog posts', '2016-09-21 02:34:09', '2016-09-21 02:34:09'),
(2, 'edit-user', 'Edit Users', 'edit existing users', '2016-09-21 02:34:09', '2016-09-21 02:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Project Owner', 'User is the owner of a given project', '2016-09-21 02:34:09', '2016-09-21 02:34:09'),
(2, 'admin', 'User Administrator', 'User is allowed to manage and edit other users', '2016-09-21 02:34:09', '2016-09-21 02:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first`, `middle`, `last`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'trungstormsix', '', '', '', 'trungstorsmix@gmail.com', '$2y$10$q8JVZCwr7AEXlruBz4bu/uQG3bm3N5z1cQjpEIJ1zRgAbA9LQPfp.', 'vF242gxEjwgJakAP4xJFiPhL1IKiYoHOCGhRLkI0f99FXB4vInbj1kvT1NfV', '2016-09-23 19:09:35', '2016-09-26 19:32:05');

-- --------------------------------------------------------

--
-- Table structure for table `ycats`
--

CREATE TABLE `ycats` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ycats`
--

INSERT INTO `ycats` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'Japaness Lessons', NULL, '2016-09-26 20:47:52'),
(2, 'Funny Videos', '2016-09-26 21:50:38', '2016-09-26 21:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `yplaylists`
--

CREATE TABLE `yplaylists` (
  `id` int(10) UNSIGNED NOT NULL,
  `yid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `item_count` int(11) DEFAULT NULL,
  `view_count` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `yplaylists`
--

INSERT INTO `yplaylists` (`id`, `yid`, `title`, `thumb_url`, `cat_id`, `item_count`, `view_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PLPSfPyOOcp3Q1YWaj9JV9XB1OA_m1-Rdy', 'Japanese for Every Day', 'http://i1.ytimg.com/vi/zal7LhB2-mI/hqdefault.jpg', 1, 25, NULL, 1, NULL, '2016-09-26 21:47:07'),
(2, 'PLPSfPyOOcp3TetFOvK1JBXi28BE2Rklp4', 'Weekly Words with Risa', 'http://i1.ytimg.com/vi/fCJZ_LkjS0I/hqdefault.jpg', 1, 52, NULL, 0, NULL, NULL),
(3, 'PLPSfPyOOcp3SyG326n_7q10fJgq-GloTa', 'Introduction to Japanese\r\n', 'http://i1.ytimg.com/vi/ePu05w5aIBE/hqdefault.jpg', 1, 5, NULL, 0, NULL, NULL),
(4, 'PLA7DB863D6946E1CD', 'Learn Hiragana and Katakana', 'http://i1.ytimg.com/vi/IGwu3T-4npo/hqdefault.jpg', 1, 11, NULL, 0, NULL, NULL),
(5, 'PLPSfPyOOcp3TUqQK_TM9wYMn1VrQ0v2p8', 'Innovative Japanese', 'http://i1.ytimg.com/vi/X6lXlpf-n6k/hqdefault.jpg', 1, 12, NULL, 0, NULL, NULL),
(6, 'PLPSfPyOOcp3SAkThpgHto1uwrEFan4QXV', 'Japanese Holiday Words', 'http://i1.ytimg.com/vi/wwc5HVpCD8s/hqdefault.jpg', 1, 16, NULL, 0, NULL, NULL),
(7, 'PLPSfPyOOcp3SxK_tEoQZXlvULxootJrjh', 'Absolute Beginners - Listening', 'http://i1.ytimg.com/vi/pycW3akA-PI/hqdefault.jpg', 1, 20, NULL, 0, NULL, NULL),
(8, 'PLPSfPyOOcp3QnuFfxNhWzIWPmwfTLCFRw', 'Beginners - Listening', 'http://i1.ytimg.com/vi/TutGd9PE68Y/hqdefault.jpg', 1, 19, NULL, 0, NULL, NULL),
(9, 'PLPSfPyOOcp3RRR73FFsv1kKIJ0kDwF5B6', 'Intermediate Learners - Listening', 'http://i1.ytimg.com/vi/r-ZZhttTZJM/hqdefault.jpg', 1, 14, NULL, 0, NULL, NULL),
(10, 'PLPSfPyOOcp3QzSNEWY-5uNd1LKfPpOAaE', 'Advanced Learners - Listening', 'http://i1.ytimg.com/vi/uJb7K2ZT50I/hqdefault.jpg', 1, 14, NULL, 0, NULL, NULL),
(11, 'PLlo9BdMqKVfa4ZR3kQztM-Xp4qDQIXinX', 'Japanese Conversation Sentences', 'http://i1.ytimg.com/vi/ZqNLriIeXlU/hqdefault.jpg', 1, 80, NULL, 1, NULL, NULL),
(12, 'PLlo9BdMqKVfYM12ZSa9EoTd_OOPs8qzMl', 'Learn Japanese Vocabulary 1000', 'http://i1.ytimg.com/vi/PD51zp6JGRk/hqdefault.jpg', 1, 6, NULL, 1, NULL, NULL),
(13, 'PL99E670F92D704946', 'Japanese Words and Phrases', 'http://i1.ytimg.com/vi/Mz3uCFOStt4/hqdefault.jpg', 1, 41, NULL, 1, NULL, NULL),
(14, 'PLPSfPyOOcp3R9ZPLNjZkWxRy-BWCfNMrn', 'Top 5 Videos You Must Watch to Learn Japanese!', 'http://i1.ytimg.com/vi/n6ciMT6KhVo/hqdefault.jpg', 1, 5, 0, 0, '2016-09-26 03:16:11', '2016-09-26 03:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `yvideos`
--

CREATE TABLE `yvideos` (
  `id` int(10) UNSIGNED NOT NULL,
  `yid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `view_count` int(11) NOT NULL DEFAULT '0',
  `time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `channel_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_sub` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `yvideos`
--

INSERT INTO `yvideos` (`id`, `yid`, `title`, `thumb_url`, `view_count`, `time`, `channel_id`, `has_sub`, `note`, `created_at`, `updated_at`) VALUES
(1, 'zal7LhB2-mI', 'Top 10 Compliments You Always Want to Hear in Japanese', 'http://i1.ytimg.com/vi/zal7LhB2-mI/hqdefault.jpg', 0, '2:28 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(2, '28kRZY1z0IE', 'What Japanese Adjective Describes Your Personality Best? - Learn Japanese Vocabulary', 'http://i1.ytimg.com/vi/28kRZY1z0IE/hqdefault.jpg', 0, '4:00 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(3, 'WnW_YRJuEiQ', '10 Happy Words in Japanese', 'http://i1.ytimg.com/vi/WnW_YRJuEiQ/hqdefault.jpg', 0, '3:44 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(4, 'K48KrSiWWPI', '10 Ways to Remember Japanese Words', 'http://i1.ytimg.com/vi/K48KrSiWWPI/hqdefault.jpg', 0, '6:01 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(5, 'xEgM-BVGv5g', 'Learn the Top 10 Foods That Will Kill You Faster in Japanese', 'http://i1.ytimg.com/vi/xEgM-BVGv5g/hqdefault.jpg', 0, '6:37 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(6, 'qpbub-UL_6w', 'Learn the Top 10 Japanese Foods', 'http://i1.ytimg.com/vi/qpbub-UL_6w/hqdefault.jpg', 0, '4:04 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(7, 'b40NNXRSf9w', 'Learn 10 Japanese Phrases for Bad Students', 'http://i1.ytimg.com/vi/b40NNXRSf9w/hqdefault.jpg', 0, '2:49 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(8, 'pSpHh4V0O4Y', 'Learn 10 Japanese Phrases to Amaze Native Speakers', 'http://i1.ytimg.com/vi/pSpHh4V0O4Y/hqdefault.jpg', 0, '4:21 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(9, 'FIOSnbTw5q0', 'Learn 9 STAR WARS Words and Phrases in Japanese!', 'http://i1.ytimg.com/vi/FIOSnbTw5q0/hqdefault.jpg', 0, '4:27 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(10, 'Q5gnchggurQ', 'Learn 10 Japanese Phrases that Make You Look Like a Fool', 'http://i1.ytimg.com/vi/Q5gnchggurQ/hqdefault.jpg', 0, '3:32 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(11, 'bgQai_uRLj8', 'Learn 10 Japanese Phrases You NEVER Want to Hear', 'http://i1.ytimg.com/vi/bgQai_uRLj8/hqdefault.jpg', 0, '3:05 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(12, 'opQFOYPjqD0', 'Learn 10 Japanese Phrases You Always Want to Hear', 'http://i1.ytimg.com/vi/opQFOYPjqD0/hqdefault.jpg', 0, '4:15 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(13, 'uPppbuuVdPY', 'Learn 10 Lines You Need for Introducing Yourself in Japanese', 'http://i1.ytimg.com/vi/uPppbuuVdPY/hqdefault.jpg', 0, '4:36 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(14, 'P7JIl63EDfc', 'Learn the Top 15 Japanese Questions You Should Know', 'http://i1.ytimg.com/vi/P7JIl63EDfc/hqdefault.jpg', 0, '5:53 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(15, 'Zkgj1hYndig', 'Learn the Top 10 Hardest Japanese Words to Pronounce', 'http://i1.ytimg.com/vi/Zkgj1hYndig/hqdefault.jpg', 0, '4:27 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(16, 'dEvSG-XZEsk', 'Learn the Top 15 Favorite Japanese Words (chosen by Fans)', 'http://i1.ytimg.com/vi/dEvSG-XZEsk/hqdefault.jpg', 0, '7:57 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(17, 'v2PLSSWoiUs', 'Learn the Top 25 Must-Know Japanese Adjectives!', 'http://i1.ytimg.com/vi/v2PLSSWoiUs/hqdefault.jpg', 0, '4:33 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(18, 'J7Miaa3pChA', 'Learn the Top 25 Must-Know Japanese Nouns!', 'http://i1.ytimg.com/vi/J7Miaa3pChA/hqdefault.jpg', 0, '6:19 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(19, '4MHudeT01g4', 'Learn the Top 25 Must-Know Japanese Verbs!', 'http://i1.ytimg.com/vi/4MHudeT01g4/hqdefault.jpg', 0, '5:55 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(20, 'n6ciMT6KhVo', 'Learn the Top 25 Must-Know Japanese Phrases!', 'http://i1.ytimg.com/vi/n6ciMT6KhVo/hqdefault.jpg', 0, '6:22 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(21, 'fCJZ_LkjS0I', '       Learn 125 Beginner Japanese Words with Risa!     ', 'http://i1.ytimg.com/vi/fCJZ_LkjS0I/hqdefault.jpg', 0, '21:58 ', '', 0, '', NULL, NULL),
(22, 'Cmvz-fvqo8E', '       Weekly Japanese Words with Risa - Sports     ', 'http://i1.ytimg.com/vi/Cmvz-fvqo8E/hqdefault.jpg', 0, '2:07 ', '', 0, '', NULL, NULL),
(23, 'u0j7Duq6FPI', '       Weekly Japanese Words with Risa - Drinks     ', 'http://i1.ytimg.com/vi/u0j7Duq6FPI/hqdefault.jpg', 0, '1:16 ', '', 0, '', NULL, NULL),
(24, 'h_UQ6o0Ti0w', '       Weekly Japanese Words with Risa - Health Concerns     ', 'http://i1.ytimg.com/vi/h_UQ6o0Ti0w/hqdefault.jpg', 0, '1:24 ', '', 0, '', NULL, NULL),
(25, 'OSeIarLhV8M', '       Weekly Japanese Words with Risa - Food     ', 'http://i1.ytimg.com/vi/OSeIarLhV8M/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(26, 'zBoHdPnWygI', '       Weekly Japanese Words with Risa - Restaurant Verbs     ', 'http://i1.ytimg.com/vi/zBoHdPnWygI/hqdefault.jpg', 0, '1:36 ', '', 0, '', NULL, NULL),
(27, 'fukr0MUTnrA', '       Weekly Japanese Words with Risa - Mammals     ', 'http://i1.ytimg.com/vi/fukr0MUTnrA/hqdefault.jpg', 0, '1:39 ', '', 0, '', NULL, NULL),
(28, 't0c5TRwGCzE', '       Weekly Japanese Words with Risa - Photography     ', 'http://i1.ytimg.com/vi/t0c5TRwGCzE/hqdefault.jpg', 0, '1:50 ', '', 0, '', NULL, NULL),
(29, 'FTjC0jHvLSw', '       Weekly Japanese Words with Risa - Things Your Body Does     ', 'http://i1.ytimg.com/vi/FTjC0jHvLSw/hqdefault.jpg', 0, '1:44 ', '', 0, '', NULL, NULL),
(30, 'VBmCejem4Pg', '       Weekly Japanese Words with Risa - Slang Adjectives     ', 'http://i1.ytimg.com/vi/VBmCejem4Pg/hqdefault.jpg', 0, '1:43 ', '', 0, '', NULL, NULL),
(31, 'uvJ3R8GXI50', '       Weekly Japanese Words with Risa - Direction Words     ', 'http://i1.ytimg.com/vi/uvJ3R8GXI50/hqdefault.jpg', 0, '1:12 ', '', 0, '', NULL, NULL),
(32, '1Bmubl2i9zQ', '       Weekly Japanese Words with Risa - Summer Activities     ', 'http://i1.ytimg.com/vi/1Bmubl2i9zQ/hqdefault.jpg', 0, '1:53 ', '', 0, '', NULL, NULL),
(33, 'SXCDY1jpu1E', '       Weekly Japanese Words with Risa - Sushi Items     ', 'http://i1.ytimg.com/vi/SXCDY1jpu1E/hqdefault.jpg', 0, '1:15 ', '', 0, '', NULL, NULL),
(34, 'ZTRVwsJqJks', '       Weekly Japanese Words with Risa - The Solar System     ', 'http://i1.ytimg.com/vi/ZTRVwsJqJks/hqdefault.jpg', 0, '1:37 ', '', 0, '', NULL, NULL),
(35, 'OIZPkc2S2Dg', '       Weekly Japanese Words with Risa - Your Face     ', 'http://i1.ytimg.com/vi/OIZPkc2S2Dg/hqdefault.jpg', 0, '1:30 ', '', 0, '', NULL, NULL),
(36, '75eiu3e-sfs', '       Weekly Japanese Words with Risa - Spring Adjectives and Adverbs     ', 'http://i1.ytimg.com/vi/75eiu3e-sfs/hqdefault.jpg', 0, '1:22 ', '', 0, '', NULL, NULL),
(37, 'aPE9l21OQCI', '       Weekly Japanese Words with Risa - Slang Nouns     ', 'http://i1.ytimg.com/vi/aPE9l21OQCI/hqdefault.jpg', 0, '1:54 ', '', 0, '', NULL, NULL),
(38, 'Gcr8Pdjyh1Q', '       Weekly Japanese Words with Risa - The Weather     ', 'http://i1.ytimg.com/vi/Gcr8Pdjyh1Q/hqdefault.jpg', 0, '1:17 ', '', 0, '', NULL, NULL),
(39, 'rjcImQJMfy4', '       Weekly Japanese Words with Risa - Track and Field     ', 'http://i1.ytimg.com/vi/rjcImQJMfy4/hqdefault.jpg', 0, '2:08 ', '', 0, '', NULL, NULL),
(40, 'ug_8uvBOuy0', '       Weekly Japanese Words with Risa - Tongue-Twisters     ', 'http://i1.ytimg.com/vi/ug_8uvBOuy0/hqdefault.jpg', 0, '2:09 ', '', 0, '', NULL, NULL),
(41, '2PprFMkHnZc', '       Weekly Japanese Words with Risa - Condition Onomatopoeia     ', 'http://i1.ytimg.com/vi/2PprFMkHnZc/hqdefault.jpg', 0, '1:30 ', '', 0, '', NULL, NULL),
(42, 'Dx6x46nyWmg', '       Weekly Japanese Words with Risa - In Your Toolbox     ', 'http://i1.ytimg.com/vi/Dx6x46nyWmg/hqdefault.jpg', 0, '1:45 ', '', 0, '', NULL, NULL),
(43, '5_sjhme7y4M', '       Weekly Japanese Words with Risa - On a Plane     ', 'http://i1.ytimg.com/vi/5_sjhme7y4M/hqdefault.jpg', 0, '1:41 ', '', 0, '', NULL, NULL),
(44, 'AQm2wVqXiJs', '       Weekly Japanese Words with Risa - Clothing Actions     ', 'http://i1.ytimg.com/vi/AQm2wVqXiJs/hqdefault.jpg', 0, '1:40 ', '', 0, '', NULL, NULL),
(45, '--oxbhbnkbA', '       Weekly Japanese Words with Risa - Home Appliances     ', 'http://i1.ytimg.com/vi/--oxbhbnkbA/hqdefault.jpg', 0, '1:35 ', '', 0, '', NULL, NULL),
(46, 'Y-zBXwJUCZQ', '       Weekly Japanese Words with Risa - Musical Instruments     ', 'http://i1.ytimg.com/vi/Y-zBXwJUCZQ/hqdefault.jpg', 0, '1:51 ', '', 0, '', NULL, NULL),
(47, 'ASp3gkk7IFA', '       Weekly Japanese Words with Risa - Pest Extermination     ', 'http://i1.ytimg.com/vi/ASp3gkk7IFA/hqdefault.jpg', 0, '1:44 ', '', 0, '', NULL, NULL),
(48, 'hDjeTJMoA2c', '       Weekly Japanese Words with Risa - Making a Compliment     ', 'http://i1.ytimg.com/vi/hDjeTJMoA2c/hqdefault.jpg', 0, '1:30 ', '', 0, '', NULL, NULL),
(49, '3aRAEy-hZWg', '       Weekly Japanese Words with Risa - Common Slang Expressions     ', 'http://i1.ytimg.com/vi/3aRAEy-hZWg/hqdefault.jpg', 0, '1:34 ', '', 0, '', NULL, NULL),
(50, 'ZMP9WT-DFMU', '       Weekly Japanese Words with Risa - Slang Verbs     ', 'http://i1.ytimg.com/vi/ZMP9WT-DFMU/hqdefault.jpg', 0, '1:25 ', '', 0, '', NULL, NULL),
(51, 'OVknbxvD8lQ', '       Weekly Japanese Words with Risa - Clothes     ', 'http://i1.ytimg.com/vi/OVknbxvD8lQ/hqdefault.jpg', 0, '1:23 ', '', 0, '', NULL, NULL),
(52, 'LcmosJbJdUQ', '       Weekly Japanese Words with Risa - News Words     ', 'http://i1.ytimg.com/vi/LcmosJbJdUQ/hqdefault.jpg', 0, '1:39 ', '', 0, '', NULL, NULL),
(53, 'UuyZmmZOiiQ', '       Weekly Japanese Words with Risa - Bugs!     ', 'http://i1.ytimg.com/vi/UuyZmmZOiiQ/hqdefault.jpg', 0, '2:00 ', '', 0, '', NULL, NULL),
(54, '4K64ln6n1Qk', '       Weekly Japanese Words with Risa - In your Wallet     ', 'http://i1.ytimg.com/vi/4K64ln6n1Qk/hqdefault.jpg', 0, '1:48 ', '', 0, '', NULL, NULL),
(55, '-BIaXWJNZlM', '       Weekly Japanese Words with Risa - Going Through Customs     ', 'http://i1.ytimg.com/vi/-BIaXWJNZlM/hqdefault.jpg', 0, '1:45 ', '', 0, '', NULL, NULL),
(56, 'lZUVV1MSGSY', '       Weekly Japanese Words with Risa - Academics     ', 'http://i1.ytimg.com/vi/lZUVV1MSGSY/hqdefault.jpg', 0, '1:31 ', '', 0, '', NULL, NULL),
(57, 'jnM-g9Y7UAA', '       Weekly Japanese Words with Risa - Clothing Accessories     ', 'http://i1.ytimg.com/vi/jnM-g9Y7UAA/hqdefault.jpg', 0, '1:46 ', '', 0, '', NULL, NULL),
(58, 'vm-vjeJvknc', '       Weekly Japanese Words with Risa - Disasters!     ', 'http://i1.ytimg.com/vi/vm-vjeJvknc/hqdefault.jpg', 0, '1:37 ', '', 0, '', NULL, NULL),
(59, 'QqlF-YLSjDw', '       Weekly Japanese Words with Risa - Exercise!     ', 'http://i1.ytimg.com/vi/QqlF-YLSjDw/hqdefault.jpg', 0, '2:05 ', '', 0, '', NULL, NULL),
(60, '1hzAZ5v9dfM', '       Weekly Japanese Words with Risa - Intermediate Weather     ', 'http://i1.ytimg.com/vi/1hzAZ5v9dfM/hqdefault.jpg', 0, '1:47 ', '', 0, '', NULL, NULL),
(61, '_meRTBYx-5k', '       Weekly Japanese Words with Risa - Physical Appearance     ', 'http://i1.ytimg.com/vi/_meRTBYx-5k/hqdefault.jpg', 0, '1:57 ', '', 0, '', NULL, NULL),
(62, 'kEHvQm2YRh0', '       Weekly Japanese Words with Risa!     ', 'http://i1.ytimg.com/vi/kEHvQm2YRh0/hqdefault.jpg', 0, '1:33 ', '', 0, '', NULL, NULL),
(63, 'v31KtlYw5oA', '       Weekly Japanese Words with Risa - Spring Activities     ', 'http://i1.ytimg.com/vi/v31KtlYw5oA/hqdefault.jpg', 0, '1:46 ', '', 0, '', NULL, NULL),
(64, 'y3j2PfkOSqE', '       Weekly Japanese Words with Risa - Difficult Words to Say     ', 'http://i1.ytimg.com/vi/y3j2PfkOSqE/hqdefault.jpg', 0, '1:25 ', '', 0, '', NULL, NULL),
(65, 'KNRhuRLwkeo', '       Weekly Japanese Words with Risa - Computer Words     ', 'http://i1.ytimg.com/vi/KNRhuRLwkeo/hqdefault.jpg', 0, '1:31 ', '', 0, '', NULL, NULL),
(66, 'vbHneGmg5uc', '       Weekly Words with Risa - Spring Verbs     ', 'http://i1.ytimg.com/vi/vbHneGmg5uc/hqdefault.jpg', 0, '1:49 ', '', 0, '', NULL, NULL),
(67, 'bu6lZ1bhZ7U', '       Weekly Japanese Words with Risa ♥ Falling in Love     ', 'http://i1.ytimg.com/vi/bu6lZ1bhZ7U/hqdefault.jpg', 0, '1:32 ', '', 0, '', NULL, '2016-09-26 03:43:03'),
(68, 'w5Xwzpw8CjI', '       Weekly Japanese Words with Risa - Number Idioms     ', 'http://i1.ytimg.com/vi/w5Xwzpw8CjI/hqdefault.jpg', 0, '1:27 ', '', 0, '', NULL, NULL),
(69, 'mUiwGjJcKVA', '       Weekly Japanese Words with Risa - Difficult Katakana Words     ', 'http://i1.ytimg.com/vi/mUiwGjJcKVA/hqdefault.jpg', 0, '1:22 ', '', 0, '', NULL, NULL),
(70, 'hnRL5GeMgGA', '       Weekly Japanese Words with Risa - Studying Onomatopoeia     ', 'http://i1.ytimg.com/vi/hnRL5GeMgGA/hqdefault.jpg', 0, '1:20 ', '', 0, '', NULL, NULL),
(71, 'wnIsWkRvPSY', '       Weekly Japanese Words with Risa - Japanese Cities     ', 'http://i1.ytimg.com/vi/wnIsWkRvPSY/hqdefault.jpg', 0, '1:45 ', '', 0, '', NULL, NULL),
(72, 'd8rp_jwJw0Y', '       Weekly Japanese Words with Risa - Japanese Actors     ', 'http://i1.ytimg.com/vi/d8rp_jwJw0Y/hqdefault.jpg', 0, '1:33 ', '', 0, '', NULL, NULL),
(73, 'DdlhdCP-W5w', '       Introduction to Japanese - Why Learn Japanese?     ', 'http://i1.ytimg.com/vi/DdlhdCP-W5w/hqdefault.jpg', 0, '6:47 ', '', 0, '', NULL, '2016-09-26 03:43:03'),
(74, 'ZhVlq7yDQho', '       Introduction to Japanese Pronunciation     ', 'http://i1.ytimg.com/vi/ZhVlq7yDQho/hqdefault.jpg', 0, '6:05 ', '', 0, '', NULL, NULL),
(75, 'ePu05w5aIBE', '       Introduction to Japanese Grammar     ', 'http://i1.ytimg.com/vi/ePu05w5aIBE/hqdefault.jpg', 0, '6:29 ', '', 0, '', NULL, NULL),
(76, 'CFq2Y43DY0U', '       Introduction to Japanese Writing     ', 'http://i1.ytimg.com/vi/CFq2Y43DY0U/hqdefault.jpg', 0, '5:31 ', '', 0, '', NULL, NULL),
(77, 'ycVlxOzZbaY', '       Introduction to Japanese – Basic Bootcamp     ', 'http://i1.ytimg.com/vi/ycVlxOzZbaY/hqdefault.jpg', 0, '6:59 ', '', 0, '', NULL, NULL),
(78, '8zfBMDNkJuI', '       Learn Hiragana - Kantan Kana Lesson 1 Learn to Read and Write Japanese     ', 'http://i1.ytimg.com/vi/8zfBMDNkJuI/hqdefault.jpg', 0, '5:33 ', '', 0, '', NULL, NULL),
(79, 'BquugHa7wKg', '       Learn to Read and Write Japanese - Kantan Kana lesson 2     ', 'http://i1.ytimg.com/vi/BquugHa7wKg/hqdefault.jpg', 0, '5:16 ', '', 0, '', NULL, NULL),
(80, 'IGwu3T-4npo', '       Learn to Read and Write Japanese - Kantan Kana lesson 3     ', 'http://i1.ytimg.com/vi/IGwu3T-4npo/hqdefault.jpg', 0, '5:20 ', '', 0, '', NULL, NULL),
(81, 'yTMfTfAY7ZA', '       Learn to Read and Write Japanese Hiragana - Kantan Kana lesson 4     ', 'http://i1.ytimg.com/vi/yTMfTfAY7ZA/hqdefault.jpg', 0, '5:49 ', '', 0, '', NULL, NULL),
(82, 'khkuLPIiS3I', '       Learn to Read and Write Japanese Hiragana - Kantan Kana lesson 5     ', 'http://i1.ytimg.com/vi/khkuLPIiS3I/hqdefault.jpg', 0, '5:03 ', '', 0, '', NULL, NULL),
(83, 'nsgFUkub3Fk', '       Learn to Read and Write Japanese Hiragana - Kantan Kana lesson 6     ', 'http://i1.ytimg.com/vi/nsgFUkub3Fk/hqdefault.jpg', 0, '5:03 ', '', 0, '', NULL, NULL),
(84, 'M_ONsy2E3J8', '       Learn to Read and Write Japanese Hiragana - Kantan Kana lesson 7     ', 'http://i1.ytimg.com/vi/M_ONsy2E3J8/hqdefault.jpg', 0, '4:40 ', '', 0, '', NULL, NULL),
(85, 'sIpzSouCZ5I', '       Learn to Read and Write Japanese Hiragana - Kantan Kana lesson 8     ', 'http://i1.ytimg.com/vi/sIpzSouCZ5I/hqdefault.jpg', 0, '5:50 ', '', 0, '', NULL, NULL),
(86, 'cwW70fv9RBk', '       Learn Katakana - Kantan Kana Lesson 14 Learn to Read and Write Japanese     ', 'http://i1.ytimg.com/vi/cwW70fv9RBk/hqdefault.jpg', 0, '3:53 ', '', 0, '', NULL, NULL),
(87, 'dHJKqnxhOwk', '       Learn Katakana - Kantan Kana Lesson 15 Learn to Read and Write Japanese     ', 'http://i1.ytimg.com/vi/dHJKqnxhOwk/hqdefault.jpg', 0, '4:55 ', '', 0, '', NULL, NULL),
(88, 'fM0fO8lCdkQ', '       Kantan Kana now on JapanesePod101.com!     ', 'http://i1.ytimg.com/vi/fM0fO8lCdkQ/hqdefault.jpg', 0, '1:27 ', '', 0, '', NULL, NULL),
(89, 'X6lXlpf-n6k', '       How to Introduce Yourself in Japanese | Innovative Japanese     ', 'http://i1.ytimg.com/vi/X6lXlpf-n6k/hqdefault.jpg', 0, '14:43 ', '', 0, '', NULL, '2016-09-26 03:43:03'),
(90, 'uAtChyBQxvw', '       How to Describe Where You&#39;re From | Innovative Japanese     ', 'http://i1.ytimg.com/vi/uAtChyBQxvw/hqdefault.jpg', 0, '6:48 ', '', 0, '', NULL, NULL),
(91, '-pj3KErj0EE', '       How to Navigate Passport Control in Japanese | Innovative Japanese     ', 'http://i1.ytimg.com/vi/-pj3KErj0EE/hqdefault.jpg', 0, '9:04 ', '', 0, '', NULL, NULL),
(92, 'iVAD6emOlAo', '       How to Exchange Contact Information in Japanese | Innovative Japanese     ', 'http://i1.ytimg.com/vi/iVAD6emOlAo/hqdefault.jpg', 0, '7:21 ', '', 0, '', NULL, NULL),
(93, '3k3T0_jftGk', '       Checking in at a Hotel | Innovative Japanese     ', 'http://i1.ytimg.com/vi/3k3T0_jftGk/hqdefault.jpg', 0, '12:36 ', '', 0, '', NULL, NULL),
(94, 'GP9EgylNB9c', '       Buying Items at a Register in Japan | Innovative Japanese     ', 'http://i1.ytimg.com/vi/GP9EgylNB9c/hqdefault.jpg', 0, '6:39 ', '', 0, '', NULL, NULL),
(95, 'p7pQE8_kxGQ', '       Greeting an Old Acquaintance | Innovative Japanese     ', 'http://i1.ytimg.com/vi/p7pQE8_kxGQ/hqdefault.jpg', 0, '7:10 ', '', 0, '', NULL, NULL),
(96, 'xkq8-9qa2ig', '       Explaining Details about an Appointment | Innovative Japanese     ', 'http://i1.ytimg.com/vi/xkq8-9qa2ig/hqdefault.jpg', 0, '6:00 ', '', 0, '', NULL, NULL),
(97, '2fqxUvZb2_U', '       How to Finish Up Work in a Japanese Office | Innovative Japanese     ', 'http://i1.ytimg.com/vi/2fqxUvZb2_U/hqdefault.jpg', 0, '3:15 ', '', 0, '', NULL, NULL),
(98, 'ZBH2v4hVpys', '       Discussing Future Plans | Innovative Japanese     ', 'http://i1.ytimg.com/vi/ZBH2v4hVpys/hqdefault.jpg', 0, '6:55 ', '', 0, '', NULL, NULL),
(99, 'LqhJHnQIMf8', '       Buying Tickets for Public Transportation | Innovative Japanese     ', 'http://i1.ytimg.com/vi/LqhJHnQIMf8/hqdefault.jpg', 0, '8:50 ', '', 0, '', NULL, NULL),
(100, 'woy0a-ZFe14', '       How to Order Food at a Ramen Restaurant | Innovative Japanese     ', 'http://i1.ytimg.com/vi/woy0a-ZFe14/hqdefault.jpg', 0, '3:49 ', '', 0, '', NULL, NULL),
(101, 'wwc5HVpCD8s', '       Japanese VALENTINE&#39;S DAY Words with Risa!     ', 'http://i1.ytimg.com/vi/wwc5HVpCD8s/hqdefault.jpg', 0, '1:30 ', '', 0, '', NULL, NULL),
(102, 'mJJW-XfMPCA', '       Japanese NATIONAL FOUNDATION DAY Words with Risa!     ', 'http://i1.ytimg.com/vi/mJJW-XfMPCA/hqdefault.jpg', 0, '1:48 ', '', 0, '', NULL, NULL),
(103, '_9OO-Kf9Rec', '       Japanese SETSUBUN Words with Risa!     ', 'http://i1.ytimg.com/vi/_9OO-Kf9Rec/hqdefault.jpg', 0, '1:59 ', '', 0, '', NULL, NULL),
(104, 'OnORKu95mr4', '       Japanese COMING OF AGE DAY Words with Risa!     ', 'http://i1.ytimg.com/vi/OnORKu95mr4/hqdefault.jpg', 0, '2:20 ', '', 0, '', NULL, NULL),
(105, 'MIdX1Gv-QyM', '       Japanese NEW YEARS Words with Risa!     ', 'http://i1.ytimg.com/vi/MIdX1Gv-QyM/hqdefault.jpg', 0, '2:01 ', '', 0, '', NULL, NULL),
(106, 'IgEqmCqNKuo', '       Japanese CULTURE DAY Words with Risa!     ', 'http://i1.ytimg.com/vi/IgEqmCqNKuo/hqdefault.jpg', 0, '1:50 ', '', 0, '', NULL, NULL),
(107, 'BFSc7ToTmI4', '       Japanese HALLOWEEN Words with Risa!     ', 'http://i1.ytimg.com/vi/BFSc7ToTmI4/hqdefault.jpg', 0, '2:40 ', '', 0, '', NULL, NULL),
(108, 'Z-FHPwa7qIM', '       Japanese OBON Words with Risa! - お盆     ', 'http://i1.ytimg.com/vi/Z-FHPwa7qIM/hqdefault.jpg', 0, '1:51 ', '', 0, '', NULL, NULL),
(109, 'EkG8H6TmUlA', '       Japanese TANABATA (Star Festival) Words with Risa! - 七夕     ', 'http://i1.ytimg.com/vi/EkG8H6TmUlA/hqdefault.jpg', 0, '2:17 ', '', 0, '', NULL, NULL),
(110, 'g0hcuH1UdA4', '       Japanese RESPECT FOR THE AGED DAY Words with Risa! - 敬老の日     ', 'http://i1.ytimg.com/vi/g0hcuH1UdA4/hqdefault.jpg', 0, '2:10 ', '', 0, '', NULL, NULL),
(111, 's-I4vzUycSM', '       Japanese CHILDREN&#39;S DAY Words with Risa! - こどもの日     ', 'http://i1.ytimg.com/vi/s-I4vzUycSM/hqdefault.jpg', 0, '1:48 ', '', 0, '', NULL, NULL),
(112, 'Yhw_AEpW690', '       Japanese WHITE DAY Words with Risa!     ', 'http://i1.ytimg.com/vi/Yhw_AEpW690/hqdefault.jpg', 0, '2:32 ', '', 0, '', NULL, NULL),
(113, '9MwLqr1hrfQ', '       Japanese TSUYU (Rainy Season) Words with Risa! - 梅雨     ', 'http://i1.ytimg.com/vi/9MwLqr1hrfQ/hqdefault.jpg', 0, '1:51 ', '', 0, '', NULL, NULL),
(114, 'hYnR6pcWxqI', '       Japanese HANAMI Words with Risa! - Cherry Blossom Viewing in Japan - 花見     ', 'http://i1.ytimg.com/vi/hYnR6pcWxqI/hqdefault.jpg', 0, '2:35 ', '', 0, '', NULL, NULL),
(115, 'ZVs-m4j88ck', '       Japanese HINA MATSURI Words with Risa!     ', 'http://i1.ytimg.com/vi/ZVs-m4j88ck/hqdefault.jpg', 0, '2:13 ', '', 0, '', NULL, NULL),
(116, 'UZwR3jOhBAM', '       Japanese SHICHI-GO-SAN Words with Risa! - 七五三     ', 'http://i1.ytimg.com/vi/UZwR3jOhBAM/hqdefault.jpg', 0, '1:57 ', '', 0, '', NULL, NULL),
(117, 'pycW3akA-PI', '       Japanese Listening Comprehension - At a Japanese Bookstore     ', 'http://i1.ytimg.com/vi/pycW3akA-PI/hqdefault.jpg', 0, '1:47 ', '', 0, '', NULL, NULL),
(118, 'FuOiwTE5QDE', '       Japanese Listening Comprehension - At a Restaurant in Japan     ', 'http://i1.ytimg.com/vi/FuOiwTE5QDE/hqdefault.jpg', 0, '1:39 ', '', 0, '', NULL, NULL),
(119, 'x9-v3SWuOF8', '       Japanese Listening Comprehension - Calling the Japanese Doctor&#39;s Office     ', 'http://i1.ytimg.com/vi/x9-v3SWuOF8/hqdefault.jpg', 0, '1:31 ', '', 0, '', NULL, NULL),
(120, 'TpaglETsUto', '       Japanese Listening Comprehension - Reading a Japanese Journal     ', 'http://i1.ytimg.com/vi/TpaglETsUto/hqdefault.jpg', 0, '1:36 ', '', 0, '', NULL, NULL),
(121, '4FuD8pYK2mc', '       Japanese Listening Comprehension - Looking At a Photograph from Japan     ', 'http://i1.ytimg.com/vi/4FuD8pYK2mc/hqdefault.jpg', 0, '1:30 ', '', 0, '', NULL, NULL),
(122, '6Lg78R2P7QI', '       Japanese Listening Comprehension - Seeing a Movie in Japan     ', 'http://i1.ytimg.com/vi/6Lg78R2P7QI/hqdefault.jpg', 0, '2:05 ', '', 0, '', NULL, NULL),
(123, 'mM_4k2Rurjg', '       Japanese Listening Comprehension - Shopping for a Shirt in Japan     ', 'http://i1.ytimg.com/vi/mM_4k2Rurjg/hqdefault.jpg', 0, '1:48 ', '', 0, '', NULL, NULL),
(124, 'DK13W_SwBxQ', '       Japanese Listening Comprehension - Ordering a Burger in Japanese     ', 'http://i1.ytimg.com/vi/DK13W_SwBxQ/hqdefault.jpg', 0, '1:35 ', '', 0, '', NULL, NULL),
(125, 'KOBY8gklk1A', '       Japanese Listening Comprehension - Baking a Cake in Japan     ', 'http://i1.ytimg.com/vi/KOBY8gklk1A/hqdefault.jpg', 0, '1:41 ', '', 0, '', NULL, NULL),
(126, 'MuvZ5RoCmSI', '       Japanese Listening Comprehension - Making Plans for the Day in Japanese     ', 'http://i1.ytimg.com/vi/MuvZ5RoCmSI/hqdefault.jpg', 0, '2:02 ', '', 0, '', NULL, NULL),
(127, 'i5B9A0lsF0E', '       Japanese Listening Comprehension - Ordering Lunch at a Restaurant in Japan     ', 'http://i1.ytimg.com/vi/i5B9A0lsF0E/hqdefault.jpg', 0, '1:37 ', '', 0, '', NULL, NULL),
(128, 's4qB6cihCas', '       Japanese Listening Comprehension - Choosing a Place to Wait in Japan     ', 'http://i1.ytimg.com/vi/s4qB6cihCas/hqdefault.jpg', 0, '1:42 ', '', 0, '', NULL, NULL),
(129, 'ukOZ0oEBmoo', '       Japanese Listening Comprehension - Talking About Vacation Plans in Japanese     ', 'http://i1.ytimg.com/vi/ukOZ0oEBmoo/hqdefault.jpg', 0, '1:51 ', '', 0, '', NULL, NULL),
(130, 'jqiQmcjHgXU', '       Japanese Listening Comprehension - Talking About Breakfast in Japanese     ', 'http://i1.ytimg.com/vi/jqiQmcjHgXU/hqdefault.jpg', 0, '1:47 ', '', 0, '', NULL, NULL),
(131, 'lGrzmDOfPeY', '       Japanese Listening Comprehension - Finding What You Want at a Department Store in Japan     ', 'http://i1.ytimg.com/vi/lGrzmDOfPeY/hqdefault.jpg', 0, '1:46 ', '', 0, '', NULL, NULL),
(132, 'TARhYm6AT8I', '       Japanese Listening Comprehension - Talking About your Age in Japanese     ', 'http://i1.ytimg.com/vi/TARhYm6AT8I/hqdefault.jpg', 0, '1:37 ', '', 0, '', NULL, NULL),
(133, 'NbYnZES0Cgg', '       Japanese Listening Comprehension - Shopping at a Boutique in Japan     ', 'http://i1.ytimg.com/vi/NbYnZES0Cgg/hqdefault.jpg', 0, '1:53 ', '', 0, '', NULL, NULL),
(134, 'k_y-lm74L1c', '       Japanese Listening Comprehension - Talking About a Party in Japanese     ', 'http://i1.ytimg.com/vi/k_y-lm74L1c/hqdefault.jpg', 0, '1:35 ', '', 0, '', NULL, NULL),
(135, 'XaOzy6FkwsU', '       Japanese Listening Comprehension - Arranging Furniture in a Room     ', 'http://i1.ytimg.com/vi/XaOzy6FkwsU/hqdefault.jpg', 0, '1:36 ', '', 0, '', NULL, NULL),
(136, 'jjvauzpvooA', '       Japanese Listening Comprehension - Rescheduling a Dentist Appointment in Japan     ', 'http://i1.ytimg.com/vi/jjvauzpvooA/hqdefault.jpg', 0, '3:36 ', '', 0, '', NULL, NULL),
(137, 'TutGd9PE68Y', '       Japanese Listening Comprehension - At the Jewelry Store in Japan     ', 'http://i1.ytimg.com/vi/TutGd9PE68Y/hqdefault.jpg', 0, '2:44 ', '', 0, '', NULL, NULL),
(138, '6LG8pMax-6Q', '       Japanese Listening Comprehension - Rearranging the Office in Japan     ', 'http://i1.ytimg.com/vi/6LG8pMax-6Q/hqdefault.jpg', 0, '2:25 ', '', 0, '', NULL, NULL),
(139, 'aGrUCqBjpeQ', '       Japanese Listening Comprehension - Getting Some Groceries in Japan     ', 'http://i1.ytimg.com/vi/aGrUCqBjpeQ/hqdefault.jpg', 0, '2:25 ', '', 0, '', NULL, NULL),
(140, '5SNrXa_wGZw', '       Japanese Listening Comprehension - Listening to a Japanese Forecast     ', 'http://i1.ytimg.com/vi/5SNrXa_wGZw/hqdefault.jpg', 0, '1:39 ', '', 0, '', NULL, NULL),
(141, 'AWuMLhjxIeA', '       Japanese Listening Comprehension - Discussing a New Design in Japanese     ', 'http://i1.ytimg.com/vi/AWuMLhjxIeA/hqdefault.jpg', 0, '2:30 ', '', 0, '', NULL, NULL),
(142, 'W1xYi4RVQiA', '       Japanese Listening Comprehension - Getting Japanese Directions     ', 'http://i1.ytimg.com/vi/W1xYi4RVQiA/hqdefault.jpg', 0, '1:59 ', '', 0, '', NULL, NULL),
(143, 'wqSk586JJME', '       Japanese Listening Comprehension - Choosing a Drink in Japan     ', 'http://i1.ytimg.com/vi/wqSk586JJME/hqdefault.jpg', 0, '1:52 ', '', 0, '', NULL, NULL),
(144, 'oWYL1jecM8k', '       Japanese Listening Comprehension - Shopping for a Computer in Japan     ', 'http://i1.ytimg.com/vi/oWYL1jecM8k/hqdefault.jpg', 0, '2:18 ', '', 0, '', NULL, NULL),
(145, 'ZKbbCG4oZdw', '       Japanese Listening Comprehension - Talking About Your Schedule in Japanese     ', 'http://i1.ytimg.com/vi/ZKbbCG4oZdw/hqdefault.jpg', 0, '1:58 ', '', 0, '', NULL, NULL),
(146, 'gdZqtykij_s', '       Japanese Listening Comprehension - Ordering a Pizza in Japanese     ', 'http://i1.ytimg.com/vi/gdZqtykij_s/hqdefault.jpg', 0, '2:13 ', '', 0, '', NULL, NULL),
(147, 'zGljcfcT-uo', '       Japanese Listening Comprehension - Asking about a Restaurant&#39;s Opening Hours in Japanese     ', 'http://i1.ytimg.com/vi/zGljcfcT-uo/hqdefault.jpg', 0, '2:17 ', '', 0, '', NULL, NULL),
(148, 'TNRYkIgQ3SE', '       Japanese Listening Comprehension - Choosing a Delivery Time in Japan     ', 'http://i1.ytimg.com/vi/TNRYkIgQ3SE/hqdefault.jpg', 0, '2:10 ', '', 0, '', NULL, NULL),
(149, 'PsglHl1D4Rw', '       Japanese Listening Comprehension - Talking About Medicine in Japanese     ', 'http://i1.ytimg.com/vi/PsglHl1D4Rw/hqdefault.jpg', 0, '2:13 ', '', 0, '', NULL, NULL),
(150, 'bE5EWvsBPnw', '       [Video riêng tư]     ', 'http://i1.ytimg.com/vi/bE5EWvsBPnw/hqdefault.jpg', 0, '', '', 0, '', NULL, NULL),
(151, 'X1K1IVO6Ucs', '       Japanese Listening Comprehension - Choosing a Pair of Glasses in Japan     ', 'http://i1.ytimg.com/vi/X1K1IVO6Ucs/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(152, '5z3Squ3PH50', '       Japanese Listening Comprehension - Finding A Friend&#39;s Apartment in Japan     ', 'http://i1.ytimg.com/vi/5z3Squ3PH50/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(153, 'CagncByRjrU', '       Japanese Listening Comprehension - Renting a DVD in Japan     ', 'http://i1.ytimg.com/vi/CagncByRjrU/hqdefault.jpg', 0, '1:57 ', '', 0, '', NULL, NULL),
(154, '0lufp06s-N4', '       Japanese Listening Comprehension - What Time is it Now in Japan?     ', 'http://i1.ytimg.com/vi/0lufp06s-N4/hqdefault.jpg', 0, '1:58 ', '', 0, '', NULL, NULL),
(155, 'vhYsx8UW5Rc', '       Japanese Listening Comprehension - Choosing a Seat on a Flight in Japan     ', 'http://i1.ytimg.com/vi/vhYsx8UW5Rc/hqdefault.jpg', 0, '2:00 ', '', 0, '', NULL, NULL),
(156, 'r-ZZhttTZJM', '       Japanese Listening Comprehension - Booking a Hotel in Japan     ', 'http://i1.ytimg.com/vi/r-ZZhttTZJM/hqdefault.jpg', 0, '3:11 ', '', 0, '', NULL, NULL),
(157, 'XPGUpwnzux8', '       Japanese Listening Comprehension - At the Hairdresser in Japan     ', 'http://i1.ytimg.com/vi/XPGUpwnzux8/hqdefault.jpg', 0, '3:00 ', '', 0, '', NULL, NULL),
(158, 'RcYzIodIAbY', '       Japanese Listening Comprehension - Reading Japanese Job Postings     ', 'http://i1.ytimg.com/vi/RcYzIodIAbY/hqdefault.jpg', 0, '3:29 ', '', 0, '', NULL, NULL),
(159, '8U0e5ZimduU', '       Japanese Listening Comprehension - Shopping for an Outfit in Japan     ', 'http://i1.ytimg.com/vi/8U0e5ZimduU/hqdefault.jpg', 0, '2:59 ', '', 0, '', NULL, NULL),
(160, 'BJtx1iNQNCU', '       Japanese Listening Comprehension - Discussing a Document in Japanese     ', 'http://i1.ytimg.com/vi/BJtx1iNQNCU/hqdefault.jpg', 0, '3:15 ', '', 0, '', NULL, NULL),
(161, '1mxd0J3MXYM', '       Japanese Listening Comprehension - Reporting a Lost Item in Japanese     ', 'http://i1.ytimg.com/vi/1mxd0J3MXYM/hqdefault.jpg', 0, '2:15 ', '', 0, '', NULL, NULL),
(162, 'qLObJQqFOc4', '       Japanese Listening Comprehension - Looking for an Apartment in Japan     ', 'http://i1.ytimg.com/vi/qLObJQqFOc4/hqdefault.jpg', 0, '3:29 ', '', 0, '', NULL, NULL),
(163, 'H5Sjvqx9J2I', '       Japanese Listening Comprehension - Choosing a Cake in Japan     ', 'http://i1.ytimg.com/vi/H5Sjvqx9J2I/hqdefault.jpg', 0, '3:02 ', '', 0, '', NULL, NULL),
(164, 'KHQQANohVt0', '       Japanese Listening Comprehension - Deciding When to Move in Japan     ', 'http://i1.ytimg.com/vi/KHQQANohVt0/hqdefault.jpg', 0, '3:27 ', '', 0, '', NULL, NULL),
(165, 'PJHTzhssf5A', '       Japanese Listening Comprehension - Delivering a Sales Report in Japanese     ', 'http://i1.ytimg.com/vi/PJHTzhssf5A/hqdefault.jpg', 0, '3:01 ', '', 0, '', NULL, NULL),
(166, 'h2mI5uWQuL4', '       Japanese Listening Comprehension - Buying Shirts in a Sale in Japan     ', 'http://i1.ytimg.com/vi/h2mI5uWQuL4/hqdefault.jpg', 0, '2:35 ', '', 0, '', NULL, NULL),
(167, 'RNBQotPy0xU', '       Japanese Listening Comprehension - Scheduling a Checkup in Japanese     ', 'http://i1.ytimg.com/vi/RNBQotPy0xU/hqdefault.jpg', 0, '3:01 ', '', 0, '', NULL, NULL),
(168, 'TD1vI5BzNN4', '       Japanese Listening Comprehension - Talking About a Person in Japanese     ', 'http://i1.ytimg.com/vi/TD1vI5BzNN4/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(169, 'VZdooFkpGXg', '       Japanese Listening Comprehension - Talking About a Photo in Japanese     ', 'http://i1.ytimg.com/vi/VZdooFkpGXg/hqdefault.jpg', 0, '2:51 ', '', 0, '', NULL, NULL),
(170, 'uJb7K2ZT50I', '       Japanese Listening Comprehension - A Japanese Business Presentation     ', 'http://i1.ytimg.com/vi/uJb7K2ZT50I/hqdefault.jpg', 0, '3:39 ', '', 0, '', NULL, NULL),
(171, 'bH3bbFXx6Tg', '       Japanese Listening Comprehension - Getting a Gym Membership in Japan     ', 'http://i1.ytimg.com/vi/bH3bbFXx6Tg/hqdefault.jpg', 0, '4:07 ', '', 0, '', NULL, NULL),
(172, 'y6mcprBt-tU', '       Japanese Listening Comprehension - At a Printing Company in Japan     ', 'http://i1.ytimg.com/vi/y6mcprBt-tU/hqdefault.jpg', 0, '3:50 ', '', 0, '', NULL, NULL),
(173, '-gwwaJ0GBe8', '       Japanese Listening Comprehension - Reserving Tickets to a Play in Japanese     ', 'http://i1.ytimg.com/vi/-gwwaJ0GBe8/hqdefault.jpg', 0, '3:52 ', '', 0, '', NULL, NULL),
(174, 'q92D06WyonA', '       Japanese Listening Comprehension - Lesson Video Preparing For a Japanese Business Meeting     ', 'http://i1.ytimg.com/vi/q92D06WyonA/hqdefault.jpg', 0, '3:26 ', '', 0, '', NULL, NULL),
(175, 'fl2xPnFBFKc', '       Japanese Listening Comprehension - Deciding on a Hotel in Japan     ', 'http://i1.ytimg.com/vi/fl2xPnFBFKc/hqdefault.jpg', 0, '4:01 ', '', 0, '', NULL, NULL),
(176, '43x9HM7Yo74', '       Japanese Listening Comprehension - Setting up a Meeting Room in Japan     ', 'http://i1.ytimg.com/vi/43x9HM7Yo74/hqdefault.jpg', 0, '3:21 ', '', 0, '', NULL, NULL),
(177, '7jm7BzlldNQ', '       Japanese Listening Comprehension - Ordering Office Supplies in Japanese     ', 'http://i1.ytimg.com/vi/7jm7BzlldNQ/hqdefault.jpg', 0, '3:43 ', '', 0, '', NULL, NULL),
(178, '54ZyHUsN2Ik', '       Japanese Listening Comprehension - Getting to the Airport in Japan     ', 'http://i1.ytimg.com/vi/54ZyHUsN2Ik/hqdefault.jpg', 0, '3:36 ', '', 0, '', NULL, NULL),
(179, 'QMXNl6ckDpM', '       Japanese Listening Comprehension - Talking to a Supplier in Japanese     ', 'http://i1.ytimg.com/vi/QMXNl6ckDpM/hqdefault.jpg', 0, '3:22 ', '', 0, '', NULL, NULL),
(180, '7jakVvp1O4g', '       Japanese Listening Comprehension - Going to the Library in Japan     ', 'http://i1.ytimg.com/vi/7jakVvp1O4g/hqdefault.jpg', 0, '3:29 ', '', 0, '', NULL, NULL),
(181, 'UYd8mkqaiks', '       Japanese Listening Comprehension - Choosing Travel Insurance in Japan     ', 'http://i1.ytimg.com/vi/UYd8mkqaiks/hqdefault.jpg', 0, '3:57 ', '', 0, '', NULL, NULL),
(182, '66Y9kkfyRAg', '       Japanese Listening Comprehension - Discussing Survey Results in Japanese     ', 'http://i1.ytimg.com/vi/66Y9kkfyRAg/hqdefault.jpg', 0, '3:23 ', '', 0, '', NULL, NULL),
(183, 'HgcqcEeq2vw', '       Japanese Listening Comprehension - Giving Back to the Community in Japan     ', 'http://i1.ytimg.com/vi/HgcqcEeq2vw/hqdefault.jpg', 0, '3:58 ', '', 0, '', NULL, NULL),
(184, 'ZqNLriIeXlU', '       Japanese Conversation Sentences 1000 No1, learning for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/ZqNLriIeXlU/hqdefault.jpg', 0, '2:59 ', '', 0, '', NULL, NULL),
(185, '8CaTdIC9yFU', '       Japanese Conversation Sentences 1000 No2, learning for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/8CaTdIC9yFU/hqdefault.jpg', 0, '2:49 ', '', 0, '', NULL, NULL),
(186, 'Bt2f9lKlgRE', '       Japanese Conversation Sentences 1000 No3, learning for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/Bt2f9lKlgRE/hqdefault.jpg', 0, '2:56 ', '', 0, '', NULL, NULL),
(187, 'K2Dzcj7rVcc', '       Japanese Conversation Sentences 1000 No4, learning for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/K2Dzcj7rVcc/hqdefault.jpg', 0, '2:42 ', '', 0, '', NULL, NULL),
(188, 'YiKNtDCwZXU', '       Japanese Conversation Sentences 1000 No5, learning for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/YiKNtDCwZXU/hqdefault.jpg', 0, '3:14 ', '', 0, '', NULL, NULL),
(189, 'M6cfMm2e1fY', '       Japanese Conversation Sentences 1000 No6, learn for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/M6cfMm2e1fY/hqdefault.jpg', 0, '2:58 ', '', 0, '', NULL, NULL),
(190, 'jD3kSGt5d-A', '       Japanese Conversation Sentences 1000 No7, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/jD3kSGt5d-A/hqdefault.jpg', 0, '2:58 ', '', 0, '', NULL, NULL),
(191, '_uL9omsefjc', '       Japanese Conversation Sentences 1000 No8, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/_uL9omsefjc/hqdefault.jpg', 0, '4:16 ', '', 0, '', NULL, NULL),
(192, '-ss9PFcLaDs', '       Japanese Conversation Sentences 1000 No9, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/-ss9PFcLaDs/hqdefault.jpg', 0, '3:28 ', '', 0, '', NULL, NULL),
(193, '8jj0X_JScEM', '       Japanese Conversation Sentences 1000 No10, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/8jj0X_JScEM/hqdefault.jpg', 0, '3:36 ', '', 0, '', NULL, NULL),
(194, 'yI1FrGvzptA', '       Japanese Conversation Sentences 1000 No11, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/yI1FrGvzptA/hqdefault.jpg', 0, '3:09 ', '', 0, '', NULL, NULL),
(195, '4A3xEDV6dvU', '       Japanese Conversation Sentences 1000 No12, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/4A3xEDV6dvU/hqdefault.jpg', 0, '3:29 ', '', 0, '', NULL, NULL),
(196, 'aEfL98UGbfc', '       Japanese Conversation Sentences 1000 No13, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/aEfL98UGbfc/hqdefault.jpg', 0, '3:39 ', '', 0, '', NULL, NULL),
(197, 'gdFGTkAbgNI', '       Japanese Conversation Sentences 1000 No14, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/gdFGTkAbgNI/hqdefault.jpg', 0, '3:39 ', '', 0, '', NULL, NULL),
(198, 'Y-0-WPFkbuo', '       Japanese Conversation Sentences 1000 No15, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/Y-0-WPFkbuo/hqdefault.jpg', 0, '4:41 ', '', 0, '', NULL, NULL),
(199, '_R50wPAsDjI', '       Japanese Conversation Sentences 1000 No16, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/_R50wPAsDjI/hqdefault.jpg', 0, '4:26 ', '', 0, '', NULL, NULL),
(200, 'wAI0Jvk6adU', '       Japanese Conversation Sentences 1000 No17, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/wAI0Jvk6adU/hqdefault.jpg', 0, '4:06 ', '', 0, '', NULL, NULL),
(201, '0-RfY5xw_vk', '       Japanese Conversation Sentences 1000 No18, study for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/0-RfY5xw_vk/hqdefault.jpg', 0, '4:13 ', '', 0, '', NULL, NULL),
(202, 'FRPrK4p4sfw', '       Japanese Conversation Sentences 1000 No19, Learn for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/FRPrK4p4sfw/hqdefault.jpg', 0, '4:26 ', '', 0, '', NULL, NULL),
(203, '8oFOV8Rc4OA', '       Japanese Conversation Sentences 1000 No 20, Learn for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/8oFOV8Rc4OA/hqdefault.jpg', 0, '4:09 ', '', 0, '', NULL, NULL),
(204, 'o0y_9keAKWU', '       Japanese Conversation Sentences 1000 No 21, Learn japanese for beginners with english subtitles     ', 'http://i1.ytimg.com/vi/o0y_9keAKWU/hqdefault.jpg', 0, '4:31 ', '', 0, '', NULL, NULL),
(205, 'lreL-mdySpA', '       Japanese Conversation Sentences 1000 No 22, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/lreL-mdySpA/hqdefault.jpg', 0, '4:03 ', '', 0, '', NULL, NULL),
(206, 'HaQwzSnUthw', '       Japanese Conversation Sentences 1000 No 23, Learn japanese language with english subtitles     ', 'http://i1.ytimg.com/vi/HaQwzSnUthw/hqdefault.jpg', 0, '4:21 ', '', 0, '', NULL, NULL),
(207, '7RSh99ac0mU', '       Japanese Conversation Sentences 1000 No 24, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/7RSh99ac0mU/hqdefault.jpg', 0, '4:06 ', '', 0, '', NULL, NULL),
(208, 'Fk4rttrrJ78', '       Japanese Conversation Sentences 1000 No 25, Learn Basic japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/Fk4rttrrJ78/hqdefault.jpg', 0, '3:26 ', '', 0, '', NULL, NULL),
(209, 'pRhB6bzQb9g', '       Japanese Conversation Sentences 1000 No 26, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/pRhB6bzQb9g/hqdefault.jpg', 0, '3:10 ', '', 0, '', NULL, NULL),
(210, 'mR7UEe8vd4E', '       Japanese Conversation Sentences 1000 No 27, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/mR7UEe8vd4E/hqdefault.jpg', 0, '3:21 ', '', 0, '', NULL, NULL),
(211, 'T8nPY22QX8o', '       Japanese Conversation Sentences 1000 No 28, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/T8nPY22QX8o/hqdefault.jpg', 0, '3:26 ', '', 0, '', NULL, NULL),
(212, 's-_meA74Ld0', '       Japanese Conversation Sentences 1000 No 29, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/s-_meA74Ld0/hqdefault.jpg', 0, '3:46 ', '', 0, '', NULL, NULL),
(213, 'M_MlcXewNYA', '       japanese conversation sentences 1000 No 30, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/M_MlcXewNYA/hqdefault.jpg', 0, '4:48 ', '', 0, '', NULL, NULL),
(214, 'hBbFzNkzuGk', '       Japanese Conversation Sentences 1000 No 31, Learn useful japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/hBbFzNkzuGk/hqdefault.jpg', 0, '3:21 ', '', 0, '', NULL, NULL),
(215, 'NbNTqQ9hYrU', '       Japanese Conversation Sentences 1000 No 32, Learn conversation practice with english subtitles     ', 'http://i1.ytimg.com/vi/NbNTqQ9hYrU/hqdefault.jpg', 0, '4:25 ', '', 0, '', NULL, NULL),
(216, '0YE8Ex5SnFI', '       Japanese Conversation Sentences 1000 No 33, Basic japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/0YE8Ex5SnFI/hqdefault.jpg', 0, '3:31 ', '', 0, '', NULL, NULL),
(217, 'ur0F5HcfYP4', '       Japanese Conversation Sentences 1000 No 34, Study japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/ur0F5HcfYP4/hqdefault.jpg', 0, '3:17 ', '', 0, '', NULL, NULL),
(218, 'fRDz3BWaqhs', '       Japanese Conversation Sentences 1000 No 35, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/fRDz3BWaqhs/hqdefault.jpg', 0, '4:01 ', '', 0, '', NULL, NULL),
(219, 'ZGjREAHUQ7s', '       Japanese Conversation Sentences 1000 No 37, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/ZGjREAHUQ7s/hqdefault.jpg', 0, '4:04 ', '', 0, '', NULL, NULL),
(220, 'XaQQy98o4D4', '       Japanese Conversation Sentences 1000 No 38, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/XaQQy98o4D4/hqdefault.jpg', 0, '4:18 ', '', 0, '', NULL, NULL),
(221, 'yESwUxysLyw', '       Japanese Conversation Sentences 1000 No 39, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/yESwUxysLyw/hqdefault.jpg', 0, '3:36 ', '', 0, '', NULL, NULL),
(222, '0PnqlHd3y9I', '       Japanese Conversation Sentences 1000 No 40, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/0PnqlHd3y9I/hqdefault.jpg', 0, '3:49 ', '', 0, '', NULL, NULL),
(223, 't_JYKjS3fFQ', '       Japanese Conversation Sentences 1000 No 41, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/t_JYKjS3fFQ/hqdefault.jpg', 0, '3:53 ', '', 0, '', NULL, NULL),
(224, 'DtIZdTfFfz0', '       Japanese Conversation Sentences 1000 No 42, Learn japanese lesson with english subtitles. nihongo     ', 'http://i1.ytimg.com/vi/DtIZdTfFfz0/hqdefault.jpg', 0, '3:51 ', '', 0, '', NULL, NULL),
(225, 'TXHsyeBklro', '       Japanese Conversation Sentences 1000 No 43, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/TXHsyeBklro/hqdefault.jpg', 0, '4:01 ', '', 0, '', NULL, NULL),
(226, 'w8svwwCB9Lw', '       modified - Japanese Conversation Sentences 1000 No 36     ', 'http://i1.ytimg.com/vi/w8svwwCB9Lw/hqdefault.jpg', 0, '3:28 ', '', 0, '', NULL, NULL),
(227, 'nfeGhALMIVk', '       Japanese Conversation Sentences 1000 No 45, Learn japanese lesson1 with english subtitles     ', 'http://i1.ytimg.com/vi/nfeGhALMIVk/hqdefault.jpg', 0, '3:33 ', '', 0, '', NULL, NULL),
(228, '74EXU7kGasM', '       Japanese Conversation Sentences 1000 No 46, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/74EXU7kGasM/hqdefault.jpg', 0, '3:39 ', '', 0, '', NULL, NULL),
(229, '8mON6AevmPM', '       Japanese Conversation Sentences 1000 No 47, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/8mON6AevmPM/hqdefault.jpg', 0, '4:01 ', '', 0, '', NULL, NULL),
(230, 'lTZrA-23yxU', '       Japanese Conversation Sentences 1000 No 48, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/lTZrA-23yxU/hqdefault.jpg', 0, '3:51 ', '', 0, '', NULL, NULL),
(231, '5SdaFdLbpLY', '       Japanese Conversation Sentences 1000 No 49, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/5SdaFdLbpLY/hqdefault.jpg', 0, '3:39 ', '', 0, '', NULL, NULL),
(232, 'e1pTBdXe04Q', '       Japanese Conversation Sentences 1000 No 50, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/e1pTBdXe04Q/hqdefault.jpg', 0, '3:44 ', '', 0, '', NULL, NULL),
(233, 'UJBaREQpWAY', '       Japanese Conversation Sentences 1000 No 51, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/UJBaREQpWAY/hqdefault.jpg', 0, '4:21 ', '', 0, '', NULL, NULL),
(234, 'ep_akr4cSvI', '       Japanese Conversation Sentences 1000 No 52, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/ep_akr4cSvI/hqdefault.jpg', 0, '3:04 ', '', 0, '', NULL, NULL),
(235, 'vL-ifTNmf4o', '       Japanese Conversation Sentences 1000 No 53, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/vL-ifTNmf4o/hqdefault.jpg', 0, '3:47 ', '', 0, '', NULL, NULL),
(236, '13lLM3zi5vY', '       Japanese Conversation Sentences 1000 No 54, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/13lLM3zi5vY/hqdefault.jpg', 0, '4:46 ', '', 0, '', NULL, NULL),
(237, '1qCZctHjXxs', '       Japanese Conversation Sentences 1000 No 55, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/1qCZctHjXxs/hqdefault.jpg', 0, '3:01 ', '', 0, '', NULL, NULL),
(238, 'f0m8GpQALCI', '       Japanese Conversation Sentences 1000 No 56, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/f0m8GpQALCI/hqdefault.jpg', 0, '4:03 ', '', 0, '', NULL, NULL),
(239, 'kDAsfYKtBIE', '       Japanese Conversation Sentences 1000 No 57, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/kDAsfYKtBIE/hqdefault.jpg', 0, '3:51 ', '', 0, '', NULL, NULL),
(240, '9DYfN84f3f8', '       Japanese Conversation Sentences 1000 No 58, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/9DYfN84f3f8/hqdefault.jpg', 0, '3:33 ', '', 0, '', NULL, NULL),
(241, '_tyitG27UKw', '       Japanese Conversation Sentences 1000 No 59, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/_tyitG27UKw/hqdefault.jpg', 0, '3:25 ', '', 0, '', NULL, NULL),
(242, 'd6jLWvhYCT0', '       Japanese Conversation Sentences 1000 No 60, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/d6jLWvhYCT0/hqdefault.jpg', 0, '3:57 ', '', 0, '', NULL, NULL),
(243, '0bqmrPsPu0w', '       Japanese Conversation Sentences 1000 No 61, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/0bqmrPsPu0w/hqdefault.jpg', 0, '3:18 ', '', 0, '', NULL, NULL),
(244, 'VQcg6tgZOxQ', '       Japanese Conversation Sentences 1000 No 62, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/VQcg6tgZOxQ/hqdefault.jpg', 0, '3:24 ', '', 0, '', NULL, NULL),
(245, 'VFdnaN_vgq8', '       Japanese Conversation Sentences 1000 No 63, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/VFdnaN_vgq8/hqdefault.jpg', 0, '3:48 ', '', 0, '', NULL, NULL),
(246, 'KpYaPWSOtCY', '       Japanese Conversation Sentences 1000 No 64, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/KpYaPWSOtCY/hqdefault.jpg', 0, '4:19 ', '', 0, '', NULL, NULL),
(247, 'uJeJhGpn5TQ', '       Japanese Conversation Sentences 1000 No 65, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/uJeJhGpn5TQ/hqdefault.jpg', 0, '2:56 ', '', 0, '', NULL, NULL),
(248, 'AhupYEmXSHo', '       Japanese Conversation Sentences 1000 No 66, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/AhupYEmXSHo/hqdefault.jpg', 0, '3:19 ', '', 0, '', NULL, NULL),
(249, 'FYuBF3PfA44', '       Japanese Conversation Sentences 1000 No 67, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/FYuBF3PfA44/hqdefault.jpg', 0, '3:11 ', '', 0, '', NULL, NULL),
(250, 'DaGWK65_GbY', '       Japanese Conversation Sentences 1000 No68 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/DaGWK65_GbY/hqdefault.jpg', 0, '3:05 ', '', 0, '', NULL, NULL),
(251, 'lG5H-tSxjO4', '       Japanese Conversation Sentences 1000 No 44, Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/lG5H-tSxjO4/hqdefault.jpg', 0, '3:03 ', '', 0, '', NULL, NULL),
(252, 'FApW4WZ6ce8', '       Japanese Conversation Sentences 1000 No69 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/FApW4WZ6ce8/hqdefault.jpg', 0, '3:07 ', '', 0, '', NULL, NULL),
(253, 'Zr9815jEfsA', '       Japanese Conversation Sentences 1000 No70 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/Zr9815jEfsA/hqdefault.jpg', 0, '3:26 ', '', 0, '', NULL, NULL),
(254, 'cli3dQ10eaY', '       Japanese Conversation Sentences 1000 No71 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/cli3dQ10eaY/hqdefault.jpg', 0, '2:49 ', '', 0, '', NULL, NULL),
(255, 'kwjYIXust0E', '       Japanese Conversation Sentences 1000 No72 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/kwjYIXust0E/hqdefault.jpg', 0, '4:22 ', '', 0, '', NULL, NULL),
(256, 'tKPP0dk-hag', '       Japanese Conversation Sentences 1000 No73 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/tKPP0dk-hag/hqdefault.jpg', 0, '2:41 ', '', 0, '', NULL, NULL),
(257, 'v-ccCY1Jo4k', '       Japanese Conversation Sentences 1000 No74 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/v-ccCY1Jo4k/hqdefault.jpg', 0, '3:10 ', '', 0, '', NULL, NULL),
(258, 'Itfq-INL7QM', '       Japanese Conversation Sentences 1000 No , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/Itfq-INL7QM/hqdefault.jpg', 0, '2:51 ', '', 0, '', NULL, NULL),
(259, 'AtD0hCq1PXQ', '       Japanese Conversation Sentences 1000 No76 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/AtD0hCq1PXQ/hqdefault.jpg', 0, '2:55 ', '', 0, '', NULL, NULL),
(260, 'cIl2m12AdTQ', '       Japanese Conversation Sentences 1000 No77 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/cIl2m12AdTQ/hqdefault.jpg', 0, '3:03 ', '', 0, '', NULL, NULL),
(261, 'l_iCocp_SLQ', '       Japanese Conversation Sentences 1000 No78 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/l_iCocp_SLQ/hqdefault.jpg', 0, '3:11 ', '', 0, '', NULL, NULL),
(262, '0Pct44wfP1Y', '       Japanese Conversation Sentences 1000 No79 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/0Pct44wfP1Y/hqdefault.jpg', 0, '3:36 ', '', 0, '', NULL, NULL),
(263, 'rRTvD429Izg', '       Japanese Conversation Sentences 1000 No80 , Learn japanese lesson with english subtitles     ', 'http://i1.ytimg.com/vi/rRTvD429Izg/hqdefault.jpg', 0, '5:12 ', '', 0, '', NULL, NULL);
INSERT INTO `yvideos` (`id`, `yid`, `title`, `thumb_url`, `view_count`, `time`, `channel_id`, `has_sub`, `note`, `created_at`, `updated_at`) VALUES
(264, 'PD51zp6JGRk', '       Japanese Vocabulary 1000 No 1, integrated version, Learn japanese words lesson english subtitles     ', 'http://i1.ytimg.com/vi/PD51zp6JGRk/hqdefault.jpg', 0, '1:02:41 ', '', 0, '', NULL, NULL),
(265, 'OJSPE0jNJpI', '       Japanese Vocabulary 1000 No 2, integrated version, Learn japanese words lesson english subtitles     ', 'http://i1.ytimg.com/vi/OJSPE0jNJpI/hqdefault.jpg', 0, '1:02:47 ', '', 0, '', NULL, NULL),
(266, '4xdV2coQwoo', '       Japanese Vocabulary 1000 No 3, integrated version, Learn japanese words lesson english subtitles     ', 'http://i1.ytimg.com/vi/4xdV2coQwoo/hqdefault.jpg', 0, '57:18 ', '', 0, '', NULL, NULL),
(267, 'uxtT9GggwEo', '       Japanese Vocabulary 1000 No 4, integrated version, Learn japanese words lesson english subtitles     ', 'http://i1.ytimg.com/vi/uxtT9GggwEo/hqdefault.jpg', 0, '46:47 ', '', 0, '', NULL, NULL),
(268, '_DDe5lKWcnM', '       Japanese Vocabulary 1000 No 5, integrated version, Learn japanese words with english subtitles     ', 'http://i1.ytimg.com/vi/_DDe5lKWcnM/hqdefault.jpg', 0, '54:51 ', '', 0, '', NULL, NULL),
(269, 'k4-SpqXAgq0', '       Japanese Vocabulary 1000 No6, integrated version, Learn japanese words with english subtitles     ', 'http://i1.ytimg.com/vi/k4-SpqXAgq0/hqdefault.jpg', 0, '1:04:12 ', '', 0, '', NULL, NULL),
(270, 'Mz3uCFOStt4', '       Learn Japanese - Video Vocabulary     ', 'http://i1.ytimg.com/vi/Mz3uCFOStt4/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(271, 'GmToAMrI8xw', '       Learn Japanese - Video Vocabulary 2     ', 'http://i1.ytimg.com/vi/GmToAMrI8xw/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(272, 'mhJNQl7jp2Q', '       Learn Japanese - Video Vocabulary 3     ', 'http://i1.ytimg.com/vi/mhJNQl7jp2Q/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(273, 'Ue5JSrmQoTM', '       Learn Japanese - Video Vocabulary 4     ', 'http://i1.ytimg.com/vi/Ue5JSrmQoTM/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(274, '2zTFAVFsTUQ', '       Learn Japanese - Video Vocabulary 5     ', 'http://i1.ytimg.com/vi/2zTFAVFsTUQ/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(275, 'R_bWt2WOe3k', '       Learn Japanese - Video Vocabulary 6     ', 'http://i1.ytimg.com/vi/R_bWt2WOe3k/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(276, 'ayxedHTCdig', '       Learn Japanese - Video Vocabulary 7     ', 'http://i1.ytimg.com/vi/ayxedHTCdig/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(277, 'I-J3zrXJrEE', '       Learn Japanese - Video Vocabulary 8     ', 'http://i1.ytimg.com/vi/I-J3zrXJrEE/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(278, 'zqHVr4oxZsQ', '       Learn Japanese - Video Vocabulary 9     ', 'http://i1.ytimg.com/vi/zqHVr4oxZsQ/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(279, 'DiNZSx6e8is', '       Learn Japanese - Video Vocabulary 10     ', 'http://i1.ytimg.com/vi/DiNZSx6e8is/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(280, 'RpeQzNpq1TI', '       Learn Japanese - Video Vocabulary 11     ', 'http://i1.ytimg.com/vi/RpeQzNpq1TI/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(281, 'LblCpGGqhVA', '       Learn Japanese - Video Vocabulary 12     ', 'http://i1.ytimg.com/vi/LblCpGGqhVA/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(282, 'DS-1e4uaDf4', '       Learn Japanese - Video Vocabulary 13     ', 'http://i1.ytimg.com/vi/DS-1e4uaDf4/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(283, 'crAuEx88ayc', '       Learn Japanese - Video Vocabulary 14     ', 'http://i1.ytimg.com/vi/crAuEx88ayc/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(284, 'lWecNTeJYw0', '       Learn Japanese - Video Vocabulary 15     ', 'http://i1.ytimg.com/vi/lWecNTeJYw0/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(285, 'r1CICJdlKfc', '       Learn Japanese - Video Vocabulary 16     ', 'http://i1.ytimg.com/vi/r1CICJdlKfc/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(286, 'cVh1TiwgpKo', '       Learn Japanese - Video Vocabulary 17     ', 'http://i1.ytimg.com/vi/cVh1TiwgpKo/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(287, '8R0pgfQXWWo', '       Learn Japanese - Video Vocabulary 18     ', 'http://i1.ytimg.com/vi/8R0pgfQXWWo/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(288, 'sZ-_l_CDU-0', '       Learn Japanese - Video Vocabulary 19     ', 'http://i1.ytimg.com/vi/sZ-_l_CDU-0/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(289, 'Snwibsc2Wkw', '       Learn Japanese - Video Vocabulary 20     ', 'http://i1.ytimg.com/vi/Snwibsc2Wkw/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(290, 'oJFaET40GN4', '       Learn Japanese - Video Vocabulary 21     ', 'http://i1.ytimg.com/vi/oJFaET40GN4/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(291, 'N0T210I64nE', '       Learn Japanese - Video Vocabulary 22     ', 'http://i1.ytimg.com/vi/N0T210I64nE/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(292, 'hyp-TwDNv_k', '       Learn Japanese - Video Vocabulary 24     ', 'http://i1.ytimg.com/vi/hyp-TwDNv_k/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(293, '0S4ekBEJbzQ', '       Learn Japanese - Video Vocabulary 23     ', 'http://i1.ytimg.com/vi/0S4ekBEJbzQ/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(294, 'xOKYZvE-648', '       Learn Japanese - Video Vocabulary 25     ', 'http://i1.ytimg.com/vi/xOKYZvE-648/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(295, 'enHm9bCPAdE', '       Learn Japanese - Video Vocabulary 26     ', 'http://i1.ytimg.com/vi/enHm9bCPAdE/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(296, 'qVcA7e1Ca2s', '       Learn Japanese - Video Vocabulary 27     ', 'http://i1.ytimg.com/vi/qVcA7e1Ca2s/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(297, '0iFCJGjuErk', '       Learn Japanese - Video Vocabulary 28     ', 'http://i1.ytimg.com/vi/0iFCJGjuErk/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(298, 'S34csQRgtZM', '       Learn Japanese - Video Vocabulary 29     ', 'http://i1.ytimg.com/vi/S34csQRgtZM/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(299, 'XhjNVptd04Q', '       Learn Japanese - Video Vocabulary 30     ', 'http://i1.ytimg.com/vi/XhjNVptd04Q/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(300, 'srDKFaQIBx0', '       Learn Japanese - Video Vocabulary 31     ', 'http://i1.ytimg.com/vi/srDKFaQIBx0/hqdefault.jpg', 0, '2:04 ', '', 0, '', NULL, NULL),
(301, 'WHcikJkuxQw', '       Learn Japanese - Video Vocabulary 32     ', 'http://i1.ytimg.com/vi/WHcikJkuxQw/hqdefault.jpg', 0, '2:03 ', '', 0, '', NULL, NULL),
(302, 'yei8VWSApQo', '       Learn Japanese - Video Vocabulary 33     ', 'http://i1.ytimg.com/vi/yei8VWSApQo/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(303, 'j35myRYN7ZQ', '       Learn Japanese - Video Vocabulary 34     ', 'http://i1.ytimg.com/vi/j35myRYN7ZQ/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(304, 'dVBbbF1wkec', '       Learn Japanese - Video Vocabulary 35     ', 'http://i1.ytimg.com/vi/dVBbbF1wkec/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(305, 'QnWY1nEwK_s', '       Learn Japanese - Video Vocabulary 36     ', 'http://i1.ytimg.com/vi/QnWY1nEwK_s/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(306, 'XS9rsnsG02k', '       Learn Japanese - Video Vocabulary 37     ', 'http://i1.ytimg.com/vi/XS9rsnsG02k/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(307, 'uPMLeWgWW6A', '       Learn Japanese - Video Vocabulary 38     ', 'http://i1.ytimg.com/vi/uPMLeWgWW6A/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(308, 'Ad6sVAH3FrI', '       Learn Japanese - Video Vocabulary 39     ', 'http://i1.ytimg.com/vi/Ad6sVAH3FrI/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(309, 'xlrgYSjzNTQ', '       Learn Japanese - Video Vocabulary 40     ', 'http://i1.ytimg.com/vi/xlrgYSjzNTQ/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(310, 'aAqvB-l67hY', '       Learn Japanese - Video Vocabulary 41     ', 'http://i1.ytimg.com/vi/aAqvB-l67hY/hqdefault.jpg', 0, '2:21 ', '', 0, '', NULL, NULL),
(311, '7MHvK2DLcOM', 'Top 10 Expressions for Agreeing and Disagreeing in Japanese', 'http://i1.ytimg.com/vi/7MHvK2DLcOM/hqdefault.jpg', 0, '2:24 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(312, 'yMYo2CCXNgI', 'Learn the Top 10 Sad Words in Japanese ⚡ Japanese Emotions Vocabulary', 'http://i1.ytimg.com/vi/yMYo2CCXNgI/hqdefault.jpg', 0, '2:38 ', '', 0, '', NULL, '2016-09-26 19:43:13'),
(313, 'qNk7XqYvVtc', '       Ask a Japanese Teacher! How often should I use WATASHI WA?     ', 'http://i1.ytimg.com/vi/qNk7XqYvVtc/hqdefault.jpg', 0, '2:18 ', NULL, 0, '', '2016-09-26 03:28:09', '2016-09-26 03:43:03'),
(314, 'LziAHbw2k6Y', 'Top 20 Must-Know Family Words in Japanese', 'http://i1.ytimg.com/vi/LziAHbw2k6Y/hqdefault.jpg', 0, '5:03 ', NULL, 0, '', '2016-09-26 18:29:40', '2016-09-26 19:43:13'),
(315, 'nuDZN8Ftxps', 'Top 20 Travel Phrases You Should Know in Japanese - Vocabulary with Risa', 'http://i1.ytimg.com/vi/nuDZN8Ftxps/hqdefault.jpg', 0, '5:20 ', NULL, 0, '', '2016-09-26 18:29:40', '2016-09-26 19:43:13'),
(316, 'WDCEjJGQWVk', 'Top 10 Responses to &quot;How are you?&quot; in Japanese', 'http://i1.ytimg.com/vi/WDCEjJGQWVk/hqdefault.jpg', 0, '1:51 ', NULL, 0, '', '2016-09-26 18:29:40', '2016-09-26 19:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `yvideo_playlist`
--

CREATE TABLE `yvideo_playlist` (
  `video_id` int(10) UNSIGNED NOT NULL,
  `playlist_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `yvideo_playlist`
--

INSERT INTO `yvideo_playlist` (`video_id`, `playlist_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(20, 14),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
(59, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(67, 14),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(73, 3),
(73, 14),
(74, 3),
(75, 3),
(76, 3),
(77, 3),
(78, 4),
(79, 4),
(80, 4),
(81, 4),
(82, 4),
(83, 4),
(84, 4),
(85, 4),
(86, 4),
(87, 4),
(88, 4),
(89, 5),
(89, 14),
(90, 5),
(91, 5),
(92, 5),
(93, 5),
(94, 5),
(95, 5),
(96, 5),
(97, 5),
(98, 5),
(99, 5),
(100, 5),
(101, 6),
(102, 6),
(103, 6),
(104, 6),
(105, 6),
(106, 6),
(107, 6),
(108, 6),
(109, 6),
(110, 6),
(111, 6),
(112, 6),
(113, 6),
(114, 6),
(115, 6),
(116, 6),
(117, 7),
(118, 7),
(119, 7),
(120, 7),
(121, 7),
(122, 7),
(123, 7),
(124, 7),
(125, 7),
(126, 7),
(127, 7),
(128, 7),
(129, 7),
(130, 7),
(131, 7),
(132, 7),
(133, 7),
(134, 7),
(135, 7),
(136, 7),
(137, 8),
(138, 8),
(139, 8),
(140, 8),
(141, 8),
(142, 8),
(143, 8),
(144, 8),
(145, 8),
(146, 8),
(147, 8),
(148, 8),
(149, 8),
(150, 8),
(151, 8),
(152, 8),
(153, 8),
(154, 8),
(155, 8),
(156, 9),
(157, 9),
(158, 9),
(159, 9),
(160, 9),
(161, 9),
(162, 9),
(163, 9),
(164, 9),
(165, 9),
(166, 9),
(167, 9),
(168, 9),
(169, 9),
(170, 10),
(171, 10),
(172, 10),
(173, 10),
(174, 10),
(175, 10),
(176, 10),
(177, 10),
(178, 10),
(179, 10),
(180, 10),
(181, 10),
(182, 10),
(183, 10),
(184, 11),
(185, 11),
(186, 11),
(187, 11),
(188, 11),
(189, 11),
(190, 11),
(191, 11),
(192, 11),
(193, 11),
(194, 11),
(195, 11),
(196, 11),
(197, 11),
(198, 11),
(199, 11),
(200, 11),
(201, 11),
(202, 11),
(203, 11),
(204, 11),
(205, 11),
(206, 11),
(207, 11),
(208, 11),
(209, 11),
(210, 11),
(211, 11),
(212, 11),
(213, 11),
(214, 11),
(215, 11),
(216, 11),
(217, 11),
(218, 11),
(219, 11),
(220, 11),
(221, 11),
(222, 11),
(223, 11),
(224, 11),
(225, 11),
(226, 11),
(227, 11),
(228, 11),
(229, 11),
(230, 11),
(231, 11),
(232, 11),
(233, 11),
(234, 11),
(235, 11),
(236, 11),
(237, 11),
(238, 11),
(239, 11),
(240, 11),
(241, 11),
(242, 11),
(243, 11),
(244, 11),
(245, 11),
(246, 11),
(247, 11),
(248, 11),
(249, 11),
(250, 11),
(251, 11),
(252, 11),
(253, 11),
(254, 11),
(255, 11),
(256, 11),
(257, 11),
(258, 11),
(259, 11),
(260, 11),
(261, 11),
(262, 11),
(263, 11),
(264, 12),
(265, 12),
(266, 12),
(267, 12),
(268, 12),
(269, 12),
(270, 13),
(271, 13),
(272, 13),
(273, 13),
(274, 13),
(275, 13),
(276, 13),
(277, 13),
(278, 13),
(279, 13),
(280, 13),
(281, 13),
(282, 13),
(283, 13),
(284, 13),
(285, 13),
(286, 13),
(287, 13),
(288, 13),
(289, 13),
(290, 13),
(291, 13),
(292, 13),
(293, 13),
(294, 13),
(295, 13),
(296, 13),
(297, 13),
(298, 13),
(299, 13),
(300, 13),
(301, 13),
(302, 13),
(303, 13),
(304, 13),
(305, 13),
(306, 13),
(307, 13),
(308, 13),
(309, 13),
(310, 13),
(311, 1),
(312, 1),
(313, 14),
(314, 1),
(315, 1),
(316, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `ycats`
--
ALTER TABLE `ycats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yplaylists`
--
ALTER TABLE `yplaylists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yplaylists_yid_unique` (`yid`);

--
-- Indexes for table `yvideos`
--
ALTER TABLE `yvideos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yvideos_yid_unique` (`yid`);

--
-- Indexes for table `yvideo_playlist`
--
ALTER TABLE `yvideo_playlist`
  ADD PRIMARY KEY (`video_id`,`playlist_id`),
  ADD KEY `yvideo_playlist_playlist_id_foreign` (`playlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ycats`
--
ALTER TABLE `ycats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `yplaylists`
--
ALTER TABLE `yplaylists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `yvideos`
--
ALTER TABLE `yvideos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yvideo_playlist`
--
ALTER TABLE `yvideo_playlist`
  ADD CONSTRAINT `yvideo_playlist_playlist_id_foreign` FOREIGN KEY (`playlist_id`) REFERENCES `yplaylists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yvideo_playlist_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `yvideos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
