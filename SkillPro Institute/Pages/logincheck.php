<?php
session_start();

// Include DB connection
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // --- Admin login ---
    if ($username == "Admin" && $password == "123") {
        $insert_admin = "INSERT INTO logins (username, login_time) VALUES ('$username', NOW())";
        mysqli_query($conn, $insert_admin);

        $_SESSION['username'] = $username;
        echo "<script>alert('Admin login successful!'); window.location.href = 'adminpage.php';</script>";
        exit();
    }

    // --- Member login ---
    $sql_member = "SELECT * FROM newmember WHERE username='$username'";
    $result_member = mysqli_query($conn, $sql_member);

    if (mysqli_num_rows($result_member) > 0) {
        $row = mysqli_fetch_assoc($result_member);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $insert_log = "INSERT INTO logins (username, login_time) VALUES ('$username', NOW())";
            mysqli_query($conn, $insert_log);

            echo "<script>alert('Login successful! Welcome, " . htmlspecialchars($username) . "'); window.location.href = 'memberpage.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href = 'loginpage.php';</script>";
            exit();
        }
    }

    // --- Trainer login (with error handling if table doesn't exist) ---
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'trainers'");
    if (mysqli_num_rows($table_check) > 0) {
        $sql_trainer = "SELECT * FROM trainers WHERE username='$username'";
        $result_trainer = mysqli_query($conn, $sql_trainer);

        if ($result_trainer && mysqli_num_rows($result_trainer) > 0) {
            $row = mysqli_fetch_assoc($result_trainer);
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $insert_log = "INSERT INTO logins (username, login_time) VALUES ('$username', NOW())";
                mysqli_query($conn, $insert_log);

                echo "<script>alert('Trainer login successful! Welcome, " . htmlspecialchars($username) . "'); window.location.href = 'trainerspage.php';</script>";
                exit();
            } else {
                echo "<script>alert('Incorrect password for trainer!'); window.location.href = 'loginpage.php';</script>";
                exit();
            }
        }
    }

    // If username not found
    echo "<script>alert('Username not found in any records.'); window.location.href = 'loginpage.php';</script>";
}
?>
