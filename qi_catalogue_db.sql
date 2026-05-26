-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 07:03 PM
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
(19, 'Enhanced', 'regulatory', 'ss', 'uploads/documents/1753964688_abc.pdf', 'abc.pdf', '121', '2025-08-07', '2025-07-31 12:24:48', '2025-07-31 12:24:48'),
(20, 'circular 123', 'awards', '', 'uploads/documents/1753972990_circular.pdf', 'circular.pdf', '3333', '2025-08-16', '2025-07-31 14:43:10', '2025-07-31 14:43:10'),
(23, '4. Accreditation and Certification Documents', 'accreditation', '4. Accreditation and Certification Documents\\r\\n', 'uploads/documents/1754493396_question3.pdf', 'question (3).pdf', '3333', '2025-08-22', '2025-08-06 15:16:36', '2025-08-06 15:16:36'),
(25, 'Enhanced', 'metrology', '', 'uploads/documents/1758791679_Untitleddocument29.pdf', 'Untitled document (29).pdf', '', '2025-10-11', '2025-09-25 09:14:39', '2025-09-25 09:14:39'),
(26, 'filtered', 'directories', 'filtered', 'uploads/documents/1758794151_assignment1-vhdl2.pdf', 'assignment1-vhdl (2).pdf', '', NULL, '2025-09-25 09:55:51', '2025-09-25 09:55:51'),
(27, 'filtered', 'standards', 'filtered', 'uploads/documents/1758794220_Untitleddocument26.pdf', 'Untitled document (26).pdf', 'filtered', '2025-10-10', '2025-09-25 09:57:00', '2025-09-25 09:57:00');

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
(5, 9, 'uploads/responses/resp_688b82bf5c3dd1.70573438.pdf', 'response (1).pdf', NULL, '2025-07-31 14:50:39'),
(6, 9, 'uploads/responses/resp_688b788c2c2705.01781605.pdf', 'SonarQube (4) (1).pdf', NULL, '2025-07-31 14:07:08'),
(7, 10, 'uploads/responses/resp_688b790dd633f7.56556098.pdf', 'abc (1).pdf', NULL, '2025-07-31 14:09:17'),
(8, 7, 'uploads/responses/resp_688b7ab200a743.56235880.pdf', 'abc.pdf', 'reject', '2025-07-31 14:16:18'),
(9, 1, 'uploads/responses/resp_688b7ad63b6310.10239249.pdf', 'abc (1).pdf', NULL, '2025-07-31 14:16:54'),
(10, 11, 'uploads/responses/resp_688b8186ca7b25.22087307.pdf', 'response.pdf', NULL, '2025-07-31 14:45:26'),
(11, 12, 'uploads/responses/resp_688b86c699f300.75326688.pdf', 'abc (3).pdf', NULL, '2025-07-31 15:07:50'),
(12, 13, 'uploads/responses/resp_6893725de60482.40118482.pdf', 'response (2).pdf', 'sucess', '2025-08-06 15:18:53'),
(13, 18, 'uploads/responses/resp_698f4b1742ef75.91842287.pdf', 'AWS Academy Cloud Architecting 3 – Capstone Project.pdf', NULL, '2026-02-13 16:02:31');

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
(9, 13, 6, 'uploads/submissions/688a45e7af0ee.pdf', 'abc.pdf', '2025-07-30 16:18:47', 'approved'),
(10, 17, 12, 'uploads/submissions/688a48f2801df.pdf', 'abc (1).pdf', '2025-07-30 16:31:46', 'rejected'),
(11, 20, 16, 'uploads/submissions/688b8141e25ea.pdf', 'question.pdf', '2025-07-31 14:44:17', 'rejected'),
(12, 20, 6, 'uploads/submissions/688b86947b034.pdf', 'question (2).pdf', '2025-07-31 15:07:00', 'rejected'),
(13, 23, 16, 'uploads/submissions/689371fbb0c78.pdf', 'response (2).pdf', '2025-08-06 15:17:15', 'approved'),
(14, 13, 17, 'uploads/submissions/6988ca72803db.pdf', '04. Regression.pdf', '2026-02-08 17:40:02', 'pending'),
(15, 13, 17, 'uploads/submissions/6988cf7295c1c.pdf', 'SLICTS Model Paper 5.pdf', '2026-02-08 18:01:22', 'pending'),
(16, 13, 17, 'uploads/submissions/6988d29a10313.pdf', 'job application.pdf', '2026-02-08 18:14:50', 'pending'),
(17, 13, 17, 'uploads/submissions/6988d5240d83b.pdf', 'job application.pdf', '2026-02-08 18:25:40', 'pending'),
(18, 13, 17, 'uploads/submissions/6988daece2ff6.pdf', 'job application (2).pdf', '2026-02-08 18:50:20', 'approved'),
(19, 17, 17, 'uploads/submissions/698f4f93c81cc.pdf', 'abc (7).pdf', '2026-02-13 16:21:39', 'pending'),
(20, 16, 17, 'uploads/submissions/698f4ffa1135d.pdf', 'job application (2) (3).pdf', '2026-02-13 16:23:22', 'pending');

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

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Lasanthi Kalpani', 'lasanthikalpani@gmail.com', 'ss', '2026-05-09 17:06:34');

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
(6, 'Community Rep', '🏭 Industry/Enterprise', '', 'Maria Garcia', 'Local NGO', 'maria.g@localngo.org', '+1 (555) 345-6789', 'http://localhost/nqi_srilanka/nqi_form.html', 'Strict on deadlines', '📘 Standards Development, 🧪 Calibration, 🛡️ Certification, 🔬 Laboratory Testing, 🔎 Inspection, 🎯 Training, 📏 Metrology, ⚖️ Regulatory Oversight, 🧠 R&D', '', '🟢 Yes (SLAB)', 'Strict on deadlines', '✅ Yes', '✅ Yes', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', 'To create a new Git branch for updating stakeholder information, follow these steps in your terminal or command prompt:', '2025-07-17 00:02:49', 'approved', NULL),
(18, 'Academy for Educational Development', 'Industry/Enterprise', 'Global Enterprises', 'Lasanthi Kalpani', 'External Consultant', 'lasanthikalpani@gmail.com', '0717208416', '', '', 'Standards Development, Calibration, Certification, Laboratory Testing, Inspection', '', 'Yes (SLAB)', '', 'Yes', 'Yes', '', '', '2026-05-07 07:16:08', 'pending', 16);

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `product_category` varchar(100) DEFAULT NULL,
  `industry_sector` varchar(100) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `conformity_service` varchar(255) DEFAULT NULL,
  `certification_status` varchar(100) DEFAULT NULL,
  `export_status` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `brand_name`, `manufacturer`, `product_category`, `industry_sector`, `organization`, `conformity_service`, `certification_status`, `export_status`, `province`, `district`, `city`, `contact_person`, `phone`, `email`, `website`, `description`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'abc', 'abc', 'pqr', 'Services', 'Services', 'SLSI', 'Inspection', 'SLS Certified', 'Currently Exporting', 'Western', 'Colombo', 'Matara', 'Lasanthi Kalpani', '+94717208416', 'lasanthikalpani@gmail.com', '', '', 16, 'pending', '2026-05-24 22:37:16', NULL),
