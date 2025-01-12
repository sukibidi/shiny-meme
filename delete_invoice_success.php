<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Deleted Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #e63946;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #333;
        }

        .success-icon {
            font-size: 50px;
            color: #e63946;
            margin-bottom: 20px;
        }

        .back-button {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-icon">✔</div>
    <h2>Invoice Deleted Successfully!</h2>
    <p>The invoice was successfully removed from the system.</p>
    <a class="back-button" href="view_invoices.php">Back to Invoice List</a>
</div>

</body>
</html>
