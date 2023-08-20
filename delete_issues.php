<?php include 'admindashboard.php'; ?>

<div class="error-message">
<?php
// Check if the free_issues_label parameter exists in the URL
if (isset($_GET['free_issues_label'])) {
    $free_issues_label = $_GET['free_issues_label'];
}
require_once('config.php');
    // Prepare the SQL query to delete the free issue from the user table
    $sql = "DELETE FROM define_free_issues WHERE free_issues_label = '$free_issues_label'";

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        $Message = "Free Issues deleted successfully.";
    } else {
        $Message = "Error deleting free Issue: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
        echo "<div class=\"error-message\">";
        echo $Message;
        echo "<div class = \"ok-button\">";
        echo "<td><a href=\"admindashboard.php#define-free-issues\">OK</a></td>";
        echo "</div>";
        echo "</div>";
        ?>
</div>