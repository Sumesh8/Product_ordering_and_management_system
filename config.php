<?php
// Database connection details
$servername = 'localhost';
$username_db = 'root';
$password_db = '';
$dbname = 'prouct_order_and_management_system'; 

// Create a connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>