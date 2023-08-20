<?php include 'admindashboard.php'; ?>

<div class="error-message">
<?php
// Check if the user_code parameter exists in the URL
if (isset($_GET['user_code'])) {
    $user_code = $_GET['user_code'];
}
require_once('config.php');
    // Prepare the SQL query to delete the user from the user table
    $sql = "DELETE FROM user WHERE user_code = '$user_code'";

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        $Message = "User deleted successfully.";
    } else {
        $Message = "Error deleting user: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
    echo "<div class=\"error-message\">";
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#customer-registration\">OK</a></td>";
    echo "</div>";
    echo "</div>";
?>
</div>
