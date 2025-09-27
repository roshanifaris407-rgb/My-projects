-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 24, 2025 at 04:11 PM
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
-- Database: `greenlife_wellness`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int UNSIGNED NOT NULL,
  `therapist_id` int UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `member_id`, `therapist_id`, `appointment_date`, `appointment_time`, `service_type`, `status`, `created_at`, `price`) VALUES
(5, 1, 1, '2025-08-30', '12:30:00', 'Massage Therapy', 'Scheduled', '2025-08-24 07:24:21', 2500);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `category`, `title`, `content`, `created_at`, `image`) VALUES
(1, 'Health Recipes', 'GreenLife Wellness Power Smoothie Bowl 🥬🍌🍓', 'Ingredients (Serves 1-2):\r\n\r\n1 cup almond milk\r\n1 frozen banana\r\n1/2 cup frozen berries\r\nHandful of spinach/kale\r\n1 tbsp chia seeds\r\n1 tbsp almond butter\r\n1 tsp honey/maple syrup (optional)\r\n\r\nToppings: Fresh berries, kiwi/mango, granola, seeds\r\n\r\nInstructions:\r\n\r\nBlend all base ingredients until smooth.\r\nPour into a bowl and top with fruits, granola, and seeds.\r\nServe immediately.\r\n\r\nBenefits: Boosts immunity, digestion, heart & brain health, and energy.', '2025-08-24 01:28:46', '../Uploads/Blogs/1756028164_smoothy.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customer_memberships`
--

DROP TABLE IF EXISTS `customer_memberships`;
CREATE TABLE IF NOT EXISTS `customer_memberships` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `status` enum('Pending','Active','Expired') DEFAULT 'Pending',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_memberships`
--

INSERT INTO `customer_memberships` (`id`, `member_id`, `plan_id`, `status`, `start_date`, `end_date`, `created_at`) VALUES
(1, 1, 1, 'Pending', '2025-08-23', '2026-08-23', '2025-08-24 04:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `blog_id` int DEFAULT NULL,
  `message` text NOT NULL,
  `response` text,
  `status` enum('Pending','Responded') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `client_name`, `client_email`, `blog_id`, `message`, `response`, `status`, `created_at`) VALUES
(1, 'Mohamed Feroos', 'roshanifaris407@gmail.com', 1, 'AEGSFHNFDCMVFM', 'dhffh', 'Pending', '2025-08-24 15:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

DROP TABLE IF EXISTS `membership_plans`;
CREATE TABLE IF NOT EXISTS `membership_plans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `membership_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `service` varchar(100) NOT NULL,
  `services` text,
  `benefits` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Pending','Expired') DEFAULT 'Active',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `membership_type`, `price`, `service`, `services`, `benefits`, `created_at`, `status`, `start_date`, `end_date`) VALUES
(1, 'Basic', 19000.00, '', 'Massage Therapy', NULL, '2025-08-23 08:07:41', 'Active', '2025-08-28', '2025-09-28');

-- --------------------------------------------------------

--
-- Table structure for table `newmember`
--

DROP TABLE IF EXISTS `newmember`;
CREATE TABLE IF NOT EXISTS `newmember` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `full_name` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `membership_type` varchar(50) DEFAULT NULL,
  `choose_program` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `joined_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `newmember`
--

INSERT INTO `newmember` (`id`, `username`, `email`, `address`, `password`, `created_at`, `full_name`, `age`, `gender`, `contact_number`, `membership_type`, `choose_program`, `profile_photo`, `joined_date`) VALUES
(1, 'mohamedferoos522', 'roshanifaris407@gmail.com', NULL, '$2y$10$2S7833xM.k1hi15vgWIn2u4U2tY4.zD/Gu0Jjx1MpYoVUSYrHI0iS', '2025-08-24 04:50:40', 'Mohamed Feroos', 22, 'Male', '0763959774', 'Basic', 'Massage Therapy', 'uploads/1756011040_icon.png', '2025-08-24 04:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `valid_until` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `description`, `discount`, `valid_until`, `created_at`, `status`) VALUES
(1, 'Summer Sale', 'Get 20% off on all wellness packages.', 20.00, '2025-09-30', '2025-08-22 17:05:28', 'Active'),
(2, 'New Year Offer', 'Start the year healthy! 15% discount on all memberships.', 15.00, '2025-12-31', '2025-08-22 17:05:28', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `price`, `status`, `created_at`) VALUES
(1, 'Spa Services', 'spa services', 5000.00, 'active', '2025-08-21 16:12:58'),
(2, 'Spa Services', 'spa services', 5000.00, 'active', '2025-08-21 17:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

DROP TABLE IF EXISTS `therapist`;
CREATE TABLE IF NOT EXISTS `therapist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `therapist`
--

INSERT INTO `therapist` (`id`, `username`, `email`, `specialization`, `password`, `name`, `phone`, `specialty`, `status`) VALUES
(1, 'admin', 'Kavindi@example.com', '', '$2y$10$g/THItvQ/hXFG07Q2UcTke8DZBAmSjAlHwDsGoZfbDoFg2lQnWplW', 'Ms. Kavindi Senanayake', '0771234567', 'Massage Therapy', 'Active'),
(2, 'therapist2', 'Sandun@example.com', '', '$2y$10$abc123...', 'Mr. Sandun Jayawardena', '0771234567', 'Yoga Classes', 'Active'),
(3, 'therapist3', 'Nirmala@example.com', '', '$2y$10$abc123...', 'Dr. Nirmala Fernando', '0772345678', 'Physiotherapy', 'Active'),
(4, 'therapist4', 'Anjali@example.com', 'Ayurveda Therapy', '$2y$10$abc123...', 'Dr. Anjali Perera', '0773456789', 'Ayurveda Therapy', 'Active'),
(5, 'therapist5', 'Tharindu@example.com', '', '$2y$10$abc123...', 'Mr. Tharindu Silva', '0774567890', 'Nutrition Guidance', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

DROP TABLE IF EXISTS `therapists`;
CREATE TABLE IF NOT EXISTS `therapists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `therapists`
--

INSERT INTO `therapists` (`id`, `username`, `email`, `password`, `name`, `phone`, `specialty`, `status`) VALUES
(2, 'therapist1', 'Kavindi@example.com', '$2y$10$abc123...', 'Ms. Kavindi Senanayake', '0771234567', 'Massage Therapy', 'Active'),
(3, 'therapist2', 'Tharindu@example.com', '$2y$10$abc1234...', 'Mr. Tharindu Silva', '0763959774', 'Nutrition Guidance', 'Active'),
(4, 'therapist3', 'Anjali@example.com', '$2y$10$abc12345...', 'Dr. Anjali Perera', '0773039810', 'Ayurveda Therapy', 'Active'),
(5, 'therapist4', ' Sandun@example.com', '$2y$10$abc123456...', 'Mr. Sandun Jayawardena', '0777123456', 'Yoga Classes', 'Active'),
(6, 'therapist5', 'Nirmala@example.com', '$2y$10$abc1234567...', 'Dr. Nirmala Fernando', '0765765283', 'Physiotherapy', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `therapists_services`
--

DROP TABLE IF EXISTS `therapists_services`;
CREATE TABLE IF NOT EXISTS `therapists_services` (
  `therapist_id` int NOT NULL,
  `service_id` int NOT NULL,
  PRIMARY KEY (`therapist_id`,`service_id`),
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','therapist','admin') DEFAULT 'client',
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `date_of_birth` date DEFAULT NULL,
  `health_conditions` text,
  `specialization` varchar(100) DEFAULT NULL,
  `bio` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_memberships`
--
ALTER TABLE `customer_memberships`
  ADD CONSTRAINT `customer_memberships_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `newmember` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_memberships_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `membership_plans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
