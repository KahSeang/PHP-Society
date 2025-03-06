<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['favorite_id'])) {
    $favorite_id = $_POST['favorite_id'];

    $sql = "DELETE FROM favorites WHERE user_id = ? AND favorite_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $favorite_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Event removed from favorites successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch favorite events for the logged-in user
$sql = "SELECT event.*, favorites.favorite_id FROM event JOIN favorites ON event.event_id = favorites.event_id WHERE favorites.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
    background-image: url('Websystemphp/10.webp');
      background-size: cover;
    background-position: center;
            margin: 0;
        }
  
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
    background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .event {
            display: flex;
            margin-bottom: 20px;
            padding: 20px;
    background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            
        }
        
        .event-img {
            width: 30%;
            margin-right: 20px;
        }
        
        .event-img img {
            width: 100%;
            border-radius: 5px;
        }
        
        .event-details {
            width: 70%;
        }
        
        .event-details h2 {
            color: #333;
            margin-top: 0;
        }
        
        .event-details p {
            color: #666;
            line-height: 1.6;
        }
        
        .delete-btn {
            background-color: #ff6347;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .event-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }

        .event {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .event:hover {
            transform: translateY(-5px);
        }

        .event-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .event-details {
            padding: 20px;
        }

        .event-details h2 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .event-details p {
            margin: 0;
            color: #666;
        }

        .delete-btn {
            background-color: #ff6347;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #ff4838;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>
    <br><br>
<div class="container">
        
    <h1>Your Favorite Events</h1>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<br><br>';
            echo "<div class='event'>";
            echo "<div class='event-img'><img src='" . htmlspecialchars($row['event_img']) . "' alt='Event Image'></div>";
            echo "<div class='event-details'>";
            echo "<h2>" . htmlspecialchars($row['event_name']) . "</h2>";
            echo "<p>" . htmlspecialchars($row['type_name']) . "</p>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='favorite_id' value='" . $row['favorite_id'] . "'>";
            echo "<button type='submit' class='delete-btn'>Remove from Favorites</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>You have no favorite events.</p>";
    }
    ?>
</div>
    <br><br><br><br>
<?php include('footer.php'); ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
