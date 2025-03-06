<?php
// Connect to your MySQL database
$connection = mysqli_connect('localhost', 'root', '', 'login');

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process delete action
if (isset($_POST['submit_delete']) && isset($_POST['delete_user_id'])) {
    $deleteUserId = $_POST['delete_user_id'];
    $deleteFavoritesQuery = "DELETE FROM favorites WHERE user_id = '$deleteUserId'";
   if (!mysqli_query($connection, $deleteFavoritesQuery)) {
        echo "Error deleting cart records: " . mysqli_error($connection);
    }
    $deleteCommentQuert = "DELETE FROM comments WHERE user_id ='$deleteUserId'";
    if (!mysqli_query($connection, $deleteCommentQuert)) {
        echo "Error deleting comments records: " . mysqli_error($connection);
    }
        
     $deleteBookingDetailsQuery = "DELETE FROM booking_details WHERE user_id = '$deleteUserId'";
    if (!mysqli_query($connection, $deleteBookingDetailsQuery)) {
        echo "Error deleting booking details records: " . mysqli_error($connection);
    }
    // Delete related records from the cart table first
    $deleteCartQuery = "DELETE FROM cart WHERE user_id = '$deleteUserId'";
    if (!mysqli_query($connection, $deleteCartQuery)) {
        echo "Error deleting cart records: " . mysqli_error($connection);
    }

    // Proceed with deleting the user record
    $deleteUserQuery = "DELETE FROM users WHERE user_id = '$deleteUserId'";
    if (mysqli_query($connection, $deleteUserQuery)) {
        // User deleted successfully
        echo "<script>alert('User deleted successfully');</script>";
        // Redirect to watchuser.php or any other page after deletion
        header("Location: watchuser.php");
        exit();
    } else {
        echo "Error deleting user record: " . mysqli_error($connection);
    }
}

// Query to select user data
$query = "SELECT user_id, username, email ,softskill FROM users";

// Execute query
$result = mysqli_query($connection, $query);
?>

<!-- user_table.php -->
<?php include 'adminheader.php'; ?>
<?php include 'adminsidebar.php'; ?>

<div class="content">
    <div class="mb-3">
        <a href="register.php" class="btn btn-success">Add New User</a>
        <button class="btn btn-primary ml-2" onclick="location.reload();">Refresh Table</button>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Soft Skill</th>
                <th>Action</th> <!-- New column for action buttons -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if query was successful
            if ($result) {
                // Fetch data and display it in table rows
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" .$row['softskill']."</td>";
                    // Add action buttons for modify and delete
                    echo "<td>";
                    echo "<a href='modifyuser.php?user_id=" . $row['user_id'] . "' class='btn btn-primary btn-sm'>Modify</a>"; // Modify button
                    ?>
                    <form method='post' action='watchuser.php' onsubmit="return confirm('Are you sure you want to delete this user?')">
                        <input type='hidden' name='delete_user_id' value='<?= $row['user_id'] ?>'>
                        <button type='submit' name='submit_delete' class='btn btn-danger btn-sm ml-2 rounded-pill'>Delete</button> <!-- Submit button for delete -->
                    </form>
                    <?php
                    echo "</td>";
                    echo "</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($connection);
            }

            // Close connection
            mysqli_close($connection);
            ?>
        </tbody>
    </table>
</div>
<style>
    .btn {
        margin-bottom: 10px; /* Adjust vertical spacing between buttons */
    }
  
</style>
