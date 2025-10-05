<?php
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

// ----------------------
// Database Connection
// ----------------------
$host = 'localhost';
$db   = 'skillpro_institute';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}

// ----------------------
// Handle Add Course
// ----------------------
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    if (!empty($course_name)) {
        $stmt = $pdo->prepare("INSERT INTO courses (course_name) VALUES (:course_name)");
        $stmt->execute(['course_name' => $course_name]);
        header("Location: manage_courses.php");
        exit();
    }
}

// ----------------------
// Handle Edit Course
// ----------------------
if (isset($_POST['edit_course'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    if (!empty($course_name)) {
        $stmt = $pdo->prepare("UPDATE courses SET course_name = :course_name WHERE course_id = :course_id");
        $stmt->execute(['course_name' => $course_name, 'course_id' => $course_id]);
        header("Location: manage_courses.php");
        exit();
    }
}

// ----------------------
// Handle Delete Course
// ----------------------
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = :course_id");
    $stmt->execute(['course_id' => $course_id]);
    header("Location: manage_courses.php");
    exit();
}

// ----------------------
// Fetch Courses
// ----------------------
$stmt = $pdo->prepare("SELECT * FROM courses ORDER BY course_id DESC");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------------------
// Handle Edit Form Display
// ----------------------
$edit_course = null;
if (isset($_GET['edit'])) {
    $course_id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = :course_id");
    $stmt->execute(['course_id' => $course_id]);
    $edit_course = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('../Images/background.jpg'); /* Replace with your background image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        .card, table {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent card/table */
        }
        .back-btn {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Back Button -->
    <a href="admin_content_management.php" class="btn btn-secondary back-btn">&larr; Back</a> <!-- Change the href to your desired page -->

    <!-- Logo -->
    <div class="text-center">
        <img src="../Images/logo1.png" alt="Logo" class="logo"> <!-- Replace with your logo path -->
    </div>

    <h2 class="text-center text-white mb-4">Manage Courses</h2>

    <!-- Add or Edit Form -->
    <div class="card mb-4 p-3">
        <div class="card-body">
            <?php if ($edit_course): ?>
                <h5>Edit Course</h5>
                <form method="post">
                    <input type="hidden" name="course_id" value="<?= $edit_course['course_id']; ?>">
                    <div class="mb-3">
                        <label>Course Name</label>
                        <input type="text" name="course_name" class="form-control" value="<?= htmlspecialchars($edit_course['course_name']); ?>" required>
                    </div>
                    <button type="submit" name="edit_course" class="btn btn-success">Update</button>
                    <a href="manage_courses.php" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <h5>Add New Course</h5>
                <form method="post">
                    <div class="mb-3">
                        <label>Course Name</label>
                        <input type="text" name="course_name" class="form-control" placeholder="Enter course name" required>
                    </div>
                    <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses): ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['course_id']); ?></td>
                            <td><?= htmlspecialchars($course['course_name']); ?></td>
                            <td>
                                <a href="manage_courses.php?edit=<?= $course['course_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="manage_courses.php?delete=<?= $course['course_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No courses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
