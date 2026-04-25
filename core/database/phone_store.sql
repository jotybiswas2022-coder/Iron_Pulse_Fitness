-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 01:18 PM
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
-- Database: `phone_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(57, 2, 6, 1, '2026-02-27 06:07:35', '2026-02-27 06:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'Camera', '2026-02-19 03:35:53', '2026-02-19 03:35:53'),
(4, 'iPhone', '2026-02-21 08:53:08', '2026-02-21 08:55:01'),
(11, 'Android', '2026-02-24 05:34:52', '2026-02-24 05:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Joty Biswas', 'jotybiswas0199@gmail.com', 'হাই', '2026-02-19 03:21:07', '2026-02-19 03:21:07'),
(2, 'মইন মোল্লা', 'jotybiswas0199@gmail.com', 'কেমন আছেন?', '2026-02-19 03:21:24', '2026-02-19 03:21:24'),
(3, 'Joty Biswas', 'jotybiswas0199@gmail.com', 'কি খবর???', '2026-02-19 11:29:05', '2026-02-19 11:29:05'),
(4, 'Joty Biswas', 'jotybiswas0199@gmail.com', 'সাধারণ পুলের আওতায় নিয়োগযোগ্য বিভিন্ন মন্ত্রণালয়-বিভাগের “ব্যক্তিগত কর্মকর্তা” (১০ম গ্রেড) পদের ব্যবহারিক (সাঁটলিপি ও কম্পিউটার মুদ্রাক্ষর) পরীক্ষার কেন্দ্র, সময়সূচি ও নির্দেশাবলি।', '2026-02-19 11:30:20', '2026-02-19 11:30:20'),
(37, 'Joty Biswas', 'jotybiswas0199@gmail.com', 'গফদ্গ', '2026-02-27 05:09:53', '2026-02-27 05:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_21_173017_create_categories_table', 1),
(5, '2026_01_24_173801_create_products_table', 1),
(6, '2026_01_27_150926_create_carts_table', 1),
(7, '2026_01_31_155048_create_settings_table', 1),
(8, '2026_02_04_055853_create_orders_table', 1),
(9, '2026_02_04_055926_create_order_details_table', 1),
(10, '2026_02_19_071829_create_contacts_table', 2),
(11, '2026_02_19_074942_drop_unique_from_contacts_email', 3),
(12, '2026_02_19_092000_create_contacts_table', 4),
(13, '2026_02_25_141638_create_sliders_table', 5),
(14, '2026_02_25_145317_create_sliders_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `product_price_after_discount` decimal(15,2) NOT NULL,
  `delivery_charge` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `firstname`, `lastname`, `email`, `phone`, `address`, `product_price_after_discount`, `delivery_charge`, `tax`, `total_price`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(9, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 43200.00, 150.00, 1296.00, 44646.00, NULL, 'canceled', '2026-02-04 02:47:45', '2026-02-19 03:55:33'),
(10, 3, 'Masum', 'Billah', 'Masum018@gmail.com', '01845865857', '22/2, Kabi Nazrul Sarak, west Tootpara', 252000.00, 150.00, 7560.00, 259710.00, NULL, 'approved', '2026-02-04 02:50:03', '2026-02-18 13:10:07'),
(11, 3, 'Masum', 'Billah', 'Masum018@gmail.com', '+8801560065396', '22/2, Kabi Nazrul Sarak, west Tootpara', 43200.00, 150.00, 1296.00, 44646.00, NULL, 'approved', '2026-02-04 05:13:20', '2026-02-19 03:51:40'),
(12, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 143400.00, 150.00, 4302.00, 147852.00, NULL, 'approved', '2026-02-04 12:00:04', '2026-02-18 13:10:05'),
(13, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, NULL, 'approved', '2026-02-18 12:27:03', '2026-02-18 13:13:14'),
(14, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, NULL, 'approved', '2026-02-18 13:42:09', '2026-02-18 14:02:42'),
(15, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01845865857', '22/2, Kabi Nazrul Sarak, west Tootpara', 43200.00, 150.00, 1296.00, 44646.00, NULL, 'canceled', '2026-02-19 11:08:44', '2026-02-20 11:25:02'),
(16, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '+8801560065396', '22/2, Kabi Nazrul Sarak, west Tootpara', 39200.00, 150.00, 1176.00, 40526.00, NULL, 'approved', '2026-02-21 08:07:08', '2026-02-21 08:11:13'),
(17, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 96200.00, 150.00, 2886.00, 99236.00, NULL, 'canceled', '2026-02-21 10:04:11', '2026-02-21 10:07:37'),
(20, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, NULL, 'approved', '2026-02-22 00:07:21', '2026-02-22 00:07:30'),
(21, 3, 'আব্দুর', 'রহিম', 'jotybiswas0199@gmail.com', '01845865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 196000.00, 150.00, 5880.00, 202030.00, NULL, 'canceled', '2026-02-22 00:38:49', '2026-02-22 01:24:47'),
(22, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, NULL, 'approved', '2026-02-23 07:58:41', '2026-02-23 07:58:52'),
(23, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01845865857', '22/2, Kabi Nazrul Sarak, west Tootpara, 22/2, Kabi Nazrul Sarak, west Tootpara', 126000.00, 150.00, 3780.00, 129930.00, NULL, 'canceled', '2026-02-23 08:01:52', '2026-02-23 08:02:28'),
(24, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara, 22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, NULL, 'canceled', '2026-02-23 08:18:13', '2026-02-23 08:18:27'),
(25, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara, 22/2, Kabi Nazrul Sarak, west Tootpara', 631800.00, 150.00, 18954.00, 650904.00, NULL, 'approved', '2026-02-23 12:02:54', '2026-02-23 12:03:58'),
(26, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 126000.00, 150.00, 3780.00, 129930.00, NULL, 'canceled', '2026-02-23 12:25:09', '2026-02-23 12:25:28'),
(27, 3, 'Masum', 'Billah', 'Masum018@gmail.com', '01845865856', 'Alamin Sarak, Khulna', 43200.00, 150.00, 1296.00, 44646.00, NULL, 'approved', '2026-02-24 02:11:43', '2026-02-24 02:12:08'),
(28, 3, 'Joty', 'Biswas', 'Masum018@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 57000.00, 150.00, 1710.00, 58860.00, 'cod', 'canceled', '2026-02-24 02:52:29', '2026-02-24 03:19:16'),
(29, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 126000.00, 150.00, 3780.00, 129930.00, 'bkash', 'canceled', '2026-02-24 03:19:01', '2026-02-24 03:19:12'),
(30, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 471600.00, 150.00, 14148.00, 485898.00, 'cod', 'approved', '2026-02-24 05:47:49', '2026-02-24 05:47:56'),
(31, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01845865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 114000.00, 150.00, 3420.00, 117570.00, 'nagad', 'approved', '2026-02-24 06:01:12', '2026-02-24 06:01:21'),
(32, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 126000.00, 150.00, 3780.00, 129930.00, 'cod', 'approved', '2026-02-24 06:09:09', '2026-02-25 07:48:47'),
(33, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 39200.00, 150.00, 1176.00, 40526.00, 'cod', 'canceled', '2026-02-25 07:48:27', '2026-02-25 09:34:33'),
(34, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 115200.00, 150.00, 3456.00, 118806.00, 'cod', 'canceled', '2026-02-26 07:39:44', '2026-02-27 04:31:44'),
(35, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 252000.00, 150.00, 7560.00, 259710.00, 'bkash', 'approved', '2026-02-26 07:57:33', '2026-02-27 04:31:48'),
(36, 2, 'Joty', 'Biswas', 'jotybiswas0199@gmail.com', '01245865856', '22/2, Kabi Nazrul Sarak, west Tootpara', 126000.00, 150.00, 3780.00, 129930.00, 'cod', 'canceled', '2026-02-26 12:32:42', '2026-02-27 04:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `product_quantity`, `product_price`, `status`, `created_at`, `updated_at`) VALUES
(14, 9, 3, 'Honour 25', '1', '45000.00', 'Processing', '2026-02-04 02:47:45', '2026-02-04 02:47:45'),
(15, 10, 1, 'iphone 16', '2', '140000.00', 'Processing', '2026-02-04 02:50:03', '2026-02-04 02:50:03'),
(16, 11, 3, 'Honour 25', '1', '45000.00', 'Processing', '2026-02-04 05:13:21', '2026-02-04 05:13:21'),
(17, 12, 2, 'Vivo X300 Pro', '1', '60000.00', 'Processing', '2026-02-04 12:00:04', '2026-02-04 12:00:04'),
(18, 12, 3, 'Honour 25', '2', '45000.00', 'Processing', '2026-02-04 12:00:04', '2026-02-04 12:00:04'),
(19, 13, 2, 'Vivo X300 Pro', '1', '60000.00', 'Processing', '2026-02-18 12:27:03', '2026-02-18 12:27:03'),
(20, 14, 2, 'Vivo X300 Pro', '1', '60000.00', 'Processing', '2026-02-18 13:42:09', '2026-02-18 13:42:09'),
(21, 15, 3, 'Honour 25', '1', '45000.00', 'Processing', '2026-02-19 11:08:44', '2026-02-19 11:08:44'),
(22, 16, 4, 'iphone 12', '1', '40000.00', 'Processing', '2026-02-21 08:07:08', '2026-02-21 08:07:08'),
(23, 17, 4, 'iphone 12', '1', '40000.00', 'Processing', '2026-02-21 10:04:11', '2026-02-21 10:04:11'),
(24, 17, 5, 'Digital Camera', '1', '60000.00', 'Processing', '2026-02-21 10:04:11', '2026-02-21 10:04:11'),
(27, 20, 5, 'Digital Camera', '1', '60000.00', 'processing', '2026-02-22 00:07:21', '2026-02-22 00:07:21'),
(28, 21, 4, 'iphone 12', '5', '40000.00', 'processing', '2026-02-22 00:38:49', '2026-02-22 00:38:49'),
(29, 22, 5, 'Digital Camera', '1', '60000.00', 'processing', '2026-02-23 07:58:41', '2026-02-23 07:58:41'),
(30, 23, 1, 'iphone 16', '1', '140000.00', 'processing', '2026-02-23 08:01:52', '2026-02-23 08:01:52'),
(31, 24, 5, 'Digital Camera', '1', '60000.00', 'canceled', '2026-02-23 08:18:13', '2026-02-23 08:18:27'),
(32, 25, 6, 'iphone 17', '4', '120000.00', 'approved', '2026-02-23 12:02:54', '2026-02-23 12:03:58'),
(33, 25, 5, 'Digital Camera', '3', '60000.00', 'approved', '2026-02-23 12:02:54', '2026-02-23 12:03:58'),
(34, 26, 1, 'iphone 16', '1', '140000.00', 'canceled', '2026-02-23 12:25:09', '2026-02-23 12:25:28'),
(35, 27, 3, 'Honour 25', '1', '45000.00', 'approved', '2026-02-24 02:11:43', '2026-02-24 02:12:08'),
(36, 28, 5, 'Digital Camera', '1', '60000.00', 'canceled', '2026-02-24 02:52:29', '2026-02-24 03:19:16'),
(37, 29, 1, 'iphone 16', '1', '140000.00', 'canceled', '2026-02-24 03:19:01', '2026-02-24 03:19:12'),
(38, 30, 6, 'iphone 17', '3', '120000.00', 'approved', '2026-02-24 05:47:49', '2026-02-24 05:47:56'),
(39, 30, 1, 'iphone 16', '1', '140000.00', 'approved', '2026-02-24 05:47:49', '2026-02-24 05:47:56'),
(40, 31, 5, 'Digital Camera', '2', '60000.00', 'approved', '2026-02-24 06:01:12', '2026-02-24 06:01:21'),
(41, 32, 1, 'iphone 16', '1', '140000.00', 'approved', '2026-02-24 06:09:09', '2026-02-25 07:48:47'),
(42, 33, 4, 'iphone 12', '1', '40000.00', 'canceled', '2026-02-25 07:48:27', '2026-02-25 09:34:33'),
(43, 34, 6, 'iphone 17', '1', '120000.00', 'canceled', '2026-02-26 07:39:44', '2026-02-27 04:31:44'),
(44, 35, 1, 'iphone 16', '2', '140000.00', 'approved', '2026-02-26 07:57:33', '2026-02-27 04:31:48'),
(45, 36, 1, 'iphone 16', '1', '140000.00', 'canceled', '2026-02-26 12:32:42', '2026-02-27 04:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('jotybiswas0199@gmail.com', '$2y$12$jVrpIunorMC6nNxFYzNHGe5hkS9eXU6IiMwJ5sFXfMRyffZ7rnkki', '2026-02-27 04:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `base_price` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount` double NOT NULL,
  `stock` int(11) NOT NULL,
  `details` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `base_price`, `price`, `discount`, `stock`, `details`, `image`, `created_at`, `updated_at`) VALUES
