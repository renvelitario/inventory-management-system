-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2023 at 04:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ims_customers`
--

CREATE TABLE `ims_customers` (
  `cust_id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `address` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contact_no` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ims_orders`
--

CREATE TABLE `ims_orders` (
  `order_id` int(11) NOT NULL,
  `product_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ims_products`
--

CREATE TABLE `ims_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` enum('active','inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ims_purchases`
--

CREATE TABLE `ims_purchases` (
  `purchase_id` int(11) NOT NULL,
  `product_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `quantity` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ims_users`
--

CREATE TABLE `ims_users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `acc_type` enum('Admin','User') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` enum('Active','Inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ims_users`
--

INSERT INTO `ims_users` (`user_id`, `email`, `password`, `username`, `acc_type`, `status`) VALUES
(1, 'admin@admin.com', '$2y$10$fvWViJnP/V1axKL7PbK9x.LUJQ5AxWYyr1.CDsTwtzLF903ZOEfM6', 'Admin', 'Admin', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ims_customers`
--
ALTER TABLE `ims_customers`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `ims_orders`
--
ALTER TABLE `ims_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `ims_products`
--
ALTER TABLE `ims_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `ims_purchases`
--
ALTER TABLE `ims_purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `ims_users`
--
ALTER TABLE `ims_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ims_customers`
--
ALTER TABLE `ims_customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Dumping data for table `ims_customers`
--
INSERT INTO `ims_customers` (`cust_id`, `name`, `address`, `contact_no`) VALUES
(1, 'Juan Dela Cruz', 'San Pedro, Laguna', '09123456789'),
(2, 'Maria Villanueva', 'Dasmarinas, Cavite', '09987654321');

--
-- AUTO_INCREMENT for table `ims_orders`
--
ALTER TABLE `ims_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dumping data for table `ims_orders`
--
INSERT INTO `ims_orders` (`order_id`, `product_id`, `customer_id`, `quantity`, `order_date`) VALUES
(1, '1', 1, 2, '2023-07-09 10:00:00'),
(2, '2', 2, 1, '2023-07-09 11:30:00');

--
-- AUTO_INCREMENT for table `ims_products`
--
ALTER TABLE `ims_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Dumping data for table `ims_products`
--
INSERT INTO `ims_products` (`product_id`, `product_name`, `quantity`, `price`, `status`) VALUES
(1, 'Wireless Mouse', 50, 25.99, 'active'),
(2, 'Mechanical Keyboard', 30, 89.50, 'active'),
(3, '24-inch Monitor', 15, 149.00, 'active');

--
-- AUTO_INCREMENT for table `ims_purchases`
--
ALTER TABLE `ims_purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dumping data for table `ims_purchases`
--
INSERT INTO `ims_purchases` (`purchase_id`, `product_id`, `quantity`, `purchase_date`) VALUES
(1, '1', '100', '2023-07-01 09:00:00'),
(2, '2', '50', '2023-07-02 14:15:00');

--
-- AUTO_INCREMENT for table `ims_users`
--
ALTER TABLE `ims_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
