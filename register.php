<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'tankahseang05@gmail.com';              // SMTP username
        $mail->Password   = 'qpnazwzwvowwbwhb';                        // SMTP password (use an app password here)
        $mail->Port       = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('tankahseang05@gmail.com', 'Rasa Senang Register Confirmation');
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

function generateActivationToken() {
    return bin2hex(random_bytes(16)); // 32 characters long
}

$success_message = '';
$error_message = '';

if(isset($_POST['submit'])){
    require 'db_connection.php';

    $recaptcha_secret = '6Lf_p7QpAAAAALI1iSPLJG6ZB-i9PBrE6JgBrXVT'; 
    
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Make the API request
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $verify_response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $response_data = json_decode($verify_response);

    // If reCAPTCHA is successfully validated
    if($response_data->success){
        // Retrieve the form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        // Check if the email address already exists in the database
$email_check_query = "SELECT email FROM users WHERE email=? UNION SELECT email FROM admin WHERE email=?";
        $email_check_stmt = $conn->prepare($email_check_query);
$email_check_stmt->bind_param("ss", $email, $email);
        $email_check_stmt->execute();
        $email_check_result = $email_check_stmt->get_result();

        if ($email_check_result->num_rows > 0) {
            $error_message = "Email address already exists. Please choose a different email.";
        } else {
            if(strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $error_message = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
            } 
             else {
                $activation_token = generateActivationToken();

                $sql = "INSERT INTO users (username, password, email, activation_code, is_active) VALUES (?, ?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                $stmt->bind_param("ssss", $username, $hashed_password, $email, $activation_token);

                if($stmt->execute()){
                    $to = $email;
                    $subject = 'Account Activation Required';
                    $activation_link = "http://localhost/AsgmPhPKS/activate.php?code=" . $activation_token;
                    $body = "Dear {$username},<br><br>Please click on the following link to activate your account: <a href='{$activation_link}'>Activate Account</a>";
                    if(sendEmail($to, $subject, $body)) {
                        $success_message = "Registration successful. An activation email has been sent.";
                    } else {
                        $error_message = "Registration successful, but there was an error sending the activation email. Please contact support.";
                    }
                } else {
                    $error_message = "Error: " . $conn->error;
                }

                $stmt->close();
            }
        }

        $email_check_stmt->close();
        $conn->close();
    } else {
        $error_message = "reCAPTCHA verification failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register OK</title>
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
            max-width: 400px;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.3);
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
            display: block;
            font-weight: bold;
        }

       
        input[type="text"],
        input[type="password"],
        input[type="email"],
        button {
            width: 89%; /* Make the input fields and button take up full width */
            padding: 15px; /* Increase padding to make them larger */
            font-size: 16px; /* Increase font size for better readability */
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px; /* Reduce margin between form elements */
        }
  input[type="email"]:focus {
            border-color: #4CAF50; /* Change border color on focus */
        }
        button {
            background-color: #4CAF50;
            color: black;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: black;
            
        }
        a {
            text-decoration: none;
            color:blue;
        }
        form label{
            color: black;
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
    </style>
   
</head>
<body>
    <div class="container">
        <h2 style="color:black ;">User Registration</h2>
        <?php if($success_message !== ''): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if($error_message !== ''): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6Lf_p7QpAAAAADaVfAbvFkCzSTLJ7bK0n-n2DSkG"></div>
            </div>
            <button type="submit" name="submit" style="width: 100%">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
   <script>
    $(document).ready(function(){
        // Focus on the username/email input field when the page loads
        $("#username").focus();
    });
     function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var email = document.getElementById("email").value;

            if (username.trim() === '' || password.trim() === '' || confirmPassword.trim() === '' || email.trim() === '') {
                alert("All fields are required.");
                return false;
            }

            if (!isValidEmail(email)) {
                alert("Invalid email format.");
                return false;
            }

            // Check password length
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }

            // Check password format (e.g., contains at least one uppercase letter, one lowercase letter, and one number)
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!passwordRegex.test(password)) {
                alert("Password must contain at least one uppercase letter, one lowercase letter, and one number.");
                return false;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }

        function isValidEmail(email) {
            var emailRegex = /\S+@\S+\.\S+/;
            return emailRegex.test(email);
        }
</script>

</body>
</html>
