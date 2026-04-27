-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 09:43 PM
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
-- Database: `habit2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `points_reward` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges_categories`
--

CREATE TABLE `badges_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `goal_category_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`id`, `title`, `description`, `goal_category_id`, `created_by`, `start_date`, `end_date`, `is_public`, `created_at`, `updated_at`) VALUES
(1, '1w', '1w', 9, 3, '2025-12-27', '2026-01-03', 1, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(2, 'test1', 't1', 1, 1, '2025-12-28', '2025-12-31', 1, '2025-12-28 13:43:05', '2025-12-28 13:43:05'),
(3, 'test2', 't2', 9, 2, '2025-12-28', '2025-12-31', 1, '2025-12-28 14:31:14', '2025-12-28 14:31:14'),
(4, 'test', 'test', 7, 2, '2026-01-23', '2026-01-30', 1, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(5, 'test5_4', 'wspólny', 16, 6, '2026-02-24', '2026-03-03', 1, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(6, 'test5', 'test', 16, 6, '2026-02-24', '2026-03-03', 1, '2026-02-24 21:19:44', '2026-02-24 21:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_attachments`
--

CREATE TABLE `challenge_attachments` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(191) DEFAULT NULL,
  `original_name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenge_attachments`
--

INSERT INTO `challenge_attachments` (`id`, `challenge_id`, `user_id`, `file_path`, `mime_type`, `original_name`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'challenges/MGA4nsB1RWa7E1ZyE6vz2gxfu3Q3d9S3fv4Z8SQu.png', 'image/png', 'lateralus.png', '2025-12-28 14:31:15', '2025-12-28 14:31:15'),
(2, 5, 6, 'challenges/zmbQjsdG7V0MS6gtneaD5VPDtFBTSX59M1Veb9Nc.png', 'image/png', 'robot.png', '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(3, 6, 6, 'challenges/W8XaaBxrlZhgLdvE61GWcgHC1lNwklLp9his5jTD.png', 'image/png', 'robot.png', '2026-02-24 21:19:44', '2026-02-24 21:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_comments`
--

CREATE TABLE `challenge_comments` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenge_comments`
--

INSERT INTO `challenge_comments` (`id`, `challenge_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 'ok', '2025-12-28 15:28:17', '2025-12-28 15:28:17'),
(3, 2, 2, 'ok', '2025-12-29 21:14:20', '2025-12-29 21:14:20'),
(4, 1, 1, 'ok', '2026-01-10 07:11:01', '2026-01-10 07:11:01'),
(5, 4, 2, 'hhh', '2026-01-23 18:28:12', '2026-01-23 18:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_days`
--

CREATE TABLE `challenge_days` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','done','missed') NOT NULL DEFAULT 'pending',
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge_days`
--

INSERT INTO `challenge_days` (`id`, `challenge_id`, `user_id`, `date`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2025-12-27', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(2, 1, 3, '2025-12-28', 'done', NULL, '2025-12-27 10:50:48', '2025-12-28 13:43:45'),
(3, 1, 3, '2025-12-29', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(4, 1, 3, '2025-12-30', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(5, 1, 3, '2025-12-31', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(6, 1, 3, '2026-01-01', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(7, 1, 3, '2026-01-02', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(8, 1, 3, '2026-01-03', 'pending', NULL, '2025-12-27 10:50:48', '2025-12-27 10:50:48'),
(9, 1, 1, '2025-12-27', 'done', NULL, '2025-12-27 10:51:55', '2025-12-27 11:35:00'),
(10, 1, 1, '2025-12-28', 'done', NULL, '2025-12-27 10:51:55', '2025-12-28 13:41:45'),
(11, 1, 1, '2025-12-29', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(12, 1, 1, '2025-12-30', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(13, 1, 1, '2025-12-31', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(14, 1, 1, '2026-01-01', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(15, 1, 1, '2026-01-02', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(16, 1, 1, '2026-01-03', 'pending', NULL, '2025-12-27 10:51:55', '2025-12-27 10:51:55'),
(17, 1, 2, '2025-12-27', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(18, 1, 2, '2025-12-28', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(19, 1, 2, '2025-12-29', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(20, 1, 2, '2025-12-30', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(21, 1, 2, '2025-12-31', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(22, 1, 2, '2026-01-01', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(23, 1, 2, '2026-01-02', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(24, 1, 2, '2026-01-03', 'pending', NULL, '2025-12-27 11:37:45', '2025-12-27 11:37:45'),
(25, 2, 1, '2025-12-28', 'done', NULL, '2025-12-28 13:43:05', '2025-12-28 13:43:11'),
(26, 2, 1, '2025-12-29', 'pending', NULL, '2025-12-28 13:43:05', '2025-12-28 13:43:05'),
(27, 2, 1, '2025-12-30', 'pending', NULL, '2025-12-28 13:43:05', '2025-12-28 13:43:05'),
(28, 2, 1, '2025-12-31', 'pending', NULL, '2025-12-28 13:43:05', '2025-12-28 13:43:05'),
(29, 2, 2, '2025-12-28', 'done', NULL, '2025-12-28 13:44:12', '2025-12-28 13:44:20'),
(30, 2, 2, '2025-12-29', 'done', NULL, '2025-12-28 13:44:12', '2025-12-29 21:15:16'),
(31, 2, 2, '2025-12-30', 'pending', NULL, '2025-12-28 13:44:12', '2025-12-28 13:44:12'),
(32, 2, 2, '2025-12-31', 'pending', NULL, '2025-12-28 13:44:12', '2025-12-28 13:44:12'),
(33, 3, 2, '2025-12-28', 'done', NULL, '2025-12-28 14:31:15', '2025-12-28 14:31:32'),
(34, 3, 2, '2025-12-29', 'pending', NULL, '2025-12-28 14:31:15', '2025-12-28 14:31:15'),
(35, 3, 2, '2025-12-30', 'pending', NULL, '2025-12-28 14:31:15', '2025-12-28 14:31:15'),
(36, 3, 2, '2025-12-31', 'pending', NULL, '2025-12-28 14:31:15', '2025-12-28 14:31:15'),
(37, 3, 1, '2025-12-28', 'done', NULL, '2025-12-28 14:32:20', '2025-12-28 14:32:24'),
(38, 3, 1, '2025-12-29', 'done', NULL, '2025-12-28 14:32:20', '2025-12-29 20:33:42'),
(39, 3, 1, '2025-12-30', 'pending', NULL, '2025-12-28 14:32:20', '2025-12-28 14:32:20'),
(40, 3, 1, '2025-12-31', 'pending', NULL, '2025-12-28 14:32:20', '2025-12-28 14:32:20'),
(41, 3, 3, '2025-12-28', 'done', NULL, '2025-12-28 14:33:06', '2025-12-28 14:33:14'),
(42, 3, 3, '2025-12-29', 'pending', NULL, '2025-12-28 14:33:06', '2025-12-28 14:33:06'),
(43, 3, 3, '2025-12-30', 'pending', NULL, '2025-12-28 14:33:06', '2025-12-28 14:33:06'),
(44, 3, 3, '2025-12-31', 'pending', NULL, '2025-12-28 14:33:06', '2025-12-28 14:33:06'),
(45, 4, 2, '2026-01-23', 'done', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:52'),
(46, 4, 2, '2026-01-24', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(47, 4, 2, '2026-01-25', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(48, 4, 2, '2026-01-26', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(49, 4, 2, '2026-01-27', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(50, 4, 2, '2026-01-28', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(51, 4, 2, '2026-01-29', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(52, 4, 2, '2026-01-30', 'pending', NULL, '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(53, 4, 1, '2026-01-23', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(54, 4, 1, '2026-01-24', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(55, 4, 1, '2026-01-25', 'done', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:49'),
(56, 4, 1, '2026-01-26', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(57, 4, 1, '2026-01-27', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(58, 4, 1, '2026-01-28', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(59, 4, 1, '2026-01-29', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(60, 4, 1, '2026-01-30', 'pending', NULL, '2026-01-25 14:04:42', '2026-01-25 14:04:42'),
(61, 5, 6, '2026-02-24', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(62, 5, 6, '2026-02-25', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(63, 5, 6, '2026-02-26', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(64, 5, 6, '2026-02-27', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(65, 5, 6, '2026-02-28', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(66, 5, 6, '2026-03-01', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(67, 5, 6, '2026-03-02', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(68, 5, 6, '2026-03-03', 'pending', NULL, '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(69, 6, 6, '2026-02-24', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(70, 6, 6, '2026-02-25', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(71, 6, 6, '2026-02-26', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(72, 6, 6, '2026-02-27', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(73, 6, 6, '2026-02-28', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(74, 6, 6, '2026-03-01', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(75, 6, 6, '2026-03-02', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(76, 6, 6, '2026-03-03', 'pending', NULL, '2026-02-24 21:19:44', '2026-02-24 21:19:44'),
(77, 6, 5, '2026-02-24', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(78, 6, 5, '2026-02-25', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(79, 6, 5, '2026-02-26', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(80, 6, 5, '2026-02-27', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(81, 6, 5, '2026-02-28', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(82, 6, 5, '2026-03-01', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(83, 6, 5, '2026-03-02', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29'),
(84, 6, 5, '2026-03-03', 'pending', NULL, '2026-02-24 21:20:29', '2026-02-24 21:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_invites`
--

CREATE TABLE `challenge_invites` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `invited_user_id` int(11) NOT NULL,
  `invited_by` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge_invites`
--

INSERT INTO `challenge_invites` (`id`, `challenge_id`, `invited_user_id`, `invited_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'accepted', '2025-12-27 10:50:48', '2025-12-27 10:51:55'),
(2, 1, 2, 3, 'accepted', '2025-12-27 10:50:48', '2025-12-27 11:37:45'),
(3, 2, 2, 1, 'accepted', '2025-12-28 13:43:05', '2025-12-28 13:44:12'),
(4, 3, 1, 2, 'accepted', '2025-12-28 14:31:15', '2025-12-28 14:32:20'),
(5, 3, 3, 2, 'accepted', '2025-12-28 14:31:15', '2025-12-28 14:33:06'),
(6, 4, 1, 2, 'accepted', '2026-01-23 18:27:42', '2026-01-25 14:04:42'),
(7, 4, 3, 2, 'pending', '2026-01-23 18:27:42', '2026-01-23 18:27:42'),
(8, 5, 5, 6, 'pending', '2026-02-24 21:17:03', '2026-02-24 21:17:03'),
(9, 6, 5, 6, 'accepted', '2026-02-24 21:19:44', '2026-02-24 21:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_participants`
--

CREATE TABLE `challenge_participants` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('active','finished','abandoned') DEFAULT 'active',
  `joined_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenge_participants`
--

INSERT INTO `challenge_participants` (`id`, `challenge_id`, `user_id`, `status`, `joined_at`) VALUES
(1, 1, 3, 'finished', '2025-12-27 10:50:48'),
(2, 1, 1, 'finished', '2025-12-27 10:51:55'),
(3, 1, 2, 'active', '2025-12-27 11:37:45'),
(4, 2, 1, 'finished', '2025-12-28 13:43:05'),
(5, 2, 2, 'active', '2025-12-28 13:44:12'),
(6, 3, 2, 'active', '2025-12-28 14:31:15'),
(7, 3, 1, 'finished', '2025-12-28 14:32:20'),
(8, 3, 3, 'finished', '2025-12-28 14:33:06'),
(9, 4, 2, 'active', '2026-01-23 18:27:42'),
(10, 4, 1, 'finished', '2026-01-25 14:04:42'),
(11, 5, 6, 'active', '2026-02-24 21:17:03'),
(12, 6, 6, 'active', '2026-02-24 21:19:44'),
(13, 6, 5, 'active', '2026-02-24 21:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_progress`
--

CREATE TABLE `challenge_progress` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `percent_complete` decimal(5,2) DEFAULT 0.00,
  `last_update` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenge_rewards`
--

CREATE TABLE `challenge_rewards` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_name` varchar(150) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` enum('pending','accepted','blocked') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friendships`
--

INSERT INTO `friendships` (`id`, `user_id`, `friend_id`, `status`, `created_at`) VALUES
(1, 1, 2, 'accepted', '2025-12-13 07:39:28'),
(2, 3, 2, 'accepted', '2025-12-16 22:38:20'),
(3, 1, 3, 'accepted', '2025-12-19 15:34:42'),
(4, 5, 6, 'accepted', '2026-02-24 21:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `goal_category_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `interval_days` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','finished','paused') NOT NULL DEFAULT 'active',
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `goal_category_id`, `title`, `description`, `interval_days`, `start_date`, `end_date`, `status`, `is_public`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'tesst2', 'test2', 1, '2025-11-16', '2025-11-30', 'finished', 1, '2025-11-16 13:23:15', '2025-12-02 20:03:37'),
(3, 1, 9, 'test3', 'test3', 3, '2025-11-16', '2025-11-29', 'finished', 1, '2025-11-16 13:28:20', '2025-12-02 20:03:25'),
(4, 1, 8, 'hobby1', 'cel', 1, '2025-11-27', '2025-11-30', 'finished', 1, '2025-11-27 19:34:55', '2025-12-02 20:03:25'),
(5, 1, 10, '11', 'test', 1, '2025-11-27', '2025-11-30', 'finished', 1, '2025-11-27 20:32:34', '2025-12-02 20:03:25'),
(7, 1, 9, 'biegam', '1km', 7, '2025-12-02', '2025-12-10', 'finished', 1, '2025-12-02 20:59:20', '2025-12-13 07:33:04'),
(8, 1, 4, 'bbb', 'bbb', 30, '2025-12-03', '2026-02-03', 'paused', 0, '2025-12-03 21:35:46', '2025-12-18 21:09:19'),
(10, 1, 1, '4332', 'nnn', 1, '2025-12-03', '2025-12-10', 'finished', 1, '2025-12-03 21:38:06', '2025-12-13 07:33:04'),
(11, 1, 9, '123123', '123', 1, '2025-12-03', '2025-12-10', 'finished', 0, '2025-12-03 21:39:30', '2025-12-13 07:33:04'),
(12, 1, 8, 'hahah', 'hahah', 1, '2025-12-03', '2025-12-10', 'finished', 0, '2025-12-03 21:46:58', '2025-12-13 07:33:04'),
(13, 1, 10, 'raz dwa', 'raz dwa', 7, '2025-12-04', '2025-12-26', 'finished', 1, '2025-12-04 20:16:41', '2025-12-27 10:52:01'),
(14, 1, 9, '123', '1', 1, '2025-12-04', '2025-12-11', 'finished', 0, '2025-12-04 20:18:07', '2025-12-13 07:33:04'),
(15, 1, 2, '1', '1', 1, '2025-12-03', '2025-12-11', 'finished', 0, '2025-12-04 20:34:02', '2025-12-13 07:33:04'),
(16, 1, 9, '2', '2', 7, '2025-12-04', '2025-12-11', 'paused', 1, '2025-12-04 20:34:37', '2025-12-04 20:36:38'),
(18, 1, 7, 'test7', 'test', 1, '2025-12-13', '2025-12-21', 'finished', 1, '2025-12-13 09:48:07', '2025-12-26 20:20:55'),
(19, 3, 2, '1', '1', 1, '2025-12-14', '2025-12-28', 'finished', 1, '2025-12-14 10:33:32', '2025-12-30 21:35:32'),
(20, 1, 8, 'test5', 'test5', 2, '2025-12-19', '2025-12-31', 'finished', 0, '2025-12-17 21:16:44', '2026-01-10 07:06:19'),
(21, 1, 1, 'test', 'test', 2, '2025-12-18', '2025-12-25', 'finished', 1, '2025-12-18 20:11:53', '2025-12-26 20:20:55'),
(22, 1, 1, '1', '1', 1, '2025-12-19', '2025-12-26', 'finished', 1, '2025-12-19 15:32:34', '2025-12-27 10:52:01'),
(23, 1, 2, 'xxx', 'xxx', 1, '2025-12-26', '2026-01-02', 'finished', 1, '2025-12-26 20:21:38', '2026-01-10 07:06:19'),
(24, 1, 10, '2', '2', 1, '2025-12-29', '2025-12-31', 'finished', 1, '2025-12-29 20:35:23', '2026-01-10 07:06:19'),
(25, 3, 8, '1', '1', 1, '2025-12-30', '2025-12-30', 'finished', 1, '2025-12-30 21:36:17', '2026-01-23 18:40:48'),
(26, 3, 7, '1', '1', 1, '2025-12-30', '2025-12-31', 'finished', 1, '2025-12-30 21:37:56', '2026-01-23 18:40:48'),
(27, 1, 9, '1', '1', 1, '2026-01-10', '2026-01-10', 'finished', 1, '2026-01-10 07:20:32', '2026-01-12 09:12:47'),
(29, 1, 9, '2', '2', 2, '2026-01-10', '2026-01-10', 'finished', 1, '2026-01-10 07:22:06', '2026-01-12 09:12:47'),
(30, 1, 10, '1', '1', 1, '2026-01-10', '2026-01-12', 'finished', 1, '2026-01-10 08:30:07', '2026-01-23 16:58:39'),
(31, 1, 1, 'test', 'raz dwa', 1, '2026-01-23', '2026-01-30', 'finished', 1, '2026-01-23 16:59:43', '2026-02-02 21:52:46'),
(33, 2, 1, '1', NULL, 1, '2026-01-23', '2026-01-24', 'finished', 1, '2026-01-23 18:34:59', '2026-02-02 21:02:38'),
(34, 2, 1, '1', NULL, 1, '2026-01-23', '2026-01-24', 'finished', 1, '2026-01-23 18:35:03', '2026-02-02 21:02:38'),
(35, 3, 9, 'q', 'q', 1, '2026-01-23', '2026-01-23', 'finished', 1, '2026-01-23 18:41:23', '2026-02-17 22:01:58'),
(36, 2, 4, 'test', 'test', 1, '2026-02-02', '2026-02-03', 'finished', 1, '2026-02-02 21:51:40', '2026-02-05 20:17:58'),
(38, 2, 7, 'test_mobile21_1', 'oooo', 1, '2026-02-03', '2026-02-10', 'finished', 1, '2026-02-03 21:14:16', '2026-02-16 21:37:18'),
(40, 2, 4, 'test_1mobie', 'tekst...test', 2, '2026-02-08', '2026-03-10', 'active', 1, '2026-02-08 06:43:07', '2026-02-08 06:46:43'),
(41, 2, 10, 'jeden', 'opis', 1, '2026-02-16', '2026-02-19', 'finished', 0, '2026-02-16 21:43:58', '2026-02-23 18:07:37'),
(42, 1, 4, 'mobile_test2', 'opis2', 1, '2026-02-16', '2026-02-19', 'finished', 1, '2026-02-16 22:06:35', '2026-02-23 17:40:32'),
(43, 3, 2, 'nauka', 'nauka', 1, '2026-02-17', '2026-02-24', 'active', 1, '2026-02-17 22:06:12', '2026-02-17 22:06:12'),
(44, 1, 4, 'mobile3_1', 'mobile', 1, '2026-02-17', '2026-03-19', 'active', 1, '2026-02-17 22:08:02', '2026-02-24 21:39:17'),
(45, 3, 7, 'mobile4_1', 'mobile4', 1, '2026-02-17', '2026-03-19', 'active', 1, '2026-02-17 22:09:17', '2026-02-17 22:09:52'),
(46, 2, 10, 'cel1', 'opis', 1, '2026-02-23', '2026-03-25', 'active', 1, '2026-02-23 18:08:01', '2026-02-23 18:08:01'),
(48, 6, 16, 'test5', 'test5', 2, '2026-02-24', '2026-03-03', 'active', 1, '2026-02-24 20:57:38', '2026-02-24 20:57:38'),
(49, 6, 9, 'test5_123', 'test1', 2, '2026-02-25', '2026-03-03', 'active', 1, '2026-02-24 21:06:01', '2026-02-24 21:28:32'),
(50, 1, 7, 'test', 'test', 1, '2026-02-24', '2026-03-26', 'active', 1, '2026-02-24 21:40:03', '2026-02-24 21:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `goal_attachments`
--

CREATE TABLE `goal_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goal_id` bigint(20) UNSIGNED NOT NULL,
  `goal_day_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(50) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_attachments`
--

INSERT INTO `goal_attachments` (`id`, `goal_id`, `goal_day_id`, `user_id`, `file_path`, `mime_type`, `original_name`, `created_at`, `updated_at`) VALUES
(2, 2, NULL, 1, 'goals/OSgqzgcUMlchm8fFpXpGLW65ZGAZcPlgRhFWaNWE.png', 'image/png', 'jazz.png', '2025-11-16 13:23:15', '2025-11-16 13:23:15'),
(3, 3, NULL, 1, 'goals/yuSGLINWGG1gqEZuarhM4H8FejFuDtVP76Y0HKqd.png', 'image/png', 'k44.png', '2025-11-16 13:28:20', '2025-11-16 13:28:20'),
(4, 4, NULL, 1, 'goals/XDyrrWokad9nj1s5BGzBWULJyXXADNuvu4RDbnB7.png', 'image/png', 'jazz.png', '2025-11-27 19:34:56', '2025-11-27 19:34:56'),
(5, 5, NULL, 1, 'goals/xGfDlffVEfDF99PVBUh4aiveCji1n9ffyh9HfvMJ.png', 'image/png', 'lateralus.png', '2025-11-27 20:32:35', '2025-11-27 20:32:35'),
(7, 7, NULL, 1, 'goals/UVFl8xQaQH9bso0LGJQc4hCjMs87tfmISSbgcNNC.png', 'image/png', 's3.png', '2025-12-02 20:59:21', '2025-12-02 20:59:21'),
(8, 7, NULL, 1, 'goals/uCMzXtS2MXGiTG9PjTJRtjsHTHgJVjRUgHJWuYZM.png', 'image/png', 's3.png', '2025-12-02 21:30:13', '2025-12-02 21:30:13'),
(9, 8, NULL, 1, 'goals/ucFUMHPolFKoyNwTCQvcos7BjSAPZ3h8QZeCt2xa.png', 'image/png', 'ChatGPT Image Apr 5, 2025, 07_33_22 AM.png', '2025-12-03 21:35:47', '2025-12-03 21:35:47'),
(11, 10, NULL, 1, 'goals/TxqQbea47VxlXaATkE4A3XbaQNmIW6nvv3GhdOKw.png', 'image/png', 's2.png', '2025-12-03 21:38:06', '2025-12-03 21:38:06'),
(12, 11, NULL, 1, 'goals/6G8bZUjYsqfKk6U57M3x6JvxM27iRAPo6Urgj5ER.png', 'image/png', 'lateralus.png', '2025-12-03 21:39:30', '2025-12-03 21:39:30'),
(13, 12, NULL, 1, 'goals/ZWp0127Js0r888IlnWQmcGTAMUb026J4IijIuHTZ.png', 'image/png', 's3.png', '2025-12-03 21:46:58', '2025-12-03 21:46:58'),
(14, 13, NULL, 1, 'goals/p6879RwlV80mSUf96JR3HgxHq16kScpASiUJEKXh.png', 'image/png', 'user_icon.png', '2025-12-04 20:16:42', '2025-12-04 20:16:42'),
(16, 18, NULL, 1, 'goals/8Ol0sTlzWG1FP3ltJ9BjTmVOUVvtRXMMEnCoSanj.png', 'image/png', 'ChatGPT Image Apr 8, 2025, 08_56_19 PM.png', '2025-12-13 09:48:08', '2025-12-13 09:48:08'),
(17, 19, NULL, 3, 'goals/ByHax4PNOtvi0COKWnbDlnhfJiCf5dv0LLGD6JRV.png', 'image/png', 's2.png', '2025-12-14 10:33:33', '2025-12-14 10:33:33'),
(18, 20, NULL, 1, 'goals/cLqums6Lp78cPIFop5vLFI2zKEv8a9XybphEWXlG.png', 'image/png', 'lateralus.png', '2025-12-17 21:16:46', '2025-12-17 21:16:46'),
(19, 21, NULL, 1, 'goals/PIAwlG1ChvaceCvwnMjUXXoNWiuw6UGP0nrT6W76.png', 'image/png', 'Zrzut ekranu 2025-04-13 o 13.08.35 (1).png', '2025-12-18 20:11:55', '2025-12-18 20:11:55'),
(20, 23, NULL, 1, 'goals/vJg4THJR91K7eH6tq0s9X4HgTCSBKDaVrvKDvgZb.png', 'image/png', 'lateralus.png', '2025-12-26 20:21:39', '2025-12-26 20:21:39'),
(21, 24, NULL, 1, 'goals/c8Ll0q1L4fwc490msV1VwEFgokvOMe6kmtdofZfr.png', 'image/png', 'jazz.png', '2025-12-29 20:35:25', '2025-12-29 20:35:25'),
(22, 25, NULL, 3, 'goals/daGWehm27qQLyPdYy2HP3KfpIfI9XgIsUHSkH3sC.png', 'image/png', 's3.png', '2025-12-30 21:36:18', '2025-12-30 21:36:18'),
(23, 30, NULL, 1, 'goals/Fz0qr2ioRZMzwpGoy6JYcilWEzUVqz5pac3krj12.png', 'image/png', 'ChatGPT Image Apr 5, 2025, 07_33_22 AM.png', '2026-01-10 08:30:09', '2026-01-10 08:30:09'),
(24, 30, 204, 1, 'goal_days/OgvDH4jQJbw3YTn94ouNGpj2J1JdXf0xUZIum4JN.png', 'image/png', 'ChatGPT Image Jun 17, 2025, 09_43_09 PM.png', '2026-01-10 08:30:37', '2026-01-10 08:30:37'),
(25, 31, NULL, 1, 'goals/0P6gLK5A1pGYZ5B0PaDFzqRjvQya6XVbz5zdXsrD.png', 'image/png', 's2.png', '2026-01-23 16:59:43', '2026-01-23 16:59:43'),
(26, 31, 207, 1, 'goal_days/ZeTkSe59njIlymoqo0Z0Nw0mN8SpI6DvZCMDbN8W.png', 'image/png', 's2.png', '2026-01-23 17:00:08', '2026-01-23 17:00:08'),
(27, 31, 209, 1, 'goal_days/gEnmIrzbtPjFHvx4nNnci9fYp3f3GtBbfwPu7C4z.png', 'image/png', 's2.png', '2026-01-25 14:22:56', '2026-01-25 14:22:56'),
(28, 31, 210, 1, 'goal_days/Vt97VbmPFqH2x7kIzsec4c94WKRXudcgZ8AoRToT.png', 'image/png', 's1.png', '2026-01-26 21:42:31', '2026-01-26 21:42:31'),
(29, 31, 210, 1, 'goal_days/457t78RdIHKnGE9D9ALsfApM8uluAPBcNkhdD7Uo.png', 'image/png', 'ChatGPT Image Jun 17, 2025, 09_43_09 PM.png', '2026-01-26 21:44:02', '2026-01-26 21:44:02'),
(30, 23, 190, 1, 'goal_days/oz1P3tX9XENZio02Y79PZPdnAEtcrMIyfG3NOaw9.png', 'image/png', 'lateralus.png', '2026-02-19 21:50:00', '2026-02-19 21:50:00'),
(33, 48, NULL, 6, 'goals/qVSY4g6f3ieNfmpM4ZR57g346m7R5CVw2ACsxLTy.png', 'image/png', 'robot.png', '2026-02-24 20:57:38', '2026-02-24 20:57:38'),
(34, 48, 386, 6, 'goal_days/F9cmTN2goOaeMtcRBKK9UnzjsGiVEvHunSnPyTeP.png', 'image/png', 'robot.png', '2026-02-24 20:58:29', '2026-02-24 20:58:29'),
(35, 49, NULL, 6, 'goals/gHB5nAuHPU703Si69bT4MBgMjvLs41JMtt2chscj.png', 'image/png', 'robot.png', '2026-02-24 21:06:01', '2026-02-24 21:06:01'),
(36, 49, 390, 6, 'goal_days/IgDPsStmKeEIoJWsYOba7ZU0sGhvmJgT5hS6rCt5.png', 'image/png', 'robot.png', '2026-02-24 21:06:38', '2026-02-24 21:06:38'),
(37, 49, NULL, 6, 'goals/JSLcOJT1x4OpLpKLorl3ZjxMIfQPexiMdG1318it.png', 'image/png', 'back.png', '2026-02-24 21:26:44', '2026-02-24 21:26:44'),
(38, 49, NULL, 6, 'goals/6JxsFsRNZWnAAzxOa8KhZVihZgI4j8cfeAP2yVp9.png', 'image/png', 'back.png', '2026-02-24 21:27:37', '2026-02-24 21:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `goal_categories`
--

CREATE TABLE `goal_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#64748b',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_categories`
--

INSERT INTO `goal_categories` (`id`, `name`, `color`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Zdrowie', '#22c55e', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(2, 'Nauka / Rozwój', '#3b82f6', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(3, 'Praca / Kariera', '#eab308', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(4, 'Finanse', '#f97316', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(5, 'Relacje / Rodzina', '#ec4899', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(6, 'Samopoczucie / Psychika', '#a855f7', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(7, 'Dom / Organizacja', '#0ea5e9', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(8, 'Hobby / Pasja', '#f97316', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(9, 'Sport / Aktywność', '#16a34a', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(10, 'Inne', '#64748b', NULL, '2025-11-16 10:05:48', '2025-11-16 10:05:48'),
(13, 'test_mobile', '#64748b', 1, '2026-02-02 21:41:21', '2026-02-02 21:41:21'),
(16, 'test5', '#f53505', 6, '2026-02-24 20:56:33', '2026-02-24 20:56:33'),
(17, 'test5_1', '#056dff', 6, '2026-02-24 21:05:08', '2026-02-24 21:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `goal_comments`
--

CREATE TABLE `goal_comments` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_comments`
--

INSERT INTO `goal_comments` (`id`, `goal_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(2, 18, 2, 'ok', '2025-12-13 11:51:49', '2025-12-13 11:51:49'),
(3, 18, 2, 'ok ok', '2025-12-13 11:51:58', '2025-12-13 11:51:58'),
(4, 10, 2, 'panie !!!', '2025-12-16 22:40:08', '2025-12-16 22:40:08'),
(5, 23, 2, 'ok', '2025-12-28 13:46:08', '2025-12-28 13:46:08'),
(6, 23, 3, 'ok', '2025-12-28 14:42:40', '2025-12-28 14:42:40'),
(7, 23, 1, 'git', '2025-12-28 14:51:02', '2025-12-28 14:51:02'),
(8, 26, 1, 'ok', '2026-01-10 07:08:15', '2026-01-10 07:08:15'),
(9, 45, 1, 'powodzenia', '2026-02-25 19:31:55', '2026-02-25 19:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `goal_days`
--

CREATE TABLE `goal_days` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','done','skipped') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goal_days`
--

INSERT INTO `goal_days` (`id`, `goal_id`, `date`, `status`, `note`, `created_at`) VALUES
(5, 2, '2025-11-16', 'pending', NULL, '2025-11-16 13:23:22'),
(6, 2, '2025-11-17', 'pending', NULL, '2025-11-16 13:23:22'),
(7, 2, '2025-11-18', 'pending', NULL, '2025-11-16 13:23:22'),
(8, 2, '2025-11-19', 'pending', NULL, '2025-11-16 13:23:22'),
(9, 2, '2025-11-20', 'pending', NULL, '2025-11-16 13:23:22'),
(10, 2, '2025-11-21', 'pending', NULL, '2025-11-16 13:23:22'),
(11, 2, '2025-11-22', 'pending', NULL, '2025-11-16 13:23:22'),
(12, 2, '2025-11-23', 'pending', NULL, '2025-11-16 13:23:22'),
(13, 2, '2025-11-24', 'pending', NULL, '2025-11-16 13:23:22'),
(14, 2, '2025-11-25', 'pending', NULL, '2025-11-16 13:23:22'),
(15, 2, '2025-11-26', 'pending', NULL, '2025-11-16 13:23:22'),
(16, 2, '2025-11-27', 'pending', NULL, '2025-11-16 13:23:22'),
(17, 2, '2025-11-28', 'pending', NULL, '2025-11-16 13:23:22'),
(18, 2, '2025-11-29', 'pending', NULL, '2025-11-16 13:23:22'),
(19, 2, '2025-11-30', 'pending', NULL, '2025-11-16 13:23:22'),
(20, 3, '2025-11-16', 'pending', NULL, '2025-11-27 19:33:15'),
(21, 3, '2025-11-19', 'pending', NULL, '2025-11-27 19:33:15'),
(22, 3, '2025-11-22', 'pending', NULL, '2025-11-27 19:33:15'),
(23, 3, '2025-11-25', 'pending', NULL, '2025-11-27 19:33:15'),
(24, 3, '2025-11-28', 'pending', NULL, '2025-11-27 19:33:15'),
(25, 4, '2025-11-27', 'done', 'bardzo dobrze', '2025-11-27 19:35:02'),
(26, 4, '2025-11-28', 'pending', NULL, '2025-11-27 19:35:19'),
(27, 4, '2025-11-29', 'pending', NULL, '2025-11-27 19:35:19'),
(28, 4, '2025-11-30', 'pending', NULL, '2025-11-27 19:35:19'),
(29, 5, '2025-11-27', 'done', 'ok ok ok', '2025-11-27 20:32:42'),
(30, 5, '2025-11-28', 'pending', NULL, '2025-11-27 21:04:45'),
(31, 5, '2025-11-29', 'pending', NULL, '2025-11-27 21:04:45'),
(32, 5, '2025-11-30', 'pending', NULL, '2025-11-27 21:04:45'),
(50, 7, '2025-12-02', 'pending', NULL, '2025-12-03 21:29:39'),
(51, 7, '2025-12-09', 'done', '50m', '2025-12-03 21:29:39'),
(52, 8, '2025-12-03', 'pending', NULL, '2025-12-03 21:35:53'),
(53, 8, '2026-01-02', 'pending', NULL, '2025-12-03 21:35:53'),
(54, 8, '2026-02-01', 'pending', NULL, '2025-12-03 21:35:53'),
(63, 12, '2025-12-03', 'pending', NULL, '2025-12-03 21:47:05'),
(64, 12, '2025-12-04', 'done', 'ok', '2025-12-03 21:47:05'),
(65, 12, '2025-12-05', 'pending', NULL, '2025-12-03 21:47:05'),
(66, 12, '2025-12-06', 'pending', NULL, '2025-12-03 21:47:05'),
(67, 12, '2025-12-07', 'pending', NULL, '2025-12-03 21:47:05'),
(68, 12, '2025-12-08', 'pending', NULL, '2025-12-03 21:47:05'),
(69, 12, '2025-12-09', 'pending', NULL, '2025-12-03 21:47:05'),
(70, 12, '2025-12-10', 'pending', NULL, '2025-12-03 21:47:05'),
(71, 10, '2025-12-03', 'pending', NULL, '2025-12-03 21:54:02'),
(72, 10, '2025-12-04', 'done', 'ok', '2025-12-03 21:54:02'),
(73, 10, '2025-12-05', 'pending', NULL, '2025-12-03 21:54:02'),
(74, 10, '2025-12-06', 'pending', NULL, '2025-12-03 21:54:02'),
(75, 10, '2025-12-07', 'pending', NULL, '2025-12-03 21:54:02'),
(76, 10, '2025-12-08', 'done', 'ok', '2025-12-03 21:54:02'),
(77, 10, '2025-12-09', 'pending', NULL, '2025-12-03 21:54:02'),
(78, 10, '2025-12-10', 'pending', NULL, '2025-12-03 21:54:02'),
(83, 11, '2025-12-03', 'pending', NULL, '2025-12-04 20:17:31'),
(84, 11, '2025-12-04', 'done', 'ok', '2025-12-04 20:17:31'),
(85, 11, '2025-12-05', 'pending', NULL, '2025-12-04 20:17:31'),
(86, 11, '2025-12-06', 'pending', NULL, '2025-12-04 20:17:31'),
(87, 11, '2025-12-07', 'pending', NULL, '2025-12-04 20:17:31'),
(88, 11, '2025-12-08', 'pending', NULL, '2025-12-04 20:17:31'),
(89, 11, '2025-12-09', 'pending', NULL, '2025-12-04 20:17:31'),
(90, 11, '2025-12-10', 'pending', NULL, '2025-12-04 20:17:31'),
(91, 14, '2025-12-04', 'done', 'ok', '2025-12-04 20:33:13'),
(92, 14, '2025-12-05', 'pending', NULL, '2025-12-04 20:33:13'),
(93, 14, '2025-12-06', 'pending', NULL, '2025-12-04 20:33:13'),
(94, 14, '2025-12-07', 'pending', NULL, '2025-12-04 20:33:13'),
(95, 14, '2025-12-08', 'pending', NULL, '2025-12-04 20:33:13'),
(96, 14, '2025-12-09', 'pending', NULL, '2025-12-04 20:33:13'),
(97, 14, '2025-12-10', 'pending', NULL, '2025-12-04 20:33:13'),
(98, 14, '2025-12-11', 'pending', NULL, '2025-12-04 20:33:13'),
(107, 16, '2025-12-04', 'done', 'ok', '2025-12-04 20:34:37'),
(108, 16, '2025-12-11', 'pending', NULL, '2025-12-04 20:34:37'),
(109, 13, '2025-12-04', 'done', 'ok', '2025-12-04 20:37:05'),
(110, 13, '2025-12-11', 'pending', NULL, '2025-12-04 20:37:05'),
(111, 13, '2025-12-18', 'done', NULL, '2025-12-04 20:37:05'),
(112, 13, '2025-12-25', 'pending', NULL, '2025-12-04 20:37:05'),
(113, 15, '2025-12-03', 'pending', NULL, '2025-12-04 20:37:52'),
(114, 15, '2025-12-04', 'done', 'ok', '2025-12-04 20:37:52'),
(115, 15, '2025-12-05', 'pending', NULL, '2025-12-04 20:37:52'),
(116, 15, '2025-12-06', 'pending', NULL, '2025-12-04 20:37:52'),
(117, 15, '2025-12-07', 'pending', NULL, '2025-12-04 20:37:52'),
(118, 15, '2025-12-08', 'pending', NULL, '2025-12-04 20:37:52'),
(119, 15, '2025-12-09', 'pending', NULL, '2025-12-04 20:37:52'),
(120, 15, '2025-12-10', 'pending', NULL, '2025-12-04 20:37:52'),
(121, 15, '2025-12-11', 'pending', NULL, '2025-12-04 20:37:52'),
(144, 18, '2025-12-13', 'done', 'ok', '2025-12-13 09:48:08'),
(145, 18, '2025-12-14', 'done', 'ok', '2025-12-13 09:48:08'),
(146, 18, '2025-12-15', 'pending', NULL, '2025-12-13 09:48:08'),
(147, 18, '2025-12-16', 'pending', NULL, '2025-12-13 09:48:08'),
(148, 18, '2025-12-17', 'done', 'ok', '2025-12-13 09:48:08'),
(149, 18, '2025-12-18', 'pending', NULL, '2025-12-13 09:48:08'),
(150, 18, '2025-12-19', 'pending', NULL, '2025-12-13 09:48:08'),
(151, 18, '2025-12-20', 'pending', NULL, '2025-12-13 09:48:08'),
(152, 18, '2025-12-21', 'pending', NULL, '2025-12-13 09:48:08'),
(153, 19, '2025-12-14', 'done', 'ok', '2025-12-14 10:33:33'),
(154, 19, '2025-12-15', 'pending', NULL, '2025-12-14 10:33:33'),
(155, 19, '2025-12-16', 'done', 'ok', '2025-12-14 10:33:33'),
(156, 19, '2025-12-17', 'pending', NULL, '2025-12-14 10:33:33'),
(157, 19, '2025-12-18', 'pending', NULL, '2025-12-14 10:33:33'),
(158, 19, '2025-12-19', 'pending', NULL, '2025-12-14 10:33:33'),
(159, 19, '2025-12-20', 'pending', NULL, '2025-12-14 10:33:33'),
(160, 19, '2025-12-21', 'pending', NULL, '2025-12-14 10:33:33'),
(161, 19, '2025-12-22', 'pending', NULL, '2025-12-14 10:33:33'),
(162, 19, '2025-12-23', 'pending', NULL, '2025-12-14 10:33:33'),
(163, 19, '2025-12-24', 'pending', NULL, '2025-12-14 10:33:33'),
(164, 19, '2025-12-25', 'pending', NULL, '2025-12-14 10:33:33'),
(165, 19, '2025-12-26', 'pending', NULL, '2025-12-14 10:33:33'),
(166, 19, '2025-12-27', 'done', 'ok', '2025-12-14 10:33:33'),
(167, 19, '2025-12-28', 'done', 'ok', '2025-12-14 10:33:33'),
(168, 20, '2025-12-19', 'pending', NULL, '2025-12-17 21:16:46'),
(169, 20, '2025-12-21', 'pending', NULL, '2025-12-17 21:16:46'),
(170, 20, '2025-12-23', 'pending', NULL, '2025-12-17 21:16:46'),
(171, 20, '2025-12-25', 'pending', NULL, '2025-12-17 21:16:46'),
(172, 20, '2025-12-27', 'pending', NULL, '2025-12-17 21:16:46'),
(173, 20, '2025-12-29', 'done', 'ok', '2025-12-17 21:16:46'),
(174, 20, '2025-12-31', 'pending', NULL, '2025-12-17 21:16:46'),
(175, 21, '2025-12-18', 'done', 'ok', '2025-12-18 20:11:55'),
(176, 21, '2025-12-20', 'pending', NULL, '2025-12-18 20:11:55'),
(177, 21, '2025-12-22', 'pending', NULL, '2025-12-18 20:11:55'),
(178, 21, '2025-12-24', 'pending', NULL, '2025-12-18 20:11:55'),
(179, 22, '2025-12-19', 'done', 'ok', '2025-12-19 15:32:34'),
(180, 22, '2025-12-20', 'pending', NULL, '2025-12-19 15:32:34'),
(181, 22, '2025-12-21', 'pending', NULL, '2025-12-19 15:32:34'),
(182, 22, '2025-12-22', 'pending', NULL, '2025-12-19 15:32:34'),
(183, 22, '2025-12-23', 'pending', NULL, '2025-12-19 15:32:34'),
(184, 22, '2025-12-24', 'pending', NULL, '2025-12-19 15:32:34'),
(185, 22, '2025-12-25', 'pending', NULL, '2025-12-19 15:32:34'),
(186, 22, '2025-12-26', 'pending', NULL, '2025-12-19 15:32:34'),
(187, 23, '2025-12-26', 'done', 'ok', '2025-12-26 20:21:39'),
(188, 23, '2025-12-27', 'pending', NULL, '2025-12-26 20:21:39'),
(189, 23, '2025-12-28', 'done', 'ok', '2025-12-26 20:21:39'),
(190, 23, '2025-12-29', 'pending', 'ok', '2025-12-26 20:21:39'),
(191, 23, '2025-12-30', 'done', 'ok', '2025-12-26 20:21:39'),
(192, 23, '2025-12-31', 'pending', NULL, '2025-12-26 20:21:39'),
(193, 23, '2026-01-01', 'pending', NULL, '2025-12-26 20:21:39'),
(194, 23, '2026-01-02', 'pending', NULL, '2025-12-26 20:21:39'),
(195, 24, '2025-12-29', 'done', 'ok', '2025-12-29 20:35:25'),
(196, 24, '2025-12-30', 'done', 'ok', '2025-12-29 20:35:25'),
(197, 24, '2025-12-31', 'pending', NULL, '2025-12-29 20:35:25'),
(198, 25, '2025-12-30', 'done', 'ok', '2025-12-30 21:36:18'),
(199, 26, '2025-12-30', 'done', 'ok', '2025-12-30 21:37:56'),
(200, 26, '2025-12-31', 'pending', NULL, '2025-12-30 21:37:56'),
(201, 27, '2026-01-10', 'done', 'ok', '2026-01-10 07:20:32'),
(203, 29, '2026-01-10', 'done', 'ok', '2026-01-10 07:22:06'),
(204, 30, '2026-01-10', 'done', 'ok', '2026-01-10 08:30:09'),
(205, 30, '2026-01-11', 'pending', NULL, '2026-01-10 08:30:09'),
(206, 30, '2026-01-12', 'pending', NULL, '2026-01-10 08:30:09'),
(207, 31, '2026-01-23', 'done', 'ok', '2026-01-23 16:59:43'),
(208, 31, '2026-01-24', 'pending', NULL, '2026-01-23 16:59:43'),
(209, 31, '2026-01-25', 'done', 'ok', '2026-01-23 16:59:43'),
(210, 31, '2026-01-26', 'done', 'ok', '2026-01-23 16:59:43'),
(211, 31, '2026-01-27', 'pending', NULL, '2026-01-23 16:59:44'),
(212, 31, '2026-01-28', 'pending', NULL, '2026-01-23 16:59:44'),
(213, 31, '2026-01-29', 'pending', NULL, '2026-01-23 16:59:44'),
(214, 31, '2026-01-30', 'pending', NULL, '2026-01-23 16:59:44'),
(223, 33, '2026-01-23', 'pending', NULL, '2026-01-23 18:34:59'),
(224, 33, '2026-01-24', 'pending', NULL, '2026-01-23 18:35:00'),
(225, 34, '2026-01-23', 'done', 'ok', '2026-01-23 18:35:03'),
(226, 34, '2026-01-24', 'pending', NULL, '2026-01-23 18:35:03'),
(227, 35, '2026-01-23', 'done', 'ok', '2026-01-23 18:41:24'),
(228, 36, '2026-02-02', 'pending', NULL, '2026-02-02 21:51:40'),
(229, 36, '2026-02-03', 'pending', NULL, '2026-02-02 21:51:40'),
(231, 38, '2026-02-03', 'pending', NULL, '2026-02-03 21:14:16'),
(232, 38, '2026-02-04', 'pending', NULL, '2026-02-03 21:14:16'),
(233, 38, '2026-02-05', 'done', NULL, '2026-02-03 21:14:16'),
(234, 38, '2026-02-06', 'pending', NULL, '2026-02-03 21:14:16'),
(235, 38, '2026-02-07', 'done', NULL, '2026-02-03 21:14:16'),
(236, 38, '2026-02-08', 'pending', NULL, '2026-02-03 21:14:16'),
(237, 38, '2026-02-09', 'pending', NULL, '2026-02-03 21:14:16'),
(238, 38, '2026-02-10', 'pending', NULL, '2026-02-03 21:14:16'),
(253, 40, '2026-02-08', 'done', NULL, '2026-02-08 06:43:07'),
(254, 40, '2026-02-10', 'pending', NULL, '2026-02-08 06:43:07'),
(255, 40, '2026-02-12', 'pending', NULL, '2026-02-08 06:43:07'),
(256, 40, '2026-02-14', 'pending', NULL, '2026-02-08 06:43:07'),
(257, 40, '2026-02-16', 'done', NULL, '2026-02-08 06:43:07'),
(258, 40, '2026-02-18', 'pending', NULL, '2026-02-08 06:43:07'),
(259, 40, '2026-02-20', 'pending', NULL, '2026-02-08 06:43:07'),
(260, 40, '2026-02-22', 'pending', NULL, '2026-02-08 06:43:07'),
(261, 40, '2026-02-24', 'pending', NULL, '2026-02-08 06:43:07'),
(262, 40, '2026-02-26', 'pending', NULL, '2026-02-08 06:43:07'),
(263, 40, '2026-02-28', 'pending', NULL, '2026-02-08 06:43:07'),
(264, 40, '2026-03-02', 'pending', NULL, '2026-02-08 06:43:07'),
(265, 40, '2026-03-04', 'pending', NULL, '2026-02-08 06:43:07'),
(266, 40, '2026-03-06', 'pending', NULL, '2026-02-08 06:43:07'),
(267, 40, '2026-03-08', 'pending', NULL, '2026-02-08 06:43:07'),
(268, 40, '2026-03-10', 'pending', NULL, '2026-02-08 06:43:07'),
(269, 41, '2026-02-16', 'done', NULL, '2026-02-16 21:43:58'),
(270, 41, '2026-02-17', 'done', NULL, '2026-02-16 21:43:58'),
(271, 41, '2026-02-18', 'done', NULL, '2026-02-16 21:43:58'),
(272, 41, '2026-02-19', 'pending', NULL, '2026-02-16 21:43:58'),
(273, 42, '2026-02-16', 'done', NULL, '2026-02-16 22:06:35'),
(274, 42, '2026-02-17', 'done', NULL, '2026-02-16 22:06:35'),
(275, 42, '2026-02-18', 'done', NULL, '2026-02-16 22:06:35'),
(276, 42, '2026-02-19', 'done', 'ok', '2026-02-16 22:06:35'),
(277, 43, '2026-02-17', 'done', NULL, '2026-02-17 22:06:12'),
(278, 43, '2026-02-18', 'pending', NULL, '2026-02-17 22:06:12'),
(279, 43, '2026-02-19', 'pending', NULL, '2026-02-17 22:06:12'),
(280, 43, '2026-02-20', 'pending', NULL, '2026-02-17 22:06:12'),
(281, 43, '2026-02-21', 'pending', NULL, '2026-02-17 22:06:12'),
(282, 43, '2026-02-22', 'pending', NULL, '2026-02-17 22:06:12'),
(283, 43, '2026-02-23', 'pending', NULL, '2026-02-17 22:06:12'),
(284, 43, '2026-02-24', 'pending', NULL, '2026-02-17 22:06:12'),
(285, 44, '2026-02-17', 'pending', NULL, '2026-02-17 22:08:02'),
(286, 44, '2026-02-18', 'done', NULL, '2026-02-17 22:08:02'),
(287, 44, '2026-02-19', 'done', 'ok', '2026-02-17 22:08:02'),
(288, 44, '2026-02-20', 'pending', NULL, '2026-02-17 22:08:02'),
(289, 44, '2026-02-21', 'pending', NULL, '2026-02-17 22:08:02'),
(290, 44, '2026-02-22', 'pending', NULL, '2026-02-17 22:08:02'),
(291, 44, '2026-02-23', 'done', NULL, '2026-02-17 22:08:02'),
(292, 44, '2026-02-24', 'done', NULL, '2026-02-17 22:08:02'),
(293, 44, '2026-02-25', 'pending', NULL, '2026-02-17 22:08:02'),
(294, 44, '2026-02-26', 'pending', NULL, '2026-02-17 22:08:02'),
(295, 44, '2026-02-27', 'pending', NULL, '2026-02-17 22:08:02'),
(296, 44, '2026-02-28', 'pending', NULL, '2026-02-17 22:08:02'),
(297, 44, '2026-03-01', 'pending', NULL, '2026-02-17 22:08:02'),
(298, 44, '2026-03-02', 'pending', NULL, '2026-02-17 22:08:02'),
(299, 44, '2026-03-03', 'pending', NULL, '2026-02-17 22:08:02'),
(300, 44, '2026-03-04', 'pending', NULL, '2026-02-17 22:08:02'),
(301, 44, '2026-03-05', 'pending', NULL, '2026-02-17 22:08:02'),
(302, 44, '2026-03-06', 'pending', NULL, '2026-02-17 22:08:02'),
(303, 44, '2026-03-07', 'pending', NULL, '2026-02-17 22:08:02'),
(304, 44, '2026-03-08', 'pending', NULL, '2026-02-17 22:08:02'),
(305, 44, '2026-03-09', 'pending', NULL, '2026-02-17 22:08:02'),
(306, 44, '2026-03-10', 'pending', NULL, '2026-02-17 22:08:02'),
(307, 44, '2026-03-11', 'pending', NULL, '2026-02-17 22:08:02'),
(308, 44, '2026-03-12', 'pending', NULL, '2026-02-17 22:08:02'),
(309, 44, '2026-03-13', 'pending', NULL, '2026-02-17 22:08:02'),
(310, 44, '2026-03-14', 'pending', NULL, '2026-02-17 22:08:02'),
(311, 44, '2026-03-15', 'pending', NULL, '2026-02-17 22:08:02'),
(312, 44, '2026-03-16', 'pending', NULL, '2026-02-17 22:08:02'),
(313, 44, '2026-03-17', 'pending', NULL, '2026-02-17 22:08:02'),
(314, 44, '2026-03-18', 'pending', NULL, '2026-02-17 22:08:02'),
(315, 44, '2026-03-19', 'pending', NULL, '2026-02-17 22:08:02'),
(316, 45, '2026-02-17', 'done', NULL, '2026-02-17 22:09:17'),
(317, 45, '2026-02-18', 'pending', NULL, '2026-02-17 22:09:17'),
(318, 45, '2026-02-19', 'pending', NULL, '2026-02-17 22:09:17'),
(319, 45, '2026-02-20', 'pending', NULL, '2026-02-17 22:09:17'),
(320, 45, '2026-02-21', 'pending', NULL, '2026-02-17 22:09:17'),
(321, 45, '2026-02-22', 'pending', NULL, '2026-02-17 22:09:17'),
(322, 45, '2026-02-23', 'pending', NULL, '2026-02-17 22:09:17'),
(323, 45, '2026-02-24', 'pending', NULL, '2026-02-17 22:09:17'),
(324, 45, '2026-02-25', 'pending', NULL, '2026-02-17 22:09:17'),
(325, 45, '2026-02-26', 'pending', NULL, '2026-02-17 22:09:17'),
(326, 45, '2026-02-27', 'pending', NULL, '2026-02-17 22:09:17'),
(327, 45, '2026-02-28', 'pending', NULL, '2026-02-17 22:09:17'),
(328, 45, '2026-03-01', 'pending', NULL, '2026-02-17 22:09:17'),
(329, 45, '2026-03-02', 'pending', NULL, '2026-02-17 22:09:17'),
(330, 45, '2026-03-03', 'pending', NULL, '2026-02-17 22:09:17'),
(331, 45, '2026-03-04', 'pending', NULL, '2026-02-17 22:09:17'),
(332, 45, '2026-03-05', 'pending', NULL, '2026-02-17 22:09:17'),
(333, 45, '2026-03-06', 'pending', NULL, '2026-02-17 22:09:17'),
(334, 45, '2026-03-07', 'pending', NULL, '2026-02-17 22:09:17'),
(335, 45, '2026-03-08', 'pending', NULL, '2026-02-17 22:09:17'),
(336, 45, '2026-03-09', 'pending', NULL, '2026-02-17 22:09:17'),
(337, 45, '2026-03-10', 'pending', NULL, '2026-02-17 22:09:17'),
(338, 45, '2026-03-11', 'pending', NULL, '2026-02-17 22:09:17'),
(339, 45, '2026-03-12', 'pending', NULL, '2026-02-17 22:09:17'),
(340, 45, '2026-03-13', 'pending', NULL, '2026-02-17 22:09:17'),
(341, 45, '2026-03-14', 'pending', NULL, '2026-02-17 22:09:17'),
(342, 45, '2026-03-15', 'pending', NULL, '2026-02-17 22:09:17'),
(343, 45, '2026-03-16', 'pending', NULL, '2026-02-17 22:09:17'),
(344, 45, '2026-03-17', 'pending', NULL, '2026-02-17 22:09:17'),
(345, 45, '2026-03-18', 'pending', NULL, '2026-02-17 22:09:17'),
(346, 45, '2026-03-19', 'pending', NULL, '2026-02-17 22:09:17'),
(347, 46, '2026-02-23', 'done', NULL, '2026-02-23 18:08:01'),
(348, 46, '2026-02-24', 'pending', NULL, '2026-02-23 18:08:02'),
(349, 46, '2026-02-25', 'pending', NULL, '2026-02-23 18:08:02'),
(350, 46, '2026-02-26', 'pending', NULL, '2026-02-23 18:08:02'),
(351, 46, '2026-02-27', 'pending', NULL, '2026-02-23 18:08:02'),
(352, 46, '2026-02-28', 'pending', NULL, '2026-02-23 18:08:02'),
(353, 46, '2026-03-01', 'pending', NULL, '2026-02-23 18:08:02'),
(354, 46, '2026-03-02', 'pending', NULL, '2026-02-23 18:08:02'),
(355, 46, '2026-03-03', 'pending', NULL, '2026-02-23 18:08:02'),
(356, 46, '2026-03-04', 'pending', NULL, '2026-02-23 18:08:02'),
(357, 46, '2026-03-05', 'pending', NULL, '2026-02-23 18:08:02'),
(358, 46, '2026-03-06', 'pending', NULL, '2026-02-23 18:08:02'),
(359, 46, '2026-03-07', 'pending', NULL, '2026-02-23 18:08:02'),
(360, 46, '2026-03-08', 'pending', NULL, '2026-02-23 18:08:02'),
(361, 46, '2026-03-09', 'pending', NULL, '2026-02-23 18:08:02'),
(362, 46, '2026-03-10', 'pending', NULL, '2026-02-23 18:08:02'),
(363, 46, '2026-03-11', 'pending', NULL, '2026-02-23 18:08:02'),
(364, 46, '2026-03-12', 'pending', NULL, '2026-02-23 18:08:02'),
(365, 46, '2026-03-13', 'pending', NULL, '2026-02-23 18:08:02'),
(366, 46, '2026-03-14', 'pending', NULL, '2026-02-23 18:08:02'),
(367, 46, '2026-03-15', 'pending', NULL, '2026-02-23 18:08:02'),
(368, 46, '2026-03-16', 'pending', NULL, '2026-02-23 18:08:02'),
(369, 46, '2026-03-17', 'pending', NULL, '2026-02-23 18:08:02'),
(370, 46, '2026-03-18', 'pending', NULL, '2026-02-23 18:08:02'),
(371, 46, '2026-03-19', 'pending', NULL, '2026-02-23 18:08:02'),
(372, 46, '2026-03-20', 'pending', NULL, '2026-02-23 18:08:02'),
(373, 46, '2026-03-21', 'pending', NULL, '2026-02-23 18:08:02'),
(374, 46, '2026-03-22', 'pending', NULL, '2026-02-23 18:08:02'),
(375, 46, '2026-03-23', 'pending', NULL, '2026-02-23 18:08:02'),
(376, 46, '2026-03-24', 'pending', NULL, '2026-02-23 18:08:02'),
(377, 46, '2026-03-25', 'pending', NULL, '2026-02-23 18:08:02'),
(386, 48, '2026-02-24', 'done', 'ok', '2026-02-24 20:57:38'),
(387, 48, '2026-02-26', 'pending', NULL, '2026-02-24 20:57:38'),
(388, 48, '2026-02-28', 'pending', NULL, '2026-02-24 20:57:38'),
(389, 48, '2026-03-02', 'pending', NULL, '2026-02-24 20:57:38'),
(401, 49, '2026-02-25', 'pending', NULL, '2026-02-24 21:28:33'),
(402, 49, '2026-02-27', 'pending', NULL, '2026-02-24 21:28:33'),
(403, 49, '2026-03-01', 'pending', NULL, '2026-02-24 21:28:33'),
(404, 49, '2026-03-03', 'pending', NULL, '2026-02-24 21:28:33'),
(405, 50, '2026-02-24', 'pending', NULL, '2026-02-24 21:40:03'),
(406, 50, '2026-02-25', 'pending', NULL, '2026-02-24 21:40:03'),
(407, 50, '2026-02-26', 'pending', NULL, '2026-02-24 21:40:03'),
(408, 50, '2026-02-27', 'pending', NULL, '2026-02-24 21:40:03'),
(409, 50, '2026-02-28', 'pending', NULL, '2026-02-24 21:40:03'),
(410, 50, '2026-03-01', 'pending', NULL, '2026-02-24 21:40:03'),
(411, 50, '2026-03-02', 'pending', NULL, '2026-02-24 21:40:03'),
(412, 50, '2026-03-03', 'pending', NULL, '2026-02-24 21:40:03'),
(413, 50, '2026-03-04', 'pending', NULL, '2026-02-24 21:40:03'),
(414, 50, '2026-03-05', 'pending', NULL, '2026-02-24 21:40:03'),
(415, 50, '2026-03-06', 'pending', NULL, '2026-02-24 21:40:03'),
(416, 50, '2026-03-07', 'pending', NULL, '2026-02-24 21:40:03'),
(417, 50, '2026-03-08', 'pending', NULL, '2026-02-24 21:40:03'),
(418, 50, '2026-03-09', 'pending', NULL, '2026-02-24 21:40:03'),
(419, 50, '2026-03-10', 'pending', NULL, '2026-02-24 21:40:03'),
(420, 50, '2026-03-11', 'pending', NULL, '2026-02-24 21:40:03'),
(421, 50, '2026-03-12', 'pending', NULL, '2026-02-24 21:40:03'),
(422, 50, '2026-03-13', 'pending', NULL, '2026-02-24 21:40:03'),
(423, 50, '2026-03-14', 'pending', NULL, '2026-02-24 21:40:03'),
(424, 50, '2026-03-15', 'pending', NULL, '2026-02-24 21:40:03'),
(425, 50, '2026-03-16', 'pending', NULL, '2026-02-24 21:40:03'),
(426, 50, '2026-03-17', 'pending', NULL, '2026-02-24 21:40:03'),
(427, 50, '2026-03-18', 'pending', NULL, '2026-02-24 21:40:03'),
(428, 50, '2026-03-19', 'pending', NULL, '2026-02-24 21:40:03'),
(429, 50, '2026-03-20', 'pending', NULL, '2026-02-24 21:40:03'),
(430, 50, '2026-03-21', 'pending', NULL, '2026-02-24 21:40:03'),
(431, 50, '2026-03-22', 'pending', NULL, '2026-02-24 21:40:03'),
(432, 50, '2026-03-23', 'pending', NULL, '2026-02-24 21:40:03'),
(433, 50, '2026-03-24', 'pending', NULL, '2026-02-24 21:40:03'),
(434, 50, '2026-03-25', 'pending', NULL, '2026-02-24 21:40:03'),
(435, 50, '2026-03-26', 'pending', NULL, '2026-02-24 21:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `goal_likes`
--

CREATE TABLE `goal_likes` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goal_likes`
--

INSERT INTO `goal_likes` (`id`, `goal_id`, `user_id`, `created_at`) VALUES
(3, 13, 2, '2025-12-13 09:48:57'),
(6, 16, 2, '2025-12-13 10:01:02'),
(7, 18, 2, '2025-12-13 11:11:35'),
(10, 13, 3, '2025-12-13 11:17:19'),
(11, 19, 2, '2025-12-16 22:39:11'),
(14, 23, 3, '2025-12-27 09:06:48'),
(15, 23, 2, '2025-12-28 13:45:47'),
(16, 24, 2, '2025-12-29 20:36:20'),
(22, 19, 1, '2025-12-30 21:34:50'),
(23, 24, 3, '2025-12-30 21:35:17'),
(24, 25, 1, '2026-01-10 07:06:48'),
(25, 26, 1, '2026-01-10 07:06:52'),
(26, 27, 3, '2026-01-10 07:23:37'),
(27, 29, 3, '2026-01-10 07:23:44'),
(30, 31, 2, '2026-01-23 18:29:48'),
(31, 35, 2, '2026-01-25 14:03:39'),
(32, 33, 1, '2026-01-25 14:05:27'),
(33, 34, 1, '2026-01-25 14:05:33'),
(34, 35, 1, '2026-01-25 14:05:40'),
(36, 46, 6, '2026-02-24 21:11:39'),
(37, 49, 5, '2026-02-24 21:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `goal_notes`
--

CREATE TABLE `goal_notes` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goal_reminders`
--

CREATE TABLE `goal_reminders` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_16_091858_add_interval_days_to_goals_table', 1),
(2, '2025_11_16_100036_add_color_and_user_id_to_goal_categories_table', 2),
(3, '2025_12_17_211239_drop_frequency_from_goals_table', 3),
(4, '2026_01_24_155531_create_personal_access_tokens_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'mobile', 'b9080029f46cf332ccd3716d6ff82945f16727d96106af1c419d3b3126cd19ec', '[\"*\"]', '2026-01-25 13:01:21', NULL, '2026-01-24 16:19:18', '2026-01-25 13:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `points_history`
--

CREATE TABLE `points_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `actor_user_id` int(11) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `action` varchar(150) NOT NULL,
  `related_type` varchar(50) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points_history`
--

INSERT INTO `points_history` (`id`, `user_id`, `actor_user_id`, `event_date`, `action`, `related_type`, `related_id`, `points`, `created_at`) VALUES
(1, 1, NULL, '2025-12-29', 'done_today', 'goal', 20, 5, '2025-12-29 20:23:28'),
(2, 1, NULL, '2025-12-29', 'done_today', 'goal', 24, 5, '2025-12-29 20:35:34'),
(3, 2, NULL, '2025-12-29', 'like', 'goal', 24, 1, '2025-12-29 20:36:20'),
(4, 2, NULL, '2025-12-29', 'done_today', 'challenge', 2, 5, '2025-12-29 21:14:05'),
(5, 2, 1, '2025-12-30', 'like', 'goal', 17, 1, '2025-12-30 21:10:12'),
(8, 1, NULL, '2025-12-30', 'done_today', 'goal', 24, 5, '2025-12-30 21:32:46'),
(9, 1, NULL, '2025-12-30', 'done_today', 'goal', 23, 5, '2025-12-30 21:33:01'),
(11, 3, 1, '2025-12-30', 'like', 'goal', 19, 1, '2025-12-30 21:34:40'),
(13, 1, 3, '2025-12-30', 'like', 'goal', 24, 1, '2025-12-30 21:35:17'),
(14, 3, NULL, '2025-12-30', 'done_today', 'goal', 25, 5, '2025-12-30 21:36:28'),
(15, 3, NULL, '2025-12-30', 'goal_completed_80', 'goal', 25, 50, '2025-12-30 21:36:28'),
(16, 3, NULL, '2025-12-30', 'goal_completed_100', 'goal', 25, 100, '2025-12-30 21:36:28'),
(17, 3, NULL, '2025-12-30', 'done_today', 'goal', 26, 5, '2025-12-30 21:38:05'),
(18, 3, 1, '2026-01-10', 'like', 'goal', 25, 1, '2026-01-10 07:06:48'),
(19, 3, 1, '2026-01-10', 'like', 'goal', 26, 1, '2026-01-10 07:06:52'),
(20, 1, NULL, '2026-01-10', 'done_today', 'goal', 27, 5, '2026-01-10 07:20:48'),
(21, 1, NULL, '2026-01-10', 'done_today', 'goal', 29, 5, '2026-01-10 07:22:19'),
(22, 1, 3, '2026-01-10', 'like', 'goal', 27, 1, '2026-01-10 07:23:37'),
(23, 1, 3, '2026-01-10', 'like', 'goal', 29, 1, '2026-01-10 07:23:44'),
(24, 1, NULL, '2026-01-10', 'done_today', 'goal', 30, 5, '2026-01-10 08:30:18'),
(25, 1, 4, '2026-01-12', 'pro_verified', 'pro_request', 1, 100, '2026-01-12 09:11:44'),
(26, 1, 4, '2026-01-12', 'pro_verified', 'pro_request', 2, 100, '2026-01-12 09:14:36'),
(27, 1, NULL, '2026-01-23', 'done_today', 'goal', 31, 5, '2026-01-23 16:59:53'),
(28, 2, NULL, '2026-01-23', 'done_today', 'challenge', 4, 5, '2026-01-23 18:27:52'),
(29, 1, 2, '2026-01-23', 'like', 'goal', 31, 1, '2026-01-23 18:29:41'),
(32, 2, NULL, '2026-01-23', 'done_today', 'goal', 32, 5, '2026-01-23 18:31:16'),
(33, 2, NULL, '2026-01-23', 'done_today', 'goal', 34, 5, '2026-01-23 18:35:09'),
(34, 3, NULL, '2026-01-23', 'done_today', 'goal', 35, 5, '2026-01-23 18:41:30'),
(35, 3, 2, '2026-01-25', 'like', 'goal', 35, 1, '2026-01-25 14:03:40'),
(36, 1, NULL, '2026-01-25', 'done_today', 'challenge', 4, 5, '2026-01-25 14:04:49'),
(37, 2, 1, '2026-01-25', 'like', 'goal', 33, 1, '2026-01-25 14:05:27'),
(38, 2, 1, '2026-01-25', 'like', 'goal', 34, 1, '2026-01-25 14:05:33'),
(40, 1, NULL, '2026-01-25', 'done_today', 'goal', 31, 5, '2026-01-25 14:22:40'),
(41, 1, NULL, '2026-01-26', 'done_today', 'goal', 31, 5, '2026-01-26 21:42:07'),
(42, 2, NULL, '2026-02-05', 'done_today', 'goal', 38, 5, '2026-02-05 20:31:55'),
(43, 2, NULL, '2026-02-07', 'done_today', 'goal', 38, 5, '2026-02-07 10:12:09'),
(44, 2, NULL, '2026-02-08', 'done_today', 'goal', 40, 5, '2026-02-08 06:43:23'),
(45, 2, NULL, '2026-02-16', 'done_today', 'goal', 40, 5, '2026-02-16 21:37:40'),
(46, 2, NULL, '2026-02-16', 'done_today', 'goal', 41, 5, '2026-02-16 21:44:16'),
(47, 1, NULL, '2026-02-16', 'done_today', 'goal', 42, 5, '2026-02-16 22:06:52'),
(48, 2, NULL, '2026-02-17', 'done_today', 'goal', 41, 5, '2026-02-17 20:44:59'),
(49, 1, NULL, '2026-02-17', 'done_today', 'goal', 42, 5, '2026-02-17 20:56:48'),
(50, 3, NULL, '2026-02-17', 'done_today', 'goal', 43, 5, '2026-02-17 22:06:34'),
(51, 3, NULL, '2026-02-17', 'done_today', 'goal', 45, 5, '2026-02-17 22:09:34'),
(52, 1, NULL, '2026-02-18', 'done_today', 'goal', 44, 5, '2026-02-18 21:41:14'),
(53, 1, NULL, '2026-02-18', 'done_today', 'goal', 42, 5, '2026-02-18 21:41:54'),
(54, 2, NULL, '2026-02-18', 'done_today', 'goal', 41, 5, '2026-02-18 21:42:50'),
(55, 1, NULL, '2026-02-19', 'done_today', 'goal', 42, 5, '2026-02-19 20:07:47'),
(56, 1, NULL, '2026-02-19', 'done_today', 'goal', 44, 5, '2026-02-19 20:28:02'),
(57, 1, NULL, '2026-02-23', 'done_today', 'goal', 44, 5, '2026-02-23 18:03:32'),
(58, 2, NULL, '2026-02-23', 'done_today', 'goal', 46, 5, '2026-02-23 18:08:30'),
(59, 1, 4, '2026-02-23', 'pro_verified', 'pro_request', 4, 100, '2026-02-23 18:09:52'),
(60, 2, 6, '2026-02-24', 'like', 'goal', 46, 1, '2026-02-24 20:43:45'),
(61, 6, NULL, '2026-02-24', 'done_today', 'goal', 47, 5, '2026-02-24 20:47:38'),
(62, 6, NULL, '2026-02-24', 'done_today', 'goal', 48, 5, '2026-02-24 20:58:13'),
(63, 6, NULL, '2026-02-24', 'done_today', 'goal', 49, 5, '2026-02-24 21:06:24'),
(65, 6, 5, '2026-02-24', 'like', 'goal', 49, 1, '2026-02-24 21:13:20'),
(66, 1, NULL, '2026-02-24', 'done_today', 'goal', 44, 5, '2026-02-24 21:38:53'),
(67, 1, 4, '2026-02-25', 'pro_verified', 'pro_request', 6, 100, '2026-02-25 19:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_requests`
--

CREATE TABLE `pro_requests` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `admin_note` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pro_requests`
--

INSERT INTO `pro_requests` (`id`, `goal_id`, `user_id`, `status`, `requested_at`, `reviewed_by`, `reviewed_at`, `admin_note`) VALUES
(1, 23, 1, 'approved', '2026-01-10 15:33:09', 4, '2026-01-12 09:11:44', 'ok'),
(2, 14, 1, 'approved', '2026-01-12 09:13:54', 4, '2026-01-12 09:14:36', 'ok'),
(3, 31, 1, 'rejected', '2026-02-02 20:14:52', 4, '2026-02-23 18:10:12', 'vvv'),
(4, 30, 1, 'approved', '2026-02-19 20:28:47', 4, '2026-02-23 18:09:52', 'ok'),
(5, 42, 1, 'pending', '2026-02-23 18:11:09', NULL, NULL, NULL),
(6, 29, 1, 'approved', '2026-02-25 19:28:10', 4, '2026-02-25 19:29:14', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--

CREATE TABLE `rankings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 1,
  `last_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rankings`
--

INSERT INTO `rankings` (`id`, `user_id`, `total_points`, `level`, `last_update`) VALUES
(1, 1, 499, 3, '2026-02-25 19:29:14'),
(2, 2, 65, 1, '2026-02-24 20:43:45'),
(3, 3, 179, 2, '2026-02-17 22:09:34'),
(4, 6, 16, 1, '2026-02-24 21:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_templates`
--

CREATE TABLE `report_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `file_format` enum('pdf','xlsx','csv') DEFAULT 'pdf',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Master Admin', '2026-01-10 08:52:37', '2026-01-10 08:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_goals` int(11) DEFAULT 0,
  `completed_goals` int(11) DEFAULT 0,
  `active_challenges` int(11) DEFAULT 0,
  `completed_challenges` int(11) DEFAULT 0,
  `total_likes` int(11) DEFAULT 0,
  `total_comments` int(11) DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'test1', 'test1@test', '$2y$12$EvOhGzEwOxQW01UEejqExeQJU80PFScdCLp62qiMcb8k2F.QCg5mK', 1, '2025-11-16 08:10:38', '2025-11-16 08:10:38'),
(2, 'test2', 'test2@test2', '$2y$12$bd07JaSkZ8mu9qny7FDAoeP.zb5GvTwRSxdilVfLg7VUXTS0BB0q6', 0, '2025-12-09 20:34:05', '2026-02-08 10:07:54'),
(3, 'test3', 'test3@test3', '$2y$12$LfFj.a/5bW0wRYR6byp25eNyJ7e7OdRIB0yt5KTMrj/r2.iQXeMFK', 1, '2025-12-13 11:17:02', '2025-12-13 11:17:02'),
(4, 'Master Admin', 'admin@admin', '$2y$12$7Q5UwnUhsOzqZjQ5K/WOEuhRvCD38XvT9.WiDE8x0gZqnlCj1LGG6', 1, '2026-01-10 08:57:18', '2026-01-10 08:57:18'),
(5, 'test4', 'test4@test4', '$2y$12$0Hv59rKbMSMtOhufbOwflOM1Nv4fZjhuDHStuuZFPr9htI7q4aGwy', 1, '2026-02-24 20:40:37', '2026-02-24 20:40:37'),
(6, 'test5', 'test5@test5', '$2y$12$PCYh0.M0RH.aX1Mb6PPT2uwWPZTbzZAJ3S9L5Lf8qoH4Z.nF5aCqm', 1, '2026-02-24 20:41:58', '2026-02-24 20:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `earned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`) VALUES
(1, 4, 1, '2026-01-10 08:58:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_achievements_name` (`name`);

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `badges_categories`
--
ALTER TABLE `badges_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_category_id` (`goal_category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `challenge_attachments`
--
ALTER TABLE `challenge_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `challenge_attachments_challenge_id_index` (`challenge_id`),
  ADD KEY `challenge_attachments_user_id_index` (`user_id`);

--
-- Indexes for table `challenge_comments`
--
ALTER TABLE `challenge_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `challenge_id` (`challenge_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `challenge_days`
--
ALTER TABLE `challenge_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_challenge_days` (`challenge_id`,`user_id`,`date`),
  ADD KEY `idx_challenge_days_user` (`user_id`),
  ADD KEY `idx_challenge_days_challenge` (`challenge_id`);

--
-- Indexes for table `challenge_invites`
--
ALTER TABLE `challenge_invites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_challenge_invite` (`challenge_id`,`invited_user_id`),
  ADD KEY `idx_challenge_invites_challenge` (`challenge_id`),
  ADD KEY `idx_challenge_invites_invited_user` (`invited_user_id`),
  ADD KEY `idx_challenge_invites_invited_by` (`invited_by`);

--
-- Indexes for table `challenge_participants`
--
ALTER TABLE `challenge_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `challenge_id` (`challenge_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `challenge_progress`
--
ALTER TABLE `challenge_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `challenge_id` (`challenge_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `challenge_rewards`
--
ALTER TABLE `challenge_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `challenge_id` (`challenge_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`friend_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `goal_category_id` (`goal_category_id`);

--
-- Indexes for table `goal_attachments`
--
ALTER TABLE `goal_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goal_categories`
--
ALTER TABLE `goal_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `goal_comments`
--
ALTER TABLE `goal_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_goal_comments_goal_id` (`goal_id`),
  ADD KEY `idx_goal_comments_user_id` (`user_id`);

--
-- Indexes for table `goal_days`
--
ALTER TABLE `goal_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goal_id` (`goal_id`,`date`),
  ADD UNIQUE KEY `uq_goal_days_goal_date` (`goal_id`,`date`);

--
-- Indexes for table `goal_likes`
--
ALTER TABLE `goal_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goal_likes_goal_user_unique` (`goal_id`,`user_id`),
  ADD KEY `goal_likes_goal_id_index` (`goal_id`),
  ADD KEY `goal_likes_user_id_index` (`user_id`);

--
-- Indexes for table `goal_notes`
--
ALTER TABLE `goal_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `goal_reminders`
--
ALTER TABLE `goal_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `points_history`
--
ALTER TABLE `points_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ph_once_per_day` (`user_id`,`event_date`,`action`,`related_type`,`related_id`),
  ADD UNIQUE KEY `uq_like_once_per_actor` (`user_id`,`actor_user_id`,`action`,`related_type`,`related_id`),
  ADD KEY `user_id` (`user_id`,`created_at`),
  ADD KEY `idx_points_user_created` (`user_id`,`created_at`),
  ADD KEY `idx_points_history_actor_user_id` (`actor_user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pro_requests`
--
ALTER TABLE `pro_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pro_requests_goal` (`goal_id`),
  ADD KEY `idx_pro_requests_user` (`user_id`),
  ADD KEY `idx_pro_requests_status` (`status`),
  ADD KEY `idx_pro_requests_reviewed_by` (`reviewed_by`);

--
-- Indexes for table `rankings`
--
ALTER TABLE `rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `uq_rankings_user` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `report_templates`
--
ALTER TABLE `report_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`achievement_id`),
  ADD UNIQUE KEY `uq_user_achievement` (`user_id`,`achievement_id`),
  ADD KEY `idx_ua_user` (`user_id`),
  ADD KEY `idx_ua_achievement` (`achievement_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges_categories`
--
ALTER TABLE `badges_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `challenge_attachments`
--
ALTER TABLE `challenge_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `challenge_comments`
--
ALTER TABLE `challenge_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `challenge_days`
--
ALTER TABLE `challenge_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `challenge_invites`
--
ALTER TABLE `challenge_invites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `challenge_participants`
--
ALTER TABLE `challenge_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `challenge_progress`
--
ALTER TABLE `challenge_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `challenge_rewards`
--
ALTER TABLE `challenge_rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `goal_attachments`
--
ALTER TABLE `goal_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `goal_categories`
--
ALTER TABLE `goal_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `goal_comments`
--
ALTER TABLE `goal_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `goal_days`
--
ALTER TABLE `goal_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=436;

--
-- AUTO_INCREMENT for table `goal_likes`
--
ALTER TABLE `goal_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `goal_notes`
--
ALTER TABLE `goal_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goal_reminders`
--
ALTER TABLE `goal_reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `points_history`
--
ALTER TABLE `points_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pro_requests`
--
ALTER TABLE `pro_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rankings`
--
ALTER TABLE `rankings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_templates`
--
ALTER TABLE `report_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `challenges`
--
ALTER TABLE `challenges`
  ADD CONSTRAINT `challenges_ibfk_1` FOREIGN KEY (`goal_category_id`) REFERENCES `goal_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `challenges_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_attachments`
--
ALTER TABLE `challenge_attachments`
  ADD CONSTRAINT `challenge_attachments_challenge_id_foreign` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `challenge_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_comments`
--
ALTER TABLE `challenge_comments`
  ADD CONSTRAINT `challenge_comments_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `challenge_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_days`
--
ALTER TABLE `challenge_days`
  ADD CONSTRAINT `fk_challenge_days_challenge` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_challenge_days_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_invites`
--
ALTER TABLE `challenge_invites`
  ADD CONSTRAINT `fk_challenge_invites_challenge` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_challenge_invites_invited_by` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_challenge_invites_invited_user` FOREIGN KEY (`invited_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_participants`
--
ALTER TABLE `challenge_participants`
  ADD CONSTRAINT `challenge_participants_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `challenge_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_progress`
--
ALTER TABLE `challenge_progress`
  ADD CONSTRAINT `challenge_progress_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `challenge_progress_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `challenge_rewards`
--
ALTER TABLE `challenge_rewards`
  ADD CONSTRAINT `challenge_rewards_ibfk_1` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `challenge_rewards_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `friendships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friendships_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`goal_category_id`) REFERENCES `goal_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `goal_comments`
--
ALTER TABLE `goal_comments`
  ADD CONSTRAINT `fk_goal_comments_goal` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_goal_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goal_days`
--
ALTER TABLE `goal_days`
  ADD CONSTRAINT `goal_days_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goal_likes`
--
ALTER TABLE `goal_likes`
  ADD CONSTRAINT `goal_likes_goal_id_fk` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goal_likes_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goal_notes`
--
ALTER TABLE `goal_notes`
  ADD CONSTRAINT `goal_notes_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goal_reminders`
--
ALTER TABLE `goal_reminders`
  ADD CONSTRAINT `goal_reminders_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `points_history`
--
ALTER TABLE `points_history`
  ADD CONSTRAINT `fk_points_history_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_points_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `points_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_requests`
--
ALTER TABLE `pro_requests`
  ADD CONSTRAINT `fk_pro_requests_goal` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pro_requests_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pro_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rankings`
--
ALTER TABLE `rankings`
  ADD CONSTRAINT `fk_rankings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rankings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `fk_ua_achievement` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ua_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
