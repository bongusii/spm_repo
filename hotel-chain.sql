-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 26, 2025 at 07:25 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel-chain`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `promotion_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_hotel_id_foreign` (`hotel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `hotel_id`, `check_in`, `check_out`, `total_price`, `status`, `payment_status`, `notes`, `created_at`, `updated_at`, `promotion_code`, `discount_amount`) VALUES
(3, 1, 1, '2025-11-26', '2025-11-27', 700000.00, 'completed', 'unpaid', NULL, '2025-11-25 22:37:08', '2025-11-25 22:53:49', NULL, 0.00),
(4, 1, 1, '2025-11-27', '2025-11-28', 800000.00, 'completed', 'unpaid', NULL, '2025-11-25 22:54:06', '2025-11-25 23:37:46', NULL, 0.00),
(5, 3, 1, '2025-11-29', '2025-11-30', 650000.00, 'completed', 'unpaid', NULL, '2025-11-25 23:06:40', '2025-11-25 23:39:22', NULL, 0.00),
(6, 4, 1, '2025-11-27', '2025-11-28', 720000.00, 'confirmed', 'unpaid', 'Tôi muốn phòng có view biển!', '2025-11-25 23:48:33', '2025-11-26 00:09:31', 'WINTER2025', 80000.00),
(7, 1, 1, '2025-11-27', '2025-11-28', 800000.00, 'pending', 'unpaid', NULL, '2025-11-26 00:10:28', '2025-11-26 00:10:28', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking_room`
--

DROP TABLE IF EXISTS `booking_room`;
CREATE TABLE IF NOT EXISTS `booking_room` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price_at_booking` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_room_booking_id_foreign` (`booking_id`),
  KEY `booking_room_room_type_id_foreign` (`room_type_id`),
  KEY `booking_room_room_id_foreign` (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_room`
--

INSERT INTO `booking_room` (`id`, `booking_id`, `room_type_id`, `quantity`, `price_at_booking`, `created_at`, `updated_at`, `room_id`) VALUES
(1, 1, 1, 1, 800000.00, '2025-11-25 19:31:03', '2025-11-25 19:31:03', NULL),
(2, 2, 2, 1, 700000.00, '2025-11-25 19:43:23', '2025-11-25 19:43:23', NULL),
(3, 3, 2, 1, 700000.00, '2025-11-25 22:37:08', '2025-11-25 22:37:08', NULL),
(4, 4, 1, 1, 800000.00, '2025-11-25 22:54:06', '2025-11-25 22:54:11', 1),
(5, 5, 3, 1, 650000.00, '2025-11-25 23:06:40', '2025-11-25 23:39:21', 7),
(6, 6, 1, 1, 800000.00, '2025-11-25 23:48:33', '2025-11-26 00:09:31', 1),
(7, 7, 1, 1, 800000.00, '2025-11-26 00:10:28', '2025-11-26 00:10:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hotline` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotels_manager_id_foreign` (`manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `address`, `hotline`, `description`, `manager_id`, `created_at`, `updated_at`) VALUES
(1, 'Bongusi Long Xuyên', '24 Hà Hoàng Hổ, phường Long Xuyên, An Giang', '0987654321', NULL, NULL, '2025-11-25 15:41:15', '2025-11-25 15:41:15'),
(2, 'Bongusi Chi Lăng', '132 Hoàng Hoa Thám, phường Chi Lăng, An Giang', '0987654322', NULL, 4, '2025-11-25 15:41:37', '2025-11-25 15:41:37'),
(3, 'Bongusi Bình Đức', '31B Bùi Thị Xuân, phường Bình Đức, An Giang', '0987654323', NULL, 5, '2025-11-25 15:43:38', '2025-11-25 15:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `invoice_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` datetime NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_booking_id_unique` (`booking_id`),
  UNIQUE KEY `invoices_invoice_code_unique` (`invoice_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `booking_id`, `invoice_code`, `issued_at`, `total_amount`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'INV-2025-0005', '2025-11-26 06:41:36', 650000.00, 'cash', 'paid', '2025-11-25 23:41:36', '2025-11-25 23:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_25_221521_create_hotels_table', 1),
(5, '2025_11_25_221551_create_room_types_table', 1),
(6, '2025_11_25_221551_create_rooms_table', 1),
(7, '2025_11_25_221712_create_bookings_table', 1),
(8, '2025_11_25_221810_create_booking_details_table', 1),
(9, '2025_11_25_221906_create_promotions_table', 1),
(10, '2025_11_26_055102_add_room_id_to_booking_room_table', 2),
(11, '2025_11_26_061731_add_promotion_cols_to_bookings_table', 3),
(12, '2025_11_26_063408_create_invoices_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotions_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `code`, `discount_percent`, `discount_amount`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'WINTER2025', 10.00, 200000.00, '2025-11-26', '2025-11-30', '2025-11-25 23:16:36', '2025-11-25 23:16:36'),
(2, 'BONGUSI9124', 50.00, 500000.00, '2025-11-26', '2025-12-26', '2025-11-25 23:24:03', '2025-11-25 23:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `room_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_room_type_id_foreign` (`room_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '100', 'booked', '2025-11-25 15:48:46', '2025-11-26 00:09:31'),
(4, 2, '200', 'available', '2025-11-25 15:48:59', '2025-11-25 15:48:59'),
(5, 2, '201', 'available', '2025-11-25 15:49:02', '2025-11-25 15:49:02'),
(6, 2, '202', 'available', '2025-11-25 15:49:06', '2025-11-25 15:49:06'),
(7, 3, '300', 'available', '2025-11-25 23:03:30', '2025-11-25 23:39:22'),
(8, 3, '301', 'available', '2025-11-25 23:03:37', '2025-11-25 23:03:37'),
(9, 3, '302', 'available', '2025-11-25 23:03:43', '2025-11-25 23:03:43'),
(10, 4, '400', 'available', '2025-11-25 23:03:53', '2025-11-25 23:03:53'),
(11, 4, '401', 'available', '2025-11-25 23:04:00', '2025-11-25 23:04:00'),
(12, 4, '402', 'available', '2025-11-25 23:04:05', '2025-11-25 23:04:05'),
(13, 5, '500', 'available', '2025-11-25 23:04:12', '2025-11-25 23:04:12'),
(15, 5, '501', 'available', '2025-11-25 23:04:30', '2025-11-25 23:04:30'),
(16, 5, '502', 'available', '2025-11-25 23:04:36', '2025-11-25 23:04:36'),
(17, 6, '600', 'available', '2025-11-25 23:04:42', '2025-11-25 23:04:42'),
(18, 6, '601', 'available', '2025-11-25 23:04:48', '2025-11-25 23:04:48'),
(19, 6, '602', 'available', '2025-11-25 23:04:53', '2025-11-25 23:04:53');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

DROP TABLE IF EXISTS `room_types`;
CREATE TABLE IF NOT EXISTS `room_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL,
  `amenities` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `room_types_hotel_id_foreign` (`hotel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `hotel_id`, `name`, `price_per_night`, `capacity`, `amenities`, `created_at`, `updated_at`) VALUES
(1, 1, 'V.I.P - V1', 800000.00, 4, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:43:56', '2025-11-25 15:43:56'),
(2, 1, 'V.I.P - V2', 700000.00, 3, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:44:14', '2025-11-25 15:44:14'),
(3, 1, 'V.I.P - V3', 650000.00, 2, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:44:31', '2025-11-25 15:44:31'),
(4, 1, 'Standard - V1', 500000.00, 4, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:44:58', '2025-11-25 15:44:58'),
(5, 1, 'Standard - V2', 450000.00, 2, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:45:15', '2025-11-25 15:45:15'),
(6, 1, 'Standard - V3', 400000.00, 2, '[\"wifi\", \"breakfast\", \"ac\"]', '2025-11-25 15:45:28', '2025-11-25 15:45:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Trương Phước Giàu', 'giau@gmail.com', NULL, '$2y$12$YU9B3y2sSGvZ9hNYPEohQONgqMCJOzu2O64Q0QUPfo1RN/pmjv.qe', 'customer', NULL, '2025-11-25 19:41:14', '2025-11-25 19:41:14'),
(2, 'Phan Hoàng Anh', 'anh@gmail.com', NULL, '$2y$12$X4CWewYue6j3UFvx/OIT4eZfseeJXYISE8JRc/uHt0DeIpG1wYQze', 'super_admin', NULL, '2025-11-25 20:22:17', '2025-11-25 20:22:17'),
(3, 'Nguyễn Minh Mẩn', 'man@gmail.com', NULL, '$2y$12$OsjKM7T9wAX3rzkmUZjWBO/KXRtB071HecAthQKcfdgnN5cb8Vqti', 'customer', NULL, '2025-11-25 23:06:29', '2025-11-25 23:06:29'),
(4, 'Hồ Ngọc Minh Khánh', 'khanh@gmail.com', NULL, '$2y$12$kMfmVhwmoEn4hPwEn2cDi.qpATwo8NZ2ygPlOshfIGgHlzoOzR/7K', 'branch_manager', NULL, '2025-11-25 23:48:02', '2025-11-25 23:48:02'),
(5, 'Lê Thị Thanh Hương', 'huong@gmail.com', NULL, '$2y$12$uNwpDM.Ytxy5EodYCf7bQednZRiF1nicYh/IV9R6FgI1.7HfVrR5y', 'branch_manager', NULL, '2025-11-25 23:51:12', '2025-11-25 23:51:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
