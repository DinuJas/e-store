CREATE DATABASE IF NOT EXISTS `e-store`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `e-store`;

-- -----------------------------
-- users
-- -----------------------------
CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB;

-- -----------------------------
-- baskets
-- -----------------------------
CREATE TABLE `baskets` (
  `basket_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `status` ENUM('active','ordered') NOT NULL,
  PRIMARY KEY (`basket_id`),
  KEY `fk_baskets_users` (`user_id`),
  CONSTRAINT `fk_baskets_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`user_id`)
) ENGINE=InnoDB;

-- -----------------------------
-- products
-- -----------------------------
CREATE TABLE `products` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `image` TEXT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2),
  `product_info` TEXT,
  `stock` INT,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB;

-- -----------------------------
-- basket_products
-- -----------------------------
CREATE TABLE `basket_products` (
  `basket_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`basket_id`, `product_id`),
  KEY `fk_bp_products` (`product_id`),
  CONSTRAINT `fk_bp_baskets`
    FOREIGN KEY (`basket_id`)
    REFERENCES `baskets` (`basket_id`),
  CONSTRAINT `fk_bp_products`
    FOREIGN KEY (`product_id`)
    REFERENCES `products` (`product_id`)
) ENGINE=InnoDB;

-- -----------------------------
-- orders
-- -----------------------------
CREATE TABLE `orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `fk_orders_users` (`user_id`),
  CONSTRAINT `fk_orders_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`user_id`)
) ENGINE=InnoDB;

-- -----------------------------
-- order_items
-- -----------------------------
CREATE TABLE `order_items` (
  `order_item_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price_at_purchase` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `fk_oi_orders` (`order_id`),
  KEY `fk_oi_products` (`product_id`),
  CONSTRAINT `fk_oi_orders`
    FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`order_id`),
  CONSTRAINT `fk_oi_products`
    FOREIGN KEY (`product_id`)
    REFERENCES `products` (`product_id`)
) ENGINE=InnoDB;

-- -----------------------------
-- payments
-- -----------------------------
CREATE TABLE `payments` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `second_name` VARCHAR(255) NOT NULL,
  `card_number` VARCHAR(45) NOT NULL,
  `card_expiry` CHAR(5) NOT NULL,
  `card_cvv` CHAR(4) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `postcode` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20) NOT NULL,
  `payment_status` ENUM('pending','paid','failed') DEFAULT 'pending',
  `amount` DECIMAL(10,2),
  `paid_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `fk_payments_orders` (`order_id`),
  CONSTRAINT `fk_payments_orders`
    FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB;
