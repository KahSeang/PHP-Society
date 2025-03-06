<?php
session_start();
require_once 'session_check.php'; 

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php'; 
$user_id = $_SESSION['user_id'];

$default_profile_img = "Websystemphp/first/profile.jpg";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['gender']) && isset($_POST['address']) && isset($_POST['postcode']) && isset($_POST['city']) && isset($_POST['state'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $postcode = $_POST['postcode'];
        $city = $_POST['city'];
        $state = $_POST['state'];

        $sql = "UPDATE users SET username=?, email=?, gender=?, address=?, postcode=?, city=?, state=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $username, $email, $gender, $address, $postcode, $city, $state, $user_id);
        $stmt->execute();
        $stmt->close();
    }

     

    header("Location: userprofile.php");
    exit();
}

$sql = "SELECT username, email, user_img, gender, address, postcode, city, state ,softskill FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$user['user_img'] = empty($user['user_img']) ? $default_profile_img : $user['user_img'];

include 'header.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            display: flex;
            justify-content: center;
        }

        .profile-details {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .profile-details img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            object-position: center;
            margin-right: 20px;
        }

        .profile-info {
            color: #333;
        }

        .profile-info h2 {
            margin-top: 0;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .profile-info p {
            margin: 5px 0;
            font-size: 16px;
        }

        .profile-form {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="file"],
        .profile-form select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .profile-form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .profile-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
                      body {
    background-image: url('Websystemphp/10.webp');
    background-size: cover;
    background-position: center;
}       
    </style>
</head>
<body>
<div class="container">
    <div class="profile-details">
        <img src="<?php echo htmlspecialchars($user['user_img']) . '?' . time(); ?>" alt="Profile Image">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Gender: <?php echo htmlspecialchars($user['gender']); ?></p>
            <p>Address: <?php echo htmlspecialchars($user['address']); ?></p>
            <p>City: <?php echo htmlspecialchars($user['city']); ?></p>
            <p>State: <?php echo htmlspecialchars($user['state']); ?></p>
            <p>Postcode: <?php echo htmlspecialchars($user['postcode']); ?></p>
            <p>Soft Skill: <?php echo htmlspecialchars($user['softskill']); ?></p>
            <p>User ID: <?php echo $user_id; ?></p>
        </div>
    </div>
    <!-- Update Profile Form -->
    <div class="profile-form">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        <form action="userprofile.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male" <?php if ($user['gender'] === 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($user['gender'] === 'female') echo 'selected'; ?>>Female</option>
            </select><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required><br>
            <label for="state">State:</label>
            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required><br>
            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" value="<?php echo htmlspecialchars($user['postcode']); ?>" required><br>
            <label for="user_img">Profile Image:</label>
            <input type="file" id="user_img" name="user_img"><br>
            <input type="submit" value="Update Profile">
        </form>
    </div>
</div>
</body>
</html>
<?php include('footer.php'); ?>
