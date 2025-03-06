<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:#f0f0f0;
        }

        .cart-item {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            display: flex;
            align-items: center;
            border: 1px solid black;
            border-radius: 10px;
            margin: 20px 20px;
            border: none;
            box-shadow: 2px 3px 10px 10px rgba(0, 0, 0, 0.1); 
        }

        .cart-item img {
            max-width: 200px;
            max-height: 200px;
            margin: 20px;
            border-radius: 10px;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 20px;
        }

        .cart-item-details h3,
        .cart-item-details p {
            margin: 0;
            padding: 5px 10px;
        }

        .cart-item-details p {
            margin-bottom: 5px;
        }

        .quantity-btns {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .quantity-btns button {
            border: none;
            background-color: transparent;
            cursor: pointer;
            font-size: 1.2em;
            padding: 5px 8px;
            margin-right: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .quantity-btns button:hover {
            background-color: #f0f0f0;
        }

        .checkout-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #999999;
    color: black;
    border: none;
    cursor: pointer;
    border-radius: 10px;
    margin-left: 88.5%; /* Align to the right */
}


        .subtotal {
            margin-top: 40px;
            text-align: right;
            margin-right: 20px;
        }

        .total {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            margin-right: 20px;
        }
        
        h2{
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="cart">
        <?php

        include 'header.php';
        echo '    <h2>Shopping Cart</h2><br>';
        $dbHost = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName = 'login';

        $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $userId = $_SESSION['user_id'] ?? 0; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'], $_POST['quantity'])) {
            $eventId = $_POST['event_id'];
            $quantityChange = ($_POST['quantity'] === '+') ? 1 : -1;

            $availableQuantityQuery = "SELECT event_available FROM event WHERE event_id = $eventId";
            $availableQuantityResult = $conn->query($availableQuantityQuery);
            if ($availableQuantityResult->num_rows > 0) {
                $availableQuantityRow = $availableQuantityResult->fetch_assoc();
                $availableQuantity = $availableQuantityRow['event_available'];

                if ($quantityChange > 0 && $availableQuantity <= 0) {
                    echo "<script>alert('Cannot add more. Quantity exceeds available quantity.');</script>";
                } else {
                    $updateQuery = "UPDATE cart SET quantity = quantity + $quantityChange WHERE user_id = $userId AND event_id = $eventId";
                    if ($conn->query($updateQuery) === TRUE) {
                        $checkQuantityQuery = "SELECT quantity FROM cart WHERE user_id = $userId AND event_id = $eventId";
                        $checkResult = $conn->query($checkQuantityQuery);
                        if ($checkResult->num_rows > 0) {
                            $quantityRow = $checkResult->fetch_assoc();
                            if ($quantityRow['quantity'] <= 0) {
                                $deleteQuery = "DELETE FROM cart WHERE user_id = $userId AND event_id = $eventId";
                                if ($conn->query($deleteQuery) === TRUE) {
                                } else {
                                    echo "Error deleting item: " . $conn->error;
                                }
                            }
                        } else {
                            echo "Error checking quantity: " . $conn->error;
                        }
                    } else {
                        echo "Error updating quantity: " . $conn->error;
                    }
                }
            } else {
                echo "Error retrieving available quantity: " . $conn->error;
            }
        }

        // Display cart items
        $cartQuery = "SELECT e.event_id, e.event_name, e.event_price, e.event_img, c.quantity, e.event_available FROM cart c JOIN event e ON c.event_id = e.event_id WHERE c.user_id = ?";
        if ($stmt = $conn->prepare($cartQuery)) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                echo "<p>Your cart is empty.</p>";
            } else {
                $subtotal = 0;
                $canProceed = true;
                while ($row = $result->fetch_assoc()) {
                    $itemTotal = $row['event_price'] * $row['quantity'];
                    $subtotal += $itemTotal;
                    if ($row['quantity'] > $row['event_available']) {
                        $canProceed = false; 
                    }
                    echo "<div class='cart-item'>";
                    echo "<img src='" . htmlspecialchars($row['event_img']) . "' alt='Event Image'>";
                    echo "<div class='cart-item-details'>";
                    echo "<h3>" . htmlspecialchars($row['event_name']) . "</h3>";
                    echo "<p>Price: $" . htmlspecialchars($row['event_price']) . "</p>";
                    echo "<p>Quantity: {$row['quantity']}</p>";
                    echo "<p>Total: $" . number_format($itemTotal, 2) . "</p>";
                    echo "<div class='quantity-btns'>";
                    echo "<form action='{$_SERVER["PHP_SELF"]}' method='post'>";
                    echo "<input type='hidden' name='event_id' value='{$row['event_id']}'>";
                    echo "<button type='submit' name='quantity' value='-'>-</button>";
                    echo "</form>";
                    echo "<form action='{$_SERVER["PHP_SELF"]}' method='post'>";
                    echo "<input type='hidden' name='event_id' value='{$row['event_id']}'>";
                    echo "<button type='submit' name='quantity' value='+'>+</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "<div class='subtotal'>Subtotal: $" . number_format($subtotal, 2) . "</div>";
                echo "<div class='total'>Total: $" . number_format($subtotal, 2) . "</div>";

                if ($canProceed) {
                    echo "<form action='payment1.php' method='post'>";
                    echo "<input type='hidden' name='user_id' value='{$userId}'>";
                    echo "<button type='submit' class='checkout-btn'>Proceed to Payment</button>";
                    echo "</form>";
                } else {
                    echo "<p style='color: red;'>Cannot proceed to payment. Quantity exceeds available quantity.</p>";
                }
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . htmlspecialchars($conn->error);
        }

        $conn->close();
        ?>
    </div><br><br><br><br><br><br><br><br><br><br>
    <?php        include 'footer.php'; ?>
</body>
</html>