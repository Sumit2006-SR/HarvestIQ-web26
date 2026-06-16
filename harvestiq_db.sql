-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 02:50 PM
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
-- Database: `harvestiq_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `crop_knowledge`
--

CREATE TABLE `crop_knowledge` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `soil_type` enum('Loamy','Clay','Sandy') NOT NULL,
  `season` enum('Rabi','Kharif') NOT NULL,
  `water_req` enum('Low','Medium','High') DEFAULT 'Medium',
  `duration` varchar(50) DEFAULT NULL,
  `yield_per_acre` int(11) NOT NULL,
  `cost_per_acre` decimal(10,2) DEFAULT 5000.00,
  `price_per_kg` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crop_knowledge`
--

INSERT INTO `crop_knowledge` (`id`, `name`, `icon`, `soil_type`, `season`, `water_req`, `duration`, `yield_per_acre`, `cost_per_acre`, `price_per_kg`, `reason`) VALUES
(1, 'Wheat', '🌾', 'Loamy', 'Rabi', 'Medium', '120 days', 1500, 5000.00, 30.00, 'Loamy soil retains moisture perfectly for Wheat during the dry winter (Rabi) season.'),
(2, 'Potato', '🥔', 'Loamy', 'Rabi', 'Medium', '90 days', 8000, 5000.00, 15.00, 'Potatoes thrive in loose, loamy soil during cooler climates, offering high yield and fast cash returns.'),
(3, 'Rice (Paddy)', '🍚', 'Clay', 'Kharif', 'Medium', '150 days', 2000, 5000.00, 25.00, 'Clay soil holds water heavily, which is exactly what Rice needs during the rainy Kharif season.'),
(4, 'Maize (Corn)', '🌽', 'Sandy', 'Kharif', 'Medium', '110 days', 1800, 5000.00, 22.00, 'Maize requires well-drained sandy soil and warm weather, making it highly resilient and profitable.'),
(5, 'Tomato', '🍅', 'Loamy', 'Rabi', 'Medium', '90 Days', 8000, 15000.00, 25.00, 'Excellent winter cash crop with high market demand and fast returns in loamy soil.'),
(6, 'Onion', '🧅', 'Sandy', 'Rabi', 'Low', '120 Days', 6000, 20000.00, 40.00, 'Bulb crops thrive in well-drained sandy soil during dry winters, preventing rot.'),
(7, 'Garlic', '🧄', 'Loamy', 'Rabi', 'Low', '130 Days', 4000, 25000.00, 80.00, 'High-value spice crop suitable for loamy winter soils with low moisture needs.'),
(8, 'Mustard', '🌼', 'Clay', 'Rabi', 'Low', '100 Days', 800, 8000.00, 60.00, 'Requires very little irrigation and grows exceptionally well in residual moisture of clay soil.'),
(9, 'Sugarcane', '🎋', 'Loamy', 'Kharif', 'High', '365 Days', 30000, 40000.00, 5.00, 'Long-duration cash crop needing heavy water and rich loamy soil for maximum sugar content.'),
(10, 'Jute', '🌿', 'Clay', 'Kharif', 'High', '120 Days', 1200, 12000.00, 50.00, 'Thrives in flooded clay soils during the monsoon season, highly profitable.'),
(11, 'Cotton', '☁️', 'Sandy', 'Kharif', 'Medium', '150 Days', 1000, 18000.00, 70.00, 'Deep-rooted crop that needs well-drained sandy soil and sunny weather to prevent boll rot.'),
(12, 'Peanut', '🥜', 'Sandy', 'Kharif', 'Low', '110 Days', 1200, 15000.00, 75.00, 'Sandy soil allows easy pod formation underground and makes harvesting much easier.'),
(13, 'Soybean', '🌱', 'Loamy', 'Kharif', 'Medium', '100 Days', 1000, 14000.00, 55.00, 'Excellent nitrogen-fixing legume for the monsoon that improves soil health.'),
(14, 'Chili', '🌶️', 'Loamy', 'Kharif', 'Medium', '150 Days', 3000, 20000.00, 60.00, 'High-profit spice that grows well in well-drained loamy soil, susceptible to waterlogging.'),
(15, 'Cabbage', '🥬', 'Clay', 'Rabi', 'Medium', '80 Days', 10000, 15000.00, 20.00, 'Cool-weather leafy vegetable that retains moisture well in heavy clay soils.'),
(16, 'Cauliflower', '🥦', 'Loamy', 'Rabi', 'Medium', '85 Days', 8000, 16000.00, 30.00, 'Needs rich, well-drained loamy soil and cool temperatures to form tight, white heads.'),
(17, 'Carrot', '🥕', 'Sandy', 'Rabi', 'Medium', '90 Days', 7000, 12000.00, 25.00, 'Sandy soil ensures straight, unimpeded root growth without deformation.'),
(18, 'Radish', '🍠', 'Sandy', 'Rabi', 'Medium', '45 Days', 6000, 8000.00, 15.00, 'Very fast-growing catch crop for sandy winter soils, quick turnaround for cash.'),
(19, 'Eggplant', '🍆', 'Loamy', 'Kharif', 'Medium', '140 Days', 8000, 18000.00, 30.00, 'Hardy vegetable that yields continuously in warm, moist conditions.'),
(20, 'Pumpkin', '🎃', 'Loamy', 'Kharif', 'Medium', '100 Days', 12000, 10000.00, 15.00, 'Sprawling vine crop that utilizes monsoon rains effectively and requires minimal care.'),
(21, 'Watermelon', '🍉', 'Sandy', 'Rabi', 'High', '90 Days', 15000, 25000.00, 15.00, 'Needs sandy soil for drainage but high irrigation for maximum fruit sizing and sweetness.'),
(22, 'Spinach', '🌿', 'Clay', 'Rabi', 'High', '40 Days', 3000, 6000.00, 35.00, 'Fast-growing leafy green that benefits immensely from high moisture retention in clay.'),
(23, 'Chickpea', '🧆', 'Loamy', 'Rabi', 'Low', '110 Days', 700, 9000.00, 70.00, 'Drought-tolerant pulse crop ideal for dry winter farming with minimal inputs.');

