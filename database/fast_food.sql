-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 09:02 AM
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
-- Database: `fast_food`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','preparing','delivering','completed','cancelled') DEFAULT 'pending',
  `total_amount` int(11) NOT NULL DEFAULT 0,
  `shipping_name` varchar(120) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `total_amount`, `shipping_name`, `shipping_phone`, `shipping_address`, `created_at`, `updated_at`) VALUES
(50, 17, 'pending', 150000, 'Test User', '0123456789', '123 Test Street', '2025-10-22 08:33:37', '2025-10-22 08:33:37'),
(57, 1, 'confirmed', 180000, NULL, NULL, NULL, '2025-11-29 06:18:03', '2025-11-29 06:18:03'),
(58, 1, 'completed', 360000, NULL, NULL, NULL, '2025-12-10 06:18:06', '2025-12-16 17:57:37'),
(59, 18, 'completed', 120000, 'Hùng Phi Nguyễn', '0444456654', 'Nguyễn Thiện Thành, Trà Vinh', '2025-12-18 08:35:27', '2025-12-18 08:39:39'),
(60, 18, 'pending', 55000, 'Hùng Phi Nguyễn', '0101101122', 'Nguyễn Thiện Thành, Trà Vinh', '2025-12-23 07:15:30', '2025-12-23 07:15:30'),
(61, 18, 'pending', 120000, 'Hùng Phi Nguyễn', '6633445556', 'Nguyễn Thiện Thành, Trà Vinh', '2025-12-23 07:33:51', '2025-12-23 07:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES
(58, 50, 7, 2, 15000, 30000),
(59, 50, 9, 1, 120000, 120000),
(66, 59, 9, 1, 120000, 120000),
(67, 60, 7, 1, 55000, 55000),
(68, 61, 9, 1, 120000, 120000);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','preparing','delivering','completed','cancelled') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status`, `note`, `created_at`) VALUES
(121, 50, 'pending', 'Tạo đơn', '2025-10-22 08:33:37'),
(138, 58, 'completed', 'Admin cập nhật', '2025-12-16 17:57:37'),
(139, 59, 'pending', 'Tạo đơn', '2025-12-18 08:35:27'),
(140, 59, 'completed', 'Admin cập nhật', '2025-12-18 08:39:39'),
(141, 59, 'completed', 'Admin cập nhật', '2025-12-18 08:39:44'),
(142, 60, 'pending', 'Tạo đơn', '2025-12-23 07:15:30'),
(143, 61, 'pending', 'Tạo đơn', '2025-12-23 07:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(160) NOT NULL,
  `price` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `average_rating` decimal(2,1) DEFAULT 0.0,
  `total_reviews` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image_url`, `is_active`, `average_rating`, `total_reviews`) VALUES
(1, 'Burger Bò Phô Mai Deluxe', 85000, 'Burger bò Wagyu nướng hoàn hảo với phô mai cheddar cao cấp, rau xanh hữu cơ, cà chua tươi và sốt đặc biệt của nhà hàng. Kèm theo khoai tây chiên giòn.', NULL, 1, 4.5, 2),
(2, 'Pizza Hải Sản Cao Cấp', 150000, 'Pizza đế bánh mỏng giòn với tôm tươi, mực baby, cua Alaska và phô mai mozzarella nhập khẩu. Nướng trong lò đá truyền thống.', NULL, 1, 4.0, 1),
(3, 'Gà Rán Giòn Cay', 95000, 'Gà rán giòn tan với lớp bột tẩm gia vị cay nồng đặc trưng, ăn kèm khoai tây chiên và coleslaw tươi mát.', NULL, 1, 5.0, 1),
(4, 'Coca Cola Premium', 25000, 'Coca Cola nguyên chất 330ml được phục vụ trong chai thủy tinh, mát lạnh với đá viên tươi.', 'CocaColaClassic24x033l_1765714321.png', 1, 5.0, 1),
(5, 'Khoai Tây Chiên Truffle', 45000, 'Khoai tây chiên giòn rụm với muối truffle cao cấp, phô mai parmesan và herbs tươi. Kèm 3 loại sốt đặc biệt.', NULL, 1, 4.0, 1),
(6, 'Sandwich Gà Nướng Avocado', 65000, 'Sandwich với ức gà nướng tẩm gia vị, bơ tươi, rau xanh hữu cơ và sốt aioli tự làm trên bánh mì sourdough.', NULL, 1, 4.5, 2),
(7, 'Hot Dog Phô Mai Nướng', 55000, 'Hot dog với xúc xích bò cao cấp, phô mai cheddar nướng chảy, hành tây caramel và sốt mustard Dijon.', 'hinh-hotdog-pho-mai_1766048708.jpg', 1, 4.0, 1),
(8, 'Salad Caesar Tôm Nướng', 75000, 'Salad Caesar truyền thống với tôm nướng tỏi ớt, rau xà lách romaine tươi, phô mai parmesan và crouton giòn.', 'bot-wallpdf_1766048581.png', 1, 0.0, 0),
(9, 'Pizza Hải Sản', 120000, 'Pizza cở lớn ngập phô mai', 'pizza_1761062369.jpg', 1, 5.0, 1),
(10, 'Gà rán', 30000, 'Gà giòn tann', 'pngtree-golden-fried-chicken-with-crispy-french-fries-png-image_12950142_1765904591.png', 1, 4.0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(6, 6, 1, NULL, 5, 'Món ăn rất ngon, sẽ đặt lại!', 1, '2025-12-14 08:09:14', '2025-12-14 08:09:14'),
(7, 6, 3, NULL, 4, 'Ngon nhưng hơi mặn một chút.', 1, '2025-12-14 08:09:14', '2025-12-14 08:09:14'),
(8, 7, 1, NULL, 4, 'Món này cũng ổn, giá hợp lý.', 1, '2025-12-14 08:09:14', '2025-12-14 08:09:14'),
(9, 10, 1, NULL, 4, 'tạm được', 1, '2025-12-14 08:55:07', '2025-12-14 08:55:07'),
(10, 9, 1, NULL, 5, '', 1, '2025-12-14 09:05:02', '2025-12-14 09:05:02'),
(11, 1, 1, NULL, 5, 'Burger rất ngon! Thịt bò tươi, phô mai tan chảy. Sẽ đặt lại!', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(12, 1, 17, NULL, 4, 'Ngon nhưng hơi mặn một chút. Nhìn chung vẫn ổn.', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(13, 2, 1, NULL, 4, 'Pizza hải sản tươi ngon, đế bánh giòn. Giá hơi cao.', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(14, 3, 17, NULL, 5, 'Gà rán giòn tan, gia vị đậm đà. Recommend!', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(15, 4, 1, NULL, 5, 'Coca mát lạnh, giao nhanh. Perfect!', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(16, 5, 17, NULL, 4, 'Khoai tây giòn, muối vừa phải. Ổn!', 1, '2025-12-14 11:15:35', '2025-12-14 11:15:35'),
(53, 9, 18, 59, 5, 'ngon tuyệt', 1, '2025-12-18 08:40:59', '2025-12-18 08:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `address`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'daocongduy', NULL, NULL, 'daocongduy123', '$2y$10$yFGBt8ZpdS1xWg0XtUNh0OcG5/dsjZe31wzn8bZmidftpdkOcyH8G', 'user', '2025-10-16 08:53:35'),
(3, 'Admin Demo', NULL, NULL, 'admin@example.com', '$2y$10$tlvtcqlU1mMAuS9mq14V1efcDPVUSvxTAbWDvPduxEtUMtmOjMPFK', 'admin', '2025-10-16 09:01:20'),
(17, 'Test User', NULL, NULL, 'test@local', '$2y$10$aQ4KoBhOih8cLI8hGSP6Gu5i9NQt9Fspr.UnV8pQveXKrps5nNwla', 'user', '2025-10-22 08:25:52'),
(18, 'kha', NULL, NULL, 'kha@gmail.com', '$2y$10$u3m4p2P9YH/7L6GxW8YYQeNQt33R2KTGARvotkxKdpp2MBwulk0R6', 'user', '2025-12-18 07:29:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_review_order` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_approved` (`is_approved`),
  ADD KEY `idx_reviews_rating` (`rating`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_review_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
