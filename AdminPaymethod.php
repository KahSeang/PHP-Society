<?php
// Include header and sidebar files
include 'adminheader.php';
include 'adminsidebar.php';
include 'db_connection.php';

// Function to establish a database connection
function getConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";
    
    $connection = new mysqli($servername, $username, $password, $dbname);
    
    // Check for connection error
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
    return $connection;
}

// Function to delete a payment method
function deletePayment($id) {
    $connection = getConnection();
    
    $sql = "DELETE FROM payment_method WHERE id = ?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $id);
    
    $result = $statement->execute();
    
    if ($result) {
        echo "<script>alert('Payment method deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $statement->error . "');</script>";
    }
    
    // Close statement and connection
    $statement->close();
    $connection->close();
}

// Function to update a payment method
function updatePayment($id, $new_payment_name) {
    $connection = getConnection();
    
    $sql = "UPDATE payment_method SET payment_type = ? WHERE id = ?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("si", $new_payment_name, $id);
    
    $result = $statement->execute();
    
    if ($result) {
        echo "<script>alert('Payment method updated successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $statement->error . "');</script>";
    }
    
    // Close statement and connection
    $statement->close();
    $connection->close();
}

// Function to create a payment method
function createPayment($payment_type) {
    $connection = getConnection();
    
    $sql = "INSERT INTO payment_method (payment_type) VALUES (?)";
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $payment_type);
    
    $result = $statement->execute();
    
    if ($result) {
        echo "<script>alert('Payment method created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $statement->error . "');</script>";
    }
    
    // Close statement and connection
    $statement->close();
    $connection->close();
}

// Check for form submission
if (isset($_POST['delete_payment'])) {
    $id = $_POST['id'];
    deletePayment($id);
}

if (isset($_POST['update_payment'])) {
    $id = $_POST['id'];
    $new_payment_name = $_POST['new_payment_name'];
    updatePayment($id, $new_payment_name);
}

if (isset($_POST['create_payment'])) {
    $payment_type = $_POST['payment_type'];
    createPayment($payment_type);
}

// HTML content starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Payment CRUD</title>
    <style>
        /* Add CSS styles for sidebar */
        #sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #333;
            padding-top: 50px; /* Adjust this value according to your header height */
        }

        #content {
            margin-left: 250px; /* Ensure content doesn't overlap with sidebar */
            padding: 20px;
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <?php include 'adminsidebar.php'; ?>

    <div id="content">
        <h2>Payment Type CRUD</h2>

        <!-- Form for creating a new payment type -->
        <h3>Create Payment Type</h3>
        <form action="" method="POST">
            <label for="payment_type">Payment Name:</label>
            <input type="text" id="payment_type" name="payment_type" required>
            <button type="submit" name="create_payment">Create</button>
        </form>
        
        <!-- Form for updating an existing payment method -->
        <h3>Update Payment</h3>
        <form action="" method="POST">
            <label for="id">Payment ID:</label>
            <input type="text" id="id" name="id" required>
            <label for="new_payment_name">New Payment Name:</label>
            <input type="text" id="new_payment_name" name="new_payment_name" required>
            <button type="submit" name="update_payment">Update</button>
        </form>
        
        <h3>View Payments</h3>
        <?php
        // Display payment methods
        $connection = getConnection();
        $query = "SELECT * FROM payment_method";
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead class='thead-dark'><tr><th>ID</th><th>Name</th><th>Action</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['payment_type']}</td>";
                echo "<td>
                        <form action='' method='POST'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='delete_payment'>Delete</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "No payment methods found.";
        }
        
        // Close connection
        $connection->close();
        ?>
    </div>

    <?php include 'admin_footer.php'; ?>
</body>
</html>