<?php
include 'config.php';

// Adjust query to include user identification
$user_id = $_SESSION['user_id']; // Assume user ID is stored in session
$query = "SELECT dish_name, COUNT(*) AS item_count FROM userorders WHERE user_id = ? GROUP BY dish_name";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$food_item_count = array();
while ($row = $result->fetch_assoc()) {
    $food_item_count[] = $row;
}

echo json_encode($food_item_count);

$stmt->close();
$conn->close();
?>
