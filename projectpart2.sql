-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 01, 2025 lúc 05:18 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `projectpart2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `eoi`
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
-- Đang đổ dữ liệu cho bảng `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `jobRef`, `firstName`, `lastName`, `dob`, `gender`, `streetAddress`, `suburb`, `state`, `postcode`, `email`, `phone`, `skill1`, `skill2`, `skill3`, `skill4`, `otherSkills`, `status`) VALUES
(1, 'CE-VIC-1025', 'sdssdsd', 'dsdsds', '2002-12-12', 'Female', 'dssddad', 'sadsadad', 'VIC', '3008', 'hienvgh@gmail.com', '904771558', 'Networking', 'Cybersecurity', NULL, NULL, 'dsdsdsdsds', 'New'),
(2, 'PE-QLD-1243', 'sdss', 'dsdsdsd', '2002-12-13', 'Other', '123 titan street', 'dsdsdsd', 'ACT', '0038', 'haireif@gmail.com', '904771558', 'Programming', NULL, NULL, NULL, '', 'New'),
(3, 'CC-TAS-773', 'Vu', 'Vui', '2888-03-10', 'Male', '67 sigma street', 'what town', 'SA', '5111', 'hairyeif@gmail.com', '969767065', NULL, NULL, NULL, NULL, 'uh i have uh gaming laptop pls accept', 'New');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
