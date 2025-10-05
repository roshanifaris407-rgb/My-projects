<?php
session_start();
include '../Pages/DBconnect.php'; // Correct path to your DBconnect.php

$show_message = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Insert into inquiries table
        $stmt = mysqli_prepare($conn, "INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $show_message = true; // Display success message
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Inquiry Submitted - SkillPro Institute</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: url("../Images/background.jpg") no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    color: #fff;
}
.container {
    max-width: 700px;
    margin: 80px auto;
    background: rgba(0,0,0,0.7);
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}
.logo {
    width: 120px;
    margin-bottom: 20px;
}
h1 {
    font-size: 28px;
    margin-bottom: 20px;
}
p {
    font-size: 18px;
    margin-bottom: 30px;
}
.success-message {
    background: #28a745;
    color: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: bold;
}
.btn-home {
    background: #007bff;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
}
.btn-home:hover {
    background: #0056b3;
}
</style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <h1>Inquiry Submission</h1>

    <?php if($show_message): ?>
        <div class="success-message">✅ Your inquiry has been submitted successfully!</div>
    <?php else: ?>
        <p>There was an issue with your submission. Please fill all fields and try again.</p>
    <?php endif; ?>

    <a href="home.php" class="btn-home">Back to Home</a>
</div>
</body>
</html>
