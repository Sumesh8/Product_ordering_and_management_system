<?php
// Retrieve product information from the database
require_once('config.php');
$sql_select_product = "SELECT * FROM product";
$result_product = $conn->query($sql_select_product);
?>

<!-- Add separate search boxes for each category -->
<div class= "search-group">
<input class = "search-box" type="text" id="nameSearchProduct" onkeyup="searchProductByName()" placeholder="Search by product name">
<input class = "search-box" type="text" id="productCodeSearch" onkeyup="searchByProductCode()" placeholder="Search by product code">

</div>

<!-- Display a table with customer information -->
<div class="tablebox2">
    <table>
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>free Product</th>
            <th>Disscount</th>
            <th>Product Price</th>
            <th>Expiry date</th>
            <th>delete</th>
        </tr>
        <?php while ($row = $result_product->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['product_code']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['free_product']; ?></td>
                <td><?php echo $row['has_discount']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['expiry_date']; ?></td>
                <td><a href="delete_product.php?product_code=<?php echo $row['product_code']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Include JavaScript code -->
<!-- Include JavaScript code for name search -->
<script>
    function searchProductByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("nameSearchProduct");
        filter = input.value.toUpperCase();
        table = document.querySelector(".tablebox2 table");
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
    function searchByProductCode() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("productCodeSearch");
        filter = input.value.trim();
        table = document.querySelector(".tablebox2 table");
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

