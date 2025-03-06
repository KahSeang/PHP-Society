<?php
// Include header and sidebar
include 'adminheader.php';
include 'adminsidebar.php';

// Establish database connection
$connection = mysqli_connect('localhost', 'root', '', 'login');

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query to retrieve user information
    $query = "SELECT username, email, softskill FROM users WHERE user_id = $user_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $email = $row['email'];
        $soft_skills = $row['softskill'];
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newSoftSkills = $_POST['new_soft_skills'];

    // Update user information
    $updateQuery = "UPDATE users SET username = '$newUsername', email = '$newEmail', softskill = '$newSoftSkills' WHERE user_id = $user_id";

    if (mysqli_query($connection, $updateQuery)) {
        echo "<script>alert('User information updated successfully');</script>";
        // Redirect to user_table.php or any other page after modification
        echo '<meta http-equiv="refresh" content="0;URL=\'watchuser.php?user_id='.$user_id.'\'">';
        exit;
    } else {
        echo "Error updating user information: " . mysqli_error($connection);
    }
}
?>

<div class="content">
    <div class="mb-3">
        <h2>Modify User</h2>
    </div>
    <form method="post" action="modifyuser.php?user_id=<?php echo $user_id; ?>">
        <div class="form-group">
            <label for="new_username">New Username:</label>
            <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $username; ?>" required>
        </div>
        <div class="form-group">
            <label for="new_email">New Email:</label>
            <input type="email" class="form-control" id="new_email" name="new_email" value="<?php echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label for="new_soft_skills">New Soft Skills:</label>
<input type="text" class="form-control" id="new_soft_skills" name="new_soft_skills" value="<?php echo $soft_skills; ?>" pattern="[0-9]+" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include 'admin_footer.php'; ?>
