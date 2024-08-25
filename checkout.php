<?php
global $conn, $conn;
session_start();
include 'php/connect.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: shop.php"); // Redirect to shop if the cart is empty
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $cart = $_SESSION['cart'];
    $total_price = $_POST['total_price'];

    // Insert order details into the database
    $sql = "INSERT INTO orders (name, email, address, total_price, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $name, $email, $address, $total_price);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        // Insert each cart item into order_items table
        foreach ($cart as $product_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt->execute();
        }

        // Clear the cart
        unset($_SESSION['cart']);

        header("Location: order_confirmation.php?order_id=$order_id"); // Redirect to order confirmation
        exit();
    } else {
        $error = "There was an issue processing your order. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Inventory Management System</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .checkout-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .checkout-container h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .checkout-container label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .checkout-container input,
        .checkout-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .checkout-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .checkout-button:hover {
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
        <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
    </ul>
</nav>

<!-- Checkout Form -->
<div class="checkout-container">
    <h1>Checkout</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="checkout.php" method="post">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="address">Shipping Address:</label>
        <textarea id="address" name="address" required></textarea>

        <input type="hidden" name="total_price" value="<?php echo $_POST['total_price']; ?>">
        <button type="submit" class="checkout-button">Confirm Order</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
