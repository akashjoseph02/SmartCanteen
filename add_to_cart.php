<?php
session_start();
require 'php/config.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Retrieve POST data
$itemName = $_POST['name'] ?? '';
$itemPrice = $_POST['price'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;
$shopName = $_POST['shop_name'] ?? ''; // Retrieve shop name from POST data

// Validate data
if (empty($itemName) || !is_numeric($itemPrice) || !is_numeric($quantity) || empty($shopName)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

// Retrieve the user's email from the session
$userEmail = $_SESSION['email'];

// Initialize cart in session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update session cart
if (isset($_SESSION['cart'][$itemName])) {
    // Update the quantity of the existing item
    $_SESSION['cart'][$itemName]['quantity'] += $quantity;
} else {
    // Add new item to the cart
    $_SESSION['cart'][$itemName] = [
        'dish_name' => $itemName,
        'price' => $itemPrice,
        'quantity' => $quantity,
        'shop_name' => $shopName // Include shop name
    ];
}

// Update or insert item in the database
$query = "SELECT * FROM cart WHERE email = ? AND dish_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $userEmail, $itemName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Item exists, update the quantity
    $query = "UPDATE cart SET quantity = quantity + ? WHERE email = ? AND dish_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $quantity, $userEmail, $itemName);
} else {
    // Item does not exist, insert new item
    $query = "INSERT INTO cart (email, dish_name, price, quantity, shop_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdis", $userEmail, $itemName, $itemPrice, $quantity, $shopName);
}

$stmt->execute();
$stmt->close();

// Prepare response
$response = [
    'status' => 'success',
    'cart' => $_SESSION['cart']
];

echo json_encode($response);
