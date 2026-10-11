-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 06:18 PM
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
-- Database: `daycare_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `checked_in` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `daycare_id`, `child_id`, `checked_in`) VALUES
(2, 9, 4, '2024-09-04 13:45:00'),
(3, 9, 6, '2025-04-18 05:13:00'),
(4, 9, 5, '2025-04-27 06:29:00'),
(5, 9, 7, '2025-04-27 16:42:00'),
(6, 9, 7, '2025-04-24 20:09:00'),
(7, 9, 6, '2025-04-18 07:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `child_activities`
--

CREATE TABLE `child_activities` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `activity_date` date NOT NULL,
  `playtime` varchar(255) DEFAULT NULL,
  `learning_session` varchar(255) DEFAULT NULL,
  `meal` varchar(255) DEFAULT NULL,
  `nap` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `child_activities`
--

INSERT INTO `child_activities` (`id`, `daycare_id`, `child_id`, `activity_date`, `playtime`, `learning_session`, `meal`, `nap`, `image`, `created_at`) VALUES
(2, 9, 7, '2025-04-27', '38 mint', '45 mint', 'fruits', '2 hours', '680e2c36c2968_pc.png', '2025-04-27 13:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `daycare_children`
--

CREATE TABLE `daycare_children` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daycare_children`
--

INSERT INTO `daycare_children` (`id`, `daycare_id`, `name`, `age`, `created_at`) VALUES
(5, 9, 'ali', 7, '2025-04-27 07:02:36'),
(6, 8, 'hamza', 2, '2025-04-27 07:07:43'),
(7, 8, 'khan', 10, '2025-04-27 08:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `month_year` varchar(20) NOT NULL,
  `present_days` int(11) NOT NULL,
  `absent_days` int(11) NOT NULL,
  `fee_per_day` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(500) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `daycare_id`, `child_id`, `month_year`, `present_days`, `absent_days`, `fee_per_day`, `total_amount`, `status`, `generated_at`) VALUES
(24, 9, 6, '2025-04', 2, 28, 100.00, 200.00, 'Paid', '2025-04-27 12:20:34'),
(25, 9, 7, '2025-04', 2, 28, 100.00, 200.00, 'Paid', '2025-04-27 12:20:34'),
(26, 9, 5, '2025-05', 0, 31, 100.00, 0.00, 'Pending', '2025-04-27 12:54:18'),
(27, 9, 6, '2025-05', 0, 31, 100.00, 0.00, 'Pending', '2025-04-27 12:54:18'),
(28, 9, 7, '2025-05', 0, 31, 100.00, 0.00, 'Paid', '2025-04-27 12:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `meals_schedule`
--

CREATE TABLE `meals_schedule` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `meal_date` date NOT NULL,
  `meal_time` enum('Breakfast','Lunch','Snack','Dinner') NOT NULL,
  `meal_description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meals_schedule`
--

INSERT INTO `meals_schedule` (`id`, `daycare_id`, `meal_date`, `meal_time`, `meal_description`, `created_at`) VALUES
(1, 9, '2025-04-27', 'Lunch', 'meat vegetables', '2025-04-27 10:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`) VALUES
(26, 9, 8, 'hello', '2025-04-27 15:49:23'),
(27, 9, 8, 'hello', '2025-04-27 15:56:31'),
(28, 9, 8, 'h', '2025-04-27 15:56:37'),
(29, 9, 8, ' : suno', '2025-04-27 16:04:25'),
(30, 9, 8, 'Aahil Mughal : kha h', '2025-04-27 16:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `notification_type` enum('Event','Holiday','Emergency') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `daycare_id`, `title`, `message`, `notification_type`, `created_at`) VALUES
(1, 9, 'holiday', 'a usual holiday', 'Holiday', '2025-04-27 11:01:28');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(50) DEFAULT NULL,
  `support` varchar(100) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `time_duration` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `user_id`, `emergency_contact_name`, `emergency_contact_phone`, `support`, `area`, `time_duration`) VALUES
(9, 8, 'Rehman', '0313543212', 'Part', 'Gulberg', '09am to 05pm');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `daycare_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `parent_id`, `daycare_id`, `payment_amount`, `payment_date`) VALUES
(10, 8, 9, 500.00, '2024-09-09'),
(11, 8, 9, 400.00, '2025-04-27'),
(12, 8, 9, 99999999.99, '2025-04-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `role`) VALUES
(8, 'Aahil Mughal', 'p@gmail.com', '$2y$10$OFwybCUBcJwbvbzlV61XKO3kZ9KwKbrTF3vA47Y.BLMO36bNWR8Uu', 'Lahore', '03124567645', 'parent'),
(9, 'Nabeel Mirza', 'd@gmail.com', '$2y$10$iC.DkRgYXZHSLA99zKQqFOySiCcJ08R8.5OFO/k6E4fPsnhmYjG4i', 'Lahore', '0313567654', 'daycare'),
(13, 'Ali', 'g44@gmail.com', '$2y$10$zXj9K2r8xeUmUR1t05FKl.gWplx6ZeFH2g/RE2PT8dodqOV0YjXK6', 'Khanewal', '32634645', 'manager'),
(17, 'tayyab', 'h3@gmail.com', '$2y$10$LoR3Y7fY7EhbiG/YxBpO5OcgpshDVREsSHYTtKmIJWg.wLn3L/p2e', 'Gujrat', '423524356436', 'parent');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `child_activities`
--
ALTER TABLE `child_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daycare_id` (`daycare_id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `daycare_children`
--
ALTER TABLE `daycare_children`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meals_schedule`
--
ALTER TABLE `meals_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `child_activities`
--
ALTER TABLE `child_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `daycare_children`
--
ALTER TABLE `daycare_children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `meals_schedule`
--
ALTER TABLE `meals_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child_activities`
--
ALTER TABLE `child_activities`
  ADD CONSTRAINT `child_activities_ibfk_1` FOREIGN KEY (`daycare_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `child_activities_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `daycare_children` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
