<?php
global $conn, $conn;
session_start();
include 'php/connect.php';

// Get the order ID from the query string
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Fetch the order details
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Fetch the order items
$sql = "SELECT p.product_name, oi.quantity, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Inventory Management System</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .confirmation-container h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .confirmation-container p {
            margin-bottom: 10px;
            color: #666;
        }

        .confirmation-container ul {
            list-style: none;
            padding: 0;
        }

        .confirmation-container ul li {
            margin-bottom: 10px;
            color: #333;
        }

        .confirmation-container ul li span {
            font-weight: bold;
        }

        .confirmation-container .back-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 20px;
            text-decoration: none;
        }

        .confirmation-container .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
    </ul>
</nav>

<!-- Order Confirmation Details -->
<div class="confirmation-container">
    <h1>Order Confirmation</h1>
    <p>Thank you for your order, <?php echo $order['name']; ?>.</p>
    <p>Order ID: <?php echo $order['id']; ?></p>
    <p>Total Price: $<?php echo number_format($order['total_price'], 2); ?></p>
    <h2>Order Details</h2>
    <ul>
        <?php foreach ($order_items as $item): ?>
            <li><?php echo $item['quantity']; ?> x <?php echo $item['product_name']; ?> - $<?php echo number_format($item['price'], 2); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php" class="back-button">Back to Home</a>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
