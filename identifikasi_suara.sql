-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for identifikasi_suara
CREATE DATABASE IF NOT EXISTS `identifikasi_suara` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `identifikasi_suara`;

-- Dumping structure for table identifikasi_suara.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.admins: ~1 rows (approximately)
INSERT INTO `admins` (`id`, `nama`, `email`, `password`, `no_hp`, `foto_profile`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@gmail.com', '$2y$12$mCMFNFansdDOQrOK6hhgWOAOBETKM0rRWEX20LLjUrKJFiOWSbC7m', '081225679175', NULL, '2025-12-10 07:57:22', '2026-01-06 07:01:10');

-- Dumping structure for table identifikasi_suara.hasil_identifikasi
CREATE TABLE IF NOT EXISTS `hasil_identifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usia` int DEFAULT NULL,
  `sumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_suara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hasil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akurasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribution_by_emotion` json DEFAULT NULL,
  `distribution_by_suku` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.hasil_identifikasi: ~6 rows (approximately)
INSERT INTO `hasil_identifikasi` (`id`, `nama`, `email`, `gender`, `usia`, `sumber`, `file_suara`, `durasi`, `hasil`, `akurasi`, `distribution_by_emotion`, `distribution_by_suku`, `created_at`, `updated_at`) VALUES
	(71, 'elva', 'elvrshdn@gmail.com', 'Laki-laki', 22, 'upload', 'betawi_mutea_6 menit.wav', '06:02', 'Angry', NULL, '{"Sad": {"count": 4, "percent": 1.108}, "Angry": {"count": 242, "percent": 67.036}, "Happy": {"count": 28, "percent": 7.7562}, "Neutral": {"count": 74, "percent": 20.4986}, "Surprised": {"count": 13, "percent": 3.6011}}', '{"Jawa": {"count": 4, "percent": 1.108}, "Batak": {"count": 37, "percent": 10.2493}, "Sunda": {"count": 136, "percent": 37.6731}, "Betawi": {"count": 110, "percent": 30.4709}, "Minang": {"count": 74, "percent": 20.4986}}', '2026-01-23 13:19:28', '2026-01-23 13:19:28'),
	(72, 'elva', 'elvrshdn@gmail.com', 'Laki-laki', 22, 'upload', 'betawi_mutea_6 menit.wav', '06:02', 'Angry', NULL, '{"Sad": {"count": 4, "percent": 1.108}, "Angry": {"count": 242, "percent": 67.036}, "Happy": {"count": 28, "percent": 7.7562}, "Neutral": {"count": 74, "percent": 20.4986}, "Surprised": {"count": 13, "percent": 3.6011}}', '{"Jawa": {"count": 4, "percent": 1.108}, "Batak": {"count": 37, "percent": 10.2493}, "Sunda": {"count": 136, "percent": 37.6731}, "Betawi": {"count": 110, "percent": 30.4709}, "Minang": {"count": 74, "percent": 20.4986}}', '2026-01-23 15:00:12', '2026-01-23 15:00:12'),
	(73, 'elvares', 'elvrshdn@gmail.com', 'Laki-laki', 22, 'upload', 'betawi_mutea_6 menit.wav', '06:02', 'Angry', NULL, '{"Sad": {"count": 4, "percent": 1.108}, "Angry": {"count": 242, "percent": 67.036}, "Happy": {"count": 28, "percent": 7.7562}, "Neutral": {"count": 74, "percent": 20.4986}, "Surprised": {"count": 13, "percent": 3.6011}}', '{"Jawa": {"count": 4, "percent": 1.108}, "Batak": {"count": 37, "percent": 10.2493}, "Sunda": {"count": 136, "percent": 37.6731}, "Betawi": {"count": 110, "percent": 30.4709}, "Minang": {"count": 74, "percent": 20.4986}}', '2026-01-25 15:11:22', '2026-01-25 15:11:22'),
	(74, 'elvares', 'elvrshdn@gmail.com', 'Laki-laki', 20, 'upload', 'yappinggggg.wav', '00:20', 'Angry', NULL, '{"Sad": {"count": 0, "percent": 0}, "Angry": {"count": 11, "percent": 57.8947}, "Happy": {"count": 0, "percent": 0}, "Neutral": {"count": 8, "percent": 42.1053}, "Surprised": {"count": 0, "percent": 0}}', '{"Jawa": {"count": 0, "percent": 0}, "Batak": {"count": 1, "percent": 5.2632}, "Sunda": {"count": 0, "percent": 0}, "Betawi": {"count": 1, "percent": 5.2632}, "Minang": {"count": 17, "percent": 89.4737}}', '2026-02-16 11:30:17', '2026-02-16 11:30:17'),
	(75, 'elvares', 'elvrshdn@gmail.com', 'Laki-laki', 22, 'upload', 'sita_negatif.wav', '00:59', 'Surprised', NULL, '{"Sad": {"count": 0, "percent": 0}, "Angry": {"count": 7, "percent": 12.069}, "Happy": {"count": 0, "percent": 0}, "Neutral": {"count": 18, "percent": 31.0345}, "Surprised": {"count": 33, "percent": 56.8966}}', '{"Jawa": {"count": 5, "percent": 8.6207}, "Batak": {"count": 0, "percent": 0}, "Sunda": {"count": 0, "percent": 0}, "Betawi": {"count": 5, "percent": 8.6207}, "Minang": {"count": 48, "percent": 82.7586}}', '2026-05-04 12:51:30', '2026-05-04 12:51:30'),
	(76, 'elvares', 'elvrshdn@gmail.com', 'Laki-laki', 22, 'upload', 'Sedih.wav', '01:14', 'Angry', NULL, '{"Sad": {"count": 0, "percent": 0}, "Angry": {"count": 62, "percent": 84.9315}, "Happy": {"count": 0, "percent": 0}, "Neutral": {"count": 11, "percent": 15.0685}, "Surprised": {"count": 0, "percent": 0}}', '{"Jawa": {"count": 0, "percent": 0}, "Batak": {"count": 1, "percent": 1.3699}, "Sunda": {"count": 50, "percent": 68.4932}, "Betawi": {"count": 18, "percent": 24.6575}, "Minang": {"count": 4, "percent": 5.4795}}', '2026-05-04 13:22:25', '2026-05-04 13:22:25');

-- Dumping structure for table identifikasi_suara.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.migrations: ~8 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2025_11_11_152247_create_users_table', 1),
	(2, '2025_11_11_152330_create_admins_table', 1),
	(3, '2025_11_11_152625_create_sessions_table', 1),
	(4, '2025_12_05_075040_create_setting_waktus_table', 1),
	(5, '2025_12_10_143028_create_hasil_identifikasi_table', 1),
	(6, '2025_12_18_144300_add_durasi_to_hasil_identifikasi_table', 2),
	(7, '2026_01_06_133518_add_jk_usia_to_admins_table', 3),
	(8, '2026_01_06_135640_drop_jk_usia_from_admins_table', 4);

-- Dumping structure for table identifikasi_suara.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('LhcF7DL7ct2fdwHEr22EH4eWd1bGyGKPIOvKZzy7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiYkplTXoyd2NKYUMwcWVydUVzWXp0dVZzMUJrMGk1Y0gxWTJRYTZMQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1778033985),
	('WH5zYRNewO7zmVMNc3lDIwlFhvVetmgtXtjBynmp', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36 Edg/147.0.0.0', 'YTo4OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiWUVkZFN0ZW9PcUZOT0FJZzNJT3RCUXcxNFFUODJJb1dFaFViaVY2bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo4OiJ1c2VybmFtZSI7czo3OiJlbHZhcmVzIjtzOjU6ImVtYWlsIjtzOjE4OiJlbHZyc2hkbkBnbWFpbC5jb20iO3M6NToibm9faHAiO3M6MTI6IjA4MTIyNTY3OTE3NSI7czo2OiJnZW5kZXIiO3M6OToiTGFraS1sYWtpIjtzOjQ6InVzaWEiO3M6MjoiMjIiO30=', 1777901078);

-- Dumping structure for table identifikasi_suara.setting_waktus
CREATE TABLE IF NOT EXISTS `setting_waktus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `durasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3-5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.setting_waktus: ~1 rows (approximately)
INSERT INTO `setting_waktus` (`id`, `durasi`, `created_at`, `updated_at`) VALUES
	(1, '1-10', '2025-12-11 03:46:33', '2026-05-04 12:28:18');

-- Dumping structure for table identifikasi_suara.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table identifikasi_suara.users: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
