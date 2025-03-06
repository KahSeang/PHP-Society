<?php
require_once 'session_check.php';
require_once 'db_connection.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.cart_id, c.user_id, c.event_id, c.quantity, e.type_name, e.event_name, e.event_price 
          FROM cart c 
          INNER JOIN event e ON c.event_id = e.event_id 
          WHERE c.user_id = $user_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed.");
}

$user_query = "SELECT username, email, gender, address, postcode, city, state FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if ($user_result) {
    $user_data = mysqli_fetch_assoc($user_result);
} else {
    $user_data = array();
}

// Initialize $stmtPayment outside of conditional statements
$stmtPayment = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user inputs
    $fullname = isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $postcode = isset($_POST['postcode']) ? htmlspecialchars($_POST['postcode']) : '';
    $city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
    $state = isset($_POST['state']) ? htmlspecialchars($_POST['state']) : '';
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';

    // Update user information if the form is submitted
    if (isset($_POST['updateUser'])) {
        $updateUserQuery = "UPDATE users SET username=?, email=?, address=?, postcode=?, city=?, state=? WHERE user_id=?";
        $stmtUpdateUser = $conn->prepare($updateUserQuery);
        $stmtUpdateUser->bind_param("ssssssi", $fullname, $email, $address, $postcode, $city, $state, $user_id);
        $stmtUpdateUser->execute();
        if ($stmtUpdateUser->affected_rows > 0) {
            echo "User information updated successfully.";
        } else {
            echo "Error updating user information: " . $conn->error;
        }
    }

    // Insert payment data into the database
    if ($paymentMethod == 'credit_card') {
        $cardNumber = isset($_POST['cardNumber']) ? htmlspecialchars($_POST['cardNumber']) : '';
        $cardName = isset($_POST['cardName']) ? htmlspecialchars($_POST['cardName']) : '';
        $expiryDate = isset($_POST['expiryDate']) ? htmlspecialchars($_POST['expiryDate']) : '';
        $cvv = isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : '';

        $insertPaymentQuery = "INSERT INTO payment (user_id, payment_method, card_number, card_name, expiry_date, cvv)
                               VALUES (?, ?, ?, ?, ?, ?)";
        $stmtPayment = $conn->prepare($insertPaymentQuery);
        $stmtPayment->bind_param("isssss", $user_id, $paymentMethod, $cardNumber, $cardName, $expiryDate, $cvv);
    } elseif ($paymentMethod == 'paypal') {
        $paypalEmail = isset($_POST['paypalEmail']) ? htmlspecialchars($_POST['paypalEmail']) : '';

        $insertPaymentQuery = "INSERT INTO payment (user_id, payment_method, paypal_email)
                               VALUES (?, ?, ?)";
        $stmtPayment = $conn->prepare($insertPaymentQuery);
        $stmtPayment->bind_param("iss", $user_id, $paymentMethod, $paypalEmail);
    } elseif ($paymentMethod == 'tng') {
        // Handle TNG payment method
    }

    if ($stmtPayment) {
        $stmtPayment->execute();

        if ($stmtPayment->affected_rows > 0) {
            // Payment data inserted successfully
            // Redirect to the receipt page with query parameters containing relevant data
            $receiptURL = "receipt.php?name=$fullname&email=$email&address=$address&city=$city&state=$state&zipCode=$postcode&paymentMethod=$paymentMethod";
            header("Location: $receiptURL");
            exit;
        } else {
            echo "Error: Payment data insertion failed.";
        }

        $stmtPayment->close();
    } else {
        echo "Error: Failed to prepare payment statement.";
    }
}

