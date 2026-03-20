<?php
$pageStyles = ['orders/orders_list.css'];
require __DIR__ . '/../layout/header.php';
?>

<?php
// Function to retrieve the price of a product based on the product ID
function getProductPrice($product_id) {
    $conn = Database::getInstance()->getConnection();

    $sql = "SELECT price FROM ims_products WHERE product_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row["price"];
        }
        $stmt->close();
    }
    return 0;
}
?>

<div class="container">
    <h2>Orders</h2>
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search...">
        <button type="button" onclick="filterTable()" class="search-button">Search</button>
    </div>
    <table id="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Customer ID</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch orders from the database
            $conn = Database::getInstance()->getConnection();

            $sql = "SELECT * FROM ims_orders";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["order_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["product_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["customer_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";

                    // Compute the amount based on quantity and price
                    $amount = $row["quantity"] * getProductPrice($row["product_id"]);
                    echo "<td>" . htmlspecialchars($amount) . "</td>";

                    echo "<td>" . htmlspecialchars($row["order_date"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr id='no-results-row'><td colspan='6'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function filterTable() {
        var input = document.getElementById("search-input").value.toUpperCase();
        var table = document.getElementById("orders-table");
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
        headerRow.style.display = showHeader ? "" : "table-row"; // Show/hide the header based on showHeader value
    }
</script>

</body>
</html>
