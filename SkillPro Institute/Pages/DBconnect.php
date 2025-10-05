<?php
$servername = "localhost";
$username = "root"; // default for WAMP
$password = "";     // default for WAMP
$dbname = "skillpro_institute";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
