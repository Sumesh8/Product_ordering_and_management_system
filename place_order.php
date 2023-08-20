<div class="place-box">

    <?php
    require_once('config.php');
    //Get the highest order number from the database
    $sql_max_order_code = "SELECT MAX(order_number) AS max_order_code FROM order_details";
    $result_max_order_code = $conn->query($sql_max_order_code);
    $row_max_order_code = $result_max_order_code->fetch_assoc();
    $next_order_code = $row_max_order_code["max_order_code"] + 1;

    //  Generate a 4-digit order number with leading zeros
    $next_order_code_padded = str_pad($next_order_code, 4, '0', STR_PAD_LEFT);


    // Retrieve product information from the database
    $sql_select_order = "SELECT * FROM placing_order";
    $result_order = $conn->query($sql_select_order);

    ?>

    <div class="add-order">
        <form method="post">
            <?php
            // SQL query to select product_name and free_product from product table
            $sql_select_customer = "SELECT username FROM user";
            $result_customer = $conn->query($sql_select_customer);
            ?>
            <div class="form-group">
                <label for="username">Customer Userame:</label>
                <select name="username" id="username" required>
                    <?php
                    // Check if there are any rows returned
                    if ($result_customer->num_rows > 0) {
                        while ($row = $result_customer->fetch_assoc()) {
                            $username = $row['username'];
                            echo "<option value='$username'>$username</option>";
                        }
                    } else {
                        // No products found
                        echo "<option disabled selected>No user found</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="order_number">Order Number:</label>
                <input type="text" name="order_number" id="order_number" value="<?php echo $next_order_code_padded; ?>" readonly>
            </div>

            <div class="tablebox4">
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Unit Price</th>
                        <th>Pusrchased Quantity</th>
                        <th>Free Quantity</th>
                        <th>Total Quantity</th>
                        <th>Discount Quantity</th>
                        <th>Unit Discount</th>
                        <th>Total Discount</th>
                        <th>Amount</th>
                        <th>delete</th>
                    </tr>

                    <?php
                    $netAmount = 0;
                    while ($row = $result_order->fetch_assoc()) {
                        $netAmount += $row['amount']; ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['product_code']; ?></td>
                            <td><?php echo $row['unit_price']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['free_quantity']; ?></td>
                            <td><?php echo ($row['free_quantity'] + $row['quantity']); ?></td>
                            <td><?php echo $row['discount_quantity']; ?></td>
                            <td><?php 
                                if($row['discount_quantity'] != 0){
                                    echo ($row['discount'] / $row['discount_quantity']); 
                                }
                                else{
                                    echo $row['discount'] ;
                                }
                                ?></td>
                            <td><?php echo $row['discount']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><a href="delete_product_order.php?product_order_id=<?php echo $row['id']; ?>">Delete</a></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="9" align="right"><strong>Total Net Amount:</strong></td>
                        <td><?php echo $netAmount; ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <!-- Hidden input field to store the net amount -->
            <input type="hidden" name="net_amount" value="<?php echo $netAmount; ?>">

            <button type="submit" name="add_order" class = "submit-buttons">Add Order</button>
        </form>
    </div>


    <div class="add-product">
        <form method="post">
            <?php
            // SQL query to select product_name and free_product from product table
            $sql_select_products = "SELECT product_name FROM product";
            $result_products = $conn->query($sql_select_products);
            ?>
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <select name="product_name" id="product_name" required>
                    <?php
                    // Check if there are any rows returned
                    if ($result_products->num_rows > 0) {
                        while ($row = $result_products->fetch_assoc()) {
                            $product_name = $row['product_name'];
                            echo "<option value='$product_name'>$product_name</option>";
                        }
                    } else {
                        // No products found
                        echo "<option disabled selected>No products found</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="text" name="quantity" id="quantity" required>
            </div>

            <button type="submit" name="add_product" class = "submit-buttons">Add Product</button>
        </form>
    </div>

    <div class="error-message">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {
            $product_name = $_POST["product_name"];
            $quantity = $_POST["quantity"];


            // check quantity is an intiger,
            if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
                echo "Error: purchase quantity must be integer.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            $sql_check_product = "SELECT product_code ,price FROM product WHERE product_name = '$product_name'";
            $result_products = $conn->query($sql_check_product);
            if ($result_products->num_rows > 0) {
                while ($row = $result_products->fetch_assoc()) {
                    $product_code = $row['product_code'];
                    $unit_price = $row['price'];
                }
            } else {
                // No products found
                echo "Error: No products found.";
                echo "Error: purchase quantity must be numeric.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

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
                            $free_quantity = ($free_quantity / $row['purchase_quantity']) + 1 ;
                            $free_quantity = floor($free_quantity);
                            $free_quantity = $free_quantity * $row['free_quantity'];
                        } else if ($quantity > $row['upper_limit']) {
                            $free_quantity = $row['upper_limit'] - $row['lower_limit'];
                            $free_quantity = ($free_quantity / $row['purchase_quantity']) + 1;
                            $free_quantity = floor($free_quantity);
                            $free_quantity = $free_quantity * $row['free_quantity'];
                        }
                        else {
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
                            $discountquantity = $row['purchase_quantity'] ;
                            $totalDiscount = $row['discount'] * $discountquantity ;

                        } else {
                            $discountquantity = 0;
                            $totalDiscount = 0;
                        }
                    } else if ($row['type'] == "multiple") {
                        if ($row['lower_limit'] <= $quantity && $quantity <= $row['upper_limit']) {
                            $discountquantity = $quantity - $row['lower_limit'];
                            $discountquantity = ($discountquantity / $row['purchase_quantity']) + 1 ;
                            $discountquantity = floor($discountquantity);
                            $totalDiscount = $discountquantity * $row['discount'];

                        } else if ($quantity > $row['upper_limit']) {
                            $discountquantity = $row['upper_limit'] - $row['lower_limit'];
                            $discountquantity = ($discountquantity / $row['purchase_quantity']) + 1;
                            $discountquantity = floor($discountquantity);
                            $totalDiscount = $discountquantity * $row['discount'];

                        }
                        else {
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
                echo "Order insert successful!";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
            } else {
                echo "Error: " . $sql_insert_product_order . "<br>" . $conn->error;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_order"])) {
            $username = $_POST['username'];
            $netAmount = $_POST['net_amount'];
            $orderDate = date('Y-m-d');
            $orderTime = date('H:i:s');


            //If Order is empty,
            if($netAmount== 0.0){
                echo "Please add product for the add order!";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit();
            }

            // Insert the order data into the user table
            $sql_insert_order = "INSERT INTO order_details (order_number, username, order_date, order_time, net_amount)
                VALUES ('$next_order_code_padded','$username', '$orderDate', '$orderTime', '$netAmount' )";

            if ($conn->query($sql_insert_order) === TRUE) {
   
            } else {
                echo "Error: " . $sql_insert_order . "<br>" . $conn->error;
            }

            // Process each row of data and insert into the database
            $result_order = $conn->query("SELECT * FROM placing_order");

            while ($row = $result_order->fetch_assoc()) {
                $product_name = $row['product_name'];
                $product_code = $row['product_code'];
                $unit_price = $row['unit_price'];
                $quantity = $row['quantity'];
                $free_quantity = $row['free_quantity'];
                $discount_quantity = $row['discount_quantity'];
                $discount = $row['discount'];
                $amount = $row['amount'];

                $sql_order_product = "INSERT INTO purchased_product (id, order_number, username, product_name, product_code, unit_price, quantity, free_quantity, discount_quantity, discount, amount) VALUES 
                        (' ', '$next_order_code_padded', '$username', '$product_name', '$product_code', '$unit_price', '$quantity', '$free_quantity', '$discount_quantity', '$discount','$amount')";

                if ($conn->query($sql_order_product) === TRUE) {
                    
                } else {
                    echo "Error: " . $sql_order_product . "<br>" . $conn->error;
                }
            }


            $delete_purchase = "DELETE FROM placing_order";

            // Execute the query
            if ($conn->query($delete_purchase) === TRUE) {
                echo "Order insert successful!";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
            } else {
                echo "Error deleting records: " . $conn->error;
            }
        }
        ?>


    </div>
</div>