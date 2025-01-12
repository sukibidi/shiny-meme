<?php
include 'db_connect.php'; // Include database connection

// Check if the form is submitted and the customer ID is present
if (isset($_POST['cus_no'])) {
    $cus_no = $_POST['cus_no']; // Retrieve customer ID from the POST request
    $cus_name = $_POST['cus_name'];
    $cus_age = $_POST['cus_age'];
    $cus_contact = $_POST['cus_contact'];

    // Prepare the SQL UPDATE statement
    $stmt = $conn->prepare("UPDATE Customer SET cus_name = ?, cus_age = ?, cus_contact = ? WHERE cus_no = ?");
    
    // Check if the statement preparation failed
    if (!$stmt) {
        echo "Error preparing query: " . $conn->error;
        exit();
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("sisi", $cus_name, $cus_age, $cus_contact, $cus_no); // 's' = string, 'i' = integer

    // Execute the statement
    if ($stmt->execute()) {
        // Success: Redirect to the customer list or show a success message
        echo "Customer updated successfully!";
        header("Location: view_customers.php"); // Redirect to customer list page
        exit();
    } else {
        // If the query failed, show an error
        echo "Error executing query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No customer ID provided.";
}
