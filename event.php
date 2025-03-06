<?php session_start();
require_once 'session_check.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <?php 
if (!empty($_GET['search'])) {
    if (isset($_COOKIE['search_query'])) {
        $searchQueries = explode('|', $_COOKIE['search_query']);
    } else {
        $searchQueries = [];
    }

    $searchQueries[] = $_GET['search'];

    setcookie('search_query', implode('|', $searchQueries), time() + (86400 * 30), "/"); // Cookie will expire in 30 days
}


?>
    <meta charset="UTF-8">
    <title></title>
    <style>
                 body {
    background-image: url('Websystemphp/10.webp');
    background-size: cover;
    background-position: center;
    p
}       .event-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; /* Adjust gap between evwebent cards */
            padding: 20px;
        }

        .event {
            position: relative;
    background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden; 
            width: calc(33.333% - 20px); 
            max-width: 400px; 
            opacity: 0;
            transform: translateY(20px); 
            transition: opacity 0.5s ease, transform 0.5s ease; /* Smooth transition */
        }

        .event.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .event img {
            width: 100%;
            height: 300px; /* Adjust this value to make the images bigger */
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: transform 0.5s ease; /* Smooth transition for hover effect */
        }

        .event:hover img {
            transform: scale(1.0); /* Scale up image on hover */
        }

        /* Hover overlay */
        .event .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.5s ease; /* Smooth transition for overlay */
            z-index: 1; /* Ensure overlay is above image */
        }

        .event:hover .hover-overlay {
            opacity: 1; /* Show overlay on hover */
        }

        .event .hover-text {
            color: #fff;
            font-size: 20px;
            text-align: center;
        }

        /* Rest of your CSS */

        .event h2 {
            font-size: 18px;
            margin: 0;
            color: #333;
            position: relative; /* Ensure z-index works */
            z-index: 1; /* Ensure text is above overlay */
        }

        .search-container {
            margin-bottom: 20px;
            float: right; /* Move the search container to the right */

        }

        .search-container input[type=text] {
            padding: 10px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px;
            margin-top: 8px;
            background: #333;
            color: white;
            font-size: 17px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
      
        .event-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .event-info {
            padding: 15px;
            text-align: center;
        }

        .event-info h5 {
            margin: 10px 0;
        }

        .event-info p {
            margin: 5px 0;
        font-size: 16px;
        color: #444;
             text-align: center;
        }

        .event-info span {
            font-weight: bold;
            color: #333;
             text-align: center;
        }

        /* Fading in elements */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 2s;
        }

        /* Icon styles */
        .event-icon {
            margin-right: 7px;
            vertical-align: middle;
        }

        /* Responsive grid */
        @media (max-width: 1200px) {
            .event-card { width: calc(33.333% - 30px); }
        }

        @media (max-width: 992px) {
            .event-card { width: calc(50% - 30px); }
        }

        @media (max-width: 768px) {
            .event-card { width: 100%; }
        }  
         @keyframes fadeInUp {
       from {
           transform: translate3d(0, 20px, 0);
           opacity: 0;
       }
       to {
           transform: translate3d(0, 0, 0);
           opacity: 1;
       }
   }

   .event {
       animation: fadeInUp 0.5s ease forwards;
       /* Ensure that animation only plays once */
       animation-fill-mode: both;
   }
   
    </style>
    <style>
    /* Countdown timer styles */
    #countdown {
        text-align: center;
        font-size: 20px;
        margin-bottom: 20px;
    background-color: rgba(255, 255, 255, 0.8); /* White background with 80% opacity */
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #countdown .timer-section {
        display: inline-block;
        margin-right: 20px;
    }

    #countdown .timer-section:last-child {
        margin-right: 0;
    }

    #countdown .timer-label {
        font-size: 16px;
        color: #555;
    }

    #countdown .timer-value {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    .event ion-icon{
      margin-right: 10px;  
    }
.event p ,h2 {
    font-family: 'Open Sans', sans-serif;
}
</style>

</head>
<body>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">

<?php include('header.php'); ?>
<?php include('eventheader.php'); 
    include_once 'session_check.php';
?>

<div class="search-container">
    <form action="event.php" method="GET">
        <input type="text" placeholder="Search.." name="search">
        <button type="submit">Search</button>
    </form>
