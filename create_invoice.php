<?php
session_start();
include 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cus_no = $_POST['cus_no'];
    $inv_date = date("Y-m-d");
    $created_by = $_SESSION['user_id'];
    $total_sale = 0;

    $conn->begin_transaction();
    try {
        // Insert new invoice
        $sql_inv = "INSERT INTO Invoice (inv_date, total_sale, cus_no, created_by, modified_by) VALUES (?, ?, ?, ?, ?)";
        $stmt_inv = $conn->prepare($sql_inv);
        $stmt_inv->bind_param("sdiii", $inv_date, $total_sale, $cus_no, $created_by, $created_by);
        if (!$stmt_inv->execute()) {
            throw new Exception("Error inserting invoice: " . $stmt_inv->error);
        }
        $inv_no = $conn->insert_id;

        // Process selected products
        if (!empty($_POST['products'])) {
            foreach ($_POST['products'] as $prod_no => $qty_sold) {
                if ($qty_sold > 0) {
                    $sql_prod = "SELECT prod_price, qoh FROM Product WHERE prod_no = ?";
                    $stmt_prod = $conn->prepare($sql_prod);
                    $stmt_prod->bind_param("i", $prod_no);
                    $stmt_prod->execute();
                    $result_prod = $stmt_prod->get_result();

                    if ($result_prod->num_rows > 0) {
                        $product = $result_prod->fetch_assoc();
                        $prod_price = $product['prod_price'];
                        $qoh = $product['qoh'];

                        if ($qty_sold > $qoh) {
                            throw new Exception("Error: Not enough stock for product $prod_no.");
                        }

                        $line_total = $prod_price * $qty_sold;
                        $total_sale += $line_total;
                        $new_qoh = $qoh - $qty_sold;

                        $sql_line = "INSERT INTO Invoice_Line (inv_no, prod_no, qty_sold) VALUES (?, ?, ?)";
                        $stmt_line = $conn->prepare($sql_line);
                        $stmt_line->bind_param("iii", $inv_no, $prod_no, $qty_sold);
                        $stmt_line->execute();

                        $sql_update_stock = "UPDATE Product SET qoh = ? WHERE prod_no = ?";
                        $stmt_update_stock = $conn->prepare($sql_update_stock);
                        $stmt_update_stock->bind_param("ii", $new_qoh, $prod_no);
                        $stmt_update_stock->execute();
                    } else {
                        throw new Exception("Error: Product not found.");
                    }
                }
            }

            $sql_update_invoice = "UPDATE Invoice SET total_sale = ? WHERE inv_no = ?";
            $stmt_update_invoice = $conn->prepare($sql_update_invoice);
            $stmt_update_invoice->bind_param("di", $total_sale, $inv_no);
            $stmt_update_invoice->execute();

            $conn->commit();
            header("Location: view_invoices.php?message=Invoice created successfully");
            exit();
        } else {
            throw new Exception("Error: No products selected.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        die($e->getMessage());
    }
}

// Fetch customers and products
$sql_customers = "SELECT cus_no, cus_name FROM Customer";
$result_customers = $conn->query($sql_customers);

$sql_products = "SELECT prod_no, prod_name, prod_price, qoh FROM Product";
$result_products = $conn->query($sql_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
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
            width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #e63946;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .customer-panel, .product-panel {
            margin-bottom: 20px;
        }

        /* Customer Panel */
        .customer-panel select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Product Panel (scrollable box) */
        .product-panel {
            height: 250px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product-panel .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .product-item label {
            flex: 1;
        }

        .product-item input[type="checkbox"] {
            margin-right: 10px;
        }

        .product-item input[type="number"] {
            width: 80px;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
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
            width: 97%;
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
    <h2>Create Invoice</h2>
    <form action="create_invoice.php" method="POST">

        <!-- Customer Panel -->
        <div class="customer-panel">
            <label for="cus_no">Select Customer:</label>
            <select name="cus_no" id="cus_no" required>
                <?php
                while ($row = $result_customers->fetch_assoc()) {
                    echo "<option value='{$row['cus_no']}'>{$row['cus_name']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Product Panel with scrollable box -->
        <div class="product-panel">
            <h3>Select Products:</h3>
            <?php
            while ($row = $result_products->fetch_assoc()) {
                echo "<div class='product-item'>";
                echo "<label>{$row['prod_name']} (RM {$row['prod_price']}) - Stock: {$row['qoh']}</label>";
                echo "<input type='checkbox' name='products[{$row['prod_no']}]' value='0' onclick='toggleQuantityInput(this, {$row['prod_no']})'>";
                echo "<input type='number' name='products[{$row['prod_no']}]' id='qty_{$row['prod_no']}' placeholder='Qty' min='0' style='display: none;'>";
                echo "</div>";
            }
            ?>
        </div>

        <button type="submit">Create Invoice</button>
    </form>
    <a class="back-button" href="dashboard.php">Back to Dashboard</a>
</div>

<script>
    function toggleQuantityInput(checkbox, prod_no) {
        const qtyInput = document.getElementById('qty_' + prod_no);
        if (checkbox.checked) {
            qtyInput.style.display = 'inline-block';
            qtyInput.value = 1;
        } else {
            qtyInput.style.display = 'none';
            qtyInput.value = 0;
        }
    }
</script>

</body>
</html>
