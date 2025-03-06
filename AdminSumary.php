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

// Calculate total profit
$sqlProfit = "SELECT SUM(b.quantity * e.event_price) AS total_profit
              FROM booking_details b
              JOIN event e ON b.event_id = e.event_id";
$resultProfit = $conn->query($sqlProfit);
$rowProfit = $resultProfit->fetch_assoc();
$totalProfit = $rowProfit['total_profit'];

// Calculate total booking count
$sqlBookingCount = "SELECT COUNT(*) AS total_bookings FROM booking_details";
$resultBookingCount = $conn->query($sqlBookingCount);
$rowBookingCount = $resultBookingCount->fetch_assoc();
$totalBookings = $rowBookingCount['total_bookings'];

// Calculate total event count
$sqlEventCount = "SELECT COUNT(*) AS total_events FROM event";
$resultEventCount = $conn->query($sqlEventCount);
$rowEventCount = $resultEventCount->fetch_assoc();
$totalEvents = $rowEventCount['total_events'];

// Retrieve top favorite events
$sqlTopFavorites = "SELECT e.event_id, e.event_name, COUNT(f.event_id) AS favorite_count
                   FROM event e
                   LEFT JOIN favorites f ON e.event_id = f.event_id
                   GROUP BY e.event_id
                   ORDER BY favorite_count DESC
                   LIMIT 5";
$resultTopFavorites = $conn->query($sqlTopFavorites);

// Retrieve top booking events
$sqlTopBookings = "SELECT e.event_id, e.event_name, COUNT(b.event_id) AS booking_count
                   FROM event e
                   LEFT JOIN booking_details b ON e.event_id = b.event_id
                   GROUP BY e.event_id
                   ORDER BY booking_count DESC
                   LIMIT 5";
$resultTopBookings = $conn->query($sqlTopBookings);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profit Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
        }

        .content {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-top: 0;
        }

        p {
            font-size: 18px;
            color: #666;
            margin-bottom: 15px;
        }

        .summary-info {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .info-box {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            flex: 1;
            margin: 0 10px;
        }

        .info-box h2 {
            font-size: 24px;
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 18px;
            color: #666;
            margin: 0;
        }

        .favorite-events,
        .top-bookings {
            margin-top: 30px;
        }

        .favorite-event,
        .top-booking {
            margin-bottom: 10px;
        }

        .event-name {
            font-weight: bold;
        }

        .favorite-count,
        .booking-count {
            color: #888;
        }
    </style>
</head>
<body>
    <!-- Header and Sidebar -->
    <?php include 'adminheader.php'; ?>
    <?php include 'adminsidebar.php'; ?>

    <!-- Content -->
    <div class="container">
        <div class="content">
            <h1>Profit Summary</h1>
            <div class="summary-info">
                <div class="info-box">
                    <h2>Total Profit</h2>
                    <p>$<?php echo $totalProfit; ?></p>
                </div>
                <div class="info-box">
                    <h2>Total Bookings</h2>
                    <p><?php echo $totalBookings; ?></p>
                </div>
                <div class="info-box">
                    <h2>Total Events</h2>
                    <p><?php echo $totalEvents; ?></p>
                </div>
            </div>

            <!-- Top Favorite Events -->
            <div class="favorite-events">
                <h2>Top Favorite Events</h2>
                <?php
                if ($resultTopFavorites->num_rows > 0) {
                    while($row = $resultTopFavorites->fetch_assoc()) {
                        echo "<div class='favorite-event'>";
                        echo "<p class='event-name'>" . $row["event_name"] . "</p>";
                        echo "<p class='favorite-count'>Favorited " . $row["favorite_count"] . " times</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No favorite events found</p>";
                }
                ?>
            </div>

            <!-- Top Booking Events -->
            <div class="top-bookings">
                <h2>Top Booking Events</h2>
                <?php
                if ($resultTopBookings->num_rows > 0) {
                    while($row = $resultTopBookings->fetch_assoc()) {
                        echo "<div class='top-booking'>";
                        echo "<p class='event-name'>" . $row["event_name"] . "</p>";
                        echo "<p class='booking-count'>Booked " . $row["booking_count"] . " times</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No booking events found</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
