<?php

include 'adminsidebar.php';
include 'adminheader.php';
?>   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Add margin reset to the body */
        }

        form {
            margin: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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

    // Fetch employee data to populate the form
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];

        $sql = "SELECT * FROM employee WHERE emp_id=$edit_id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $edit_name = $row['name'];
            $edit_description = $row['description'];
            $edit_image = $row['emp_photo'];
        }
    }

    // Update employee record
    if (isset($_POST['update'])) {
        $edit_id = $_POST['edit_id'];
        $edit_name = $_POST['edit_name'];
        $edit_description = $_POST['edit_description'];

        if ($_FILES['edit_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["edit_image"]["name"]);

            // Create the target directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $target_file)) {
                $edit_image = $_FILES["edit_image"]["name"]; // Store only the file name
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $sql = "UPDATE employee SET name='$edit_name', description='$edit_description', emp_photo='$edit_image' WHERE emp_id=$edit_id";
        if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Record updated successfully"); window.location.href = "employee.php";</script>';
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    ?>
    <div style="margin-left:300px">
    <h1>Edit Employee</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <label for="edit_name">Name:</label>
        <input type="text" id="edit_name" name="edit_name" value="<?php echo isset($edit_name) ? $edit_name : ''; ?>" required><br>
        <label for="edit_description">Description:</label><br>
        <textarea id="edit_description" name="edit_description" rows="4" cols="50"><?php echo isset($edit_description) ? $edit_description : ''; ?></textarea><br>
        <label for="current_image">Current Image:</label><br>
        <?php if(isset($edit_image) && !empty($edit_image)): ?>
            <img src="uploads/<?php echo $edit_image; ?>" alt="Current Image" style="max-width: 200px;"><br>
        <?php endif; ?>
        <label for="edit_image">Upload New Image:</label>
        <input type="file" id="edit_image" name="edit_image"><br>
        <input type="submit" name="update" value="Update">
    </form>
</div>
    <?php
    $conn->close();
    ?>
</body>
</html>
