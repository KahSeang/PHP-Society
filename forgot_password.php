<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'tankahseang05@gmail.com';                 // SMTP username
        $mail->Password   = 'qpnazwzwvowwbwhb';                        // SMTP password
        $mail->Port       = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('tankahseang05@gmail.com', 'KAH SEANG帅哥');
        $mail->addAddress($to);                                     // Add a recipient
        
        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

session_start();
require_once 'db_connection.php'; // Include your database connection

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    
    $connection = mysqli_connect('localhost','root','','login');
    
    $query = "SELECT * FROM users WHERE email='$email'";
    
    $result = mysqli_query($connection, $query);
    
    if(mysqli_num_rows($result) == 1){ 
        $row = mysqli_fetch_assoc($result);
        $reset_token = bin2hex(random_bytes(16)); // Generate unique reset token
        $update_query = "UPDATE users SET reset_token='$reset_token' WHERE email='$email'";
        mysqli_query($connection, $update_query);
        
        $reset_link = "http://localhost/AsgmPhPKS/reset_password.php?token=" . $reset_token;
        $body = "Please click the following link to reset your password: " . $reset_link;
        if(sendEmail($email, 'Password Reset', $body)){
            $success_message = "A password reset link has been sent to your email address.";
        } else {
            $error_message = "Error sending password reset email.";
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
    <title>Forgot Password</title>
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

.container {
    width: 500px;
    height: 300px;
    margin: 0 auto;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.container h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin: 20px 0px;
    text-align: center;
}

input[type="email"],
button {
    width: 80%;
    height: 35px;
    border: 1px solid #ccc;
    border-radius: 10px;
    transition: border-color 0.3s;
    box-sizing: border-box;
    text-align: center;
    align-items: center;
    margin-left: 50px;
    
}

input[type="email"]:focus {
    border-color: #007bff;
}

button {
    background-color: grey;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 30px;
}

button:hover {
    background-color: #403f3b;
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

.card-header {
    background-color: grey;
    color: white;
    border-bottom: none;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    text-align: center;
    height: 20px;
    padding: 20px;
}

    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Forgot Password
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Please enter your email" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
