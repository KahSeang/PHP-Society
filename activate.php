<?php
require 'db_connection.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Prepare SQL to check the activation code and update the user status
    $stmt = $conn->prepare("SELECT * FROM users WHERE activation_code = ? AND is_active = 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $update_stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_code = '' WHERE activation_code = ?");
        $update_stmt->bind_param("s", $code);
        $update_stmt->execute();
        if ($update_stmt->affected_rows > 0) {
            echo "Your account has been activated successfully!";
        } else {
            echo "Your account could not be activated.";
        }
        $update_stmt->close();
    } else {
        echo "This activation code is invalid or the account has already been activated.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No activation code provided.";
}
?>
