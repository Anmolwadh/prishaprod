# Prisha Enterprises — Disposable Products Ecommerce

Complete PHP + MySQL ecommerce website for disposable products wholesale and retail.

## Requirements

- XAMPP (Apache + MySQL + PHP 8+)
- PHP extensions: PDO, pdo_mysql, fileinfo, mbstring

## Installation

1. Copy this project folder to:
   ```
   C:\xampp\htdocs\prisha-enterprises
   ```
   If your folder is currently on the Desktop, copy/rename it to `prisha-enterprises` under `htdocs`.

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Open phpMyAdmin: `http://localhost/phpmyadmin`

4. Import the SQL file:
   ```
   database/prisha_enterprises.sql
   ```

5. Confirm database credentials in `config/database.php` (default XAMPP: user `root`, empty password).

6. Open:
   ```
   http://localhost/prisha-enterprises/
   ```

7. Optional: visit `http://localhost/prisha-enterprises/install.php` to test DB and set admin password, then **delete `install.php`**.

## Default Logins

### Admin
- URL: `http://localhost/prisha-enterprises/admin/login.php`
- Username: `admin`
- Password: `password` (change immediately via Settings or install.php)

### Demo Customer
- Email: `customer@example.com`
- Password: `password`

## Features

- Customer storefront with shop, filters, product details, cart, COD checkout
- Order tracking timeline
- Customer registration / login / account / order history
- Admin dashboard, orders, products, categories, inventory, customers, reports, settings
- Bulk enquiry form
- WhatsApp floating button (number from settings)
- Flat shipping with free-shipping threshold (configurable)
- CSRF protection, password hashing, prepared statements, secure uploads

## Project Structure

See folders: `admin/`, `ajax/`, `assets/`, `config/`, `database/`, `includes/`, `uploads/`

## Notes

- Product placeholder images are SVG files in `assets/images/`
- Uploaded product images go to `uploads/products/`
- Order numbers follow format: `PEYYYYMMDD0001`
