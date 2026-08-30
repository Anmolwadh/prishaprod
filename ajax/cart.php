<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($input)) {
    $input = $_POST;
}

$token = $input['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verify_csrf(is_string($token) ? $token : null)) {
    json_response(['success' => false, 'message' => 'Invalid security token.'], 403);
}

$action = (string)($input['action'] ?? '');
$productId = (int)($input['product_id'] ?? 0);
$qty = (int)($input['qty'] ?? 1);

try {
    $pdo = getDB();

    if ($action === 'add' || $action === 'update') {
        if ($productId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid product.'], 400);
        }
        $stmt = $pdo->prepare("SELECT id, name, sku, price, stock, image, pack_size, gst FROM products WHERE id = ? AND status = 'Active'");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        if (!$product) {
            json_response(['success' => false, 'message' => 'Product not found.'], 404);
        }

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($action === 'add') {
            $current = (int)($_SESSION['cart'][$productId]['qty'] ?? 0);
            $qty = $current + max(1, $qty);
        } else {
            $qty = max(0, $qty);
        }

        if ($qty === 0) {
            unset($_SESSION['cart'][$productId]);
            $totals = cart_totals();
            json_response([
                'success' => true,
                'message' => 'Item removed.',
                'cart_count' => cart_count(),
                'totals' => $totals,
                'cart' => array_values($_SESSION['cart']),
            ]);
        }

        if ($qty > (int)$product['stock']) {
            json_response(['success' => false, 'message' => 'Only ' . (int)$product['stock'] . ' units available in stock.'], 400);
        }

        $_SESSION['cart'][$productId] = [
            'product_id' => (int)$product['id'],
            'name' => $product['name'],
            'sku' => $product['sku'],
            'price' => (float)$product['price'],
            'qty' => $qty,
            'image' => $product['image'],
            'pack_size' => $product['pack_size'],
            'stock' => (int)$product['stock'],
            'gst' => max(0, min(100, (float)($product['gst'] ?? 0))),
        ];

        $totals = cart_totals();
        json_response([
            'success' => true,
            'message' => $action === 'add' ? 'Product added to cart.' : 'Cart updated.',
            'cart_count' => cart_count(),
            'totals' => $totals,
            'cart' => array_values($_SESSION['cart']),
        ]);
    }

    if ($action === 'remove') {
        unset($_SESSION['cart'][$productId]);
        $totals = cart_totals();
        json_response([
            'success' => true,
            'message' => 'Item removed.',
            'cart_count' => cart_count(),
            'totals' => $totals,
            'cart' => array_values($_SESSION['cart'] ?? []),
        ]);
    }

    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        json_response([
            'success' => true,
            'message' => 'Cart cleared.',
            'cart_count' => 0,
            'totals' => cart_totals(),
            'cart' => [],
        ]);
    }

    if ($action === 'get') {
        json_response([
            'success' => true,
            'cart_count' => cart_count(),
            'totals' => cart_totals(),
            'cart' => array_values(cart()),
        ]);
    }

    json_response(['success' => false, 'message' => 'Unknown action.'], 400);
} catch (Throwable $e) {
    error_log($e->getMessage());
    json_response(['success' => false, 'message' => 'Unable to update cart.'], 500);
}
