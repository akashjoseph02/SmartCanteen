<?php
session_start();
$userEmail = $_SESSION['email']; // Make sure this is set correctly

$conn = new mysqli('localhost', 'root', '', 'orderapp'); // Adjust DB connection details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total money spent
$totalSpentSql = "SELECT SUM(price * quantity) AS total_spent FROM userorders WHERE email = ?";
$totalSpentStmt = $conn->prepare($totalSpentSql);

if ($totalSpentStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $totalSpentSql);
}

$totalSpentStmt->bind_param("s", $userEmail);
$totalSpentStmt->execute();
$totalSpentResult = $totalSpentStmt->get_result();

if ($totalSpentResult === false) {
    die("Execute failed: " . $totalSpentStmt->error);
}

$totalSpentRow = $totalSpentResult->fetch_assoc();
$totalSpent = $totalSpentRow['total_spent'] ?? 0;
$totalSpentStmt->close();

// Order summary
$orderSummarySql = "SELECT dish_name, SUM(quantity) AS total_quantity FROM userorders WHERE email = ? GROUP BY dish_name";
$orderSummaryStmt = $conn->prepare($orderSummarySql);

if ($orderSummaryStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $orderSummarySql);
}

$orderSummaryStmt->bind_param("s", $userEmail);
$orderSummaryStmt->execute();
$orderSummaryResult = $orderSummaryStmt->get_result();

if ($orderSummaryResult === false) {
    die("Execute failed: " . $orderSummaryStmt->error);
}

$orders = [];
while ($row = $orderSummaryResult->fetch_assoc()) {
    $orders[] = $row;
}
$orderSummaryStmt->close();

// Recent orders (last 3 orders)
$recentOrdersSql = "SELECT dish_name, quantity, price, order_date FROM userorders WHERE email = ? ORDER BY order_date DESC LIMIT 3";
$recentOrdersStmt = $conn->prepare($recentOrdersSql);

if ($recentOrdersStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $recentOrdersSql);
}

$recentOrdersStmt->bind_param("s", $userEmail);
$recentOrdersStmt->execute();
$recentOrdersResult = $recentOrdersStmt->get_result();

if ($recentOrdersResult === false) {
    die("Execute failed: " . $recentOrdersStmt->error);
}

$recentOrders = [];
while ($row = $recentOrdersResult->fetch_assoc()) {
    $recentOrders[] = $row;
}
$recentOrdersStmt->close();

// All recent orders for the modal
$allRecentOrdersSql = "SELECT dish_name, quantity, price, order_date FROM userorders WHERE email = ? ORDER BY order_date DESC";
$allRecentOrdersStmt = $conn->prepare($allRecentOrdersSql);

if ($allRecentOrdersStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $allRecentOrdersSql);
}

$allRecentOrdersStmt->bind_param("s", $userEmail);
$allRecentOrdersStmt->execute();
$allRecentOrdersResult = $allRecentOrdersStmt->get_result();

if ($allRecentOrdersResult === false) {
    die("Execute failed: " . $allRecentOrdersStmt->error);
}

$allRecentOrders = [];
while ($row = $allRecentOrdersResult->fetch_assoc()) {
    $allRecentOrders[] = $row;
}
$allRecentOrdersStmt->close();

// Pie Chart for Orders by Shop
$shopOrdersSql = "SELECT shop_name, COUNT(*) AS total_orders FROM userorders WHERE email = ? GROUP BY shop_name";
$shopOrdersStmt = $conn->prepare($shopOrdersSql);

if ($shopOrdersStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $shopOrdersSql);
}

$shopOrdersStmt->bind_param("s", $userEmail);
$shopOrdersStmt->execute();
$shopOrdersResult = $shopOrdersStmt->get_result();

if ($shopOrdersResult === false) {
    die("Execute failed: " . $shopOrdersStmt->error);
}

$shopOrders = [];
while ($row = $shopOrdersResult->fetch_assoc()) {
    $shopOrders[] = $row;
}
$shopOrdersStmt->close();

// Order Volume Over Time
$orderVolumeSql = "SELECT DATE(order_date) AS order_date, COUNT(*) AS order_count FROM userorders WHERE email = ? GROUP BY DATE(order_date)";
$orderVolumeStmt = $conn->prepare($orderVolumeSql);

