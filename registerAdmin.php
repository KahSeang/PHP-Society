<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

$usernameErr = $passwordErr = $emailErr = "";
$username = $password = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            $passwordErr = "Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            // Check if the email is already registered
            $connection = mysqli_connect('localhost', 'root', '', 'login');
            $query = "SELECT email FROM admin WHERE email='$email'";
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                $emailErr = "This email is already registered";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $insertQuery = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($connection, $insertQuery);
                mysqli_stmt_bind_param($stmt, "sss", $username, $passwordHash, $email);
                if (mysqli_stmt_execute($stmt)) {

                    if (sendEmail($username, $email)) {
                        echo '<script>alert("Registration successful. Email sent successfully."); window.location.replace("login.php");</script>';
                        exit();
                    } else {
                        echo '<script>alert("Registration successful. Email could not be sent."); window.location.replace("login.php");</script>';
                        exit();
                    }
                } else {
                    echo '<script>alert("Error: ' . mysqli_error($connection) . '");</script>';
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_close($connection);
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendEmail($username, $email) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'tankahseang05@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'qpnazwzwvowwbwhb'; // Replace with your SMTP password
        $mail->Port = 587; // Replace with your SMTP port
        $mail->SMTPSecure = 'tls'; // Replace with 'tls' or 'ssl' based on your SMTP settings

        // Sender and recipient
        $mail->setFrom('tankahseang05@gmail.com', 'Your Name');
        $mail->addAddress($email, $username);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body = 'Hello ' . $username . ',<br><br>Your registration was successful.';

        // Send email
        $mail->send();
        
        return true; // Email sent successfully
    } catch (Exception $e) {
        // Handle any exceptions
        return false; // Email sending failed
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            background-image: url('Websystemphp/10.webp');
        }

        form {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 20px 10px rgba(0, 0, 0.3, 0.3);
            width: 400px;
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        form p {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            text-align: center;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: 155px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
        }

        a:hover {
            color: #000;
        }
        
        .g-recaptcha{
            margin-left: 50px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <p>Admin Register</p>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
        <span class="error"><?php echo $usernameErr; ?></span>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}" title="Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long">
        <span class="error"><?php echo $passwordErr; ?></span>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        <span class="error"><?php echo $emailErr; ?></span>

        <div class="g-recaptcha" data-sitekey="6Lfdm9spAAAAAEVo8f994cQwaJTC2WuUt9QcJPG0"></div>
        
        <input type="submit" name="submit" value="Submit">
        <a href="login.php">Here to Login</a>
    </form>
</body>
</html>
