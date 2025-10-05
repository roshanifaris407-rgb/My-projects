<?php
session_start();

// Only allow admin
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

// Initialize messages
$success_msg = "";
$error_msg = "";

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];

    // Check if student exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND role='student'");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Delete student
        $delete = $conn->prepare("DELETE FROM users WHERE id=? AND role='student'");
        if ($delete->execute([$student_id])) {
            $success_msg = "Student '{$student['fullname']}' has been deleted successfully.";
        } else {
            $error_msg = "Failed to delete the student. Try again.";
        }
    } else {
        $error_msg = "Student not found.";
    }
}

// Fetch all students
$stmt = $conn->prepare("SELECT id, username, fullname, email FROM users WHERE role='student' ORDER BY username ASC");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delete Student - SkillPro Institute</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: url('../Images/background.jpg') no-repeat center center/cover;
    min-height: 100vh;
}
.container {
    max-width: 700px;
    margin: 50px auto;
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
}
h2 { text-align: center; margin-bottom: 25px; color: #004080; }
.logo {
    display: block;
    margin: 0 auto 20px auto;
    width: 120px;
    height: auto;
}
.btn-back { margin-bottom: 20px; background: #6c757d; color: #fff; border-radius: 8px; }
.btn-back:hover { background: #5a6268; }
.alert { font-size: 0.9rem; }
table { margin-top: 20px; }
</style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <a href="student_management.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
    <h2>Delete Student</h2>

    <?php if($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <?php if(count($students) > 0): ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $index => $student): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($student['username']); ?></td>
                <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                        <button type="submit" name="delete_student" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>
</div>
</body>
</html>
