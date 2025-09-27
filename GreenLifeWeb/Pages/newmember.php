<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GreenLife Wellness Member Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(rgba(13,27,42,0.7), rgba(13,27,42,0.7)), url("../Images/Back Ground.jpg") no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }

        .nav-btn {
            position: absolute;
            top: 20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: background 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(6px);
        }
        .nav-btn:hover { background: rgba(255,255,255,0.35); color: #000; }
        .nav-home { left: 20px; }
        .nav-login { left: 120px; }

        .form-container {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 40px 30px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideFade 0.6s ease-out;
        }

        @keyframes slideFade { 
            0% { transform: translateY(40px); opacity: 0; } 
            100% { transform: translateY(0); opacity: 1; } 
        }

        h2 { text-align: center; color: #ffffff; font-size: 28px; margin-bottom: 30px; font-weight: 600; }

        .form-group { margin-bottom: 22px; }

        label { display: block; color: #ffffff; font-weight: 500; margin-bottom: 8px; font-size: 15px; }

        input, select { width: 100%; padding: 12px 14px; border: 1px solid #ccc; border-radius: 10px; font-size: 15px; background-color: #f9f9f9; transition: all 0.3s ease; }
        input:focus, select:focus { border-color: #1c7ed6; box-shadow: 0 0 5px rgba(28, 126, 214, 0.4); outline: none; background-color: #fff; }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #1c7ed6, #1971c2);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover { background: linear-gradient(to right, #1864ab, #0b4f9c); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

<a href="home.php" class="nav-btn nav-home">Home</a>

<?php
include('dbconnect.php');

// Predefined services
$services = [
    "Ayurvedic Therapy",
    "Yoga and Meditation Classes",
    "Nutrition and Diet Consultation",
    "Physiotherapy",
    "Massage Therapy"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $membership = mysqli_real_escape_string($conn, $_POST['membership']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);

    // ---- Upload profile photo ----
    $profilePhoto = "";
    if (!empty($_FILES['profile_photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
        $fileName = time() . "_" . basename($_FILES["profile_photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFilePath)) {
            $profilePhoto = $targetFilePath;
        }
    }

    // ---- Check duplicate email ----
    $check_email = "SELECT * FROM newmember WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This email is already registered. Please use another one.');</script>";
    } else {
        // Auto-generate username and password
        $username = strtolower(str_replace(' ', '', $name)) . rand(100, 999);
        $password_plain = substr(str_shuffle('abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        // ✅ Insert new member (no address column now)
        $sql = "INSERT INTO newmember 
                (full_name, age, gender, contact_number, email, membership_type, choose_program, username, password, profile_photo, joined_date)
                VALUES 
                ('$name', '$age', '$gender', '$contact', '$email', '$membership', '$service', '$username', '$password_hashed', '$profilePhoto', NOW())";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('New member registered!\\nUsername: $username\\nPassword: $password_plain');
                window.location.href = 'loginpage.php';
            </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<div class="form-container">
    <h2>Register as a GreenLife Wellness Center Member</h2>
    <form action="newmember.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" required>
        </div>

        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="membership">Membership Type</label>
            <select id="membership" name="membership" required>
                <option value="">Select Membership</option>
                <option value="Basic">Basic</option>
                <option value="Premium">Premium</option>
                <option value="VIP">VIP</option>
            </select>
        </div>

        <div class="form-group">
            <label for="service">Service</label>
            <select id="serviceSelect" name="service" required>
                <option value="">Select Service</option>
                <?php foreach ($services as $serviceName): ?>
                    <option value="<?= htmlspecialchars($serviceName) ?>"><?= htmlspecialchars($serviceName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="profile_photo">Profile Photo</label>
            <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
        </div>

        <button type="submit">Register Now</button>
    </form>
</div>

</body>
</html>
