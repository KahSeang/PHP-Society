<?php session_start();
require_once 'session_check.php'; 


include 'db_connection.php'; // Include the database connection file

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        * {
            font-family: Nunito, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
        }
                         body {
    background-image: url('Websystemphp/10.webp');
    background-size: cover;
    background-position: center;
}   

        .fade-in {
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .responsive-container-block {
            min-height: 75px;
            height: fit-content;
            width: 100%;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            margin: 0 auto;
            justify-content: flex-start;
        }

        .responsive-container-block.bigContainer {
            padding: 10px 20px;
            margin: 0 auto;
        }

        .responsive-container-block.Container {
            max-width: 1320px;
            align-items: center;
            justify-content: center;
            margin: 80px auto;
        }

        .responsive-container-block.leftSide {
            width: auto;
            align-items: flex-start;
            padding: 10px 0;
            flex-direction: column;
            margin: 0 auto;
            max-width: 300px;
        }

        .text-blk.heading {
            font-size: 40px;
            line-height: 64px;
            font-weight: 900;
            color: white    ;
            margin: 0;
        }

        .text-blk.subHeading {
            font-size: 18px;
            line-height: 25px;
            margin: 0;
        }

        .responsive-container-block.rightSide {
            width: 675px;
            position: relative;
            display: flex;
            height: 700px;
            min-height: auto;
        }

        .number1img,
        .number2img,
        .number3img,
        .number4img,
        .number5img,
        .number6img,
        .number7img {
            position: absolute;
        }

        .number1img {
            margin: 39% 80% 29% 0;
            width: 20%;
            height: 32%;
        }

        .number2img {
            margin: 19% 42% 42% 23%;
            width: 35%;
            height: 39%;
        }

        .number3img {
            margin: 62% 64% 30% 23%;
            width: 13%;
            height: 21%;
        }

        .number4img {
            margin: 62% 27% 0 39%;
            width: 34%;
            height: 33%;
        }

        .number5img {
            margin: 38% 27% 41% 60%;
            width: 13%;
            height: 21%;
        }

        .number6img {
            margin: 0 3% 67% 62%;
            width: 35%;
            height: 33%;
        }

        .number7img {
            margin: 40% 0 18% 75%;
            width: 25%;
            height: 42%;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            overflow: hidden;
            padding: 0 20px;
        }

        .section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px;
            background: #C2CAD0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .section:nth-child(even) .section-content {
            order: 2;
        }

        .section:hover {
            transform: translateY(-5px);
        }

        .section .section-image {
            border-radius: 8px 0 0 8px;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .section-content {
            flex: 1;
            max-width: 65%;
            padding: 20px;
        }

        .section h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .section p {
            color: #666;
        }

        .google-map-container {
            position: relative;
            overflow: hidden;
            padding-top: 30%;
            margin-bottom: 50px;
        }

        .google-map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        @media (max-width: 1024px) {
            .responsive-container-block.Container {
                flex-direction: column-reverse;
            }

            .text-blk.heading {
                text-align: center;
                max-width: 370px;
            }

            .text-blk.subHeading {
                text-align: center;
            }

            .responsive-container-block.leftSide {
                align-items: center;
                max-width: 480px;
            }

            .responsive-container-block.rightSide {
                margin: 0 auto 70px auto;
            }
        }

        @media (max-width: 768px) {
            .responsive-container-block.rightSide {
                width: 450px;
                height: 450px;
            }

            .responsive-container-block.leftSide {
                max-width: 450px;
            }

            .section .section-image,
            .section .section-content {
                width: 100%;
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 500px) {
            .number1img,
            .number2img,
            .number3img,
            .number4img,
            .number5img,
            .number6img,
            .number7img {
                display: none;
            }

            .responsive-container-block.rightSide {
                width: 100%;
                height: 250px;
                margin: 0 auto 100px auto;
            }

            .text-blk.heading {
                font-size: 25px;
                line-height: 40px;
                max-width: 370px;
            }

            .text-blk.subHeading {
                font-size: 14px;
                line-height: 25px;
            }

            .responsive-container-block.leftSide {
                width: 100%;
            }
        }
        .employee-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #FFF;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.team-heading {
    color: #333;
    margin-bottom: 20px;
}

.employee-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.employee-profile {
    flex: 0 1 20%; /* Adjusts the width for five profiles per row */
    margin: 10px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    padding: 10px;
    background: #EFEFEF;
    border-radius: 8px;
}

.profile-image {
    width: 100%;
    height: auto;
    border-radius: 50%;
    margin-bottom: 10px;
}

.role {
    font-weight: bold;
    color: #555;
}

@media (max-width: 1024px) {
    .employee-profile {
        flex: 0 1 33%; /* Adjusts the width for three profiles per row */
    }
}

@media (max-width: 768px) {
    .employee-profile {
        flex: 0 1 50%; /* Adjusts the width for two profiles per row */
    }
}

@media (max-width: 480px) {
    .employee-profile {
        flex: 0 1 100%; /* Full width for a single profile per row */
    }
}
.section {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .section-image {
            flex: 0 0 200px;
            height: 200px;
            border-radius: 8px;
            margin-right: 20px;
        }

        .section-content {
            flex: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .section {
                flex-direction: column;
                text-align: center;
            }

            .section-image {
                margin: 0 auto 20px auto;
            }
        }
    </style>
</head>

<body>
<?php include('header.php'); ?>

<?php
include('db_connection.php');

$query = "SELECT img_1, img_2, img_3, img_4, img_5, img_6 FROM aboutus WHERE id = 1"; 
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $image1 = $row['img_1'];
    $image2 = $row['img_2'];
    $image3 = $row['img_3'];
    $image4 = $row['img_4'];
    $image5 = $row['img_5'];
    $image6 = $row['img_6'];
} else {
    $image1 = 'default_image1.jpg';
    $image2 = 'default_image2.jpg';
    $image3 = 'default_image3.jpg';
    $image4 = 'default_image4.jpg';
    $image5 = 'default_image5.jpg';
    $image6 = 'default_image6.jpg';
}
?>

<div class="responsive-container-block bigContainer fade-in">
    <div class="responsive-container-block Container">
        <div class="responsive-container-block leftSide">
       <?php 
$sql = "SELECT * FROM aboutus";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    if($row = mysqli_fetch_assoc($result)) {
?>
    <p class="text-blk heading"><?php echo $row['h1']; ?></p>
    <p class="text-blk subHeading"><?php echo $row['descr']; ?> At OK Society, we believe in the power of events to connect and inspire. With a focus on innovation, attention to detail, and a commitment to excellence, we strive to exceed our clients' expectations and transform visions into reality. Join us as we make your next event an unforgettable experience!</p>
<?php
    } 
} 
?>

        </div>
        <div class="responsive-container-block rightSide">
            <!-- Replace hardcoded image sources with PHP variables -->
            <img class="number1img" src="<?php echo $image1; ?>">
            <img class="number2img" src="<?php echo $image2; ?>">
            <img class="number3img" src="<?php echo $image3; ?>">
            <img class="number5img" src="<?php echo $image4; ?>">
            <img class="number4img" src="<?php echo $image5; ?>">
            <img class="number7img" src="<?php echo $image6; ?>">
        </div>
    </div>
