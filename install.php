<?php
/**
 * One-time installer / password reset helper
 * Visit: http://localhost/prisha-enterprises/install.php
 * Delete this file after setup for security.
 */
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'test') {
            $pdo = getDB();
            $message = 'Database connection successful. Database: ' . DB_NAME;
        } elseif ($action === 'reset_admin') {
            $pdo = getDB();
            $pass = trim((string)($_POST['password'] ?? 'Admin@123'));
            if (strlen($pass) < 6) {
                throw new RuntimeException('Password must be at least 6 characters.');
            }
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE admins SET password = ? WHERE username = ?');
            $stmt->execute([$hash, 'admin']);
            if ($stmt->rowCount() === 0) {
                $pdo->prepare('INSERT INTO admins (username, email, password, name, status) VALUES (?,?,?,?,?)')
                    ->execute(['admin', 'admin@prishaenterprises.com', $hash, 'Administrator', 'Active']);
            }
            $message = 'Admin password updated. Username: admin';
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Install | Prisha Enterprises</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:640px">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h1 class="h4">Prisha Enterprises Setup</h1>
      <ol class="small">
        <li>Copy this folder to <code>C:\xampp\htdocs\prisha-enterprises</code></li>
        <li>Start Apache + MySQL in XAMPP</li>
        <li>Import <code>database/prisha_enterprises.sql</code> in phpMyAdmin</li>
        <li>Confirm DB settings in <code>config/database.php</code></li>
        <li>Reset admin password below, then delete this file</li>
      </ol>
      <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post" class="mb-3">
        <input type="hidden" name="action" value="test">
        <button class="btn btn-outline-success" type="submit">Test Database Connection</button>
      </form>
      <form method="post">
        <input type="hidden" name="action" value="reset_admin">
        <label class="form-label">Set Admin Password</label>
        <div class="input-group">
          <input type="text" name="password" class="form-control" value="Admin@123">
          <button class="btn btn-success" type="submit">Update Admin Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
