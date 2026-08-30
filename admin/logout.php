<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
logout_admin();
flash('success', 'Admin logged out.');
redirect('admin/login.php');
