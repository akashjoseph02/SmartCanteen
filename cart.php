<?php
session_start();
require 'php/config.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}

$userEmail = $_SESSION['email'];

// Initialize the cart for the logged-in user if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve cart items from the database for the logged-in user
$query = "SELECT dish_name, price, quantity, shop_name FROM cart WHERE email = '$userEmail'";
$result = mysqli_query($conn, $query);

$cartItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[$row['dish_name']] = [
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'shop_name' => $row['shop_name']
    ];
}

$_SESSION['cart'] = $cartItems;

// Handle remove item
if (isset($_GET['remove'])) {
    $itemName = $_GET['remove'];
    if (isset($cartItems[$itemName])) {
        if ($cartItems[$itemName]['quantity'] > 1) {
            $cartItems[$itemName]['quantity']--;
            $quantity = $cartItems[$itemName]['quantity'];
            $query = "UPDATE cart SET quantity = $quantity WHERE email = '$userEmail' AND dish_name = '$itemName'";
            mysqli_query($conn, $query);
        } else {
            unset($cartItems[$itemName]);
            $query = "DELETE FROM cart WHERE email = '$userEmail' AND dish_name = '$itemName'";
            mysqli_query($conn, $query);
        }
        $_SESSION['cart'] = $cartItems;
    }
    header('Location: cart.php');
    exit();
}

$totalAmount = 0;

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO userorders (email, dish_name, quantity, price, shop_name, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    foreach ($cartItems as $itemName => $item) {
        $quantity = $item['quantity'];
        $price = $item['price'];
        $shopName = $item['shop_name'];
        
        // Bind and execute the statement
        if (!$stmt->bind_param("ssiss", $userEmail, $itemName, $quantity, $price, $shopName)) {
            die('Bind failed: ' . htmlspecialchars($stmt->error));
        }
        
        if (!$stmt->execute()) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
    }
    
    $stmt->close();
    
    // Clear the cart after placing the order
    $query = "DELETE FROM cart WHERE email = '$userEmail'";
    if (!mysqli_query($conn, $query)) {
        die('Delete from cart failed: ' . htmlspecialchars($conn->error));
    }
    
    unset($_SESSION['cart']);
    
    // Redirect to order confirmation page
    header('Location: order_confirmation.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        button, .remove-item {
            padding: 5px 10px;
            margin: 5px;
        }
        .remove-item {
            cursor: pointer;
            color: red;
            text-decoration: underline;
        }
    </style>
</head>
<body style="background-color: #4FBBE6;">
    <h1>Your Cart</h1>
    <a href="MainPage.php"><button>Back to Ordering</button></a>
    <?php if (!empty($cartItems)): ?>
        <table id="cart-table">
            <tr>
                <th>Dish Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cartItems as $itemName => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($itemName); ?></td>
                    <td>&#8377; <?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>&#8377; <?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                    <td>
                        <a href="cart.php?remove=<?php echo urlencode($itemName); ?>" class="remove-item">Remove</a>
                    </td>
                </tr>
                <?php $totalAmount += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </table>
        <h2>Total Amount: &#8377; <?php echo $totalAmount; ?></h2>
        <form method="post">
            <button type="submit">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>