-- --------------------------------------------------------

--
-- Table structure for table `market_prices`
--

CREATE TABLE `market_prices` (
  `id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `mandi_name` varchar(100) NOT NULL,
  `trend` enum('up','down','stable') NOT NULL DEFAULT 'stable',
  `price_change` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_prices`
--

INSERT INTO `market_prices` (`id`, `crop_name`, `icon`, `price`, `unit`, `mandi_name`, `trend`, `price_change`, `updated_at`) VALUES
(1, 'Rice (Paddy)', '🌾', 2200.00, 'Quintal', 'Burdwan Wholesale', 'up', '+₹50', '2026-06-16 07:47:42'),
(2, 'Potato', '🥔', 1200.00, 'Quintal', 'Hooghly Mandi', 'down', '-₹30', '2026-06-16 07:47:42'),
(4, 'Pineapple', '🍍', 900.00, '5kg', 'contai', 'up', '12%', '2026-06-16 08:15:34'),
(5, 'Pineapple', '🍍', 900.00, '5kg', 'contaiug', 'up', '12%', '2026-06-16 09:38:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','admin') DEFAULT 'farmer',
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `is_verified`, `created_at`) VALUES
(1, 'Sumit Rudra', 'sumitrudra02@gmail.com', '$2y$10$zA9sY0XP.NQMYO2CFzIKWu9mTtMy3k9YuQUq80qsqtJOvb4lTsnBe', 'farmer', 0, '2026-06-16 05:32:17'),
(2, 'Sumit Rudra', 'sumitrudra14@gmail.com', '$2y$10$XTWJed.a9TdXT/z4xvACZ.fI4YgXqWiS1HhXpZhFljDoxqcKOPZ8O', 'admin', 0, '2026-06-16 05:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `weather_advisories`
--

CREATE TABLE `weather_advisories` (
  `id` int(11) NOT NULL,
  `weather_condition` varchar(100) NOT NULL,
  `advice_text` text NOT NULL,
  `date_posted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weather_alerts`
--

CREATE TABLE `weather_alerts` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `target_area` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weather_alerts`
--

INSERT INTO `weather_alerts` (`id`, `message`, `target_area`, `is_active`, `expires_at`, `created_at`) VALUES
(1, 'asdfghjkl', 'Birbhum', 1, '2026-06-17 18:50:00', '2026-06-16 15:47:58'),
(2, 'swserftrfygnuberdrectrvyuvbghvgfvhv hbv', 'Purba Medinipur', 1, NULL, '2026-06-16 16:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `weather_logs`
--

CREATE TABLE `weather_logs` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `temperature` int(11) DEFAULT NULL,
  `humidity` int(11) DEFAULT NULL,
  `wind_speed` decimal(5,1) DEFAULT NULL,
  `pressure` int(11) DEFAULT NULL,
  `weather_condition` varchar(100) DEFAULT NULL,
  `suitability_score` int(11) DEFAULT NULL,
  `risk_level` varchar(20) DEFAULT NULL,
  `fetched_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weather_logs`
--

INSERT INTO `weather_logs` (`id`, `location`, `lat`, `lon`, `temperature`, `humidity`, `wind_speed`, `pressure`, `weather_condition`, `suitability_score`, `risk_level`, `fetched_at`) VALUES
(1, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:49:28'),
(2, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:49:29'),
(3, 'Contai, IN', 21.7845, 87.7372, 33, 61, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 15:49:42'),
(4, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:50:32'),
(5, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:51:29'),
(6, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:51:49'),
(7, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:52:45'),
(8, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:53:06'),
(9, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 15:53:42'),
(10, 'Contai, IN', 21.7845, 87.7372, 33, 61, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 15:53:53'),
(11, 'Kolkata, IN', 22.5697, 88.3697, 37, 40, 17.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:02:39'),
(12, 'Contai, IN', 21.7846, 87.7372, 32, 64, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 16:02:51'),
(13, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:07:21'),
(14, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:08:12'),
(15, 'Contai, IN', 21.7846, 87.7372, 32, 64, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 16:08:23'),
(16, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:11:38'),
(17, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:11:49'),
(18, 'Contai, IN', 21.7846, 87.7372, 32, 64, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 16:13:26'),
(19, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:15:48'),
(20, 'Contai, IN', 21.7846, 87.7372, 32, 64, 25.0, 1002, 'Clear sky', 100, 'Low', '2026-06-16 16:16:24'),
(21, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:16:43'),
(22, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:18:08'),
(23, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:18:17'),
(24, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:24:53'),
(25, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:25:08'),
(26, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:25:19'),
(27, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:25:34'),
(28, 'Mumbai, IN', 19.0144, 72.8479, 31, 66, 19.0, 1008, 'Clear sky', 100, 'Low', '2026-06-16 16:25:42'),
(29, 'Delhi, IN', 28.6667, 77.2167, 42, 17, 8.0, 999, 'Few clouds', 65, 'Moderate', '2026-06-16 16:25:57'),
(30, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:26:14'),
(31, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:27:15'),
(32, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:30:13'),
(33, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:30:26'),
(34, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:31:11'),
(35, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:31:45'),
(36, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Light rain', 100, 'Low', '2026-06-16 16:31:49'),
(37, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 16:34:49'),
(38, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 16:34:58'),
(39, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 16:47:09'),
(40, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 16:47:20'),
(41, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 16:58:29'),
(42, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 17:04:19'),
(43, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 19.0, 1001, 'Moderate rain', 100, 'Low', '2026-06-16 17:06:57'),
(44, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Moderate rain', 100, 'Low', '2026-06-16 17:14:07'),
(45, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Light rain', 100, 'Low', '2026-06-16 17:43:47'),
(46, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Light rain', 100, 'Low', '2026-06-16 17:44:06'),
(47, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Light rain', 100, 'Low', '2026-06-16 17:44:54'),
(48, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Light rain', 100, 'Low', '2026-06-16 17:46:56'),
(49, 'Kolkata, IN', 22.5697, 88.3697, 34, 51, 25.0, 1002, 'Light rain', 100, 'Low', '2026-06-16 17:47:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crop_knowledge`
--
ALTER TABLE `crop_knowledge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_prices`
--
ALTER TABLE `market_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weather_advisories`
--
ALTER TABLE `weather_advisories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather_alerts`
--
ALTER TABLE `weather_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather_logs`
--
ALTER TABLE `weather_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location`),
  ADD KEY `fetched_at` (`fetched_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crop_knowledge`
--
ALTER TABLE `crop_knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `market_prices`
--
ALTER TABLE `market_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weather_advisories`
--
ALTER TABLE `weather_advisories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weather_alerts`
--
ALTER TABLE `weather_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weather_logs`
--
ALTER TABLE `weather_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
