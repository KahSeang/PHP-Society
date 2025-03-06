<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Query to fetch user information
$user_query = "SELECT username, email, gender, address, postcode, city, state FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);

// Fetch user data
if ($user_result) {
    $user_data = mysqli_fetch_assoc($user_result);
} else {
    $user_data = array();
}

// Check if form is submitted for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Update user information
    $update_query = "UPDATE users SET username = '$fullname', email = '$email', address = '$address', postcode = '$postcode', city = '$city', state = '$state' WHERE user_id = $user_id";
    $update_result = mysqli_query($conn, $update_query);

    // Check if update was successful
    if ($update_result) {
        // Redirect to payment page
        header("Location: payment3.php");
        exit;
    } else {
        echo "<script>alert('Failed to update information');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fill Information</title>
<!-- CSS Styles -->
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
}

.container {
    width: 80%;
    max-width: 960px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.input-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.button-container {
    text-align: center;
    padding-top: 10px;
}

.next-btn {
    padding: 10px 30px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px;
    text-decoration: none;
}

.next-btn:hover {
    background-color: #2980b9;
}
</style>
</head>
<body>
        <?php include 'header.php'; ?>

<div class="container">
    <h2>Fill Information</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Hidden input fields for user ID and original email -->
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="original_email" value="<?php echo $user_data['email']; ?>">
        
        <div class="input-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo isset($user_data['username']) ? htmlspecialchars($user_data['username']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo isset($user_data['address']) ? htmlspecialchars($user_data['address']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <label for="postcode">Postcode</label>
            <input type="text" id="postcode" name="postcode" value="<?php echo isset($user_data['postcode']) ? htmlspecialchars($user_data['postcode']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" value="<?php echo isset($user_data['city']) ? htmlspecialchars($user_data['city']) : ''; ?>" required>
        </div>
        <div class="input-group">
            <label for="state">State</label>
            <input type="text" id="state" name="state" value="<?php echo isset($user_data['state']) ? htmlspecialchars($user_data['state']) : ''; ?>" required>
        </div>
      <div class="button-container">
        <button type="submit" class="next-btn">Next</button>
    </div>
    </form>
</div>
</body>
    <?php include 'footer.php'; ?>

</html>
