<?php
session_start();
include 'db_connect.php';

// Fetch customer details
$sql = "SELECT * FROM Customer";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching customers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
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
    <h2>Customer List</h2>
    <table>
        <tr>
            <th>No.</th> <!-- Number column -->
            <th>Customer Name</th>
            <th>Age</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $count = 1; // Numbering starts from 1
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $count . "</td>"; // Display the number
                echo "<td>" . $row['cus_name'] . "</td>";
                echo "<td>" . $row['cus_age'] . "</td>";
                echo "<td>" . $row['cus_contact'] . "</td>";
                echo "<td class='action-links'>";
                echo "<a href='update_customer.php?cus_no=" . $row['cus_no'] . "'>Edit</a>";
                echo "<a href='delete_customer.php?cus_no=" . $row['cus_no'] . "' onclick='return confirm(\"Are you sure you want to delete this customer?\");'>Delete</a>";
                echo "</td>";
                echo "</tr>";
                $count++; // Increment the counter
            }
        } else {
            echo "<tr><td colspan='5'>No customers found.</td></tr>";
        }
        ?>
    </table>

    <div class="button-container">
        <a class="back-button" href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
