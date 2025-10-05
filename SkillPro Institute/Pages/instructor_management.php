<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Instructor Management</title>
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
.management-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.4);
    width: 450px;
    text-align: center;
}
.management-box img {
    display: block;
    margin: 0 auto 15px auto;
    width: 120px;
    height: auto;
}
.management-box h2 {
    margin-bottom: 30px;
    color: #004080;
}
.management-box .btn {
    border-radius: 10px;
    width: 80%;
    padding: 15px;
    margin: 10px 0;
    font-weight: 600;
    transition: 0.3s;
}
.btn-view { background: #007bff; border: none; color: white; }
.btn-view:hover { background: #0056b3; }
.btn-addremove { background: #28a745; border: none; color: white; }
.btn-addremove:hover { background: #218838; }
.btn-assign { background: #17a2b8; border: none; color: white; }
.btn-assign:hover { background: #117a8b; }
.btn-back { background: #6c757d; border: none; color: white; }
.btn-back:hover { background: #5a6268; }
</style>
</head>
<body>
<div class="management-box">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo">

    <h2>Instructor Management</h2>
    
    <a href="view_instructors.php" class="btn btn-view">View Instructor Dashboard</a>
    <a href="add_remove_instructor.php" class="btn btn-addremove">Add/Remove Instructor</a>
    <a href="assign_courses.php" class="btn btn-assign">Assign Courses to Instructor</a>
    <a href="admin_user_management.php" class="btn btn-back">Back</a>
</div>
</body>
</html>
