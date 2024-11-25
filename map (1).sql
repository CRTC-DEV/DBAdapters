-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 22, 2024 lúc 03:25 AM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `map`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `signagedevicetouch`
--

CREATE TABLE `signagedevicetouch` (
  `SignageId` int(11) NOT NULL,
  `DeviceTouchScreenId` int(11) NOT NULL,
  `Status` tinyint(1) DEFAULT 1 COMMENT '1 show, 2 Updated, 2 Disable',
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiDate` datetime DEFAULT NULL,
  `UserId` int(11) DEFAULT 1,
  `OrderIndex` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `signagemapitem`
--

CREATE TABLE `signagemapitem` (
  `SignageId` int(11) NOT NULL,
  `MapItemId` int(11) NOT NULL,
  `Status` tinyint(1) DEFAULT 1 COMMENT '1 show, 2 Updated, 2 Disable',
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiDate` datetime DEFAULT NULL,
  `UserId` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `signages`
--

CREATE TABLE `signages` (
  `Id` int(11) NOT NULL,
  `CadId` varchar(12) DEFAULT NULL,
  `TitleId` int(11) DEFAULT NULL,
  `Longitudes` decimal(10,0) DEFAULT NULL,
  `Latitudes` decimal(10,0) DEFAULT NULL,
  `Status` tinyint(1) DEFAULT 1 COMMENT '1 show, 2 Updated, 2 Disable',
  `CreatedDate` datetime DEFAULT NULL,
  `ModifiDate` datetime DEFAULT NULL,
  `UserId` int(11) DEFAULT 1,
  `Rank` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `signagedevicetouch`
--
ALTER TABLE `signagedevicetouch`
  ADD PRIMARY KEY (`SignageId`,`DeviceTouchScreenId`);

--
-- Chỉ mục cho bảng `signagemapitem`
--
ALTER TABLE `signagemapitem`
  ADD PRIMARY KEY (`SignageId`,`MapItemId`);

--
-- Chỉ mục cho bảng `signages`
--
ALTER TABLE `signages`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `signages`
--
ALTER TABLE `signages`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
