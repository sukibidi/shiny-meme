<!DOCTYPE html>
<html lang="en">
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Container for the form */
    .container {
        width: 400px;
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #e63946;
        margin-bottom: 20px;
        font-size: 22px;
    }

    label, input {
        display: block;
        width: 94%;
        margin-bottom: 15px;
        font-size: 16px;
    }

    input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    button, .back-button {
        display: block;
        width: 100%;
        padding: 10px 1px;
        border-radius: 5px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button {
        background-color: #e63946;
        color: white;
    }

    button:hover {
        background-color: #d62828;
    }

    .back-button {
        background-color: #333;
        color: white;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
    }

    .back-button:hover {
        background-color: #555;
    }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>
        <!-- HTML form part of create_product.php -->
        <form action="create_product.php" method="POST">
            <label for="prod_name">Product Name:</label>
            <input type="text" id="prod_name" name="prod_name" required>

            <label for="prod_price">Product Price (RM):</label>
            <input type="number" step="0.01" id="prod_price" name="prod_price" required>

            <label for="qoh">Quantity On Hand:</label>
            <input type="number" id="qoh" name="qoh" required>

            <button type="submit">Add Product</button>
        </form>
        <a href="view_products.php" class="back-button">Back to Product List</a>
    </div>
</body>
</html>

<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prod_name = $_POST['prod_name'];  // Product Name instead of prod_desc
    $prod_price = $_POST['prod_price'];
    $qoh = $_POST['qoh'];  // Quantity on Hand
    $created_by = $_SESSION['user_id'];  // Track who created the product

    // Insert the product into the Product table
    $sql = "INSERT INTO Product (prod_name, prod_price, qoh, created_by, modified_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiii", $prod_name, $prod_price, $qoh, $created_by, $created_by);

    if ($stmt->execute()) {
        // Redirect to the success page
        header("Location: create_product_success.php");
        exit();
    } else {
        echo "Error inserting product: " . $stmt->error;
    }
}
?>
