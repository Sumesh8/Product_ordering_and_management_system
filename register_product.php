<div class="form-box">

        <?php
        require_once('config.php');
        // Get the highest product_code from the database
        $sql_max_product_code = "SELECT MAX(product_code) AS max_product_code FROM product";
        $result_max_product_code = $conn->query($sql_max_product_code);
        $row_max_product_code = $result_max_product_code->fetch_assoc();
        $next_product_code = $row_max_product_code["max_product_code"] + 1;

        // Generate a 4-digit product_code with leading zeros
        $next_product_code_padded = str_pad($next_product_code, 4, '0', STR_PAD_LEFT);
        ?>

        <form method="post" action = "add_register_product.php">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="product_name" required>
            </div>
            
            <div class="form-group">
                <label for="product_code">Product Code:</label>
                <input type="text" name="product_code" id="product_code" value="<?php echo $next_product_code_padded; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="product_price">Product Price:</label>
                <input type="text" name="product_price" id="product_price" required>
            </div>
            
            <div class="form-group">
                <label for="free_product">Free Product:</label>
                <select name="free_product" id="free_product" required>
                    <option value="yes">yes</option>
                    <option value="no">no</option>
                </select>
            </div>

            <div class="form-group">
                <label for="has_discount">Disscount :</label>
                <select name="has_discount" id="has_discount" required>
                    <option value="yes">yes</option>
                    <option value="no">no</option>
                </select>
            </div>
         
            <div class="form-group">
                <label for="expiry_date">expiry Date:</label>
                <input type="date" name="expiry_date" id="expiry_date" required>
            </div>
            
            <button type="submit" name="submit_product_register" class= "submit-buttons">Register Product</button>
        </form>
        </div>

        