<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.cart_id, c.user_id, c.event_id, c.quantity, e.type_name, e.event_name, e.event_price 
          FROM cart c 
          INNER JOIN event e ON c.event_id = e.event_id 
          WHERE c.user_id = $user_id";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if query was successful
if (!$result) {
    die("Database query failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chosen Categories</title>
<!-- CSS Styles -->
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
}

.container {
    width: 80%;
    max-width: 960px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.button-container {
    text-align: center;
    padding-top: 10px;
}

.next-btn {
    padding: 10px 30px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px;
    text-decoration: none;
}

.next-btn:hover {
    background-color: #2980b9;
}
</style>
</head>
<body>
    <?php include 'header.php'; ?>
<div class="container">
    <h2>Chosen Categories</h2><br><br>
    <?php
    // Check if there are rows fetched from the database
    if (mysqli_num_rows($result) > 0) {
        $eventCount = 0;
        $subtotal = 0;
        $totalQuantity = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $eventCount++;

            // Calculate subtotal for each item and add to total
            $subtotal += $row['quantity'] * $row['event_price'];
            // Add quantity of current item to total quantity
            $totalQuantity += $row['quantity'];

            // Output the selected category and event information
            ?>
            <div>
                <p>Selected Category: <?php echo htmlspecialchars($row['type_name']); ?></p>
                <p>Selected Event: <?php echo htmlspecialchars($row['event_name']); ?></p>
                <p>Selected Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
            </div>
            <br><br>
            <?php
        }

        // Display a message indicating multiple events
        if ($eventCount > 1) {
            ?>
            <p>There are <?php echo $eventCount; ?> events in your cart.</p><br>
            <?php
        }
    } else {
        // If no rows found, display a message
        ?>
        <p>No items found in your cart.</p>
        <?php
    }
    ?>
    <div>
        <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p><br>
        <p>Total Quantity: <?php echo $totalQuantity; ?></p>
    </div>
    <div class="button-container">
        <a href="payment2.php" class="next-btn">Next</a>
    </div>
</div>
</body>
    <?php include 'footer.php'; ?>

</html>
