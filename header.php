<?php require_once 'session_check.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OK Society</title>
 <style>
        * {
            padding: 0;
            margin: 0;
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
font-family: 'Roboto', sans-serif;
        }
        body {
font-family: 'Roboto', sans-serif;
        }
        nav {
    background: linear-gradient(to bottom, #4e5160, #7d8095); /* Gradient background */
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
            margin: 0 18px;
            font-weight: bold;
            font-size: 20px;
        }

        nav li a {
            transition: all 0.5s ease-in-out;
            color: whitesmoke;
        }

        nav li a:hover {
            color: darkgray;
            font-weight: bold;
            text-decoration: none;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .button {
            padding: 9px 25px;
            border-radius: 50px;
            cursor: pointer;
            background-color: rgba(100, 196, 195, 0.9);
            color: rgba(0, 0, 0, 0.8);
            border: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: rgba(100, 196, 195, 1);
        }

        .button:focus {
            outline: none;
        }
   
   .custom-cursor {
    cursor: url('Websystemphp/cursor.png'), auto;
}

p {
    font-family: 'Open Sans', sans-serif;
}
h1 {
        font-family: 'Open Sans', sans-serif;

}
h2{
        font-family: 'Open Sans', sans-serif;

}
h3{    font-family: 'Open Sans', sans-serif;

}
h4{
        font-family: 'Open Sans', sans-serif;

}
h5{
        font-family: 'Open Sans', sans-serif;

}
    </style>
</head>
<body>
          <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">

  <?php   if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    // Now you can use $userId as needed
} else {
    // User is not logged in, handle accordingly
}
?>
    <nav>

  <span>
            <img src="oklogo.png" style="width:100px;height:100px;margin-left:20px;">
        </span>        <ul>
            <li><a href="home.php?user_id=<?php echo $_SESSION['user_id']; ?>">Home</a></li>
            <li><a href="aboutus.php?user_id=<?php echo $_SESSION['user_id']; ?>">About Us</a></li>
            <li><a href="event.php?user_id=<?php echo $_SESSION['user_id']; ?>">Events</a></li>
            <li><a href="feedback.php?user_id=<?php echo $_SESSION['user_id']; ?>">Feedback</a></li>
            <li><a href="event_history.php?user_id=<?php echo $_SESSION['user_id']; ?>">History</a></li>
            <li><a href="userprofile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
</li>
            <li><a href="favorite_events.php?user_id=<?php echo $_SESSION['user_id']; ?>">Favourite</a></li>
            <li><a href="cart.php?user_id=<?php echo $_SESSION['user_id']; ?>">Cart</a></li>
                        <li><a href="moment.php?user_id=<?php echo $_SESSION['user_id']; ?>">Moment</a></li>
                                                <li><a href="chat.php?user_id=<?php echo $_SESSION['user_id']; ?>">Chat Box</a></li>
                                                                                                <li><a href="certificate.php?user_id=<?php echo $_SESSION['user_id']; ?>">SoftSkill</a></li>


                        

            
    
    <li><a href="login.php"><button class="button">LogIn</button></a></li>


        </ul>
    </nav>
</body>
 
</html>
