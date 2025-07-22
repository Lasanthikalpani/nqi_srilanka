-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 07:53 PM
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
(1, 'laasa', '🏛️ NQI Body (e.g., SLSI, SLAB, MUSSD)', '', 'Lasanthi Kalpani', 'kaals', 'lasanthikalpani@gmail.com', '0717208416', '', '', '🔎 Inspection, 📏 Metrology', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', 'kajs', '', '2025-07-16 00:24:39', 'approved', NULL),
(2, 'laasa', '⚖️ Regulatory Authority', '', 'Lasanthi Kalpani', 'kaals', 'lasanthikalpani@gmail.com', '0717208416', '', 'adndn', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification', '', '🌐 Yes (Other)', '', '❌ No', '✅ Yes', '', '', '2025-07-16 00:35:27', 'rejected', NULL),
(3, 'laasa', '🔬 Testing Laboratory', '', 'Lasanthi Kalpani', 'kaals', 'lasanthikalpani@gmail.com', '0717208416', '', 'dvdv', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-16 00:46:07', 'approved', NULL),
(4, 'IBM', '🎓 Educational/Research Institute', '', 'adminuser', 'manager', 'lasanthikalpani@gmail.com', '0717208416', '', 'adminuser', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-16 01:00:48', 'approved', NULL),
(5, 'United Nations', '🎓 Educational/Research Institute', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'https://www.amnesty.org/en/what-we-do/united-nations/?utm_source=google&amp;utm_medium=cpc&amp;gad_source=1&amp;gad_campaignid=1357523470&amp;gbraid=0AAAAADiFSPQQh1QNNzhF24m79SQ5VuVAG&amp;gclid=Cj0KCQjwm93DBhD_ARIsADR_DjGEXk8khhTrJ2JmfLZ5iBYT99Sh09IQeXidY', 'The UN was founded after the Second World War by 51 countries never wanting to see the horrors of war and holocaust again. Over the last 70 years, UN membership and relevance has grown, and it now has 193 member states. It is the world’s largest and most important international organization.', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', 'The UN was founded after the Second World War by 51 countries never wanting to see the horrors of war and holocaust again. Over the last 70 years, UN membership and relevance has grown, and it now has 193 member states. It is the world’s largest and most important international organization.', 'The UN was founded after the Second World War by 51 countries never wanting to see the horrors of war and holocaust again. Over the last 70 years, UN membership and relevance has grown, and it now has 193 member states. It is the world’s largest and most important international organization.', '2025-07-16 23:20:15', 'pending', NULL),
(6, 'Stakeholder', '📜 Certification Body', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'http://localhost/nqi_srilanka/nqi_form.html', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training', '', '🟢 Yes (SLAB)', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '✅ Yes', '✅ Yes', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '2025-07-17 00:02:49', 'pending', NULL),
(7, 'U.S. Department of Energy National Laboratory', '🎓 Educational/Research Institute', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'https://www.amnesty.org/en/what-we-do/united-nations/?utm_source=google&amp;utm_medium=cpc&amp;gad_source=1&amp;gad_campaignid=1357523470&amp;gbraid=0AAAAADiFSPQQh1QNNzhF24m79SQ5VuVAG&amp;gclid=Cj0KCQjwm93DBhD_ARIsADR_DjGEXk8khhTrJ2JmfLZ5iBYT99Sh09IQeXidY', 'U.S. Department of Energy National Laboratory', '🔬 Laboratory Testing, ⚖️ Regulatory Oversight, 🧠 R&D', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', 'U.S. Department of Energy National Laboratory', '', '2025-07-17 11:14:45', 'pending', NULL),
(8, 'IBM', '🔬 Testing Laboratory', '', 'Lasanthi Kalpani', 'manager', 'lasanthikalpani@gmail.com', '0717208416', 'http://localhost/nqi_srilanka/nqi_form.html', '', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🎯 Training, 🧠 R&D', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-17 11:41:15', 'approved', NULL),
(9, 'Early skyscrapers', '📜 Certification Body', '', 'Lasanthi Kalpani', 'CEO', 'lasanthikalpani@gmail.com', '0717208416', 'https://www.amnesty.org/en/what-we-do/united-nations/?utm_source=google&amp;utm_medium=cpc&amp;gad_source=1&amp;gad_campaignid=1357523470&amp;gbraid=0AAAAADiFSPQQh1QNNzhF24m79SQ5VuVAG&amp;gclid=Cj0KCQjwm93DBhD_ARIsADR_DjGEXk8khhTrJ2JmfLZ5iBYT99Sh09IQeXidY', 'Early skyscrapers', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training, 📏 Metrology, ⚖️ Regulatory Oversight, 🧠 R&D, 📝 Other', 'Early skyscrapers', '🟢 Yes (SLAB)', 'Early skyscrapers', '✅ Yes', '✅ Yes', 'Early skyscrapers', 'Early skyscrapers', '2025-07-17 23:41:41', 'pending', NULL),
(10, 'Academy for Educational Development', '📜 Certification Body', '', 'Lasanthi Kalpani', 'CEO', 'lasanthikalpani@gmail.com', '0717208416', 'https://www.amnesty.org/en/what-we-do/united-nations/?utm_source=google&amp;utm_medium=cpc&amp;gad_source=1&amp;gad_campaignid=1357523470&amp;gbraid=0AAAAADiFSPQQh1QNNzhF24m79SQ5VuVAG&amp;gclid=Cj0KCQjwm93DBhD_ARIsADR_DjGEXk8khhTrJ2JmfLZ5iBYT99Sh09IQeXidY', 'Semi-protected: This is a redirect from a title that is semi-protected from editing for any of several possible reasons.', '🧪 Calibration, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training, 📏 Metrology', '', '🟢 Yes (SLAB)', 'Semi-protected: This is a redirect from a title that is semi-protected from editing for any of several possible reasons.', '✅ Yes', '✅ Yes', 'Semi-protected: This is a redirect from a title that is semi-protected from editing for any of several possible reasons.', 'Semi-protected: This is a redirect from a title that is semi-protected from editing for any of several possible reasons.', '2025-07-18 00:00:46', 'pending', NULL),
(11, 'ActionAid', '🏭 Industry/Enterprise', '', 'Lasanthi Kalpani', 'CEO', 'lasanthikalpani@gmail.com', '0717208416', 'http://localhost/nqi_srilanka/nqi_form.html', 'ActionAid', '🛡️ Certification, 🔬 Laboratory Testing, 🎯 Training', '', '🟢 Yes (SLAB)', 'ActionAid', '✅ Yes', '✅ Yes', 'ActionAid', 'ActionAid', '2025-07-18 00:04:06', 'pending', NULL),
(12, 'NQI Stakeholder Infor', '📜 Certification Body', '', 'Lasanthi Kalpani', 'ceo', 'lasanthikalpani@gmail.com', '0717208416', 'https://sourceforge.net/projects/xampp/', 'https://sourceforge.net/projects/xampp/', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection', '', '🟢 Yes (SLAB)', 'https://sourceforge.net/projects/xampp/', '✅ Yes', '✅ Yes', 'https://sourceforge.net/projects/xampp/', 'https://sourceforge.net/projects/xampp/', '2025-07-21 04:34:52', 'pending', NULL),
(13, 'The X Files', '🎓 Educational/Research Institute', '', 'Lasanthi Kalpani', 'ceo', 'lasanthikalpani@gmail.com', '0717208416', 'https://sourceforge.net/projects/xampp/', 'The X Files', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', 'The X Files', '✅ Yes', '✅ Yes', 'The X Files', 'The X Files', '2025-07-22 17:38:55', 'pending', 16);

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

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `user_id`, `token`, `expiration`) VALUES
(7, 4, '0e925801840ecc654053eeb654e174ed40e0dae201e0f7c7137e11dc4040f787', '2025-07-20 07:50:08'),
(8, 1, '1d77b6d91a03d596f0a2c6be1df8235741de6ff9fd4f1324f9be44c9ddca0a82', '2025-07-20 07:50:26');

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
(1, 'Lasanthi', 'Kalpani', 'lasanthikalpani@gmail.com', '$2y$10$UFNIPj071lGC764O.VZupeVIQkTrxoTtlMnCrg77aFBESX6xLk6CG', 'abc', 'sme', 0, 1, '2025-07-17 03:43:11'),
(4, 'kasuni', 'lakshika', 'kasuni@gmail.com', '$2y$10$9/TvLNRSnuaCptph54I83eC176tnsrJCXMgA8fWCpkx18KjkGDt/K', 'abc', 'government', 0, 1, '2025-07-17 07:21:03'),
(5, 'sadun', 'janaka', 'sadun@gmail.com', '$2y$10$rSt.O5VX1OBePFlCRNRhXeWUyUdlFkHg7s4hGsUtHCdkgMjF/eGxu', 'abc', 'researcher', 0, 1, '2025-07-17 09:34:17'),
(6, 'janaka', 'Heshan', 'janaka@gmail.com', '$2y$10$o5svnsAmM/mq.9wl/EoU4u.F.HcHu2C1Xks0EZdCsCTPoo3rEm6wC', '', 'researcher', 0, 1, '2025-07-17 10:17:40'),
(9, 'Admin', 'User', 'admin@example.com', '$2y$10$X8jMTg9WvZf4dSJQ2nYrE.9mzQbJxKpLqH1cV3DyNw7Rt5sUvGhOi', NULL, '', 0, 0, '2025-07-22 14:12:58'),
(11, 'Admin', 'User', 'adminuser@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '', 0, 0, '2025-07-22 14:48:31'),
(12, 'Admin', 'User', 'admin@nqi.lk', '$2y$10$gu8slhOUbPjJEduBDs4ueOLlLUnciIDBKrIhulyu1QCAww4yBCYNW', NULL, 'other', 1, 0, '2025-07-22 15:15:31'),
(16, 'Gillian', 'Anderson', 'Anderson@gmail.com', '$2y$10$Pp1Dg2mOD7qHPOadd5E9AuolxXLcFeP3zWBMoUHF3wikc7iB8uEqS', 'lass', 'quality_professional', 0, 1, '2025-07-22 17:35:42');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
