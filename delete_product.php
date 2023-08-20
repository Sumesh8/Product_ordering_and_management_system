<?php include 'admindashboard.php'; ?>

<div class="error-message">
<?php
// Check if the product_code parameter exists in the URL
if (isset($_GET['product_code'])) {
    $product_code = $_GET['product_code'];
}
require_once('config.php');
    // Prepare the SQL query to delete the free from the define free issues corresponding to the delete product
    $sql1 = "DELETE FROM define_free_issues WHERE product_code = '$product_code'";

    if ($conn->query($sql1) === TRUE) {
        // Prepare the SQL query to delete the discount from the products discount corresponding to the delete product
        $sql2 = "DELETE FROM products_discount WHERE product_code = '$product_code'";
    
        // Execute the delete query
        if ($conn->query($sql2) === TRUE) {
           // Prepare the SQL query to delete the product from product table
            $sql3 = "DELETE FROM product WHERE product_code = '$product_code'";

            // Execute the delete query
            if ($conn->query($sql3) === TRUE) {
                $Message = "Product deleted successfully.";
            } else {
                $Message = "Error deleting product: " . $conn->error;
            }
        }else {
            $Message = "Error deleting product from discounts_products: " . $conn->error;
    
        }
    }else {
        $Message = "Error deleting product from define_free_issues: " . $conn->error;
    }

    $conn->close();
        echo "<div class=\"error-message\">";
        echo $Message;
        echo "<div class = \"ok-button\">";
        echo "<td><a href=\"admindashboard.php#product-registration\">OK</a></td>";
        echo "</div>";
        echo "</div>";

?>
</div>