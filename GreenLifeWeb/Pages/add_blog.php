<?php
include 'DBconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO blogs (category, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $category, $title, $content);

    if ($stmt->execute()) {
        echo "<p style='color:green; text-align:center;'>✅ Blog added successfully!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Blog</title>
<style>
/* Full page background image */
body {
    font-family: Arial, sans-serif;
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    margin: 0;
    padding: 0;
}

/* Container styling */
.container {
    max-width: 600px;
    margin: 50px auto;
    background: rgba(27, 42, 65, 0.9); /* semi-transparent */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
}

/* Logo at the top */
.logo {
    display: block;
    margin: 0 auto 20px auto;
    width: 120px;
    height: auto;
}

h2 {
    text-align: center;
    color: #ff6600;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
    color: #ffcc99;
}

input[type="text"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 6px;
    background: #2c3e55;
    color: #fff;
    font-size: 14px;
}

button, .back-btn {
    width: 100%;
    padding: 12px;
    background: #ff6600;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
    text-align: center;
    display: inline-block;
    margin-bottom: 15px;
    text-decoration: none;
}

button:hover, .back-btn:hover {
    background: #e65c00;
}
</style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/Logo.png" alt="Logo" class="logo">

    <!-- Back to Blogs Button -->
    <a href="manage_blogs.php" class="back-btn">⬅ Back to Blogs</a>

    <h2>Add New Blog</h2>
    <form method="post">
        <label>Category:</label>
        <select name="category" required>
            <option value="Success Stories">Success Stories</option>
            <option value="Health Recipes">Health Recipes</option>
            <option value="Workout Routines">Workout Routines</option>
            <option value="Wellness Tips">Wellness Tips</option>
        </select>

        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Content:</label>
        <textarea name="content" rows="5" required></textarea>

        <button type="submit">➕ Add Blog</button>
    </form>
</div>
</body>
</html>