if ($orderVolumeStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $orderVolumeSql);
}

$orderVolumeStmt->bind_param("s", $userEmail);
$orderVolumeStmt->execute();
$orderVolumeResult = $orderVolumeStmt->get_result();

if ($orderVolumeResult === false) {
    die("Execute failed: " . $orderVolumeStmt->error);
}

$orderVolume = [];
while ($row = $orderVolumeResult->fetch_assoc()) {
    $orderVolume[] = $row;
}
$orderVolumeStmt->close();

// Fetch count of each food item ordered by the user
$itemCountsSql = "SELECT dish_name, SUM(quantity) AS total_quantity FROM userorders WHERE email = ? GROUP BY dish_name";
$itemCountsStmt = $conn->prepare($itemCountsSql);

if ($itemCountsStmt === false) {
    die("Prepare failed: " . $conn->error . "\nSQL: " . $itemCountsSql);
}

$itemCountsStmt->bind_param("s", $userEmail);
$itemCountsStmt->execute();
$itemCountsResult = $itemCountsStmt->get_result();

if ($itemCountsResult === false) {
    die("Execute failed: " . $itemCountsStmt->error);
}

$itemCounts = [];
while ($row = $itemCountsResult->fetch_assoc()) {
    $itemCounts[] = $row;
}
$itemCountsStmt->close();

$conn->close();
?>



