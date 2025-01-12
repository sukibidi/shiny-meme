<?php
include 'db_connect.php';

// Check if customer ID is provided via GET (in the URL)
if (isset($_GET['cus_no'])) {
    $cus_no = $_GET['cus_no'];

    // Fetch the customer details using the customer ID
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE cus_no = ?");
    $stmt->bind_param("i", $cus_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        echo "Customer not found!";
        exit();
    }

    // Display the form with the customer's existing data
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Customer</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #ffffff;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .container {
                background-color: #f9f9f9;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                width: 100%;
                text-align: center;
            }
            h2 {
                color: #e63946;
                margin-bottom: 20px;
            }
            label {
                display: block;
                margin: 10px 0 5px;
                color: #333;
            }
            input[type="text"], input[type="number"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            input[type="submit"] {
                background-color: #e63946;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }
            input[type="submit"]:hover {
                background-color: #d62828;
            }
            a {
                text-decoration: none;
                color: #e63946;
                margin-top: 20px;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Update Customer</h2>
            <form action="process_update_customer.php" method="POST">
                <!-- Hidden field to pass the customer ID (cus_no) -->
                <input type="hidden" name="cus_no" value="<?php echo htmlspecialchars($customer['cus_no']); ?>"> 
                
                <label for="cus_name">Name:</label>
                <input type="text" id="cus_name" name="cus_name" value="<?php echo htmlspecialchars($customer['cus_name']); ?>" required>

                <label for="cus_age">Age:</label>
                <input type="number" id="cus_age" name="cus_age" value="<?php echo htmlspecialchars($customer['cus_age']); ?>" required>

                <label for="cus_contact">Contact:</label>
                <input type="text" id="cus_contact" name="cus_contact" value="<?php echo htmlspecialchars($customer['cus_contact']); ?>" required>

                <input type="submit" value="Update Customer">
            </form>
            <a href="view_customers.php">Back to Customer List</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "No customer ID provided in the URL.";
}
?>
