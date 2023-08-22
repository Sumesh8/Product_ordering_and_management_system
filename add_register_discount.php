<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_discount_register"])) {
        require_once('config.php');
        $Message = "";
        $type = $_POST["type"];
        $product_name = $_POST["selected_product_name4"];
        $next_discount_code_padded = $_POST["discount_label"];
        // Select product code,
        $sql_check_product = "SELECT product_code,price FROM product WHERE product_name = '$product_name'";
        $result_products = $conn->query($sql_check_product);
        if ($result_products->num_rows > 0) {
            while ($row = $result_products->fetch_assoc()) {
                $product_code = $row['product_code'];
                $unit_price = $row['price'];
            }
        } else {
            // No products found
            $Message = "Error: No products found.";
        }

        $discount_product_for_define = $_POST["discount_product_for_define"];
        $purchase_quantity = $_POST["purchase_quantity"];
        $discount = $_POST["discount"];
        $lower_limit = $_POST["lower_limit"];
        $upper_limit = $_POST["upper_limit"];

        // Check if the free issue already exists in the database
        $sql_check_discount = "SELECT * FROM products_discount WHERE product_code = '$product_code'";
        $result_check_discount = $conn->query($sql_check_discount);
        if ($result_check_discount->num_rows > 0) {
            $Message = "Error: Discount already exists. Please choose a different discount.";
        } elseif ($discount_product_for_define == "no") {
            $Message = "Error: Not a discount product.";
        } elseif (!filter_var($purchase_quantity, FILTER_VALIDATE_INT)) {
            $Message = "Error: purchase quantity must be integer.";
        } elseif (!filter_var($discount, FILTER_VALIDATE_FLOAT)) {
            $Message = "Error: price must be a valid floating-point number.";
        } elseif ($unit_price < $discount) {
            $Message = "Error: discount must be lower than unit price.";
        } elseif (!filter_var($lower_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: lower limit must be integer.";
        } elseif (!filter_var($upper_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: upper_limit must be integer.";
        } elseif ($upper_limit < $lower_limit) {
            $Message = "Error: upper limit must be lower than upper limit.";
        }



        // Insert the free issues data into the user table
        else {
            $sql_insert_discount = "INSERT INTO products_discount (discount_label, type, product_code, purchase_quantity, discount, lower_limit, upper_limit)
                            VALUES ($next_discount_code_padded ,'$type', '$product_code', '$purchase_quantity', '$discount', '$lower_limit', '$upper_limit' )";

            if ($conn->query($sql_insert_discount) === TRUE) {
                $Message = "discount registration successful!";
            } else {
                $Message = "Error: " . $sql_insert_discount . "<br>" . $conn->error;
            }
        }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#define-discount\">   OK</a></td>";
    echo "</div>";
    ?>
</div>