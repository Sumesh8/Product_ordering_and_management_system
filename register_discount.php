<div class="form-box">

    <?php
    
    require_once('config.php');
    // Get the highest Register discount code from the database
    $sql_max_discount_code = "SELECT MAX(discount_label) AS max_discount_code FROM products_discount";
    $result_max_discount_code = $conn->query($sql_max_discount_code);
    $row_max_discount_code = $result_max_discount_code->fetch_assoc();
    $next_discount_code = $row_max_discount_code["max_discount_code"] + 1;

    // Generate a 4-digit Discount label code with leading zeros
    $next_discount_code_padded = str_pad($next_discount_code, 4, '0', STR_PAD_LEFT);
    ?>

    <form method="post" action = "add_register_discount.php">
        <div class="form-group">
            <label for="discount_label">Dicount Label:</label>
            <input type="text" name="discount_label" id="discount_label" value="<?php echo $next_discount_code_padded; ?>" readonly>
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
            <label for="purchase_product3">Purchase Product:</label>
            <select name="purchase_product3" id="purchase_product3" required>
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
        <input type="hidden" name="selected_product_name4" id="selected_product_name4" value="">

        <div class="form-group">
            <label for="discount_product_for_define">Discount Statues:</label>
            <input type="text" name="discount_product_for_define" id="discount_product_for_define" readonly>
        </div>

        <div class="form-group">
            <label for="purchase_quantity">Purchase Quantity:</label>
            <input type="text" name="purchase_quantity" id="purchase_quantity" required>
        </div>

        <div class="form-group">
            <label for="discount">Discount Amount:</label>
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

        <button type="submit" name="submit_discount_register" class = "submit-buttons" >Register discount</button>
    </form>

    <script>
    // Function to update the input field with the selected value
    function updateHiddenDiscountField() {
        var dropdown = document.getElementById('purchase_product3');
        var selectedValue = dropdown.options[dropdown.selectedIndex].text;
        var inputField = document.getElementById('selected_product_name4');
        inputField.value = selectedValue;
    }

    // Add event listener to the select element
    document.getElementById('purchase_product3').addEventListener('change', updateHiddenDiscountField);

    // Trigger the change event initially to set the initial value
    updateHiddenDiscountField();

    // Function to update the input field with the selected value
    function updateInputDiscountField() {
        var dropdown = document.getElementById('purchase_product3');
        var selectedValue = dropdown.value;
        var inputField = document.getElementById('discount_product_for_define');
        inputField.value = selectedValue;
    }

    // Add event listener to the select element
    document.getElementById('purchase_product3').addEventListener('change', updateInputDiscountField);

    // Trigger the change event initially to set the initial value
    updateInputDiscountField();
</script>

</div>