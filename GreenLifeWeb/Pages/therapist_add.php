<?php
session_start();

// --- Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$database = "greenlife_wellness";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// --- Handle Add New Therapist Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];

    // Default profile image
    $profile_image = "Therapist_profile.png"; // Place this image in Images folder

    $insert_sql = "INSERT INTO therapist (name, email, specialization, status, profile_image) VALUES (?, ?, ?, 'Active', ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $name, $email, $specialization, $profile_image);

    if ($stmt->execute()) {
        // Redirect to therapist list page after adding
        echo "<script>alert('New Therapist Added Successfully!'); window.location.href='therapist_list.php';</script>";
        exit();
    } else {
        echo "Error adding therapist: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Therapist - GreenLife Wellness</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}
header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    background: rgba(255, 255, 255, 0.8);
}
header img { height: 70px; }
.container {
    max-width: 500px;
    margin: 80px auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
h2 { text-align: center; color: #0D1B2A; margin-bottom: 30px; font-size: 28px; }
form label { display: block; margin-top: 15px; font-weight: bold; color: #0D1B2A; }
form input[type="text"], form input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 15px;
}
.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: bold;
    text-decoration: none;
    color: #fff;
    background: #0D1B2A;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.btn:hover { background: #ef621b; }
.btn-back { background: gray; margin-left: 15px; }
.btn-back:hover { background: darkgray; }
.button-group { text-align: center; }
</style>
</head>
<body>

<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo">
</header>

<div class="container">
    <h2>Add New Therapist</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Specialization:</label>
        <input type="text" name="specialization" required>

        <div class="button-group">
            <button type="submit" class="btn">Add Therapist</button>
            <a href= "Therapiest profile.php" class="btn btn-back">Back to List</a>
        </div>
    </form>
</div>

</body>
</html>
