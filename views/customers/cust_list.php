<?php
$pageStyles = ['customers/cust_list.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="container">
    <h2>Customers</h2>
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search...">
        <button type="button" onclick="filterTable()" class="search-button">Search</button>
    </div>
    <table id="customers-table">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact No</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch customers from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM ims_customers";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["cust_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["contact_no"]) . "</td>";
                    echo "<td><a href='/IM-SYSTEM/cust_update?cust_id=" . htmlspecialchars($row["cust_id"]) . "' class='edit-button'>Edit</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr id='no-results-row'><td colspan='5'>No customers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function filterTable() {
        var input = document.getElementById("search-input").value.toUpperCase();
        var table = document.getElementById("customers-table");
        var rows = table.getElementsByTagName("tr");

        var showHeader = false;

        // Show/hide the table rows based on the search input
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            var found = false;

            for (var j = 0; j < cells.length; j++) {
                var cellValue = cells[j].textContent.toUpperCase();

                if (cellValue.indexOf(input) > -1) {
                    found = true;
                    break;
                }
            }

            rows[i].style.display = found ? "" : "none";

            if (found) {
                showHeader = true; // Set showHeader to true if at least one row matches the search
            }
        }

        var headerRow = table.getElementsByTagName("thead")[0].getElementsByTagName("tr")[0];
        headerRow.style.display = showHeader ? "" : "none"; // Show/hide the header based on showHeader value
    }
</script>

</body>
</html>