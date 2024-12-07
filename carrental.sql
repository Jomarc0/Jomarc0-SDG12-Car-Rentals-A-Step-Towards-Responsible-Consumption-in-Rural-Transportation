-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 06:52 AM
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
-- Database: `carrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'Admin123', '$2y$10$zYNLcKOZjy4G1.KQmust8uLU5NQZKJhX3NsrzJJwltMoCLgUTvK.S');

-- --------------------------------------------------------

--
-- Table structure for table `identityverification`
--

CREATE TABLE `identityverification` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `country` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `id_number` int(11) NOT NULL,
  `valid_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `verify_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `identityverification`
--

INSERT INTO `identityverification` (`id`, `first_name`, `last_name`, `dob`, `country`, `address`, `id_number`, `valid_id`, `user_id`, `verify_status`) VALUES
(6, 'Ray Jomar', 'Catapang', '2004-10-25', 'Philippines', 'Banay-Banay, Lipa City', 0, '../uploads/drivers_license/462584239_2916693118495766_7540549461721915381_n.jpg', 8, 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` enum('client','admin') NOT NULL,
  `message` text NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `message`, `admin_id`, `user_id`, `message_at`) VALUES
(6, 'client', 'hello', NULL, 8, '2024-11-25 14:32:38'),
(7, 'admin', 'yo\r\n', 1, 8, '2024-11-25 14:41:56'),
(8, 'client', 'asda', NULL, 8, '2024-12-07 05:21:23');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `rent_id` int(11) NOT NULL,
  `payment_link` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `amount`, `payment_status`, `rent_id`, `payment_link`, `user_id`, `payment_date`) VALUES
