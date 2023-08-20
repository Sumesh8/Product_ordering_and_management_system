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
        define_free_issues.free_issues_label,
        define_free_issues.type,
        define_free_issues.purchase_quantity,
        define_free_issues.free_quantity,
        define_free_issues.lower_limit,
        define_free_issues.upper_limit
    FROM
        product
    INNER JOIN
        define_free_issues
    ON
        product.product_code = define_free_issues.product_code
";

// Execute the SQL query
$result_issues = $conn->query($sql_join_tables);
?>

<!-- Add separate search boxes for each category -->
<div class= "search-group">
<input class = "search-box" type="text" id="nameSearchdefineProduct" onkeyup="searchDefineByProductName()" placeholder="Search by product name">
<input class = "search-box" type="text" id="defineCodeSearch" onkeyup="searchByfreeIssueLabel()" placeholder="Search by free isssue code">
</div>

<!-- Display a table with customer information -->
<div class="tablebox3">
    <table>
        <tr>
            <th>Free Issue Label</th>
            <th>Product Name</th>
            <th>free Product</th>
            <th>Type</th>
            <th>Puchase Quantity</th>
            <th>Free Quantity</th>
            <th>Lower limit</th>
            <th>Upper limit</th>
            <th>delete</th>
        </tr>
        <?php while ($row = $result_issues->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['free_issues_label']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['free_product']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['purchase_quantity']; ?></td>
                <td><?php echo $row['free_quantity']; ?></td>
                <td><?php echo $row['lower_limit']; ?></td>
                <td><?php echo $row['upper_limit']; ?></td>
                <td><a href="delete_issues.php?free_issues_label=<?php echo $row['free_issues_label']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Include JavaScript code -->
<!-- Include JavaScript code for name search -->
<script>
    function searchDefineByProductName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("nameSearchdefineProduct");
        filter = input.value.toUpperCase();
        table = document.querySelector(".tablebox3 table");
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
    function searchByfreeIssueLabel() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("defineCodeSearch");
        filter = input.value.trim();
        table = document.querySelector(".tablebox3 table");
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
