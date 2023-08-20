

<!-- Edit Product Form -->
<div class="form-box">
    <form method="post" action = "add_edit_product.php">
        <div class="form-group">
            <label for="product_code">Product Code:</label>
            <input type="text" name="product_code" id="product_code" required>
        </div>

        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>
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
            <label for="product_price">Product Price:</label>
            <input type="text" name="product_price" id="product_price" required>
        </div>

        <div class="form-group">
            <label for="expiry_date">expiry Date:</label>
            <input type="date" name="expiry_date" id="expiry_date" required>
        </div>

        <button type="submit" name="submit_product_edit" class= "submit-buttons">Update Product</button>
    </form>

    
</div>