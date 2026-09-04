<?php
http_response_code(404);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/database.php';
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found | Prisha Enterprises</title>
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars(BASE_URL . '/assets/images/favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= htmlspecialchars(BASE_URL . '/assets/images/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= htmlspecialchars(BASE_URL . '/assets/images/favicon-16x16.png') ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= htmlspecialchars(BASE_URL . '/assets/images/apple-touch-icon.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container text-center">
    <h1 class="display-5 text-success">404</h1>
    <p class="lead">Sorry, the page you are looking for was not found.</p>
    <a class="btn btn-success" href="<?= htmlspecialchars(BASE_URL . '/index.php') ?>">Go Home</a>
  </div>
</body>
</html>
