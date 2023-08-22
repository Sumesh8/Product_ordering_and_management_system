<div class="view-order">
    <?php
    require_once "config.php";
    // SQL query to select order number and free_product from product table
    $sql_select_order_details = "SELECT * FROM order_details";
    $result_order_details = $conn->query($sql_select_order_details);
    ?>
    <div class="tablebox6">
        <form id="bulkForm" method="post">
            <table>
                <tr>

                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Order date</th>
                    <th>Order time</th>
                    <th>Net Amount</th>
                    <th>Detailed view</th>
                    <th>Bulk operation</th>

                </tr>

                <?php
                // Retrieve order order information from the database
                if ($result_order_details->num_rows > 0) {
                    while ($row = $result_order_details->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo $row['order_number']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['order_time']; ?></td>
                            <td><?php echo $row['net_amount']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="order_number" value="<?php echo $row['order_number']; ?>">
                                    <input type="hidden" name="customer_username" value="<?php echo $row['username']; ?>">
                                    <a href="#order-view">                                
                                        <button type="submit" name="submit_order_details" class="submit-buttons">View</button>
                                    </a>
                                </form>
                            </td>

                            <td><input type="checkbox" name="selected_orders[]" value="<?php echo $row['order_number']; ?>"> </td>
                        </tr>
                <?php }
                }
                ?>
                <tr>
                    <td colspan="6"></td>
                    <td>
                        <a href="#order-view">
                            <button type="submit" name="bulk_operation" class="submit-buttons">Perform Bulk Operation</button>
                        </a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div class="detals-view">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_order_details"])) {

        $submit_order_number = $_POST["order_number"];
        $customer_username = $_POST["customer_username"];


        // SQL query to select order number and free_product from product table
        $sql_select_order__product_details = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
        $result_order_product_details = $conn->query($sql_select_order__product_details);
    ?>

        <h2>Selected Order Details</h2>
        <br>
        <div class="form-group">
            <label for="orderIDSelected">Order Number: <?php echo $submit_order_number ?></label>
        </div>

        <div class="form-group">
            <label for="usernameSelected">Customer Username: <?php echo $customer_username ?> </label>
        </div>

        <div class="tablebox7">
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
                while ($row = $result_order_product_details->fetch_assoc()) {
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
                            if ($row['discount_quantity'] != 0) {
                                echo ($row['discount'] / $row['discount_quantity']);
                            } else {
                                echo $row['discount'];
                            }
                            ?></td>
                        <td><?php echo $row['discount']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
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
</div>
<?php  }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["bulk_operation"])) {

?>
    <h2>Selected Invoice</h2>
    <div class="tablebox8">
        <table>
            <tr>
                <th>Invoice Number</th>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Order date</th>
                <th>Order time</th>
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
                <th>Net Amount</th>

            </tr>


            <?php
            require_once "config.php";

            //For generate invoice Number
            $sql_max_invoice_number = 1;

            // Generate a 4-digit invoice number with leading zeros
            $next_invoice_number_padded = str_pad($sql_max_invoice_number, 4, '0', STR_PAD_LEFT);

            // Initialyy tatal net amount identify ass 0 value,
            $totalNetAmount = 0;
            if (isset($_POST['selected_orders'])) {
                foreach ($_POST['selected_orders'] as $selected_order) {
                    $submit_order_number = $selected_order;
                    $submitted_orders[] = $submit_order_number;

                    $sql_select_order_bulk = "SELECT * FROM order_details WHERE order_number = '$submit_order_number'";
                    $result_order_bulk = $conn->query($sql_select_order_bulk);
                    while ($row1 = $result_order_bulk->fetch_assoc()) {
            ?>
                        <tr>
                            <td><?php echo  $next_invoice_number_padded; ?></td>
                            <td><?php echo $row1['order_number']; ?></td>
                            <td><?php echo $row1['username']; ?></td>
                            <td><?php echo $row1['order_date']; ?></td>
                            <td><?php echo $row1['order_time']; ?></td>
                            <?php
                            $sql_select_products_bulk = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
                            $result_products_bulk = $conn->query($sql_select_products_bulk);
                            $isFirst = true;
                            while ($row2 = $result_products_bulk->fetch_assoc()) {
                                if (!$isFirst) {
                                    echo "<td colspan=\"4\" align=\"right\"> <td>";
                                }
                            ?>

                                <td><?php echo $row2['product_name']; ?></td>
                                <td><?php echo $row2['product_code']; ?></td>
                                <td><?php echo $row2['unit_price']; ?></td>
                                <td><?php echo $row2['quantity']; ?></td>
                                <td><?php echo $row2['free_quantity']; ?></td>
                                <td><?php echo ($row2['free_quantity'] + $row2['quantity']); ?></td>
                                <td><?php echo $row2['discount_quantity']; ?></td>
                                <td><?php
                                    if ($row2['discount_quantity'] != 0) {
                                        echo ($row2['discount'] / $row2['discount_quantity']);
                                    } else {
                                        echo $row2['discount'];
                                    }
                                    ?></td>
                                <td><?php echo $row2['discount']; ?></td>
                                <td><?php echo $row2['amount']; ?></td>
                        </tr>
                    <?php
                                $isFirst = false;
                            }
                            $totalNetAmount = $totalNetAmount + $row1['net_amount'];

                            // Increase the invoice number,
                            $sql_max_invoice_number += 1;
                            // Generate a 4-digit invoice number with leading zeros
                            $next_invoice_number_padded = str_pad($sql_max_invoice_number, 4, '0', STR_PAD_LEFT);

                    ?>
                    <td colspan="16" align="right"><?php echo $row1['net_amount']; ?></td>


                    </tr>
        <?php
                    }
                }
            }
        ?>
        <tr>
            <td colspan="15" align="right">Total Net Amount :</td>
            <td><?php 
            $formattedTotalNetAmount = number_format($totalNetAmount, 2);
            echo $formattedTotalNetAmount; ?></td>

        </tr>
        </table>
    </div>

    <div class="button-container">
        <form action="generate_invoce_csv.php" method="post">
        <?php foreach ($submitted_orders as $order_number) { ?>
            <input type="hidden" name="order_number[]" value="<?php echo $order_number; ?>">
        <?php } ?>

            <button type="submit" name="print_order" class="submit-buttons">Print CSV</button>
        </form>

        <form action="generate_invoice_pdf.php" method="post">
        <?php foreach ($submitted_orders as $order_number) { ?>
            <input type="hidden" name="order_number[]" value="<?php echo $order_number; ?>">
        <?php } ?>
            <button type="submit" name="print_order" class="submit-buttons">Print PDF</button>
        </form>
        </div>

    <?php

    }

    ?>