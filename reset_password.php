<?php
session_start();
require_once 'db_connection.php'; // Include your database connection

if(isset($_GET['token'])){
    $token = $_GET['token'];
    
    $connection = mysqli_connect('localhost','root','','login');
    
    $query = "SELECT * FROM users WHERE reset_token='$token'";
    
    $result = mysqli_query($connection, $query);
    
    if(mysqli_num_rows($result) == 1){ 
        // Token is valid, show password reset form
        $_SESSION['reset_token'] = $token;
    } else {
        // Token is invalid or expired, redirect to forgot_password.php
        header("Location: forgot_password.php");
        exit();
    }
    mysqli_close($connection);
}

if(isset($_POST['submit'])){
    if(isset($_SESSION['reset_token'])){
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if($new_password == $confirm_password){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $connection = mysqli_connect('localhost','root','','login');
            $update_query = "UPDATE users SET password='$hashed_password', reset_token=NULL WHERE reset_token='{$_SESSION['reset_token']}'";
            mysqli_query($connection, $update_query);
            mysqli_close($connection);
            unset($_SESSION['reset_token']);
            $success_message = "Password has been reset successfully.";
        } else {
            $error_message = "Passwords do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Reset Password
                    </div>
                    <div class="card-body">
                        <?php if(isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                        <?php } ?>
                        <?php if(isset($success_message)) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success_message; ?>
                        </div>
                        <?php } ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
