<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_define_register"])) {
        $Message = "";
        require_once('config.php');
        $free_issues_label = $_POST["free_issues_label"];
        $type = $_POST["type"];
        $product_name = $_POST["selected_product_name2"];
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

        $free_product_for_edit = $_POST["free_product_for_edit"];
        $purchase_quantity = $_POST["purchase_quantity"];
        $free_quantity = $_POST["free_quantity"];
        $lower_limit = $_POST["lower_limit"];
        $upper_limit = $_POST["upper_limit"];

        // Check if the free issue not exists in the database
        $sql_check_issuescode = "SELECT * FROM define_free_issues WHERE free_issues_label = '$free_issues_label'";
        $result_check_issuescode = $conn->query($sql_check_issuescode);

        if ($result_check_issuescode->num_rows == 0) {
            $Message = "Error: Invalid Free Label.";
        }

        // Check if the product name already exists in the database
        $sql_check_issues = "SELECT * FROM define_free_issues WHERE product_code = ' $product_code'";
        $result_check_issues = $conn->query($sql_check_issues);
        if ($result_check_issues->num_rows > 0 && $Message == "") {
            $row = $result_check_issues->fetch_assoc();
            if ($free_issues_label != $row['free_issues_label']) {
                $Message = "Error: Free issue already define for this product.";
            }
        }

        if ($Message == "") {
            if ($free_product_for_edit == "no") {
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
                $Message = "Error: upper limit must be integer.";
            } elseif ($upper_limit < $lower_limit) {
                $Message = "Error: lower limit must be lower than upper limit.";
            } else {


                $sql_update_define = "UPDATE define_free_issues SET 
                type = '$type',
                product_code = '$product_code',
                purchase_quantity = '$purchase_quantity',
                free_quantity = '$free_quantity',
                lower_limit = '$lower_limit',
                upper_limit = '$upper_limit'
                WHERE free_issues_label = '$free_issues_label'";
                if ($conn->query($sql_update_define) === TRUE) {
                    $Message = "Free issues update successful!";
                } else {
                    $Message = "Error: " . $sql_update_define . "<br>" . $conn->error;
                }
            }
        }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#define-free-issues\">   OK</a></td>";
    echo "</div>";
    ?>
</div>