<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e2e2e2, #ffffff);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .dashboard-header,
        .chart-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            flex: 1;
            margin: 10px;
            transition: all 0.3s ease-in-out;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.1), transparent);
            border-radius: 12px;
            z-index: 0;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card h3 {
            margin: 0;
            z-index: 1;
            color: #333;
        }

        .card p {
            font-size: 1.5em;
            color: #007bff;
            margin: 10px 0 0;
            z-index: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            transition: background-color 0.3s ease-in-out;
        }

        table th {
            background-color: #f4f4f4;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .chart-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            max-height: 400px;
        }

        .chart-container2 {
            display: flex;
            max-height: 400px;
            width: 100%;
            padding-left: 200px;
        }

        .chart-item {
            width: 45%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            padding: 70px;
            box-sizing: border-box;
            height: 400px;
            margin: 10px;
        }

        .chart-item2 {
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            padding: 10px;
            box-sizing: border-box;
            height: 400px;
            margin: 10px;
            
        }

        .chart-item h2 {
            margin: 0 0 15px;
            color: #333;
        }

        .show-more {
            display: inline-block;
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background: #ffffff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-content .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .modal-content .close:hover,
        .modal-content .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .home-link {
            position: absolute;
            top: 20px;
            right: 20px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <a href="MainPage.php" class="home-link">Home</a>
    <div class="container">
        <h1>Dashboard</h1>

        <div class="dashboard-header">
            <div class="card">
                <h3>Total Money Spent</h3>
                <p>&#8377; <?php echo number_format($totalSpent, 2); ?></p>
            </div>

            <div class="card">
                <h3>Total Orders</h3>
                <p><?php echo count($orders); ?></p>
            </div>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <table>
                <tr>
                    <th>Dish Name</th>
                    <th>Quantity</th>
                </tr>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo $order['dish_name']; ?></td>
                        <td><?php echo $order['total_quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="recent-orders">
            <h2>Recent Orders</h2>
            <table>
                <tr>
                    <th>Dish Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Order Date</th>
                </tr>
                <?php foreach ($recentOrders as $order) : ?>
                    <tr>
                        <td><?php echo $order['dish_name']; ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td>&#8377; <?php echo number_format($order['price'], 2); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <span class="show-more" onclick="document.getElementById('myModal').style.display='block'">Show All Recent Orders</span>
        </div>
        <br><br>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
                <h2>All Recent Orders</h2>
                <table>
                    <tr>
                        <th>Dish Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Order Date</th>
                    </tr>
                    <?php foreach ($allRecentOrders as $order) : ?>
                        <tr>
                            <td><?php echo $order['dish_name']; ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>&#8377; <?php echo number_format($order['price'], 2); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($order['order_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-item">
                <h2>Orders by Shop</h2>
                <canvas id="ordersByShop"></canvas>
            </div>
            <div class="chart-item">
                <h2>Order Volume Over Time</h2>
                <canvas id="orderVolume"></canvas>
            </div>
        </div>
        <div class="chart-container2">
        <center><div class="chart-item2">
                <h1>Food Item Count</h1>
                <canvas id="foodItemCountChart"></canvas>
            </div></center>
            <!-- <div class="chart-item">
                <h1>Comparison</h1>
                <canvas id="popularDishesChart"></canvas>
            </div> -->
        </div>

    </div>

    <script>
        var ordersByShopCtx = document.getElementById('ordersByShop').getContext('2d');
        var ordersByShopData = {
            labels: <?php echo json_encode(array_column($shopOrders, 'shop_name')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($shopOrders, 'total_orders')); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        };
        var ordersByShopChart = new Chart(ordersByShopCtx, {
            type: 'pie',
            data: ordersByShopData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (context.parsed) {
                                    label += ': ' + context.parsed + ' orders';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        var orderVolumeCtx = document.getElementById('orderVolume').getContext('2d');
        var orderVolumeData = {
            labels: <?php echo json_encode(array_column($orderVolume, 'order_date')); ?>,
            datasets: [{
                label: 'Order Volume',
                data: <?php echo json_encode(array_column($orderVolume, 'order_count')); ?>,
                fill: false,
                borderColor: '#36A2EB',
                tension: 0.1,
                pointRadius: 5
            }]
        };
        var orderVolumeChart = new Chart(orderVolumeCtx, {
            type: 'line',
            data: orderVolumeData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (context.parsed.y !== null) {
                                    label += ': ' + context.parsed.y + ' orders';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        var ctx = document.getElementById('foodItemCountChart').getContext('2d');
        var foodItemCountChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($itemCounts, 'dish_name')); ?>,
                datasets: [{
                    label: 'Count of Orders',
                    data: <?php echo json_encode(array_column($itemCounts, 'total_quantity')); ?>,
                    backgroundColor: '#FFCE56',
                    borderColor: '#FFCE56',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (context.parsed.y !== null) {
                                    label += ': ' + context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateTotalSpent() {
            fetch('total_money_spent.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('total-spent').textContent = `Total Spent: $${data}`;
                });
        }

        // Call this function on page load and periodically (e.g., every minute)
        window.onload = updateTotalSpent;
        setInterval(updateTotalSpent, 30000); // Update every minute

        function deleteItem(itemId) {
            fetch('delete_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `item_id=${itemId}`
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    updateTotalSpent(); // Refresh dashboard data
                });
        }

        // Example usage: deleteItem(1);


        // Function to fetch and update total money spent
        function updateTotalMoney() {
            $.ajax({
                url: 'get_total_money.php',
                method: 'GET',
                success: function(data) {
                    $('#total-money').text(data);
                }
            });
        }

        // Function to fetch and update orders by shop
        function updateOrdersByShop() {
            $.ajax({
                url: 'get_orders_by_shop.php',
                method: 'GET',
                success: function(data) {
                    var chartData = JSON.parse(data);
                    // Update your pie chart with new data
                    updatePieChart(chartData);
                }
            });
        }

        // Function to fetch and update food item count
        function updateFoodItemCount() {
            $.ajax({
                url: 'get_food_item_count.php',
                method: 'GET',
                success: function(data) {
                    var chartData = JSON.parse(data);
                    // Update your bar chart with new data
                    updateBarChart(chartData);
                }
            });
        }

        // Function to update all dashboard components
        function updateDashboard() {
            updateTotalMoney();
            updateOrdersByShop();
            updateFoodItemCount();
        }

        // Call the updateDashboard function periodically
        setInterval(updateDashboard, 30000); // Refresh every 30 seconds

        // Call the updateDashboard function on page load
        $(document).ready(function() {
            updateDashboard();
        });

        // Most Popular Dishes
        fetch('php/get_popular_dishes.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data); // Log the data
                const ctx = document.getElementById('popularDishesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.map(item => item.dish_name),
                        datasets: [{
                            label: 'Order Count',
                            data: data.map(item => item.order_count),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>

</html>