<!-- edit_customer.php -->

<!-- Edit Customer Form -->
<div class="form-box" >
    <form method="post" action = "add_edit_customer.php">
        <div class="form-group">
            <label for="customer_code">Customer Code:</label>
            <input type="text" name="customer_code" id="customer_code" required>
        </div>
        <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" name="customer_name" id="customer_name" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
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

        <button type="submit" name="submit_customer_edit" class= "submit-buttons">Update Customer</button>
    </form>
    </div>

    