<?php
ob_start(); // Start output buffering
?>
<!-- user_table.php -->
<?php include 'adminheader.php'; ?>
<?php include 'adminsidebar.php'; ?>

<div class="content">
    <div class="mb-3">
        <a href="registerAdmin.php" class="btn btn-success">Add New User</a>
        <button class="btn btn-primary ml-2" onclick="location.reload();">Refresh Table</button>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th> <!-- New column for action buttons -->
            </tr>
        </thead>
        <tbody>
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

                // Delete user from the database
                $deleteQuery = "DELETE FROM admin WHERE admin_id = '$deleteUserId'";
                if (mysqli_query($connection, $deleteQuery)) {
                    echo '<script>alert("Delete successfully"); window.location.href = "watchadmin.php";</script>';
                } else {
                    echo "Error deleting user record: " . mysqli_error($connection);
                }
            }

            // Query to select user data
            $query = "SELECT admin_id, username, email FROM admin";

            // Execute query
            $result = mysqli_query($connection, $query);

            // Check if query was successful
            if ($result) {
                // Fetch data and display it in table rows
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['admin_id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    // Add action buttons for modify and delete
                    echo "<td>";
                    echo "<a href='modifyadmin.php?user_id=" . $row['admin_id'] . "' class='btn btn-primary btn-sm'>Modify</a>"; // Modify button
                    ?>
                    <form method='post' action=''>
                        <input type='hidden' name='delete_user_id' value='<?= $row['admin_id'] ?>'>
                        <button type='submit' name='submit_delete' class='btn btn-danger btn-sm ml-2' onclick="return confirmDelete()">Delete</button> <!-- Submit button for delete -->
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

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this user?");
    }
</script>

<?php include 'admin_footer.php'; ?>
<?php
ob_end_flush(); // Flush the output buffer and send the output to the browser
?>