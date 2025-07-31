-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 04:39 PM
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
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `category`, `description`, `file_path`, `file_name`, `version`, `effective_date`, `created_at`, `updated_at`) VALUES
(13, 'pos', 'standards', 'dd', 'uploads/documents/1753883752_SonarQube.pdf', 'SonarQube.pdf', '121', '2025-08-09', '2025-07-30 13:55:52', '2025-07-30 13:55:52'),
(14, 'pos', 'policy', 'sdsd', 'uploads/documents/1753883856_SonarQube.pdf', 'SonarQube.pdf', '121', '2025-08-09', '2025-07-30 13:57:36', '2025-07-30 13:57:36'),
(16, 'Enhanced', 'forms', 'Enhanced Modal Dialogs: Styled modals to match the theme with animations\\r\\n\\r\\n', 'uploads/documents/1753889882_SonarQube3.pdf', 'SonarQube (3).pdf', '121', '2025-08-08', '2025-07-30 15:38:02', '2025-07-30 15:38:02'),
(17, 'ksjddj', 'awards', '', 'uploads/documents/1753893026_abc.pdf', 'abc.pdf', '121', '2025-08-09', '2025-07-30 16:30:26', '2025-07-30 16:30:26'),
(19, 'Enhanced', 'regulatory', 'ss', 'uploads/documents/1753964688_abc.pdf', 'abc.pdf', '121', '2025-08-07', '2025-07-31 12:24:48', '2025-07-31 12:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `document_responses`
--

CREATE TABLE `document_responses` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `response_file_path` varchar(255) NOT NULL,
  `response_file_name` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_responses`
--

INSERT INTO `document_responses` (`id`, `submission_id`, `response_file_path`, `response_file_name`, `comments`, `created_at`) VALUES
(1, 10, 'uploads/responses/resp_688b7a90502f98.74738190.pdf', '1753803557_SonarQube1.pdf', 'reject', '2025-07-31 14:15:44'),
(2, 10, 'uploads/responses/resp_688b7848c27383.53209485.pdf', 'SonarQube (3) (3).pdf', 'ddd', '2025-07-31 14:06:00'),
(3, 10, 'uploads/responses/resp_688b785ad32e64.18411710.pdf', 'abc (1).pdf', 'ddd', '2025-07-31 14:06:18'),
(4, 10, 'uploads/responses/resp_688b7871665670.69977305.pdf', 'abc.pdf', NULL, '2025-07-31 14:06:41'),
(5, 9, 'uploads/responses/resp_688b787d6dc088.11196082.pdf', 'abc.pdf', NULL, '2025-07-31 14:06:53'),
(6, 9, 'uploads/responses/resp_688b788c2c2705.01781605.pdf', 'SonarQube (4) (1).pdf', NULL, '2025-07-31 14:07:08'),
(7, 10, 'uploads/responses/resp_688b790dd633f7.56556098.pdf', 'abc (1).pdf', NULL, '2025-07-31 14:09:17'),
(8, 7, 'uploads/responses/resp_688b7ab200a743.56235880.pdf', 'abc.pdf', 'reject', '2025-07-31 14:16:18'),
(9, 1, 'uploads/responses/resp_688b7ad63b6310.10239249.pdf', 'abc (1).pdf', NULL, '2025-07-31 14:16:54');

-- --------------------------------------------------------

--
-- Table structure for table `document_submissions`
--

CREATE TABLE `document_submissions` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_submissions`
--

INSERT INTO `document_submissions` (`id`, `document_id`, `user_id`, `file_path`, `file_name`, `submitted_at`, `status`) VALUES
(1, 13, 16, 'uploads/submissions/688a38c5e15d3.pdf', 'SonarQube (3).pdf', '2025-07-30 15:22:45', 'approved'),
(2, 13, 16, 'uploads/submissions/688a3c0cb0d89.pdf', 'SonarQube (4).pdf', '2025-07-30 15:36:44', 'rejected'),
(3, 14, 16, 'uploads/submissions/688a40fa61c74.pdf', 'SonarQube (3) (1).pdf', '2025-07-30 15:57:46', 'approved'),
(4, 13, 6, 'uploads/submissions/688a4122e62b9.pdf', 'SonarQube (5).pdf', '2025-07-30 15:58:26', 'approved'),
(5, 16, 6, 'uploads/submissions/688a413358ab3.pdf', 'SonarQube (3) (2).pdf', '2025-07-30 15:58:43', 'approved'),
(6, 13, 12, 'uploads/submissions/688a451aa303b.pdf', 'SonarQube (6).pdf', '2025-07-30 16:15:22', 'rejected'),
(7, 13, 12, 'uploads/submissions/688a452cc59d5.pdf', 'SonarQube (6).pdf', '2025-07-30 16:15:40', 'rejected'),
(8, 13, 12, 'uploads/submissions/688a453dc851d.pdf', 'SonarQube (6).pdf', '2025-07-30 16:15:57', 'approved'),
(9, 13, 6, 'uploads/submissions/688a45e7af0ee.pdf', 'abc.pdf', '2025-07-30 16:18:47', 'rejected'),
(10, 17, 12, 'uploads/submissions/688a48f2801df.pdf', 'abc (1).pdf', '2025-07-30 16:31:46', 'rejected');

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
(3, 'Tech Solutions', '⚖️ Regulatory Authority', '', 'Michael Brown', 'IT Manager', 'michael.b@techsol.com', '0717208416', '', 'dvdv', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🎯 Training, ⚖️ Regulatory Oversight, 🧠 R&D', '', '🟢 Yes (SLAB)', 'Weekly,2023-10-18,Needs detailed reports', '✅ Yes', '✅ Yes', 'Weekly,2023-10-18,Needs detailed reports', 'Weekly,2023-10-18,Needs detailed reports', '2025-07-16 00:46:07', 'rejected', NULL),
(4, 'IBM', '🎓 Educational/Research Institute', '', 'adminuser', 'manager', 'lasanthikalpani@gmail.com', '0717208416', '', 'adminuser', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing', '', '🟢 Yes (SLAB)', '', '✅ Yes', '✅ Yes', '', '', '2025-07-16 01:00:48', 'pending', NULL),
(6, 'Community Rep', '🏭 Industry/Enterprise', '', 'Maria Garcia', 'Local NGO', 'maria.g@localngo.org', '+1 (555) 345-6789', 'http://localhost/nqi_srilanka/nqi_form.html', 'Strict on deadlines', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training, 📏 Metrology, ⚖️ Regulatory Oversight, 🧠 R&D', '', '🟢 Yes (SLAB)', 'Strict on deadlines', '✅ Yes', '✅ Yes', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '2025-07-17 00:02:49', 'approved', NULL);

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
(15, 16, '81a78ae1f65f3c7c587c6c79b22677da42f3df8ffdeff2da386b4da1cbd6d6d4', '2025-07-28 17:25:54');

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
(6, 'janaka', 'Heshan', 'janaka@gmail.com', '$2y$10$o5svnsAmM/mq.9wl/EoU4u.F.HcHu2C1Xks0EZdCsCTPoo3rEm6wC', '', 'researcher', 0, 1, '2025-07-17 10:17:40'),
(12, 'Admin', 'User', 'admin@nqi.lk', '$2y$10$gu8slhOUbPjJEduBDs4ueOLlLUnciIDBKrIhulyu1QCAww4yBCYNW', NULL, 'other', 1, 0, '2025-07-22 15:15:31'),
(16, 'Gillian', 'Anderson', 'Anderson@gmail.com', '$2y$10$Pp1Dg2mOD7qHPOadd5E9AuolxXLcFeP3zWBMoUHF3wikc7iB8uEqS', 'lass', 'quality_professional', 0, 1, '2025-07-22 17:35:42'),
(17, 'Robert De Niro', 'Robert De Niro', 'RobertDeNiro@gmail.com', '$2y$10$sXlndEaW8R3sPmuChUm3V.yWsuPiX9eNEwStcGvw9hR0PlEJRrSNa', 'Robert De Niro', 'quality_professional', 0, 1, '2025-07-24 03:47:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_responses`
--
ALTER TABLE `document_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `document_submissions`
--
ALTER TABLE `document_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `document_responses`
--
ALTER TABLE `document_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `document_submissions`
--
ALTER TABLE `document_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nqi_stakeholders`
--
ALTER TABLE `nqi_stakeholders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_responses`
--
ALTER TABLE `document_responses`
  ADD CONSTRAINT `document_responses_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submissions` (`id`);

--
-- Constraints for table `document_submissions`
--
ALTER TABLE `document_submissions`
  ADD CONSTRAINT `document_submissions_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `document_submissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
