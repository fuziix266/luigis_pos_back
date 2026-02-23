-- ============================================
-- POS LUIGI'S — Script Completo de BD
-- Motor: MariaDB 10.4+
-- ============================================

USE `luigis_pos`;

-- ============================================
-- 1. USUARIOS Y AUTENTICACIÓN
-- ============================================

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'cajero', 'cocina', 'delivery') NOT NULL DEFAULT 'cajero',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. CATÁLOGO
-- ============================================

CREATE TABLE IF NOT EXISTS `pizza_sizes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(20) NOT NULL UNIQUE,
    `display_name` VARCHAR(30) NOT NULL,
    `extra_price` INT NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(30) NOT NULL UNIQUE,
    `display_name` VARCHAR(50) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pizzas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `is_customizable` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pizza_prices` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `pizza_id` INT UNSIGNED NOT NULL,
    `size_id` INT UNSIGNED NOT NULL,
    `price` INT NOT NULL,
    UNIQUE KEY `uk_pizza_size` (`pizza_id`, `size_id`),
    FOREIGN KEY (`pizza_id`) REFERENCES `pizzas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`size_id`) REFERENCES `pizza_sizes`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ingredients` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL UNIQUE,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pizza_ingredients` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `pizza_id` INT UNSIGNED NOT NULL,
    `ingredient_id` INT UNSIGNED NOT NULL,
    `is_base` TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_pizza_ing` (`pizza_id`, `ingredient_id`),
    FOREIGN KEY (`pizza_id`) REFERENCES `pizzas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `drinks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL,
    `price` INT NOT NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `side_dishes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL,
    `price` INT NOT NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. PROMOCIONES
-- ============================================

CREATE TABLE IF NOT EXISTS `promos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL,
    `code` VARCHAR(30) NOT NULL UNIQUE,
    `description` TEXT,
    `base_price` INT NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `promo_day_config` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `day_of_week` TINYINT NOT NULL,
    `pizza_id` INT UNSIGNED DEFAULT NULL,
    `is_closed` TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE KEY `uk_day` (`day_of_week`),
    FOREIGN KEY (`pizza_id`) REFERENCES `pizzas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. ZONAS DE DELIVERY
-- ============================================

CREATE TABLE IF NOT EXISTS `delivery_zones` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL,
    `lat_threshold` DECIMAL(10, 6) NOT NULL,
    `extra_charge` INT NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. PEDIDOS
-- ============================================

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(20) NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `client_name` VARCHAR(100) DEFAULT NULL,
    `delivery_type` ENUM('Local', 'Retiro', 'Delivery', 'PedidosYa', 'UberEats') DEFAULT NULL,
    `payment_method` ENUM('Efectivo', 'Transferencia', 'Tarjeta', 'Debito', 'Credito') DEFAULT NULL,
    `delivery_address` VARCHAR(255) DEFAULT NULL,
    `address_detail` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `status` ENUM('NUEVO', 'PREP', 'ARMADO', 'HORNO', 'LISTO', 'RETIRADO', 'EN_CAMINO', 'ENTREGADO', 'ELIMINADO') NOT NULL DEFAULT 'NUEVO',
    `subtotal` INT NOT NULL DEFAULT 0,
    `delivery_fee` INT NOT NULL DEFAULT 0,
    `total_amount` INT NOT NULL DEFAULT 0,
    `activation_time` DATETIME DEFAULT NULL,
    `time_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `time_prep` DATETIME DEFAULT NULL,
    `time_armado` DATETIME DEFAULT NULL,
    `time_entered_oven` DATETIME DEFAULT NULL,
    `time_completed` DATETIME DEFAULT NULL,
    `time_pickup` DATETIME DEFAULT NULL,
    `time_delivered` DATETIME DEFAULT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `notes` TEXT DEFAULT NULL,
    `sort_position` INT NOT NULL DEFAULT 0,
    INDEX `idx_status` (`status`),
    INDEX `idx_delivery_type` (`delivery_type`),
    INDEX `idx_time_created` (`time_created`),
    INDEX `idx_activation` (`activation_time`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `item_type` ENUM('pizza', 'promo', 'drink', 'side', 'delivery_fee') NOT NULL DEFAULT 'pizza',
    `item_name` VARCHAR(100) NOT NULL,
    `details` TEXT DEFAULT NULL,
    `removed_ingredients` TEXT DEFAULT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `unit_price` INT NOT NULL DEFAULT 0,
    `total_price` INT NOT NULL DEFAULT 0,
    `comments` TEXT DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_item_extras` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_item_id` INT UNSIGNED NOT NULL,
    `ingredient_id` INT UNSIGNED DEFAULT NULL,
    `ingredient_name` VARCHAR(60) NOT NULL,
    `extra_price` INT NOT NULL DEFAULT 0,
    FOREIGN KEY (`order_item_id`) REFERENCES `order_items`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. CONFIGURACIÓN
-- ============================================

CREATE TABLE IF NOT EXISTS `system_config` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `config_key` VARCHAR(50) NOT NULL UNIQUE,
    `config_value` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
