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





    <form method="post">
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

    <div class="error-message">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_define_register"])) {
            $type = $_POST["type"];
            $product_name = $_POST["selected_product_name"];
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
            
            $free_product_for_define = $_POST["free_product_for_define"];
            $purchase_quantity = $_POST["purchase_quantity"];
            $free_quantity = $_POST["free_quantity"];
            $lower_limit = $_POST["lower_limit"];
            $upper_limit = $_POST["upper_limit"];

            if ($free_product_for_define == "no") {
                echo "Error: Not a free product.";
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

            if (!filter_var($free_quantity, FILTER_VALIDATE_INT)) {
                echo "Error: free quantity must be integer.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            if ($purchase_quantity < $free_quantity) {
                echo "Error: free quantity must be lower than purchase quantity.";
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
                echo "Error: free limit be lower than upper limit.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            // Check if the free issue already exists in the database
            $sql_check_define = "SELECT * FROM define_free_issues WHERE product_code = '$product_code'";
            $result_check_define = $conn->query($sql_check_define);
            if ($result_check_define->num_rows > 0) {
                echo "Error: free issues already exists. Please choose a different free issues.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            // Insert the free issues data into the user table
            $sql_insert_define = "INSERT INTO define_free_issues (free_issues_label, type, product_code, purchase_quantity, free_quantity, lower_limit, upper_limit)
                            VALUES ($next_define_code_padded,'$type', '$product_code', '$purchase_quantity', '$free_quantity', '$lower_limit', '$upper_limit' )";

            if ($conn->query($sql_insert_define) === TRUE) {
                echo "Free issue registration successful!";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
            } else {
                echo "Error: " . $sql_insert_define . "<br>" . $conn->error;
            }
        }
        ?>
    </div>

</div>