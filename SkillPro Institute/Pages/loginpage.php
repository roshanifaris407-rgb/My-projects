<?php
session_start();
include 'DBconnect.php'; // Make sure this path is correct

// Predefined instructor accounts
$instructors = [
    ['username' => 'instructor1', 'password' => 'pass123', 'email' => 'instructor1@example.com'],
    ['username' => 'instructor2', 'password' => 'pass234', 'email' => 'instructor2@example.com'],
    ['username' => 'instructor3', 'password' => 'pass345', 'email' => 'instructor3@example.com']
];

// Predefined admin account
$admin = ['username' => 'admin', 'password' => 'admin123', 'email' => 'admin@example.com'];

// Fetch all usernames and emails for suggestions (from newmember)
$student_suggestions = [];
if ($conn->query("SHOW TABLES LIKE 'newmember'")->num_rows > 0) {
    $result = $conn->query("SELECT username, email FROM newmember");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $student_suggestions[] = $row['username'];
            $student_suggestions[] = $row['email'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Admin login
    if ($role === "admin") {
        if ($username === $admin['username'] && $password === $admin['password']) {
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = $admin['username'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['role'] = "admin";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid admin credentials!";
        }
    }

    // Instructor login (predefined accounts)
    elseif ($role === "instructor") {
        $found = false;
        foreach ($instructors as $inst) {
            if ($inst['username'] === $username && $inst['password'] === $password) {
                $_SESSION['user_id'] = 0; // optional
                $_SESSION['username'] = $inst['username'];
                $_SESSION['email'] = $inst['email'];
                $_SESSION['role'] = "instructor";
                $found = true;
                header("Location: instructor_dashboard.php");
                exit();
            }
        }
        if (!$found) $error = "Invalid instructor credentials!";
    }

    // Student login from newmember table
    elseif ($role === "student") {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM newmember WHERE (username=? OR email=?) AND role='student' LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($password === $user['password'] || password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = "student";
                    header("Location: student_dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "Student not found!";
            }
            $stmt->close();
        } else {
            $error = "Database query error: " . $conn->error;
        }
    } else {
        $error = "Invalid role selected!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SkillPro Institute - Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    background: url('../Images/background.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Poppins', sans-serif;
}
.login-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.4);
    width: 400px;
    text-align: center;
}
.login-box img {
    display: block;
    margin: 0 auto 15px auto;
    width: 120px;
    height: auto;
}
.login-box h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
    color: #004080;
}
.form-control { border-radius: 10px; margin-bottom: 15px; }
.btn-primary, .btn-secondary {
    border-radius: 10px; transition: 0.3s; width: 100%; padding: 12px; font-weight: 600;
}
.btn-primary { background: #004080; border: none; }
.btn-primary:hover { background: #0066cc; }
.btn-secondary { background: #6c757d; border: none; margin-top: 10px; }
.btn-secondary:hover { background: #5a6268; }
.error-msg { color: red; text-align: center; margin-bottom: 15px; }
.register-link { text-align: center; margin-top: 15px; }
.password-wrapper { position: relative; }
.password-wrapper input { padding-right: 40px; }
.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #999;
    transition: 0.3s;
}
.toggle-password:hover { color: #004080; }
@media (max-width: 480px) {
    .login-box { width: 90%; padding: 30px; }
}
</style>
</head>
<body>
<div class="login-box">
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo">
    <h2>Login</h2>
    <?php if(isset($error)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" class="form-control" placeholder="Username or Email" required
               list="students-list"
               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        <datalist id="students-list">
            <?php foreach($student_suggestions as $suggestion): ?>
                <option value="<?php echo htmlspecialchars($suggestion); ?>"></option>
            <?php endforeach; ?>
        </datalist>

        <div class="password-wrapper">
            <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
            <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <select name="role" class="form-control" required>
            <option value="">-- Select Role --</option>
            <option value="student" <?php echo (isset($_POST['role']) && $_POST['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
            <option value="instructor" <?php echo (isset($_POST['role']) && $_POST['role'] === 'instructor') ? 'selected' : ''; ?>>Instructor</option>
            <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <a href="home.php" class="btn btn-secondary">Back to Home</a>
    <div class="register-link">
        Don’t have an account? <a href="newmember.php">Register</a>
    </div>
</div>
<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    passwordField.type = passwordField.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
