<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_customer_register"])) {
        $customer_name = $_POST["customer_name"];
        $username = $_POST["username"];
        $customer_address = $_POST["customer_address"];
        $customer_contact = $_POST["customer_contact"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Check if the username already exists in the database
        $sql_check_username = "SELECT * FROM user WHERE username = '$username'";
        $result_check_username = $conn->query($sql_check_username);
        if ($result_check_username->num_rows > 0) {
            $Message = "Error: Username already exists. Please choose a different username.";
        }

        // Validate contact number (must be exactly 10 digits)
        elseif (!preg_match("/^[0-9]{10}$/", $customer_contact)) {
            $Message = "Error: Contact number must be exactly 10 digits.";
        }

        // Validate password and confirm password match
        elseif ($password !== $confirm_password) {
            $Message = "Error: Passwords do not match.";
        } else {
            // Insert the new customer data into the user table
            $sql_insert_customer = "INSERT INTO user (user_code, name, address, contact_number, password, type, username)
                            VALUES ($next_user_code_padded, '$customer_name', '$customer_address', '$customer_contact', '$password', 'non admin','$username')";

            if ($conn->query($sql_insert_customer) === TRUE) {
                $Message = "Customer registration successful!";
            } else {
                $Message = "Error: " . $sql_insert_customer . "<br>" . $conn->error;
            }
        }
    }
    $conn->close();
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#customer-registration\">   OK</a></td>";
    echo "</div>";
    ?>
</div>
