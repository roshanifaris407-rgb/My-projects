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

// Fetch all students for dropdown
$stmt = $conn->prepare("SELECT id, username, fullname FROM users WHERE role='student' ORDER BY username ASC");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    $student_id = $_POST['student_id'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($fullname) || empty($email)) {
        $error_msg = "Full Name and Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } else {
        // If password is provided, hash it, else keep old password
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET fullname=?, email=?, password=? WHERE id=?");
            $updated = $update->execute([$fullname, $email, $hashed_password, $student_id]);
        } else {
            $update = $conn->prepare("UPDATE users SET fullname=?, email=? WHERE id=?");
            $updated = $update->execute([$fullname, $email, $student_id]);
        }

        if ($updated) {
            $success_msg = "Student details updated successfully.";
        } else {
            $error_msg = "Failed to update student details.";
        }
    }
}

// Fetch selected student details if student_id is set via GET or POST
$selected_student = null;
if (isset($_GET['student_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND role='student'");
    $stmt->execute([$_GET['student_id']]);
    $selected_student = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_POST['student_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND role='student'");
    $stmt->execute([$_POST['student_id']]);
    $selected_student = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Student - SkillPro Institute</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: url('../Images/background.jpg') no-repeat center center/cover;
    min-height: 100vh;
}
.container {
    max-width: 600px;
    margin: 50px auto;
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
}
h2 { margin-bottom: 25px; color: #004080; }
.logo {
    display: block;
    margin: 0 auto 20px auto;
    width: 120px;
    height: auto;
}
.btn-back { margin-bottom: 20px; background: #6c757d; color: #fff; border-radius: 8px; }
.btn-back:hover { background: #5a6268; }
.alert { font-size: 0.9rem; }
</style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <a href="student_management.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
    <h2>Update Student Details</h2>

    <?php if($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <!-- Select student -->
    <form method="GET" class="mb-3">
        <label for="student_id" class="form-label">Select Student</label>
        <select name="student_id" id="student_id" class="form-select" onchange="this.form.submit()" required>
            <option value="">-- Select a student --</option>
            <?php foreach($students as $student): ?>
                <option value="<?php echo $student['id']; ?>" <?php echo ($selected_student && $selected_student['id']==$student['id'])?'selected':''; ?>>
                    <?php echo htmlspecialchars($student['username'].' ('.$student['fullname'].')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if($selected_student): ?>
    <form method="POST">
        <input type="hidden" name="student_id" value="<?php echo $selected_student['id']; ?>">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($selected_student['fullname']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($selected_student['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password">
        </div>
        <button type="submit" name="update_student" class="btn btn-warning w-100">Update Student</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
