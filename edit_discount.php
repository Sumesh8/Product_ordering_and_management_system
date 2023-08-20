<div class="form-box">
<form method="post">
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




    <div class="error-message">
        <?php
        
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_discount_edit"])) {
            $discount_label = $_POST["discount_label"];
            $type = $_POST["type"];
            $product_name = $_POST["selected_product_name5"];
            // Select product code,
            $sql_check_product = "SELECT product_code FROM product WHERE product_name = '$product_name'";
            $result_products = $conn->query($sql_check_product);
            if ($result_products->num_rows > 0) {
                while ($row = $result_products->fetch_assoc()) {
                    $product_code = $row['product_code'];
                }
            } else {
                // No products found
                echo "Error: No products found.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            $discount_product_for_edit = $_POST["discount_product_for_edit"];
            $purchase_quantity = $_POST["purchase_quantity"];
            $discount = $_POST["discount"];
            $lower_limit = $_POST["lower_limit"];
            $upper_limit = $_POST["upper_limit"];

            // Check if the discount not exists in the database
            $sql_check_disconutcode = "SELECT * FROM products_discount WHERE discount_label = '$discount_label'";
            $result_check_discountcode = $conn->query($sql_check_disconutcode);

            if ($result_check_discountcode->num_rows == 0) {
                echo "Error: Invalid discount Label.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            // Check if the discount already exists in the database
            else if ($result_check_discountcode->num_rows > 0) {
                $row = $result_check_discountcode->fetch_assoc();
                if ($discount_label != $row['discount_label']) {
                    echo "Error: discount is already define.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }
            }

            if ($discount_product_for_edit == "no") {
                echo "Error: Not a discount product.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }
            if (!filter_var($purchase_quantity, FILTER_VALIDATE_INT)) {
                echo "Error: purchase quantity must be integer.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if (!filter_var($discount, FILTER_VALIDATE_FLOAT)) {
                echo "Error: price must be a valid floating-point number.";
                echo "<div class=\"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">OK</a></td>";
                echo "</div>";
                exit;
            }


            // Select product code,
            $sql_check_product = "SELECT price FROM product WHERE product_code = '$product_code'";
            $result_products = $conn->query($sql_check_product);
            if ($result_products->num_rows > 0) {
                while ($row = $result_products->fetch_assoc()) {
                    $unit_price = $row['price'];
                }
            } else {
                // No products found
                echo "Error: No products found.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if ($unit_price <= $discount) {
                echo "Error: Discount must be lower than purchase quantity.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if (!filter_var($lower_limit, FILTER_VALIDATE_INT)) {
                echo "Error: lower limit must be integer.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if (!filter_var($upper_limit, FILTER_VALIDATE_INT)) {
                echo "Error: upper_limit must be integer.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if ($upper_limit < $lower_limit) {
                echo "Error: lower limit must be lower than upper limit.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }



            // Check if the product name already exists in the database
            $sql_check_issues = "SELECT * FROM products_discount WHERE product_code = ' $product_code'";
            $result_check_issues = $conn->query($sql_check_issues);
            if ($result_check_issues->num_rows > 0) {
                $row = $result_check_issues->fetch_assoc();
                if ($discount_label != $row['discount_label']) {
                    echo "Error:  
                    discount is already define for this product.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }
            }

            $sql_update_discount = "UPDATE products_discount SET 
                type = '$type',
                product_code = '$product_code',
                purchase_quantity = '$purchase_quantity',
                discount = '$discount',
                lower_limit = '$lower_limit',
                upper_limit = '$upper_limit'
                WHERE discount_label = '$discount_label'";
            if ($conn->query($sql_update_discount) === TRUE) {
                echo "Discount update successful!";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
            } else {
                echo "Error: " . $sql_update_discount . "<br>" . $conn->error;
            }
        }
        ?>
    </div>

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