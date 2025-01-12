<?php
include 'db_connect.php';

// Enable error reporting (optional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prod_desc = $_POST['prod_desc'];
    $prod_price = $_POST['prod_price'];
    $qoh = $_POST['qoh'];

    // Insert the product without specifying prod_no, as it will auto-increment
    $stmt = $conn->prepare("INSERT INTO Product (prod_desc, prod_price, qoh) VALUES (?, ?, ?)");

    if (!$stmt) {
        echo "Error preparing query: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sdi", $prod_desc, $prod_price, $qoh);

    if ($stmt->execute()) {
        // UI for success after product is added
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Product Added Successfully</title>
            <style>
                /* General body styling */
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }

                /* Container styling */
                .container {
                    text-align: center;
                    background-color: #ffffff;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                    max-width: 400px;
                    width: 100%;
                }

                /* Success Icon */
                .success-icon {
                    font-size: 50px;
                    color: #4CAF50;
                    margin-bottom: 20px;
                }

                /* Success message */
                h2 {
                    color: #e63946;
                    margin-bottom: 20px;
                    font-size: 24px;
                }

                /* Info text */
                p {
                    color: #333;
                    margin-bottom: 30px;
                }

                /* Button styling */
                .button {
                    text-decoration: none;
                    color: white;
                    background-color: #e63946;
                    padding: 10px 20px;
                    border-radius: 5px;
                    display: inline-block;
                    margin: 10px;
                    transition: background-color 0.3s ease;
                }

                .button:hover {
                    background-color: #d62828;
                }

                /* Option container */
                .option-container {
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>

            <div class="container">
                <!-- Success icon (checkmark) -->
                <div class="success-icon">✔</div>

                <!-- Success message -->
                <h2>Product Added Successfully!</h2>
                <p>The product "<?php echo htmlspecialchars($prod_desc); ?>" has been added to your inventory.</p>

                <!-- Option buttons -->
                <div class="option-container">
                    <a href="view_products.php" class="button">View Products</a>
                    <a href="create_product.php" class="button">Add Another Product</a>
                    <a href="dashboard.php" class="button">Back to Home</a>
                </div>
            </div>

        </body>
        </html>
        <?php
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
