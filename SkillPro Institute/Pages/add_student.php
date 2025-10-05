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

// Include PHP QR Code library
include '../phpqrcode/qrlib.php';

// Initialize messages
$success_msg = "";
$error_msg = "";
$qrFile = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']); // now using the new 'fullname' column
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
        $error_msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error_msg = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert using the new fullname column
            $insert = $conn->prepare("INSERT INTO users (username, fullname, email, password, role, created_at) VALUES (?, ?, ?, ?, 'student', NOW())");
            
            if ($insert->execute([$username, $fullname, $email, $hashed_password])) {
                
                // Generate QR Code
                $qrData = "Username: $username\nFull Name: $fullname\nEmail: $email";
                $qrDir = "../qr_codes/";
                
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }
                
                $qrFile = $qrDir . $username . ".png";
                QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 5);

                $success_msg = "Student added successfully! QR code generated.";
            } else {
                $error_msg = "Failed to add student. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Student - SkillPro Institute</title>
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
    background: rgba(0,0,0,0.5);
    z-index: 0;
}
.container {
    position: relative;
    z-index: 1;
    max-width: 500px;
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 15px;
    margin-top: 50px;
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}
.btn-back {
    background: #6c757d;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    padding: 8px 15px;
    display: inline-block;
    margin-bottom: 20px;
}
.btn-back:hover { background: #5a6268; }
.alert { font-size: 0.9rem; }
.qr-preview {
    text-align: center;
    margin-top: 15px;
}
.qr-preview img {
    width: 150px;
    height: auto;
    margin-top: 10px;
}
</style>
</head>
<body>
<div class="container">

    <a href="student_management.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>

    <h2>Add New Student</h2>

    <?php if($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php if (!empty($qrFile) && file_exists($qrFile)): ?>
            <div class="qr-preview">
                <p><strong>Generated QR Code:</strong></p>
                <img src="<?php echo $qrFile; ?>" alt="QR Code">
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if($error_msg): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter full name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus-circle me-1"></i> Add Student</button>
    </form>
</div>
</body>
</html>
