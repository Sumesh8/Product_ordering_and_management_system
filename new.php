<?php 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["print_order"])) {
    $order_number = $_POST["order_number"];
echo $order_number;
}
?>