$conn->close();
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Steps</title>

    <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
    }

    /* Container */
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

    /* Process wrapper */
    .process-wrapper {
      display: flex;
      justify-content: center;
      list-style-type: none;
      padding: 0;
      margin-bottom: 20px;
    }

    /* Process steps */
    .process-step {
      text-align: center;
      flex: 1;
    }
    .process-step.active {
      border: 2px solid #3498db; /* Primary color */
    }

    .process-step .circle.active {
      background-color: #3498db; /* Primary color */
    }

    .process-step .circle {
      width: 40px;
      height: 40px;
      line-height: 40px;
      border-radius: 50%;
      background-color: #3498db; /* Primary color */
      color: white;
      display: inline-block;
      margin-bottom: 5px;
    }

    .process-step.gray .circle {
      background-color: #bdc3c7; /* Gray color for inactive step */
    }

    /* Process lines */
    .process-line {
      height: 5px;
      background-color: #3498db; /* Primary color */
      flex-grow: 1;
      align-self: center;
    }

    .process-line.gray {
      background-color: #bdc3c7; /* Gray color for inactive line */
    }

    /* Step content */
    .step-content {
      display: none;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .step-content.active {
      display: block;
    }

    /* Form and table styling */
    form > table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table td {
      padding: 10px;
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    /* Input and select styling */
    input[type="text"],
    select,
    input[type="email"],
    input[type="tel"],
    input[type="number"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    select:focus,
    input:focus {
      border-color: #3498db; /* Primary color for focus */
      outline: none;
    }

    /* Button container */
    .button-container {
      text-align: center;
      padding-top: 10px;
    }

    /* Buttons */
    button {
      padding: 10px 30px;
      background-color: #3498db; /* Primary color */
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin: 5px;
    }

    button:hover {
      background-color: #2980b9; /* Darker shade for hover state */
    }

    /* Media queries for responsive adjustments */
    @media (max-width: 768px) {
      .container {
        width: 90%;
      }

      .process-step .circle {
        width: 35px;
        height: 35px;
        line-height: 35px;
      }
    }

    @media (max-width: 480px) {
      .process-step {
        display: block;
        margin-bottom: 10px;
      }

      .process-line,
      .process-step .circle {
        display: none;
      }

      .step-content {
        padding: 15px;
      }

    }
     nav {
          position: fixed;
          top: 0;
          z-index: 100;
          width: 100%;
        }
       body {
          padding-top: 120px; /* Height of your header */
        }

    </style>
            <?php include 'header.php'; ?>

    </head>

    <body>




    <ul class="process-wrapper">
      <li class="process-step active" id="step1">
        <span class="circle active" data-step="1">1</span>
        <div>Chosen Categories</div>
      </li>
      <li class="process-line"></li>
      <li class="process-step" id="step2">
        <span class="circle" data-step="2">2</span>
        <div>Fill Information</div>
      </li>
      <li class="process-line"></li>
      <li class="process-step" id="step3">
        <span class="circle" data-step="3">3</span>
        <div>Choose Payment Method</div>
      </li>
      <li class="process-line"></li>
      <li class="process-step" id="step4">
        <span class="circle" data-step="4">4</span>
        <div>Complete Payment</div>
      </li>
    </ul>


    <div class="step-content" id="step-content1">
        <form action="payment.php" method="post">
        <?php
        // Check if there are rows fetched from the database
        if (mysqli_num_rows($result) > 0) {
            // Variable to track the number of events
            $eventCount = 0;
            // Initialize subtotal and total quantity variables
            $subtotal = 0;
            $totalQuantity = 0;

            // Loop through the rows fetched from the database
            while ($row = mysqli_fetch_assoc($result)) {
                // Increment the event count for each row
                $eventCount++;

                // Calculate subtotal for each item and add to total
                $subtotal += $row['quantity'] * $row['event_price'];
                // Add quantity of current item to total quantity
                $totalQuantity += $row['quantity'];

                // Output the selected category and event information
        ?>
                <div>
                    <p>Selected Category: <?php echo htmlspecialchars($row['type_name']); ?></p>
                    <p>Selected Event: <?php echo htmlspecialchars($row['event_name']); ?></p>
                    <p>Selected Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
                </div>
        <br><br>
        <?php
            }

            // Display a message indicating multiple events
            if ($eventCount > 1) {
        ?>
        <p>There are <?php echo $eventCount; ?> events in your cart.</p><br>
        <?php
            }
        } else {
            // If no rows found, display a message
        ?>
            <p>No items found in your cart.</p>
        <?php
        }
        ?>
        <div>
            <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p><br>
            <p>Total Quantity: <?php echo $totalQuantity; ?></p>

        </div>
     <div class="button-container">
            <button id="nextBtnStep1" type="button" onclick="nextStep()">Next</button>
        </div>    </form>
    </div>

    <div class="step-content" id="step-content2">
        <div class="outer-container">
            <div class="container">
                <form action="payment.php" method="post">
                    <table>
                        <tr>
                            <td>
                                <label for="fullname">Fullname*</label>
                            </td>
                            <td>
                                <!-- Populate fullname field with user's username -->
                                <input type="text" id="fullname" name="fullname" class="required" value="<?php echo isset($user_data['username']) ? htmlspecialchars($user_data['username']) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email">Email*</label>
                            </td>
                            <td>
                                <!-- Populate email field with user's email -->
                                <input type="email" id="email" name="email" class="required" value="<?php echo isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>" required>
                            </td>
                        </tr>
                       
                        <tr>
                            <td>
                                <label for="address">Address*</label>
                            </td>
                            <td>
                                <!-- Populate address field with user's address -->
                                <input type="text" id="address" name="address" class="required" value="<?php echo isset($user_data['address']) ? htmlspecialchars($user_data['address']) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="postcode">Postcode*</label>
                            </td>
                            <td>
                                <!-- Populate postcode field with user's postcode -->
                                <input type="text" id="postcode" name="postcode" class="required" value="<?php echo isset($user_data['postcode']) ? htmlspecialchars($user_data['postcode']) : ''; ?>" required>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <label for="city">City*</label>
                            </td>
                            <td><input type="text" id="city" name="city" class="required" value="<?php echo !empty($user_data['city']) ? htmlspecialchars($user_data['city']) : ''; ?>" required></td>

                        </tr>
                              <tr>
                            <td>
                                <label for="state">State*</label>
                            </td>
                            <td><input type="text" id="state" name="state" class="required" value="<?php echo !empty($user_data['state']) ? htmlspecialchars($user_data['state']) : ''; ?>" required></td>

                        </tr>
                    </table>
                <div class="button-container">
            <button id="prevBtnStep2" type="button" onclick="prevStep()">Previous</button>
            <button id="nextBtnStep2" type="button" onclick="nextStep()" name="submit">Next</button>
        </div>     
                </form>
            </div>
        </div>
    </div>

     <div class="step-content" id="step-content3">
    <div class="container">
        <h2>Choose Payment Method</h2>
        <form action="payment.php" method="post">
            <div class="inputBox">
                <label for="paymentMethod">Select Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod" class="required" onchange="togglePaymentFields()" required>
                    <option value="choose">Choose</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
            </div>
            <div id="paymentFields"></div> <!-- This is where payment method-specific fields will be displayed -->
            <div class="button-container">
                <button id="prevBtnStep3" type="button" onclick="prevStep()">Previous</button>
                <button id="nextBtnStep3" type="button" onclick="nextStep()">Next</button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePaymentFields() {
        var paymentMethod = document.getElementById('paymentMethod').value;
        var paymentFieldsContainer = document.getElementById('paymentFields');

        // Clear any previously displayed fields
        paymentFieldsContainer.innerHTML = '';

        // Show fields based on selected payment method
        if (paymentMethod === 'credit_card') {
            paymentFieldsContainer.innerHTML = `
                <div class="inputBox">
                    <label for="cardNumber">Card Number:</label>
                    <input type="text" id="cardNumber" name="cardNumber" class="required">
                </div>
                <div class="inputBox">
                    <label for="cardName">Card Name:</label>
                    <input type="text" id="cardName" name="cardName" class="required">
                </div>
                <div class="inputBox">
                    <label for="expiryDate">Expiry Date:</label>
                    <input type="text" id="expiryDate" name="expiryDate" class="required">
                </div>
                <div class="inputBox">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" class="required">
                </div>
            `;
        } else if (paymentMethod === 'paypal') {
            paymentFieldsContainer.innerHTML = `
                <div class="inputBox">
                    <label for="paypalEmail">PayPal Email:</label>
                    <input type="email" id="paypalEmail" name="paypalEmail" class="required">
                </div>
            `;
        }
        // You can add similar blocks for other payment methods
    }
</script>


    <div class="step-content" id="step-content4">
        <div class="outer-container">
            <div class="container">
                <style>
                    /* Style the cart table */
                    #cart-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }

                    /* Style table header */
                    #cart-table thead {
                        background-color: #333;
                        color: #fff;
                    }

                    #cart-table th,
                    #cart-table td {
                        padding: 10px;
                        text-align: left;
                    }

                    /* Style alternating rows */
                    #cart-table tbody tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }

                    /* Style table cell borders */
                    #cart-table th,
                    #cart-table td {
                        border-bottom: 1px solid #ddd;
                    }

                    /* Style the "Your cart is empty" message */
                    #cart-table tbody td[colspan="4"] {
                        text-align: center;
                        padding: 20px;
                        font-style: italic;
                    }

                    /* Style the table header cells */
                    #cart-table th {
                        font-weight: bold;
                    }

                    /* Style the table footer */
                    #cart-table tfoot td {
                        font-weight: bold;
                        border-top: 1px solid #ddd;
                        padding-top: 10px;
                    }
                    html{
                        width: 1300px;
                        margin: auto;
                    }

                </style>
            </head>

            <body>
                <?php
    // Check if the form is submitted for updating user information and if user data is set
    if (isset($_POST['submit'])) {
        // Retrieve form data for user information
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $postcode = $_POST['postcode'];
        $city = $_POST['city'];
        $state = $_POST['state'];

        // Update the user's information in the database
        $updateUserQuery = "UPDATE users SET username=?, email=?, gender=?, address=?, postcode=?, city=?, state=? WHERE user_id=?";
        $stmtUser = $conn->prepare($updateUserQuery);
        $stmtUser->bind_param("sssssssi", $fullname, $email, $gender, $address, $postcode, $city, $state, $user_id);
        $stmtUser->execute();

        // Check if the user information was successfully updated
        if ($stmtUser->affected_rows > 0) {
            echo "User information updated successfully.";
        } else {
            echo "Error updating user information: " . $conn->error;
        }
    }
    ?>

                <div>
                    <h1>Receipt</h1>

                    <h2>Billing Information:</h2>
                    <ul>
                        <li><strong>Name:</strong> <span id="receiptName"></span></li>
                        <li><strong>Email:</strong> <span id="receiptEmail"></span></li>
                        <li><strong>Address:</strong> <span id="receiptAddress"></span></li>
                        <li><strong>City:</strong> <span id="receiptCity"></span></li>
                        <li><strong>State:</strong> <span id="receiptState"></span></li>
                        <li><strong>Zip Code:</strong> <span id="receiptCode"></span></li>
                    </ul>

                    <h2>Payment Information:</h2>
                    <ul>
                        <li><strong>Name on Card:</strong> <span id="receiptName2"></span></li>
                        <li><strong>Credit Card Number:</strong> <span id="receiptCredit"></span></li>
                        <li><strong>Expiry Month:</strong> <span id="receiptExoMonth"></span></li>
                        <li><strong>Expiry Year:</strong> <span id="receiptExoYear"></span></li>
                        <li><strong>CVV:</strong> <span id="receiptCvv"></span></li>
                    </ul>

                    <h2>Delivery Option:</h2>
                    <p><span id="receiptDeliveryOption"></span></p>

                    <h2>ToTal</h2>
                </div>

                <!--Table-->
                <div id="cart-table-container">
                    <table id="cart-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Cart items will be populated here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total</td>
                                <td id="totalPrice">MYR 0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            <script>
                // JavaScript function to extract and display data from query parameters
                function displayReceiptData() {
                    // Parse the query parameters from the URL
                    var urlParams = new URLSearchParams(window.location.search);

                    // Extract and display data
                    document.getElementById('receiptName').textContent = urlParams.get('name');
                    document.getElementById('receiptEmail').textContent = urlParams.get('email');
                    document.getElementById('receiptAddress').textContent = urlParams.get('address');
                    document.getElementById('receiptCity').textContent = urlParams.get('city');
                    document.getElementById('receiptState').textContent = urlParams.get('state');
                    document.getElementById('receiptCode').textContent = urlParams.get('zipCode');

                    document.getElementById('receiptName2').textContent = urlParams.get('cardName');
                    document.getElementById('receiptCredit').textContent = urlParams.get('cardNumber');
                    document.getElementById('receiptExoMonth').textContent = urlParams.get('expMonth');
                    document.getElementById('receiptExoYear').textContent = urlParams.get('expYear');
                    document.getElementById('receiptCvv').textContent = urlParams.get('cvv');

                    document.getElementById('receiptDeliveryOption').textContent = urlParams.get('delivery-option');
                }

                // Call the displayReceiptData function when the page loads
                window.onload = displayReceiptData;



            </script>
    </div>
    </div>
    </div>

    <div class="button-container">
    <button id="prevBtn" style="display:none;">Previous</button>
    <button id="nextBtn" type="submit" name="submit">Next</button>

    </div>

     <script>
        const steps = document.querySelectorAll('.process-step');
        const lines = document.querySelectorAll('.process-line');
        const stepContents = document.querySelectorAll('.step-content');
        let currentStep = 0;

        function updateProcess() {
          steps.forEach((step, index) => {
            step.classList[index === currentStep ? 'add' : 'remove']('active');
          });

          lines.forEach((line, index) => {
            line.classList[index < currentStep ? 'remove' : 'add']('gray');
          });

          stepContents.forEach((content, index) => {
            content.classList[index === currentStep ? 'add' : 'remove']('active');
          });

          document.getElementById('prevBtn').style.display = currentStep === 0 ? 'none' : 'inline-block';
          document.getElementById('nextBtn').innerText = currentStep === steps.length - 1 ? 'Finish' : 'Next';
        }

    function nextStep() {
      const requiredFields = stepContents[currentStep].querySelectorAll('.required');
      let isValid = true;
      let missingFields = false;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.classList.add('invalid');
          isValid = false;
          missingFields = true;
        } else {
          field.classList.remove('invalid');
        }
      });

      if (missingFields) {
        alert('Please fill in all required fields.');
      }

      if (isValid && currentStep < steps.length - 1) {
        currentStep++;
        updateProcess();
      }
    }


        function prevStep() {
          if (currentStep > 0) {
            currentStep--;
            updateProcess();
          }
        }

        document.getElementById('nextBtn').addEventListener('click', nextStep);
        document.getElementById('prevBtn').addEventListener('click', prevStep);

        updateProcess(); // Initialize the process bar based on the current step
      </script>

      <style>
    .required:invalid {
      border-color: red;
    }


        .invalid::placeholder {
          color: red;
        }
      </style>


    </body>
    </html>