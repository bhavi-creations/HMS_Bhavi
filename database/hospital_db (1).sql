-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 11:43 AM
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
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `bed_number` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `status` enum('Available','Occupied','Under Maintenance') DEFAULT 'Available',
  `assigned_to_patient_id` varchar(20) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `ward_id`, `bed_number`, `gender`, `status`, `assigned_to_patient_id`, `is_deleted`) VALUES
(219, 5, '1', 'Male', 'Available', NULL, 0),
(220, 5, '2', 'Male', 'Available', NULL, 0),
(221, 5, '3', 'Male', 'Available', NULL, 0),
(222, 5, '4', 'Male', 'Available', NULL, 0),
(223, 5, '5', 'Male', 'Available', NULL, 0),
(224, 5, '6', 'Male', 'Available', NULL, 0),
(225, 5, '7', 'Male', 'Available', NULL, 0),
(226, 5, '8', 'Male', 'Available', NULL, 0),
(227, 5, '9', 'Male', 'Available', NULL, 0),
(228, 5, '10', 'Male', 'Available', NULL, 0),
(229, 5, '11', 'Male', 'Available', NULL, 0),
(230, 5, '12', 'Male', 'Available', NULL, 0),
(231, 5, '13', 'Male', 'Available', NULL, 0),
(232, 5, '14', 'Male', 'Available', NULL, 0),
(233, 5, '15', 'Male', 'Available', NULL, 0),
(234, 5, '16', 'Male', 'Available', NULL, 0),
(235, 5, '17', 'Male', 'Available', NULL, 0),
(236, 5, '18', 'Male', 'Available', NULL, 0),
(237, 5, '19', 'Male', 'Available', NULL, 0),
(238, 5, '20', 'Male', 'Available', NULL, 0),
(239, 5, '21', 'Male', 'Available', NULL, 0),
(240, 5, '22', 'Male', 'Available', NULL, 0),
(241, 5, '23', 'Male', 'Available', NULL, 0),
(264, 5, '1', 'Female', 'Available', NULL, 0),
(265, 5, '2', 'Female', 'Available', NULL, 0),
(266, 5, '3', 'Female', 'Available', NULL, 0),
(267, 5, '4', 'Female', 'Available', NULL, 0),
(268, 5, '5', 'Female', 'Available', NULL, 0),
(269, 5, '6', 'Female', 'Available', NULL, 0),
(270, 5, '7', 'Female', 'Available', NULL, 0),
(271, 5, '8', 'Female', 'Available', NULL, 0),
(272, 5, '9', 'Female', 'Available', NULL, 0),
(273, 5, '10', 'Female', 'Available', NULL, 0),
(274, 5, '11', 'Female', 'Available', NULL, 0),
(275, 5, '12', 'Female', 'Available', NULL, 0),
(276, 5, '13', 'Female', 'Available', NULL, 0),
(277, 5, '14', 'Female', 'Available', NULL, 0),
(278, 5, '15', 'Female', 'Available', NULL, 0),
(279, 5, '16', 'Female', 'Available', NULL, 0),
(280, 5, '17', 'Female', 'Available', NULL, 0),
(281, 5, '18', 'Female', 'Available', NULL, 0),
(282, 5, '19', 'Female', 'Available', NULL, 0),
(283, 5, '20', 'Female', 'Available', NULL, 0),
(284, 5, '21', 'Female', 'Available', NULL, 0),
(285, 5, '22', 'Female', 'Available', NULL, 0);

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
('DOC20250514-0010', 'mani', '1997-01-16', 'Male', '9239423432', 'abhi@gmail.com', 'heart ', 'mbbs', 2, '39858', '2025-05-01', 'cardio', '2 : 6', 'ahbo@gmail.com', '$2y$10$suPxHL7rfXonIRDusbQhbey6hUJrxdlFa6zlVQeju/.t9j3D2SUA2', 'wef', '../../../assets/uploads/doctors_profiles/495.jpg', 20000.00, '324325222222', '243243', '23', '4', '../../../assets/uploads/doctors_profiles/1747196819_ai-generated-picture-of-a-tiger-walking-in-the-forest-photo.jpg,../../../assets/uploads/doctors_profiles/1747196819_A1.png', '2025-05-14 00:56:59', '2025-05-14 00:56:59');

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
  `ipd_id` varchar(20) NOT NULL,
  `opd_casualty_id` varchar(20) NOT NULL,
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
  `medical_history` text NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `final_fee` decimal(10,2) GENERATED ALWAYS AS (`fee` - `fee` * `discount` / 100) STORED,
  `reports` varchar(255) NOT NULL,
  `admission_date` datetime DEFAULT NULL,
  `ward_number` varchar(50) DEFAULT NULL,
  `bed_number` varchar(50) DEFAULT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `discharge_status` enum('Pending','Discharged','Deceased') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients_ipd`
--

INSERT INTO `patients_ipd` (`ipd_id`, `opd_casualty_id`, `name`, `age`, `gender`, `guardian_name`, `contact`, `whatsapp_number`, `address`, `problem`, `doctor`, `referred_by`, `remarks`, `medical_history`, `fee`, `discount`, `reports`, `admission_date`, `ward_number`, `bed_number`, `discharge_date`, `discharge_status`, `created_at`, `updated_at`) VALUES
('IP250516000001', 'OP250121000002', 'mani', 10, 'Male', 'raju', '9879879879', '56', 'fggr', 'fdhs', 'agar', 'tata', 'ratna', 'j', 56.00, 6.00, '', '2025-05-28 00:00:00', '52', '3', NULL, '', '2025-05-16 06:11:26', '2025-05-16 16:33:36'),
('IP250516000002', 'OP250121000002', 'mani', 10, 'Male', 'raju', '9879879879', '56', 'yt', 'fdhs', 'agar', 'tata', 'ratna', 'j', 56.00, 6.00, '', '2025-05-18 14:01:00', '5', '22', NULL, '', '2025-05-16 06:16:50', '2025-05-16 17:04:19'),
('IP250516000003', 'OP250121000001', 'mani', 34, 'Male', 'raju', '9879879879', '2343243232', 'r', 'fdhs', 'agarwal', 'tata', 'ratna', 'f', 333.00, 3.00, '', '2025-05-24 00:00:00', '', '', NULL, '', '2025-05-16 07:01:01', '2025-05-16 16:25:28'),
('IP250516000004', 'OP250127000003', 'raman', 33, 'Male', 'raju', '9879879879', '2343243232', 't', 't', 'sanjay', 'tata', 'ratna', 'tr', 300.00, 1.00, '../../../assets/uploads/patient_reports/9cbc9333-49a1-422e-ba99-989ab22b925c.png,../../../assets/uploads/patient_reports/IT.pdf.jpg,../../../assets/uploads/patient_reports/CocoFarms Luxury Resort (2).pdf,assets/uploads/patient_reports/682aaf3122e29_ai-gen', '2025-05-01 05:04:00', '2', '22', NULL, '', '2025-05-16 07:01:05', '2025-05-19 09:42:53'),
('IP250516000005', 'OP250514000005', 'Car Rentals', 10, 'Male', 'raju', '9879879879', '2343243232', 'fd', 'fdhs', 'agarwal', 'tata', 'ratna', 'gg', 222.00, 2.00, '', '2025-05-19 09:39:00', '5', '87', NULL, '', '2025-05-16 07:18:01', '2025-05-19 10:28:24'),
('IP250516000006', 'OP250514000004', 'Car Rentals', 10, 'Male', 'raju', '9879879879', '2343243232', 'h', 'fdhs', 'sanjay', 'tata', 'ratna', 'h', 555.00, 5.00, '', NULL, NULL, NULL, NULL, 'Pending', '2025-05-16 07:22:19', '2025-05-16 12:52:19'),
('IP250516000007', 'OP250516000007', 'rao', 10, 'Male', 'raju', '8278272340', '2343243232', 'f', 'fdhs', 'agarwal', 'tata', 'ratna', 'fd', 434.00, 4.00, '', '2025-05-16 09:28:00', '44', '4', '2025-05-19 10:28:00', 'Discharged', '2025-05-16 07:28:23', '2025-05-19 10:28:48'),
('IP250516000008', 'OP250514000006', 'fasa', 10, 'Male', 'raju', '9879879879', '2343243232', 'v', 'fdhs', 'agarwal', 'tata', 'ratna', 'f', 333.00, 3.00, '../../../assets/uploads/patient_reports/coco sliders (2).png,../../../assets/uploads/patient_reports/12345.pdf', '2025-05-17 00:00:00', '9', '99', NULL, '', '2025-05-16 11:04:11', '2025-05-16 16:41:59'),
('IP250516000009', 'OP250516000008', 'dhanush', 22, 'Male', 'raju', '9879879879', '56', 'dhnejnj', '23423', 'agarwal', 'tata', 'ratna', 'non', 344.00, 2.00, '../../../assets/uploads/patient_reports/pu.png,../../../assets/uploads/patient_reports/NUEROSTAR BROUCHER new 1.pdf,../../../assets/uploads/patient_reports/Hand-drawn-cartoon-clinical-thermometer-plant-gynecology-illustration-business-doctor_166133_wh1.pn', '2025-05-16 17:15:00', '56', '6', NULL, '', '2025-05-16 11:45:05', '2025-05-20 17:31:21'),
('IP250520000001', 'OP250520000011', 'gg rag', 0, 'Male', '', '', '', '', '', '', '', '', 'db', 0.00, 0.00, 'vk logo 6_1747723285.jpg,stock-vector-alphabet-letters-icon-logo-vk-or-kv-monogram-2203519181_1747723285.jpg,B_organized_1747723285.pdf', '2025-05-20 13:30:00', '', '22', NULL, '', '2025-05-20 10:12:14', '2025-05-20 17:56:25'),
('IP250521000001', 'OP250520000009', 'baga', 10, 'Male', 'raju', '9879879879', '2343243232', 'rr', 't', 'agar', 'tata', 'ratna', 'f', 444.00, 4.00, 'vk logo 6_1747719456.jpg,th_1747719456.jpg', '2025-05-21 06:07:00', '2', '22', NULL, 'Pending', '2025-05-21 04:07:01', '2025-05-21 09:38:13');

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
('OP250121000001', 'mani', 34, 'Male', 'raju', '9879879879', '2343243232', 'r', 'fdhs', 'agarwal', 'tata', 'ratna', 'Casualty', 'f', 333.00, 3.00, '', '2025-01-21 11:29:21', '2025-05-14 16:15:45'),
('OP250121000002', 'mani', 10, 'Male', 'raju', '9879879879', '56', 'yt', 'fdhs', 'agar', 'tata', 'ratna', 'OPD', 'j', 56.00, 6.00, '', '2025-01-21 11:29:55', '2025-01-27 10:22:54'),
('OP250127000003', 'raman', 33, 'Male', 'raju', '9879879879', '2343243232', 't', 't', 'sanjay', 'tata', 'ratna', 'Casualty', 'tr', 300.00, 1.00, '../../../assets/uploads/patient_reports/9cbc9333-49a1-422e-ba99-989ab22b925c.png,../../../assets/uploads/patient_reports/IT.pdf.jpg,../../../assets/uploads/patient_reports/CocoFarms Luxury Resort (2).pdf', '2025-01-27 06:19:12', '2025-01-27 11:49:12'),
('OP250514000004', 'Car Rentals', 10, 'Male', 'raju', '9879879879', '2343243232', 'h', 'fdhs', 'sanjay', 'tata', 'ratna', 'OPD', 'h', 555.00, 5.00, '', '2025-05-14 09:52:29', '2025-05-14 15:22:29'),
('OP250514000005', 'prem', 10, 'Male', 'raju', '9879879879', '2343243232', 'fd', 'fdhs', 'agarwal', 'tata', 'ratna', 'OPD', 'ggh', 222.00, 2.00, '../../../assets/uploads/patient_reports/stock-vector-alphabet-letters-icon-logo-vk-or-kv-monogram-2203519181.jpg,../../../assets/uploads/patient_reports/VK.png', '2025-05-14 09:52:53', '2025-05-19 12:19:08'),
('OP250514000006', 'fasa', 10, 'Male', 'raju', '9879879879', '2343243232', 'v', 'fdhs', 'agarwal', 'tata', 'ratna', 'OPD', 'f', 333.00, 3.00, '../../../assets/uploads/patient_reports/coco sliders (2).png,../../../assets/uploads/patient_reports/67.pdf,../../../assets/uploads/patient_reports/12345.pdf', '2025-05-14 10:54:44', '2025-05-14 16:24:44'),
('OP250516000007', 'rao', 10, 'Male', 'raju', '8278272340', '2343243232', 'f', 'fdhs', 'agarwal', 'tata', 'ratna', 'Casualty', 'fd', 434.00, 4.00, '', '2025-05-16 07:28:16', '2025-05-16 12:58:16'),
('OP250516000008', 'dhanush', 22, 'Male', 'raju', '9879879879', '56', 'dhnejnj', '23423', 'agarwal', 'tata', 'ratna', 'Casualty', 'non', 344.00, 2.00, '../../../assets/uploads/patient_reports/pu.png,../../../assets/uploads/patient_reports/NUEROSTAR BROUCHER new 1.pdf,../../../assets/uploads/patient_reports/Hand-drawn-cartoon-clinical-thermometer-plant-gynecology-illustration-business-doctor_166133_wh1.pn', '2025-05-16 11:44:36', '2025-05-16 17:14:36'),
('OP250520000009', 'baga', 10, 'Male', 'raju', '9879879879', '2343243232', 'rr', 't', 'agar', 'tata', 'ratna', 'OPD', 'f', 444.00, 4.00, 'vk logo 6_1747719456.jpg,th_1747719456.jpg', '2025-05-20 05:37:36', '2025-05-20 11:07:36'),
('OP250520000010', 'rajamama', 67, 'Male', 'raju', '554563245', '2343243232', 'h', 'fdhs', 'agarwal', 'tata', 'ratna', 'Casualty', '5h', 454.00, 5.00, '', '2025-05-20 06:39:04', '2025-05-20 12:09:04'),
('OP250520000011', 'gg rag', 33, 'Male', 'raju', '9879879879', '2343243232', 'tb', 'fdhs', 'sanjay', 'tata', 'ratna', 'Casualty', 'db', 3435.00, 3.00, 'vk logo 6_1747723285.jpg,stock-vector-alphabet-letters-icon-logo-vk-or-kv-monogram-2203519181_1747723285.jpg,B_organized_1747723285.pdf', '2025-05-20 06:41:25', '2025-05-20 17:30:11');

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

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `id` int(11) NOT NULL,
  `ward_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`id`, `ward_name`) VALUES
(2, 'Neonatal Intensive Care Unit (NICU)'),
(3, 'Pediatric Ward'),
(4, 'Maternity Ward'),
(5, 'Labor and Delivery (L&D) Ward'),
(6, 'Operation Theatre (OT) / Surgical Ward'),
(9, 'Observation Ward');

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
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ward_id` (`ward_id`);

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
  ADD PRIMARY KEY (`ipd_id`),
  ADD KEY `opd_casualty_id` (`opd_casualty_id`);

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
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

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
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`);

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `patients_ipd`
--
ALTER TABLE `patients_ipd`
  ADD CONSTRAINT `patients_ipd_ibfk_1` FOREIGN KEY (`opd_casualty_id`) REFERENCES `patients_opd` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
