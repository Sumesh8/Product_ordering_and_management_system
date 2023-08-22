<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["print_order"])) {
    $csv_file_path = 'Invoice.csv';

    $csv_file = fopen($csv_file_path, 'w');
    fputcsv($csv_file, [
        'Invoice Number', 
        'Order Number', 
        'Customer Name', 
        'Order date', 
        'Order time', 
        'Product Name', 
        'Product Code', 
        'Unit Price', 
        'Pusrchased Quantity', 
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

// Initialyy tatal net amount identify ass 0 value,
$totalNetAmount = 0;
if (isset($_POST['order_number'])) {
    foreach ($_POST['order_number'] as $selected_order) {
        $submit_order_number = $selected_order;
        $submitted_orders[] = $submit_order_number;

        $sql_select_order_bulk = "SELECT * FROM order_details WHERE order_number = '$submit_order_number'";
        $result_order_bulk = $conn->query($sql_select_order_bulk);
        while ($row1 = $result_order_bulk->fetch_assoc()) {
            $row_data = [
                 $next_invoice_number_padded ,
                 $row1['order_number'], 
                 $row1['username'], 
                 $row1['order_date'], 
                 $row1['order_time']
            ];

                $sql_select_products_bulk = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
                $result_products_bulk = $conn->query($sql_select_products_bulk);
                $isFirst = true;
                while ($row2 = $result_products_bulk->fetch_assoc()) {
                    if (!$isFirst) {
                         $row_data = [
                            '','','','',''
                         ];
                    }
                    
                    $row_data[] = $row2['product_name'];
                    $row_data[] = $row2['product_code'];
                    $row_data[] = $row2['unit_price'];
                    $row_data[] = $row2['quantity'];
                    $row_data[] = $row2['free_quantity'];
                    $row_data[] = $row2['free_quantity'] + $row2['quantity'];
                    $row_data[] = $row2['discount_quantity'];
                    if ($row2['discount_quantity'] != 0) {
                        $row_data[] = ($row2['discount'] / $row2['discount_quantity']);
                    } else {
                        $row_data[] =  $row2['discount'] ;
                    }
                    $row_data[] = $row2['discount'];
                    $row_data[] = $row2['amount'];
                    $isFirst = false;
                    fputcsv($csv_file, $row_data);
                }

                // Increase the invoice number,
                $sql_max_invoice_number += 1;
                // Generate a 4-digit invoice number with leading zeros
                $next_invoice_number_padded = str_pad($sql_max_invoice_number, 4, '0', STR_PAD_LEFT);

                fputcsv($csv_file, [
                    '', '', '', '', '', '', '', '', '', '','','', '','', 
                    'Net Amount', 
                    $row1['net_amount']
                ]);

                $totalNetAmount = $totalNetAmount + $row1['net_amount'];

                    }
                }
                
            }
            fputcsv($csv_file, [
                '', '', '', '', '', '', '', '', '', '','','', '','', 
                'Total Net Amount', 
                $totalNetAmount
            ]);


            // Close the CSV file
    fclose($csv_file);

    // Set the appropriate headers for file download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="Invoice.csv"');
    header('Content-Length: ' . filesize($csv_file_path));

    // Output the CSV file to the browser
    readfile($csv_file_path);

    // Delete the generated CSV file from the server
    unlink($csv_file_path);

    // Exit to prevent any additional output
    exit;
        }
