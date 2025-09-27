<?php
session_start();
include 'DBconnect.php';

// Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("<script>alert('Access denied!');window.location='loginpage.php';</script>");
}

// Fetch all blogs (for table & card view)
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Blogs - GreenLife Wellness</title>
<style>
/* Full page background image */
body {
    font-family: Arial, sans-serif;
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    color: #fff;
}

/* Semi-transparent container for content */
.container {
    max-width: 1000px;
    margin: 50px auto;
    background: rgba(27, 42, 65, 0.95);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
}

/* Header with logo */
header {
    background: rgba(27, 38, 59, 0.9);
    color: #fff;
    padding: 15px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    position: relative;
}

/* Logo styling */
.logo {
    display: block;
    margin: 0 auto 10px auto;
    width: 100px;
    height: auto;
}

/* Table styling */
h2 {
    color: #ff6600;
    text-align: center;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table, th, td {
    border: 1px solid #ddd;
}
th, td {
    padding: 12px;
    text-align: left;
}
th {
    background: #1B263B;
    color: #fff;
}
tr:nth-child(even) {
    background: rgba(255,255,255,0.1);
}
.actions a {
    text-decoration: none;
    padding: 6px 12px;
    margin: 0 5px;
    border-radius: 5px;
    color: #fff;
}
.actions .edit {
    background: #007bff;
}
.actions .delete {
    background: #dc3545;
}

/* Buttons styling */
.add-btn, .back-btn {
    display: inline-block;
    margin-bottom: 15px;
    padding: 10px 20px;
    background: #ff6600;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    margin-right: 10px;
}
.add-btn:hover, .back-btn:hover {
    background: #e65c00;
}

/* Blog Card Section */
.blogs-section {
    margin-top: 50px;
}
.blog-card {
    width: 280px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    margin: 20px;
    display: inline-block;
    vertical-align: top;
    overflow: hidden;
    transition: transform 0.3s;
    color: #000;
}
.blog-card:hover { transform: translateY(-5px); }

.blog-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border: none;
    cursor: pointer;
}

.blog-card .info {
    padding: 15px;
}
.blog-card h3 { margin: 0 0 10px; font-size: 18px; color:#1B263B; }
.blog-card p { margin: 0; color: #555; font-size:14px; }
</style>
</head>
<body>

<header>
    <!-- Logo -->
    <img src="../Images/Logo.png" alt="Logo" class="logo">
    Manage Blogs
</header>

<div class="container">
    <!-- Back and Add buttons -->
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    <a href="add_blog.php" class="add-btn">+ Add New Blog</a>

    <!-- Table View (Admin Management) -->
    <h2>Blog Management Table</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Content</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($blog = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $blog['id']; ?></td>
                <td>
                    <img src="<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>" alt="Blog Image" width="100">
                </td>
                <td><?php echo htmlspecialchars($blog['title']); ?></td>
                <td><?php echo substr(htmlspecialchars($blog['content']), 0, 100) . '...'; ?></td>
                <td><?php echo $blog['created_at']; ?></td>
                <td class="actions">
                    <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="edit">Edit</a>
                    <a href="delete_blog.php?id=<?php echo $blog['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No blogs found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Blog Card Preview Section -->
    <div class="blogs-section">
        <h2>Blog Preview (Clickable)</h2>
        <?php
        // Reset result pointer & fetch again for cards
        mysqli_data_seek($result, 0);
        while ($blog = mysqli_fetch_assoc($result)) { ?>
            <div class="blog-card">
                <a href="view_blog.php?id=<?= $blog['id'] ?>">
                    <img src="<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>" 
                         alt="<?= htmlspecialchars($blog['title']) ?>">
                </a>
                <div class="info">
                    <h3><?= htmlspecialchars($blog['title']) ?></h3>
                    <p><strong>Category:</strong> <?= htmlspecialchars($blog['category']) ?></p>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
