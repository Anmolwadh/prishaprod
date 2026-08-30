-- Prisha Enterprises Ecommerce Database
-- Import via phpMyAdmin or: mysql -u root < prisha_enterprises.sql

CREATE DATABASE IF NOT EXISTS prisha_enterprises CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE prisha_enterprises;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS bulk_enquiries;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS settings;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE clients (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_clients_status (status)
) ENGINE=InnoDB;

CREATE TABLE customers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL,
  address TEXT NULL,
  city VARCHAR(80) NULL,
  state VARCHAR(80) NULL,
  pincode VARCHAR(10) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_customers_email (email),
  KEY idx_customers_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  description TEXT NULL,
  image VARCHAR(255) NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_categories_slug (slug),
  KEY idx_categories_status (status)
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL,
  sku VARCHAR(50) NOT NULL,
  description TEXT NULL,
  short_description VARCHAR(500) NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  mrp DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  discount DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  gst DECIMAL(5,2) NOT NULL DEFAULT 18.00,
  stock INT NOT NULL DEFAULT 0,
  pack_size VARCHAR(100) NULL,
  image VARCHAR(255) NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_products_sku (sku),
  UNIQUE KEY uk_products_slug (slug),
  KEY idx_products_category_id (category_id),
  KEY idx_products_status (status),
  KEY idx_products_created_at (created_at),
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(30) NOT NULL,
  customer_id INT UNSIGNED NULL,
  customer_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NULL,
  phone VARCHAR(15) NOT NULL,
  address TEXT NOT NULL,
  city VARCHAR(80) NOT NULL,
  state VARCHAR(80) NOT NULL,
  pincode VARCHAR(10) NOT NULL,
  landmark VARCHAR(150) NULL,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  shipping DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  payment_method VARCHAR(30) NOT NULL DEFAULT 'COD',
  payment_status ENUM('Pending','Paid','Failed','Refunded') NOT NULL DEFAULT 'Pending',
  order_status ENUM('Pending','Confirmed','Processing','Shipped','Out for Delivery','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_orders_order_number (order_number),
  KEY idx_orders_phone (phone),
  KEY idx_orders_email (email),
  KEY idx_orders_order_status (order_status),
  KEY idx_orders_created_at (created_at),
  KEY idx_orders_customer_id (customer_id),
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(200) NOT NULL,
  sku VARCHAR(50) NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_order_items_order_id (order_id),
  KEY idx_order_items_product_id (product_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE bulk_enquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  business_name VARCHAR(150) NULL,
  phone VARCHAR(15) NOT NULL,
  email VARCHAR(100) NULL,
  product VARCHAR(200) NULL,
  quantity VARCHAR(50) NULL,
  message TEXT NULL,
  status ENUM('New','Contacted','Closed') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_bulk_status (status),
  KEY idx_bulk_created_at (created_at)
) ENGINE=InnoDB;

CREATE TABLE settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL,
  setting_value TEXT NULL,
  UNIQUE KEY uk_settings_key (setting_key)
) ENGINE=InnoDB;

-- Admin password: password (change after first login via Settings or install.php)
-- Hash is PHP password_hash compatible bcrypt for: password
INSERT INTO admins (username, email, password, name, status) VALUES
('admin', 'admin@prishaenterprises.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Active');

INSERT INTO settings (setting_key, setting_value) VALUES
('business_name', 'Prisha Enterprises'),
('phone', '+918054798966'),
('email', 'info@prishaenterprises.com'),
('address', 'Sco 290/1 near Scholar Public School, Bachitter Nagar, Dhamoli, Rajpura'),
('shipping_charge', '60'),
('free_shipping_minimum', '999'),
('whatsapp_number', '918054798966'),
('low_stock_threshold', '10');

INSERT INTO categories (id, name, slug, description, image, status) VALUES
(1, 'Meal Trays', 'meal-trays', 'Disposable meal trays with multiple compartments for restaurants and catering.', 'prod_eb043832affb9802.webp', 'Active'),
(2, 'Disposable Containers', 'disposable-containers', 'Food containers with lids in multiple sizes for takeaway and storage.', 'prod_3346e5f9c9925b33.jpg', 'Active'),
(3, 'Disposable Glasses', 'disposable-glasses', 'Ripple and plain disposable glasses for hot and cold beverages.', 'prod_99c47f3eb19e2cea.jpg', 'Active'),
(4, 'Eco-Friendly Plates', 'eco-friendly-plates', 'Eco-friendly disposable plates for events and everyday use.', 'cat-plates.svg', 'Active'),
(5, 'Butter Paper', 'butter-paper', 'Butter paper sheets and rolls for food wrapping and baking.', 'prod_4c1df2e83893bdb0.webp', 'Active'),
(6, 'Food Packaging', 'food-packing', 'Assorted food packaging solutions for retail and wholesale.', 'prod_9052d82c9f9a13db.webp', 'Active'),
(7, 'Other', 'other', 'Other disposable and packaging products.', 'cat-other.svg', 'Active');

