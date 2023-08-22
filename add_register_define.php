<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_define_register"])) {
        require_once('config.php');
        $Message = "";
        $type = $_POST["type"];
        $product_name = $_POST["selected_product_name"];
        $next_define_code_padded = $_POST["free_issues_label"];

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

        $free_product_for_define = $_POST["free_product_for_define"];
        $purchase_quantity = $_POST["purchase_quantity"];
        $free_quantity = $_POST["free_quantity"];
        $lower_limit = $_POST["lower_limit"];
        $upper_limit = $_POST["upper_limit"];

        // Check if the free issue already exists in the database
        $sql_check_define = "SELECT * FROM define_free_issues WHERE product_code = '$product_code'";
        $result_check_define = $conn->query($sql_check_define);
        if ($result_check_define->num_rows > 0) {
            $Message = "Error: free issues already exists. Please choose a different free issues.";
        } elseif ($free_product_for_define == "no") {
            $Message = "Error: Not a free product.";
        } elseif (!filter_var($purchase_quantity, FILTER_VALIDATE_INT)) {
            $Message = "Error: purchase quantity must be integer.";
        } elseif (!filter_var($free_quantity, FILTER_VALIDATE_INT)) {
            $Message = "Error: free quantity must be integer.";
        } elseif ($purchase_quantity < $free_quantity) {
            $Message = "Error: free quantity must be lower than purchase quantity.";
        } elseif (!filter_var($lower_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: lower limit must be integer.";
        } elseif (!filter_var($upper_limit, FILTER_VALIDATE_INT)) {
            $Message = "Error: upper_limit must be integer.";
        } elseif ($upper_limit < $lower_limit) {
            $Message = "Error: free limit be lower than upper limit.";
        } else {
            // Insert the free issues data into the user table
            $sql_insert_define = "INSERT INTO define_free_issues (free_issues_label, type, product_code, purchase_quantity, free_quantity, lower_limit, upper_limit)
                            VALUES ($next_define_code_padded,'$type', '$product_code', '$purchase_quantity', '$free_quantity', '$lower_limit', '$upper_limit' )";

            if ($conn->query($sql_insert_define) === TRUE) {
                $Message = "Free issue registration successful!";
            } else {
                $Message = "Error: " . $sql_insert_define . "<br>" . $conn->error;
            }
        }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#define-free-issues\">   OK</a></td>";
    echo "</div>";
    ?>

</div>