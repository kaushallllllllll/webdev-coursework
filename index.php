<?php
global $conn;
session_start();
include 'php/connect.php';  // Correct path to the connect.php file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Inventory Management System</title>
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

        .home-container {
            padding: 20px;
            text-align: center;
            background-color: white;
            margin: 20px auto;
            max-width: 1200px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .home-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .home-container p {
            margin-bottom: 40px;
            color: #666;
        }

        .featured-products {
            margin: 20px auto;
            max-width: 1200px;
            text-align: center;
        }

        .featured-products h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
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
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin.php" class="login-button">Dashboard</a>
        <a href="php/logout.php" class="login-button">Logout</a>
    <?php else: ?>
        <a href="#admin-login" class="login-button">Admin Login</a>
    <?php endif; ?>
</nav>

<!-- Main Content Area -->
<div class="home-container">
    <h1>Welcome to the Inventory Management System</h1>
    <p>This is a simple system to manage your inventory. You can add, edit, and delete products, manage your stock levels, and more.</p>

    <!-- Admin Login Form -->
    <div id="admin-login" class="login-container">
        <h2>Admin Login</h2>
        <form action="php/login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="hidden" name="role" value="admin">
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-list">
            <?php
            // Fetch featured products from the database
            $sql = "SELECT id, product_name, price, image_url FROM products WHERE is_featured = 1 LIMIT 4";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Error: " . $conn->error;
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product">';
                    echo '<img src="' . $row['image_url'] . '" alt="' . $row['product_name'] . '">';
                    echo '<h3>' . $row['product_name'] . '</h3>';
                    echo '<p>$' . $row['price'] . '</p>';
                    echo '<a href="product_details.php?id=' . $row['id'] . '" class="btn">View Details</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No featured products available at the moment.</p>';
            }

            $conn->close();
            ?>
        </div>
    </section>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
