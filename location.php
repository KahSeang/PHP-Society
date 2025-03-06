<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch location from aboutus table
$sql = "SELECT location FROM aboutus WHERE id=1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    $location = $row["location"];
  }
} else {
  echo "0 results";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Map Example</title>
  <style>
    /* Set the size of the div element that contains the map */
    #map {
      height: 50%;
      width: 100%;
    }
    /* The page should take the full height of the viewport */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
  <div id="map"></div>

  <!-- Include the Google Maps JavaScript API -->
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdz0-crQ6pS8SR7-LLDGhvnQojkzcKnX0&callback=initMap"></script>

  <script>
    // PHP variable to JavaScript
    var locationAddress = <?php echo json_encode($location); ?>;

    // Initialize and add the map
    function initMap() {
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'address': locationAddress }, function(results, status) {
        if (status === 'OK') {
          var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: results[0].geometry.location
          });
          var marker = new google.maps.Marker({
            position: results[0].geometry.location,
            map: map
          });
        } else {
          alert('Geocode was not successful for the following reason: ' + status);
        }
      });
    }
  </script>
</body>
</html>