(2, 'abc', 'cga', 'opp', 'Food & Agricultural', 'Agriculture', 'SLTB', 'Microbiological Testing', 'In Progress', 'Currently Exporting', 'Western', 'Colombo', 'Matara', 'Lasanthi Kalpani', '+94717208416', 'lasanthikalpani@gmail.com', '', '', 16, 'pending', '2026-05-24 22:52:17', NULL),
(3, 'blck tea', 'log', 'opp', 'Tea & Beverages', 'Chemicals', 'SLAB', 'Electrical Testing', 'SLS Certified', 'Currently Exporting', 'Western', 'Colombo', 'Matara', 'Lasanthi Kalpani', '+94717208416', 'lasanthikalpani@gmail.com', '', '', 16, 'pending', '2026-05-26 22:22:06', NULL);

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
(6, 'janaka', 'Heshan', 'janaka@gmail.com', '$2y$10$5S98xhtqjTYQuIDgClawfOkPDpQVAkexB8.skYI3Mur76a/H1qo/i', '', 'researcher', 0, 1, '2025-07-17 10:17:40'),
(12, 'Admin', 'User', 'admin@nqi.lk', '$2y$10$YE6h2B0KNPytfRKui5u/xey36Hj2q/Oy7m6GtpfXpORqRljVlh43q', NULL, 'other', 1, 0, '2025-07-22 15:15:31'),
(16, 'Gillian', 'Anderson', 'Anderson@gmail.com', '$2y$10$Pp1Dg2mOD7qHPOadd5E9AuolxXLcFeP3zWBMoUHF3wikc7iB8uEqS', 'lass', 'quality_professional', 0, 1, '2025-07-22 17:35:42'),
(17, 'Robert De Niro', 'Robert De Niro', 'RobertDeNiro@gmail.com', '$2y$10$sXlndEaW8R3sPmuChUm3V.yWsuPiX9eNEwStcGvw9hR0PlEJRrSNa', 'Robert De Niro', 'quality_professional', 0, 1, '2025-07-24 03:47:36'),
(18, 'Jane', 'Doe', 'jane.doe@example.com', '$2y$10$.09CSQebq7vovCWUfA0yieeSkAFgZi58FF2dpLn3NZMIccLJM4DQO', 'TechCorp', 'quality_professional', 0, 0, '2026-05-23 08:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `xml_storage`
--

CREATE TABLE `xml_storage` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `original_file_path` varchar(255) NOT NULL,
  `xml_file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xml_storage`
--

INSERT INTO `xml_storage` (`id`, `document_id`, `action_type`, `original_file_path`, `xml_file_path`, `created_at`) VALUES
(1, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770573671_6988cf67ef40c.xml', '2026-02-08 18:01:12'),
(2, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770575836_6988d7dc032a9.xml', '2026-02-08 18:37:16'),
(3, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770575924_6988d83461e31.xml', '2026-02-08 18:38:44'),
(4, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770998240_698f49e031f90.xml', '2026-02-13 15:57:22'),
(5, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770998242_698f49e2547d9.xml', '2026-02-13 15:57:23'),
(6, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770998288_698f4a10e2bb3.xml', '2026-02-13 15:58:09'),
(7, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1770998620_698f4b5c0305c.xml', '2026-02-13 16:03:40'),
(8, 17, 'download', 'uploads/documents/1753893026_abc.pdf', 'xml_storage/doc_17_download_1770999574_698f4f16be7b0.xml', '2026-02-13 16:19:35'),
(9, 14, 'download', 'uploads/documents/1753883856_SonarQube.pdf', 'xml_storage/doc_14_download_1770999659_698f4f6b8e924.xml', '2026-02-13 16:21:00'),
(10, 16, 'download', 'uploads/documents/1753889882_SonarQube3.pdf', 'xml_storage/doc_16_download_1770999786_698f4fea5536f.xml', '2026-02-13 16:23:06'),
(11, 16, 'download', 'uploads/documents/1753889882_SonarQube3.pdf', 'xml_storage/doc_16_download_1778176045_69fcd02d81443.xml', '2026-05-07 17:47:26'),
(12, 13, 'download', 'uploads/documents/1753883752_SonarQube.pdf', 'xml_storage/doc_13_download_1778226942_69fd96fe7ce8d.xml', '2026-05-08 07:55:44');

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `product_category` (`product_category`),
  ADD KEY `industry_sector` (`industry_sector`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `xml_storage`
--
ALTER TABLE `xml_storage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `document_responses`
--
ALTER TABLE `document_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `document_submissions`
--
ALTER TABLE `document_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nqi_stakeholders`
--
ALTER TABLE `nqi_stakeholders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `xml_storage`
--
ALTER TABLE `xml_storage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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

--
-- Constraints for table `xml_storage`
--
ALTER TABLE `xml_storage`
  ADD CONSTRAINT `xml_storage_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
