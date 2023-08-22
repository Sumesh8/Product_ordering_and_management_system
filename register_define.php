<div class="form-box">

    <?php
    
    require_once('config.php');
    // Get the highest Register Define Free Issue code from the database
    $sql_max_define_code = "SELECT MAX(free_issues_label) AS max_define_code FROM define_free_issues";
    $result_max_define_code = $conn->query($sql_max_define_code);
    $row_max_define_code = $result_max_define_code->fetch_assoc();
    $next_define_code = $row_max_define_code["max_define_code"] + 1;

    // Generate a 4-digit Define Free Issue code with leading zeros
    $next_define_code_padded = str_pad($next_define_code, 4, '0', STR_PAD_LEFT);
    ?>





    <form method="post" action = "add_register_define.php">
        <div class="form-group">
            <label for="free_issues_label">Free Issues Label:</label>
            <input type="text" name="free_issues_label" id="free_issues_label" value="<?php echo $next_define_code_padded; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="flat">flat</option>
                <option value="multiple">multiple</option>
            </select>
        </div>
        <?php
        // SQL query to select product_name and free_product from product table
        $sql_select_products = "SELECT product_name,free_product FROM product";
        $result_products = $conn->query($sql_select_products);
        ?>

        <div class="form-group">
            <label for="purchase_product">Purchase Product:</label>
            <select name="purchase_product" id="purchase_product" required>
                <?php
                // Check if there are any rows returned
                if ($result_products->num_rows > 0) {
                    while ($row = $result_products->fetch_assoc()) {
                        $product_name = $row['product_name'];
                        $free_product = $row['free_product'];
                        echo "<option value='$free_product'>$product_name</option>";
                    }
                } else {
                    // No products found
                    echo "<option disabled selected>No products found</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="selected_product_name" id="selected_product_name" value="">

        <div class="form-group">
            <label for="free_product_for_define">Free Product:</label>
            <input type="text" name="free_product_for_define" id="free_product_for_define" readonly>
        </div>

        <div class="form-group">
            <label for="purchase_quantity">Purchase Quantity:</label>
            <input type="text" name="purchase_quantity" id="purchase_quantity" required>
        </div>

        <div class="form-group">
            <label for="free_quantity">Free Quantity:</label>
            <input type="text" name="free_quantity" id="free_quantity" required>
        </div>

        <div class="form-group">
            <label for="lower_limit">Lower Limit:</label>
            <input type="text" name="lower_limit" id="lower_limit" required>
        </div>

        <div class="form-group">
            <label for="upper_limit">Upper Limit:</label>
            <input type="text" name="upper_limit" id="upper_limit" required>
        </div>

        <button type="submit" name="submit_define_register" class = "submit-buttons">Register Free Issue</button>
    </form>

    <script>
        // Function to update the input field with the selected value
        function updatehiddenField() {
            var dropdown = document.getElementById('purchase_product');
            var selectedValue = dropdown.options[dropdown.selectedIndex];
            var inputField = document.getElementById('selected_product_name');
            inputField.value = selectedValue.text;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product').addEventListener('change', updatehiddenField);

        // Trigger the change event initially to set the initial value
        updatehiddenField();

        // Function to update the input field with the selected value
        function updateInputField() {
            var dropdown = document.getElementById('purchase_product');
            var selectedValue = dropdown.value;
            var inputField = document.getElementById('free_product_for_define');
            inputField.value = selectedValue;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product').addEventListener('change', updateInputField);

        // Trigger the change event initially to set the initial value
        updateInputField();
    </script>
</div>
    