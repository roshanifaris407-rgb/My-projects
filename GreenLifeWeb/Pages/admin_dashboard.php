<?php
session_start();

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("<script>alert('Access denied!');window.location='loginpage.php';</script>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
body { 
    font-family: 'Roboto', sans-serif; 
    margin: 0; 
    padding: 0; 
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed; 
    background-size: cover;
    color: #fff; 
}
header { 
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,27,42,0.9);
    padding: 15px; 
    color: #fff; 
    font-size: 24px; 
    font-weight: bold; 
    position: relative;
}
header img {
    position: absolute;
    left: 20px;
    height: 50px;
}
.container { 
    padding: 30px; 
}
.card { 
    background: rgba(0,0,0,0.6); 
    padding: 20px; 
    margin: 15px 0; 
    border-radius: 12px; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.5);
    transition: transform 0.2s ease;
}
.card:hover {
    transform: translateY(-5px);
}
.card h3 { 
    margin: 0 0 10px; 
    color: #ef621b; 
}
.card p { 
    margin: 0; 
}
.nav-links { 
    margin: 20px 0; 
}
.nav-links a { 
    display: inline-block; 
    margin-right: 15px; 
    margin-bottom: 10px;
    padding: 12px 22px; 
    background: #ef621b; 
    color: #fff; 
    text-decoration: none; 
    border-radius: 6px; 
    font-weight: bold;
    transition: background 0.3s ease, transform 0.2s ease;
}
.nav-links a:hover { 
    background: #d15817; 
    transform: scale(1.05);
}
.logout { 
    display: inline-block; 
    margin-top: 20px; 
    padding: 12px 22px; 
    background: #ef621b; 
    color: #fff; 
    text-decoration: none; 
    border-radius: 6px; 
    transition: background 0.3s ease, transform 0.2s ease;
}
.logout:hover { 
    background: #d15817; 
    transform: scale(1.05);
}
</style>
</head>
<body>

<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo">
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
</header>

<div class="container">
    <div class="card">
        <h3>Manage Customers</h3>
        <p>View, edit, or delete customer accounts.</p>
    </div>
    <div class="card">
        <h3>Manage Therapists</h3>
        <p>Approve or manage therapist profiles.</p>
    </div>
    <div class="card">
        <h3>Reports & Analytics</h3>
        <p>Track system usage and wellness statistics.</p>
    </div>

    <div class="nav-links">
        <a href="manage_membership_plans.php">Manage Memberships</a>
        <a href="manage_services.php">Manage Services</a>
        <a href="Therapiest profile.php">Therapist Profile</a>
        <a href="promotions.php">Promotions</a>
        <a href="manage_blogs.php">Manage Blogs</a>
        <a href="manage_appointments.php">Manage Appointments</a>
        <a href= "manage_inquiries.php">Manage Inquiries</a> 
    </div>

    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>
