<?php include 'admindashboard.php'; ?>

<div class="error-message">
<?php
// Check if the discount_label parameter exists in the URL
if (isset($_GET['discount_label'])) {
    $discount_label = $_GET['discount_label'];
}
require_once('config.php');
    // Prepare the SQL query to delete the discount the user table
    $sql = "DELETE FROM products_discount WHERE discount_label = '$discount_label'";

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        $Message = "Discount deleted successfully.";
    } else {
        $Message = "Error deleting discount: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
        echo "<div class=\"error-message\">";
        echo $Message;
        echo "<div class = \"ok-button\">";
        echo "<td><a href=\"admindashboard.php#define-discount\">OK</a></td>";
        echo "</div>";
        echo "</div>";
?>
</div>