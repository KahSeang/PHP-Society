<?php 
// Include the database connection file
require_once 'db_connection.php';

// Query to get the count of events in each category, excluding category ID 1
$query = "SELECT category.category_name, COUNT(event.event_id) AS event_count 
          FROM category 
          LEFT JOIN event ON category.category_id = event.category_id 
          WHERE category.category_id != 1
          GROUP BY category.category_id 
          ORDER BY event_count DESC";

// Execute the query
$result = mysqli_query($conn, $query);

// Create arrays to store category names and their corresponding event counts
$categories = array();
$eventCounts = array();

// Fetch data from the result set
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['category_name'];
    $eventCounts[] = $row['event_count'];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'adminheader.php'; ?>
    <?php include 'adminsidebar.php'; ?>
    
    <div class="content">
        <h2>Event Analysis by Category</h2>

        <!-- Display the bar chart -->
        <canvas id="eventChart" width="400" height="200"></canvas>

        <script>
            var ctx = document.getElementById('eventChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($categories); ?>,
                    datasets: [{
                        label: 'Event Count',
                        data: <?php echo json_encode($eventCounts); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

    <?php include 'admin_footer.php'; ?>
</body>
</html>
