<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    require_once('config.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_product_edit"])) {
        $product_code = $_POST["product_code"];
        $product_name = $_POST["product_name"];
        $free_product = $_POST["free_product"];
        $has_discount = $_POST["has_discount"];
        $product_price = $_POST["product_price"];
        $product_expiry_date = $_POST["expiry_date"];

        // Check if the productcode not exists in the database
        $sql_check_productcode = "SELECT * FROM product WHERE product_code = '$product_code'";
        $result_check_productcode = $conn->query($sql_check_productcode);

        // Check if the product name already exists in the database
        $sql_check_product = "SELECT * FROM product WHERE product_name = '$product_name'";
        $result_check_product = $conn->query($sql_check_product);

        // Check if the free isuues is yes in the database
        $sql_check_freeissues = "SELECT * FROM define_free_issues WHERE product_code = '$product_code'";
        $result_check_freeissues = $conn->query($sql_check_freeissues);

        // Check if the discount is yes in the database
        $sql_check_discount = "SELECT * FROM products_discount WHERE product_code = '$product_code'";
        $result_check_discount = $conn->query($sql_check_discount);

        $Message = "";
        if ($result_check_productcode->num_rows == 0) {
            $Message = "Error: Invalid Product code.";
        } elseif (!is_numeric($product_price)) {
            $Message = "Error: Invalid Product Price.";
        } elseif ($result_check_productcode->num_rows > 0) {
            $row1 = $result_check_productcode->fetch_assoc();
            if ($product_name != $row1['product_name']) {
                if ($result_check_product->num_rows > 0) {
                    $row2 = $result_check_product->fetch_assoc();
                    if ($product_name == $row2['product_name']) {
                        $Message = "Error: product name already exists. Please choose a different product name.";
                    }
                }
            }
        }

        if ($Message == "") {
            // Update the customer data into the user 

            $sql_update_product = "UPDATE product SET 
            product_name = '$product_name',
            free_product = '$free_product',
            has_discount = '$has_discount',
            price = '$product_price',
            expiry_date = '$product_expiry_date'
            WHERE product_code = '$product_code'";

            if ($conn->query($sql_update_product) === TRUE) {
                $Message .= "Product update successfull!<br>";
            } else {
                $Message .= "Error: " . $sql_update_customer . "<br>" . $conn->error;
            }

            // Delete corresponfing define free issues if product free is set to no deleted,
            if ($result_check_freeissues->num_rows > 0) {
                if ($free_product == "no") {
                    // Prepare the SQL query to delete the free issue from the user table
                    $sql1 = "DELETE FROM define_free_issues WHERE product_code = '$product_code'";

                    // Execute the delete query
                    if ($conn->query($sql1) === TRUE) {

                        $Message .= "Note that if there is any corresponding free issues, it also delete.<br>";
                    } else {
                        $Message .= "Error deleting free Issue: " . $conn->error . "<br>";
                    }
                }
            }


            // Delete corresponfing define free issues if product free is set to no deleted,
            if ($result_check_discount->num_rows > 0) {
                if ($has_discount == "no") {
                    // Prepare the SQL query to delete the free issue from the user table
                    $sql2 = "DELETE FROM products_discount WHERE product_code = '$product_code'";

                    // Execute the delete query
                    if ($conn->query($sql2) === TRUE) {

                        $Message .= "Note that if there is any corresponding discount, it also delete.<br>";
                    } else {
                        $Message .= "Error deleting Discount: " . $conn->error . "<br>";
                    }
                }
            }
        }
    }
    $conn->close();
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#product-registration\">   OK</a></td>";
    echo "</div>";

    ?>
</div>