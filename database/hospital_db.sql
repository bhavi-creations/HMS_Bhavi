-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 01:02 PM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Paid','Pending') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `availability_schedule` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `serial_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `contact`, `email`, `availability_schedule`, `created_at`, `photo`, `status`, `serial_number`) VALUES
(8, 'abhi', 'heart ', '8754308754', 'abhi@gmail.com', '24hrs 3', '2024-12-20 04:52:58', '20241220310688.jpg', 0, NULL),
(9, 'u', 'heart robber', '9879879879', 'visoi@gmail.com', 'uj', '2024-12-20 05:00:32', '20241220819787.jpeg', 0, NULL),
(10, 'abhi', 'dental ', '8278272340', 'vgv@gmail.com', 'gfx', '2024-12-20 09:20:17', '20241220143372.jpg', 0, NULL),
(11, 'siva', 'ramaram', '43543', 'visoi@gmail.com', 'f', '2024-12-25 07:20:19', '20241225463340.jpeg', 1, NULL),
(12, 'sat', 'dental ', '9879879879', 'vgv@gmail.com', 'rh', '2024-12-28 06:44:36', '20241228243111.jpeg', 1, NULL),
(13, 'labes', 'we', '9879879879', 'latha@gmail.com', 'reg', '2024-12-28 06:44:51', '20241228451413.jpeg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctors_list`
--

CREATE TABLE `doctors_list` (
  `doctor_id` varchar(20) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `join_date` date DEFAULT NULL,
  `department` varchar(100) NOT NULL,
  `shift_timings` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `availability_days` varchar(50) DEFAULT NULL,
  `consultation_hours` varchar(50) DEFAULT NULL,
  `certificates` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors_list`
--

INSERT INTO `doctors_list` (`doctor_id`, `doctor_name`, `dob`, `gender`, `phone`, `email`, `specialization`, `qualification`, `experience`, `registration_number`, `join_date`, `department`, `shift_timings`, `username`, `password`, `address`, `profile_image`, `salary`, `bank_account`, `tax_id`, `availability_days`, `consultation_hours`, `certificates`, `created_at`, `updated_at`) VALUES
('DOC20250129-0003', 'ranesh', '2025-03-01', 'Female', '9239423434', 'mani@gmail.caom', 'kidney', 'degreee', 1, '0987', NULL, 'ew', 'morning', 'bhavicreations2@gmail.com', '$2y$10$vPrtjuPimv8ujXzEtFhui.rz9ysyR71TzGg/QWbf42oHrNFa46hSy', '12-22-343 maha laksahmi nagea kakainada', '../../../assets/uploads/doctors_profiles/clip_art.png', 22222.00, '15353555', '234232345678990', '5', '2', '../../../assets/uploads/doctors_profiles/1738130478_Untitled design (4).png', '2025-01-29 01:31:18', '2025-01-29 01:31:18'),
('DOC20250129-0004', 'reddy', '1990-10-05', 'Male', '9239423434', 'reddy@gmail.com', 'lungs', 'mbbs', 2, '587469', NULL, 'dental', 'night', 'bhavicreations6@gmail.com', '$2y$10$tIUQCichQCe3GHKVRaKYRuqBRbhpxNMOZfKI6wXsn/HVbzsjE3IIe', '12-22-358 maha laksahmi nagea kakainada', '../../../assets/uploads/doctors_profiles/Best-multi-services-hospital-in-kakinada-aruna-hospital.webp', 5478.00, '324325222222', '243243', '5', '1', '../../../assets/uploads/doctors_profiles/1738130619_clip_art.png', '2025-01-29 01:33:39', '2025-01-29 01:33:39'),
('DOC20250129-0005', 'abhi', '2025-01-17', 'Male', '9239423432474788', 'abhi@gmail.com', 'kidney', '10', 1, '02876', NULL, 'ew', 'morning', 'bhavicreations8@gmail.com', '$2y$10$C08eK.I92l7OfSMCzPo31uTBxjDT2YSvE/4xXvrx/ByRJScpE2hTK', '12-22-34 maha laksahmi nagea kakainada', '../../../assets/uploads/doctors_profiles/Best-multi-services-hospital-in-kakinada-aruna-hospital.webp', 99999.00, '3243252222222323222', '3454354543531', '15', '10', '', '2025-01-29 01:35:11', '2025-01-29 01:35:11'),
('DOC20250129-0006', 'sasthri', '2025-01-10', 'Male', '9239423434', 'abhi@gmail.com', 'lungs', 'mbbs', 3, '324', NULL, 'heart', '234', ' ht', '$2y$10$xY7NuPjwab5DNEXI4Cz8W./ud4SW.7mjmKy3.LapKYraYrCx/2wHa', 'wet', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 234.00, '3243252222222323222', '3454354543531', '43t', '43t', '../../../assets/uploads/doctors_profiles/1738151044_WhatsApp Image 2024-12-30 at 18.08.58 (2).jpeg,../../../assets/uploads/doctors_profiles/1738151044_CocoFarms Luxury Resort (3).pdf,../../../assets/uploads/doctors_profiles/1738151044_100970_Environment, Forest, Science & Technology Department_Security_Audit_Initial_Report.pdf', '2025-01-29 07:14:04', '2025-01-29 07:14:04'),
('DOC20250130-0001', 'ravan', '2025-01-04', 'Female', '213', 'abhi@gmail.com', 'lasik', 'rmp', 1, '09832', NULL, 'eyes', '2-5', 'jumba', '$2y$10$apUvLFU8pylVpew6m/NKDeAkL./CJwrAjIWvVJQJLWHJyKYaWY.K6', 'rjy', '../../../assets/uploads/doctors_profiles/alci_oi._Roll_No._00170828051019-removebg-preview.png', 24433.00, '15353555', '234232345678990', 'sun-mon', '2-6', '../../../assets/uploads/doctors_profiles/1738218301_9cbc9333-49a1-422e-ba99-989ab22b925c.png,../../../assets/uploads/doctors_profiles/1738218301_IT.pdf.jpg,../../../assets/uploads/doctors_profiles/1738218301_IT.pdf.pdf', '2025-01-30 01:55:01', '2025-01-30 01:55:01'),
('DOC20250130-0002', 'mani', '2025-01-16', 'Male', '213', 'bhavicreations3022@gmail.come', 'heart ', 'degreee', 2, '123', NULL, 'kidnay', 'night', 'bhavicreations@gmail.com', '$2y$10$Zdc6MIEmDFW/Mi41.IPjmO4yMD0FTCEfNEZjdW5LSYyOqfopSVkey', '23 ksnjgs', '../../../assets/uploads/doctors_profiles/slider (1).jpg', 200000.00, '3243252222222323222', '23423', '5', '3', '../../../assets/uploads/doctors_profiles/1738221683_Untitled design (4).png', '2025-01-30 02:51:23', '2025-01-30 02:51:23'),
('DOC20250130-0003', 'mohan ', '2025-01-25', 'Female', '9239423432474788', 'bhavicreations302222@gmail.come', 'lungs', 'degreee', 1, '345', NULL, 'dental', 'mrng', 'bhavicreations1@gmail.com', '$2y$10$/6sFGmAb3P3bQQEC0bBmie94tJzmCu1FIJkRuVHcuk7n5UXQdDfPi', '12 kjnfji hfi', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 58000.00, '324325222222', '3454354543531', '15', '9', '../../../assets/uploads/doctors_profiles/1738221762_clip_art.png', '2025-01-30 02:52:42', '2025-01-30 02:52:42'),
('DOC20250130-0004', 'kumar', '2025-01-15', 'Male', '9239423434', 'vgv@gmail.com', 'kidney', 'mbbs', 6, '456789', NULL, 'ew', 'mhnbg', 'bhavicreations3@gmail.com', '$2y$10$W1smW6vzDuTpMNt1ytdKkeKz5A1kIClVT94OY2MF1UnOt183lWbLC', 'i hgggh', '../../../assets/uploads/doctors_profiles/Best-multi-services-hospital-in-kakinada-aruna-hospital.webp', 98000.00, '3243252222222323222', '234232345678990', '10', '1', '../../../assets/uploads/doctors_profiles/1738221871_Untitled design (4).png', '2025-01-30 02:54:31', '2025-01-30 02:54:31'),
('DOC20250130-0005', 'veney', '2025-01-15', 'Female', '213', 'visoi@gmail.com', 'lasik', 'mbbs', 4, '325232', '2025-01-24', 'ear', '34', 'wetwe', '$2y$10$U93U57s8pCoxlUFcP/vpIe4peObFk6F61xuNKt.h6jW.dfhcNrkTe', '43', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 334.00, '324325222222', '354', '32', '43', '../../../assets/uploads/doctors_profiles/1738234518_2-2 R20 Supple Nov-2024.pdf,../../../assets/uploads/doctors_profiles/1738234518_Untitled design (4).png', '2025-01-30 06:25:18', '2025-01-30 06:25:18'),
('DOC20250130-0006', 'savithri', '2025-01-02', 'Male', '9239423434', 'bhavicreations3022@gmail.come', 'heart ', 'degreee', 23, '2525', '2025-01-24', 'ew', '34', 'htrnr', '$2y$10$HZu3zZUhwjYopM/4oGAbaOZAITyb1zksRGWOQUcsizldw6qZDBKni', '24', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 234.00, '2343545', '354', '23', '23', '../../../assets/uploads/doctors_profiles/1738237661_Untitled design (4).png', '2025-01-30 07:17:41', '2025-01-30 07:17:41'),
('DOC20250130-0007', 'savitri', '2025-01-18', 'Female', '9239423434', 'bhavicreations3022@gmail.come', 'heart ', 'mbbs', 23, '23464', '2025-01-02', 'ew', '23', 'dfb', '$2y$10$/Mea61iVJFQ8QFsf0VHWd.nbh6JvMdrCHb0TuVXqvHkxR7mKqEBJu', '43', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 23.00, '2343545', '354', '34', '34', '../../../assets/uploads/doctors_profiles/1738237715_3-1 R20 Reg_Supple Nov_Dec-2024.pdf', '2025-01-30 07:18:35', '2025-01-30 07:18:35'),
('DOC20250130-0008', 'mani', '2025-01-02', 'Female', '213', 'bhavicreations@gmail.com', 'kidney', 'mbbs', 23, '235246q', '2025-01-17', 'ew', '34', 'jh', '$2y$10$MEgEfLf5pQk2/FzSnerQaOofoPfcpPwOQHfADMT9mLHrw9ymN6yPa', 'f', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 34.00, '324325222222', '23423', '34', '34', '../../../assets/uploads/doctors_profiles/1738237800_2-2 R20 Supple Nov-2024.pdf', '2025-01-30 07:20:00', '2025-01-30 07:20:00'),
('DOC20250131-0009', 'raja', '2025-01-10', 'Female', '9239423434', 'visoi@gmail.com', 'kidney', 'degreee', 2, '357657', '2025-01-18', 'dental', '3-5', 'll', '$2y$10$PWLmnTx90xLVmtmDEv.kie/BLHLH4FwEyflOHtsuhqaZgQkauChpi', '36', '../../../assets/uploads/doctors_profiles/Untitled design (4).png', 22.00, '2343545', '3454354543531', '34', '54', '../../../assets/uploads/doctors_profiles/1738318171_3-1 R20 Reg_Supple Nov_Dec-2024.pdf,../../../assets/uploads/doctors_profiles/1738318171_9cbc9333-49a1-422e-ba99-989ab22b925c.png', '2025-01-31 05:39:31', '2025-01-31 05:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `availability_schedule` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `serial_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurses`
--

INSERT INTO `nurses` (`id`, `name`, `department`, `contact`, `email`, `availability_schedule`, `created_at`, `photo`, `status`, `serial_number`) VALUES
(5, 'latha', 'dental', '24324324', 'latha@gmail.com', 'ttlfdml435345', '2024-12-20 04:53:55', '20241220212114.png', 0, NULL),
(6, 'labes', 'ew', '8278272340', 'latha@gmail.com', 'tu', '2024-12-25 07:20:44', '20241225835202.png', 0, NULL),
(7, 'oi', 'ew', '9887985879', 'visoi@gmail.com', 'yt', '2024-12-26 05:22:16', '2024122619002.jpg', 0, NULL),
(8, 'labes', 'dental', '8278272340', 'vgv@gmail.com', 'rg', '2024-12-28 06:45:26', '20241228720249.png', 1, NULL),
(9, 'arega', 'ew', '234', 'latha@gmail.com', 'f', '2024-12-28 07:19:25', '20241228319022.png', 0, NULL),
(10, 'ge', 'ew', '8278272340', 'vgv@gmail.com', 'rf', '2024-12-28 07:19:43', '20241228231054.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `serial_number` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `age`, `gender`, `contact`, `address`, `medical_history`, `created_at`, `serial_number`, `status`) VALUES
(8, 'vision', 33, 'Female', '9879879879', 'rjy', 'non', '2024-12-20 08:48:48', NULL, 1),
(10, 'new', 22, 'Male', '9879879879', '3f', '32f', '2024-12-28 05:59:15', NULL, 1),
(11, 'vision', 234, 'Female', '9879879879', 'mn', 'n/', '2024-12-28 06:47:10', NULL, 1),
(12, 'tttttt', 234, 'Female', '9879879879', 'dbx', 'trb', '2024-12-28 09:18:03', NULL, 1),
(13, 'jack ', 77, 'Male', '242', 'fb', '45t', '2024-12-30 06:16:59', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `patients_ipd`
--

CREATE TABLE `patients_ipd` (
  `id` varchar(15) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `doctor` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `admission_type` varchar(50) DEFAULT NULL,
  `bed_number` varchar(50) DEFAULT NULL,
  `ward_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients_ipd`
--

INSERT INTO `patients_ipd` (`id`, `name`, `age`, `gender`, `doctor`, `contact`, `address`, `medical_history`, `admission_type`, `bed_number`, `ward_type`, `notes`, `created_at`) VALUES
('', 'reddy', 52, 'Male', 'tt', '44', 'dd', 'dd', 'IPD', NULL, NULL, NULL, '2025-01-09 12:06:24'),
('1', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-04 10:35:39'),
('2', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-04 11:11:02'),
('3', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-04 11:11:02'),
('4', 'reddy', 52, 'Male', 'tt', '44', 'dd', 'dd', 'IPD', NULL, NULL, NULL, '2025-01-04 11:11:20'),
('5', 'reddy', 52, 'Male', 'tt', '44', 'dd', 'dd', 'IPD', NULL, NULL, NULL, '2025-01-04 11:11:58'),
('6', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', '6', 'ICU', '', '2025-01-04 11:53:07'),
('7', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-07 04:54:53'),
('8', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-07 04:57:55'),
('9', 'raja', 10, 'Male', 'agarwal', '44334', 'fd', 'ehe', 'IPD', NULL, NULL, NULL, '2025-01-07 04:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `patients_opd`
--

CREATE TABLE `patients_opd` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `whatsapp_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `problem` varchar(255) NOT NULL,
  `doctor` varchar(255) NOT NULL,
  `referred_by` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `admission_type` enum('Casualty','OPD') NOT NULL,
  `medical_history` text NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `final_fee` decimal(10,2) GENERATED ALWAYS AS (`fee` - `fee` * `discount` / 100) STORED,
  `reports` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients_opd`
--

INSERT INTO `patients_opd` (`id`, `name`, `age`, `gender`, `guardian_name`, `contact`, `whatsapp_number`, `address`, `problem`, `doctor`, `referred_by`, `remarks`, `admission_type`, `medical_history`, `fee`, `discount`, `reports`, `created_at`, `updated_at`) VALUES
('OP250121000001', 'mani', 10, 'Male', 'raju', '9879879879', '2343243232', 'r', 'fdhs', 'agarwal', 'tata', 'ratna', 'Casualty', 'f', 333.00, 3.00, '', '2025-01-21 11:29:21', '2025-01-21 16:59:21'),
('OP250121000002', 'mani', 10, 'Male', 'raju', '9879879879', '56', 'yt', 'fdhs', 'agar', 'tata', 'ratna', 'OPD', 'j', 56.00, 6.00, '', '2025-01-21 11:29:55', '2025-01-27 10:22:54'),
('OP250127000003', 'raman', 33, 'Male', 'raju', '9879879879', '2343243232', 't', 't', 'sanjay', 'tata', 'ratna', 'Casualty', 'tr', 300.00, 1.00, '../../../assets/uploads/patient_reports/9cbc9333-49a1-422e-ba99-989ab22b925c.png,../../../assets/uploads/patient_reports/IT.pdf.jpg,../../../assets/uploads/patient_reports/CocoFarms Luxury Resort (2).pdf', '2025-01-27 06:19:12', '2025-01-27 11:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(1, 'bhavi', 'creations', 'bhavicreations@gmail.com', '600c304331ed6847dd108dea621d56ea', '2024-11-29 12:29:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors_list`
--
ALTER TABLE `doctors_list`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients_ipd`
--
ALTER TABLE `patients_ipd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients_opd`
--
ALTER TABLE `patients_opd`
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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `nurses`
--
ALTER TABLE `nurses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
