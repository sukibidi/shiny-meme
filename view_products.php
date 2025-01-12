<?php
session_start();
include 'db_connect.php';

// Fetch product details including QOH (Quantity on Hand)
$sql = "SELECT * FROM Product";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching products: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #e63946;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #e63946;
            color: white;
        }

        .action-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #e63946;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #555;
        }

        .button-container {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Product List</h2>
    <table>
        <tr>
            <th>No.</th> <!-- Number column -->
            <th>Product Name</th>
            <th>Price (RM)</th>
            <th>Quantity On Hand (QOH)</th> <!-- Display current stock -->
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $count = 1; // Numbering starts from 1
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $count . "</td>"; // Display the number
                echo "<td>" . $row['prod_name'] . "</td>";
                echo "<td>" . number_format($row['prod_price'], 2) . "</td>";
                echo "<td>" . $row['qoh'] . "</td>";  // Display the updated QOH
                echo "<td class='action-links'>";
                echo "<a href='update_product.php?prod_no=" . $row['prod_no'] . "'>Edit</a>";
                echo "<a href='delete_product.php?prod_no=" . $row['prod_no'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>";
                echo "</td>";
                echo "</tr>";
                $count++; // Increment the counter
            }
        } else {
            echo "<tr><td colspan='5'>No products found.</td></tr>";
        }
        ?>
    </table>

    <div class="button-container">
        <a class="back-button" href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
