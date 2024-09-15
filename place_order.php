<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'php/config.php'; // Ensure this path is correct

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['email'];

// Prepare to fetch cart items
$sql = "SELECT * FROM cart WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$cartItems = $stmt->get_result();

// Prepare to insert into userorders
$insertOrderSql = "INSERT INTO userorders (reg_id, email, dish_name, quantity, price, shop_name, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
$insertOrderStmt = $conn->prepare($insertOrderSql);
if (!$insertOrderStmt) {
    die("Prepare failed: " . $conn->error);
}

while ($item = $cartItems->fetch_assoc()) {
    $reg_id = $item['reg_id'];
    $dish_name = $item['dish_name'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $shop_name = $item['shop_name']; // Ensure shop_name is correctly retrieved
    $order_date = date("Y-m-d H:i:s"); // Current date and time

    $insertOrderStmt->bind_param("issdsss", $reg_id, $email, $dish_name, $quantity, $price, $shop_name, $order_date);
    if (!$insertOrderStmt->execute()) {
        die("Execute failed: " . $insertOrderStmt->error);
    }
}

// Clear the cart after placing the order
$clearCartSql = "DELETE FROM cart WHERE email = ?";
$clearCartStmt = $conn->prepare($clearCartSql);
if (!$clearCartStmt) {
    die("Prepare failed: " . $conn->error);
}
$clearCartStmt->bind_param("s", $email);
if (!$clearCartStmt->execute()) {
    die("Execute failed: " . $clearCartStmt->error);
}

// Close statements and connection
$insertOrderStmt->close();
$clearCartStmt->close();
$conn->close();

// JavaScript to show success message and redirect to order_confirmation.php
echo "<script>
    alert('Order placed successfully!');
    window.location.href = 'order_confirmation.php';
</script>";

exit();
?>
