<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management System</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for the content */
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 100%;
        }

        /* Title styling */
        h2 {
            color: #e63946;
            margin-bottom: 40px;
            font-size: 28px;
        }

        /* Card layout for buttons */
        .card-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* Each card for buttons */
        .card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            width: 230px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Icon styling */
        .card-icon {
            font-size: 48px;
            color: #e63946;
            margin-bottom: 20px;
        }

        /* Button links inside cards */
        .card a {
            text-decoration: none;
            color: #ffffff;
            background-color: #e63946;
            padding: 12px 20px;
            border-radius: 5px;
            display: block;
            margin-top: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .card a:hover {
            background-color: #d62828;
        }

        /* Footer styling */
        footer {
            margin-top: 40px;
            color: #888;
            font-size: 12px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .card-group {
                flex-direction: column;
                align-items: center;
            }
        }

    </style>
    <!-- Include FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">
        <h2>Management System</h2>

        <div class="card-group">
            <!-- Customer Card (Left) -->
            <div class="card">
                <div class="card-icon"><i class="fas fa-user"></i></div>
                <h3>Customer Management</h3>
                <a href="create_customer.php">Add New Customer</a>
                <a href="view_customers.php">View All Customers</a>
            </div>

            <!-- Product Card (Center) -->
            <div class="card">
                <div class="card-icon"><i class="fas fa-box"></i></div>
                <h3>Product Management</h3>
                <a href="create_product.php">Add New Product</a>
                <a href="view_products.php">View All Products</a>
            </div>

            <!-- Invoice Card (Right) -->
            <div class="card">
                <div class="card-icon"><i class="fas fa-file-invoice"></i></div>
                <h3>Invoice Management</h3>
                <a href="create_invoice.php">Create New Invoice</a>
                <a href="view_invoices.php">View All Invoices</a>
            </div>
        </div>

        <footer>
            <p>&copy; 2024 Iekanism </p>
        </footer>
    </div>

</body>
</html>
