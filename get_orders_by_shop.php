<?php
include 'php\config.php';

$query = "SELECT shop_name, COUNT(*) AS order_count FROM userorders GROUP BY shop_name";
$result = $conn->query($query);

$orders_by_shop = array();
while ($row = $result->fetch_assoc()) {
    $orders_by_shop[] = $row;
}

echo json_encode($orders_by_shop);

$conn->close();
?>
