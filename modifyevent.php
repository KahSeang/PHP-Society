<?php
include 'adminheader.php';
include 'adminsidebar.php';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Initialize variables to store event details
$type_name = $location = $description = $start_time = $end_time = $image_1 = $image_2 = $image_3 = $image_4 = $image_5 = $event_name = $price = $image = $event_time = $hover_image = $soft_skill = $category_id = $available = "";

// Check if event ID is provided in the URL
if(isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Retrieve event details from the database
    $sql = "SELECT * FROM event WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $type_name = $row['type_name'];
        $location = $row['event_location'];
        $description = $row['event_description'];
        $start_time = $row['start_time'];
        $end_time = $row['end_time'];
        $image_1 = $row['event_imgside1'];
        $image_2 = $row['event_imgside2'];
        $image_3 = $row['event_imgside3'];
        $image_4 = $row['event_imgside4'];
        $image_5 = $row['event_imgside5'];
        $event_name = $row['event_name'];
        $price = $row['event_price'];
        $image = $row['event_img'];
        $event_time = $row['event_time'];
        $hover_image = $row['event_hover'];
        $soft_skill = $row['event_softskill'];
        $category_id = $row['category_id'];
        $available = $row['event_available'];
    } else {
        echo "Event not found.";
    }

    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $type_name = $_POST['type_name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $event_name = $_POST['event_name'];
    $price = $_POST['price'];
    $event_time = $_POST['event_time'];
    $soft_skill = $_POST['soft_skill'];
    $category_id = $_POST['category_id'];
    $available = $_POST['available'];

    // File Uploads
    $image_1 = uploadFile('image_1', 11500000, $image_1);
    $image_2 = uploadFile('image_2', 11500000, $image_2);
    $image_3 = uploadFile('image_3', 11500000, $image_3);
    $image_4 = uploadFile('image_4', 11500000, $image_4);
    $image_5 = uploadFile('image_5', 11500000, $image_5);
    $image = uploadFile('main_image', 11500000, $image);
    $hover_image = uploadFile('hover_image', 11500000, $hover_image);

    // Check if any data is changed
    if ($type_name != '' && $location != '' && $description != '' && $start_time != '' && $end_time != '' && $event_name != '' && $price != '' && $event_time != '' && $soft_skill != '' && $category_id != '' && $available != '') {
        // Update event details in the database
        $sql = "UPDATE event SET type_name=?, event_location=?, event_description=?, start_time=?, end_time=?, event_imgside1=?, event_imgside2=?, event_imgside3=?, event_imgside4=?, event_imgside5=?, event_name=?, event_price=?, event_img=?, event_time=?, event_hover=?, event_softskill=?, category_id=?, event_available=? WHERE event_id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssssssssssssssiii", $type_name, $location, $description, $start_time, $end_time, $image_1, $image_2, $image_3, $image_4, $image_5, $event_name, $price, $image, $event_time, $hover_image, $soft_skill, $category_id, $available, $event_id);

        if ($stmt->execute()) {
echo "<script>alert('Update Successful'); window.location.href = 'editvent.php';</script>";
        } else {
            $update_status = "Error updating event: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $update_status = "No changes detected.";
    }
}

// Function to handle file upload
function uploadFile($inputName, $maxFileSize, $currentImage) {
    if (!empty($_FILES[$inputName]['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES[$inputName]['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check file size
        if ($_FILES[$inputName]['size'] > $maxFileSize) {
            echo "Sorry, your file is too large.";
            return $currentImage;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($fileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            return $currentImage;
        }

        // Upload file to server
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
            return $targetFilePath;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return $currentImage;
        }
    } else {
        // No file uploaded, return the current image path
        return $currentImage;
    }
}
?>

<div class="content">
    <h2>Edit Event</h2>
    <?php if(isset($update_status)) { ?>
        <div><?php echo $update_status; ?></div>
    <?php } ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $event_id); ?>" method="POST" enctype="multipart/form-data" class="edit-event-form">
        <!-- Populate form fields with retrieved event details -->
        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" value="<?php echo $event_name; ?>" required><br><br>
        
        <label for="type_name">Type Name:</label>
        <input type="text" id="type_name" name="type_name" value="<?php echo $type_name; ?>" required><br><br>
 
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo $location; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required><?php echo $description; ?></textarea><br><br>
        
        <label for="category_id">Category ID:</label>
        <input type="text" id="category_id" name="category_id" value="<?php echo $category_id; ?>" required><br><br>

        <label for="start_time">Start Time:</label>
        <input type="date" id="start_time" name="start_time" value="<?php echo $start_time; ?>" required><br><br>

        <label for="end_time">End Time:</label>
        <input type="date" id="end_time" name="end_time" value="<?php echo $end_time; ?>" required><br><br>

        <label for="available">Available:</label>
        <input type="number" id="available" name="available" value="<?php echo $available; ?>" required><br><br>

        <label for="image_1">Image 1 Path:</label>
        <input type="file" id="image_1" name="image_1" accept="image/*"><br><br>

        <label for="image_2">Image 2 Path:</label>
        <input type="file" id="image_2" name="image_2" accept="image/*"><br><br>

        <label for="image_3">Image 3 Path:</label>
        <input type="file" id="image_3" name="image_3" accept="image/*"><br><br>

        <label for="image_4">Image 4 Path:</label>
        <input type="file" id="image_4" name="image_4" accept="image/*"><br><br>

        <label for="image_5">Image 5 Path:</label>
        <input type="file" id="image_5" name="image_5" accept="image/*"><br><br>


        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>" required><br><br>

        <label for="event_time">Event Time:</label>
        <input type="text" id="event_time" name="event_time" value="<?php echo $event_time; ?>" required><br><br>

        <label for="soft_skill">Soft Skill:</label>
        <input type="number" id="soft_skill" name="soft_skill" value="<?php echo $soft_skill; ?>" required><br><br>

        <label for="main_image">Main Event Image Path:</label>
        <input type="file" id="main_image" name="main_image" accept="image/*"><br><br>
        
        <label for="hover_image">Hover Image Path:</label>
        <input type="file" id="hover_image" name="hover_image" accept="image/*"><br><br>
        
        <input type="submit" value="Save Changes">
    </form>
</div>

<?php include 'admin_footer.php'; ?>
