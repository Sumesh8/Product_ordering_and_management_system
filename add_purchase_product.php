<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {
        require_once('config.php');
        $Message = "";
        $product_name = $_POST["product_name"];
        $quantity = $_POST["quantity"];
        $sql_check_product = "SELECT product_code ,price FROM product WHERE product_name = '$product_name'";
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

        // check quantity is an intiger,
        if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
            $Message = "Error: purchase quantity must be integer.";
        } else {

            $sql_join_tables1 = "
        SELECT
            product.product_code,
            product.product_name,
            product.price,
            product.expiry_date,
            product.free_product,
            define_free_issues.free_issues_label,
            define_free_issues.type,
            define_free_issues.purchase_quantity,
            define_free_issues.free_quantity,
            define_free_issues.lower_limit,
            define_free_issues.upper_limit
        FROM
            product
        INNER JOIN
            define_free_issues
        ON
            product.product_code = define_free_issues.product_code
        ";

            // Execute the SQL query
            $result_product_details = $conn->query($sql_join_tables1);
            $hasFreeIssue = false;

            // Claculate free quantity
            while ($row = $result_product_details->fetch_assoc()) {
                if ($product_code == $row['product_code']) {
                    if ($row['type'] == "flat") {
                        if ($row['lower_limit'] <= $quantity) {
                            $free_quantity = $row['free_quantity'];
                        } else {
                            $free_quantity = 0;
                        }
                    } else if ($row['type'] == "multiple") {
                        if ($row['lower_limit'] <= $quantity && $quantity <= $row['upper_limit']) {
                            $free_quantity = $quantity - $row['lower_limit'];
                            $free_quantity = ($free_quantity / $row['purchase_quantity']) + 1;
                            $free_quantity = floor($free_quantity);
                            $free_quantity = $free_quantity * $row['free_quantity'];
                        } else if ($quantity > $row['upper_limit']) {
                            $free_quantity = $row['upper_limit'] - $row['lower_limit'];
                            $free_quantity = ($free_quantity / $row['purchase_quantity']) + 1;
                            $free_quantity = floor($free_quantity);
                            $free_quantity = $free_quantity * $row['free_quantity'];
                        } else {
                            $free_quantity = 0;
                        }
                    } else {
                        $free_quantity = 0;
                    }
                    $hasFreeIssue = true;
                }
            }
            if (!$hasFreeIssue) {
                $free_quantity = 0;
            }

            $sql_join_tables2 = "
            SELECT
                product.product_code,
                product.product_name,
                product.price,
                product.expiry_date,
                product.has_discount,
                products_discount.discount_label,
                products_discount.type,
                products_discount.purchase_quantity,
                products_discount.discount,
                products_discount.lower_limit,
                products_discount.upper_limit
            FROM
                product
            INNER JOIN
                products_discount
            ON
                product.product_code = products_discount.product_code
            ";

            // Execute the SQL query
            $result_product_details2 = $conn->query($sql_join_tables2);
            $hasDiscount = false;

            // Claculate discount
            while ($row = $result_product_details2->fetch_assoc()) {
                if ($product_code == $row['product_code']) {
                    if ($row['type'] == "flat") {
                        if ($row['lower_limit'] <= $quantity) {
                            $discountquantity = $row['purchase_quantity'];
                            $totalDiscount = $row['discount'] * $discountquantity;
                        } else {
                            $discountquantity = 0;
                            $totalDiscount = 0;
                        }
                    } else if ($row['type'] == "multiple") {
                        if ($row['lower_limit'] <= $quantity && $quantity <= $row['upper_limit']) {
                            $discountquantity = $quantity - $row['lower_limit'];
                            $discountquantity = ($discountquantity / $row['purchase_quantity']) + 1;
                            $discountquantity = floor($discountquantity);
                            $totalDiscount = $discountquantity * $row['discount'];
                        } else if ($quantity > $row['upper_limit']) {
                            $discountquantity = $row['upper_limit'] - $row['lower_limit'];
                            $discountquantity = ($discountquantity / $row['purchase_quantity']) + 1;
                            $discountquantity = floor($discountquantity);
                            $totalDiscount = $discountquantity * $row['discount'];
                        } else {
                            $discountquantity = 0;
                            $totalDiscount = 0;
                        }
                    } else {
                        $discountquantity = 0;
                        $totalDiscount = 0;
                    }
                    $hasDiscount = true;
                }
            }
            if (!$hasDiscount) {
                $discountquantity = 0;
                $totalDiscount = 0;
            }




            $amount =  (($quantity * $unit_price) - $totalDiscount);

            // Insert the free issues data into the user table
            $sql_insert_product_order = "INSERT INTO placing_order (id, product_name, product_code, unit_price, quantity, free_quantity, discount_quantity, discount, amount)
                    VALUES ('','$product_name', '$product_code', '$unit_price', '$quantity', '$free_quantity', '$discountquantity' , '$totalDiscount' ,'$amount' )";

            if ($conn->query($sql_insert_product_order) === TRUE) {
                $Message = "Order insert successful!";
            } else {
                $Message = "Error: " . $sql_insert_product_order . "<br>" . $conn->error;
            }
        }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#placing-order\">   OK</a></td>";
    echo "</div>";
    ?>
</div>