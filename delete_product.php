<?php
session_start();
include 'db_connect.php';

if (isset($_GET['prod_no'])) {
    $prod_no = $_GET['prod_no'];

    // Delete the product
    $sql = "DELETE FROM Product WHERE prod_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prod_no);

    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: delete_product_success.php");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
}
?>
