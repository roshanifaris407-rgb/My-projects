<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$host = "localhost";
$db_name = "skillpro_institute";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch all courses
try {
    $stmt = $conn->prepare("SELECT * FROM courses ORDER BY course_name ASC");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $courses = [];
    $error = "Error loading courses: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Courses</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url("../Images/background.jpg") no-repeat center center fixed;
    background-size: cover;
}
.container {
    max-width: 800px;
    margin: 50px auto;
    background: rgba(255,255,255,0.95);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.logo {
    width: 120px;
    display: block;
    margin: 0 auto 15px auto;
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}
.course-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 12px;
    background: #f8f9fa;
}
.course-item h4 {
    margin: 0 0 5px 0;
    color: #004080;
}
.course-item p {
    margin: 3px 0;
    color: #555;
}
.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    text-decoration: none;
    background: #6c757d;
    color: white;
    border-radius: 8px;
    transition: 0.3s;
}
.back-btn:hover {
    background: #444;
}
</style>
</head>
<body>

<div class="container">
    <img src="../Images/logo1.png" alt="Logo" class="logo">
    <h2>📚 Courses Available</h2>

    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <div class="course-item">
                <h4><?php echo htmlspecialchars($course['course_name'] ?? 'No Name'); ?></h4>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description'] ?? 'No description'); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration'] ?? '-'); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($course['category'] ?? '-'); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses found.</p>
    <?php endif; ?>

    <a href="admin_content_management.php" class="back-btn">⬅ Back</a>
</div>

</body>
</html>
