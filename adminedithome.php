<?php
session_start();
include 'adminheader.php';
include 'adminsidebar.php';

// Database credentials and connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$currentData = [];

// Fetch the current data from the database to auto-fill the form
$sql = "SELECT * FROM home WHERE home_id=1";
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    $currentData = $result->fetch_assoc();
} else {
    echo "No data to display";
}

// Function to handle file uploads
function uploadFile($inputName, $destinationPath, $existingPath) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
        $tmp_name = $_FILES[$inputName]['tmp_name'];
        $fileName = time() . '_' . $_FILES[$inputName]['name']; // Prefix the file name with the current timestamp
        $path = $destinationPath . $fileName;
        if (move_uploaded_file($tmp_name, $path)) {
            return $path;
        } else {
            echo "Failed to upload file: " . htmlspecialchars($_FILES[$inputName]['name']);
        }
    }
    return $existingPath;
}

// Check if the submit button was pressed
if (isset($_POST['submit'])) {
    // Fetch all text inputs
    $home_title = $_POST['home_title'];
    $home_description = $_POST['home_description'];

    // Fetch and upload all image files, pass the current data as the third parameter
    $home_poster_path = uploadFile('home_poster', "Websystemphp/home/", $currentData['home_poster'] ?? null);
    $home_video_path = uploadFile('home_video', "Websystemphp/home/", $currentData['home_video'] ?? null);
    $pj_img1_path = uploadFile('pj_img1', "Websystemphp/home/", $currentData['pj_img1'] ?? null);
    $pj_img2_path = uploadFile('pj_img2', "Websystemphp/home/", $currentData['pj_img2'] ?? null);
    $pj_img3_path = uploadFile('pj_img3', "Websystemphp/home/", $currentData['pj_img3'] ?? null);
    $award_img_path = uploadFile('award_img', "Websystemphp/home/", $currentData['award_img'] ?? null);

    // Fetch all project headers and descriptions
    $pj_h1_1 = $_POST['pj_h1_1'];
    $pj_h1_2 = $_POST['pj_h1_2'];
    $pj_h1_3 = $_POST['pj_h1_3'];
    $pj_des1 = $_POST['pj_des1'];
    $pj_des2 = $_POST['pj_des2'];
    $pj_des3 = $_POST['pj_des3'];
    $award_h1 = $_POST['award_h1'];

    // Prepare and bind the SQL statement to prevent SQL injection
    $sql = "UPDATE home SET home_poster=?, home_title=?, home_description=?, home_video=?, 
            pj_img1=?, pj_img2=?, pj_img3=?, pj_des1=?, pj_des2=?, pj_des3=?, 
            pj_h1_1=?, pj_h1_2=?, pj_h1_3=?, award_img=?, award_h1=? 
            WHERE home_id=1";
    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssssssssssss", 
        $home_poster_path, $home_title, $home_description, $home_video_path,
        $pj_img1_path, $pj_img2_path, $pj_img3_path, $pj_des1, $pj_des2, $pj_des3,
        $pj_h1_1, $pj_h1_2, $pj_h1_3, $award_img_path, $award_h1);
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$connection->close();
?>
<style>
    img {
        width: 150px; /* Adjust this value as needed */
        height: 150px; /* Adjust this value as needed */
    }
</style>
    <div class="content">
    <div class="container-fluid">
        <form action="adminedithome.php" method="post" enctype="multipart/form-data">
            HOME POSTER: <input type="file" id="home_poster" name="home_poster"><br><br>
            Current Poster: <?php echo isset($currentData['home_poster']) ? '<img src="'.$currentData['home_poster'].'" alt="Current Poster">' : 'No poster'; ?><br><br>
            
            HOME TITLE: <input type="text" id="home_title" name="home_title" value="<?php echo htmlspecialchars($currentData['home_title'] ?? ''); ?>"><br><br>
            
            HOME DESCRIPTION: <textarea id="home_description" name="home_description"><?php echo htmlspecialchars($currentData['home_description'] ?? ''); ?></textarea><br><br>
            
            HOME VIDEO: <input type="file" id="home_video" name="home_video"><br><br>
            Current Video: <?php echo isset($currentData['home_video']) ? $currentData['home_video'] : 'No video'; ?><br><br>
            
            HOME PRJ IMG 1: <input type="file" id="pj_img1" name="pj_img1"><br><br>
            Current Image 1: <?php echo isset($currentData['pj_img1']) ? '<img src="'.$currentData['pj_img1'].'" alt="Current Image 1">' : 'No image'; ?><br><br>
            
            HOME PRJ IMG 2: <input type="file" id="pj_img2" name="pj_img2"><br><br>
            Current Image 2: <?php echo isset($currentData['pj_img2']) ? '<img src="'.$currentData['pj_img2'].'" alt="Current Image 2">' : 'No image'; ?><br><br>
            
            HOME PRJ IMG 3: <input type="file" id="pj_img3" name="pj_img3"><br><br>
            Current Image 3: <?php echo isset($currentData['pj_img3']) ? '<img src="'.$currentData['pj_img3'].'" alt="Current Image 3">' : 'No image'; ?><br><br>
            
            HOME PRJ H1 1: <input type="text" id="pj_h1_1" name="pj_h1_1" value="<?php echo htmlspecialchars($currentData['pj_h1_1'] ?? ''); ?>"><br><br>
            
            HOME PRJ H1 2: <input type="text" id="pj_h1_2" name="pj_h1_2" value="<?php echo htmlspecialchars($currentData['pj_h1_2'] ?? ''); ?>"><br><br>
            
            HOME PRJ H1 3: <input type="text" id="pj_h1_3" name="pj_h1_3" value="<?php echo htmlspecialchars($currentData['pj_h1_3'] ?? ''); ?>"><br><br>
            
            HOME PRJ DESC 1: <input type="text" id="pj_des1" name="pj_des1" value="<?php echo htmlspecialchars($currentData['pj_des1'] ?? ''); ?>"><br><br>
            
            HOME PRJ DESC 2: <input type="text" id="pj_des2" name="pj_des2" value="<?php echo htmlspecialchars($currentData['pj_des2'] ?? ''); ?>"><br><br>
            
            HOME PRJ DESC 3: <input type="text" id="pj_des3" name="pj_des3" value="<?php echo htmlspecialchars($currentData['pj_des3'] ?? ''); ?>"><br><br>
            
            HOME ARW TITLE: <input type="text" id="award_h1" name="award_h1" value="<?php echo htmlspecialchars($currentData['award_h1'] ?? ''); ?>"><br><br>
            
            HOME ARW IMG: <input type="file" id="award_img" name="award_img"><br><br>
            Current Award Image: <?php echo isset($currentData['award_img']) ? '<img src="'.$currentData['award_img'].'" alt="Current Award Image">' : 'No image'; ?><br><br>
     

            <input type="submit" name="submit" value="Update"><br><br>
        </form>
    </div>
</div>



    <?php include 'admin_footer.php'; ?>
