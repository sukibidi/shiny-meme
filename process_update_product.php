<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_no = $_POST['prod_no'];
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];
    $qoh = $_POST['qoh'];

    // Prepare and execute the SQL query to update the product
    $stmt = $conn->prepare("UPDATE Product SET prod_desc = ?, prod_price = ?, qoh = ? WHERE prod_no = ?");
    $stmt->bind_param("sdii", $prod_desc, $prod_price, $qoh, $prod_no);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
        echo '<br><a href="view_products.php">View All Products</a>';
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
