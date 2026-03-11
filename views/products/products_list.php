<?php
$pageStyles = ['products/products_list.css'];
require __DIR__ . '/../layout/header.php';
?>

<div class="container">
    <h2>Products</h2>
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search...">
        <button type="button" onclick="filterTable()" class="search-button">Search</button>
    </div>
    <table id="products-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch products from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM ims_products";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $isInactive = strtolower(trim((string) $row["status"])) === "inactive";
                    $rowClass = $isInactive ? " class='inactive-row'" : "";
                    echo "<tr" . $rowClass . ">";
                    echo "<td>" . htmlspecialchars($row["product_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "<td><button class='edit-button' onclick='location.href=\"/IM-SYSTEM/products_update?product_id=" . htmlspecialchars($row["product_id"]) . "\"'>Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function filterTable() {
        var input = document.getElementById("search-input").value.toUpperCase();
        var table = document.getElementById("products-table");
        var rows = table.getElementsByTagName("tr");

        var showHeader = false;

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

            if (found) {
                rows[i].style.display = "";
                showHeader = true; // Set showHeader to true if at least one row matches the search
            } else {
                rows[i].style.display = "none";
            }
        }

        var headerRow = table.getElementsByTagName("thead")[0].getElementsByTagName("tr")[0];
        headerRow.style.display = showHeader ? "" : "none"; // Show/hide the header based on showHeader value
    }
</script>

</body>
</html>