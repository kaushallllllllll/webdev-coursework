<?php
global $conn;
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'php/connect.php';

// Fetch products
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);

// Fetch orders
$order_sql = "SELECT * FROM orders";
$order_result = $conn->query($order_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2, h3 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: right;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }

        th, td {
            padding: 10px;
            font-size: 14px;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        .logout {
            text-align: right;
            margin-top: 20px;
        }

        .logout a {
            color: #dc3545;
        }

        .logout a:hover {
            color: #c82333;
        }

        .confirm-button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <h2>Inventory Management</h2>
    <p>Welcome, <?php echo $_SESSION['username']; ?>! <a href="php/logout.php">Logout</a></p>

    <h3>Product List</h3>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $product_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['sku']; ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock_quantity']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="php/delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Add New Product</h3>
    <form action="php/add_product.php" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <button type="submit">Add Product</button>
    </form>

    <h3>Order List</h3>
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = $order_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['name']; ?></td>
                <td><?php echo $order['email']; ?></td>
                <td><?php echo $order['address']; ?></td>
                <td>$<?php echo number_format($order['total_price'], 2); ?></td> <!-- Display total price -->
                <td><?php echo $order['status']; ?></td>
                <td>
                    <?php if ($order['status'] === 'Pending'): ?>
                        <form action="php/confirm_order.php" method="post" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <button type="submit" class="confirm-button">Confirm Order</button>
                        </form>
                    <?php else: ?>
                        Confirmed
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
