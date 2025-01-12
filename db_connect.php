<?php
$host = "localhost"; // Your host
$dbname = "iekanism"; // Your database name
$username = "root"; // Your database username
$password = ""; // Your MySQL password

// MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
