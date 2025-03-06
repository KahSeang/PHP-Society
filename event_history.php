<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';

// Check if the user is logged in
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
$error_message = '';

$event_query = "SELECT e.event_name, e.type_name, e.start_time, e.end_time, e.event_location, p.payment_date, b.price, b.quantity, e.event_img, b.booking_id
                FROM event e
                INNER JOIN booking_details b ON e.event_id = b.event_id
                INNER JOIN payment p ON b.booking_id = p.payment_id
                WHERE b.user_id = ?";

if(isset($_GET['payment_date'])) {
    $event_query .= " AND DATE(p.payment_date) = ?";
    $payment_date = $_GET['payment_date'];
}

$event_query .= " ORDER BY p.payment_date DESC";

$event_stmt = mysqli_prepare($conn, $event_query);

if(isset($payment_date)) {
    mysqli_stmt_bind_param($event_stmt, 'is', $user_id, $payment_date);
} else {
    mysqli_stmt_bind_param($event_stmt, 'i', $user_id);
}

mysqli_stmt_execute($event_stmt);
$event_result = mysqli_stmt_get_result($event_stmt);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('Websystemphp/10.webp');
            background-size: cover;
            background-position: center;
            color: #333;
        }

        .container {
            max-width: 1300px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .filter-container {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            align-items: center;
        }

        label {
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #555;
        }

        .event-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .event-table th,
        .event-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .event-table th {
            background-color: #f2f2f2;
        }

        .event-img {
            width: 350px;
            height: 250px;
            float: left;
            margin-right: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .no-events {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        td {
          
    font-family: 'Open Sans', sans-serif;

        }
    </style>
</head>
<body>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">

<?php include('header.php'); ?>
<div class="container">
    <div class="filter-container">
        <form method="GET" action="">
            <label for="payment_date">Payment Date:</label>
            <input type="date" id="payment_date" name="payment_date">
            <button type="submit">Filter</button>
        </form>
    </div>

    <?php if ($event_result && mysqli_num_rows($event_result) > 0) : ?>
        <table class="event-table">
            <tr>
                <th>Event</th>
                <th>Event Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Location</th>
                <th>Booked Time</th>
                <th>Booked Price</th>
                <th>Booked Quantity</th>
            </tr>
            <?php while ($event_data = mysqli_fetch_assoc($event_result)) : ?>
                <tr>
                    <td>

                        <img src="<?php echo $event_data['event_img']; ?>" alt="Event Photo" class="event-img">
                    </td>
                    <td><?php echo $event_data['event_name']; ?></td>
                    <td><?php echo $event_data['start_time']; ?></td>
                    <td><?php echo $event_data['end_time']; ?></td>
                    <td><?php echo $event_data['event_location']; ?></td>
                    <td><?php echo $event_data['payment_date']; ?></td>
                    <td>$<?php echo $event_data['price'] * $event_data['quantity']; ?></td>
                    <td><?php echo $event_data['quantity']; ?> Tickets</td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p class="no-events">No event details found.</p>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>

</body>
</html>
