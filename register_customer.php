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

        <form method="post" action = "add_register_customer.php">
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
</div>
