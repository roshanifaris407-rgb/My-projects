<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: loginpage.php");
    exit();
}
$instructor_name = $_SESSION['username'];
$instructor_email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Instructor Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    min-height: 100vh;
    margin: 0;
    padding: 0;
}
.dashboard-container {
    max-width: 1200px;
    margin: 50px auto;
    background: #fff;
    padding: 50px 30px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    position: relative;
}
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}
.dashboard-header h2 {
    color: #004080;
}
.logout-btn {
    background:#ff4d4d;
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:10px;
    transition:0.3s;
    text-decoration: none;
}
.logout-btn:hover { 
    background:#e60000; 
}
.instructor-section-title {
    font-size: 24px;
    font-weight: bold;
    color: #004080;
    margin-bottom: 25px;
    text-align: center;
}
.instructor-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.instructor-card {
    background: #f5f9ff;
    border-radius: 15px;
    padding: 25px 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
.instructor-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.instructor-card h4 {
    color: #004080;
    margin-bottom: 10px;
}
.instructor-card p {
    margin-bottom: 20px;
    font-weight: 500;
    color: #333;
}
.instructor-card a {
    padding:10px 20px;
    background:#004080;
    color:white;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.3s;
}
.instructor-card a:hover {
    background:#0066cc;
}
.logo {
    display: block;
    margin: 0 auto 20px auto;
    width: 120px;
}
</style>
</head>
<body>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h2>Welcome, <?php echo htmlspecialchars($instructor_name); ?>!</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

   
    

    <!-- Instructor Cards -->
    <div class="instructor-cards">

        <!-- Instructor 1 -->
        <div class="instructor-card">
            <h4>Ms. Nisha Fernando</h4>
            <p>Subject: Hospitality Management</p>
            <a href="instructor_dashboard_1.php">View Dashboard</a>
        </div>

        <!-- Instructor 2 -->
        <div class="instructor-card">
            <h4>Mr. Amal Perera</h4>
            <p>Subject: ICT</p>
            <a href="instructor_dashboard_2.php">View Dashboard</a>
        </div>

        <!-- Instructor 3 -->
        <div class="instructor-card">
            <h4>Mr. Sandun Jayawardena</h4>
            <p>Subject: Engineering</p>
            <a href="instructor_dashboard_3.php">View Dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
