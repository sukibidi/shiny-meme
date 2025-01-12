<?php
session_start();
include 'db_connect.php';

// Set default sorting options
$sort_column = 'inv_date';  // Default sorting by date (newest)
$sort_order = 'DESC';  // Default descending order

// Check if sorting options are set via GET request
if (isset($_GET['sort_column']) && isset($_GET['sort_order'])) {
    $sort_column = $_GET['sort_column'];
    $sort_order = $_GET['sort_order'] === 'ASC' ? 'ASC' : 'DESC';  // Sanitize sort order
}

// Fetch invoice details with items bought and sorting
$sql = "SELECT i.inv_no, i.inv_date, i.total_sale, c.cus_name, u1.username AS created_by, u2.username AS modified_by
        FROM Invoice i
        JOIN Customer c ON i.cus_no = c.cus_no
        JOIN Users u1 ON i.created_by = u1.user_id
        JOIN Users u2 ON i.modified_by = u2.user_id
        ORDER BY $sort_column $sort_order";  // Apply sorting here
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching invoices: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
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

        .sort-options {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sort-options select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .item-list {
            text-align: left;
            margin-top: 10px;
        }

        .item-list ul {
            padding-left: 20px;
            list-style-type: disc;
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

        /* Style for spacing between items */
        .invoice-item {
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Clearer distinction for multiple lines */
        .invoice-item span {
            display: block;
            padding-left: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Invoice List</h2>

    <!-- Sorting Options -->
    <div class="sort-options">
        <form method="GET" action="view_invoices.php">
            <label for="sort_column">Sort by:</label>
            <select name="sort_column" id="sort_column">
                <option value="inv_date" <?= $sort_column === 'inv_date' ? 'selected' : '' ?>>Date</option>
                <option value="total_sale" <?= $sort_column === 'total_sale' ? 'selected' : '' ?>>Total Sale</option>
                <option value="cus_name" <?= $sort_column === 'cus_name' ? 'selected' : '' ?>>Customer Name</option>
            </select>

            <select name="sort_order" id="sort_order">
                <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>Ascending</option>
                <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>Descending</option>
            </select>

            <button type="submit">Sort</button>
        </form>
    </div>

    <table>
        <tr>
            <th>No.</th>
            <th>Invoice Number</th>
            <th>Customer Name</th>
            <th>Invoice Date</th>
            <th>Total Sale (RM)</th>
            <th>Items Bought</th>
            <th>Created By</th>
            <th>Modified By</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $count = 1;
            while ($row = $result->fetch_assoc()) {
                $inv_no = $row['inv_no'];

                // Fetch items for this invoice
                $sql_items = "SELECT p.prod_name, il.qty_sold
                              FROM Invoice_Line il
                              JOIN Product p ON il.prod_no = p.prod_no
                              WHERE il.inv_no = ?";
                $stmt_items = $conn->prepare($sql_items);
                $stmt_items->bind_param("i", $inv_no);
                $stmt_items->execute();
                $result_items = $stmt_items->get_result();

                $items = "<div class='item-list'><ul>";
                while ($item = $result_items->fetch_assoc()) {
                    $items .= "<li class='invoice-item'><span>" . $item['prod_name'] . " (Qty: " . $item['qty_sold'] . ")</span></li>";
                }
                $items .= "</ul></div>";

                echo "<tr>";
                echo "<td>" . $count . "</td>";
                echo "<td>" . $row['inv_no'] . "</td>";
                echo "<td>" . $row['cus_name'] . "</td>";
                echo "<td>" . $row['inv_date'] . "</td>";
                echo "<td>" . number_format($row['total_sale'], 2) . "</td>";
                echo "<td>" . $items . "</td>";
                echo "<td>" . $row['created_by'] . "</td>";
                echo "<td>" . $row['modified_by'] . "</td>";
                echo "<td class='action-links'>";
                echo "<a href='update_invoice.php?inv_no=" . $row['inv_no'] . "'>Edit</a>";
                echo "<a href='delete_invoice.php?inv_no=" . $row['inv_no'] . "' onclick='return confirm(\"Are you sure you want to delete this invoice?\");'>Delete</a>";
                echo "</td>";
                echo "</tr>";

                $count++;
            }
        } else {
            echo "<tr><td colspan='9'>No invoices found.</td></tr>";
        }
        ?>
    </table>

    <div class="button-container">
        <a class="back-button" href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
