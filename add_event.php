<?php
include 'adminheader.php';
include 'adminsidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "INSERT INTO event (type_name, event_location, event_description, start_time, end_time, event_imgside1, event_imgside2, event_imgside3, event_imgside4, event_imgside5, event_name, event_price, event_img, event_time, event_hover, event_softskill, category_id, event_available) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Error: " . $connection->error);
    }

    $stmt->bind_param("sssssssssssssssssi", $type_name, $location, $description, $start_time, $end_time, $image_1, $image_2, $image_3, $image_4, $image_5, $event_name, $price, $main_image, $event_time, $hover_image, $soft_skill, $category_id, $available);

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

    function handleFileUpload($image_field, &$uploaded_images)
    {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES[$image_field]["name"], PATHINFO_EXTENSION));
        // Generate a unique file name
        $target_file = $target_dir . uniqid('image_') . '.' . $imageFileType;

        $check = getimagesize($_FILES[$image_field]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES[$image_field]["size"] > 50000000) { 
                echo "Sorry, your file is too large.";
                return false;
            } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) { // Allowed image file extensions
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                return false;
            } else {
                if (move_uploaded_file($_FILES[$image_field]["tmp_name"], $target_file)) {
                    $uploaded_images[$image_field] = $target_file;
                    return true;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    return false;
                }
            }
        } else {
            echo "File is not an image.";
            return false;
        }
    }

    $uploaded_images = [];
    foreach (['image_1', 'image_2', 'image_3', 'image_4', 'image_5', 'main_image', 'hover_image'] as $image_field) {
        if (!handleFileUpload($image_field, $uploaded_images)) {
            die();
        }
    }

    foreach ($uploaded_images as $key => $image_path) {
        ${$key} = $image_path;
    }

    if ($stmt->execute()) {
        echo "<script>alert('New event added successfully.');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>


<div class="content">
    <h2>Add New Event</h2>

    <form action="add_event.php" method="POST" enctype="multipart/form-data" class="add-event-form">
        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" required><br><br>
        
        <label for="type_name">Type Name:</label>
        <input type="text" id="type_name" name="type_name" required><br><br>
 
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>
        
        <label for="category_id">Category ID:</label>
<select id="category_id" name="category_id" required>
    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "SELECT category_id, category_name FROM category";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['category_id'] != 1) {
                echo "<option value='" . $row['category_id'] . "'>" .$row['category_id'] . $row['category_name'] . "</option>";
            }
        }
    }
    ?>
</select><br><br>


        <label for="start_time">Start Time:</label>
        <input type="date" id="start_time" name="start_time" required><br><br>

        <label for="end_time">End Time:</label>
        <input type="date" id="end_time" name="end_time" required><br><br>

        <label for="available">Available:</label>
        <input type="number" id="available" name="available" required><br><br>

        <label for="image_1">Image 1:</label>
        <input type="file" id="image_1" name="image_1" accept="image/*" required><br><br>

        <label for="image_2">Image 2:</label>
        <input type="file" id="image_2" name="image_2" accept="image/*" required><br><br>

        <label for="image_3">Image 3:</label>
        <input type="file" id="image_3" name="image_3" accept="image/*" required><br><br>

        <label for="image_4">Image 4:</label>
        <input type="file" id="image_4" name="image_4" accept="image/*" required><br><br>

        <label for="image_5">Image 5:</label>
        <input type="file" id="image_5" name="image_5" accept="image/*" required><br><br>

        <label for="main_image">Main Event Image:</label>
        <input type="file" id="main_image" name="main_image" accept="image/*" required><br><br>

        <label for="hover_image">Hover Image:</label>
        <input type="file" id="hover_image" name="hover_image" accept="image/*" required><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br><br>

        <label for="event_time">Event Time:</label>
        <input type="text" id="event_time" name="event_time" required><br><br>

        <label for="soft_skill">Soft Skill:</label>
        <input type="number" id="soft_skill" name="soft_skill" required><br><br>

        <input type="submit" value="Submit">
    </form>
</div>

<?php include 'admin_footer.php'; ?>
