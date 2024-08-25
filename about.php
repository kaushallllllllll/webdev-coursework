<?php
global $conn;
session_start();
include 'php/connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Inventory Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .about-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .about-container h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .about-container p {
            margin-bottom: 20px;
            color: #666;
            line-height: 1.6;
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

<!-- About Us Content -->
<div class="about-container">
    <h1>About Us</h1>
    <p>Welcome to the Inventory Management System! Our platform is designed to help businesses efficiently manage their inventory, streamline operations, and improve overall productivity. We offer a range of features to add, edit, and manage products, track stock levels, and handle orders.</p>
    <p>Our mission is to provide a user-friendly system that caters to the needs of both small and large businesses. We believe in the power of technology to simplify business processes and help companies achieve their goals.</p>
    <p>Thank you for choosing our Inventory Management System. We look forward to helping your business grow and succeed!</p>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
