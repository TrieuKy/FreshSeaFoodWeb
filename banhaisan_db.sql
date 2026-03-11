-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for banhaisan_db
CREATE DATABASE IF NOT EXISTS `banhaisan_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `banhaisan_db`;

-- Dumping structure for table banhaisan_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '????',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.categories: ~6 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`) VALUES
	(7, 'Tom', 'tom', '', 'Cac loai tom tuoi song', '2026-03-11 06:42:13'),
	(8, 'Cua va Ghe', 'cua', '', 'Cua bien, ghe xanh tuoi song', '2026-03-11 06:42:13'),
	(9, 'Ca', 'ca', '', 'Cac loai ca bien tuoi', '2026-03-11 06:42:13'),
	(10, 'Oc', 'oc', '', 'Oc huong, ngheu, so, hau tuoi', '2026-03-11 06:42:13'),
	(11, 'Dac San', 'dac_san', '', 'Hai san dac san cao cap', '2026-03-11 06:42:13'),
	(12, 'Dong Lanh', 'dong_lanh', '', 'Hai san dong lanh dong goi san', '2026-03-11 06:42:13');

-- Dumping structure for table banhaisan_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','confirmed','shipping','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `payment_method` enum('cod','qr') COLLATE utf8mb4_unicode_ci DEFAULT 'cod',
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.orders: ~5 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `total_price`, `shipping_fee`, `discount_amount`, `status`, `customer_name`, `customer_phone`, `customer_address`, `payment_method`, `payment_status`, `note`, `created_at`, `updated_at`) VALUES
	(3, 3, 155000.00, 30000.00, 0.00, 'delivered', 'Triệu Đoan Kỳ', '0767265062', '180 Nguyễn Hữu Cảnh, Phường 22', 'cod', 'unpaid', 'chó', '2026-03-11 06:32:10', '2026-03-11 06:33:23'),
	(4, 3, 395000.00, 30000.00, 0.00, 'shipping', 'Triệu Đoan Kỳ', '0767265062', '180 Nguyễn Hữu Cảnh, Phường 22', 'cod', 'unpaid', '', '2026-03-11 06:56:12', '2026-03-11 07:02:31'),
	(5, 3, 155000.00, 30000.00, 0.00, 'confirmed', 'Triệu Đoan Kỳ', '0767265062', '180 Nguyễn Hữu Cảnh, Phường 22', 'qr', 'unpaid', '', '2026-03-11 07:00:22', '2026-03-11 07:02:29'),
	(6, 3, 190000.00, 30000.00, 0.00, 'cancelled', 'Triệu Đoan Kỳ', '0767265062', '180 Nguyễn Hữu Cảnh, Phường 22', 'cod', 'unpaid', '', '2026-03-11 07:12:38', '2026-03-11 07:21:10'),
	(7, 1, 500000.00, 0.00, 0.00, 'delivered', 'Triệu Đoan Kỳ', '0767265062', '180 Nguyễn Hữu Cảnh, Phường 22', 'cod', 'unpaid', '', '2026-03-11 07:20:27', '2026-03-11 07:21:17');

-- Dumping structure for table banhaisan_db.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`quantity` * `unit_price`)) STORED,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.order_items: ~8 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `unit_price`) VALUES
	(1, 3, 9, 'Sò lông', 1, 80000.00),
	(2, 3, 8, 'Nghêu', 1, 45000.00),
	(3, 4, 9, 'Sò lông', 4, 80000.00),
	(4, 4, 8, 'Nghêu', 1, 45000.00),
	(5, 5, 9, 'Sò lông', 1, 80000.00),
	(6, 5, 8, 'Nghêu', 1, 45000.00),
	(7, 6, 9, 'Sò lông', 2, 80000.00),
	(8, 7, 4, 'Cá Hồi', 2, 250000.00);

-- Dumping structure for table banhaisan_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.products: ~9 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `stock`, `unit`, `image`, `is_active`, `created_at`) VALUES
	(1, 7, 'Tôm Hùm Alaska', 'Nhiều dinh dưỡng', 320000.00, 50, 'con', '1770192251_TOMHUM.jfif', 1, '2026-03-11 06:09:33'),
	(2, 7, 'Tôm Càng Xanh', 'Chỉ bán sỉ', 180000.00, 80, 'kg', '1773213539_Tôm càng xanh.jpg', 1, '2026-03-11 06:09:33'),
	(3, 11, 'Rươi', 'Rươi đặc sản, chỉ có theo mùa', 120000.00, 100, 'kg', '1773213562_Rươi.jpg', 1, '2026-03-11 06:09:33'),
	(4, 9, 'Cá Hồi', 'Cá tươi', 250000.00, 30, 'con', '1770192238_CAHOI.jfif', 1, '2026-03-11 06:09:33'),
	(5, 10, 'Ốc Giác', 'Ốc to L', 180000.00, 40, 'con', '1772614354_ocgiac.jfif', 1, '2026-03-11 06:09:33'),
	(6, 10, 'Ốc Tỏi', 'Ốc tỏi mới', 150000.00, 20, 'con', '1772614954_octoi.jfif', 1, '2026-03-11 06:09:33'),
	(7, 11, 'Cua hoàng đế', 'Cua size XL', 120000.00, 60, 'kg', '1770192244_KINGCRAD.jfif', 1, '2026-03-11 06:09:33'),
	(8, 10, 'Nghêu', 'Nghêu còn sống', 45000.00, 100, 'kg', '1773213376_Nghêu.jpg', 1, '2026-03-11 06:09:33'),
	(9, 12, 'Sò lông', 'Sò lông bự chà bá', 80000.00, 70, 'kg', '1773210450_download.jpg', 1, '2026-03-11 06:09:33');

-- Dumping structure for table banhaisan_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('customer','staff','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `email`, `phone`, `address`, `role`, `is_active`, `created_at`) VALUES
	(1, 'Qu???n Tr??? Vi??n', 'admin', 'admin123', 'admin@haisan.vn', '0909000001', NULL, 'admin', 1, '2026-03-11 06:09:33'),
	(2, 'Nh??n Vi??n', 'staff', 'staff123', 'staff@haisan.vn', '0909000002', NULL, 'staff', 1, '2026-03-11 06:09:33'),
	(3, 'Kh??ch H??ng A', 'customer', 'customer123', 'khach@gmail.com', '0909000003', NULL, 'customer', 1, '2026-03-11 06:09:33');

-- Dumping structure for table banhaisan_db.vouchers
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci DEFAULT 'percent',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) DEFAULT '0.00',
  `max_uses` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table banhaisan_db.vouchers: ~2 rows (approximately)
INSERT INTO `vouchers` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `max_uses`, `used_count`, `expires_at`, `is_active`, `created_at`) VALUES
	(1, 'HAISAN10', 'percent', 10.00, 200000.00, NULL, 0, '2026-12-31', 1, '2026-03-11 06:20:25'),
	(2, 'WELCOME50K', 'fixed', 50000.00, 300000.00, NULL, 0, '2026-06-30', 1, '2026-03-11 06:20:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
