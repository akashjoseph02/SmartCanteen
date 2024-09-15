<?php
session_start();

// Check if cart session exists
if (isset($_SESSION['cart']) && isset($_GET['id'])) {
    $cartItems = $_SESSION['cart'];
    $itemName = $_GET['id'];

    if (isset($cartItems[$itemName])) {
        // Remove item from cart
        unset($cartItems[$itemName]);
        $_SESSION['cart'] = $cartItems;
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
