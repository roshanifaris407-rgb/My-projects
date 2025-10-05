<?php
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
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

// Search functionality
$search_username = isset($_GET['username']) ? $_GET['username'] : '';
$query = "SELECT * FROM users WHERE role='student'";
$params = [];

if (!empty($search_username)) {
    $query .= " AND username LIKE ?";
    $params[] = "%$search_username%";
}

$query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard - SkillPro Institute</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    background: url('../Images/background.jpg') no-repeat center center/cover;
    position: relative;
}
body::before {
    content: '';
    position: absolute;
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,0.4);
    z-index: 0;
}
.container {
    position: relative;
    z-index: 1;
    padding: 30px 15px;
    color: #fff;
}
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header-section h1 {
    font-weight: 600;
    color: #fff;
}
.logo-img {
    height: 60px;
    width: auto;
    margin-right: 15px;
}
.back-btn {
    background: #6c757d;
    border: none;
    padding: 10px 20px;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}
.back-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
}
.search-bar {
    margin-bottom: 30px;
}
.search-bar input {
    border-radius: 10px;
    padding: 12px 15px;
    width: calc(100% - 120px);
    border: none;
}
.search-bar button {
    border-radius: 10px;
    padding: 12px 20px;
    background: #4a90e2;
    border: none;
    color: #fff;
    transition: 0.3s;
}
.search-bar button:hover {
    background: #357ABD;
    transform: translateY(-2px);
}
.search-bar .btn-secondary {
    background: #6c757d;
    border-radius: 10px;
}
.student-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.student-card {
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 20px;
    text-align: center;
    transition: 0.3s;
}
.student-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}
.student-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4a90e2, #6c5ce7);
    border-radius: 50%;
    margin: 0 auto 15px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 1.2rem;
}
.student-name {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 5px;
}
.student-fullname, .student-email, .student-date {
    font-size: 0.9rem;
    color: #555;
}
.no-data {
    text-align: center;
    padding: 50px 0;
    color: #fff;
}
.no-data i {
    font-size: 3rem;
    margin-bottom: 15px;
    display: block;
}
</style>
</head>
<body>
<div class="container">

    <div class="header-section">
        <div class="d-flex align-items-center">
            <img src="../Images/logo1.png" alt="Logo" class="logo-img">
            <h1>Student Dashboard</h1>
        </div>
        <a href="student_management.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <!-- Search Bar -->
    <div class="search-bar d-flex mb-4">
        <form method="GET" class="d-flex w-100 gap-2">
            <input type="text" name="username" placeholder="Search by username..." value="<?php echo htmlspecialchars($search_username ?? ''); ?>">
            <button type="submit"><i class="fas fa-search me-1"></i> Search</button>
            <a href="view_students.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Student Dashboard -->
    <?php if(count($students) > 0): ?>
    <div class="student-cards">
        <?php foreach($students as $student): ?>
        <div class="student-card">
            <div class="student-avatar"><?php echo strtoupper(substr($student['username'] ?? '', 0, 2)); ?></div>
            <div class="student-name"><?php echo htmlspecialchars($student['username'] ?? ''); ?></div>
            <div class="student-fullname"><i class="fas fa-user me-1"></i><?php echo htmlspecialchars($student['fullname'] ?? ''); ?></div>
            <div class="student-email"><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($student['email'] ?? ''); ?></div>
            <div class="student-date"><i class="fas fa-calendar me-1"></i><?php echo !empty($student['created_at']) ? date('M j, Y g:i A', strtotime($student['created_at'])) : ''; ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="no-data">
        <i class="fas fa-user-graduate"></i>
        <div>No students found.</div>
        <small><?php echo !empty($search_username) ? 'Try adjusting your search.' : 'No students registered yet.'; ?></small>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
