<?php
// db_connection.php
$servername = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = mysqli_connect($servername, $username, $password, $database);
$conn->set_charset("utf8mb4");
$conn->query("SET collation_connection = 'utf8mb4_general_ci'");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
