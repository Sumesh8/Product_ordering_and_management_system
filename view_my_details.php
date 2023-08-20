<?php

require_once('config.php');
    // Check if the username already exists in the database
    $sql_check_username = "SELECT * FROM user WHERE username = '$username'";
    $result_check_username = $conn->query($sql_check_username);
    if ($result_check_username->num_rows > 0) {
        $userCode = $row['user_code'];     
        } 
?>


<div class="view-order">
    <?php
    require_once "config.php";
    // SQL query to select order number and free_product from product table
    $sql_select_order_details = "SELECT * FROM order_details WHERE username = '$username' ";
    $result_order_details = $conn->query($sql_select_order_details);
    ?>
    <div class="tablebox6">
    <form id="bulkForm" method="post">
        <table>
            <tr>
                
                <th>Order Number</th>
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
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['order_time']; ?></td>
                        <td><?php echo $row['net_amount']; ?></td>
                        

                       

                        <td>
                            <form method="post">
                                <input type="hidden" name="order_number" value="<?php echo $row['order_number']; ?>">
                                <button type="submit" name="submit_order_details" class = "submit-buttons">View</button>
                            </form>
                        </td>

                        <td><input type="checkbox" name="selected_orders[]" value="<?php echo $row['order_number']; ?>"> </td>
                    </tr>
            <?php }
            }
            ?>
            <tr>
                <td colspan="5"></td>
                <td>
                    <button type="submit" name="bulk_operation" class="submit-buttons">Perform Bulk Operation</button>
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

        // SQL query to select order number and free_product from product table
        $sql_select_order__product_details = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
        $result_order_product_details = $conn->query($sql_select_order__product_details);
    ?>

<h2>Selected Order Details</h2>


        <div class="tablebox7">
            <table>
                <tr><th>Product Name</th>
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
                echo "<br><br>";
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
                <?php } ?>

                <tr>
                    <td colspan="9" align="right"><strong>Total Net Amount:</strong></td>
                    <td><?php echo $netAmount; ?></td>
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

                $sql_select_order_bulk = "SELECT * FROM order_details WHERE order_number = '$submit_order_number'";
                $result_order_bulk = $conn->query($sql_select_order_bulk);
                while ($row1 = $result_order_bulk->fetch_assoc()) {
                    ?>
                    <tr>
                    <td><?php echo  $next_invoice_number_padded; ?></td>
                    <td><?php echo $row1['order_number']; ?></td>
                    <td><?php echo $row1['order_date']; ?></td>
                    <td><?php echo $row1['order_time']; ?></td>
                    <?php
                    $sql_select_products_bulk = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
                    $result_products_bulk = $conn->query($sql_select_products_bulk);
                    $isFirst = true;
                    while ($row2 = $result_products_bulk->fetch_assoc()) {
                        if(!$isFirst){
                            echo "<td colspan=\"3\" align=\"right\"> <td>" ;
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
                        if($row2['discount_quantity'] != 0){
                            echo ($row2['discount'] / $row2['discount_quantity']); 
                        }
                        else{
                            echo $row2['discount'] ;
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
                    <td colspan="15" align="right"><?php echo $row1['net_amount']; ?></td>


                </tr>
                <?php
                }
                
        }
    }
        ?>
        <tr>
        <td colspan="14" align="right">Total Net Amount :</td>
        <td ><?php echo $totalNetAmount; ?></td>

    </tr>
    </table>
</div>

<?php

}

?>