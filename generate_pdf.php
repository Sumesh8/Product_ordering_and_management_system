<?php
require_once "config.php";
require_once "Libraries/tcpdf/tcpdf.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["print_order"])) {
    $order_number = $_POST["order_number"];

    // Create a new TCPDF instance
    $pdf = new TCPDF('L', 'mm', 'A3', true, 'UTF-8', false);

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
    $pdf->Cell(40, 10, 'Product Name', 1);
    $pdf->Cell(40, 10, 'Product Code', 1);
    $pdf->Cell(40, 10, 'Unit Price', 1);
    $pdf->Cell(40, 10, 'Purchased Quantity', 1);
    $pdf->Cell(40, 10, 'Free Quantity', 1);
    $pdf->Cell(40, 10, 'Total Quantity', 1);
    $pdf->Cell(40, 10, 'Unit Discount', 1);
    $pdf->Cell(40, 10, 'Total Discount', 1);
    $pdf->Cell(40, 10, 'Amount', 1);
    $pdf->Ln(); // Move to the next line


    $netAmount = 0;
    $sql_select_order = "SELECT * FROM purchased_product WHERE order_number = '$order_number'";
    $result_order = $conn->query($sql_select_order);
    while ($row = $result_order->fetch_assoc()) {
        $netAmount += $row['amount'];
        // Add data to PDF using Cell method
        $pdf->Cell(40, 10, $row['product_name'], 1);
        $pdf->Cell(40, 10, $row['product_code'], 1);
        $pdf->Cell(40, 10, $row['unit_price'], 1);
        $pdf->Cell(40, 10, $row['quantity'], 1);
        $pdf->Cell(40, 10, $row['free_quantity'], 1);
        $pdf->Cell(40, 10, ($row['quantity']+$row['free_quantity']) , 1);
        $pdf->Cell(40, 10, ($row['discount_quantity'] != 0 ? ($row['discount'] / $row['discount_quantity']) : $row['discount']), 1);
        $pdf->Cell(40, 10, $row['discount'], 1);
        $pdf->Cell(40, 10, $row['amount'], 1);
        $pdf->Ln(); // Move to the next line
    }

    // Add Net Amount row
    $pdf->Cell(320, 10, 'Net Amount:', 1, 0, 'R');
    $formattedNetAmount = number_format($netAmount, 2);
    $pdf->Cell(40, 10, $formattedNetAmount , 1);

    // Output PDF to the browser
    $pdf->Output('order_details.pdf', 'D'); 

    // Exit to prevent any additional output
    exit;
}
?>



