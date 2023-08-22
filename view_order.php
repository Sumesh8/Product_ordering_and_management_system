<div class="view-order">
    <form method="post">
        <?php
        require_once "config.php";
        // SQL query to select order number and free_product from product table
        $sql_select_number0 = "SELECT order_number,username FROM order_details";
        $result_number0 = $conn->query($sql_select_number0);
        ?>
        <div class="form-group">
            <label for="order_number0">Order Number:</label>
            <select name="order_number0" id="order_number0" required>
                <?php

                // Check if there are any rows returned
                if ($result_number0->num_rows > 0) {
                    while ($row = $result_number0->fetch_assoc()) {
                        $order_number0 = $row['order_number'];
                        $username0 = $row['username'];
                        echo "<option value='$username0'>$order_number0</option>";
                    }
                } else {
                    // No products found
                    echo "<option disabled selected>No Order Found</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="username0">Customer Username:</label>
            <input type="text" name="username0" id="username0" readonly>
            </select>
        </div>
        <input type="hidden" name="selected_order_label" id="selected_order_label" value="">
        <a href="#placing-order">
        <button type="submit" name="view_order" class = "submit-buttons" > View</button>
            </a>

    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["view_order"])) {
        $order_number = $_POST["selected_order_label"];
        $customer_username = $_POST["username0"];
    ?>

    <br>
    <div class="form-group">
            <label for="orderIDSelected">Order Number: <?php echo $order_number ?></label>
    </div>

        <div class="form-group">
            <label for="usernameSelected">Customer Username: <?php echo $customer_username ?> </label>
        </div>

        <div class="tablebox5">
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

                </tr>

                <?php
                $netAmount = 0;
                // Retrieve order product information from the database

                $sql_select_order = "SELECT * FROM purchased_product WHERE order_number = '$order_number'";
                $result_order = $conn->query($sql_select_order);
                if ($result_order->num_rows > 0) {
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
                        </tr>
                <?php }
                }
                ?>

                <tr>
                    <td colspan="9" align="right"><strong>Total Net Amount:</strong></td>
                    <td><?php 
                    $formattedNetAmount = number_format($netAmount, 2);
                    echo $formattedNetAmount; ?></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class = "button-container">
        <form action="delete_order.php" method="post">
            <input type="hidden" name="order_number" value="<?php echo $order_number; ?>">
            <button type="submit" name="delete_order" class = "submit-buttons">Delete Order</button>
        </form>
       

        <form action="generate_csv.php" method="post">
            <input type="hidden" name="order_number" value="<?php echo $order_number; ?>">
            <button type="submit" name="print_order" class = "submit-buttons">Print CSV</button>
        </form>

        <form action="generate_pdf.php" method="post">
            <input type="hidden" name="order_number" value="<?php echo $order_number; ?>">
            <button type="submit" name="print_order" class = "submit-buttons">Print PDF</button>
        </form>
        </div>

    <?php
    } ?>

    <script>
        // Function to update the input field with the selected value
        function updateUser0Field() {
            var dropdown = document.getElementById('order_number0');
            var selectedValue = dropdown.value;
            var inputField = document.getElementById('username0');
            inputField.value = selectedValue;
        }

        // Add event listener to the select element
        document.getElementById('order_number0').addEventListener('change', updateUser0Field);

        // Trigger the change event initially to set the initial value
        updateUser0Field();

        // Function to update the input field with the selected value
        function updatehiddenField2() {
            var dropdown = document.getElementById('order_number0');
            var selectedValue = dropdown.options[dropdown.selectedIndex];
            var inputField = document.getElementById('selected_order_label');
            inputField.value = selectedValue.text;
        }

        // Add event listener to the select element
        document.getElementById('order_number0').addEventListener('change', updatehiddenField2);

        // Trigger the change event initially to set the initial value
        updatehiddenField2();
    </script>

</div>