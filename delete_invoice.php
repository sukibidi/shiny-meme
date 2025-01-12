<?php
session_start();
include 'db_connect.php';

if (isset($_GET['inv_no'])) {
    $inv_no = $_GET['inv_no'];

    // Delete the invoice
    $sql = "DELETE FROM Invoice WHERE inv_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $inv_no);

    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: delete_invoice_success.php");
        exit();
    } else {
        echo "Error deleting invoice: " . $stmt->error;
    }
}
?>
