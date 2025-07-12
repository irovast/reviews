-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 12, 2025 at 01:43 AM
-- Server version: 10.6.22-MariaDB-cll-lve-log
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irovimiy_review`
--

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` int(11) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `google_link` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `slug`, `name`, `logo_url`, `google_link`, `email`, `user_id`) VALUES
(4, 'Afandina', 'Afandina', 'logos/6849245e57378.jpeg', 'ChIJ0zanWAH39T4RHPJ_-usOL2Q', 'afandinanagah@gmail.com', 2),
(8, 'kiwkiw', 'tytyrtyrt', 'logos/684be37f0fda6.png', 'rtyrtytry', 'irovast@gmail.com', 6),
(9, 'Marwaghassan', 'Marwa ghassan', 'logos/686e322b8ccfc.png', 'ChIJVWXq6VlZXz4RCU7Snu2DWF8', 'irovast@gmail.com', 7),
(10, 'sevana', 'Sevana', 'logos/686e407d3b95e.jpeg', 'ChIJV62XRABdXz4R8nnHzv191u0', 'Info@sevanabakery.com', 8);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `business_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(64) DEFAULT NULL,
  `device` varchar(64) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `business_id`, `name`, `email`, `message`, `created_at`, `ip`, `device`, `location`) VALUES
(7, 4, 'rdfgdfg', 'dfgdfg', 'dfgdfgdf', '2025-06-13 09:07:29', '108.171.103.161', 'Desktop', 'Boston, Massachusetts, United States'),
(3, 4, 'dfgdfgdf', '0500252525', 'dsfgsdfgdfgd', '2025-06-13 07:53:15', '108.171.103.161', 'Desktop', 'Boston, Massachusetts, United States'),
(4, 4, 'khgjkhgj', '05050505', 'jhgkgjhkh', '2025-06-13 07:54:21', '108.171.103.161', 'Desktop', 'Boston, Massachusetts, United States'),
(5, 4, 'huiyui', '0553060967', 'yuiyuiyuiy', '2025-06-13 08:12:24', '108.171.103.161', 'Desktop', 'Boston, Massachusetts, United States'),
(9, 4, 'Maged ', '0585284404', 'Hi', '2025-06-26 18:48:53', '92.99.90.44', 'Mobile', 'Dubai, Dubai, United Arab Emirates'),
(8, 4, 'hfghfg', 'fhghfgh', 'fghfghf', '2025-06-13 09:07:38', '108.171.103.161', 'Desktop', 'Boston, Massachusetts, United States'),
(10, 4, '????', '0502344756', '??????', '2025-06-28 16:11:48', '2001:8f8:1f06:5a87:d8d4:b9b0:5871:e9d', 'Mobile', 'Dubai, Dubai, United Arab Emirates'),
(11, 4, '???? ', '0564703891', '??? ??? ', '2025-06-29 19:38:05', '2001:8f8:1f52:7842:9490:16fb:8e95:b890', 'Mobile', 'Sharjah, Sharjah, United Arab Emirates'),
(12, 4, 'Marwa ghassan ', '503141005', '?????', '2025-07-07 01:00:16', '2001:8f8:1f2a:a7aa:1d0:8719:8216:72a9', 'Mobile', 'Dubai, Dubai, United Arab Emirates'),
(13, 10, 'Taher', '0562221004', 'Test ', '2025-07-10 14:39:34', '2001:8f8:1d63:3f38:c466:82ff:fe62:5b16', 'Mobile', 'Dubai, Dubai, United Arab Emirates');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `business_id` int(11) DEFAULT NULL,
  `happy_clicks` int(11) DEFAULT 0,
  `angry_clicks` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `business_id`, `happy_clicks`, `angry_clicks`, `views`) VALUES
(3, 8, 2, 4, 3),
(2, 4, 18, 14, 48);

-- --------------------------------------------------------

--
-- Table structure for table `stats_log`
--

CREATE TABLE `stats_log` (
  `id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `happy_clicks` int(11) DEFAULT 0,
  `angry_clicks` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stats_log`
--

INSERT INTO `stats_log` (`id`, `business_id`, `views`, `happy_clicks`, `angry_clicks`, `created_at`) VALUES
(1, 8, 1, 0, 0, '2025-06-13 06:10:27'),
(2, 8, 0, 0, 1, '2025-06-13 06:10:29'),
(3, 8, 0, 1, 0, '2025-06-13 06:10:31'),
(4, 8, 0, 0, 1, '2025-06-13 06:10:33'),
(5, 4, 1, 0, 0, '2025-06-14 23:20:40'),
(6, 4, 0, 1, 0, '2025-06-14 23:21:11'),
(7, 4, 0, 1, 0, '2025-06-14 23:21:20'),
(8, 4, 0, 0, 1, '2025-06-14 23:21:28'),
(9, 4, 1, 0, 0, '2025-06-15 00:36:33'),
(10, 4, 0, 1, 0, '2025-06-15 00:36:38'),
(11, 4, 0, 0, 1, '2025-06-15 00:36:43'),
(12, 4, 1, 0, 0, '2025-06-26 13:04:19'),
(13, 4, 1, 0, 0, '2025-06-26 13:17:10'),
(14, 4, 1, 0, 0, '2025-06-26 14:44:50'),
(15, 4, 1, 0, 0, '2025-06-26 14:45:22'),
(16, 4, 1, 0, 0, '2025-06-26 14:47:19'),
(17, 4, 1, 0, 0, '2025-06-26 14:48:03'),
(18, 4, 1, 0, 0, '2025-06-26 14:48:18'),
(19, 4, 0, 0, 1, '2025-06-26 14:48:22'),
(20, 4, 1, 0, 0, '2025-06-26 23:32:18'),
(21, 4, 1, 0, 0, '2025-06-26 23:32:18'),
(22, 4, 1, 0, 0, '2025-06-27 15:56:39'),
(23, 4, 1, 0, 0, '2025-06-28 11:09:34'),
(24, 4, 1, 0, 0, '2025-06-28 11:54:37'),
(25, 4, 0, 0, 1, '2025-06-28 11:57:08'),
(26, 4, 1, 0, 0, '2025-06-28 12:09:57'),
(27, 4, 1, 0, 0, '2025-06-28 12:12:29'),
(28, 4, 0, 1, 0, '2025-06-28 12:12:30'),
(29, 4, 1, 0, 0, '2025-06-28 12:38:51'),
(30, 4, 1, 0, 0, '2025-06-28 12:38:55'),
(31, 4, 0, 0, 1, '2025-06-28 12:39:32'),
(32, 4, 1, 0, 0, '2025-06-28 16:17:17'),
(33, 4, 0, 1, 0, '2025-06-28 16:17:52'),
(34, 4, 0, 0, 1, '2025-06-28 16:18:14'),
(35, 4, 1, 0, 0, '2025-06-28 16:57:57'),
(36, 4, 0, 1, 0, '2025-06-28 16:58:33'),
(37, 4, 1, 0, 0, '2025-06-28 16:59:18'),
(38, 4, 0, 1, 0, '2025-06-28 16:59:37'),
(39, 4, 0, 0, 1, '2025-06-28 17:00:19'),
(40, 4, 1, 0, 0, '2025-06-28 17:07:41'),
(41, 4, 1, 0, 0, '2025-06-28 17:07:43'),
(42, 4, 1, 0, 0, '2025-06-28 17:13:50'),
(43, 4, 1, 0, 0, '2025-06-28 17:27:34'),
(44, 4, 0, 0, 1, '2025-06-28 17:27:35'),
(45, 4, 1, 0, 0, '2025-06-29 15:23:57'),
(46, 4, 0, 1, 0, '2025-06-29 15:23:59'),
(47, 4, 1, 0, 0, '2025-06-29 15:25:13'),
(48, 4, 0, 0, 1, '2025-06-29 15:25:15'),
(49, 4, 1, 0, 0, '2025-06-29 15:34:28'),
(50, 4, 0, 1, 0, '2025-06-29 15:34:37'),
(51, 4, 0, 0, 1, '2025-06-29 15:34:43'),
(52, 4, 1, 0, 0, '2025-06-29 15:36:10'),
(53, 4, 0, 1, 0, '2025-06-29 15:36:17'),
(54, 4, 0, 0, 1, '2025-06-29 15:36:19'),
(55, 4, 1, 0, 0, '2025-06-29 15:37:28'),
(56, 4, 0, 0, 1, '2025-06-29 15:37:29'),
(57, 4, 1, 0, 0, '2025-06-29 15:41:46'),
(58, 4, 0, 1, 0, '2025-06-29 15:41:47'),
(59, 4, 1, 0, 0, '2025-06-30 10:12:09'),
(60, 4, 0, 0, 1, '2025-06-30 10:13:16'),
(61, 4, 1, 0, 0, '2025-06-30 10:14:45'),
(62, 4, 1, 0, 0, '2025-06-30 17:22:18'),
(63, 4, 0, 1, 0, '2025-06-30 17:22:22'),
(64, 4, 1, 0, 0, '2025-06-30 17:22:50'),
(65, 4, 0, 1, 0, '2025-06-30 17:22:54'),
(66, 4, 0, 1, 0, '2025-06-30 17:23:04'),
(67, 4, 1, 0, 0, '2025-06-30 17:23:37'),
(68, 4, 0, 1, 0, '2025-06-30 17:23:39'),
(69, 4, 1, 0, 0, '2025-07-06 13:38:06'),
(70, 4, 1, 0, 0, '2025-07-06 13:38:07'),
(71, 4, 1, 0, 0, '2025-07-06 13:38:09'),
(72, 4, 1, 0, 0, '2025-07-06 13:38:23'),
(73, 4, 0, 0, 1, '2025-07-06 13:38:44'),
(74, 4, 1, 0, 0, '2025-07-06 13:39:10'),
(75, 4, 0, 1, 0, '2025-07-06 13:39:12'),
(76, 4, 1, 0, 0, '2025-07-06 20:59:14'),
(77, 4, 0, 1, 0, '2025-07-06 20:59:25'),
(78, 4, 1, 0, 0, '2025-07-06 20:59:41'),
(79, 4, 1, 0, 0, '2025-07-08 19:18:19'),
(80, 9, 1, 0, 0, '2025-07-09 05:11:45'),
(81, 9, 0, 1, 0, '2025-07-09 05:11:48'),
(82, 10, 1, 0, 0, '2025-07-09 06:12:17'),
(83, 10, 0, 1, 0, '2025-07-09 06:12:21'),
(84, 4, 1, 0, 0, '2025-07-09 06:48:38'),
(85, 4, 1, 0, 0, '2025-07-09 06:48:43'),
(86, 9, 1, 0, 0, '2025-07-09 11:46:51'),
(87, 10, 1, 0, 0, '2025-07-09 11:47:00'),
(88, 10, 1, 0, 0, '2025-07-09 12:25:48'),
(89, 10, 1, 0, 0, '2025-07-09 13:02:12'),
(90, 10, 0, 1, 0, '2025-07-09 13:02:17'),
(91, 9, 1, 0, 0, '2025-07-09 15:32:32'),
(92, 10, 1, 0, 0, '2025-07-09 15:32:32'),
(93, 9, 1, 0, 0, '2025-07-09 15:34:02'),
(94, 9, 1, 0, 0, '2025-07-09 15:34:07'),
(95, 9, 1, 0, 0, '2025-07-09 15:34:11'),
(96, 9, 1, 0, 0, '2025-07-09 15:35:19'),
(97, 10, 1, 0, 0, '2025-07-10 10:38:56'),
(98, 10, 0, 0, 1, '2025-07-10 10:39:02'),
(99, 10, 1, 0, 0, '2025-07-10 10:42:01'),
(100, 10, 0, 1, 0, '2025-07-10 10:42:02'),
(101, 10, 0, 0, 1, '2025-07-10 11:43:16'),
(102, 10, 0, 0, 1, '2025-07-10 11:43:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `plain_password` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `plain_password`) VALUES
(1, 'admin', '92364f430b9675008c6985bbd0cbc0213d39c413ea06f51ebca1521546014bb3', 'admin', NULL),
(2, 'Afandina', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'user', 'admin123'),
(6, 'kiwkiw', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'user', '123456'),
(7, 'Marwaghassan', '0d95a5b0b9673ba20e55dd7e0cb81388838ae52fa7e76bc42cf5ac48dd541ad4', 'user', 'Marwa@2326'),
(8, 'sevana', 'ded48f503d81e639e8bf2ab6cafe2043d9a8272f573c9d1785aac918d65aab17', 'user', 'Sevana@2326');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_id` (`business_id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `business_id` (`business_id`);

--
-- Indexes for table `stats_log`
--
ALTER TABLE `stats_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stats_log`
--
ALTER TABLE `stats_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
