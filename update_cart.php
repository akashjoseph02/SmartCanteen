<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if cart session exists
    $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // Get POST data
    $itemId = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);

    // Update quantity
    if ($quantity > 0) {
        foreach ($cartItems as &$item) {
            if ($item['id'] === $itemId) {
                $item['quantity'] = $quantity;
            }
        }
        $_SESSION['cart'] = $cartItems;
    }

    // Return updated cart data
    echo json_encode([
        'status' => 'success',
        'cart' => $_SESSION['cart']
    ]);
}
