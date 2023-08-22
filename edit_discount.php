<div class="form-box">
<form method="post" action = "add_edit_discount.php">
        <div class="form-group">
            <label for="discount_label">Discount Label:</label>
            <input type="text" name="discount_label" id="discount_label"  required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="flat">flat</option>
                <option value="multiple">multiple</option>
            </select>
        </div>
        <?php

        // SQL query to select product_name and has discount from product table
        $sql_select_products = "SELECT product_name,has_discount FROM product";
        $result_products = $conn->query($sql_select_products);
        ?>

        <div class="form-group">
            <label for="purchase_product4">Purchase Product:</label>
            <select name="purchase_product4" id="purchase_product4" required>
                <?php
                // Check if there are any rows returned
                if ($result_products->num_rows > 0) {
                    while ($row = $result_products->fetch_assoc()) {
                        $product_name = $row['product_name'];
                        $has_discount = $row['has_discount'];
                        echo "<option value='$has_discount'>$product_name </option>";
                    }
                } else {
                    // No products found
                    echo "<option disabled selected>No products found</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="selected_product_name5" id="selected_product_name5" value="">

        <div class="form-group">
            <label for="discount_product_for_edit">discount statues:</label>
            <input type="text" name="discount_product_for_edit" id="discount_product_for_edit" readonly>
        </div>

        <div class="form-group">
            <label for="purchase_quantity">Purchase Quantity:</label>
            <input type="text" name="purchase_quantity" id="purchase_quantity" required>
        </div>

        <div class="form-group">
            <label for="discount">discount Amount:</label>
            <input type="text" name="discount" id="discount" required>
        </div>

        <div class="form-group">
            <label for="lower_limit">Lower Limit:</label>
            <input type="text" name="lower_limit" id="lower_limit" required>
        </div>

        <div class="form-group">
            <label for="upper_limit">Upper Limit:</label>
            <input type="text" name="upper_limit" id="upper_limit" required>
        </div>

        <button type="submit" name="submit_discount_edit" class = "submit-buttons">Edit discount</button>
    </form>

    <script>
        // Function to update the input field with the selected value
        function updatehiddenDiscountEditField() {
            var dropdown = document.getElementById('purchase_product4');
            var selectedValue = dropdown.options[dropdown.selectedIndex];
            var inputField = document.getElementById('selected_product_name5');
            inputField.value = selectedValue.text;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product4').addEventListener('change', updatehiddenDiscountEditField);

        // Trigger the change event initially to set the initial value
        updatehiddenDiscountEditField();

        // Function to update the input field with the selected value
        function updateInputDiscountEditField() {
            var dropdown = document.getElementById('purchase_product4');
            var selectedValue = dropdown.value;
            var inputField = document.getElementById('discount_product_for_edit');
            inputField.value = selectedValue;
        }

        // Add event listener to the select element
        document.getElementById('purchase_product4').addEventListener('change', updateInputDiscountEditField);

        // Trigger the change event initially to set the initial value
        updateInputDiscountEditField();
    </script>

</div>