<?php
// Connect to the database
$connection = mysqli_connect('localhost', 'root', '', 'login');

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if event ID is provided
if (isset($_GET['id'])) {
    // Escape event ID to prevent SQL injection
    $event_id = mysqli_real_escape_string($connection, $_GET['id']);

    // SQL query to delete the event
    $sql = "DELETE FROM event WHERE event_id = '$event_id'";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
        // Event deleted successfully
        echo "<script>alert('Event deleted successfully'); window.location.href = 'editevent.php';</script>";
    } else {
        // Error deleting event
        echo "Error deleting event: " . mysqli_error($connection);
    }
} else {
    // Event ID not provided
    echo "Event ID not provided";
}

// Close the database connection
mysqli_close($connection);
?>
