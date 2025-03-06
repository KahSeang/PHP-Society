<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';

// Debug: Check form submission

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Initialize variables
$user_data = [];
$booked_events = [];
$error_message = '';

// Query to fetch user information including payment details
$user_query = "SELECT u.username, u.email, u.address, u.postcode, u.city, u.state
               FROM users u
               WHERE u.user_id = $user_id";

$user_result = mysqli_query($conn, $user_query);

// Fetch user data if the query is successful
if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_data = mysqli_fetch_assoc($user_result);
}

// Query to fetch booked events and payment details
$booking_details_query = "SELECT bd.*, e.event_name, p.* 
                          FROM booking_details bd
                          JOIN payment p ON bd.booking_id = p.payment_id
                          JOIN event e ON bd.event_id = e.event_id
                          WHERE bd.user_id = $user_id";

$booking_details_result = mysqli_query($conn, $booking_details_query);

// Fetch booked events and payment details if the query is successful
if ($booking_details_result && mysqli_num_rows($booking_details_result) > 0) {
    while ($row = mysqli_fetch_assoc($booking_details_result)) {
        $booked_events[] = $row;
    }
}

// Calculate subtotal and total quantity
$sub_total = 0;
$total_quantity = 0;
foreach ($booked_events as $event) {
    $sub_total += $event['price'] * $event['quantity'];
    $total_quantity += $event['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receipt</title>
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

.receipt-details {
    margin-bottom: 20px;
}

.receipt-details p {
    margin: 5px 0;
}

.booked-events h3 {
    margin-top: 20px;
}

.booked-events p {
    margin: 5px 0;
}

.payment-details h3 {
    margin-top: 20px;
}

.payment-details p {
    margin: 5px 0;
}

a {
    text-decoration: none;
    color: #3498db;
}

a:hover {
    color: #2980b9;
}
</style>
</head>
<body>
<div class="container">
    <!-- Display user information -->
    <div class="receipt-details">
        <p><strong>Full Name:</strong> <?php echo isset($user_data['username']) ? $user_data['username'] : ''; ?></p>
        <p><strong>Email:</strong> <?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?></p>
        <p><strong>Address:</strong> <?php echo isset($user_data['address']) ? $user_data['address'] : ''; ?></p>
        <p><strong>Postcode:</strong> <?php echo isset($user_data['postcode']) ? $user_data['postcode'] : ''; ?></p>
        <p><strong>City:</strong> <?php echo isset($user_data['city']) ? $user_data['city'] : ''; ?></p>
        <p><strong>State:</strong> <?php echo isset($user_data['state']) ? $user_data['state'] : ''; ?></p>
    </div>

    <!-- Booked Events -->
    <!-- Display booked events -->
    <div class="booked-events">
        <h3>Booked Events</h3>
        <?php if (!empty($booked_events)) : ?>
            <ul>
                <?php foreach ($booked_events as $event) : ?>
                    <li>
                        <p><strong>Event Name:</strong> <?php echo $event['event_name']; ?></p>
                        <p><strong>Price:</strong> <?php echo $event['price']; ?></p>
                        <p><strong>Quantity:</strong> <?php echo $event['quantity']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Subtotal:</strong> <?php echo $sub_total; ?></p>
            <p><strong>Total Quantity:</strong> <?php echo $total_quantity; ?></p>
        <?php else : ?>
            <p>No booked events found.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
