<?php
include 'config.php'; // Make sure this file contains your database connection details

header('Content-Type: application/json');

$query = "SELECT dish_name, COUNT(*) AS order_count FROM userorders GROUP BY dish_name";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
