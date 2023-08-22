<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_discount_edit"])) {
        require_once('config.php');
        $Message = "";
        $discount_label = $_POST["discount_label"];
        $type = $_POST["type"];
        $product_name = $_POST["selected_product_name5"];
        // Select product code,
        $sql_check_product = "SELECT product_code FROM product WHERE product_name = '$product_name'";
        $result_products = $conn->query($sql_check_product);
        if ($result_products->num_rows > 0) {
            while ($row = $result_products->fetch_assoc()) {
                $product_code = $row['product_code'];
            }
        } else {
            // No products found
            $Message = "Error: No products found.";
        }

        $discount_product_for_edit = $_POST["discount_product_for_edit"];
        $purchase_quantity = $_POST["purchase_quantity"];
        $discount = $_POST["discount"];
        $lower_limit = $_POST["lower_limit"];
        $upper_limit = $_POST["upper_limit"];

         // Select product code,
         $sql_check_product = "SELECT price FROM product WHERE product_code = '$product_code'";
         $result_products = $conn->query($sql_check_product);
         if ($result_products->num_rows > 0) {
             while ($row = $result_products->fetch_assoc()) {
                 $unit_price = $row['price'];
             }
         } else {
             // No products found
             $Message = "Error: No products found.";
         }

        // Check if the discount not exists in the database
        $sql_check_disconutcode = "SELECT * FROM products_discount WHERE discount_label = '$discount_label'";
        $result_check_discountcode = $conn->query($sql_check_disconutcode);

                // Check if the product name already exists in the database
                $sql_check_discount_productcode = "SELECT * FROM products_discount WHERE product_code = ' $product_code'";
                $result_check_discount_productcode = $conn->query($sql_check_discount_productcode);

        if ($result_check_discountcode->num_rows == 0) {
            $Message = "Error: Invalid discount Label.";
        }

        
        elseif ($result_check_discount_productcode->num_rows > 0) {
            $row = $result_check_discount_productcode->fetch_assoc();
            if ($discount_label != $row['discount_label']) {
                $Message = "Error: discount is already define for this product.";
            }
        }
        
            if ($Message == ""){
        if ($discount_product_for_edit == "no") {
            $Message = "Error: Not a discount product.";
        }
        elseif (!filter_var($purchase_quantity, FILTER_VALIDATE_INT)) {
            $Message = "Error: purchase quantity must be integer.";
        }

        elseif (!filter_var($discount, FILTER_VALIDATE_FLOAT)) {
            $Message = "Error: price must be a valid floating-point number.";
        }

        elseif ($unit_price <= $discount) {
            $Message = "Error: Discount must be lower than purchase quantity.";
        }

        elseif (!filter_var($lower_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: lower limit must be integer.";
        }

        elseif (!filter_var($upper_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: upper_limit must be integer.";
        }

        elseif ($upper_limit < $lower_limit) {
            $Message = "Error: lower limit must be lower than upper limit.";
        }

        
        else{
        $sql_update_discount = "UPDATE products_discount SET 
                type = '$type',
                product_code = '$product_code',
                purchase_quantity = '$purchase_quantity',
                discount = '$discount',
                lower_limit = '$lower_limit',
                upper_limit = '$upper_limit'
                WHERE discount_label = '$discount_label'";
        if ($conn->query($sql_update_discount) === TRUE) {
            $Message = "Discount update successful!";
        } else {
            $Message = "Error: " . $sql_update_discount . "<br>" . $conn->error;
        }
    }
    }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#define-discount\">   OK</a></td>";
    echo "</div>";
    ?>
</div>