</div>
<br><br><br> <br>
<div id="countdown">
    <div class="timer-section">
        <span class="timer-value" id="days">00</span>
        <span class="timer-label">Days</span>
    </div>
    <div class="timer-section">
        <span class="timer-value" id="hours">00</span>
        <span class="timer-label">Hours</span>
    </div>
    <div class="timer-section">
        <span class="timer-value" id="minutes">00</span>
        <span class="timer-label">Minutes</span>
    </div>
    <div class="timer-section">
        <span class="timer-value" id="seconds">00</span>
        <span class="timer-label">Seconds</span>
    </div>
</div>
<script>
   
    var countDownDate = new Date("2025-01-01").getTime();

    var x = setInterval(function() {
        var now = new Date().getTime();

        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        days = days < 10 ? "0" + days : days;
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Display the countdown timer
        document.getElementById("days").textContent = days;
        document.getElementById("hours").textContent = hours;
        document.getElementById("minutes").textContent = minutes;
        document.getElementById("seconds").textContent = seconds;

        // If the countdown is over, display a message
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>

<div class="event-container">
    
    <?php

    $connection = mysqli_connect('localhost', 'root', '', 'login');

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_GET['category_id']) && $_GET['category_id'] == 1) {
        $sql = "SELECT * FROM event";
    } elseif (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connection, $_GET['search']);
        $sql = "SELECT * FROM event WHERE event_name LIKE '%$search%' ";
                  

    } else {
        if (isset($_GET['category_id'])) {
            $category_id = mysqli_real_escape_string($connection, $_GET['category_id']);
            $sql = "SELECT * FROM event WHERE category_id = '$category_id'";
        } else {
            $sql = "SELECT * FROM event";
        }
    }

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="event">
                <a href="event_detail.php?event_id=<?php echo $row['event_id']; ?>">
                    <img class="normal-image" src="<?php echo $row['event_img']; ?>" alt="<?php echo $row['event_name']; ?>">
                    <img class="hover-image" src="<?php echo $row['event_hover']; ?>" alt="<?php echo $row['event_name']; ?>" style="display: none;">
                    <div class="hover-overlay">
                        <div class="hover-text"><?php echo $row['event_name']; ?></div>
                    </div>
                </a>
                <h2 style="text-align: center"><?php echo $row['event_name']; ?></h2>

                <p> <?php echo '<ion-icon name="list-circle-outline"></ion-icon>' .   $row['type_name']; ?></p>
                <p><?php echo '<ion-icon name="time-outline"></ion-icon>' .   $row['event_time']; ?></p>
               <p> <?php echo ' <ion-icon name="calendar-outline"></ion-icon>' .   $row['start_time']; ?></p>
              <p>  <?php echo ' <ion-icon name="calendar-outline"></ion-icon>' .   $row['end_time']; ?></p>
            </div>

            <?php
        } 
    } else {
        echo "No events found";
    }

    mysqli_close($connection);
    ?>
</div>
<br><br>

<?php include('footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const events = document.querySelectorAll('.event');
        events.forEach((event, index) => {
            setTimeout(() => {
                event.classList.add('animate');
            }, index * 100); // Adjust animation delay as needed
        });
    });

    // JavaScript to toggle between normal and hover images on mouseover/mouseout
    const eventCards = document.querySelectorAll('.event');
    eventCards.forEach(card => {
        const normalImage = card.querySelector('.normal-image');
        const hoverImage = card.querySelector('.hover-image');
        card.addEventListener('mouseover', () => {
            normalImage.style.display = 'none';
            hoverImage.style.display = 'block';
        });
        card.addEventListener('mouseout', () => {
            hoverImage.style.display = 'none';
            normalImage.style.display = 'block';
        });
    });
</script>
<script>
  document.querySelectorAll('.event').forEach((card, index) => {
    card.style.animationDelay = `${index * 100}ms`;

    const normalImage = card.querySelector('.normal-image');
    const hoverImage = card.querySelector('.hover-image');

    hoverImage.style.opacity = '0';

    card.addEventListener('mouseover', () => {
        normalImage.style.opacity = '0';
        hoverImage.style.opacity = '1';
    });

    card.addEventListener('mouseout', () => {
        normalImage.style.opacity = '1';
        hoverImage.style.opacity = '0';
    });
});


   document.querySelectorAll('a[href^="#"]').forEach(anchor => {
       anchor.addEventListener('click', function (e) {
           e.preventDefault();

           document.querySelector(this.getAttribute('href')).scrollIntoView({
               behavior: 'smooth'
           });
       });
   });
</script>
    
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
