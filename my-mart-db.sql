-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 06, 2026 at 03:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my-mart-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `event`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\Category', 1, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Beras & Tepung\", \"slug\": \"beras-tepung\", \"description\": null}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(2, 'default', 'created', 'App\\Models\\Category', 2, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Minyak, Gula & Bumbu\", \"slug\": \"minyak-gula-bumbu\", \"description\": null}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(3, 'default', 'created', 'App\\Models\\Category', 3, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Mie & Makanan Instan\", \"slug\": \"mie-makanan-instan\", \"description\": null}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(4, 'default', 'created', 'App\\Models\\Category', 4, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Minuman & Susu\", \"slug\": \"minuman-susu\", \"description\": null}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(5, 'default', 'created', 'App\\Models\\Category', 5, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Perlengkapan Rumah\", \"slug\": \"perlengkapan-rumah\", \"description\": null}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(6, 'default', 'created', 'App\\Models\\User', 1, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Owner Toko\", \"email\": \"owner@example.com\", \"phone\": \"081234567890\", \"status\": true, \"address\": null, \"role_id\": 1, \"password\": \"$2y$12$NBbWJDBHIU9kKonyxhzD3.59NutI./XHUfLKGfOD6ppsfn6dy1XkO\"}}', NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(7, 'default', 'created', 'App\\Models\\User', 2, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Admin\", \"email\": \"admin@example.com\", \"phone\": \"081234567891\", \"status\": true, \"address\": null, \"role_id\": 2, \"password\": \"$2y$12$iVTuwRUZDOlcsupPwhznxuPNOcBeud5sYjVAM6PqsBl/FVEGQ4gmG\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(8, 'default', 'created', 'App\\Models\\Product', 1, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Beras Putih Premium 5kg\", \"slug\": \"beras-putih-premium-5kg\", \"image\": \"products/1.jpg\", \"price\": \"70000.00\", \"stock\": 100, \"buy_price\": \"55000.00\", \"category_id\": 1, \"description\": \"Beras putih bulir panjang, bersih, dan pulen. Kemasan karung 5kg.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(9, 'default', 'created', 'App\\Models\\Product', 2, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Beras Merah Organik 1kg\", \"slug\": \"beras-merah-organik-1kg\", \"image\": \"products/2.jpg\", \"price\": \"25000.00\", \"stock\": 100, \"buy_price\": \"18000.00\", \"category_id\": 1, \"description\": \"Beras merah kaya serat, cocok untuk diet sehat.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(10, 'default', 'created', 'App\\Models\\Product', 3, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Tepung Terigu Serbaguna 1kg\", \"slug\": \"tepung-terigu-serbaguna-1kg\", \"image\": \"products/3.jpg\", \"price\": \"12000.00\", \"stock\": 100, \"buy_price\": \"9000.00\", \"category_id\": 1, \"description\": \"Tepung gandum putih halus untuk berbagai keperluan baking.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(11, 'default', 'created', 'App\\Models\\Product', 4, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Tepung Maizena (Pati Jagung) 250g\", \"slug\": \"tepung-maizena-pati-jagung-250g\", \"image\": \"products/4.jpg\", \"price\": \"8500.00\", \"stock\": 100, \"buy_price\": \"6500.00\", \"category_id\": 1, \"description\": \"Tepung pati jagung halus untuk pengental masakan.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(12, 'default', 'created', 'App\\Models\\Product', 5, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Oatmeal Instan 500g\", \"slug\": \"oatmeal-instan-500g\", \"image\": \"products/5.jpg\", \"price\": \"30000.00\", \"stock\": 100, \"buy_price\": \"23000.00\", \"category_id\": 1, \"description\": \"Gandum oat utuh yang cepat saji untuk sarapan sehat.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(13, 'default', 'created', 'App\\Models\\Product', 6, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Kacang Hijau Kupas 500g\", \"slug\": \"kacang-hijau-kupas-500g\", \"image\": \"products/6.jpg\", \"price\": \"18000.00\", \"stock\": 100, \"buy_price\": \"14000.00\", \"category_id\": 1, \"description\": \"Kacang hijau kupas bersih, cocok untuk bubur atau isian kue.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(14, 'default', 'created', 'App\\Models\\Product', 7, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Minyak Goreng Nabati 1L\", \"slug\": \"minyak-goreng-nabati-1l\", \"image\": \"products/7.jpg\", \"price\": \"19000.00\", \"stock\": 100, \"buy_price\": \"15000.00\", \"category_id\": 2, \"description\": \"Minyak goreng kelapa sawit jernih dalam kemasan botol.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(15, 'default', 'created', 'App\\Models\\Product', 8, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Gula Pasir Putih 1kg\", \"slug\": \"gula-pasir-putih-1kg\", \"image\": \"products/8.jpg\", \"price\": \"16000.00\", \"stock\": 100, \"buy_price\": \"12500.00\", \"category_id\": 2, \"description\": \"Gula tebu kristal putih murni, manis alami.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(16, 'default', 'created', 'App\\Models\\Product', 9, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Garam Laut Halus 500g\", \"slug\": \"garam-laut-halus-500g\", \"image\": \"products/9.jpg\", \"price\": \"5000.00\", \"stock\": 100, \"buy_price\": \"3500.00\", \"category_id\": 2, \"description\": \"Garam laut alami beryodium dengan tekstur halus.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(17, 'default', 'created', 'App\\Models\\Product', 10, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Lada Hitam Bubuk Murni 50g\", \"slug\": \"lada-hitam-bubuk-murni-50g\", \"image\": \"products/10.jpg\", \"price\": \"15000.00\", \"stock\": 100, \"buy_price\": \"11000.00\", \"category_id\": 2, \"description\": \"Bubuk lada hitam asli dengan aroma kuat dan pedas.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(18, 'default', 'created', 'App\\Models\\Product', 11, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Minyak Zaitun Extra Virgin 250ml\", \"slug\": \"minyak-zaitun-extra-virgin-250ml\", \"image\": \"products/11.jpg\", \"price\": \"45000.00\", \"stock\": 100, \"buy_price\": \"35000.00\", \"category_id\": 2, \"description\": \"Minyak zaitun murni untuk dressing salad atau menumis.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(19, 'default', 'created', 'App\\Models\\Product', 12, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Madu Murni Asli 350g\", \"slug\": \"madu-murni-asli-350g\", \"image\": \"products/12.jpg\", \"price\": \"55000.00\", \"stock\": 100, \"buy_price\": \"42000.00\", \"category_id\": 2, \"description\": \"Madu hutan asli dalam kemasan toples kaca.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(20, 'default', 'created', 'App\\Models\\Product', 13, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Mie Telur Kering 200g\", \"slug\": \"mie-telur-kering-200g\", \"image\": \"products/13.jpg\", \"price\": \"6000.00\", \"stock\": 100, \"buy_price\": \"4500.00\", \"category_id\": 3, \"description\": \"Mie telur kering keriting, cocok untuk mie goreng atau rebus.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(21, 'default', 'created', 'App\\Models\\Product', 14, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Pasta Spaghetti 500g\", \"slug\": \"pasta-spaghetti-500g\", \"image\": \"products/14.jpg\", \"price\": \"18000.00\", \"stock\": 100, \"buy_price\": \"14000.00\", \"category_id\": 3, \"description\": \"Pasta Italia jenis spaghetti dari gandum durum.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(22, 'default', 'created', 'App\\Models\\Product', 15, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Sarden Kaleng Saus Tomat 425g\", \"slug\": \"sarden-kaleng-saus-tomat-425g\", \"image\": \"products/15.jpg\", \"price\": \"22000.00\", \"stock\": 100, \"buy_price\": \"17000.00\", \"category_id\": 3, \"description\": \"Ikan sarden besar dalam saus tomat kental, siap santap.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(23, 'default', 'created', 'App\\Models\\Product', 16, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Telur Ayam Segar 1 Tray (30 Butir)\", \"slug\": \"telur-ayam-segar-1-tray-30-butir\", \"image\": \"products/16.jpg\", \"price\": \"55000.00\", \"stock\": 100, \"buy_price\": \"45000.00\", \"category_id\": 3, \"description\": \"Telur ayam negeri segar dalam kemasan tray karton.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(24, 'default', 'created', 'App\\Models\\Product', 17, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Roti Tawar Gandum Utuh\", \"slug\": \"roti-tawar-gandum-utuh\", \"image\": \"products/17.jpg\", \"price\": \"20000.00\", \"stock\": 100, \"buy_price\": \"15000.00\", \"category_id\": 3, \"description\": \"Roti tawar sehat dari biji gandum utuh kaya serat.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(25, 'default', 'created', 'App\\Models\\Product', 18, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Keripik Kentang Rasa Original 100g\", \"slug\": \"keripik-kentang-rasa-original-100g\", \"image\": \"products/18.jpg\", \"price\": \"12000.00\", \"stock\": 100, \"buy_price\": \"9000.00\", \"category_id\": 3, \"description\": \"Camilan keripik kentang tipis dan renyah dengan garam laut.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(26, 'default', 'created', 'App\\Models\\Product', 19, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Kopi Arabika Bubuk 200g\", \"slug\": \"kopi-arabika-bubuk-200g\", \"image\": \"products/19.jpg\", \"price\": \"35000.00\", \"stock\": 100, \"buy_price\": \"27000.00\", \"category_id\": 4, \"description\": \"Kopi bubuk arabika murni dengan aroma yang kaya.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(27, 'default', 'created', 'App\\Models\\Product', 20, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Teh Hitam Celup (Box isi 50)\", \"slug\": \"teh-hitam-celup-box-isi-50\", \"image\": \"products/20.jpg\", \"price\": \"15000.00\", \"stock\": 100, \"buy_price\": \"11000.00\", \"category_id\": 4, \"description\": \"Teh hitam celup klasik, nikmat disajikan panas atau dingin.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(28, 'default', 'created', 'App\\Models\\Product', 21, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Susu Segar Full Cream 1L\", \"slug\": \"susu-segar-full-cream-1l\", \"image\": \"products/21.jpg\", \"price\": \"22000.00\", \"stock\": 100, \"buy_price\": \"17000.00\", \"category_id\": 4, \"description\": \"Susu sapi segar pasteurisasi, kaya kalsium.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(29, 'default', 'created', 'App\\Models\\Product', 22, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Air Mineral Botol 600ml (Pack isi 12)\", \"slug\": \"air-mineral-botol-600ml-pack-isi-12\", \"image\": \"products/22.jpg\", \"price\": \"30000.00\", \"stock\": 100, \"buy_price\": \"24000.00\", \"category_id\": 4, \"description\": \"Air mineral pegunungan alami dalam kemasan praktis.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(30, 'default', 'created', 'App\\Models\\Product', 23, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Jus Jeruk Asli 1L\", \"slug\": \"jus-jeruk-asli-1l\", \"image\": \"products/23.jpg\", \"price\": \"28000.00\", \"stock\": 100, \"buy_price\": \"22000.00\", \"category_id\": 4, \"description\": \"Jus buah jeruk asli tanpa tambahan gula.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(31, 'default', 'created', 'App\\Models\\Product', 24, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Susu Almond Unsweetened 1L\", \"slug\": \"susu-almond-unsweetened-1l\", \"image\": \"products/24.jpg\", \"price\": \"45000.00\", \"stock\": 100, \"buy_price\": \"35000.00\", \"category_id\": 4, \"description\": \"Susu nabati dari kacang almond, tanpa pemanis.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(32, 'default', 'created', 'App\\Models\\Product', 25, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Sabun Batang Putih Alami 3x90g\", \"slug\": \"sabun-batang-putih-alami-3x90g\", \"image\": \"products/25.jpg\", \"price\": \"15000.00\", \"stock\": 100, \"buy_price\": \"11000.00\", \"category_id\": 5, \"description\": \"Paket sabun mandi batang, lembut di kulit dengan wangi netral.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(33, 'default', 'created', 'App\\Models\\Product', 26, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Deterjen Cair Pakaian 1L\", \"slug\": \"deterjen-cair-pakaian-1l\", \"image\": \"products/26.jpg\", \"price\": \"25000.00\", \"stock\": 100, \"buy_price\": \"19000.00\", \"category_id\": 5, \"description\": \"Sabun cuci pakaian cair konsentrat, ampuh bersihkan noda.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(34, 'default', 'created', 'App\\Models\\Product', 27, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Cairan Pencuci Piring Jeruk Nipis 750ml\", \"slug\": \"cairan-pencuci-piring-jeruk-nipis-750ml\", \"image\": \"products/27.jpg\", \"price\": \"15000.00\", \"stock\": 100, \"buy_price\": \"11000.00\", \"category_id\": 5, \"description\": \"Sabun cuci piring dengan ekstrak jeruk nipis penghilang lemak.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(35, 'default', 'created', 'App\\Models\\Product', 28, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Shampoo & Kondisioner 2-in-1 300ml\", \"slug\": \"shampoo-kondisioner-2-in-1-300ml\", \"image\": \"products/28.jpg\", \"price\": \"30000.00\", \"stock\": 100, \"buy_price\": \"23000.00\", \"category_id\": 5, \"description\": \"Shampoo perawatan rambut praktis untuk sehari-hari.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(36, 'default', 'created', 'App\\Models\\Product', 29, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Sikat Gigi Bambu (Pack isi 2)\", \"slug\": \"sikat-gigi-bambu-pack-isi-2\", \"image\": \"products/29.jpg\", \"price\": \"20000.00\", \"stock\": 100, \"buy_price\": \"15000.00\", \"category_id\": 5, \"description\": \"Sikat gigi ramah lingkungan dengan gagang bambu.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(37, 'default', 'created', 'App\\Models\\Product', 30, 'created', NULL, NULL, '{\"attributes\": {\"name\": \"Gulungan Tisu Toilet (Pack isi 6)\", \"slug\": \"gulungan-tisu-toilet-pack-isi-6\", \"image\": \"products/30.jpg\", \"price\": \"25000.00\", \"stock\": 100, \"buy_price\": \"19000.00\", \"category_id\": 5, \"description\": \"Tisu toilet 3 lapis yang lembut dan kuat.\"}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(38, 'default', 'created', 'App\\Models\\Promo', 2, 'created', NULL, NULL, '{\"attributes\": {\"code\": \"mymart\", \"type\": \"percentage\", \"value\": \"10.00\", \"status\": \"active\", \"end_date\": \"2026-12-30T17:00:00.000000Z\", \"start_date\": \"2023-12-31T17:00:00.000000Z\", \"times_used\": 0, \"description\": \"Diskon pembelian pertama di My Mart\", \"usage_limit\": 100, \"max_discount\": \"20000.00\", \"min_purchase\": \"50000.00\", \"limit_per_user\": true}}', NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(39, 'default', 'created', 'App\\Models\\Transaction', 1, 'created', 'App\\Models\\User', 3, '{\"attributes\": {\"notes\": null, \"status\": \"pending\", \"user_id\": 3, \"latitude\": \"-6.87499575\", \"order_id\": \"INV-1767667353\", \"longitude\": \"109.66500781\", \"promo_code\": null, \"snap_token\": null, \"total_amount\": \"70000.00\", \"shipping_cost\": 0, \"payment_method\": \"midtrans\", \"discount_amount\": \"0.00\", \"shipping_address\": \"aa\", \"distance_from_store\": \"0.00\"}}', NULL, '2026-01-06 02:42:33', '2026-01-06 02:42:33'),
(40, 'default', 'updated', 'App\\Models\\Product', 1, 'updated', 'App\\Models\\User', 3, '{\"old\": {\"stock\": 100}, \"attributes\": {\"stock\": 99}}', NULL, '2026-01-06 02:42:33', '2026-01-06 02:42:33'),
(41, 'default', 'updated', 'App\\Models\\Transaction', 1, 'updated', 'App\\Models\\User', 3, '{\"old\": {\"snap_token\": null}, \"attributes\": {\"snap_token\": \"ffc95b89-f8ef-4fa8-8153-1a699db9d842\"}}', NULL, '2026-01-06 02:42:34', '2026-01-06 02:42:34'),
(42, 'default', 'updated', 'App\\Models\\Transaction', 1, 'updated', 'App\\Models\\User', 3, '{\"old\": {\"status\": \"pending\"}, \"attributes\": {\"status\": \"diproses\"}}', NULL, '2026-01-06 02:42:46', '2026-01-06 02:42:46'),
(43, 'default', 'updated', 'App\\Models\\Transaction', 1, 'updated', 'App\\Models\\User', 2, '{\"old\": {\"status\": \"diproses\"}, \"attributes\": {\"status\": \"dikirim\"}}', NULL, '2026-01-06 03:01:44', '2026-01-06 03:01:44'),
(44, 'default', 'updated', 'App\\Models\\Transaction', 1, 'updated', 'App\\Models\\User', 3, '{\"old\": {\"status\": \"dikirim\"}, \"attributes\": {\"status\": \"selesai\"}}', NULL, '2026-01-06 03:07:06', '2026-01-06 03:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Beras & Tepung', 'beras-tepung', NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(2, 'Minyak, Gula & Bumbu', 'minyak-gula-bumbu', NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(3, 'Mie & Makanan Instan', 'mie-makanan-instan', NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(4, 'Minuman & Susu', 'minuman-susu', NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(5, 'Perlengkapan Rumah', 'perlengkapan-rumah', NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_14_044544_create_categories_table', 1),
(5, '2025_10_14_044738_create_roles_table', 1),
(6, '2025_10_14_045518_create_products_table', 1),
(7, '2025_10_14_052814_create_transactions_table', 1),
(8, '2025_10_15_082503_create_transaction_items_table', 1),
(9, '2025_11_12_134200_create_promos_table', 1),
(10, '2025_11_12_134210_create_promo_usages_table', 1),
(11, '2025_11_25_122704_create_store_settings_table', 1),
(12, '2025_12_04_060800_add_foreign_keys_to_users_table', 1),
(13, '2025_12_13_135132_create_activity_log_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `buy_price` decimal(15,2) NOT NULL,
  `stock` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image`, `price`, `buy_price`, `stock`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Beras Putih Premium 5kg', 'beras-putih-premium-5kg', 'Beras putih bulir panjang, bersih, dan pulen. Kemasan karung 5kg.', 'products/1.jpg', 70000.00, 55000.00, 99, NULL, '2026-01-06 02:37:09', '2026-01-06 02:42:33'),
(2, 1, 'Beras Merah Organik 1kg', 'beras-merah-organik-1kg', 'Beras merah kaya serat, cocok untuk diet sehat.', 'products/2.jpg', 25000.00, 18000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(3, 1, 'Tepung Terigu Serbaguna 1kg', 'tepung-terigu-serbaguna-1kg', 'Tepung gandum putih halus untuk berbagai keperluan baking.', 'products/3.jpg', 12000.00, 9000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(4, 1, 'Tepung Maizena (Pati Jagung) 250g', 'tepung-maizena-pati-jagung-250g', 'Tepung pati jagung halus untuk pengental masakan.', 'products/4.jpg', 8500.00, 6500.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(5, 1, 'Oatmeal Instan 500g', 'oatmeal-instan-500g', 'Gandum oat utuh yang cepat saji untuk sarapan sehat.', 'products/5.jpg', 30000.00, 23000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(6, 1, 'Kacang Hijau Kupas 500g', 'kacang-hijau-kupas-500g', 'Kacang hijau kupas bersih, cocok untuk bubur atau isian kue.', 'products/6.jpg', 18000.00, 14000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(7, 2, 'Minyak Goreng Nabati 1L', 'minyak-goreng-nabati-1l', 'Minyak goreng kelapa sawit jernih dalam kemasan botol.', 'products/7.jpg', 19000.00, 15000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(8, 2, 'Gula Pasir Putih 1kg', 'gula-pasir-putih-1kg', 'Gula tebu kristal putih murni, manis alami.', 'products/8.jpg', 16000.00, 12500.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(9, 2, 'Garam Laut Halus 500g', 'garam-laut-halus-500g', 'Garam laut alami beryodium dengan tekstur halus.', 'products/9.jpg', 5000.00, 3500.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(10, 2, 'Lada Hitam Bubuk Murni 50g', 'lada-hitam-bubuk-murni-50g', 'Bubuk lada hitam asli dengan aroma kuat dan pedas.', 'products/10.jpg', 15000.00, 11000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(11, 2, 'Minyak Zaitun Extra Virgin 250ml', 'minyak-zaitun-extra-virgin-250ml', 'Minyak zaitun murni untuk dressing salad atau menumis.', 'products/11.jpg', 45000.00, 35000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(12, 2, 'Madu Murni Asli 350g', 'madu-murni-asli-350g', 'Madu hutan asli dalam kemasan toples kaca.', 'products/12.jpg', 55000.00, 42000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(13, 3, 'Mie Telur Kering 200g', 'mie-telur-kering-200g', 'Mie telur kering keriting, cocok untuk mie goreng atau rebus.', 'products/13.jpg', 6000.00, 4500.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(14, 3, 'Pasta Spaghetti 500g', 'pasta-spaghetti-500g', 'Pasta Italia jenis spaghetti dari gandum durum.', 'products/14.jpg', 18000.00, 14000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(15, 3, 'Sarden Kaleng Saus Tomat 425g', 'sarden-kaleng-saus-tomat-425g', 'Ikan sarden besar dalam saus tomat kental, siap santap.', 'products/15.jpg', 22000.00, 17000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(16, 3, 'Telur Ayam Segar 1 Tray (30 Butir)', 'telur-ayam-segar-1-tray-30-butir', 'Telur ayam negeri segar dalam kemasan tray karton.', 'products/16.jpg', 55000.00, 45000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(17, 3, 'Roti Tawar Gandum Utuh', 'roti-tawar-gandum-utuh', 'Roti tawar sehat dari biji gandum utuh kaya serat.', 'products/17.jpg', 20000.00, 15000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(18, 3, 'Keripik Kentang Rasa Original 100g', 'keripik-kentang-rasa-original-100g', 'Camilan keripik kentang tipis dan renyah dengan garam laut.', 'products/18.jpg', 12000.00, 9000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(19, 4, 'Kopi Arabika Bubuk 200g', 'kopi-arabika-bubuk-200g', 'Kopi bubuk arabika murni dengan aroma yang kaya.', 'products/19.jpg', 35000.00, 27000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(20, 4, 'Teh Hitam Celup (Box isi 50)', 'teh-hitam-celup-box-isi-50', 'Teh hitam celup klasik, nikmat disajikan panas atau dingin.', 'products/20.jpg', 15000.00, 11000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(21, 4, 'Susu Segar Full Cream 1L', 'susu-segar-full-cream-1l', 'Susu sapi segar pasteurisasi, kaya kalsium.', 'products/21.jpg', 22000.00, 17000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(22, 4, 'Air Mineral Botol 600ml (Pack isi 12)', 'air-mineral-botol-600ml-pack-isi-12', 'Air mineral pegunungan alami dalam kemasan praktis.', 'products/22.jpg', 30000.00, 24000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(23, 4, 'Jus Jeruk Asli 1L', 'jus-jeruk-asli-1l', 'Jus buah jeruk asli tanpa tambahan gula.', 'products/23.jpg', 28000.00, 22000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(24, 4, 'Susu Almond Unsweetened 1L', 'susu-almond-unsweetened-1l', 'Susu nabati dari kacang almond, tanpa pemanis.', 'products/24.jpg', 45000.00, 35000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(25, 5, 'Sabun Batang Putih Alami 3x90g', 'sabun-batang-putih-alami-3x90g', 'Paket sabun mandi batang, lembut di kulit dengan wangi netral.', 'products/25.jpg', 15000.00, 11000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(26, 5, 'Deterjen Cair Pakaian 1L', 'deterjen-cair-pakaian-1l', 'Sabun cuci pakaian cair konsentrat, ampuh bersihkan noda.', 'products/26.jpg', 25000.00, 19000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(27, 5, 'Cairan Pencuci Piring Jeruk Nipis 750ml', 'cairan-pencuci-piring-jeruk-nipis-750ml', 'Sabun cuci piring dengan ekstrak jeruk nipis penghilang lemak.', 'products/27.jpg', 15000.00, 11000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(28, 5, 'Shampoo & Kondisioner 2-in-1 300ml', 'shampoo-kondisioner-2-in-1-300ml', 'Shampoo perawatan rambut praktis untuk sehari-hari.', 'products/28.jpg', 30000.00, 23000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(29, 5, 'Sikat Gigi Bambu (Pack isi 2)', 'sikat-gigi-bambu-pack-isi-2', 'Sikat gigi ramah lingkungan dengan gagang bambu.', 'products/29.jpg', 20000.00, 15000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(30, 5, 'Gulungan Tisu Toilet (Pack isi 6)', 'gulungan-tisu-toilet-pack-isi-6', 'Tisu toilet 3 lapis yang lembut dan kuat.', 'products/30.jpg', 25000.00, 19000.00, 100, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `promos`
--

CREATE TABLE `promos` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `times_used` int NOT NULL DEFAULT '0',
  `limit_per_user` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promos`
--

INSERT INTO `promos` (`id`, `code`, `description`, `type`, `value`, `max_discount`, `min_purchase`, `start_date`, `end_date`, `usage_limit`, `times_used`, `limit_per_user`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'mymart', 'Diskon pembelian pertama di My Mart', 'percentage', 10.00, 20000.00, 50000.00, '2024-01-01', '2026-12-31', 100, 0, 1, 'active', NULL, '2026-01-06 02:37:09', '2026-01-06 03:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `promo_usages`
--

CREATE TABLE `promo_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `promo_id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Owner of the store', '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(2, 'admin', 'Administrator', '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(3, 'customer', 'Regular Customer', '2026-01-06 02:37:08', '2026-01-06 02:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('38X8zeIBCwq5B6IHI844TiLUrw5uZ3mIw7ceY5Cp', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOWRBckpHN0NYZzJpcWZQbUpZcXg2RXZScjU3ZndkZFM1YTZmTXNhWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vbXktbWFydC5jYXJlL2NhcnQvc3VtbWFyeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6MTQ6InByZXZpb3VzX3Zpc2l0IjtzOjI1OiJodHRwczovL215LW1hcnQuY2FyZS9jYXJ0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1767667366),
('5aJY8BbnuERZfQi6C74xmDSxiHEu2JZHM6jWhXeV', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRElMMTJjeUNEV0FIRkNFeEY5dHNyUW9rMVo5bWJHU08wN0Z1aTBwcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vbXktbWFydC5jYXJlL2NhcnQvc3VtbWFyeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6MTQ6InByZXZpb3VzX3Zpc2l0IjtzOjIxOiJodHRwczovL215LW1hcnQuY2FyZS8iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1767668827),
('RXOLAMMWc31GeD7fghZsTzTfEtpIPiKOlYOFocc9', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWGxsTmVJbFJpNm1kb2Z5SGZVazlNMUFJTWdjZ0lUZWJhMXdRY040TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vbXktbWFydC5jYXJlL2NhcnQvc3VtbWFyeSI7fXM6MzoidXJsIjthOjE6e3M6MTQ6InByZXZpb3VzX3Zpc2l0IjtzOjIxOiJodHRwczovL215LW1hcnQuY2FyZS8iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1767671647);

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Toko Sembako',
  `store_address` text COLLATE utf8mb4_unicode_ci,
  `store_latitude` decimal(10,8) NOT NULL DEFAULT '-6.20000000',
  `store_longitude` decimal(11,8) NOT NULL DEFAULT '106.81666600',
  `free_shipping_radius` int NOT NULL DEFAULT '10000' COMMENT 'Radius gratis ongkir dalam meter',
  `max_delivery_distance` int NOT NULL DEFAULT '50000' COMMENT 'Jarak maksimal pengiriman dalam meter',
  `shipping_cost` int NOT NULL DEFAULT '5000' COMMENT 'Biaya ongkir di luar zona gratis',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `store_name`, `store_address`, `store_latitude`, `store_longitude`, `free_shipping_radius`, `max_delivery_distance`, `shipping_cost`, `created_at`, `updated_at`) VALUES
(1, 'My Mart', 'ini alamat toko', -6.87499575, 109.66500781, 5000, 10000, 5000, '2026-01-06 02:37:08', '2026-01-06 03:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `promo_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('midtrans','cod') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans' COMMENT 'Metode pembayaran: midtrans atau cod',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distance_from_store` decimal(10,2) DEFAULT NULL COMMENT 'Jarak dari toko dalam meter',
  `shipping_cost` int NOT NULL DEFAULT '0' COMMENT 'Biaya ongkir',
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `created_at`, `updated_at`, `deleted_at`, `user_id`, `order_id`, `total_amount`, `promo_code`, `discount_amount`, `snap_token`, `payment_method`, `status`, `shipping_address`, `latitude`, `longitude`, `distance_from_store`, `shipping_cost`, `notes`) VALUES
(1, '2026-01-06 02:42:33', '2026-01-06 03:07:06', NULL, 3, 'INV-1767667353', 70000.00, NULL, 0.00, 'ffc95b89-f8ef-4fa8-8153-1a699db9d842', 'midtrans', 'selesai', 'aa', '-6.87499575', '109.66500781', 0.00, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `created_at`, `updated_at`, `transaction_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(1, '2026-01-06 02:42:33', '2026-01-06 02:42:33', 1, 1, 'Beras Putih Premium 5kg', 1, 70000.00, 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL DEFAULT '2',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `address`, `email_verified_at`, `password`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Owner Toko', 'owner@example.com', '081234567890', NULL, NULL, '$2y$12$NBbWJDBHIU9kKonyxhzD3.59NutI./XHUfLKGfOD6ppsfn6dy1XkO', 1, NULL, NULL, '2026-01-06 02:37:08', '2026-01-06 02:37:08'),
(2, 2, 'Admin', 'admin@example.com', '081234567891', NULL, NULL, '$2y$12$iVTuwRUZDOlcsupPwhznxuPNOcBeud5sYjVAM6PqsBl/FVEGQ4gmG', 1, NULL, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09'),
(3, 3, 'Budi Pelanggan', 'budi@example.com', '081234567892', NULL, NULL, '$2y$12$VOAhhOdkdu5AHA5dvtXUTuvzZ2OH4jvxarH4QdxnHKw5nRNpN9e16', 1, NULL, NULL, '2026-01-06 02:37:09', '2026-01-06 02:37:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_name_unique` (`name`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promos_code_unique` (`code`);

--
-- Indexes for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_usages_user_id_foreign` (`user_id`),
  ADD KEY `promo_usages_promo_id_foreign` (`promo_id`),
  ADD KEY `promo_usages_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_order_id_unique` (`order_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `promos`
--
ALTER TABLE `promos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promo_usages`
--
ALTER TABLE `promo_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_usages`
--
ALTER TABLE `promo_usages`
  ADD CONSTRAINT `promo_usages_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_usages_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `promo_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
