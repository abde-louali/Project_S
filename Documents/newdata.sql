-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for snthys
CREATE DATABASE IF NOT EXISTS `snthys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `snthys`;

-- Dumping structure for table snthys.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `cin` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_cin_unique` (`cin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.admins: ~0 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`id`, `username`, `password`, `first_name`, `last_name`, `cin`, `created_at`, `updated_at`) VALUES
	(1, 'aziza', '$2y$12$H3SzV4O3DvXPj4pwp5FY8OCnK3NhVJKNOSTq8lDRl/OVEK0QTyva2', 'Aziza', 'admin', 'AZ123', '2025-04-01 16:18:51', '2025-04-14 09:32:49');

-- Dumping structure for table snthys.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table snthys.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table snthys.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code_class` varchar(50) NOT NULL,
  `filier_name` varchar(40) NOT NULL,
  `cin` varchar(40) NOT NULL,
  `s_fname` varchar(100) NOT NULL,
  `s_lname` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.classes: ~9 rows (approximately)
DELETE FROM `classes`;
INSERT INTO `classes` (`id`, `code_class`, `filier_name`, `cin`, `s_fname`, `s_lname`, `age`, `created_at`, `updated_at`) VALUES
	(1, '168', 'Christopher Cartwright', '17799', 'Camylle', 'VonRueden', 24, '2025-04-01 16:18:51', '2025-04-01 16:18:51'),
	(2, '155', 'Milan Carroll', '67151', 'Lauryn', 'Walter', 17, '2025-04-01 16:18:51', '2025-04-01 16:18:51'),
	(3, '107', 'Marcelino Nolan', '59296', 'Jazmin', 'Jakubowski', 26, '2025-04-01 16:18:51', '2025-04-01 16:18:51'),
	(4, '196', 'Maud Kemmer', '66541', 'Josue', 'Huels', 22, '2025-04-01 16:18:51', '2025-04-01 16:18:51'),
	(5, 'CS101', 'Computer Science', 'AB123456', 'Ahmed', 'Bouzid', 20, '2025-04-01 16:20:28', '2025-04-01 16:20:28'),
	(6, 'ENG202', 'Engineering', 'XY789012', 'Fatima', 'El Amrani', 17, '2025-04-01 16:20:28', '2025-04-01 16:20:28'),
	(7, 'MED303', 'Medicine', 'CD345678', 'Hassan', 'Ouhadi', 21, '2025-04-01 16:20:28', '2025-04-01 16:20:28'),
	(8, 'LAW404', 'Law', 'EF901234', 'Salma', 'Bennani', 18, '2025-04-01 16:20:28', '2025-04-01 16:20:28'),
	(9, 'ECO505', 'Economics', 'GH567890', 'Youssef', 'Chafiq', 19, '2025-04-01 16:20:28', '2025-04-01 16:20:28'),
	(10, 'CS101', 'Computer Science', 'DA123', 'Salim', 'Amlah', 19, NULL, NULL);

-- Dumping structure for table snthys.document_validations
CREATE TABLE IF NOT EXISTS `document_validations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `cin` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `verified_name` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `file_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_details`)),
  `errors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`errors`)),
  `filier_name` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `validation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_validations_student_id_foreign` (`student_id`),
  KEY `document_validations_cin_index` (`cin`),
  KEY `document_validations_filier_name_class_name_index` (`filier_name`,`class_name`),
  CONSTRAINT `document_validations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.document_validations: ~0 rows (approximately)
DELETE FROM `document_validations`;

-- Dumping structure for table snthys.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table snthys.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table snthys.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table snthys.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.migrations: ~7 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_03_03_120558_create_admins_table', 1),
	(5, '2025_03_03_120850_create_classes_table', 1),
	(6, '2025_03_03_121035_create_students_table', 1),
	(7, '2024_05_25_000000_create_document_validations_table', 2);

-- Dumping structure for table snthys.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table snthys.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.sessions: ~4 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('4px4NzHx7THGsd3sISvA6sEa5SHXWtbAyO7QNLvl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVnpXeDJrUkhETHZtdDJHRElYbHJ4NVh1cTJuZFpEY3F4dHdNTHNDMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGFzc2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoyMDoidmVyaWZpY2F0aW9uX3Jlc3VsdHMiO2E6Mjp7aTowO2E6NTp7czozOiJjaW4iO3M6ODoiQUIxMjM0NTYiO3M6NjoiZXJyb3JzIjthOjA6e31zOjEyOiJmaWxlX2RldGFpbHMiO2E6Mzp7aTowO2E6Mzp7czoxNDoiZXh0cmFjdGVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjtzOjQ6ImZpbGUiO3M6MTE6ImJhY19pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjE6e3M6MTQ6IkxlIGNhbmRpZGF0KGUpIjtzOjE5OiI6IExPVUFMSSBBQkRFU1NBTUFEIjt9fWk6MTthOjM6e3M6MTQ6ImV4dHJhY3RlZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7czo0OiJmaWxlIjtzOjEzOiJiaXJ0aF9pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjg6IjogTE9VQUxJIjtzOjc6IlByw6lub20iO3M6MTI6IjogQUJERVNTQU1BRCI7fX1pOjI7YTozOntzOjE0OiJleHRyYWN0ZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO3M6NDoiZmlsZSI7czoxNToiaWRfY2FyZF9pbWcuanBnIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjY6IkxPVUFMSSI7czo3OiJQcsOpbm9tIjtzOjEwOiJBQkRFU1NBTUFEIjt9fX1zOjEwOiJpc19jb3JyZWN0IjtiOjE7czoxMzoidmVyaWZpZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO31pOjE7YTo1OntzOjM6ImNpbiI7czo1OiJEQTEyMyI7czo2OiJlcnJv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', 1744724816),
	('fubUaaxnE8DGnqClBwZOtFuXx6DKUnpHT2mXZ4KF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVmN5YlN1S3FnR3BtWHhPWnJXT00yaFp1NGlOWTRZM2hPMk5QVXhFTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1744903533),
	('I1G7nIFVeaulq3ON57AV9GI6of6GAyxXkssLmgOT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGJDOFFwa0dVaTJXekxmNkc2enJZUnFHWXBDMU9WdGhGa01NazRDZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MjA6InZlcmlmaWNhdGlvbl9yZXN1bHRzIjthOjI6e2k6MDthOjU6e3M6MzoiY2luIjtzOjg6IkFCMTIzNDU2IjtzOjY6ImVycm9ycyI7YTowOnt9czoxMjoiZmlsZV9kZXRhaWxzIjthOjM6e2k6MDthOjM6e3M6MTQ6ImV4dHJhY3RlZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7czo0OiJmaWxlIjtzOjExOiJiYWNfaW1nLnBuZyI7czo4OiJyYXdfZGF0YSI7YToxOntzOjE0OiJMZSBjYW5kaWRhdChlKSI7czoxOToiOiBMT1VBTEkgQUJERVNTQU1BRCI7fX1pOjE7YTozOntzOjE0OiJleHRyYWN0ZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO3M6NDoiZmlsZSI7czoxMzoiYmlydGhfaW1nLnBuZyI7czo4OiJyYXdfZGF0YSI7YToyOntzOjM6Ik5vbSI7czo4OiI6IExPVUFMSSI7czo3OiJQcsOpbm9tIjtzOjEyOiI6IEFCREVTU0FNQUQiO319aToyO2E6Mzp7czoxNDoiZXh0cmFjdGVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjtzOjQ6ImZpbGUiO3M6MTU6ImlkX2NhcmRfaW1nLmpwZyI7czo4OiJyYXdfZGF0YSI7YToyOntzOjM6Ik5vbSI7czo2OiJMT1VBTEkiO3M6NzoiUHLDqW5vbSI7czoxMDoiQUJERVNTQU1BRCI7fX19czoxMDoiaXNfY29ycmVjdCI7YjoxO3M6MTM6InZlcmlmaWVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjt9aToxO2E6NTp7czozOiJjaW4iO3M6NToiREExMjMiO3M6NjoiZXJyb3JzIjthOjA6e31zOjEyOiJmaWxlX2RldGFpbHMiO2E6Mzp7aTowO2E6Mzp7czoxNDoiZXh0cmFjdGVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjtzOjQ6ImZpbGUiO3M6MTE6ImJhY19pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjE6e3M6MTQ6IkxlIGNhbmRpZGF0KGUpIjtzOjE5OiI6IExPVUFMSSBBQkRFU1NBTUFEIjt9fWk6MTthOjM6e3M6MTQ6ImV4dHJhY3RlZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7czo0OiJmaWxlIjtzOjEzOiJiaXJ0aF9pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjg6IjogTE9VQUxJIjtzOjc6IlByw6lub20iO3M6MTI6IjogQUJERVNTQU1BRCI7fX1pOjI7YTozOntzOjE0OiJleHRyYWN0ZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO3M6NDoiZmlsZSI7czoxNToiaWRfY2FyZF9pbWcuanBnIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjY6IkxPVUFMSSI7czo3OiJQcsOpbm9tIjtzOjEwOiJBQkRFU1NBTUFEIjt9fX1zOjEwOiJpc19jb3JyZWN0IjtiOjE7czoxMzoidmVyaWZpZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO319fQ==', 1743604510),
	('ydEOvGCLBlQYwwEGnxOn1pn6L3KH8hfDPuavQ7sX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '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ODoiQUIxMjM0NTYiO3M6NjoiZXJyb3JzIjthOjA6e31zOjEyOiJmaWxlX2RldGFpbHMiO2E6Mzp7aTowO2E6Mzp7czoxNDoiZXh0cmFjdGVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjtzOjQ6ImZpbGUiO3M6MTE6ImJhY19pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjE6e3M6MTQ6IkxlIGNhbmRpZGF0KGUpIjtzOjE5OiI6IExPVUFMSSBBQkRFU1NBTUFEIjt9fWk6MTthOjM6e3M6MTQ6ImV4dHJhY3RlZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7czo0OiJmaWxlIjtzOjEzOiJiaXJ0aF9pbWcucG5nIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjg6IjogTE9VQUxJIjtzOjc6IlByw6lub20iO3M6MTI6IjogQUJERVNTQU1BRCI7fX1pOjI7YTozOntzOjE0OiJleHRyYWN0ZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO3M6NDoiZmlsZSI7czoxNToiaWRfY2FyZF9pbWcuanBnIjtzOjg6InJhd19kYXRhIjthOjI6e3M6MzoiTm9tIjtzOjY6IkxPVUFMSSI7czo3OiJQcsOpbm9tIjtzOjEwOiJBQkRFU1NBTUFEIjt9fX1zOjEwOiJpc19jb3JyZWN0IjtiOjE7czoxMzoidmVyaWZpZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO31pOjE7YTo1OntzOjM6ImNpbiI7czo1OiJEQTEyMyI7czo2OiJlcnJvcnMiO2E6MDp7fXM6MTI6ImZpbGVfZGV0YWlscyI7YTozOntpOjA7YTozOntzOjE0OiJleHRyYWN0ZWRfbmFtZSI7czoxNzoiQUJERVNTQU1BRCBMT1VBTEkiO3M6NDoiZmlsZSI7czoxMToiYmFjX2ltZy5wbmciO3M6ODoicmF3X2RhdGEiO2E6MTp7czoxNDoiTGUgY2FuZGlkYXQoZSkiO3M6MTk6IjogTE9VQUxJIEFCREVTU0FNQUQiO319aToxO2E6Mzp7czoxNDoiZXh0cmFjdGVkX25hbWUiO3M6MTc6IkFCREVTU0FNQUQgTE9VQUxJIjtzOjQ6ImZpbGUiO3M6MTM6ImJpcnRoX2ltZy5wbmciO3M6ODoicmF3X2RhdGEiO2E6Mjp7czozOiJOb20iO3M6ODoiOiBMT1VBTEkiO3M6NzoiUHLDqW5vbSI7czoxMjoiOiBBQkRFU1NBTUFEIjt9fWk6MjthOjM6e3M6MTQ6ImV4dHJhY3RlZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7czo0OiJmaWxlIjtzOjE1OiJpZF9jYXJkX2ltZy5qcGciO3M6ODoicmF3X2RhdGEiO2E6Mjp7czozOiJOb20iO3M6NjoiTE9VQUxJIjtzOjc6IlByw6lub20iO3M6MTA6IkFCREVTU0FNQUQiO319fXM6MTA6ImlzX2NvcnJlY3QiO2I6MTtzOjEzOiJ2ZXJpZmllZF9uYW1lIjtzOjE3OiJBQkRFU1NBTUFEIExPVUFMSSI7fX19', 1744628011);

-- Dumping structure for table snthys.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cin` varchar(40) NOT NULL,
  `s_fname` varchar(100) NOT NULL,
  `s_lname` varchar(100) NOT NULL,
  `id_card_img` varchar(255) DEFAULT NULL,
  `bac_img` varchar(255) DEFAULT NULL,
  `birth_img` varchar(255) DEFAULT NULL,
  `code_class` varchar(50) NOT NULL,
  `filier_name` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_cin_unique` (`cin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.students: ~3 rows (approximately)
DELETE FROM `students`;
INSERT INTO `students` (`id`, `cin`, `s_fname`, `s_lname`, `id_card_img`, `bac_img`, `birth_img`, `code_class`, `filier_name`, `created_at`, `updated_at`) VALUES
	(1, 'AB123456', 'Ahmed', 'Bouzid', 'students/AB123456/id_card_img.jpg', 'students/AB123456/bac_img.png', 'students/AB123456/birth_img.png', 'CS101', 'Computer Science', '2025-04-01 16:21:43', '2025-04-01 16:21:43'),
	(2, 'DA123', 'Salim', 'Amlah', 'students/DA123/id_card_img.jpg', 'students/DA123/bac_img.png', 'students/DA123/birth_img.png', 'CS101', 'Computer Science', '2025-04-01 18:17:42', '2025-04-01 18:17:42'),
	(3, 'GH567890', 'Youssef', 'Chafiq', 'students/GH567890/id_card_img.jpg', 'students/GH567890/bac_img.png', 'students/GH567890/birth_img.png', 'ECO505', 'Economics', '2025-04-01 18:59:05', '2025-04-01 18:59:05');

-- Dumping structure for table snthys.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table snthys.users: ~0 rows (approximately)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
