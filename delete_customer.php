<?php
session_start();
include 'db_connect.php';

if (isset($_GET['cus_no'])) {
    $cus_no = $_GET['cus_no'];

    // Delete the customer
    $sql = "DELETE FROM Customer WHERE cus_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cus_no);

    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: delete_customer_success.php");
        exit();
    } else {
        echo "Error deleting customer: " . $stmt->error;
    }
}
?>
