<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cus_name = $_POST['cus_name'];
    $cus_age = $_POST['cus_age'];
    $cus_contact = $_POST['cus_contact'];

    // Prepare the SQL query
    $sql = "INSERT INTO Customer (cus_name, cus_age, cus_contact) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sis", $cus_name, $cus_age, $cus_contact);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to success page
        header("Location: create_customer_success.php");
        exit;
    } else {
        die("Error executing query: " . $stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

