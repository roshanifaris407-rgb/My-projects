<?php
session_start();
include 'DBconnect.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_blogs.php");
    exit();
}

$blog_id = intval($_GET['id']);

// Fetch blog data
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();
$stmt->close();

// Update blog
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle image upload
    $image = $blog['image']; // keep old image if not changed
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../Uploads/Blogs/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
        $image = $targetDir . $image; // store path
    }

    $stmt = $conn->prepare("UPDATE blogs SET category=?, title=?, content=?, image=? WHERE id=?");
    $stmt->bind_param("ssssi", $category, $title, $content, $image, $blog_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_blogs.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Blog - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(rgba(13,27,42,0.7), rgba(13,27,42,0.7)),
                url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    color: #fff;
}

.container {
    background: rgba(27,38,59,0.9);
    width: 90%;
    max-width: 700px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.5);
    text-align: center;
}

.container img.logo {
    display: block;
    margin: 0 auto 20px;
    width: 120px;
}

h2 {
    color: #ef621b;
    margin-bottom: 20px;
}

form input[type="text"],
form textarea,
form select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
    border: none;
    font-size: 14px;
}

form input[type="file"] {
    margin-bottom: 15px;
    color: #ddd;
}

form button, .back-btn {
    padding: 12px 25px;
    background: #ef621b;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

form button:hover, .back-btn:hover {
    background: #d15817;
}

img.preview {
    margin-top: 10px;
    max-width: 250px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

/* Optional: add subtle shadow for form inputs */
form input[type="text"], form textarea, form select {
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
}
</style>
</head>
<body>

<div class="container">
    <!-- Logo -->
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo" class="logo">

    <h2>Edit Blog</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($blog['category']) ?>" required>

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>

        <label>Content:</label>
        <textarea name="content" rows="12" style="height:250px; overflow-y:auto;"><?= htmlspecialchars($blog['content']) ?></textarea>
		
        <label>Image:</label>
        <input type="file" name="image" id="imageInput"><br>
        <img id="imagePreview" 
             src="<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>" 
             alt="Current Blog Image" class="preview">

        <button type="submit">Update Blog</button>
    </form>

    <a href="manage_blogs.php" class="back-btn">⬅ Back to Blog List</a>
</div>

<script>
// Live image preview
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.addEventListener('load', function() {
            imagePreview.setAttribute('src', this.result);
        });
        reader.readAsDataURL(file);
    } else {
        imagePreview.setAttribute('src', '<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>');
    }
});
</script>

</body>
</html>
