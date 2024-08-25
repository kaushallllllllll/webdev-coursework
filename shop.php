<?php
global $conn;
session_start();
include 'php/connect.php';  // Correct path to the connect.php file

// Initialize or retrieve the cart from the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: shop.php"); // Redirect to avoid form resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Inventory Management System</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        nav {
            background-color: #333;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .login-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-left: 20px;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        .shop-container {
            padding: 20px;
            text-align: center;
            background-color: white;
            margin: 20px auto;
            max-width: 1200px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .shop-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding-right: 15px;
        }

        .product {
            background-color: #fff;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 20px;
            width: 220px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h3 {
            font-size: 1.2em;
            margin: 15px 0;
            color: #333;
        }

        .product p {
            font-size: 1em;
            color: #666;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 50px;
        }

        .login-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: left;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container label {
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .login-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container p {
            text-align: center;
            margin-top: 15px;
        }

        .login-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container p a:hover {
            text-decoration: underline;
        }
        .cart-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
            display: inline-block;
            margin-top: 10px;
        }

        .cart-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact_form.php">Contact</a></li>
        <li><a href="cart.php">Cart (<?php echo count($_SESSION['cart']); ?>)</a></li> <!-- Cart link -->
    </ul>
    <a href="customer_logout.php" class="login-button">Logout</a>
</nav>

<!-- Main Content Area -->
<div class="shop-container">
    <h1>Our Products</h1>

    <div class="product-list">
        <?php
        $sql = "SELECT id, product_name, price, image_url FROM products";
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error: " . $conn->error;
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                echo '<img src="'.$row['image_url'].'" alt="'.$row['product_name'].'">';
                echo '<h3>'.$row['product_name'].'</h3>';
                echo '<p>$'.$row['price'].'</p>';
                echo '<form action="shop.php" method="post">';
                echo '<input type="hidden" name="product_id" value="'.$row['id'].'">';
                echo '<button type="submit" name="add_to_cart" class="cart-button">Add to Cart</button>';
                echo '</form>';
                echo '<a href="product_details.php?id='.$row['id'].'" class="btn">View Details</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available at the moment.</p>';
        }

        $conn->close();
        ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
