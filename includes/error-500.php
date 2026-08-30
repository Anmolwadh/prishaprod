<?php
http_response_code(500);
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Server Not Ready | Prisha Enterprises</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container" style="max-width:640px">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-3">Website cannot connect to the database</h1>
        <p>The site is running, but MariaDB/MySQL is not started (or not writable).</p>
        <ol class="mb-3">
          <li>Close this tab.</li>
          <li>Double-click <code>start-server.bat</code> in the project folder.</li>
          <li>Keep that black window open.</li>
          <li>Open: <a href="http://127.0.0.1:8080/">http://127.0.0.1:8080/</a></li>
        </ol>
        <p class="small text-muted mb-0">Do not use <code>http://localhost/</code> without port <code>8080</code>.</p>
      </div>
    </div>
  </div>
</body>
</html>
