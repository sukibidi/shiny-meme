<?php
include 'db_connect.php';

// Enable error reporting to see issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cus_no = $_POST['cus_no'];
    $inv_date = date('Y-m-d');
    
    // Insert invoice into the Invoice table
    $stmt = $conn->prepare("INSERT INTO Invoice (cus_no, inv_date, total_sale) VALUES (?, ?, 0)");
    
    if (!$stmt) {
        // If statement preparation failed
        echo "Error preparing query: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param('is', $cus_no, $inv_date);
    
    if ($stmt->execute()) {
        $inv_no = $conn->insert_id; // Get the inserted invoice number
        
        // Insert invoice line items
        $total_sale = 0;
        foreach ($_POST['prod_no'] as $index => $prod_no) {
            $qty_sold = $_POST['qty_sold'][$index];
            
            // Fetch product price
            $result = $conn->query("SELECT prod_price FROM Product WHERE prod_no = $prod_no");
            $product = $result->fetch_assoc();
            $prod_price = $product['prod_price'];
            
            // Calculate total sale for the invoice
            $total_sale += $prod_price * $qty_sold;
            
            // Insert into Invoice_Line
            $stmt = $conn->prepare("INSERT INTO Invoice_Line (inv_no, prod_no, qty_sold) VALUES (?, ?, ?)");
            if (!$stmt) {
                echo "Error preparing query: " . $conn->error;
                exit();
            }
            $stmt->bind_param('iii', $inv_no, $prod_no, $qty_sold);
            $stmt->execute();
        }

        // Update total sale in the Invoice table
        $stmt = $conn->prepare("UPDATE Invoice SET total_sale = ? WHERE inv_no = ?");
        if (!$stmt) {
            echo "Error preparing update query: " . $conn->error;
            exit();
        }
        $stmt->bind_param('di', $total_sale, $inv_no);
        $stmt->execute();

        // Redirect to the invoices list or show a success message
        header("Location: view_invoices.php"); // Redirect to invoice list page
        exit();
    } else {
        echo "Error executing query: " . $stmt->error;
    }
}
?>
