<!-- Edit Free Issues Form -->
<div class="form-box">
    <form method="post" >
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

 

    <div class="error-message">
        <?php
        
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_define_register"])) {
            $free_issues_label = $_POST["free_issues_label"];
            $type = $_POST["type"];
            $product_name = $_POST["selected_product_name2"];
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

            $free_product_for_edit = $_POST["free_product_for_edit"];
            $purchase_quantity = $_POST["purchase_quantity"];
            $free_quantity = $_POST["free_quantity"];
            $lower_limit = $_POST["lower_limit"];
            $upper_limit = $_POST["upper_limit"];

            // Check if the free issue not exists in the database
            $sql_check_issuescode = "SELECT * FROM define_free_issues WHERE free_issues_label = '$free_issues_label'";
            $result_check_issuescode = $conn->query($sql_check_issuescode);

            if ($result_check_issuescode->num_rows == 0) {
                echo "Error: Invalid Free Label.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }

            // Check if the issue label  already exists in the database
            else if ($result_check_issuescode->num_rows > 0) {
                $row = $result_check_issuescode->fetch_assoc();
                if ($free_issues_label != $row['free_issues_label']) {
                    echo "Error: Free issue already define.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }
            }

            if ($free_product_for_edit == "no") {
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
                echo "Error: lower limit must be lower than upper limit.";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
                exit;
            }



            // Check if the product name already exists in the database
            $sql_check_issues = "SELECT * FROM define_free_issues WHERE product_code = ' $product_code'";
            $result_check_issues = $conn->query($sql_check_issues);
            if ($result_check_issues->num_rows > 0) {
                $row = $result_check_issues->fetch_assoc();
                if ($free_issues_label != $row['free_issues_label']) {
                    echo "Error: Free issue already define for this product.";
                    echo "<div class = \"ok-button\">";
                    echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                    echo "</div>";
                    exit;
                }
            }

            $sql_update_define = "UPDATE define_free_issues SET 
                type = '$type',
                product_code = '$product_code',
                purchase_quantity = '$purchase_quantity',
                free_quantity = '$free_quantity',
                lower_limit = '$lower_limit',
                upper_limit = '$upper_limit'
                WHERE free_issues_label = '$free_issues_label'";
            if ($conn->query($sql_update_define) === TRUE) {
                echo "Free issues update successful!";
                echo "<div class = \"ok-button\">";
                echo "<td><a href=\"admindashboard.php\">   OK</a></td>";
                echo "</div>";
            } else {
                echo "Error: " . $sql_update_define . "<br>" . $conn->error;
            }
        }
        ?>
    </div>

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