(13, 3000, 'approved', 21, 'https://pm.link/org-cCJyAo3FvCusewoG29Z9JwDi/test/QJ6aVEr', 8, '2024-11-29 13:27:38'),
(14, 1500, 'approved', 25, 'https://pm.link/org-cCJyAo3FvCusewoG29Z9JwDi/test/ZaQz7ns', 8, '2024-12-07 05:20:29'),
(15, 1500, 'pending', 27, 'https://pm.link/org-cCJyAo3FvCusewoG29Z9JwDi/test/4zyD6bV', 8, '2024-12-07 05:19:26'),
(16, 8000, 'pending', 28, 'https://pm.link/org-cCJyAo3FvCusewoG29Z9JwDi/test/8awDJo7', 8, '2024-12-07 05:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `recentlydeleted`
--

CREATE TABLE `recentlydeleted` (
  `rent_id` int(11) NOT NULL,
  `booking_area` varchar(50) NOT NULL,
  `destination` varchar(150) NOT NULL,
  `trip_date_time` datetime NOT NULL,
  `return_date_time` datetime NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `rent_status` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recentlydeleted`
--

INSERT INTO `recentlydeleted` (`rent_id`, `booking_area`, `destination`, `trip_date_time`, `return_date_time`, `vehicle_type`, `rent_status`, `user_id`, `deleted_at`) VALUES
(1, 'Cebu', 'Cebu City', '2024-11-28 14:16:00', '2024-11-29 14:16:00', 'Fullsize SUV', '', 8, '2024-12-06 07:04:27'),
(2, 'Metro Manila', 'Taguig', '2024-11-29 13:54:00', '2024-12-01 13:54:00', 'SUV', 'completed', 8, '2024-12-06 07:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `rentedcar`
--

CREATE TABLE `rentedcar` (
  `rent_id` int(11) NOT NULL,
  `booking_area` varchar(50) NOT NULL,
  `destination` varchar(150) NOT NULL,
  `trip_date_time` datetime NOT NULL,
  `return_date_time` datetime NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `rent_status` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentedcar`
--

INSERT INTO `rentedcar` (`rent_id`, `booking_area`, `destination`, `trip_date_time`, `return_date_time`, `vehicle_type`, `rent_status`, `user_id`) VALUES
(12, 'Metro Manila', 'Lapu-Lapu City', '2024-11-18 21:37:00', '2024-11-20 21:37:00', 'Sedan', 'pending', 8),
(19, 'Metro Manila', 'Taguig', '2024-11-28 13:03:00', '2024-11-30 13:03:00', 'Sedan', 'completed', 8),
(21, 'Metro Manila', 'Quezon City', '2024-11-28 13:57:00', '2024-11-30 13:57:00', 'Sedan', 'completed', 8),
(22, 'Metro Manila', 'Taguig', '2024-11-28 14:04:00', '2024-11-30 14:04:00', 'Sedan', 'cancel', 8),
(25, 'Cavite', 'Kaybiang Tunnel', '2024-12-13 00:30:00', '2024-12-14 00:30:00', 'Sedan', 'rented', 8),
(26, 'Batangas', 'Laiya Beach', '2024-12-08 13:16:00', '2024-12-11 13:16:00', 'Sedan', NULL, 8),
(27, 'Cavite', 'Tagaytay City', '2024-12-09 13:18:00', '2024-12-10 13:18:00', 'Sedan', 'pending', 8),
(28, 'Laguna', 'Los Baños Hot Springs', '2024-12-12 13:45:00', '2024-12-16 13:46:00', 'Midsize SUV', 'pending', 8);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `address`, `gender`, `dob`, `email`, `phone_number`, `password`, `verification_code`, `verified`, `profile_picture`, `created_at`) VALUES
(8, 'Ray Jomar', 'Catapang', 'Banay-Banay, Lipa City', 'Male', '2004-10-25', 'rayjomar15@gmail.com', '09959141394', '$2y$10$PdwI59uXggS0sxj.yUmYyOyKt1W6Rr9SXAzn0q1p0OIPnngAaWWmi', 814699, 1, '../uploads/profile_pictures/8.jpg', '2024-12-07 05:15:02'),
(21, 'Ray Jomar', 'Catapang', '', '', '0000-00-00', 'rayjomarcatapang06@gmail.com', '', '', 795263, 1, NULL, '2024-12-06 07:14:15'),
(24, 'Ray Jomar', 'Catapang', '', '', '0000-00-00', 'rjcatapang12@gmail.com', '', '', 444820, 1, NULL, '2024-12-07 05:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_rate`
--

CREATE TABLE `vehicle_rate` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `daily_rate` int(11) NOT NULL,
  `hourly_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_rate`
--

INSERT INTO `vehicle_rate` (`vehicle_id`, `vehicle_type`, `daily_rate`, `hourly_rate`) VALUES
(1, 'Sedan', 1500, 63),
(2, 'Fullsize SUV', 2500, 104),
(3, 'Midsize SUV', 2000, 83),
(4, 'Pick Up', 3500, 146),
(5, 'Subcompact Sedan', 1500, 63),
(6, 'Van', 4000, 167),
(7, 'Sports Car', 5500, 229),
(8, 'Tesla', 6500, 270);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `identityverification`
--
ALTER TABLE `identityverification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userverify` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin` (`admin_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_userid` (`rent_id`),
  ADD KEY `payment_userid` (`user_id`);

--
-- Indexes for table `recentlydeleted`
--
ALTER TABLE `recentlydeleted`
  ADD PRIMARY KEY (`rent_id`);

--
-- Indexes for table `rentedcar`
--
ALTER TABLE `rentedcar`
  ADD PRIMARY KEY (`rent_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vehicle_rate`
--
ALTER TABLE `vehicle_rate`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `identityverification`
--
ALTER TABLE `identityverification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `recentlydeleted`
--
ALTER TABLE `recentlydeleted`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rentedcar`
--
ALTER TABLE `rentedcar`
  MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `vehicle_rate`
--
ALTER TABLE `vehicle_rate`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `identityverification`
--
ALTER TABLE `identityverification`
  ADD CONSTRAINT `userverify` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_rent` FOREIGN KEY (`rent_id`) REFERENCES `rentedcar` (`rent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_userid` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rentedcar`
--
ALTER TABLE `rentedcar`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
