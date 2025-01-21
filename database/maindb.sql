-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:40 PM
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
-- Database: `maindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'Admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(3, 3, 1, 'Men&#39;s T-Shirt', 600, 1, 'tshirt-img.png'),
(4, 3, 3, 'Shirt', 300, 1, 'shirt.png'),
(13, 2, 4, 'tanga', 10000, 1, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg'),
(24, 0, 1, 'Men\'s T-Shirt', 700, 1, 'tshirt-img.png'),
(25, 4, 4, 'tanga', 10000, 2, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg'),
(47, 0, 4, 'tanga', 10000, 1, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(5, 2, 'John Carlo Camarao', '0975224440', 'ako@gmail.com', 'cash on delivery', 'flat no. siempreviva sur mallig, purok 4, isabela, Manila, Philippines - 3323', 'Men\'s T-Shirt (600 x 1) - Shirt (300 x 1) - ', 900, '2024-12-09', 'pending'),
(6, 2, 'carlo', '123456789', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'Men\'s T-Shirt (700 x 2) - ', 1400, '2024-12-09', 'pending'),
(7, 2, 'carlo', '123456789', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'tanga (10000 x 1) - ', 10000, '2024-12-09', 'pending'),
(8, 2, 'carlo', '0975224440', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'tanga (10000 x 1) - ', 10000, '2024-12-09', 'pending'),
(9, 2, 'raema', '1234567899', 'ako@gmail.com', 'cash on delivery', 'flat no. siempreviva sur mall, cubangcubang street, purok 6, isabela, mallig, Philippines - 2233', 'tanga (10000 x 2) - ', 20000, '2024-12-09', 'pending'),
(10, 1, 'carlo', '0975224440', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'carlo (30000 x 1) - tanga (10000 x 1) - Men\'s T-Shirt (700 x 1) - ', 40700, '2024-12-09', 'pending'),
(11, 1, 'carlo', '0975224440', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'tanga (10000 x 1) - ', 10000, '2024-12-09', 'pending'),
(12, 1, 'carlo', '0975224440', 'ako@gmail.com', 'cash on delivery', 'flat no. 123, purok 4, isabela, mallig, Philippines - 2233', 'tanga (10000 x 3) - carlo (30000 x 2) - Men\'s T-Shirt (700 x 4) - ', 92800, '2024-12-09', 'pending'),
(13, 5, 'john', '0975354632', 'camarao@gmail.com', 'cash on delivery', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '<br />\r\n<b>Warning</b>:  Undefined variable $total_products in <b>C:\\xamppp\\htdocs\\CarloShopping\\checkout.php</b> on line <b>65</b><br />\r\n', 0, '2024-12-09', 'pending'),
(14, 5, 'john', '0975354632', 'camarao@gmail.com', 'cash on delivery', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '3', 31400, '2024-12-09', 'pending'),
(32, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '0', 0, '2024-12-09', 'pending'),
(33, 5, 'john', '0975354632', 'camarao@gmail.com', 'credit card', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '0', 0, '2024-12-09', 'pending'),
(34, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '2', 10700, '2024-12-09', 'pending'),
(35, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '2', 1400, '2024-12-09', 'pending'),
(36, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '3', 50000, '2024-12-09', 'pending'),
(37, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '2', 1400, '2024-12-09', 'pending'),
(38, 5, 'john', '0975354632', 'camarao@gmail.com', 'cash on delivery', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '3', 2100, '2024-12-09', 'pending'),
(39, 5, 'john', '0975354632', 'camarao@gmail.com', 'cash on delivery', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '1', 10000, '2024-12-09', 'pending'),
(40, 5, 'john', '0975354632', 'camarao@gmail.com', 'cash on delivery', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '4', 120000, '2024-12-09', 'pending'),
(41, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '4', 120000, '2024-12-09', 'pending'),
(42, 5, 'john', '0975354632', 'camarao@gmail.com', 'paypal', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320', '2', 60000, '2024-12-09', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(9, 32, 'Shirt', 2, 300.00),
(10, 33, 'carlo', 1, 30000.00),
(11, 34, 'Men&#39;s T-Shirt', 1, 700.00),
(12, 34, 'tanga', 1, 10000.00),
(13, 35, 'Men&#39;s T-Shirt', 2, 700.00),
(14, 36, 'tanga', 2, 10000.00),
(15, 36, 'carlo', 1, 30000.00),
(16, 37, 'Men&#39;s T-Shirt', 2, 700.00),
(17, 38, 'Men&#39;s T-Shirt', 3, 700.00),
(18, 39, 'tanga', 1, 10000.00),
(19, 40, 'carlo', 4, 30000.00),
(20, 41, 'carlo', 4, 30000.00),
(21, 42, 'carlo', 2, 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `stock` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `stock`) VALUES
(1, 'Men&#39;s T-Shirt', 'Cotton T-Shirt for Men', 700, 'tshirt-img.png', 22),
(3, 'Shirt', 'Shirt', 300, 'shirt.png', 0),
(4, 'tanga', 'asfsdfs', 10000, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg', 0),
(5, 'carlo', 'sadsda', 30000, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg', 2),
(6, 'carlo', 'sadsda', 30000, '8f9f1218-8363-452c-8fe9-cd72220fe357photo.jpeg', -5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `number`, `address`) VALUES
(1, 'Carlo Camarao', 'carlo@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, NULL),
(4, 'raz', 'raz@gmail.com', '54d9610f0dfe9306739d4eefc26434836ee5db22', NULL, NULL),
(5, 'john', 'camarao@gmail.com', 'b8923eeb8a7f6e7d84182eb2481d8933630b8994', '09753546321', 'flat no. 34, caragsakan, roxas, isabela, Philippines - 3320');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
