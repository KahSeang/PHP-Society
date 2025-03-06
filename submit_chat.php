<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("INSERT INTO chats (user_id, chat, date) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $user_id, $message);

            if ($stmt->execute()) {
                echo "Success";
            } else {
                echo "Failed to insert message.";
            }

            $stmt->close();
        } else {
            echo "User not logged in.";
        }
    } else {
        echo "Message is empty.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
