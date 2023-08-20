<?php
session_start(); // Start the session

// Check if the username session variable is set
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Retrieve the username from the session
} else {
    // Redirect to the login page if the username session variable is not set
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="assets/css/admindashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h1>Customer Dashboard</h1>
            <a href="#my-detals">My details</a>
            <a href="#my-order"> My Orders </a>
        </div>

        <!-- Content Area -->
        
        <div class="content" id= "my-detals">
            <h1>Welcome <?php 
            require_once('config.php');
             // Check if the username already exists in the database
             $sql_check_username = "SELECT * FROM user WHERE username = '$username'";
             $result_check_username = $conn->query($sql_check_username);

             if ($result_check_username->num_rows > 0) {
                $row = $result_check_username->fetch_assoc();
                $userCode = $row['user_code'];
                $customerName = $row['name'];
                $username = $row['username'];
                $address = $row['address'];
                $contact = $row['contact_number'];
                $type = $row['type'];
                
            } 
            
            echo $customerName; ?>!!!. This is customer dashboard </h1>

            <div class = "customer-box">
            <div class="form-group2">
                <label for="customer_code">My Name:</label>
                <input class = "customer-details" type="text" name="customer_name" id="customer_code" value="<?php echo $customerName; ?>" readonly>
            </div>

            <div class="form-group2">
                <label for="customer_code">My User Code:</label>
                <input class = "customer-details" type="text" name="customer_code" id="customer_code" value="<?php echo $userCode; ?>" readonly>
            </div>

            <div class="form-group2">
                <label for="customer_code">My username:</label>
                <input  class = "customer-details" type="text" name="username" id="username" value="<?php echo $username; ?>" readonly>
            </div>

            <div class="form-group2">
                <label for="customer_code">My address:</label>
                <input  class = "customer-details" type="text" name="customer_address" id="customer_address" value="<?php echo $address; ?>" readonly>
            </div>

            <div class="form-group2">
                <label for="customer_code">My Contact Number:</label>
                <input  class = "customer-details" type="text" name="customer_contact" id="customer_contact" value="<?php echo $contact; ?>" readonly>
            </div>

        </div>



        <div class="order-view-box" id= "my-order">
                <h2>Order Details</h2>
                <?php include 'view_my_details.php'; ?>
        </div>


        </div>
</div>
</div>
</body>

