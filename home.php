<?php
session_start();

if(isset($_SESSION['username'])){
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id']; 
    } else {
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OK Society</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        
   * {
    padding: 0;
    margin: 0;
    text-decoration: none;
    list-style: none;
    box-sizing: border-box;
    font-family: cursive;
}

body {
    font-family: serif;
}
                 body {
    background-size: cover;
    background-position: center;
}   
nav {
    background: #666666;
    height: 100px;
    width: 100%;
}

nav ul {
    float: right;
    margin-right: 20px;
}

nav ul li {
    display: inline-block;
    line-height: 100px;
    margin: 0 50px;
    font-weight: bold;
    font-size: 10px;
}

nav li a {
    transition: all 0.5s ease-in-out;
    color: rgba(0, 0, 0, 0.8);
}

nav li a:hover {
    color: darkgray;
    font-weight: bold;
    text-decoration: none;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.button {
    padding: 9px 25px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    background-color: rgba(100, 196, 195, 0.9);
    color: rgba(0, 0, 0, 0.8);
}

.button:hover {
    background-color: rgba(81, 147, 179, 0.7);
    color: white;
}

.detail-box {
    margin-left: 250px;
    position: absolute;
    margin-top: -430px;
}

 .card {
            width: 300px;
            height: 350px;
            display: inline-block;
            border-radius: 10px;
            padding: 15px;
            box-sizing: border-box;
            cursor: pointer;
            margin: 10px 15px;
            background-position: center;
            background-size: cover;
            transition: transform 0.5s;
            overflow: hidden;
            position: relative;
            text-align: center;
            margin-bottom: 20px; 
        }
.category-title {
    text-align: center;
    color: white; /* Changed to white for better visibility */
    font-size: 24px; /* Increased font size */
    font-weight: bold; /* Make the text bold */
    text-transform: uppercase; /* Optional: make the text uppercase */
    position: absolute; /* Position it over the image */
    width: 100%; /* Ensure the div spans the whole width of the card */
    bottom: 10px; /* Position the title at the bottom of the image */
    background: rgba(0, 0, 0, 0.5); /* Optional: add a semi-transparent background */
    padding: 5px 0; /* Add some padding */
    box-sizing: border-box;
     position: absolute;
    left: 50%;
    bottom: 20px; /* adjust as needed */
    transform: translateX(-50%); /* centers the title */
    background-color: rgba(0, 0, 0, 0.5); /* semi-transparent background for readability */
    width: 90%; /* adjust as needed */
    padding: 5px 0; /* some padding */
}

.category-title a {
    color: inherit; /* Use the same color as the .category-title */
    text-decoration: none;
}

/* Hover effect for the category link */
.category-title a:hover {
    color: #FFD700; /* Change color on hover */
}

.card h5 {
    margin-top: 10px;
    font-size: 20px;
    color: black;
}

.card:hover {
    transform: translateY(-10px);
}

.card:hover h5 a {
    color: #003333;
    font-size: 35px;
    transform: translate(-50%, -50%) rotate(0deg);
    white-space: nowrap;
}

.col {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

h3 {
    text-align: center;
    font-size: 40px;
    margin: 30px 0;
    position: relative;
}

h3::after {
    content: "";
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 5%;
    height: 4px;
    background-color: black;
    border-radius: 20px;
    margin-left: 720px;
    top: 60px;
}
/* Scroll animation */
@keyframes slideIn {
    0% {
        transform: translateY(20px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
.card:nth-child(1) {
    animation-delay: 0.2s;
}
.card:nth-child(2) {
    animation-delay: 0.4s;
}
.card:nth-child(3) {
    animation-delay: 0.6s;
}
.card:nth-child(4) {
    animation-delay: 0.8s;
}
.card:nth-child(5) {
    animation-delay: 1.0s;
}
.card:nth-child(6) {
    animation-delay: 1.2s;
}
.card:nth-child(7) { //spare
    animation-delay: 1.4s;
}
.card:nth-child(8) {
    animation-delay: 1.6s;
}
.card:nth-child(9) {
    animation-delay: 1.8s;
}
    </style>
    <!-- portfolio -->
    <style>
.projects-container {
  display: flex;
  flex-wrap: wrap;
  gap: 4px; /* Adjust the space between the images */
}

.project {
  position: relative;
  width: calc(33.333% - 4px); /* For three images per row, accounting for the gap */
  overflow: hidden; /* This will clip the images if they exceed the bounds */
}

.project img {
  width: 100%;
  height: auto;
  min-height: 100%; /* Ensures the image covers the height */
  object-fit: cover; /* Ensures the image covers the area, may be clipped */
  object-position: center; /* Centers the image within the container */
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8); /* Slightly transparent white background */
  color: #333;
  opacity: 0;
  transition: opacity 0.5s ease;
  display: flex;
  justify-content: center;
  align-items: center;
}

.project:hover .overlay {
  opacity: 1;
}

.text {
  text-align: center;
  font-size: 20px;
  padding: 0 10px; /* Adds padding within the overlay */
}
    </style>
</head>
<body>
    
    <?php include('header.php');?>
    
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "login";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM home";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row_home = mysqli_fetch_assoc($result);
        } else {
            $row_home = null; // Set $row_home to null if no rows are found
        }

        mysqli_close($conn);
    ?>

    <div class="hero_area">
        <img src="<?php echo isset($row_home['home_poster']) ? $row_home['home_poster'] : ''; ?>" style="display:inline-block;width: 100%;opacity: 1.5;height: 650px;position:relative;">
        <div class="detail-box">
            <?php
            if ($row_home !== null) {
            ?>
            <h1 style="font-weight:bold;font-size:55px;color:white;margin-right: 50px">
                <?php echo $row_home['home_title']; ?><br>
            </h1>
            <p style="color: honeydew;font-size: 25px">
                <?php echo $row_home['home_description']; ?><br> 
            </p>
            <?php
            } else {
                echo "No home details found.";
            }
            ?>
        </div>
    </div>

    <video controls width="600" muted autoplay style="width:100%; height: 900px;margin-top: -26px;">
        <?php
        // Check if $row_home is not null and contains a video URL
        if ($row_home !== null && !empty($row_home['home_video'])) {
        ?>
        <source src="<?php echo $row_home['home_video'];?>" type="video/mp4">
        <?php
        } else {
            echo "No video found.";
        }
        ?>
    </video>
    <div class="content-area">
    <h1 style="text-align: center;
  font-size: 36px; 
  color: #333; 
  margin-bottom: 20px">Our Projects</h1>
  <div class="projects-container">

  <div class="project">
    <img src="<?php echo isset($row_home['pj_img1']) ? $row_home['pj_img1'] : ''; ?>" alt="Project Image">
    <div class="overlay">
      <div class="text"><strong><?php echo isset($row_home['pj_h1_1']) ? $row_home['pj_h1_1'] : ''; ?></strong><br><br><?php echo isset($row_home['pj_des1']) ? $row_home['pj_des1'] : ''; ?></div>
    </div>
  </div>
  <div class="project">
    <img src="<?php echo isset($row_home['pj_img2']) ? $row_home['pj_img2'] : ''; ?>" alt="Project Image">
    <div class="overlay">
      <div class="text"><strong><?php echo isset($row_home['pj_h1_2']) ? $row_home['pj_h1_2'] : ''; ?></strong><br><br><?php echo isset($row_home['pj_des2']) ? $row_home['pj_des2'] : ''; ?></div>
    </div>
  </div>
  <div class="project">
    <img src="<?php echo isset($row_home['pj_img3']) ? $row_home['pj_img3'] : ''; ?>" alt="Project Image">
    <div class="overlay">
      <div class="text"><strong><?php echo isset($row_home['pj_h1_3']) ? $row_home['pj_h1_3'] : ''; ?></strong><br><br><?php echo isset($row_home['pj_des3']) ? $row_home['pj_des3'] : ''; ?></div>
    </div>
  </div>
</div>

    <h3>Categories</h3>
    
    <div class="col">
    <?php
    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT category_image, category_name FROM category where category_id <> 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card" style="background-image: url('<?php echo $row['category_image']; ?>');">
            <div class="category-title"><a href="event.php?name=<?php echo $row['category_name']; ?>&user_id=<?php echo $user_id; ?>"><?php echo $row['category_name']; ?></a></div>
        </div>
            <?php
        }
    } else {
        echo "No categories found.";
    }

    mysqli_close($conn);
    ?>
</div>
    <div>
        <h1 style="text-align:center"><?php echo isset($row_home['award_h1']) ? $row_home['award_h1'] : ''; ?></h1>
        <img src="<?php echo isset($row_home['award_img']) ? $row_home['award_img'] : ''; ?>"alt="award" style="width: 100% ;height: 500px;">
    </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <?php include('footer.php'); ?>
</body>
</html>
