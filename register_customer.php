<div class="form-box">

        <?php
        require_once('config.php');
        // Get the highest user_code from the database
        $sql_max_user_code = "SELECT MAX(user_code) AS max_user_code FROM user";
        $result_max_user_code = $conn->query($sql_max_user_code);
        $row_max_user_code = $result_max_user_code->fetch_assoc();
        $next_user_code = $row_max_user_code["max_user_code"] + 1;

        // Generate a 4-digit user code with leading zeros
        $next_user_code_padded = str_pad($next_user_code, 4, '0', STR_PAD_LEFT);
        ?>

        <form method="post" >
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" name="customer_name" id="customer_name" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="usename" required>
            </div>

            <div class="form-group">
                <label for="customer_code">Customer Code:</label>
                <input type="text" name="customer_code" id="customer_code" value="<?php echo $next_user_code_padded; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="customer_address">Customer Address:</label>
                <input type="text" name="customer_address" id="customer_address" required>
            </div>

            <div class="form-group">
                <label for="customer_contact">Customer contact:</label>
                <input type="text" name="customer_contact" id="customer_contact" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button type="submit" name="submit_customer_register" class= "submit-buttons">Register Customer</button>
        </form>

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
                    echo "Error: Username already exists. Please choose a different username.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }

                // Validate contact number (must be exactly 10 digits)
                if (!preg_match("/^[0-9]{10}$/", $customer_contact)) {
                    echo "Error: Contact number must be exactly 10 digits.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }

                // Validate password and confirm password match
                if ($password !== $confirm_password) {
                    echo "Error: Passwords do not match.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }

                // Insert the new customer data into the user table
                $sql_insert_customer = "INSERT INTO user (user_code, name, address, contact_number, password, type, username)
                            VALUES ($next_user_code_padded, '$customer_name', '$customer_address', '$customer_contact', '$password', 'non admin','$username')";
                            
                if ($conn->query($sql_insert_customer) === TRUE) {
                    echo "Customer registration successful!";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                } else {
                    echo "Error: " . $sql_insert_customer . "<br>" . $conn->error;
                }
            }
            ?>
        </div>

</div>