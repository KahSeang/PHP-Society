<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['event_id'])) {
    $connection = mysqli_connect('localhost', 'root', '', 'login');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_id = mysqli_real_escape_string($connection, $_POST['user_id']);
    $event_id = mysqli_real_escape_string($connection, $_POST['event_id']);

    $check_cart = "SELECT * FROM cart WHERE user_id = '$user_id' AND event_id = '$event_id'";
    $cart_result = mysqli_query($connection, $check_cart);

    if (mysqli_num_rows($cart_result) > 0) {
        $_SESSION['message'] = "Event is already in the cart.";
    } else {
        $insert_cart = "INSERT INTO cart (user_id, event_id) VALUES ('$user_id', '$event_id')";
        if (mysqli_query($connection, $insert_cart)) {
            $_SESSION['message'] = "Event added to cart successfully.";
        } else {
            $_SESSION['message'] = "Error adding event to cart: " . mysqli_error($connection);
        }
    }

    // Redirect back to the event details page
    header("Location: event_details.php?event_id=$event_id");
    exit;
} else {
    $_SESSION['message'] = "You must be logged in to add items to your cart.";
    header('Location: login.php'); 
    exit;
}
?>
