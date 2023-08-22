<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_order"])) {

        require_once('config.php');
        $Message = "";
        $username = $_POST['username'];
        $netAmount = $_POST['net_amount'];
        $orderDate = date('Y-m-d');
        $orderTime = date('H:i:s');


        //If Order is empty,
        if ($netAmount == 0.0) {
            $Message = "Please add product for the add order!";
        } else {
            // Insert the order data into the user table
            $sql_insert_order = "INSERT INTO order_details (order_number, username, order_date, order_time, net_amount)
                VALUES ('$next_order_code_padded','$username', '$orderDate', '$orderTime', '$netAmount' )";

            if ($conn->query($sql_insert_order) === TRUE) {
            } else {
                echo "Error: " . $sql_insert_order . "<br>" . $conn->error;
            }

            // Process each row of data and insert into the database
            $result_order = $conn->query("SELECT * FROM placing_order");

            while ($row = $result_order->fetch_assoc()) {
                $product_name = $row['product_name'];
                $product_code = $row['product_code'];
                $unit_price = $row['unit_price'];
                $quantity = $row['quantity'];
                $free_quantity = $row['free_quantity'];
                $discount_quantity = $row['discount_quantity'];
                $discount = $row['discount'];
                $amount = $row['amount'];

                $sql_order_product = "INSERT INTO purchased_product (id, order_number, username, product_name, product_code, unit_price, quantity, free_quantity, discount_quantity, discount, amount) VALUES 
                        (' ', '$next_order_code_padded', '$username', '$product_name', '$product_code', '$unit_price', '$quantity', '$free_quantity', '$discount_quantity', '$discount','$amount')";

                if ($conn->query($sql_order_product) === TRUE) {
                } else {
                    echo "Error: " . $sql_order_product . "<br>" . $conn->error;
                }
            }


            $delete_purchase = "DELETE FROM placing_order";

            // Execute the query
            if ($conn->query($delete_purchase) === TRUE) {
                $Message = "Order insert successful!";
            } else {
                $Message = "Error deleting records: " . $conn->error;
            }
        }
    }
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#placing-order\">   OK</a></td>";
    echo "</div>";
    ?>
</div>