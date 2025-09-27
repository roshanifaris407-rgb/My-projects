<?php
session_start();

// --- Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$database = "greenlife_wellness";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// --- Get Therapist ID from URL ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Therapist ID not provided.");
}
$therapist_id = intval($_GET['id']);

// --- Fetch Therapist Data ---
$sql = "SELECT * FROM therapist WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Therapist not found.");
}
$therapist = $result->fetch_assoc();

// --- Handle Update Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];

    $update_sql = "UPDATE therapist SET name=?, email=?, specialization=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $name, $email, $specialization, $therapist_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile Updated Successfully!'); window.location.href='therapist_list.php';</script>";
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Therapist Profile - GreenLife Wellness</title>
<style>
/* Background Image */
body {
    font-family: Arial, sans-serif;
    background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}

/* Header / Logo */
header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    background: rgba(255, 255, 255, 0.8);
}
header img {
    height: 70px;
}

/* Main container */
.container {
    max-width: 500px;
    margin: 80px auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* Form styles */
h2 {
    text-align: center;
    color: #0D1B2A;
    margin-bottom: 30px;
    font-size: 28px;
}
form label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #0D1B2A;
}
form input[type="text"],
form input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 15px;
}

/* Buttons */
.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: bold;
    text-decoration: none;
    color: #fff;
    background: #0D1B2A;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.btn:hover {
    background: #ef621b;
}
.btn-back {
    background: gray;
    margin-left: 15px;
}
.btn-back:hover {
    background: darkgray;
}
.button-group {
    text-align: center;
}
</style>
</head>
<body>

<!-- Logo Header -->
<header>
    <img src= "../Images/Logo.png" alt="GreenLife Wellness Logo">
</header>

<div class="container">
    <h2>Update Therapist Profile</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($therapist['name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($therapist['email']); ?>" required>

        <label>Specialization:</label>
        <input type="text" name="specialization" value="<?php echo htmlspecialchars($therapist['specialization']); ?>" required>

        <div class="button-group">
            <button type="submit" class="btn">Update</button>
            <a href="Therapiest profile.php" class="btn btn-back">Back to Profile</a>
        </div>
    </form>
</div>

</body>
</html>
