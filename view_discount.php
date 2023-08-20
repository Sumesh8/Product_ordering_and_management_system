<?php
// Retrieve free issues information from the database
require_once('config.php');
$sql_join_tables = "
    SELECT
        product.product_code,
        product.product_name,
        product.price,
        product.expiry_date,
        product.free_product,
        products_discount.discount_label,
        products_discount.type,
        products_discount.purchase_quantity,
        products_discount.discount,
        products_discount.lower_limit,
        products_discount.upper_limit
    FROM
        product
    INNER JOIN
        products_discount
    ON
        product.product_code = products_discount.product_code
";

// Execute the SQL query
$result_issues = $conn->query($sql_join_tables);
?>

<!-- Add separate search boxes for each category -->
<div class= "search-group">
<input class = "search-box" type="text" id="nameSearchDiscountProduct" onkeyup="searchDiscountByProductName()" placeholder="Search by product name">
<input class = "search-box" type="text" id="discountCodeSearch" onkeyup="searchByDiscountLabel()" placeholder="Search by discount code">
</div>

<!-- Display a table with customer information -->
<div class="tablebox7">
    <table>
        <tr>
            <th>Discount Label</th>
            <th>Product Name</th>
            <th>Discount Product</th>
            <th>Type</th>
            <th>Puchase Quantity</th>
            <th>Discount</th>
            <th>Lower limit</th>
            <th>Upper limit</th>
            <th>delete</th>
        </tr>
        <?php while ($row = $result_issues->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['discount_label']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['free_product']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['purchase_quantity']; ?></td>
                <td><?php echo $row['discount']; ?></td>
                <td><?php echo $row['lower_limit']; ?></td>
                <td><?php echo $row['upper_limit']; ?></td>
                <td><a href="delete_discount.php?discount_label=<?php echo $row['discount_label']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Include JavaScript code -->
<!-- Include JavaScript code for name search -->
<script>
    function searchDiscountByProductName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("nameSearchDiscountProduct");
        filter = input.value.toUpperCase();
        table = document.querySelector(".tablebox7 table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<!-- Include JavaScript code for product code search -->
<script>
    function searchByDiscountLabel() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("discountCodeSearch");
        filter = input.value.trim();
        table = document.querySelector(".tablebox7 table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.trim() === filter) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
