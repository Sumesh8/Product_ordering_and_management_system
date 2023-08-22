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
<div class="add-product">
        <form method="post" action = "add_purchase_product.php">
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

    <div class="add-order">
        <form method="post" action ="add_place_order.php">
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
                        <td><?php 
                        $formattedNetAmount = number_format($netAmount, 2);
                        echo $formattedNetAmount; ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <!-- Hidden input field to store the net amount -->
            <input type="hidden" name="net_amount" value="<?php echo $netAmount; ?>">

            <?php
            // SQL query to select product_name and free_product from product table
            $sql_select_customer = "SELECT username FROM user";
            $result_customer = $conn->query($sql_select_customer);
            ?>
            <br>
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

            <button type="submit" name="add_order" class = "submit-buttons">Add Order</button>
        </form>
    </div>

</div>