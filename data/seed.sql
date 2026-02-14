-- ============================================
-- POS LUIGI'S — Datos Iniciales (Seed)
-- ============================================

USE `luigis_pos`;

-- Usuario admin (password: admin123)
INSERT INTO `users` (`username`, `password_hash`, `full_name`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin'),
('cajero', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cajero 1', 'cajero'),
('cocina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cocina 1', 'cocina'),
('delivery', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Delivery 1', 'delivery');

-- Tamaños
INSERT INTO `pizza_sizes` (`name`, `display_name`, `extra_price`, `sort_order`) VALUES
('small', 'Chica', 900, 1),
('medium', 'Mediana', 1300, 2),
('large', 'Familiar', 2000, 3);

-- Categorías
INSERT INTO `categories` (`name`, `display_name`, `sort_order`) VALUES
('clasica', 'Clásica', 1),
('especial', 'Especial', 2),
('premium', 'Premium', 3),
('custom', 'Personalizada', 4);

-- Pizzas
INSERT INTO `pizzas` (`name`, `category_id`, `is_customizable`, `sort_order`) VALUES
('Clásica', 1, 0, 1),
('Clásica Salame', 1, 0, 2),
('Clásica Jamón', 1, 0, 3),
('Clásica Champiñón', 1, 0, 4),
('Clásica Pepperoni', 1, 0, 5),
('Napolitana', 2, 0, 6),
('Di''Pollo', 2, 0, 7),
('Hawaiana', 2, 0, 8),
('Española', 2, 0, 9),
('Nápoles', 2, 0, 10),
('Vegetariana', 3, 0, 11),
('Barbecue', 3, 0, 12),
('Mediterránea', 3, 0, 13),
('Luigi''s', 3, 0, 14),
('Arma Tu Pizza', 4, 1, 15);

-- Precios
INSERT INTO `pizza_prices` (`pizza_id`, `size_id`, `price`) VALUES
(1,1,5000),(1,2,6000),(1,3,7000),
(2,1,5000),(2,2,6000),(2,3,7500),
(3,1,5000),(3,2,6000),(3,3,7500),
(4,1,5000),(4,2,6000),(4,3,7500),
(5,1,5000),(5,2,6000),(5,3,7500),
(6,1,6000),(6,2,7500),(6,3,9000),
(7,1,7000),(7,2,8500),(7,3,10000),
(8,1,7000),(8,2,8500),(8,3,10000),
(9,1,7000),(9,2,8500),(9,3,10000),
(10,1,7000),(10,2,8500),(10,3,10000),
(11,1,8000),(11,2,9500),(11,3,11000),
(12,1,8000),(12,2,9500),(12,3,11000),
(13,1,8000),(13,2,9500),(13,3,11000),
(14,1,8000),(14,2,9500),(14,3,11000),
(15,1,8000),(15,2,9500),(15,3,11000);

-- Ingredientes
INSERT INTO `ingredients` (`name`, `sort_order`) VALUES
('Aceitunas', 1), ('Albahaca', 2), ('Camarones', 3), ('Carne', 4),
('Champiñón', 5), ('Choclo', 6), ('Chorizo Español', 7), ('Crema', 8),
('Jamón', 9), ('Jamón Serrano', 10), ('Pimentón', 11), ('Piña', 12),
('Pollo', 13), ('Pepperoni', 14), ('Queso', 15), ('Queso Parmesano', 16),
('Salame', 17), ('Salsa Barbecue', 18), ('Tocino', 19), ('Tomate Cherry', 20),
('Salsa de tomate', 21), ('Orégano', 22);

-- Ingredientes base por pizza
-- Clásica: Queso, Salsa de tomate, Orégano
INSERT INTO `pizza_ingredients` (`pizza_id`, `ingredient_id`, `is_base`) VALUES
(1,15,1),(1,21,1),(1,22,1),
-- Clásica Salame
(2,17,1),(2,15,1),(2,21,1),(2,22,1),
-- Clásica Jamón
(3,9,1),(3,15,1),(3,21,1),(3,22,1),
-- Clásica Champiñón
(4,5,1),(4,15,1),(4,21,1),(4,22,1),
-- Clásica Pepperoni
(5,14,1),(5,15,1),(5,21,1),(5,22,1),
-- Napolitana
(6,21,1),(6,9,1),(6,11,1),(6,1,1),(6,15,1),(6,22,1),
-- Di'Pollo
(7,21,1),(7,13,1),(7,11,1),(7,6,1),(7,15,1),(7,22,1),
-- Hawaiana
(8,21,1),(8,9,1),(8,1,1),(8,12,1),(8,15,1),(8,22,1),
-- Española
(9,21,1),(9,9,1),(9,11,1),(9,7,1),(9,20,1),(9,15,1),(9,22,1),
-- Nápoles
(10,21,1),(10,19,1),(10,5,1),(10,8,1),(10,15,1),(10,22,1),
-- Vegetariana
(11,21,1),(11,2,1),(11,6,1),(11,1,1),(11,11,1),(11,5,1),(11,15,1),(11,22,1),
-- Barbecue
(12,21,1),(12,4,1),(12,13,1),(12,19,1),(12,18,1),(12,15,1),(12,22,1),
-- Mediterránea
(13,21,1),(13,10,1),(13,16,1),(13,20,1),(13,2,1),(13,15,1),(13,22,1),
-- Luigi's
(14,21,1),(14,3,1),(14,7,1),(14,13,1),(14,11,1),(14,5,1),(14,6,1),(14,15,1),(14,22,1),
-- Arma Tu Pizza
(15,21,1),(15,15,1),(15,22,1);

-- Bebidas
INSERT INTO `drinks` (`name`, `price`, `sort_order`) VALUES
('Coca Cola', 2500, 1),
('Coca Cola Zero', 2500, 2),
('Fanta', 2500, 3),
('Inca Cola', 2500, 4),
('Sprite', 2500, 5);

-- Acompañamientos
INSERT INTO `side_dishes` (`name`, `price`, `sort_order`) VALUES
('Palitos de Ajo', 3000, 1),
('Palitos Parmesano', 3500, 2);

-- Promociones
INSERT INTO `promos` (`name`, `code`, `description`, `base_price`, `sort_order`) VALUES
('Promo 1', 'promo_1', '2 Pizzas Clásicas (opción 3ra pizza por $18.000)', 12000, 1),
('Promo 2', 'promo_2', '2 Pizzas + Palitos Ajo + Bebida 1.5L', 16000, 2),
('Promo del Día', 'promo_day', '2 Pizzas Familiares + Palitos Ajo + Bebida 1.5L', 17000, 3);

-- Pizza del Día
INSERT INTO `promo_day_config` (`day_of_week`, `pizza_id`, `is_closed`) VALUES
(0, 12, 0),
(1, 7,  0),
(2, 10, 0),
(3, NULL, 1),
(4, 9,  0),
(5, 8,  0),
(6, 11, 0);

-- Zonas de Delivery
INSERT INTO `delivery_zones` (`name`, `lat_threshold`, `extra_charge`, `sort_order`) VALUES
('Zona Norte Lejano', -18.425874, 1000, 1),
('Zona Norte', -18.481000, 500, 2),
('Zona Base (Sur)', -90.000000, 0, 3);

-- Configuración
INSERT INTO `system_config` (`config_key`, `config_value`, `description`) VALUES
('delivery_base_fee', '3000', 'Tarifa base de delivery en CLP'),
('oven_chambers', '1', 'Cámaras activas del horno (1 o 2)'),
('store_city', 'Arica', 'Ciudad del local'),
('store_country', 'Chile', 'País del local'),
('daily_order_counter', '0', 'Contador diario de pedidos'),
('store_name', 'Luigi''s Pizza', 'Nombre del local'),
('notification_sound_enabled', '1', 'Sonido de notificación');