(1, 'iphone 16', 4, 120000, 140000.00, 10, 16, '<p>মডেলঃ iphone 16&nbsp;</p>', 'product/WvBWtQJVocZHLaqHRCIgcmODFo4YkuNt624XxqsG.png', '2026-02-04 02:27:06', '2026-02-27 04:31:48'),
(2, 'Vivo X300 Pro', 11, 60000, 62000.00, 0, 20, '<p>Vivo X300 Pro</p>', 'product/z44AEunYb7WclaXfdOzb0HnujjV5fhobOuc2FB5e.jpg', '2026-02-04 02:27:29', '2026-02-27 03:46:59'),
(3, 'Honour 25', 11, 40000, 45000.00, 4, 19, '<p>Honour 25</p>', 'product/MyUQedyLoNmfVyV6shHha06BGKPO3aAj4uWjAKsn.png', '2026-02-04 02:31:47', '2026-02-24 05:35:02'),
(4, 'iphone 12', 4, 32000, 40000.00, 2, 3, '<p>iphone 12</p>', 'product/RIuafaDvcd6t2wIT5pkNZkWbMnG5Bi9ETIN4KsRh.jpg', '2026-02-19 03:23:46', '2026-02-27 05:24:40'),
(5, 'Digital Camera', 3, 55000, 60000.00, 5, 8, '<p><strong>Digital Camera</strong></p>', 'product/znjuYqBd3BWmkDQZKBUJNaDa9Hg7URWxIMgP6ixW.jpg', '2026-02-19 03:36:31', '2026-02-24 06:01:21'),
(6, 'iphone 17', 4, 100000, 120000.00, 4, 5, '<p>sfkhsdkjfhkdh</p>', 'product/QNtyu9sFWHqepCUL2n6FIPcs2QTWprLI0hj4Edf5.webp', '2026-02-21 07:40:36', '2026-02-27 05:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bZT6mEeDhxCEKQ4PBeuzWQjsSJNvFznAwOUtdCTU', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYTJJZGpld01qVEVUaG4xUXN6YWphWDlNcjhvVkIwbkRqcGhBMURodCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MjE4NTEzMzt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1772185187),
('M4dORlTTq4aXs2Ece7V3Nk9ArfyhlQ1k1jFVr1LS', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 OPR/127.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTXhzMm9oVGtSd1ptanJmYjVlWEtxUnZGSFQ2akR6Z3hKTmdjNWV0OCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vbG9jYWxob3N0L3Byb2R1Y3QvNCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NzIxODU0Mjk7fX0=', 1772191488),
('zahtaj6H2NhO1P001AbddEzv8RANMZ6CQJKKFCIs', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRFpGbXhsZ1hDTllPVlRWQVY1RmJ4RGNLQUtETGFxaHBvNEdQeWNJOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3MjE5MzkzMjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1772194108);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `language` varchar(255) NOT NULL DEFAULT 'en',
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `currency`, `language`, `delivery_charge`, `tax_percentage`, `created_at`, `updated_at`) VALUES
(1, 'BDT', 'en', 150.00, 3.00, '2026-02-04 02:28:17', '2026-02-27 04:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slider1` varchar(255) DEFAULT NULL,
  `slider2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `slider1`, `slider2`, `created_at`, `updated_at`) VALUES
(1, 'sliders/fYE4BKogPYUNjwqMQ0D6nWv8I2WM33Sgdp4eM1rI.jpg', 'sliders/Clfll5bRkZB1bjZBdGMC90gJBJAsOdQtMnHMpEaS.jpg', '2026-02-25 08:54:04', '2026-02-25 09:02:24'),
(2, 'sliders/mph2sDMhmGbigJgiSXRHkEOnQQ2jaARwGIp0aAcL.jpg', NULL, '2026-02-25 09:05:53', '2026-02-25 09:05:53'),
(3, 'sliders/8DsTVhQAGkDhuOFqZN8ESk12eqPFj0E8hVs8RhY4.jpg', 'sliders/9FGgqfxWYfsGw7ipaKzOnWzEgab2A7mLCbE9uaIK.jpg', '2026-02-25 09:05:58', '2026-02-27 03:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `is_admin`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-02-04 02:25:32', 0, '$2y$12$T7DqxVloOkrnmB2QUio2.uoRs2TXHL5Ct7V3bRCDss8ijrUfepsvi', '1n0bcWBCQn', '2026-02-04 02:25:32', '2026-02-04 02:25:32'),
(2, 'Joty Biswas', 'jotybiswas0199@gmail.com', NULL, 1, '$2y$12$echfnmus.LQGH8OMsm3Mk.n3U.hfL0Wzsxfepz/nG38NbtJUi7BUe', NULL, '2026-02-04 02:26:09', '2026-02-04 02:26:09'),
(3, 'Masum', 'Masum018@gmail.com', NULL, 0, '$2y$12$71GaKVoxAurPY4ru.vdGXu6eCvxEPqD65SCT.4KwgHuApGVjQGt3y', NULL, '2026-02-04 02:49:31', '2026-02-22 00:18:58'),
(4, 'mantu', 'mantu018@gmail.com', NULL, 0, '$2y$12$vpxr4.FJdX6yWObbYCOVrO6EsfNKm1gEWLT9AsTlyzhWDBDrp/mGS', NULL, '2026-02-21 09:10:57', '2026-02-21 09:10:57'),
(6, 'আইজ্যাক newton', 'newton@gmail.com', NULL, 0, '$2y$12$YvUjYDDQB1jiM3UIiursgeMiIdSdzXuVWlIhB38puFglj5VJurbIq', NULL, '2026-02-21 11:35:07', '2026-02-21 12:01:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
