<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Add margin reset to the body */
        }

        table {
            width: calc(100% - 250px); /* Adjust table width to accommodate sidebar */
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 250px; /* Ensure proper spacing for the table */
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        .content-wrapper {
            margin: 20px; /* Adjust margins as needed */
        }
    </style>
</head>
<body>
    
<?php include 'adminheader.php'; ?>
<?php include 'adminsidebar.php'; ?>
<!-- Content -->

<div class="content-wrapper"> <!-- Wrap the content in a div -->
    <?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "login";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Check if the delete button is clicked
    if(isset($_POST['delete_feedback'])){
        // Get the feedback ID to be deleted
        $feedback_id = $_POST['feedback_id'];

        // Delete the feedback from the database
        $sql_delete = "DELETE FROM feedback WHERE feedback_id = $feedback_id";
        if ($connection->query($sql_delete) === TRUE) {
            echo '<script>alert("Feedback deleted successfully");</script>';
        } else {
            echo '<script>alert("Error deleting record: ' . $connection->error . '");</script>';
        }
    }

    // Fetch data from the feedback table
    $sql_select = "SELECT feedback_id, subject, description FROM feedback";
    $result = $connection->query($sql_select);

    if ($result->num_rows > 0) {
        // Output table header
        echo "<table>";
        echo "<tr><th>ID</th><th>Subject</th><th>Description</th><th>Action</th></tr>";

        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["feedback_id"] . "</td>";
            echo "<td>" . $row["subject"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>
                    <form method='post'>
                        <input type='hidden' name='feedback_id' value='" . $row["feedback_id"] . "'>
                        <button type='submit' name='delete_feedback'>Delete</button>
                    </form>
                  </td>"; // Add delete button
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No feedback found.</p>";
    }
    ?>
</div> <!-- Close the content-wrapper div -->

</body>
</html>
