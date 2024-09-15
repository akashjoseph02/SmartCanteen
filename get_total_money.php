<?php
// total_money_spent.php
include 'php\config.php'; // Ensure this includes your DB connection

$query = "SELECT SUM(price * quantity) AS total_spent FROM userorders WHERE email = ?"; // Modify this query based on your requirement
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $_SESSION['user_email']); // Assumes user email is stored in session
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo $row['total_spent'];
?>
