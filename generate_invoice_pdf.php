<?php
require_once "config.php";
require_once "Libraries/tcpdf/tcpdf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["print_order"])) {

    // Create a new TCPDF instance
    $pdf = new TCPDF('L', 'mm', 'A1', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Sumesh');
    $pdf->SetAuthor('Sumesh');
    $pdf->SetTitle('Order Details');
    $pdf->SetSubject('Order Details');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add content to the PDF
    $pdf->Cell(40, 10, 'Invoice Number', 1);
    $pdf->Cell(40, 10, 'Order Number', 1);
    $pdf->Cell(40, 10, 'Customer Name', 1);
    $pdf->Cell(40, 10, 'Order date', 1);
    $pdf->Cell(40, 10, 'Order time', 1);
    $pdf->Cell(40, 10, 'Product Name', 1);
    $pdf->Cell(40, 10, 'Product Code', 1);
    $pdf->Cell(40, 10, 'Unit Price', 1);
    $pdf->Cell(40, 10, 'Pusrchased Quantity', 1);
    $pdf->Cell(40, 10, 'Free Quantity', 1);
    $pdf->Cell(40, 10, 'Total Quantity', 1);
    $pdf->Cell(40, 10, 'Discount Quantity', 1);
    $pdf->Cell(40, 10, 'Unit Discount', 1);
    $pdf->Cell(40, 10, 'Total Discount', 1);
    $pdf->Cell(40, 10, 'Amount', 1);
    $pdf->Cell(40, 10, 'Net Amount', 1);
    $pdf->Ln(); // Move to the next line

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
                $pdf->Cell(40, 10, $next_invoice_number_padded, 1);
                $pdf->Cell(40, 10, $row1['order_number'], 1);
                $pdf->Cell(40, 10, $row1['username'], 1);
                $pdf->Cell(40, 10, $row1['order_date'], 1);
                $pdf->Cell(40, 10, $row1['order_time'], 1);

                $sql_select_products_bulk = "SELECT * FROM purchased_product WHERE order_number = '$submit_order_number'";
                $result_products_bulk = $conn->query($sql_select_products_bulk);
                $isFirst = true;
                while ($row2 = $result_products_bulk->fetch_assoc()) {
                    if (!$isFirst) {
                        $pdf->Cell(200, 10, '', 1, 0, 'R');
                    }

                    $pdf->Cell(40, 10, $row2['product_name'], 1);
                    $pdf->Cell(40, 10, $row2['product_code'], 1);
                    $pdf->Cell(40, 10, $row2['unit_price'], 1);
                    $pdf->Cell(40, 10, $row2['quantity'], 1);
                    $pdf->Cell(40, 10, $row2['free_quantity'], 1);
                    $pdf->Cell(40, 10, $row2['free_quantity'] + $row2['quantity'], 1);
                    $pdf->Cell(40, 10, $row2['discount_quantity'], 1);

                    if ($row2['discount_quantity'] != 0) {
                        $pdf->Cell(40, 10, $row2['discount'] / $row2['discount_quantity'], 1);
                    } else {
                        $pdf->Cell(40, 10, $row2['discount'], 1);
                    }
                    $pdf->Cell(40, 10, $row2['discount'], 1);
                    $pdf->Cell(40, 10, $row2['amount'], 1);
                    $pdf->Cell(40, 10, '', 1);
                    $pdf->Ln(); // Move to the next line
                    $isFirst = false;
                }

                // Increase the invoice number,
                $sql_max_invoice_number += 1;
                // Generate a 4-digit invoice number with leading zeros
                $next_invoice_number_padded = str_pad($sql_max_invoice_number, 4, '0', STR_PAD_LEFT);

                $pdf->Cell(600, 10, 'Net Amount:', 1, 0, 'R');
                $pdf->Cell(40, 10, $row1['net_amount'], 1);
                $pdf->Ln(); // Move to the next line

                $totalNetAmount = $totalNetAmount + $row1['net_amount'];
            }
        }
    }

    $pdf->Cell(600, 10, 'Total Net Amount', 1, 0, 'R');
    $formattedTotalNetAmount = number_format($totalNetAmount, 2);
    $pdf->Cell(40, 10, $formattedTotalNetAmount , 1);
    $pdf->Ln(); // Move to the next line


    // Output PDF to the browser
    $pdf->Output('order_details.pdf', 'D');

    // Exit to prevent any additional output
    exit;
}
