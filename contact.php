<?php
global $conn;
session_start();
include 'php/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "INSERT INTO contact_form (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        $success_message = "Thank you for contacting us. We will get back to you soon.";
    } else {
        $error_message = "There was an error submitting your message. Please try again later.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Inventory Management System</title>
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

        .contact-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .contact-container h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .contact-container form {
            display: flex;
            flex-direction: column;
        }

        .contact-container label {
            margin-bottom: 5px;
            color: #333;
        }

        .contact-container input[type="text"],
        .contact-container input[type="email"],
        .contact-container textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
        }

        .contact-container button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .contact-container button:hover {
            background-color: #218838;
        }

        .contact-container .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1em;
            color: #333;
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

<!-- Contact Form -->
<div class="contact-container">
    <h1>Contact Us</h1>

    <?php if (isset($success_message)): ?>
        <p class="message"><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p class="message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="contact.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Send Message</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
</footer>
</body>
</html>
