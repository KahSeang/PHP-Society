<?php include 'adminheader.php'; ?>
<?php include 'adminsidebar.php'; ?>

<div class="content">
    <div class="payment-details">
        <h2>Payment and Booking Details</h2>

        <table class="payment-table">
            <tr>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Payment Date</th>
                    <th>Quantity</th>
                <th>Event Name</th>
            
                <th>Event Price</th>
                <th>Total Price</th>
            </tr>
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "login";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch payment and booking details from the database
            $sql = "SELECT p.payment_id, p.user_id, p.payment_date, b.booking_id, b.event_id, e.event_name, b.quantity, e.event_price
                    FROM payment p
                    JOIN booking_details b ON p.payment_id = b.booking_id
                    JOIN event e ON b.event_id = e.event_id
                    ORDER BY p.payment_id ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["payment_id"] . "</td>";
                    echo "<td>" . $row["user_id"] . "</td>";
                    echo "<td>" . $row["payment_date"] . "</td>";
                    echo "<td>" . $row["event_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["event_price"] . "</td>";
                    echo "<td>" . ($row["quantity"] * $row["event_price"]) . "</td>"; // Calculate total price
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No payments found</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </table>
    </div>
</div>

<style>
    .payment-table {
        border-collapse: collapse;
        width: 100%;
    }

    .payment-table th, .payment-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .payment-table th {
        background-color: #f2f2f2;
    }

    .payment-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>

