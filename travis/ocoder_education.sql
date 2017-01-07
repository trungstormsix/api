-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2017 at 12:05 PM
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
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `thumbnail` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `intro` varchar(500) NOT NULL,
  `categories_id` int(10) NOT NULL,
  `published` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `alias` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `parent_id` int(10) NOT NULL,
  `published` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `email` varchar(300) NOT NULL,
  `country` varchar(200) NOT NULL,
  `country_code` varchar(25) DEFAULT NULL,
  `language` varchar(100) NOT NULL,
  `app` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `engr_articles`
--

CREATE TABLE `engr_articles` (
  `id` int(11) NOT NULL,
  `intro_img` varchar(255) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `tac_gia` varchar(255) NOT NULL,
  `content` mediumtext,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `vote` int(11) NOT NULL DEFAULT '0',
  `date_edit` datetime DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `params` text,
  `on_face` int(1) NOT NULL DEFAULT '0',
  `order` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_questions`
--

CREATE TABLE `engr_questions` (
  `id` int(12) NOT NULL,
  `question` text NOT NULL,
  `answers` text NOT NULL,
  `correct` text NOT NULL,
  `explanation` text NOT NULL,
  `published` int(2) NOT NULL,
  `level` int(3) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_questions_articles`
--

CREATE TABLE `engr_questions_articles` (
  `id` int(11) NOT NULL,
  `id_questions` int(11) NOT NULL,
  `id_articles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `engr_questions_reports`
--

CREATE TABLE `engr_questions_reports` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `report` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` int(2) NOT NULL,
  `device` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_read_articles`
--

CREATE TABLE `engr_read_articles` (
  `id` int(11) NOT NULL,
  `story_id` int(11) NOT NULL,
  `time_read` datetime NOT NULL,
  `device` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `engr_types`
--

CREATE TABLE `engr_types` (
  `id` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `title_display` varchar(255) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_types_articles`
--

CREATE TABLE `engr_types_articles` (
  `id` int(11) NOT NULL,
  `the_loai` int(11) NOT NULL,
  `truyen_ngan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_types_questions`
--

CREATE TABLE `engr_types_questions` (
  `id` int(12) NOT NULL,
  `question_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `engr_vote`
--

CREATE TABLE `engr_vote` (
  `id` int(11) UNSIGNED NOT NULL,
  `device` varchar(255) CHARACTER SET utf8 NOT NULL,
  `story_id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `enli_cat`
--

CREATE TABLE `enli_cat` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `app` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enli_cat_dl`
--

CREATE TABLE `enli_cat_dl` (
  `cat_id` int(11) NOT NULL,
  `dl_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enli_dialogs`
--

CREATE TABLE `enli_dialogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `dialog` text,
  `status` int(2) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `question` text,
  `vocabulary` text,
  `note` text,
  `downloaded` int(2) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `liked` int(11) NOT NULL DEFAULT '0',
  `question_link` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enli_questions`
--

CREATE TABLE `enli_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answers` text NOT NULL,
  `correct` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enli_reports`
--

CREATE TABLE `enli_reports` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `dl_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `entest_cat`
--

CREATE TABLE `entest_cat` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `updated` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `entest_question`
--

CREATE TABLE `entest_question` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answers` text NOT NULL,
  `correct` varchar(255) NOT NULL,
  `test_id` int(11) NOT NULL,
  `explaination` text,
  `updated` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `entest_test`
--

CREATE TABLE `entest_test` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `link` text NOT NULL,
  `got` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `est_categories`
--

CREATE TABLE `est_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `author` varchar(255) DEFAULT NULL,
  `app` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL DEFAULT 'en',
  `link` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `est_cat_dl`
--

CREATE TABLE `est_cat_dl` (
  `cat_id` int(11) NOT NULL,
  `dl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `est_dialogs`
--

CREATE TABLE `est_dialogs` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `audio` varchar(255) NOT NULL,
  `dialog` text NOT NULL,
  `status` int(2) NOT NULL,
  `link` text NOT NULL,
  `note` text NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `liked` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `examples`
--

CREATE TABLE `examples` (
  `id` int(11) NOT NULL,
  `example` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `funny_image_like`
--

CREATE TABLE `funny_image_like` (
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `liked` int(2) NOT NULL COMMENT '1: like, -1: dislike'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idiom_question`
--

CREATE TABLE `idiom_question` (
  `idiom_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `id_cats`
--

CREATE TABLE `id_cats` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `id_cat_id`
--

CREATE TABLE `id_cat_id` (
  `cat_id` int(11) NOT NULL,
  `id_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `id_examples`
--

CREATE TABLE `id_examples` (
  `id` int(11) NOT NULL,
  `example` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `id_idioms`
--

CREATE TABLE `id_idioms` (
  `id` int(11) NOT NULL,
  `word` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mean` text CHARACTER SET utf8 NOT NULL,
  `example` text CHARACTER SET utf8,
  `updated` datetime NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `is_pro` int(11) NOT NULL DEFAULT '0',
  `is_got` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `il_articles`
--

CREATE TABLE `il_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` int(11) NOT NULL,
  `audio` varchar(255) NOT NULL,
  `is_pro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `il_categories`
--

CREATE TABLE `il_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(40) DEFAULT 'General',
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `il_cat_voc`
--

CREATE TABLE `il_cat_voc` (
  `cat_id` int(11) NOT NULL,
  `voc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `il_vocabularies`
--

CREATE TABLE `il_vocabularies` (
  `id` int(11) NOT NULL,
  `en` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `audio` text NOT NULL,
  `mean` text NOT NULL,
  `pictures` text NOT NULL,
  `sentences` text NOT NULL,
  `pronuciation` varchar(50) NOT NULL,
  `intro_mean` text,
  `status` int(2) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  `liked` int(11) NOT NULL,
  `is_pro` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `il_voc_sentence`
--

CREATE TABLE `il_voc_sentence` (
  `voc_id` int(11) NOT NULL,
  `sentence_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `listening_grammar`
--

CREATE TABLE `listening_grammar` (
  `dialog_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `ex` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lookup`
--

CREATE TABLE `lookup` (
  `id` int(11) NOT NULL,
  `word` varchar(255) NOT NULL,
  `audio` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  `count` int(11) NOT NULL,
  `fixed` int(11) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nusers`
--

CREATE TABLE `nusers` (
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

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `picvoc_categories`
--

CREATE TABLE `picvoc_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `intro_image` varchar(255) NOT NULL DEFAULT 'no_image.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `picvoc_cat_voc`
--

CREATE TABLE `picvoc_cat_voc` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `voc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `picvoc_vocabularies`
--

CREATE TABLE `picvoc_vocabularies` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `en_us` varchar(100) NOT NULL,
  `en_us_type` varchar(8) NOT NULL,
  `en_us_pr` varchar(100) NOT NULL,
  `en_us_audio` varchar(255) NOT NULL,
  `en_us_mean` text NOT NULL,
  `en_us_ex` text NOT NULL,
  `vi_vn` varchar(255) NOT NULL,
  `en_uk_pr` varchar(255) NOT NULL,
  `en_uk_audio` varchar(255) NOT NULL DEFAULT '',
  `es` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `more_images` text NOT NULL,
  `params` text NOT NULL,
  `report` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  `liked` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prm_apps`
--

CREATE TABLE `prm_apps` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `ad_rate` int(4) NOT NULL DEFAULT '8',
  `key_startapp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prm_groups`
--

CREATE TABLE `prm_groups` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Table structure for table `sentences`
--

CREATE TABLE `sentences` (
  `id` int(11) NOT NULL,
  `sentence` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `groupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `yvideo_playlist`
--

CREATE TABLE `yvideo_playlist` (
  `video_id` int(10) UNSIGNED NOT NULL,
  `playlist_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `engr_articles`
--
ALTER TABLE `engr_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_questions`
--
ALTER TABLE `engr_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_questions_articles`
--
ALTER TABLE `engr_questions_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_read_articles`
--
ALTER TABLE `engr_read_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_types`
--
ALTER TABLE `engr_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_types_articles`
--
ALTER TABLE `engr_types_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_types_questions`
--
ALTER TABLE `engr_types_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engr_vote`
--
ALTER TABLE `engr_vote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enli_cat`
--
ALTER TABLE `enli_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enli_dialogs`
--
ALTER TABLE `enli_dialogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enli_questions`
--
ALTER TABLE `enli_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enli_reports`
--
ALTER TABLE `enli_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entest_cat`
--
ALTER TABLE `entest_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entest_question`
--
ALTER TABLE `entest_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entest_test`
--
ALTER TABLE `entest_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `est_categories`
--
ALTER TABLE `est_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `est_cat_dl`
--
ALTER TABLE `est_cat_dl`
  ADD PRIMARY KEY (`cat_id`,`dl_id`);

--
-- Indexes for table `est_dialogs`
--
ALTER TABLE `est_dialogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `funny_image_like`
--
ALTER TABLE `funny_image_like`
  ADD PRIMARY KEY (`user_id`,`image_id`);

--
-- Indexes for table `id_cats`
--
ALTER TABLE `id_cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `id_examples`
--
ALTER TABLE `id_examples`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `id_idioms`
--
ALTER TABLE `id_idioms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word` (`word`),
  ADD KEY `word_2` (`word`),
  ADD KEY `word_3` (`word`),
  ADD KEY `word_4` (`word`);

--
-- Indexes for table `il_articles`
--
ALTER TABLE `il_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `il_categories`
--
ALTER TABLE `il_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `il_cat_voc`
--
ALTER TABLE `il_cat_voc`
  ADD PRIMARY KEY (`cat_id`,`voc_id`);

--
-- Indexes for table `il_vocabularies`
--
ALTER TABLE `il_vocabularies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `il_voc_sentence`
--
ALTER TABLE `il_voc_sentence`
  ADD PRIMARY KEY (`voc_id`,`sentence_id`);

--
-- Indexes for table `listening_grammar`
--
ALTER TABLE `listening_grammar`
  ADD KEY `dialog_id` (`dialog_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `lookup`
--
ALTER TABLE `lookup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nusers`
--
ALTER TABLE `nusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

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
-- Indexes for table `picvoc_categories`
--
ALTER TABLE `picvoc_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `picvoc_cat_voc`
--
ALTER TABLE `picvoc_cat_voc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `picvoc_vocabularies`
--
ALTER TABLE `picvoc_vocabularies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `en_us` (`en_us`,`vi_vn`);

--
-- Indexes for table `prm_apps`
--
ALTER TABLE `prm_apps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prm_groups`
--
ALTER TABLE `prm_groups`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `sentences`
--
ALTER TABLE `sentences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ycats`
--
ALTER TABLE `ycats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yplaylists`
--
ALTER TABLE `yplaylists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yvideos`
--
ALTER TABLE `yvideos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143480;
--
-- AUTO_INCREMENT for table `engr_articles`
--
ALTER TABLE `engr_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;
--
-- AUTO_INCREMENT for table `engr_questions`
--
ALTER TABLE `engr_questions`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5099;
--
-- AUTO_INCREMENT for table `engr_questions_articles`
--
ALTER TABLE `engr_questions_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3210;
--
-- AUTO_INCREMENT for table `engr_read_articles`
--
ALTER TABLE `engr_read_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99707;
--
-- AUTO_INCREMENT for table `engr_types`
--
ALTER TABLE `engr_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;
--
-- AUTO_INCREMENT for table `engr_types_articles`
--
ALTER TABLE `engr_types_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;
--
-- AUTO_INCREMENT for table `engr_types_questions`
--
ALTER TABLE `engr_types_questions`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7520;
--
-- AUTO_INCREMENT for table `engr_vote`
--
ALTER TABLE `engr_vote`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52679;
--
-- AUTO_INCREMENT for table `enli_cat`
--
ALTER TABLE `enli_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `enli_dialogs`
--
ALTER TABLE `enli_dialogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1674;
--
-- AUTO_INCREMENT for table `enli_questions`
--
ALTER TABLE `enli_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4403;
--
-- AUTO_INCREMENT for table `enli_reports`
--
ALTER TABLE `enli_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;
--
-- AUTO_INCREMENT for table `entest_cat`
--
ALTER TABLE `entest_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `entest_question`
--
ALTER TABLE `entest_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8543;
--
-- AUTO_INCREMENT for table `entest_test`
--
ALTER TABLE `entest_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=801;
--
-- AUTO_INCREMENT for table `est_categories`
--
ALTER TABLE `est_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `est_dialogs`
--
ALTER TABLE `est_dialogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=705;
--
-- AUTO_INCREMENT for table `id_cats`
--
ALTER TABLE `id_cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `id_examples`
--
ALTER TABLE `id_examples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2004;
--
-- AUTO_INCREMENT for table `id_idioms`
--
ALTER TABLE `id_idioms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4489;
--
-- AUTO_INCREMENT for table `il_articles`
--
ALTER TABLE `il_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `il_categories`
--
ALTER TABLE `il_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `il_vocabularies`
--
ALTER TABLE `il_vocabularies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2360;
--
-- AUTO_INCREMENT for table `lookup`
--
ALTER TABLE `lookup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2610;
--
-- AUTO_INCREMENT for table `nusers`
--
ALTER TABLE `nusers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `picvoc_categories`
--
ALTER TABLE `picvoc_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `picvoc_cat_voc`
--
ALTER TABLE `picvoc_cat_voc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4461;
--
-- AUTO_INCREMENT for table `picvoc_vocabularies`
--
ALTER TABLE `picvoc_vocabularies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3579;
--
-- AUTO_INCREMENT for table `prm_apps`
--
ALTER TABLE `prm_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `prm_groups`
--
ALTER TABLE `prm_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sentences`
--
ALTER TABLE `sentences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=606;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ycats`
--
ALTER TABLE `ycats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `yplaylists`
--
ALTER TABLE `yplaylists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `yvideos`
--
ALTER TABLE `yvideos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=405;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
