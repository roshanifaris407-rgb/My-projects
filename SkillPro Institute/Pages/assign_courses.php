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

// Fetch instructors
try {
    $instructors = $conn->query("SELECT id, fullname, username FROM users WHERE role = 'instructor' ORDER BY fullname")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $instructors = [];
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_course'])) {
    $instructor_id = $_POST['instructor_id'];
    $course_file = $_FILES['course_file'];

    if (!empty($instructor_id) && $course_file['error'] == 0) {
        $upload_dir = "../uploads/courses/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($course_file['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($course_file['tmp_name'], $target_file)) {
            $message = "Course assigned successfully!";
        } else {
            $error = "Error uploading the file.";
        }
    } else {
        $error = "Please select an instructor and upload a file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Courses to Instructor</title>
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
        .assign-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            width: 450px;
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 15px;
        }
        .assign-box h2 {
            margin-bottom: 20px;
            color: #004080;
        }
        .btn-back {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="assign-box">

    <!-- ✅ Logo Added -->
    <img src="../Images/logo1.png" alt="Institute Logo" class="logo">

    <h2>Assign Course</h2>

    <!-- ✅ Success Message Box -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ❌ Error Message -->
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- Instructor Dropdown -->
        <div class="mb-3 text-start">
            <label class="form-label">Select Instructor</label>
            <select name="instructor_id" class="form-select" required>
                <option value="">-- Choose an Instructor --</option>
                <?php foreach ($instructors as $instructor): ?>
                    <option value="<?php echo $instructor['id']; ?>">
                        <?php echo htmlspecialchars($instructor['fullname'] ?: $instructor['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- File Upload -->
        <div class="mb-3 text-start">
            <label class="form-label">Upload Course File</label>
            <input type="file" name="course_file" class="form-control" required>
        </div>

        <button type="submit" name="assign_course" class="btn btn-primary w-100">
            Assign File
        </button>
    </form>

    <a href="instructor_management.php" class="btn btn-secondary btn-back">Back</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
