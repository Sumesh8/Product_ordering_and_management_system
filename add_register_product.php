<?php include 'admindashboard.php'; ?>

<div class="error-message">
            <?php
            require_once('config.php');
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_product_register"])) {
                $product_code = $_POST["product_code"];
                $product_name = $_POST["product_name"];
                $free_product = $_POST["free_product"];
                $has_discount = $_POST["has_discount"];
                $product_price = $_POST["product_price"];
                $product_expiry_date = $_POST["expiry_date"];

                // Check if the product already exists in the database
                $sql_check_product = "SELECT * FROM product WHERE product_name = '$product_name'";
                $result_check_product = $conn->query($sql_check_product);
                if ($result_check_product->num_rows > 0) {
                    $Message = "Error: Product already exists. Please choose a different product.";
     
                
                }

                
                elseif (!is_numeric($product_price)) {
                    $Message = "Error: Invalid Product Price.";
    
                }

                else{
                // Insert the product data into the user table
                $sql_insert_product = "INSERT INTO product (product_code, product_name, price, expiry_date,free_product, has_discount)
                            VALUES ('$product_code', '$product_name', '$product_price', '$product_expiry_date', '$free_product' , '$has_discount')";
                            
                if ($conn->query($sql_insert_product) === TRUE) {
                    $Message = "Product registration successful!";
                }else{
                    $Message = "Error: " . $sql_insert_product . "<br>" . $conn->error;
                }
            }
            }
        
            $conn->close();
    echo $Message;
    echo "<div class = \"ok-button\">";
    echo "<td><a href=\"admindashboard.php#product-registration\">   OK</a></td>";
    echo "</div>";
            ?>
        </div>
        




