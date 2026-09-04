<?php
http_response_code(500);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Database connection | Prisha Enterprises</title>
  <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container" style="max-width:640px">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Website cannot connect to the database</h1>
        <p class="mb-2">MySQL is not reachable with the details in <code>config/database.php</code>.</p>
        <p class="mb-0 small text-muted">On Hostomy: create a database in ioPanel, import <code>database/prisha_enterprises.sql</code>, then set DB_HOST, DB_NAME, DB_USER and DB_PASS. On this PC: start MariaDB, then open the local site again.</p>
      </div>
    </div>
  </div>
</body>
</html>
