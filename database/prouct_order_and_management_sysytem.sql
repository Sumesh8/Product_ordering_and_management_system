-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2023 at 09:30 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prouct_order_and_management_sysytem`
--

-- --------------------------------------------------------

--
-- Table structure for table `define_free_issues`
--

CREATE TABLE `define_free_issues` (
  `free_issues_label` int(4) UNSIGNED ZEROFILL NOT NULL,
  `type` enum('flat','multiple') NOT NULL,
  `product_code` int(4) UNSIGNED ZEROFILL NOT NULL,
  `purchase_quantity` int(11) NOT NULL,
  `free_quantity` int(11) NOT NULL,
  `lower_limit` int(11) NOT NULL,
  `upper_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `define_free_issues`
--

INSERT INTO `define_free_issues` (`free_issues_label`, `type`, `product_code`, `purchase_quantity`, `free_quantity`, `lower_limit`, `upper_limit`) VALUES
(0001, 'flat', 0002, 1000, 1, 1000, 2147483647),
(0003, 'multiple', 0001, 100, 10, 1000, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_08_16_060206_user', 1),
(3, '2023_08_16_061826_user', 2),
(4, '2023_08_16_061939_user', 3),
(5, '2023_08_16_062027_user', 4),
(6, '2023_08_16_062335_drop_columns_from_user', 5);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_number` int(4) UNSIGNED ZEROFILL NOT NULL,
  `username` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `net_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_number`, `username`, `order_date`, `order_time`, `net_amount`) VALUES
(0001, 'admin', '2023-08-18', '18:09:04', '310000.00'),
(0002, 'admin', '2023-08-18', '18:10:32', '3289985.00'),
(0003, 'admin', '2023-08-18', '18:11:46', '20000.00');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `placing_order`
--

CREATE TABLE `placing_order` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `free_quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_code` int(4) UNSIGNED ZEROFILL NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `free_product` enum('yes','no') NOT NULL,
  `has_discount` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_code`, `product_name`, `price`, `expiry_date`, `free_product`, `has_discount`) VALUES
(0001, 'Coca-Cola', '200.00', '2023-08-31', 'yes', 'no'),
(0002, 'Fanta', '1000.00', '2023-08-25', 'yes', 'no'),
(0003, 'pepsi', '120.00', '2023-08-31', 'yes', 'no'),
(0005, 'sunlight', '450.00', '2023-08-31', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `products_discount`
--

CREATE TABLE `products_discount` (
  `discount_label` int(4) UNSIGNED ZEROFILL NOT NULL,
  `type` enum('flat','multiple') NOT NULL,
  `product_code` int(4) UNSIGNED ZEROFILL NOT NULL,
  `purchase_quantity` int(11) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `lower_limit` int(11) NOT NULL,
  `upper_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products_discount`
--

INSERT INTO `products_discount` (`discount_label`, `type`, `product_code`, `purchase_quantity`, `discount`, `lower_limit`, `upper_limit`) VALUES
(0002, 'multiple', 0005, 100, '2.50', 1000, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `purchased_product`
--

CREATE TABLE `purchased_product` (
  `id` int(11) NOT NULL,
  `order_number` int(4) UNSIGNED ZEROFILL NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `free_quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `discount_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchased_product`
--

INSERT INTO `purchased_product` (`id`, `order_number`, `username`, `product_name`, `product_code`, `unit_price`, `quantity`, `free_quantity`, `amount`, `discount`, `discount_quantity`) VALUES
(50, 0001, 'admin', 'pepsi', '0003', '120.00', 1000, 0, '120000.00', '0.00', 0),
(51, 0001, 'admin', 'Coca-Cola', '0001', '200.00', 500, 0, '100000.00', '0.00', 0),
(52, 0001, 'admin', 'sunlight', '0005', '450.00', 200, 0, '90000.00', '0.00', 0),
(53, 0002, 'admin', 'Fanta', '0002', '1000.00', 1000, 1, '1000000.00', '0.00', 0),
(54, 0002, 'admin', 'Coca-Cola', '0001', '200.00', 200, 0, '40000.00', '0.00', 0),
(55, 0002, 'admin', 'sunlight', '0005', '450.00', 5000, 0, '2249985.00', '15.00', 6),
(56, 0003, 'admin', 'Coca-Cola', '0001', '200.00', 100, 0, '20000.00', '0.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_code` int(4) UNSIGNED ZEROFILL NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `type` enum('admin','non admin') NOT NULL,
  `password` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_code`, `name`, `address`, `contact_number`, `type`, `password`, `username`) VALUES
(0001, 'admin', 'Susadi stores, Blackpool junction, Nuwara Eliya ', '0712712604', 'admin', 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `define_free_issues`
--
ALTER TABLE `define_free_issues`
  ADD PRIMARY KEY (`free_issues_label`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_number`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `placing_order`
--
ALTER TABLE `placing_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_code`);

--
-- Indexes for table `products_discount`
--
ALTER TABLE `products_discount`
  ADD PRIMARY KEY (`discount_label`);

--
-- Indexes for table `purchased_product`
--
ALTER TABLE `purchased_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `placing_order`
--
ALTER TABLE `placing_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `purchased_product`
--
ALTER TABLE `purchased_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_code` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `define_free_issues`
--
ALTER TABLE `define_free_issues`
  ADD CONSTRAINT `define_free_issues_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `product` (`product_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
