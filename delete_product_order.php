<?php include 'admindashboard.php'; ?>

<div class="error-message">
<?php
// Check if the id parameter exists in the URL
if (isset($_GET['product_order_id'])) {
    $product_order_id = $_GET['product_order_id'];
}
require_once('config.php');
    // Prepare delete the product order from placing order
    $sql1 = "DELETE FROM placing_order WHERE id = '$product_order_id'";

    if ($conn->query($sql1) === TRUE) {
        $Message = "Product Order deleted successfully.";
    }

else {
    $Message = "Error deleting product order from placing order: " . $conn->error;
}

$conn->close();
echo "<div class=\"error-message\">";
echo $Message;
echo "<div class = \"ok-button\">";
echo "<td><a href=\"admindashboard.php#placing-order\">OK</a></td>";
echo "</div>";
echo "</div>";
?>
</div>