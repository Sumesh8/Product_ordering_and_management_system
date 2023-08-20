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
        $sql_max_invoice_number = 0;

        // Generate a 4-digit invoice number with leading zeros
        $next_invoice_number_padded = str_pad($next_user_code, 4, '0', STR_PAD_LEFT);



        // Initialyy tatal net amount identify ass 0 value,
        $totalNetAmount = 0;


        if (isset($_POST['selected_orders'])) {
            foreach ($_POST['selected_orders'] as $selected_order) {
                $submit_order_number = $selected_order;
                echo $submit_order_number;

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
                    $next_invoice_number_padded = str_pad($next_user_code, 4, '0', STR_PAD_LEFT);

                    ?>
                    <td colspan="15" align="right"><?php echo $row1['net_amount']; ?></td>


                </tr>
                <?php
                }
                
        }
    }
        ?>
        <tr>
        <td colspan="14" align="right">Total Net Amount</td>
        <td ><?php echo $totalNetAmount; ?></td>

    </tr>
    </table>
</div>