-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2022 at 07:48 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ucas`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `mitem_id` int(11) NOT NULL,
  `cart_amount` int(11) NOT NULL,
  `cart_remark` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mitem`
--

CREATE TABLE `mitem` (
  `mitem_id` int(11) NOT NULL,
  `mitem_name` varchar(50) NOT NULL,
  `mitem_price` decimal(6,2) NOT NULL,
  `mitem_status` tinyint(1) NOT NULL,
  `mitem_pic` varchar(100) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `odr`
--

CREATE TABLE `odr` (
  `odr_id` int(11) NOT NULL,
  `odr_ref` varchar(30) NOT NULL,
  `odr_placedtime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `odr_status` varchar(20) NOT NULL,
  `odr_compltime` datetime NOT NULL,
  `odr_cxldtime` datetime NOT NULL,
  `odr_rate_status` tinyint(4) NOT NULL,
  `store_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `odr_detail`
--

CREATE TABLE `odr_detail` (
  `odr_detail_id` int(11) NOT NULL,
  `odr_id` int(11) NOT NULL,
  `mitem_id` int(11) NOT NULL,
  `odr_detail_amount` int(11) NOT NULL,
  `odr_detail_price` decimal(6,2) NOT NULL,
  `odr_detail_remark` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` varchar(10) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `rating_value` int(1) NOT NULL,
  `rating_comment` varchar(250) DEFAULT NULL,
  `rating_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(50) NOT NULL,
  `store_location` varchar(50) NOT NULL,
  `store_openhour` time NOT NULL,
  `store_closehour` time NOT NULL,
  `store_status` tinyint(1) NOT NULL,
  `store_pic` varchar(100) NOT NULL,
  `store_rating` decimal(10,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_pwd` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_fname` varchar(50) NOT NULL,
  `user_lname` varchar(50) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `store_id-cart_table` (`store_id`),
  ADD KEY `user_id-cart_table` (`user_id`),
  ADD KEY `mitem_id-cart_table` (`mitem_id`);

--
-- Indexes for table `mitem`
--
ALTER TABLE `mitem`
  ADD PRIMARY KEY (`mitem_id`),
  ADD KEY `store_id-mitem_table` (`store_id`);

--
-- Indexes for table `odr`
--
ALTER TABLE `odr`
  ADD PRIMARY KEY (`odr_id`),
  ADD KEY `payment_id-odr_table` (`payment_id`),
  ADD KEY `rating_id-odr_table` (`rating_id`),
  ADD KEY `store_id-odr_table` (`store_id`),
  ADD KEY `user_id-odr_table` (`user_id`);

--
-- Indexes for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD PRIMARY KEY (`odr_detail_id`),
  ADD KEY `odr_id-odr_details_table` (`odr_id`),
  ADD KEY `mitem_id-odr_details_table` (`mitem_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id-payment-table` (`user_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `store_id-user_table` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mitem`
--
ALTER TABLE `mitem`
  MODIFY `mitem_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `odr`
--
ALTER TABLE `odr`
  MODIFY `odr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `odr_detail`
--
ALTER TABLE `odr_detail`
  MODIFY `odr_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `mitem_id-cart_table` FOREIGN KEY (`mitem_id`) REFERENCES `mitem` (`mitem_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_id-cart_table` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id-cart_table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `mitem`
--
ALTER TABLE `mitem`
  ADD CONSTRAINT `store_id-mitem_table` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `odr`
--
ALTER TABLE `odr`
  ADD CONSTRAINT `payment_id-odr_table` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating_id-odr_table` FOREIGN KEY (`rating_id`) REFERENCES `rating` (`rating_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `store_id-odr_table` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id-odr_table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD CONSTRAINT `mitem_id-odr_details_table` FOREIGN KEY (`mitem_id`) REFERENCES `mitem` (`mitem_id`),
  ADD CONSTRAINT `odr_id-odr_details_table` FOREIGN KEY (`odr_id`) REFERENCES `odr` (`odr_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `user_id-payment-table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `store_id-user_table` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
