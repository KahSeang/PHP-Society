<?php 

include 'adminsidebar.php';
include 'adminheader.php';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Add margin reset to the body */
        }

        table {
            width: calc(100% - 250px); /* Adjust table width to accommodate sidebar */
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 100px; /* Ensure proper spacing for the table */
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

.content-wrapper {
        margin-left: 250px; /* Adjust this value to match the sidebar width */
        padding-top: 20px; /* Add padding to maintain distance from the header */
        padding-left: 20px; /* Add padding to maintain distance from the sidebar */
    }
    </style>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "login";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process form submission
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];

        if ($_FILES['emp_photo']['error'] == UPLOAD_ERR_OK) {
            $emp_photo = $_FILES['emp_photo']['name'];
            $tmp_name = $_FILES['emp_photo']['tmp_name'];
            move_uploaded_file($tmp_name, "uploads/" . $emp_photo);
        }

        $sql = "INSERT INTO employee (name, emp_photo, description) VALUES ('$name', '$emp_photo', '$description')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Delete employee record
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $sql = "DELETE FROM employee WHERE emp_id=$id";
        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Record deleted successfully");</script>';
        } else {
            echo '<script>alert("Error deleting record: ' . $conn->error . '");</script>';
        }
    }

    // Edit employee record
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];

        $sql = "SELECT * FROM employee WHERE emp_id=$edit_id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $edit_name = $row['name'];
            $edit_description = $row['description'];
        }
    }

    // Update employee record
    if (isset($_POST['update'])) {
        $edit_id = $_POST['edit_id'];
        $edit_name = $_POST['edit_name'];
        $edit_description = $_POST['edit_description'];

        $sql = "UPDATE employee SET name='$edit_name', description='$edit_description' WHERE emp_id=$edit_id";
        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Record updated successfully");</script>';
        } else {
            echo '<script>alert("Error updating record: ' . $conn->error . '");</script>';
        }
    }
    ?>
    <div class="content-wrapper">
    <h1>Employee Records</h1>

    <!-- Form for adding and updating employee records -->
    <form action="" method="post" enctype="multipart/form-data">
        <?php if (isset($edit_id) && isset($edit_name) && isset($edit_description)): ?>
            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <?php endif; ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($edit_name) ? $edit_name : ''; ?>" required><br><br>
        <label for="emp_photo">Photo:</label>
        <input type="file" id="emp_photo" name="emp_photo"><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"><?php echo isset($edit_description) ? $edit_description : ''; ?></textarea><br><br>
        <input type="submit" name="<?php echo isset($edit_id) ? 'update' : 'submit'; ?>" value="<?php echo isset($edit_id) ? 'Update' : 'Submit'; ?>">
    </form>

    <?php
    // Display employee records
    $sql = "SELECT * FROM employee";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Name</th><th>Employee ID</th><th>Photo</th><th>Description</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["emp_id"] . "</td>";
            echo "<td><img src='uploads/" . $row["emp_photo"] . "' width='50'></td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td><a href='employee.php?delete=" . $row["emp_id"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a> | <a href='edit.php?edit=" . $row["emp_id"] . "'>Edit</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
    </div>
</body>
</html>
