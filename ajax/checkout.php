<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Checkout is handled via checkout.php POST; this endpoint can validate cart before submit
$input = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($input)) {
    $input = $_POST;
}
$token = $input['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verify_csrf(is_string($token) ? $token : null)) {
    json_response(['success' => false, 'message' => 'Invalid security token.'], 403);
}

try {
    $items = cart();
    if (!$items) {
        json_response(['success' => false, 'message' => 'Cart is empty.'], 400);
    }
    $pdo = getDB();
    foreach ($items as $item) {
        $stmt = $pdo->prepare("SELECT stock, status, name FROM products WHERE id = ?");
        $stmt->execute([(int)$item['product_id']]);
        $p = $stmt->fetch();
        if (!$p || $p['status'] !== 'Active') {
            json_response(['success' => false, 'message' => 'A product is unavailable.'], 400);
        }
        if ((int)$item['qty'] > (int)$p['stock']) {
            json_response(['success' => false, 'message' => $p['name'] . ' has only ' . (int)$p['stock'] . ' left.'], 400);
        }
    }
    json_response(['success' => true, 'message' => 'Cart is valid.', 'totals' => cart_totals()]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    json_response(['success' => false, 'message' => 'Validation failed.'], 500);
}
