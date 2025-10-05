<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "skillpro_institute"; // ✅ database name variable
$username = "root";
$password = "";

try {
    // ✅ use $dbname instead of $skillpro_institute
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = '';
$error = '';

// Handle form submission
if ($_POST) {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Basic validation
    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            
            if ($update_stmt->execute([$hashed_password, $email])) {
                $message = "Password reset successfully! You can now login with your new password.";
            } else {
                $error = "Error updating password. Please try again.";
            }
        } else {
            $error = "Email address not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SkillPro Institute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url( "../Images/reset.jpg") no-repeat center center fixed, 
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: cover;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        
        .reset-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            width: 80px;
            height: auto;
        }
        
        .reset-title {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .reset-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e1e5e9;
            padding: 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e1e5e9;
            border-right: none;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #666;
        }
        
        @media (max-width: 576px) {
            .reset-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="logo">
                <img src="../Images/logo1.png" alt="SkillPro Institute Logo">
            </div>
            
            <h2 class="reset-title">Reset Password</h2>
            <p class="reset-subtitle">Enter your email and new password</p>
            
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Enter your email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="new_password" name="new_password" 
                           placeholder="Enter new password" required>
                    <label for="new_password"><i class="fas fa-lock me-2"></i>New Password</label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm new password" required>
                    <label for="confirm_password"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-reset">
                    <i class="fas fa-key me-2"></i>Reset Password
                </button>
            </form>
            
            <div class="back-link">
                <a href="loginpage.php">
                    <i class="fas fa-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
        
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    this.setCustomValidity('Passwords do not match');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            }
        });
        
        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            
            if (password.length >= 6) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (password.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    </script>
</body>
</html>
