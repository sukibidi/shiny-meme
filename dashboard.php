<?php
session_start();

// Assuming you're storing the user's name in session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 800px;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #e63946;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        a.button {
            display: block;
            width: 200px;
            padding: 15px;
            margin: 10px;
            text-decoration: none;
            background-color: #e63946;
            color: white;
            font-size: 18px;
            text-align: center;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        a.button:hover {
            background-color: #d62828;
            transform: scale(1.05);
        }

        a.button:active {
            background-color: #b5171b;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 30px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #555;
            transform: scale(1.05);
        }

        .back-button:active {
            background-color: #444;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Dashboard</h1>
    
    <div class="welcome-message">
        Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!
    </div>
    
    <div class="button-container">
        <a class="button" href="view_customers.php">View Customers</a>
        <a class="button" href="view_products.php">View Products</a>
        <a class="button" href="view_invoices.php">View Invoices</a>
        <a class="button" href="create_customer.php">Add Customer</a>
        <a class="button" href="create_product.php">Add Product</a>
        <a class="button" href="create_invoice.php">Create Invoice</a>
     
    </div>
    
    <a class="back-button" href="logout.php">Logout</a>
</div>

</body>
</html>
