CREATE DATABASE IF NOT EXISTS kopi_piro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kopi_piro;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS product_reviews;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('owner','customer','cashier') NOT NULL DEFAULT 'customer',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description VARCHAR(255) NOT NULL DEFAULT '',
 category ENUM('coffee','food','non_caffeine','other') NOT NULL DEFAULT 'coffee',
 price INT UNSIGNED NOT NULL,
 image VARCHAR(255) DEFAULT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NULL,
 cashier_id INT NULL,
 customer_name VARCHAR(100) NOT NULL,
 subtotal INT UNSIGNED NOT NULL,
 discount INT UNSIGNED NOT NULL DEFAULT 0,
 total INT UNSIGNED NOT NULL,
 status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_orders_cashier(cashier_id),
 INDEX idx_orders_created(created_at),
 CONSTRAINT fk_orders_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 CONSTRAINT fk_orders_cashier FOREIGN KEY(cashier_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 product_id INT NULL,
 product_name VARCHAR(100) NOT NULL,
 unit_price INT UNSIGNED NOT NULL,
 qty INT UNSIGNED NOT NULL,
 discount_amount INT UNSIGNED NOT NULL DEFAULT 0,
 CONSTRAINT fk_items_order FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
 CONSTRAINT fk_items_product FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE product_reviews (
 id INT AUTO_INCREMENT PRIMARY KEY,
 product_id INT NOT NULL,
 user_id INT NOT NULL,
 rating TINYINT UNSIGNED NOT NULL,
 review VARCHAR(500) DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_user_product_review(product_id,user_id),
 CONSTRAINT chk_rating CHECK(rating BETWEEN 1 AND 5),
 CONSTRAINT fk_review_product FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE,
 CONSTRAINT fk_review_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO products(name,description,category,price) VALUES
('Espresso','A bold single shot of espresso.','coffee',15000),
('Americano','Espresso diluted with hot water.','coffee',20000),
('Iced Americano','Espresso, chilled water and ice.','coffee',22000),
('Cappuccino','Espresso with steamed milk and foam.','coffee',25000),
('Chocolate Milk','Chocolate beverage for non-coffee drinkers.','non_caffeine',22000),
('Matcha Latte','Creamy matcha with milk.','non_caffeine',26000),
('Butter Toast','Toasted bread with butter.','food',15000),
('Mineral Water','Bottled mineral water.','other',7000);
