<?php
// Include necessary files
require_once 'session_check.php';
require_once 'db_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
    $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
    $card_name = mysqli_real_escape_string($conn, $_POST['card_name']);
    
    $sql = "INSERT INTO payment (user_id, payment_method, expiry_date, cvv, card_number, card_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, 'isssss', $user_id, $payment_method, $expiry_date, $cvv, $card_number, $card_name);
    if (mysqli_stmt_execute($stmt)) {
        $payment_id = mysqli_insert_id($conn);

        $cart_sql = "SELECT * FROM cart WHERE user_id = ?";
        $cart_stmt = mysqli_prepare($conn, $cart_sql);
        mysqli_stmt_bind_param($cart_stmt, 'i', $user_id);
        mysqli_stmt_execute($cart_stmt);
        $result = mysqli_stmt_get_result($cart_stmt);
        
        $total_price = 0;
        $total_softskill_points = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $event_sql = "SELECT event_price, event_softskill, event_available FROM event WHERE event_id = ?";
            $event_stmt = mysqli_prepare($conn, $event_sql);
            mysqli_stmt_bind_param($event_stmt, 'i', $row['event_id']);
            mysqli_stmt_execute($event_stmt);
            $event_result = mysqli_stmt_get_result($event_stmt);
            $event_data = mysqli_fetch_assoc($event_result);

            $price = $event_data['event_price'];  
            $soft_skill_points = $event_data['event_softskill'];
            $available_quantity = $event_data['event_available'];

            $total_price += $price * $row['quantity'];
            $total_softskill_points += $soft_skill_points * $row['quantity'];
            
            $booking_sql = "INSERT INTO booking_details (booking_id, event_id, user_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
            $booking_stmt = mysqli_prepare($conn, $booking_sql);
            mysqli_stmt_bind_param($booking_stmt, 'iiiii', $payment_id, $row['event_id'], $user_id, $row['quantity'], $price);
            mysqli_stmt_execute($booking_stmt);

            $new_quantity = $available_quantity - $row['quantity']; // Subtract the purchased quantity from the available quantity
            $update_event_sql = "UPDATE event SET event_available = ? WHERE event_id = ?";
            $update_event_stmt = mysqli_prepare($conn, $update_event_sql);
            mysqli_stmt_bind_param($update_event_stmt, 'ii', $new_quantity, $row['event_id']);
            mysqli_stmt_execute($update_event_stmt);

            mysqli_stmt_close($event_stmt);  // Close the event statement
        }

        $update_sql = "UPDATE users SET softskill = softskill + ? WHERE user_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, 'ii', $total_softskill_points, $user_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        $clear_sql = "DELETE FROM cart WHERE user_id = ?";
        $clear_stmt = mysqli_prepare($conn, $clear_sql);
        mysqli_stmt_bind_param($clear_stmt, 'i', $user_id);
        mysqli_stmt_execute($clear_stmt);
        
        mysqli_commit($conn);

