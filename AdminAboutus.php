<style>
    img{
        width: 100px;
        height: 100px;
    }
</style>
<?php 
include 'adminheader.php'; 
include 'adminsidebar.php'; 
require 'db_connection.php'; 

// Fetch current values from the database
$query = "SELECT * FROM aboutus WHERE id = 1"; // Assuming id=1 for aboutus
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $currentData = mysqli_fetch_assoc($result);
} else {
    // Default values if no data found
    $currentData = [
        'h1' => '',
        'descr' => '',
        'img_1' => '',
        'img_2' => '',
        'img_3' => '',
        'img_4' => '',
        'img_5' => '',
        'img_6' => '',
        'moment1_img' => '',
        'moment2_img' => '',
        'moment3_img' => '',
        'momentdesc1' => '',
        'momentdesc2' => '',
        'momentdesc3' => '',
        'location' => '' // Add default location value
    ];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $h1 = $_POST['heading']; 
    $descr = $_POST['description'];
    $location = $_POST['location']; // Get the location value
    $id = 1; // Assuming this is the ID you want to update

    // File Uploads
    $img_1 = uploadFile('image1', 11500000, $currentData['img_1']);
    $img_2 = uploadFile('image2', 11500000, $currentData['img_2']);
    $img_3 = uploadFile('image3', 11500000, $currentData['img_3']);
    $img_4 = uploadFile('image4', 11500000, $currentData['img_4']);
    $img_5 = uploadFile('image5', 11500000, $currentData['img_5']);
    $img_6 = uploadFile('image6', 11500000, $currentData['img_6']);
    $moment1_img = uploadFile('moment_image1', 11500000, $currentData['moment1_img']);
    $moment2_img = uploadFile('moment_image2', 11500000, $currentData['moment2_img']);
    $moment3_img = uploadFile('moment_image3', 11500000, $currentData['moment3_img']);

    $momentdesc1 = $_POST['momentdesc1'];
    $momentdesc2 = $_POST['momentdesc2'];
    $momentdesc3 = $_POST['momentdesc3'];

    // Prepare and execute the update query
    $updateQuery = "UPDATE aboutus SET 
        h1 = ?, 
        descr = ?, 
        img_1 = ?, 
        img_2 = ?, 
        img_3 = ?, 
        img_4 = ?, 
        img_5 = ?, 
        img_6 = ?, 
        moment1_img = ?, 
        moment2_img = ?, 
        moment3_img = ?, 
        momentdesc1 = ?, 
        momentdesc2 = ?, 
        momentdesc3 = ?, 
        location = ? 
        WHERE id = ?";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssssssssssssssi', $h1, $descr, $img_1, $img_2, $img_3, $img_4, $img_5, $img_6, $moment1_img, $moment2_img, $moment3_img, $momentdesc1, $momentdesc2, $momentdesc3, $location, $id);
    $stmt->execute();

    echo '<script>alert("Record updated successfully"); window.location.href = "AdminAboutus.php";</script>';
}
?>

<div class="content">
    <div class="container-fluid">
        <h2>EDIT ABOUT US</h2>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <!-- Input for H1 -->
            <div class="form-group">
                <label for="heading">Heading (H1):</label>
                <input type="text" class="form-control" id="heading" name="heading" value="<?php echo $currentData['h1']; ?>">
            </div>

            <!-- Input for Description -->
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5"><?php echo $currentData['descr']; ?></textarea>
            </div>

            <!-- Input for Location -->
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $currentData['location']; ?>">
            </div>

            <!-- Input for Images -->
            <?php for ($i = 1; $i <= 6; $i++) { ?>
                <div class="form-group">
                    <label for="image<?php echo $i; ?>">Image <?php echo $i; ?>:</label>
                    <input type="file" class="form-control-file" id="image<?php echo $i; ?>" name="image<?php echo $i; ?>">
                    <?php if (!empty($currentData['img_'.$i])) { ?>
                        <img src="<?php echo $currentData['img_'.$i]; ?>" alt="Current Image <?php echo $i; ?>" style="max-width: 200px;">
                        <input type="hidden" name="current_img<?php echo $i; ?>" value="<?php echo $currentData['img_'.$i]; ?>">
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Input for Moment Images -->
            <?php for ($i = 1; $i <= 3; $i++) { ?>
                <div class="form-group">
                    <label for="moment_image<?php echo $i; ?>">Moment Image <?php echo $i; ?>:</label>
                    <input type="file" class="form-control-file" id="moment_image<?php echo $i; ?>" name="moment_image<?php echo $i; ?>">
                    <?php if (!empty($currentData['moment'.$i.'_img'])) { ?>
                        <img src="<?php echo $currentData['moment'.$i.'_img']; ?>" alt="Current Moment Image <?php echo $i; ?>" style="max-width: 200px;">
                        <input type="hidden" name="current_moment_img<?php echo $i; ?>" value="<?php echo $currentData['moment'.$i.'_img']; ?>">
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Input for Moment Descriptions -->
            <?php for ($i = 1; $i <= 3; $i++) { ?>
                <div class="form-group">
                    <label for="momentdesc<?php echo $i; ?>">Moment Description <?php echo $i; ?>:</label>
                    <input type="text" class="form-control" id="momentdesc<?php echo $i; ?>" name="momentdesc<?php echo $i; ?>" value="<?php echo $currentData['momentdesc'.$i]; ?>">
                </div>
            <?php } ?>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

<?php 
// Function to handle file upload
function uploadFile($inputName, $maxFileSize, $currentImagePath) {
    // Check if a new file has been uploaded
    if (!empty($_FILES[$inputName]['name'])) {
        // Upload the new file
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES[$inputName]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES[$inputName]["size"] > $maxFileSize) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only specific file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // Return the current image path
            return $currentImagePath;
        } else {
            // Attempt to upload the file
            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES[$inputName]["name"])) . " has been uploaded.";
                return $targetFile; // Return the path to the uploaded file
            } else {
                echo "Sorry, there was an error uploading your file.";
                return $currentImagePath; // Return the current image path if upload fails
            }
        }
    } else {
        // Return the current image path if no new file was uploaded
        return $currentImagePath;
    }
}
?>
