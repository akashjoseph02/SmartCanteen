<?php
// delete_item.php
include 'php/config.php'; // Ensure this includes your DB connection

$item_id = $_POST['item_id'];
$query = "DELETE FROM userorders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $item_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo 'Item deleted successfully';
} else {
    echo 'Failed to delete item';
}
?>
