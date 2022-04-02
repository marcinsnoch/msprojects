-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               10.5.15-MariaDB-0ubuntu0.21.10.1 - Ubuntu 21.10
-- Serwer OS:                    debian-linux-gnu
-- HeidiSQL Wersja:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Zrzut struktury bazy danych msprojects
DROP DATABASE IF EXISTS `msprojects`;
CREATE DATABASE IF NOT EXISTS `msprojects` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `msprojects`;

-- Zrzut struktury tabela msprojects.addresses
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organization` varchar(150) NOT NULL,
  `address` varchar(150) CHARACTER SET utf8 NOT NULL,
  `postcode` varchar(45) CHARACTER SET utf8 NOT NULL,
  `city` varchar(45) CHARACTER SET utf8 NOT NULL,
  `country` varchar(45) CHARACTER SET utf8 NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.addresses: ~0 rows (około)
DELETE FROM `addresses`;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.authorizations
DROP TABLE IF EXISTS `authorizations`;
CREATE TABLE IF NOT EXISTS `authorizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(150) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `ceated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.authorizations: ~0 rows (około)
DELETE FROM `authorizations`;
/*!40000 ALTER TABLE `authorizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `authorizations` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.ci_sessions
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.ci_sessions: ~0 rows (około)
DELETE FROM `ci_sessions`;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
	('52s604qfn1fhqj9sjb8mri1cknnu46vj', '127.0.0.1', 1648912654, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313634383931323633313b757365725f69647c693a313b66756c6c5f6e616d657c733a31333a2241646d696e6973747261746f72223b6c6f676765645f696e7c623a313b);
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.comments
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_comments_projects` (`project_id`),
  KEY `FK_comments_users` (`user_id`),
  CONSTRAINT `FK_comments_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_comments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.comments: ~0 rows (około)
DELETE FROM `comments`;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.customers
DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `email` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `krs` varchar(50) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `regon` varchar(50) DEFAULT NULL,
  `address_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_customers_addresses` (`address_id`),
  CONSTRAINT `FK_customers_addresses` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.customers: ~0 rows (około)
DELETE FROM `customers`;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.payments
DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` float(6,2) unsigned NOT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_payments_users` (`user_id`),
  KEY `fk_payments_customers1_idx` (`customer_id`) USING BTREE,
  CONSTRAINT `FK_payments_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_payments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.payments: ~0 rows (około)
DELETE FROM `payments`;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.payment_project
DROP TABLE IF EXISTS `payment_project`;
CREATE TABLE IF NOT EXISTS `payment_project` (
  `payment_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.payment_project: ~0 rows (około)
DELETE FROM `payment_project`;
/*!40000 ALTER TABLE `payment_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_project` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.projects
DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8 NOT NULL,
  `commissioned_by` varchar(150) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `details` text CHARACTER SET utf8 DEFAULT NULL,
  `price` float(6,2) unsigned NOT NULL DEFAULT 0.00,
  `status` enum('Nowe','Realizowane','Zakończone','Nie rozliczone','Anulowane') CHARACTER SET utf8 NOT NULL DEFAULT 'Nowe',
  `customer_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_projects_users` (`user_id`),
  KEY `FK_projects_customers` (`customer_id`),
  CONSTRAINT `FK_projects_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_projects_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.projects: ~0 rows (około)
DELETE FROM `projects`;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.project_summary
DROP TABLE IF EXISTS `project_summary`;
CREATE TABLE IF NOT EXISTS `project_summary` (
  `project_id` int(10) unsigned NOT NULL,
  `summary_id` int(10) unsigned NOT NULL,
  KEY `FK_project_summary_projects` (`project_id`),
  KEY `FK_project_summary_summaries` (`summary_id`),
  CONSTRAINT `FK_project_summary_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_project_summary_summaries` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.project_summary: ~0 rows (około)
DELETE FROM `project_summary`;
/*!40000 ALTER TABLE `project_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_summary` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.summaries
DROP TABLE IF EXISTS `summaries`;
CREATE TABLE IF NOT EXISTS `summaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `views` int(10) unsigned DEFAULT 0,
  `token` varchar(250) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_summaries_users` (`user_id`) USING BTREE,
  KEY `fk_summaries_customers_id` (`customer_id`) USING BTREE,
  CONSTRAINT `FK_summaries_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_summaries_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.summaries: ~0 rows (około)
DELETE FROM `summaries`;
/*!40000 ALTER TABLE `summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `summaries` ENABLE KEYS */;

-- Zrzut struktury tabela msprojects.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` enum('tmp','owner','client') NOT NULL DEFAULT 'tmp',
  `token` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Zrzucanie danych dla tabeli msprojects.users: ~0 rows (około)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `full_name`, `password`, `role`, `token`, `created_at`, `updated_at`) VALUES
	(1, 'admin@admin.pl', 'Administrator', '$2y$10$3q2JX232uQTHx/N2DUarIO1p6RnYnJiLQxGgOhBz8JN3yNGNHk.xa', 'owner', NULL, '2022-04-02 17:16:48', '2022-04-02 17:16:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
