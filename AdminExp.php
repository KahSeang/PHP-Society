<!DOCTYPE html>
<html>
<head>
    <title>Exception Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header, footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .exception {
            color: red;
            font-weight: bold;
        }

        aside {
            width: 250px;
            background-color: #f7f7f7;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php 
    // Include header
    include 'adminheader.php'; 
    
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

    // Query to find the event with the least bookings
    $least_booked_event_query = "SELECT event_name, COUNT(*) AS bookings FROM booking_details JOIN event ON booking_details.event_id = event.event_id GROUP BY event_name ORDER BY bookings ASC LIMIT 1";
    $result_least_booked_event = $conn->query($least_booked_event_query);
    $row_least_booked_event = $result_least_booked_event->fetch_assoc();
    $least_booked_event_name = $row_least_booked_event["event_name"];
    $least_booked_event_bookings = $row_least_booked_event["bookings"];

    // Query to find the event with the least availability
    $least_available_event_query = "SELECT event_name, event.event_available AS availability FROM event LEFT JOIN booking_details ON event.event_id = booking_details.event_id GROUP BY event.event_id ORDER BY availability ASC LIMIT 1";
    $result_least_available_event = $conn->query($least_available_event_query);
    $row_least_available_event = $result_least_available_event->fetch_assoc();
    $least_available_event_name = $row_least_available_event["event_name"];
    $least_available_event_availability = $row_least_available_event["availability"];
    ?>

   <div class="content">
    <h1>Exception Report</h1>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Bookings</th>
            <th>Available Seats</th>
            <th>Exception Reason</th>
        </tr>
        <?php
        // Query to find all events and their booking counts and available seats
        $events_query = "SELECT event_name, COUNT(booking_id) AS bookings, event_available FROM event LEFT JOIN booking_details ON event.event_id = booking_details.event_id GROUP BY event.event_id";
        $result_events = $conn->query($events_query);

        // Loop through each event
        while ($row_event = $result_events->fetch_assoc()) {
            $event_name = $row_event["event_name"];
            $event_bookings = $row_event["bookings"];
            $event_available = $row_event["event_available"];
            $exception_reason = "";

            // Check for exceptions
            if ($event_bookings < 5) {
                $exception_reason = "Less than 5 bookings";
            }
            if ($event_available < 10) {
                if (!empty($exception_reason)) {
                    $exception_reason .= ", ";
                }
                $exception_reason .= "Less than 10 available seats";
            }

            // Display the event if there's an exception
            if (!empty($exception_reason)) {
                ?>
                <tr>
                    <td><?php echo $event_name; ?></td>
                    <td><?php echo $event_bookings; ?></td>
                    <td><?php echo $event_available; ?></td>
                    <td class="exception"><?php echo $exception_reason; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>

    <?php 
    // Include sidebar
    include 'adminsidebar.php'; 
    
    // Close connection
    $conn->close();
    ?>
</body>
</html>
