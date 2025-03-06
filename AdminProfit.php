<!DOCTYPE html>
<html>
<head>
    <title>Profit Summary</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php 
    include 'adminheader.php'; 
    include 'adminsidebar.php'; 

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

    // Fetch profit data from the database
    $profit_data = array();
    $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    foreach ($days as $day) {
        $sql = "SELECT SUM(b.quantity * e.event_price) AS total_profit
                FROM payment p
                JOIN booking_details b ON p.payment_id = b.booking_id
                JOIN event e ON b.event_id = e.event_id
                WHERE DAYNAME(p.payment_date) = '$day'";

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $profit_data[] = isset($row['total_profit']) ? $row['total_profit'] : 0;
    }
    ?>
    <div class="content">
        <h1>Profit Summary</h1>
        <canvas id="profitChart" width="400" height="200"></canvas>
        <table border="1" style="width: 1200px">
            <tr>
                <th>Day</th>
                <th>Profit</th>
            </tr>
            <?php for ($i = 0; $i < count($days); $i++) { ?>
                <tr>
                    <td><?php echo $days[$i]; ?></td>
                    <td><?php echo $profit_data[$i]; ?></td>
                </tr>
            <?php } ?>
        </table>
        <script>
            var ctx = document.getElementById('profitChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo implode("', '", $days); ?>'],
                    datasets: [{
                        label: 'Profit',
                        data: [<?php echo implode(', ', $profit_data); ?>],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
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
</body>
</html>
