<?php
session_start();
include 'DBconnect.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

// Delete blog
if (isset($_GET['id'])) {
    $blog_id = intval($_GET['id']);

    // Optional: Delete the image file
    $img_query = $conn->prepare("SELECT image FROM blogs WHERE id = ?");
    $img_query->bind_param("i", $blog_id);
    $img_query->execute();
    $img_result = $img_query->get_result();
    if ($img_row = $img_result->fetch_assoc()) {
        $img_path = "../Uploads/Blogs/" . $img_row['image'];
        if (file_exists($img_path)) unlink($img_path);
    }

    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_blogs.php?msg=deleted");
    exit();
}
?>
