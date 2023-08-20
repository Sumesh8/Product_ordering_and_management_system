<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php

    // Check if the order_number parameter exists in the URL
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_order"])) {
        $order_number = $_POST['order_number'];

        require_once('config.php');

        // Prepare the SQL query to delete the order from the order table
        $sql1 = "DELETE FROM order_details WHERE order_number = '$order_number'";

        // Execute the delete query
        if ($conn->query($sql1) === TRUE) {
            $sql2 = "DELETE FROM purchased_product WHERE order_number = '$order_number'";

            if ($conn->query($sql2) === TRUE) {
                // Order deletion successful
                $Message = "Order deleted successfully.";
            } else {
                $Message = "Error deleting Order products: " . $conn->error;
            }
        } else {
            $Message = "Error deleting Order: " . $conn->error;
        }

        $conn->close();
        echo "<div class=\"error-message\">";
        echo $Message;
        echo "<div class = \"ok-button\">";
        echo "<td><a href=\"admindashboard.php#placing-order\">OK</a></td>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>