INSERT INTO products (category_id, name, slug, sku, description, short_description, price, mrp, discount, stock, pack_size, image, status, featured) VALUES
(1, '3 Compartment Meal Tray', '3-compartment-meal-tray', 'PE-MT-3C',
 'Durable 3 compartment disposable meal tray ideal for thali-style meals, catering and takeaway. Leak-resistant design suitable for restaurants and events.',
 'Sturdy 3-compartment tray for everyday meals and catering.',
 180.00, 220.00, 18.18, 250, 'Pack of 50', 'product-meal-tray-3.jpg', 'Active', 1),

(1, '5 Compartment Meal Tray', '5-compartment-meal-tray', 'PE-MT-5C',
 'Premium 5 compartment disposable meal tray for complete meals. Perfect for restaurants, mess halls and bulk catering orders.',
 '5-compartment tray for full meal servings.',
 240.00, 300.00, 20.00, 200, 'Pack of 50', 'product-meal-tray-5.jpg', 'Active', 1),

(1, '8 Compartment Meal Tray', '8-compartment-meal-tray', 'PE-MT-8C',
 'Large 8 compartment disposable meal tray designed for elaborate meals and wedding catering. Strong and stackable.',
 'Spacious 8-compartment tray for bulk catering.',
 320.00, 400.00, 20.00, 150, 'Pack of 25', 'product-meal-tray-8.jpg', 'Active', 1),

(2, '500ml Disposable Container with Lid', '500ml-disposable-container-with-lid', 'PE-DC-500',
 '500ml round disposable container with secure lid. Ideal for curries, desserts and takeaway portions.',
 '500ml container with leak-resistant lid.',
 145.00, 180.00, 19.44, 300, 'Pack of 100', 'product-container-500.jpg', 'Active', 1),

(2, '750ml Disposable Container with Lid', '750ml-disposable-container-with-lid', 'PE-DC-750',
 '750ml disposable food container with tight-fit lid for medium portions. Microwave-safe options available on request.',
 '750ml takeaway container with lid.',
 175.00, 220.00, 20.45, 280, 'Pack of 100', 'product-container-750.jpg', 'Active', 1),

(2, '1000ml Disposable Container with Lid', '1000ml-disposable-container-with-lid', 'PE-DC-1000',
 '1 litre disposable container with lid for family portions and bulk takeaway. Strong walls and secure snap lid.',
 '1L container perfect for large portions.',
 210.00, 260.00, 19.23, 220, 'Pack of 50', 'product-container-1000.jpg', 'Active', 1),

(3, 'Ripple Glass 200ml', 'ripple-glass-200ml', 'PE-RG-200',
 '200ml ripple wall disposable glass for hot beverages. Heat-insulated design keeps drinks warm and hands cool.',
 'Insulated 200ml ripple paper glass.',
 95.00, 120.00, 20.83, 400, 'Pack of 100', 'ripple-glass-200.jpg', 'Active', 1),

(3, 'Ripple Glass 250ml', 'ripple-glass-250ml', 'PE-RG-250',
 '250ml ripple disposable glass suitable for tea, coffee and soft drinks. Ideal for cafes, stalls and events.',
 '250ml ripple glass for cafes and events.',
 110.00, 140.00, 21.43, 350, 'Pack of 100', 'ripple-glass-250.jpg', 'Active', 1),

(4, 'Eco-Friendly Paper Plates', 'eco-friendly-paper-plates', 'PE-EP-PLT',
 'Biodegradable eco-friendly paper plates made from sustainable materials. Strong enough for hot and cold food.',
 'Eco paper plates for events and daily use.',
 160.00, 200.00, 20.00, 180, 'Pack of 100', 'product-plates.jpg', 'Active', 1),

(5, 'Butter Paper Roll', 'butter-paper-roll', 'PE-BP-ROLL',
 'Food-grade butter paper roll for wrapping, baking and packing. Non-stick surface suitable for kitchens and bakeries.',
 'Food-grade butter paper roll for wrapping.',
 130.00, 160.00, 18.75, 120, '1 Roll (30m)', 'product-butter-paper.jpg', 'Active', 1);

-- Demo customer: customer@example.com / password
INSERT INTO customers (name, email, phone, password, address, city, state, pincode) VALUES
('Demo Customer', 'customer@example.com', '9876501234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '123 Demo Street', 'Mumbai', 'Maharashtra', '400001');

INSERT INTO clients (name, description, sort_order, status) VALUES
('Fine Dine Restaurant', 'Premium dining partner using our meal trays, containers and packaging for dine-in and takeaway service.', 1, 'Active'),
('Chandu Chat', 'Popular chat and snack outlet supplied with disposable plates, glasses and food packaging for daily service.', 2, 'Active'),
('Agra Chat Bhandar', 'Trusted chat bhandar partner supplied with disposable plates, glasses and packaging for everyday service.', 3, 'Active');


