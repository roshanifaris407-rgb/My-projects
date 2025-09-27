<?php
session_start();
include 'DBconnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnlogin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // --- Admin login (hard-coded) ---
    if ($role === "admin" && $username === "admin" && $password === "admin123") {
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = "admin";
        $_SESSION['role'] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    // --- Customer login ---
    if ($role === "customer") {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM newmember WHERE username=? OR email=? LIMIT 1");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = "customer";
                header("Location: customer_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Customer not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    /* Full-page background image */
    background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    height: 100vh;
}
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    background: rgba(27,38,59,0.85); /* semi-transparent overlay for readability */
    padding: 40px;
    width: 380px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.6);
    position: relative;
}
.login-box h2 {
    color: #ef621b;
    margin-bottom: 20px;
}
.login-box img.logo {
    display: block;
    margin: 0 auto 20px;
    width: 120px;
}
.login-box label {
    display: block;
    text-align: left;
    margin-top: 10px;
    font-weight: bold;
}
.login-box input[type="text"], .login-box input[type="password"], .login-box select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.login-box button {
    width: 100%;
    padding: 12px;
    background-color: #ef621b;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}
.login-box button:hover { background-color: #d15817; }
.password-wrapper { position: relative; }
.toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 16px; color: #fff; }
.login-link { margin-top: 15px; font-size: 14px; color: #ccc; }
.login-link a { color: #ff5722; text-decoration: none; font-weight: bold; }
.login-link a:hover { color: #e64a19; }
.error-msg { color: #ff4444; font-weight: bold; margin-bottom: 10px; }
.back-home-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #ef621b;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}
.back-home-btn:hover { background-color: #d15817; }
</style>
</head>
<body>
<div class="login-container">
  <div class="login-box">
      <!-- Logo -->
      <img src="../Images/logo.png" alt="GreenLife Wellness Logo" class="logo">

      <h2>Login to GreenLife Wellness</h2>

      <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>

      <form action="" method="post">
          <label for="username">Username or Email</label>
          <input type="text" name="username" id="username" placeholder="Enter username or email" required>

          <label for="role">Select Role</label>
          <select name="role" id="role" required>
              <option value="customer">Customer</option>
              <option value="admin">Admin</option>
          </select>

          <label for="password">Password</label>
          <div class="password-wrapper">
              <input type="password" name="password" id="password" placeholder="Enter password" required>
              <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
          </div>

          <button type="submit" name="btnlogin">Login</button>
      </form>

      <div class="login-link">
          Don't have an account? <a href="newmember.php">Register here</a>
      </div>

      <!-- Back to Home button -->
      <a href="home.php" class="back-home-btn">← Back to Home</a>
  </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    const toggleIcon = document.querySelector(".toggle-password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
</script>
</body>
</html>
