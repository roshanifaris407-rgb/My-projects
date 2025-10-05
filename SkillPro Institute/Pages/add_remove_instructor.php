<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_instructor'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username_input = trim($_POST['username']);
    $password_input = trim($_POST['password']);
    
    if ($fullname && $email && $username_input && $password_input) {
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_stmt->execute([$username_input, $email]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Username or email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role, created_at) VALUES (?, ?, ?, ?, 'instructor', NOW())");
            $message = $stmt->execute([$fullname, $email, $username_input, $hashed_password]) 
                ? "Instructor added successfully!" 
                : "Error adding instructor!";
        }
    } else {
        $error = "All fields are required!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_instructor'])) {
    $instructor_id = (int)$_POST['instructor_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'instructor'");
    $message = $stmt->execute([$instructor_id]) 
        ? "Instructor removed successfully!" 
        : "Error removing instructor!";
}

try {
    $instructors = $conn->query("SELECT * FROM users WHERE role = 'instructor' ORDER BY fullname")
                        ->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $instructors = [];
    $error = "Error loading instructors: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Instructor Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: url('../Images/background.jpg') no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    padding: 20px;
}

.bg-overlay {
    background-color: rgba(255,255,255,0.95);
    padding: 25px;
    border-radius: 15px;
}

.logo {
    display: block;
    margin: 0 auto 15px auto;
    width: 100px;
    height: auto;
}

.card {
    border-radius: 12px;
}

.btn-primary {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    padding: 8px 15px;
    font-size: 0.9rem;
}

.btn-danger {
    padding: 5px 10px;
    font-size: 0.85rem;
}
</style>
</head>
<body>

<div class="container py-4">
    <div class="bg-overlay">
        <!-- Logo -->
        <img src="../Images/logo1.png" alt="SkillPro Logo" class="logo">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Instructor Management</h2>
            <a href="instructor_management.php" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Add Instructor -->
        <div class="card mb-4 p-3">
            <h5 class="fw-semibold mb-3">Add New Instructor</h5>
            <form method="POST">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-6">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>
                <button type="submit" name="add_instructor" class="btn btn-primary mt-3">
                    <i class="fas fa-user-plus"></i> Add Instructor
                </button>
            </form>
        </div>

        <!-- Current Instructors -->
        <div class="card p-3">
            <h5 class="fw-semibold mb-3">Current Instructors (<?php echo count($instructors); ?>)</h5>
            <?php if (count($instructors) > 0): ?>
                <?php foreach ($instructors as $instructor): ?>
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                        <div>
                            <strong><?php echo htmlspecialchars($instructor['fullname'] ?: $instructor['username']); ?></strong><br>
                            <small><?php echo htmlspecialchars($instructor['email']); ?></small>
                        </div>
                        <form method="POST" onsubmit="return confirm('Remove this instructor?');">
                            <input type="hidden" name="instructor_id" value="<?php echo $instructor['id']; ?>">
                            <button type="submit" name="remove_instructor" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted mb-0">No instructors found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
