<?php
require_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['product_id']) || !isset($data['quantity'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        exit;
    }

    $product_id = intval($data['product_id']);
    $quantity = intval($data['quantity']);

    $product_query = "SELECT id, stock, name, price FROM products WHERE id = ?";
    $stmt = $con->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
        exit;
    }

    $product = $product_result->fetch_assoc();

    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'error' => 'Insufficient stock.']);
        exit;
    }

    $cart_query = "SELECT id, quantity FROM shopping_cart WHERE user_id = ? AND product_id = ?";
    $stmt = $con->prepare($cart_query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();

    if ($cart_result->num_rows > 0) {
        $cart_item = $cart_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;
        $update_query = "UPDATE shopping_cart SET quantity = ? WHERE id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
        $stmt->execute();
    } else {
        $insert_query = "INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }

    echo json_encode(['success' => true]);
    exit;
}

$cart_items_query = "SELECT shopping_cart.id, shopping_cart.quantity, products.name, products.price
                     FROM shopping_cart
                     JOIN products ON shopping_cart.product_id = products.id
                     WHERE shopping_cart.user_id = ?";
$stmt = $con->prepare($cart_items_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items_result = $stmt->get_result();
$cart_items = [];
$total_price = 0;

while ($item = $cart_items_result->fetch_assoc()) {
    $cart_items[] = $item;
    $total_price += $item['price'] * $item['quantity'];
}
?>