</div>

<div class="employee-container fade-in">
    <h2 class="team-heading">Meet Our Team</h2>
    <div class="employee-grid">
        <?php 
        include('db_connection.php');
        $query = "SELECT * FROM employee";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="employee-profile">';
echo '<img src="uploads/' . htmlspecialchars($row['emp_photo']) . '" alt="' . htmlspecialchars($row['name']) . '" class="profile-image" style="width: 200px; height: 200px;">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No employees found.</p>';
        }
        ?>
    </div>
</div><?php
include 'db_connection.php';

$sql = "SELECT * FROM aboutus";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo '<div class="container fade-in">';
        echo '    <div class="section elevate-moments">';
        echo '<img src="' . $row["moment1_img"] . '" alt="Elevate Moments" class="section-image">';
        echo '        <div class="section-content">';
        echo '<h2>Moments</h2>';
        echo '<p>' . $row['momentdesc1'] . '</p>';
        echo '        </div>';
        echo '    </div>';

        // Output other sections in a similar manner
        // Section for Create Beauty
        echo '    <div class="section create-beauty">';
        echo '        <img src="' . $row["moment2_img"] . '" alt="Create Beauty" class="section-image">';
        echo '        <div class="section-content">';
        echo '            <h2>Create Event</h2>';
        echo '            <p>' . $row['momentdesc2'] . '</p>';
        echo '        </div>';
        echo '    </div>';

        // Section for Facilitate Connection
        echo '    <div class="section facilitate-connection">';
        echo '        <img src="' . $row["moment3_img"] . '" alt="Facilitate Connection" class="section-image">';
        echo '        <div class="section-content">';
        echo '            <h2>Facilitate Connection</h2>';
        echo '            <p>' . $row['momentdesc3'] . '</p>';
        echo '        </div>';
        echo '    </div>';
        
        echo '</div>';
    }
}
?>


<?php 
echo '<h1>our location</h1>';
include 'location.php';
?>
<?php include('footer.php'); ?>
</body>
</html>
