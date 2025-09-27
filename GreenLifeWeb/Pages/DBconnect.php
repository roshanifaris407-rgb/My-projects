<?php
// Database configuration
$servername = "localhost";        // Database server
$username = "root";               // Default username for WAMP/XAMPP
$password = "";                   // Default password for WAMP/XAMPP
$database = "greenlife_wellness"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 to support full Unicode (emojis, special characters)
$conn->set_charset("utf8mb4");

// Connection is successful
// echo "Connected successfully to database: " . $database;
?>
