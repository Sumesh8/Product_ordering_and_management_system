<?php include 'admindashboard.php'; ?>

<div class="error-message">
    <?php
    require_once('config.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_customer_edit"])) {
        $user_code = $_POST["customer_code"];
        $customer_name = $_POST["customer_name"];
        $username = $_POST["username"];
        $customer_address = $_POST["customer_address"];
        $customer_contact = $_POST["customer_contact"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Check if the usercode already exists in the database
        $sql_check_usercode = "SELECT * FROM user WHERE user_code = '$user_code'";
        $result_check_usercode = $conn->query($sql_check_usercode);

        // Check if the username already exists in the database
        $sql_check_username = "SELECT * FROM user WHERE username = '$username'";
        $result_check_username = $conn->query($sql_check_username);

        if ($result_check_usercode->num_rows == 0) {
            $Message = "Error: Invalid User.";
        } elseif ($result_check_usercode->num_rows > 0) {
            $row1 = $result_check_usercode->fetch_assoc();
            if ($customer_name != $row1['username']) {
                if ($result_check_username->num_rows > 0) {
                    $row2 = $result_check_username->fetch_assoc();
                    if ($username == $row2['username']) {
                        $Message = "Error: Username already exists. Please choose a different username.";
                    }
                }
            }
        }

        // Validate contact number (must be exactly 10 digits)
        elseif (!preg_match("/^[0-9]{10}$/", $customer_contact)) {
            $Message = "Error: Contact number must be exactly 10 digits.";
        }

        // Validate password and confirm password match
        elseif ($password !== $confirm_password) {
            $Message = "Error: Passwords do not match.";
        } else {
            // Update the customer data into the user 
            $sql_update_customer = "UPDATE user SET 
                name = '$customer_name',
                username = '$username',
                address = '$customer_address',
                contact_number = '$customer_contact'
                WHERE user_code = '$user_code'";

            if ($conn->query($sql_update_customer) === TRUE) {
                $Message =  "Customer update successful!";
            } else {
                $Message = "Error: " . $sql_update_customer . "<br>" . $conn->error;
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