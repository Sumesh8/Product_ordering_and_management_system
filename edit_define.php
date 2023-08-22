<!-- Edit Free Issues Form -->
<div class="form-box">
    <form method="post" action = "add_edit_define.php">
        <div class="form-group">
            <label for="free_issues_label">Free Issues Label:</label>
            <input type="text" name="free_issues_label" id="free_issues_label" required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="flat">flat</option>
                <option value="multiple">multiple</option>
            </select>
        </div>
        <?php
        require_once('config.php');
        // SQL query to select product_name and free_product from product table
        $sql_select_products = "SELECT product_name,free_product FROM product";
        $result_products = $conn->query($sql_select_products);
        ?>

        <div class="form-group">
            <label for="purchase_product2">Purchase Product:</label>
            <select name="purchase_product2" id="purchase_product2" required>
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
        <input type="hidden" name="selected_product_name2" id="selected_product_name2" value="">

        <div class="form-group">
            <label for="free_product_for_edit">Free Product:</label>
            <input type="text" name="free_product_for_edit" id="free_product_for_edit" readonly>
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

        <button type="submit" name="update_define_register" class = "submit-buttons" >Update Free Issue</button>
    </form>

 

    
    <script>
        // Function to update the input field with the selected value
        function updatehiddenField2() {
            var dropdown = document.getElementById('purchase_product2');
            var selectedValue = dropdown.options[dropdown.selectedIndex];
            var inputField = document.getElementById('selected_product_name2');
            inputField.value = selectedValue.text;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product2').addEventListener('change', updatehiddenField2);

        // Trigger the change event initially to set the initial value
        updatehiddenField2();

        // Function to update the input field with the selected value
        function updateInputField2() {
            var dropdown = document.getElementById('purchase_product2');
            var selectedValue = dropdown.value;
            var inputField = document.getElementById('free_product_for_edit');
            inputField.value = selectedValue;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product2').addEventListener('change', updateInputField2);

        // Trigger the change event initially to set the initial value
        updateInputField2();
    </script>

</div>