echo '<script>alert("Payment successfully"); window.location.href = "event_history.php";</script>';       
    } else {    +
        $error_message = "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    if (isset($cart_stmt)) mysqli_stmt_close($cart_stmt);
    if (isset($booking_stmt)) mysqli_stmt_close($booking_stmt);
    if (isset($clear_stmt)) mysqli_stmt_close($clear_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Payment Method</title>
<!-- CSS Styles -->
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
}

.container {
    width: 80%;
    max-width: 960px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.payment-group {
    margin-bottom: 20px;
}

.payment-group label {
    display: block;
    margin-bottom: 5px;
}

.payment-group select {
    margin-right: 10px;
}

.payment-fields {
    display: none;
}

.button-container {
    text-align: center;
    padding-top: 10px;
}

.next-btn {
    padding: 10px 30px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px;
    text-decoration: none;
}

.next-btn:hover {
    background-color: #2980b9;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Select Payment Method</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="paymentForm" onsubmit="return validateForm()">
        <div class="payment-group">
            <label>Select Payment Method:</label>
            <select id="payment_method" name="payment_method" onchange="handlePaymentMethodChange()">
                <option value="SELECT">SELECT</option>
                <?php

                $query = "SELECT id, payment_type FROM payment_method";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id"] . '">' . $row["payment_type"] . '</option>';
                    }
                } else {
                    echo "0 results";
                }

                mysqli_close($conn);
                ?>
            </select>
        </div>
        <div id="credit_card_fields" class="payment-fields">
            <label for="expiry_date">Expiration Date (MM/YYYY):</label>
            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YYYY">
            <label for="cvv">CVV:</label>
<input type="text" id="cvv" name="cvv" maxlength="3" pattern="\d{3}" title="CVV must be a 3-digit number" required><br><br>
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number"><br><br>
            <label for="card_name">Card Name:</label>
            <input type="text" id="card_name" name="card_name"><br>
        </div>
        <div id="paypal_fields" class="payment-fields">
            <label for="paypal_email">PayPal Email:</label>
            <input type="email" id="paypal_email" name="paypal_email"><br>
        </div>
        <div id="qr_code" class="payment-fields">
    <img src="Websystemphp/tng.jpg" alt="QR Code">
</div>

        <div class="button-container">
            <button type="submit" class="next-btn">Next</button>
        </div>
    </form>
</div>
<script>
   function handlePaymentMethodChange() {
    var paymentMethod = document.getElementById('payment_method').value;
    var creditCardFields = document.getElementById('credit_card_fields');
    var paypalFields = document.getElementById('paypal_fields');
    var qrCodeDiv = document.getElementById('qr_code'); // Get the QR code div

    if (paymentMethod === '3') {
        creditCardFields.style.display = 'block';
        paypalFields.style.display = 'none';
        qrCodeDiv.style.display = 'none'; // Hide the QR code div
    } else if (paymentMethod === '4') {
        creditCardFields.style.display = 'none';
        paypalFields.style.display = 'block';
        qrCodeDiv.style.display = 'none'; // Hide the QR code div
    } else if (paymentMethod === '5') { // Assuming '5' corresponds to QR code payment
        creditCardFields.style.display = 'none';
        paypalFields.style.display = 'none';
        qrCodeDiv.style.display = 'block'; // Show the QR code div
    } else {
        creditCardFields.style.display = 'none';
        paypalFields.style.display = 'none';
        qrCodeDiv.style.display = 'none'; // Hide the QR code div
    }
}

</script><script>
function validateForm() {
    var paymentMethod = document.getElementById('payment_method').value;
    var expiryDate = document.getElementById('expiry_date').value;
    var cvv = document.getElementById('cvv').value;
    var cardNumber = document.getElementById('card_number').value;
    var cardName = document.getElementById('card_name').value;
    var paypalEmail = document.getElementById('paypal_email').value;

    if (paymentMethod === 'SELECT') {
        alert("Please select a payment method.");
        return false;
    }

    // Validate Credit Card Details
    if (paymentMethod === '3') { // Assuming '3' is the ID for credit cards
        var regexCardNumber = /^[0-9]{16}$/; // Simple regex for 16-digit card numbers
        var regexExpiryDate = /^(0[1-9]|1[0-2])\/?([0-9]{4})$/; // MM/YYYY format
        var regexCvv = /^[0-9]{3,4}$/; // 3 or 4 digits

        if (!regexCardNumber.test(cardNumber)) {
            alert("Please enter a valid 16-digit card number.");
            return false;
        }
        if (!regexExpiryDate.test(expiryDate)) {
            alert("Please enter a valid expiration date in MM/YYYY format.");
            return false;
        }
        if (!regexCvv.test(cvv)) {
            alert("Please enter a valid CVV.");
            return false;
        }
        if (cardName.trim() === '') {
            alert("Please enter the name on the card.");
            return false;
        }
    }

    else if (paymentMethod === '4') { // Assuming '4' is the ID for PayPal
        var regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!regexEmail.test(paypalEmail)) {
            alert("Please enter a valid PayPal email.");
            return false;
        }
    }

    return true; 
}
</script>


<?php include 'footer.php'; ?>

</body>
</html>
