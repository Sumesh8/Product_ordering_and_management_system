<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["print_order"])) {
    $order_number = $_POST["order_number"];

    $csv_file_path = 'Order_product_detals.csv';

    $csv_file = fopen($csv_file_path, 'w');
    fputcsv($csv_file, [
        'Product Name', 
        'Product Code', 
        'Unit Price', 
        'Purchased Quantity', 
        'Free Quantity', 
        'Total Quantity', 
        'Discount Quantity', 
        'Unit Discount', 
        'Total Discount', 
        'Amount'
    ]);

    $netAmount = 0;
    $sql_select_order = "SELECT * FROM purchased_product WHERE order_number = '$order_number'";
    $result_order = $conn->query($sql_select_order);
    if ($result_order->num_rows > 0) {
        while ($row = $result_order->fetch_assoc()) {
            $netAmount += $row['amount'];
            fputcsv($csv_file, [
                $row['product_name'],
                $row['product_code'],
                $row['unit_price'],
                $row['quantity'],
                $row['free_quantity'],
                $row['free_quantity'] + $row['quantity'],
                $row['discount_quantity'],
                $row['discount_quantity'] != 0 ? ($row['discount'] / $row['discount_quantity']) : $row['discount'],
                $row['discount'],
                $row['amount']
            ]);
        }
    }
    
    // Add a row for Net Amount
    fputcsv($csv_file, ['', '', '', '', '', '', '', '', 'Net Amount:', $netAmount]);

    // Close the CSV file
    fclose($csv_file);

    // Set the appropriate headers for file download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="Order_product_detals.csv"');
    header('Content-Length: ' . filesize($csv_file_path));

    // Output the CSV file to the browser
    readfile($csv_file_path);

    // Delete the generated CSV file from the server
    unlink($csv_file_path);

    // Exit to prevent any additional output
    exit;
}
?>