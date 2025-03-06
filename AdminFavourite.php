<!DOCTYPE html>
<html>
<head>
    <title>Favorite Events Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .content {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
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

// Fetch all events from the event table
$sql = "SELECT event_id, event_name FROM event";
$result = $conn->query($sql);

// Initialize favorite events array
$favoriteEvents = array();

// Process query result
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Fetch the count of favorites for each event
        $event_id = $row["event_id"];
        $event_name = $row["event_name"];
        $sql_favorites = "SELECT COUNT(user_id) AS num_favorites FROM favorites WHERE event_id = $event_id";
        $result_favorites = $conn->query($sql_favorites);
        $row_favorites = $result_favorites->fetch_assoc();
        $num_favorites = $row_favorites["num_favorites"];

        // Store event name and number of favorites in the array
        $favoriteEvents[$event_name] = $num_favorites;
    }
}

// Close connection
$conn->close();
?>

    <?php include 'adminheader.php'; ?>
    <?php include 'adminsidebar.php'; ?>
    
    <div class="content">
        <h1>Favorite Events Report</h1>
        <canvas id="favoriteEventsChart" width="400" height="200"></canvas>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Number of Favorites</th>
            </tr>
            <?php foreach ($favoriteEvents as $event_name => $num_favorites) { ?>
                <tr>
                    <td><?php echo $event_name; ?></td>
                    <td><?php echo $num_favorites; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        // PHP array to JavaScript conversion
        var favoriteEvents = <?php echo json_encode($favoriteEvents); ?>;

        // Get canvas element
        var ctx = document.getElementById('favoriteEventsChart').getContext('2d');

        // Convert favoriteEvents object to arrays for chart.js
        var eventNames = Object.keys(favoriteEvents);
        var eventCounts = Object.values(favoriteEvents);

        // Create pie chart
        var favoriteEventsChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: eventNames,
                datasets: [{
                    label: 'Number of Favorites',
                    data: eventCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
