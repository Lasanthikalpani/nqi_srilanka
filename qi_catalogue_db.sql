-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 04:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qi_catalogue_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nqi_stakeholders`
--

CREATE TABLE `nqi_stakeholders` (
  `id` int(11) NOT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `organization_type` varchar(255) DEFAULT NULL,
  `organization_type_other` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `core_services` text DEFAULT NULL,
  `services` text DEFAULT NULL,
  `services_other` varchar(255) DEFAULT NULL,
  `accreditation` varchar(255) DEFAULT NULL,
  `accreditation_details` text DEFAULT NULL,
  `compliance_update` varchar(50) DEFAULT NULL,
  `regional_branches` varchar(50) DEFAULT NULL,
  `regional_branch_list` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nqi_stakeholders`
--

INSERT INTO `nqi_stakeholders` (`id`, `organization_name`, `organization_type`, `organization_type_other`, `contact_person`, `designation`, `email`, `phone`, `website`, `core_services`, `services`, `services_other`, `accreditation`, `accreditation_details`, `compliance_update`, `regional_branches`, `regional_branch_list`, `comments`, `submitted_at`, `approval_status`, `user_id`) VALUES
(3, 'laasa', '🔬 Testing Laboratory', '', 'Lasanthi Kalpani', 'kaals', 'lasanthikalpani@gmail.com', '0717208416', '', 'dvdv', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-16 00:46:07', 'approved', NULL),
(4, 'IBM', '🎓 Educational/Research Institute', '', 'adminuser', 'manager', 'lasanthikalpani@gmail.com', '0717208416', '', 'adminuser', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-16 01:00:48', 'approved', NULL),
(6, 'Stakeholder', '📜 Certification Body', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'http://localhost/nqi_srilanka/nqi_form.html', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training', '', '🟢 Yes (SLAB)', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '✅ Yes', '✅ Yes', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '2025-07-17 00:02:49', 'pending', NULL),
(8, 'IBM', '🔬 Testing Laboratory', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'http://localhost/nqi_srilanka/nqi_form.html', '', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🎯 Training, 🧠 R&D', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-17 11:41:15', 'approved', NULL),
(9, 'Early skyscrapers', '📜 Certification Body', '', 'Lasanthi Kalpani', 'CEO', 'lasanthikalpani@gmail.com', '0717208416', 'https://www.amnesty.org/en/what-we-do/united-nations/?utm_source=google&amp;utm_medium=cpc&amp;gad_source=1&amp;gad_campaignid=1357523470&amp;gbraid=0AAAAADiFSPQQh1QNNzhF24m79SQ5VuVAG&amp;gclid=Cj0KCQjwm93DBhD_ARIsADR_DjGEXk8khhTrJ2JmfLZ5iBYT99Sh09IQeXidY', 'Early skyscrapers', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training, 📏 Metrology, ⚖️ Regulatory Oversight, 🧠 R&D, 📝 Other', 'Early skyscrapers', '🟢 Yes (SLAB)', 'Early skyscrapers', '✅ Yes', '✅ Yes', 'Early skyscrapers', 'Early skyscrapers', '2025-07-17 23:41:41', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `user_type` varchar(50) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `newsletter_subscription` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `organization`, `user_type`, `is_admin`, `newsletter_subscription`, `created_at`) VALUES
(5, 'sadun', 'janaka', 'sadun@gmail.com', '$2y$10$rSt.O5VX1OBePFlCRNRhXeWUyUdlFkHg7s4hGsUtHCdkgMjF/eGxu', 'abc', 'researcher', 0, 1, '2025-07-17 09:34:17'),
(6, 'janaka', 'Heshan', 'janaka@gmail.com', '$2y$10$o5svnsAmM/mq.9wl/EoU4u.F.HcHu2C1Xks0EZdCsCTPoo3rEm6wC', '', 'researcher', 0, 1, '2025-07-17 10:17:40'),
(12, 'Admin', 'User', 'admin@nqi.lk', '$2y$10$gu8slhOUbPjJEduBDs4ueOLlLUnciIDBKrIhulyu1QCAww4yBCYNW', NULL, 'other', 1, 0, '2025-07-22 15:15:31'),
(16, 'Gillian', 'Anderson', 'Anderson@gmail.com', '$2y$10$Pp1Dg2mOD7qHPOadd5E9AuolxXLcFeP3zWBMoUHF3wikc7iB8uEqS', 'lass', 'quality_professional', 0, 1, '2025-07-22 17:35:42'),
(17, 'Robert De Niro', 'Robert De Niro', 'RobertDeNiro@gmail.com', '$2y$10$sXlndEaW8R3sPmuChUm3V.yWsuPiX9eNEwStcGvw9hR0PlEJRrSNa', 'Robert De Niro', 'quality_professional', 0, 1, '2025-07-24 03:47:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nqi_stakeholders`
--
ALTER TABLE `nqi_stakeholders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nqi_stakeholders`
--
ALTER TABLE `nqi_stakeholders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nqi_stakeholders`
--
ALTER TABLE `nqi_stakeholders`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `fk_reset_token_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
