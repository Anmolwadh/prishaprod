<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
redirect(admin_logged_in() ? 'admin/dashboard.php' : 'admin/login.php');
