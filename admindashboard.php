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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/admindashboard.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h1>Admin Dashboard</h1>
            <a href="#customer-registration">Customer Registration</a>
            <a href="#product-registration">Product Registration</a>
            <a href="#define-free-issues">Define Free Issues</a>
            <a href="#define-discount">Define Discount</a>
            <a href="#placing-order">Placing Order</a>
            <a href="#order-view">Order View</a>
            
        </div>
        <!-- Content Area -->
        
        <div class="content" >
            <h1>Welcome to Admin Dashboard</h1>

            <div class="customer-box" id= "customer-registration">
                <h2>Customer Registration</h2>
                <div class="tabs">
                    <button class="tablink" onclick="openCustomerTab('register_customer')">Registor Customer</button>
                    <button class="tablink" onclick="openCustomerTab('view_customer')">View Customers</button>
                    <button class="tablink" onclick="openCustomerTab('edit_customer')">Edit Customer</button>
                </div>
                <script>
                    function openCustomerTab(tabName) {
                        var i, tabcustomer, tablinks;
                        tabcustomer = document.getElementsByClassName("tabcustomer");
                        for (i = 0; i < tabcustomer.length; i++) {
                            tabcustomer[i].style.display = "none";
                        }
                        document.getElementById(tabName).style.display = "block";

                    }
                    
                </script>
                <div id="register_customer" class="tabcustomer">
                    <!-- Add code for editing customer information -->   
                    <?php include 'register_customer.php'; ?>
                </div>

                <!-- View Customers Tab -->
                <div id="view_customer" class="tabcustomer" style="display: none;">
                
                    <!-- Add code to display a table with customer information from the database -->
                    <?php include 'view_customers.php'; ?>
                </div>

                <!-- Edit Customer Tab -->
                <div id="edit_customer" class="tabcustomer" style="display: none;">
                    <!-- Add code to display editing customer information -->
                    <?php include 'edit_customer.php'; ?>

                </div>
            </div>

            <div class="product-box" id= "product-registration">
                <h2>Product Registration</h2>
                <div class="tabs">
                    <button class="tablink" onclick="openProductTab('register_product')">Registor Product</button>
                    <button class="tablink" onclick="openProductTab('view_product')">View Products</button>
                    <button class="tablink" onclick="openProductTab('edit_product')">Edit Product</button>
                </div>

                <script>
                    function openProductTab(tabName) {
                        var i, tabproduct, tablinks;
                        tabproduct = document.getElementsByClassName("tabproduct");
                        for (i = 0; i < tabproduct.length; i++) {
                            tabproduct[i].style.display = "none";
                        }
                        document.getElementById(tabName).style.display = "block";

                    }
                </script>

                <!-- Rwgister product Tab -->
                <div id="register_product" class="tabproduct">
                    <!-- Add code for editing product information -->
                    <?php include 'register_product.php'; ?>
                </div>

                <!-- View product Tab -->
                <div id="view_product" class="tabproduct" style="display: none;">
                    <!-- Add code to display a table with product information from the database -->
                    <?php include 'view_products.php'; ?>
                </div>

                <!-- Edit product Tab -->
                <div id="edit_product" class="tabproduct" style="display: none;">
                    <!-- Add code to display editing product information -->
                    <?php include 'edit_product.php'; ?>
                </div>
            </div>

    
            <div class="define-box" id= "define-free-issues">
                <h2>Define Free Issues</h2>
                <div class="tabs">
                    <button class="tablink" onclick="openDefineTab('register_define')">Define Free Issues</button>
                    <button class="tablink" onclick="openDefineTab('view_define')">View Free Issues</button>
                    <button class="tablink" onclick="openDefineTab('edit_define')">Edit Free Issues</button>
                </div>

                <script>
                    function openDefineTab(tabName) {
                        var i, tabdefine, tablinks;
                        tabdefine = document.getElementsByClassName("tabdefine");
                        for (i = 0; i < tabdefine.length; i++) {
                            tabdefine[i].style.display = "none";
                        }
                        document.getElementById(tabName).style.display = "block";

                    }
                </script>

                <!-- Register Define Free Issues Tab -->
                <div id="register_define" class="tabdefine">
                    <!-- Add code for editing Define Free Issues information -->
                    <?php include 'register_define.php'; ?>
                    
                </div>


                <!-- View Define Free Issues Tab -->
                <div id="view_define" class="tabdefine" style="display: none;">
                    <!-- Add code to display a table with Define Free Issues information from the database -->
                    <?php include 'view_define.php'; ?>
                </div>

                <!-- Edit Define Free Issues Tab -->
                <div id="edit_define" class="tabdefine" style="display: none;">
                    <!-- Add code to display editing Define Free Issues information -->
                    <?php include 'edit_define.php'; ?>
                </div>
            </div>

            <div class="discount-box" id= "define-discount">
                <h2>Define Discount</h2>
                <div class="tabs">
                    <button class="tablink" onclick="openDiscountTab('register_discount')">Define Discount</button>
                    <button class="tablink" onclick="openDiscountTab('view_discount')">View Discount</button>
                    <button class="tablink" onclick="openDiscountTab('edit_discount')">Edit Discount</button>
                </div>

                <script>
                    function openDiscountTab(tabName) {
                        var i, tabdiscount, tablinks;
                        tabdiscount = document.getElementsByClassName("tabdiscount");
                        for (i = 0; i < tabdiscount.length; i++) {
                            tabdiscount[i].style.display = "none";
                        }
                        document.getElementById(tabName).style.display = "block";

                    }
                </script>
                <!-- Register Disscount Tab -->
                <div id="register_discount" class="tabdiscount">
                    <!-- Add code for register disscount information -->
                    <?php include 'register_discount.php'; ?>
                </div>

                <!-- View disscount Tab -->
                <div id="view_discount" class="tabdiscount" style="display: none;">
                    <!-- Add code to display a table with Define Free Issues information from the database -->
                    <?php include 'view_discount.php'; ?>
                    
                </div>

                <!-- Edit disscount Tab -->
                <div id="edit_discount" class="tabdiscount" style="display: none;">
                    <!-- Add code to display editing disscount information -->
                    <?php include 'edit_discount.php'; ?>
                </div>
            </div>
        
            <div class="place-order-box" id= "placing-order">
                <h2>Placing Order</h2>
                <div class="tabs">
                    <button class="tablink" onclick="openOrderTab('place_order')">Place Order</button>
                    <button class="tablink" onclick="openOrderTab('view_order')">View Order</button>
   
                </div>

                <script>
                    function openOrderTab(tabName) {
                        var i, taborder, tablinks;
                        taborder = document.getElementsByClassName("taborder");
                        for (i = 0; i < taborder.length; i++) {
                            taborder[i].style.display = "none";
                        }
                        document.getElementById(tabName).style.display = "block";

                    }
                </script>

                <!-- Place Order Tab -->
                <div id="place_order" class="taborder">
                    <!-- Add code for place order information -->
                    <?php include 'place_order.php'; ?>
                </div>

               


                <!-- View Order Tab -->
                <div id="view-order" >
                <div id="view_order" class="taborder" style="display: none;">
                    <!-- Add code to display privios order information from the database -->
                    <?php include 'view_order.php'; ?>
                </div>
                </div>


            </div>


            <div class="order-view-box" id= "order-view">
                <h2>Order Details</h2>
                <?php include 'view_order_details.php'; ?>
            </div>
        </div>
    </div>
</body>

</html>