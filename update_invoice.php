<?php
session_start();
include 'db_connect.php';

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if invoice number is provided
if (!isset($_GET['inv_no'])) {
    die("No invoice number provided.");
}

$inv_no = $_GET['inv_no'];

// Fetch the invoice details
$sql_invoice = "SELECT * FROM Invoice WHERE inv_no = ?";
$stmt_invoice = $conn->prepare($sql_invoice);
$stmt_invoice->bind_param("i", $inv_no);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();

// Check if the invoice exists
if ($result_invoice->num_rows === 0) {
    die("Invoice not found.");
}

$invoice = $result_invoice->fetch_assoc();

// Fetch the line items (products in the invoice)
$sql_line_items = "SELECT il.*, p.prod_name, p.prod_price FROM Invoice_Line il
                   JOIN Product p ON il.prod_no = p.prod_no WHERE il.inv_no = ?";
$stmt_line_items = $conn->prepare($sql_line_items);
$stmt_line_items->bind_param("i", $inv_no);
$stmt_line_items->execute();
$result_line_items = $stmt_line_items->get_result();

// If form is submitted, process the update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // Step 1: Update the invoice date
        $inv_date = $_POST['inv_date'];
        $sql_update_invoice = "UPDATE Invoice SET inv_date = ?, modified_by = ? WHERE inv_no = ?";
        $stmt_update_invoice = $conn->prepare($sql_update_invoice);
        $stmt_update_invoice->bind_param("sii", $inv_date, $_SESSION['user_id'], $inv_no);
        if (!$stmt_update_invoice->execute()) {
            throw new Exception("Error updating invoice date: " . $stmt_update_invoice->error);
        }

        // Step 2: Update each product in the invoice
        $total_sale = 0;  // Recalculate total sale
        foreach ($_POST['products'] as $prod_no => $qty_sold) {
            // Fetch the product price from the Product table
            $sql_prod = "SELECT prod_price FROM Product WHERE prod_no = ?";
            $stmt_prod = $conn->prepare($sql_prod);
            $stmt_prod->bind_param("i", $prod_no);
            $stmt_prod->execute();
            $result_prod = $stmt_prod->get_result();

            if ($result_prod->num_rows === 0) {
                throw new Exception("Product not found with prod_no: " . $prod_no);
            }

            $product = $result_prod->fetch_assoc();
            $prod_price = $product['prod_price'];

            // Calculate the new line total
            $line_total = $prod_price * $qty_sold;
            $total_sale += $line_total;

            // Update the quantity sold in the Invoice_Line table
            $sql_update_line = "UPDATE Invoice_Line SET qty_sold = ? WHERE inv_no = ? AND prod_no = ?";
            $stmt_update_line = $conn->prepare($sql_update_line);
            $stmt_update_line->bind_param("iii", $qty_sold, $inv_no, $prod_no);
            if (!$stmt_update_line->execute()) {
                throw new Exception("Error updating product line for prod_no: " . $prod_no);
            }
        }

        // Step 3: Update the total sale in the Invoice table
        $sql_update_total = "UPDATE Invoice SET total_sale = ? WHERE inv_no = ?";
        $stmt_update_total = $conn->prepare($sql_update_total);
        $stmt_update_total->bind_param("di", $total_sale, $inv_no);
        if (!$stmt_update_total->execute()) {
            throw new Exception("Error updating total sale: " . $stmt_update_total->error);
        }

        // Commit the transaction if all updates were successful
        $conn->commit();

        // Redirect with a success message
        header("Location: view_invoices.php?message=Invoice updated successfully");
        exit();

    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Invoice</title>
    <style>
        /* Minimalist CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 500px;
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
            margin-bottom: 10px;
            font-size: 16px;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
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
            margin-top: 10px;
        }

        button:hover {
            background-color: #d62828;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Invoice #<?php echo $inv_no; ?></h2>

    <form action="update_invoice.php?inv_no=<?php echo $inv_no; ?>" method="POST">
        <label for="inv_date">Invoice Date:</label>
        <input type="date" name="inv_date" value="<?php echo $invoice['inv_date']; ?>" required>

        <label>Products in Invoice:</label>
        <?php
        while ($line_item = $result_line_items->fetch_assoc()) {
            echo "<div>";
            echo "<label>{$line_item['prod_name']} (RM {$line_item['prod_price']})</label>";
            echo "<input type='number' name='products[{$line_item['prod_no']}]' value='{$line_item['qty_sold']}' min='0'>";
            echo "</div>";
        }
        ?>

        <button type="submit">Update Invoice</button>
    </form>

    <a class="back-button" href="view_invoices.php">Back to Invoice List</a>
</div>
</body>
</html>
