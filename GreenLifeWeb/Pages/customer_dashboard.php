<?php
session_start();
include 'DBconnect.php';

// Check if customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: loginpage.php");
    exit();
}

// Fetch customer details
$stmt = $conn->prepare("SELECT id, username, email FROM newmember WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
/* Full-page background */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    color: #fff;
}

/* Semi-transparent container */
.container {
    background: rgba(13, 27, 42, 0.85); /* dark overlay */
    width: 90%;
    max-width: 1000px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.6);
    text-align: center;
}

/* Logo styling */
.container img.logo {
    display: block;
    margin: 0 auto 20px;
    width: 120px;
}

/* Heading */
h1 {
    color: #ef621b;
    margin-bottom: 30px;
}

/* User info */
.user-info p {
    font-size: 18px;
    margin: 8px 0;
}

/* Buttons */
.btn {
    display: inline-block;
    margin: 10px 5px;
    padding: 12px 25px;
    background: #ef621b;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s;
}
.btn:hover {
    background: #e65100;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .container {
        padding: 20px;
    }
    .btn {
        width: 100%;
        margin: 10px 0;
    }
}
</style>
</head>
<body>

<div class="container">
    <!-- Logo -->
    <img src="../Images/logo.png" alt="GreenLife Wellness Logo" class="logo">

    <h1>Welcome, <?= htmlspecialchars($user['username']) ?>!</h1>

    <div class="user-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username'] ?? '') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
    </div>

    <div>
        <a href="book_appointment.php" class="btn">Book an Appointment</a>
        <a href="my_appointments.php" class="btn">My Appointments</a>
        <a href="membership_plans.php" class="btn">Apply Membership Plans</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

</body>
</html>
