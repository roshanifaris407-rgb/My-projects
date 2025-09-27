<?php
include 'DBconnect.php';

// Redirect if no ID is provided
if (!isset($_GET['id'])) {
    header("Location: blogs.php");
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM blogs WHERE id=$id LIMIT 1");

if (mysqli_num_rows($result) == 0) {
    echo "Blog not found!";
    exit();
}

$blog = mysqli_fetch_assoc($result);

// Handle inquiry submission
if(isset($_POST['submit_inquiry'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $blog_id = intval($_GET['id']);

    $stmt = $conn->prepare("INSERT INTO inquiries (client_name, client_email, blog_id, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $blog_id, $message);
    $stmt->execute();
    $stmt->close();

    $success_msg = "Your inquiry has been submitted. Admin will respond soon.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($blog['title']) ?> - GreenLife Wellness Blog</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(rgba(13,27,42,0.7), rgba(13,27,42,0.7)),
                url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    margin: 0;
    padding: 0;
}

header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
}

header img {
    max-height: 100px;
    width: auto;
}

.container {
    width: 80%;
    margin: 40px auto;
    background-color: rgba(27,38,59,0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.5);
}

h1, h2 {
    color: #ef621b;
    margin-bottom: 20px;
    text-align: center;
}

.meta {
    text-align: center;
    color: #ccc;
    font-size: 14px;
    margin-bottom: 20px;
}

img.blog-image {
    max-width: 100%;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    display: block;
    margin-left: auto;
    margin-right: auto;
}

p {
    font-size: 18px;
    line-height: 1.6;
    text-align: justify;
}

a.back {
    display: inline-block;
    margin-top: 25px;
    padding: 12px 25px;
    background: #ef621b;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s ease;
}

a.back:hover {
    background: #d15817;
}

/* Inquiry Form Styling */
.inquiry-form {
    margin-top: 30px;
    background:#274156;
    padding:20px;
    border-radius:12px;
}

.inquiry-form h3 {
    color:#ef621b;
    margin-bottom:15px;
    text-align:center;
}

.inquiry-form input, 
.inquiry-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    border: none;
    font-size: 14px;
}

.inquiry-form button {
    padding: 10px 20px;
    background:#ef621b;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    display: block;
    margin: 0 auto;
}

.inquiry-form button:hover {
    background:#d15817;
}

.inquiry-success {
    color:#0f0;
    text-align:center;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    p {
        font-size: 16px;
    }
}
</style>
</head>
<body>

<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Center">
</header>

<div class="container">
    <h2><?= htmlspecialchars($blog['title']) ?></h2>
    <div class="meta">
        <strong>Category:</strong> <?= htmlspecialchars($blog['category']) ?> |
        <strong>Date:</strong> <?= htmlspecialchars($blog['created_at']) ?>
    </div>
    <img src="<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>" 
         alt="<?= htmlspecialchars($blog['title']) ?>" class="blog-image">
    <p><?= nl2br(htmlspecialchars($blog['content'])) ?></p>

    <!-- Inquiry Form -->
    <div class="inquiry-form">
        <h3>Submit an Inquiry</h3>
        <?php if(isset($success_msg)) echo "<p class='inquiry-success'>$success_msg</p>"; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Inquiry" rows="5" required></textarea>
            <button type="submit" name="submit_inquiry">Submit Inquiry</button>
        </form>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <a href="blogs.php" class="back">← Back to Blogs</a>
    </div>
</div>

</body>
</html>
