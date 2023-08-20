<!-- view_customers.php -->
<?php
// db_connection.php (as mentioned in previous responses)

// Retrieve customer information from the database
require_once('config.php');
$sql_select_customers = "SELECT * FROM user";
$result_customers = $conn->query($sql_select_customers);
?>

<!-- Add separate search boxes for each category -->
<div class= "search-group">
<input class = "search-box" type="text" id="nameSearch" onkeyup="searchByName()" placeholder="Search by name">
<input class = "search-box" type="text" id="usernameSearch" onkeyup="searchByUsername()" placeholder="Search by username">
<input class = "search-box" type="text" id="codeSearch" onkeyup="searchByUserCode()" placeholder="Search by customer code">
</div>

<!-- Display a table with customer information -->
<div class="tablebox1">
    <table>
        <tr>
            <th>Customer Name</th>
            <th>Customer UserName</th>
            <th>Customer Code</th>
            <th>Customer Address</th>
            <th>Contact Number</th>
            <th>delete</th>
        </tr>
        <?php while ($row = $result_customers->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['user_code']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><a href="delete_customer.php?user_code=<?php echo $row['user_code']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<!-- Include JavaScript code -->
<!-- Include JavaScript code for name search -->
<script>
    function searchByName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("nameSearch");
        filter = input.value.toUpperCase();
        table = document.querySelector(".tablebox1 table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
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

<!-- Include JavaScript code for username search -->
<script>
    function searchByUsername() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("usernameSearch");
        filter = input.value.toUpperCase();
        table = document.querySelector(".tablebox1 table");
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

<!-- Include JavaScript code for user code search -->
<script>
    function searchByUserCode() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("codeSearch");
        filter = input.value.trim();
        table = document.querySelector(".tablebox1 table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
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





