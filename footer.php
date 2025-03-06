<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        /* Footer styling */
        footer {
            background-color: #111;
            color: white;
            padding: 40px 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 80%;
            margin-bottom: 30px;
        }

        .footer-top > div,
        .footer-top .map {
            flex: 1;
        }

        .footer-top .company h1 {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .footer-top .company p {
            font-size: 24px;
            margin-bottom: 15px;
            color: grey;
        }

        .footer-top .company img {
            margin-right: 10px;
            border-radius: 20px;
            width: 50px;
            height: auto;
        }

       .socialIcons a img {
    border-radius: 50%;
    width: 50px; /* Adjust width */
    height: 50px; /* Adjust height */
    filter: invert(100%); /* Change color to white */
}

.socialIcons a:hover {
    transform: scale(1.1);
}


       
        .footerNav ul {
            list-style-type: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 0;
        }

        .footerNav ul li {
            padding: 5px 10px;
            font-size: 18px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .footerNav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footerNav ul li a:hover {
            color: #FFD700;
            opacity: 1;
        }

        .map iframe {
            width: 100%;
            max-width: 300px;
            height: 200px;
            border: none;
            margin-top: 20px;
        }

        .footerBottom {
            background-color: #000;
            width: 100%;
            padding: 20px;
            text-align: center;
        }

        .footerBottom p {
            margin: 0;
            opacity: 0.7;
        }

        .designer {
            font-weight: bold;
            color: #FFD700;
        }
    </style>
    
    <style>
  .contact-container {
    font-family: Arial, sans-serif; /* Adjust the font-family as needed */
    color: white; /* Text color */
    padding: 18px; /* Add some padding */
  }

  .contact-container h2 {
    font-size: 24px; /* Adjust as needed */
    margin-bottom: 10px; /* Space below the header */
  }

  .contact-details {
    font-size: 18px; /* Adjust as needed */
  }

  .contact-details span {
    display: block; /* Each span on its own line */
    margin: 5px 0; /* Some margin for spacing */
  }

  .contact-details a {
    color: #EFEFEF; /* Light grey color for links, adjust as needed */
    text-decoration: none; /* No underline */
  }
  
  .contact-details a:hover {
    text-decoration: underline; /* Underline on hover */
  }
</style>
    
</head>
<body>
    <footer>
        <div class="footer-top">
            <div class="company">
                <h1>OK Society</h1>
                <p>Sponsorship</p>
                <img src="Websystemphp/sponsored.png" alt="Sponsorship 1" />
                <img src="Websystemphp/sponsored2.png" alt="Sponsorship 2" />
                <img src="Websystemphp/sponsored3.png" alt="Sponsorship 3" />
            </div>
            <div class="socialIcons">
                <a href="https://www.facebook.com/tunkuabdulrahmanuniversitycollege/" target="_blank">
                    <img src="Websystemphp/first/facebookicon.png" alt="Facebook" />
                </a>
                <a href="https://twitter.com/KlTaruc" target="_blank">
                    <img src="Websystemphp/twittericon.png" alt="Twitter" />
                </a>
                <a href="https://www.instagram.com/tarumt.official/" target="_blank">
                    <img src="Websystemphp/instagramicon.png" alt="Instagram" />
                </a>
            </div>
            <div class="contact-container">
    <h2>Contact Detail</h2>
    <div class="contact-details">
      <span>Email: <a href="mailto:ongwk-pm23@student.tarc.edu.my"><br>ongwk-pm23@student.tarc.edu.my</a></span>
      <span>Phone: <a href="tel:048995230">04-899 5230</a></span>
    </div>
  </div>
            <div class="map">
  <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.7609844919243!2d100.28487449999999!3d5.453205200000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac2c0305a5483%3A0xfeb1c7560c785259!2sTAR%20UMT%20Penang%20Branch!5e0!3m2!1sen!2smy!4v1712307895446!5m2!1sen!2smy" loading="lazy">
        </iframe>            </div>
        </div>
        <div class="footerNav">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="favourite.php">Favourite</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </div>
        <div class="footerBottom">
        </div>
    </footer>
</body>
</html>