<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_numbers"])) {

    $submitted_orders = json_decode($_POST['order_numbers'], true);

    $csv_file_path = 'table_data.csv';

    $csv_file = fopen($csv_file_path, 'w');
    fputcsv($csv_file, [
        'Invoice Number',
        'Order Number',
        'Customer Name',
        'Order date',
        'Order Time',
        'Product Name', 
        'Product Code', 
        'Unit Price', 
        'Purchased Quantity', 
        'Free Quantity', 
        'Total Quantity', 
        'Discount Quantity', 
        'Unit Discount', 
        'Total Discount', 
        'Amount',
        'Net Amount'

    ]);

    //For generate invoice Number
    $sql_max_invoice_number = 1;

    // Generate a 4-digit invoice number with leading zeros
    $next_invoice_number_padded = str_pad($sql_max_invoice_number, 4, '0', STR_PAD_LEFT);

    // Initialyy tatal net amount identify ass 0 value,
    $totalNetAmount = 0;

    foreach ( $submitted_orders as $selected_order) {
        $submit_order_number = $selected_order;

        $sql_select_order_bulk = "SELECT * FROM order_details WHERE order_number = '$submit_order_number'";
        $result_order_bulk = $conn->query($sql_select_order_bulk);

        while ($row1 = $result_order_bulk->fetch_assoc()) {
            fputcsv($csv_file, [
                $next_invoice_number_padded,
                $row1['order_number'],
                $row1['username'],
                $row1['order_date'],
                $row1['order_time'],
            ]);

                $sql_select_products_bulk = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
                $result_products_bulk = $conn->query($sql_select_products_bulk);
                $isFirst = true;
                while ($row2 = $result_products_bulk->fetch_assoc()) {
                    if (!$isFirst) {
                        echo "<td colspan=\"4\" align=\"right\"> <td>";
                    }
                
                }
    }

    ?>

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
    <td><?php echo $totalNetAmount; ?></td>

</tr>
</table>
    
    // Add a row for Net Amount
    fputcsv($csv_file, ['', '', '', '', '', '', '', '', 'Net Amount:', $netAmount]);

    // Close the CSV file
    fclose($csv_file);

    // Set the appropriate headers for file download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="table_data.csv"');
    header('Content-Length: ' . filesize($csv_file_path));

    // Output the CSV file to the browser
    readfile($csv_file_path);

    // Delete the generated CSV file from the server
    unlink($csv_file_path);

    // Exit to prevent any additional output
    exit;
}
?>