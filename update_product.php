<?php
session_start(); // Start session to track user ID for 'modified_by'

include 'db_connect.php';

// Check if product ID is provided
if (!isset($_GET['prod_no'])) {
    die("No product ID provided.");
}

$prod_no = $_GET['prod_no'];

// Fetch the product details
$sql_product = "SELECT * FROM Product WHERE prod_no = ?";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->bind_param("i", $prod_no);
$stmt_product->execute();
$result_product = $stmt_product->get_result();

if ($result_product->num_rows === 0) {
    die("Product not found.");
}

$product = $result_product->fetch_assoc();

// Handle form submission to update product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated product data
    $prod_name = $_POST['prod_name'];
    $prod_price = $_POST['prod_price'];
    $qoh = $_POST['qoh'];
    $modified_by = $_SESSION['user_id'];  // Track who modified the data

    // Update the product details in the database
    $sql_update = "UPDATE Product SET prod_name = ?, prod_price = ?, qoh = ?, modified_by = ? WHERE prod_no = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sdiii", $prod_name, $prod_price, $qoh, $modified_by, $prod_no);

    if ($stmt_update->execute()) {
        // Redirect to product list with success message
        header("Location: view_products.php?message=Product updated successfully");
        exit();
    } else {
        echo "Error updating product: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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
        }

        label, input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            font-size: 16px;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #e63946;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #d62828;
        }

        .back-button {
            display: block;
            width: 95%;
            padding: 10px;
            background-color: #333;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Product</h2>
    <form action="update_product.php?prod_no=<?php echo $prod_no; ?>" method="POST">
        <label for="prod_name">Product Name:</label>
        <input type="text" id="prod_name" name="prod_name" value="<?php echo htmlspecialchars($product['prod_name']); ?>" required>

        <label for="prod_price">Product Price (RM):</label>
        <input type="number" step="0.01" id="prod_price" name="prod_price" value="<?php echo htmlspecialchars($product['prod_price']); ?>" required>

        <label for="qoh">Quantity on Hand (QOH):</label>
        <input type="number" id="qoh" name="qoh" value="<?php echo htmlspecialchars($product['qoh']); ?>" required>

        <button type="submit">Update Product</button>
    </form>
    <a class="back-button" href="view_products.php">Back to Product List</a>
</div>

</body>
</html>
