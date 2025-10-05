<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - User Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body {
    background: url('../Images/background.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Poppins', sans-serif;
}
.dashboard-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.4);
    width: 400px;
    text-align: center;
}
.dashboard-box img {
    display: block;
    margin: 0 auto 15px auto;
    width: 120px;
    height: auto;
}
.dashboard-box h2 {
    margin-bottom: 30px;
    color: #004080;
}
.dashboard-box .btn {
    border-radius: 10px;
    width: 80%;
    padding: 15px;
    margin: 10px 0;
    font-weight: 600;
    transition: 0.3s;
}
.btn-student { background: #28a745; border: none; color: white; }
.btn-student:hover { background: #218838; }
.btn-instructor { background: #17a2b8; border: none; color: white; }
.btn-instructor:hover { background: #138496; }
</style>
</head>
<body>
<div class="dashboard-box">
    <!-- Logo added here -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo">

    <h2>User Management</h2>
    <a href="student_management.php" class="btn btn-student">Student Management</a>
    <a href="instructor_management.php" class="btn btn-instructor">Instructor Management</a>
    <a href="admin_dashboard.php" class="btn btn-student">Back to Dashboard</a>
</div>
</body>
</html>
