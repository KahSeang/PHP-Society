<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';
include 'header.php';

// Check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Initialize variables
$softskill_points = 0;
$user_name = "";

// Retrieve soft skill points and user name from the database
$sql = "SELECT softskill, username FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $softskill_points = $row['softskill'];
    $user_name = $row['username'];
}

// Check if the user qualifies for a certificate
if ($softskill_points >= 100) {
    // Generate certificate HTML
    $certificate_html = generateCertificateHTML($user_name, $softskill_points);

    // Output the certificate
    echo $certificate_html;
} else {
    // Display a message indicating that the user does not qualify for a certificate
    echo '<h1>Sorry, you do not qualify for a certificate at this time.</h1>';
}

// Function to generate certificate HTML
function generateCertificateHTML($user_name, $softskill_points) {
    // Get the current date
    $current_date = date("Y-m-d");
    
    // Set certificate color based on points
    $certificate_color = '';
    if ($softskill_points >= 1000) {
        $certificate_color = 'diamond'; // Add color for 1000 points or more
    } elseif ($softskill_points >= 300) {
        $certificate_color = 'gold';
    } elseif ($softskill_points >= 200) {
        $certificate_color = 'silver';
    } elseif ($softskill_points >= 100) {
        $certificate_color = 'bronze';
    }

    // Define colors for each level
    $color_codes = [
        'bronze' => '#cd7f32', // Bronze
        'silver' => '#c0c0c0', // Silver
        'gold' => '#ffd700',   // Gold
        'diamond' => '#0e5c91' // Diamond
    ];

    // Get the color code based on the certificate color
    $color_code = $color_codes[$certificate_color];

    // Determine text color based on background color
    $text_color = getContrastColor($color_code);

    // Certificate HTML with concatenated PHP variables
    $certificate_html = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Soft Skill Certificate</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .container {
                width: 800px;
                margin: 50px auto;
                padding: 20px;
                border: 5px solid #555;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #333;
                font-size: 32px;
                margin-bottom: 20px;
            }
            h2 {
                color: $text_color; /* Set text color */
                font-size: 24px;
                margin-bottom: 10px;
            }
            p {
                color: $text_color; /* Set text color */
                font-size: 18px;
                margin-bottom: 15px;
            }
            strong {
                color: #000;
            }
            .certificate {
                background-color: $color_code; /* Set certificate color */
                padding: 20px;
                border-radius: 10px;
                margin-top: 30px;
            }
            button {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 10px 20px;
                font-size: 18px;
                cursor: pointer;
                border-radius: 5px;
            }
            button:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Certificate of Achievement</h1>
        <div class="certificate">
            <h2>$user_name</h2>
            <p>This is to certify that</p>
            <p><strong>$user_name</strong> has successfully acquired <strong>$softskill_points</strong> soft skill points.</p>
            <p>Issued on: $current_date</p>
            <button onclick="window.print()">Print Certificate</button>
        </div>
    </div>
    </body>
    </html>
HTML;
    return $certificate_html;
}

// Function to determine text color based on background color
function getContrastColor($background_color) {
    $r = hexdec(substr($background_color, 1, 2));
    $g = hexdec(substr($background_color, 3, 2));
    $b = hexdec(substr($background_color, 5, 2));
    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return $brightness > 125 ? '#000000' : '#ffffff'; // Return black for light backgrounds, white for dark backgrounds
}


?>