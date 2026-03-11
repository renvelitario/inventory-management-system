<?php
$pageStyles = ['purchases/purchases_list.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="container">
    <h2>Purchases</h2>
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search...">
        <button type="button" onclick="filterTable()" class="search-button">Search</button>
    </div>
    <table id="purchases-table">
        <thead>
            <tr>
                <th>Purchase ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch purchases from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM ims_purchases";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["purchase_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["purchase_date"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No purchases found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function filterTable() {
        var input = document.getElementById("search-input").value.toUpperCase();
        var table = document.getElementById("purchases-table");
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