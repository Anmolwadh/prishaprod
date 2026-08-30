<?php
/**
 * Authentication helpers - Prisha Enterprises
 */
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function customer_logged_in(): bool
{
    return !empty($_SESSION['customer_id']);
}

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function current_customer(): ?array
{
    if (!customer_logged_in()) {
        return null;
    }
    static $customer = null;
    if ($customer !== null) {
        return $customer;
    }
    $stmt = getDB()->prepare('SELECT id, name, email, phone, created_at FROM customers WHERE id = ? LIMIT 1');
    $stmt->execute([(int)$_SESSION['customer_id']]);
    $customer = $stmt->fetch() ?: null;
    if (!$customer) {
        unset($_SESSION['customer_id'], $_SESSION['customer_name']);
    }
    return $customer;
}

function current_admin(): ?array
{
    if (!admin_logged_in()) {
        return null;
    }
    static $admin = null;
    if ($admin !== null) {
        return $admin;
    }
    $stmt = getDB()->prepare('SELECT id, username, name, email FROM admins WHERE id = ? AND status = ? LIMIT 1');
    $stmt->execute([(int)$_SESSION['admin_id'], 'Active']);
    $admin = $stmt->fetch() ?: null;
    if (!$admin) {
        unset($_SESSION['admin_id'], $_SESSION['admin_name']);
    }
    return $admin;
}

function require_customer(): void
{
    if (!customer_logged_in()) {
        flash('error', 'Please login to continue.');
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? url('login.php');
        redirect('login.php');
    }
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        flash('error', 'Please login to access admin panel.');
        redirect('admin/login.php');
    }
}

function login_customer(array $customer): void
{
    session_regenerate_id(true);
    $_SESSION['customer_id'] = (int)$customer['id'];
    $_SESSION['customer_name'] = $customer['name'];
}

function login_admin(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_name'] = $admin['name'] ?? $admin['username'];
}

function logout_customer(): void
{
    unset($_SESSION['customer_id'], $_SESSION['customer_name']);
}

function logout_admin(): void
{
    unset($_SESSION['admin_id'], $_SESSION['admin_name']);
}
