-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 05:45 AM
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
-- Database: `projectpart2`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `jobRef` varchar(20) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `streetAddress` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skill1` varchar(50) DEFAULT NULL,
  `skill2` varchar(50) DEFAULT NULL,
  `skill3` varchar(50) DEFAULT NULL,
  `skill4` varchar(50) DEFAULT NULL,
  `otherSkills` varchar(200) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `jobRef`, `firstName`, `lastName`, `dob`, `gender`, `streetAddress`, `suburb`, `state`, `postcode`, `email`, `phone`, `skill1`, `skill2`, `skill3`, `skill4`, `otherSkills`, `status`) VALUES
(1, 'CE-VIC-1025', 'sdssdsd', 'dsdsds', '2002-12-12', 'Female', 'dssddad', 'sadsadad', 'VIC', '3008', 'hienvgh@gmail.com', '904771558', 'Networking', 'Cybersecurity', NULL, NULL, 'dsdsdsdsds', 'New'),
(2, 'PE-QLD-1243', 'sdss', 'dsdsdsd', '2002-12-13', 'Other', '123 titan street', 'dsdsdsd', 'ACT', '0038', 'haireif@gmail.com', '904771558', 'Programming', NULL, NULL, NULL, '', 'New'),
(3, 'CC-TAS-773', 'Vu', 'Vui', '2888-03-10', 'Male', '67 sigma street', 'what town', 'SA', '5111', 'hairyeif@gmail.com', '969767065', NULL, NULL, NULL, NULL, 'uh i have uh gaming laptop pls accept', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `reports_to` varchar(100) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_title`, `reference_number`, `company_name`, `location`, `salary_range`, `reports_to`, `job_type`, `description`, `image_url`) VALUES
(1, 'Cloud Engineer', 'CE-VIC-1025', 'TechCorp Australia', 'Melbourne, VIC', '$90,000 – $115,000 / year', 'Senior Cloud Architect', 'Full-time', 'We are seeking a skilled and motivated Cloud Engineer to join our Melbourne-based technology team.\r\n\r\nKey Responsibilities:\r\n- Deploy, manage, and monitor cloud infrastructure and automation tools.\r\n- Collaborate with cross-functional teams to ensure reliable cloud operations.\r\n\r\nEssential Requirements:\r\n- Hands-on experience with AWS, Azure, or GCP services.\r\n- Knowledge of CI/CD pipelines and automation tools.\r\n\r\nPreferable Requirements:\r\n- Experience with Kubernetes or Terraform.', 'https://106215431.github.io/COS10026-Project-Part-2/images/cloud-engineer-2.jpg'),
(2, 'Chief Technology Officer (CTO)', 'CTO-VIC-78', 'TechCorp Australia', 'Melbourne, VIC', '$180,000 – $250,000 / year', 'Chief Executive Officer (CEO)', 'Full-time', 'We are seeking an experienced and visionary Chief Technology Officer (CTO) to lead the company’s innovation strategy.\r\n\r\nKey Responsibilities:\r\n- Oversee the technology strategy, ensuring scalability and security of digital systems.\r\n- Lead engineering teams and drive innovation across all technical departments.\r\n\r\nEssential Requirements:\r\n- Proven leadership experience in a senior technology role.\r\n- Strong understanding of cloud computing and software architecture.\r\n\r\nPreferable Requirements:\r\n- Background in AI or enterprise-scale system modernization.', 'https://106215431.github.io/COS10026-Project-Part-2/images/cloud.png'),
(3, 'Platform Engineer', 'PE-QLD-1243', 'TechCorp Australia', 'Queensland, QLD', '$100,000 – $120,000 / year', 'DevOps Manager', 'Full-time', 'We are hiring a Platform Engineer to build and maintain scalable internal systems supporting cloud applications.\r\n\r\nKey Responsibilities:\r\n- Develop and maintain CI/CD pipelines and container-based infrastructure.\r\n- Enhance system performance through automation and observability tools.\r\n\r\nEssential Requirements:\r\n- Experience with Docker, Kubernetes, and cloud environments.\r\n\r\nPreferable Requirements:\r\n- Knowledge of Terraform or scripting in Python/Bash.', 'https://106215431.github.io/COS10026-Project-Part-2/images/cloud-engineer.jpg'),
(4, 'Cloud Architect / Solutions Architect', 'CA-ACT-2231', 'TechCorp Australia', 'Australian Capital Territory, ACT', '$130,000 – $160,000 / year', 'Head of Cloud Engineering', 'Full-time', 'We are seeking a Cloud Architect to design secure, scalable, and cost-efficient cloud systems.\r\n\r\nKey Responsibilities:\r\n- Design and implement multi-cloud architectures aligned with business goals.\r\n- Provide technical guidance to cloud engineering teams.\r\n\r\nEssential Requirements:\r\n- Experience designing cloud infrastructure at enterprise level.\r\n\r\nPreferable Requirements:\r\n- AWS or Azure Solutions Architect certification.', 'https://106215431.github.io/COS10026-Project-Part-2/images/aws.png'),
(5, 'Cloud Consultant', 'CC-TAS-773', 'TechCorp Australia', 'Tasmania, TAS', '$110,000 – $140,000 / year', 'Principal Cloud Architect', 'Full-time', 'We are looking for a Cloud Consultant to help clients plan, migrate, and optimize their cloud solutions.\r\n\r\nKey Responsibilities:\r\n- Advise clients on cloud adoption, cost management, and best practices.\r\n- Prepare reports and documentation for technical recommendations.\r\n\r\nEssential Requirements:\r\n- Strong knowledge of AWS or Azure cloud architecture.\r\n\r\nPreferable Requirements:\r\n- Cloud certification (AWS Solutions Architect or Azure Expert).', 'https://106215431.github.io/COS10026-Project-Part-2/images/cloud-consultant.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
