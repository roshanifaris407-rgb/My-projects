<?php
session_start();
include 'DBconnect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount = $_POST['discount'];
    $valid_until = $_POST['valid_until'];

    $stmt = $conn->prepare("INSERT INTO promotions (title, description, discount, valid_until) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $title, $description, $discount, $valid_until);

    if ($stmt->execute()) {
        echo "<script>alert('Promotion added successfully!'); window.location.href='promotions.php';</script>";
        exit();
    } else {
        echo "Error adding promotion: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Promotion - GreenLife Wellness</title>
<style>
body { 
    font-family: Arial, sans-serif; 
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    margin:0; 
    padding:0; 
}
header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    background: rgba(255, 255, 255, 0.8);
}
header img { height: 70px; }

.container { 
    padding: 50px 20px; 
    max-width: 600px; 
    margin: auto; 
    background: rgba(255,255,255,0.9); 
    border-radius: 15px;
}

h1 { 
    text-align:center; 
    color:#0D1B2A; 
    margin-bottom: 20px; 
    font-size: 28px; 
}

form label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}
form input, form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
form textarea { resize: vertical; }

.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    background:#0D1B2A;
    color:#fff;
    text-decoration:none;
    border-radius:25px;
    font-weight:bold;
    transition:0.3s;
    border: none;
    cursor: pointer;
}
.btn:hover { background:#ef621b; }

.back-btn {
    display: inline-block;
    margin-top: 20px;
    margin-left: 10px;
    padding: 12px 25px;
    background: gray;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
}
.back-btn:hover { background: darkgray; }
</style>
</head>
<body>

<header>
    <img src= "../Images/Logo.png" alt="GreenLife Wellness Logo">
</header>

<div class="container">
    <h1>Add New Promotion</h1>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Discount (%):</label>
        <input type="number" name="discount" step="0.01" required>

        <label>Valid Until:</label>
        <input type="date" name="valid_until" required>

        <button type="submit" class="btn">Add Promotion</button>
        <a href= "promotions.php" class="back-btn">Back to Promotions</a>
    </form>
</div>

</body>
</html>
