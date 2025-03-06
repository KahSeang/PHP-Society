<?php
// Address to geocode
$address = "24, Lorong Lembah Permai 6, Tanjung Tokong, 11200 Tanjung Bungah, Pulau Pinang";

// Prepare the address for URL encoding
$encoded_address = urlencode($address);

// Google Maps Geocoding API URL
$geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address={$encoded_address}&key=AIzaSyAdz0-crQ6pS8SR7-LLDGhvnQojkzcKnX0";

// Fetch the geocoding data from Google Maps API
$geocode_json = file_get_contents($geocode_url);
$geocode_data = json_decode($geocode_json);

// Check if the geocoding was successful
if ($geocode_data->status == "OK") {
    // Extract latitude and longitude
    $latitude = $geocode_data->results[0]->geometry->location->lat;
    $longitude = $geocode_data->results[0]->geometry->location->lng;
    
    echo "Latitude: {$latitude}<br>";
    echo "Longitude: {$longitude}";
} else {
    echo "Geocoding failed. Status: {$geocode_data->status}";
}
?>
