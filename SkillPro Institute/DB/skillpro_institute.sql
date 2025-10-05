-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 08, 2025 at 08:39 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillpro_institute`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `mode` enum('Online','On-site') NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `category`, `mode`, `duration`, `fee`, `instructor_id`, `start_date`) VALUES
(1, 'Web Development', 'ICT', 'Online', '3 Months', '25000.00', 1, '2025-09-10'),
(2, 'Hotel Management', 'Hospitality', 'On-site', '4 Months', '35000.00', 2, '2025-09-20'),
(3, 'Mechanical Welding', 'Engineering', 'On-site', '2 Months', '20000.00', 3, '2025-09-15');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_type` enum('Workshop','Seminar','Exam','Job Fair','Holiday') NOT NULL,
  `event_date` date NOT NULL,
  `description` text,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_type`, `event_date`, `description`) VALUES
(1, 'Resume Building Workshop', 'Workshop', '2025-09-25', 'Learn how to create effective resumes and prepare for interviews.'),
(2, 'ICT Module 1 Exam', 'Exam', '2025-10-15', 'Assessment for ICT Module 1.'),
(3, 'Annual Job Fair', 'Job Fair', '2025-11-10', 'Connect with top employers and industry leaders.'),
(4, 'Public Holiday', 'Holiday', '2025-10-12', 'Institute closed for public holiday.');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE IF NOT EXISTS `inquiries` (
  `inquiry_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `inquiry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`inquiry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
CREATE TABLE IF NOT EXISTS `instructors` (
  `instructor_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  PRIMARY KEY (`instructor_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `full_name`, `email`, `phone`, `specialization`, `hire_date`) VALUES
(1, 'Mr. Amal Perera', 'amal@skillpro.lk', '0771234567', 'ICT', '2023-01-15'),
(2, 'Ms. Nisha Fernando', 'nisha@skillpro.lk', '0772345678', 'Hospitality', '2023-03-10'),
(3, 'Mr. Sandun Jayawardena', 'sandun@skillpro.lk', '0773456789', 'Engineering', '2023-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`promotion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `title`, `description`, `image`, `status`, `start_date`, `end_date`) VALUES
(1, 'Early Bird Discount', 'Register early and get 10% off your course fee', 'earlybird.jpg', 'Active', '2025-08-01', '2025-09-10'),
(2, 'Free Workshop', 'Join any course and get a free workshop', 'freeworkshop.jpg', 'Active', '2025-08-15', '2025-10-01');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE IF NOT EXISTS `registrations` (
  `registration_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`registration_id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `student_id`, `course_id`, `registration_date`) VALUES
(1, 1, 1, '2025-09-08 07:07:46'),
(2, 2, 2, '2025-09-08 07:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `email`, `password`, `phone`, `registration_date`) VALUES
(1, 'Kamal Perera', 'kamal@student.lk', '1234', '0712345678', '2025-09-08 07:07:46'),
(2, 'Nadeesha Silva', 'nadeesha@student.lk', '1234', '0723456789', '2025-09-08 07:07:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
