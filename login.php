<?php
session_start();
require_once 'db_connection.php'; // Include your database connection

if (isset($_POST['submit'])) {
    $email = $_POST['username_or_email'];
    $password = $_POST['password'];
    $connection = mysqli_connect('localhost', 'root', '', 'login');

    $user_query = "SELECT * FROM users WHERE email='$email'";
    $admin_query = "SELECT * FROM admin WHERE email='$email'";

    $user_result = mysqli_query($connection, $user_query);
    $admin_result = mysqli_query($connection, $admin_query);

    $current_time = date("Y-m-d H:i:s");
    $lockout_time = 15; // Lockout time in minutes

    if (mysqli_num_rows($user_result) == 1) {
        $row = mysqli_fetch_assoc($user_result);
        $last_attempt_time = strtotime($row['last_attempt_time']);
        $time_difference = (strtotime($current_time) - $last_attempt_time) / 60; // in minutes

        if ($row['failed_attempts'] >= 3 && $time_difference < $lockout_time) {
            $error_message = "Your account is locked. Please try again after " . ($lockout_time - $time_difference) . " minutes.";
        } else {
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $row['email'];

                // Reset failed attempts
                $reset_attempts_query = "UPDATE users SET failed_attempts = 0, last_attempt_time = NULL WHERE email='$email'";
                mysqli_query($connection, $reset_attempts_query);

                header("Location: home.php?user_id=" . $row['user_id']);
                exit();
            } else {
                $new_attempts = $row['failed_attempts'] + 1;
                $update_attempts_query = "UPDATE users SET failed_attempts = $new_attempts, last_attempt_time='$current_time' WHERE email='$email'";
                mysqli_query($connection, $update_attempts_query);

                $error_message = "Invalid password.";
            }
        }
    } elseif (mysqli_num_rows($admin_result) == 1) {
        $row = mysqli_fetch_assoc($admin_result);
        $last_attempt_time = strtotime($row['last_attempt_time']);
        $time_difference = (strtotime($current_time) - $last_attempt_time) / 60;

        if ($row['failed_attempts'] >= 3 && $time_difference < $lockout_time) {
            $error_message = "Your account is locked. Please try again after " . ($lockout_time - $time_difference) . " minutes.";
        } else {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_username'] = $row['username'];

                // Reset failed attempts
                $reset_attempts_query = "UPDATE admin SET failed_attempts = 0, last_attempt_time = NULL WHERE email='$email'";
                mysqli_query($connection, $reset_attempts_query);

                header("Location: admin.php?username=" . urlencode($row['username']));
                exit();
            } else {
                $new_attempts = $row['failed_attempts'] + 1;
                $update_attempts_query = "UPDATE admin SET failed_attempts = $new_attempts, last_attempt_time='$current_time' WHERE email='$email'";
                mysqli_query($connection, $update_attempts_query);

                $error_message = "Invalid password.";
            }
        }
    } else {
        $error_message = "User not found.";
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('Websystemphp/10.webp');
            background-position: center;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: grey;
            color: white;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
        }

        .card-body {
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-link {
            color: #4CAF50;
        }

        .btn-link:hover {
            text-decoration: none;
            color: #403f3b;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 style="text-align:center;color : whitesmoke;"></h1>
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body" style="background-color: rgba(255, 255, 255, 0.6); border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>

                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username_or_email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="username_or_email" name="username_or_email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit" style="background-color: grey;">Submit</button>
                            <a href="forgot_password.php" class="btn btn-link" style="color: grey;">Forgot Password?</a>
                            <a href="register.php" class="btn btn-link" style="color: grey;">User Register</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        //jquery
        $(document).ready(function() {
            // Focus on the username/email input field when the page loads
            $("#username_or_email").focus();
        });
    </script>
</body>

</html>
