<?php
include 'DBconnect.php';

if (!isset($_GET['id'])) {
    header("Location: manage_inquiries.php");
    exit();
}

$id = intval($_GET['id']);
if (isset($_POST['submit_response'])) {
    $response = $_POST['response'];
    $stmt = $conn->prepare("UPDATE inquiries SET response=? WHERE id=?");
    $stmt->bind_param("si", $response, $id);
    $stmt->execute();
    header("Location: manage_inquiries.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM inquiries WHERE id=$id LIMIT 1");
$inquiry = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Respond to Inquiry - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
body { 
    font-family: 'Roboto', sans-serif; 
    margin: 0; 
    padding: 0; 
    background: url( "../Images/Back Ground.jpg") no-repeat center center fixed; 
    background-size: cover;
    color: #fff; 
}
header { 
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(13,27,42,0.9);
    padding: 15px; 
    color: #fff; 
    font-size: 22px; 
    font-weight: bold; 
    position: relative;
}
header img {
    position: absolute;
    left: 20px;
    height: 50px;
}
.container {
    max-width: 800px;
    margin: 40px auto;
    background: rgba(0,0,0,0.65);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.6);
}
.container h2 {
    color: #ef621b;
    margin-bottom: 15px;
}
.message-box {
    background: rgba(255,255,255,0.1);
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    line-height: 1.6;
}
textarea {
    width: 100%;
    height: 160px;
    padding: 12px;
    border-radius: 10px;
    border: none;
    resize: none;
    font-size: 15px;
}
button {
    padding: 12px 25px;
    background: #ef621b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}
button:hover {
    background: #d15817;
    transform: scale(1.05);
}
.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}
.back-link:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo">
    Respond to Inquiry
</header>

<div class="container">
    <h2>Inquiry from <?= htmlspecialchars($inquiry['client_name']) ?></h2>
    <div class="message-box">
        <strong>Message:</strong><br>
        <?= nl2br(htmlspecialchars($inquiry['message'])) ?>
    </div>

    <form method="post">
        <label for="response"><strong>Your Response:</strong></label><br>
        <textarea name="response" id="response" placeholder="Write your response here..." required><?= htmlspecialchars($inquiry['response'] ?? '') ?></textarea><br><br>
        <button type="submit" name="submit_response">Send Response</button>
    </form>

    <a href="manage_inquiries.php" class="back-link">← Back to Inquiries</a>
</div>

</body>
</html>
