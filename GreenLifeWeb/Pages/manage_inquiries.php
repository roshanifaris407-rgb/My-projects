<?php
session_start();
include 'DBconnect.php';

// Fetch all inquiries with related blog title
$result = mysqli_query($conn, "SELECT inquiries.*, blogs.title AS blog_title 
                               FROM inquiries 
                               LEFT JOIN blogs ON inquiries.blog_id = blogs.id 
                               ORDER BY inquiries.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Inquiries</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin:0;
        padding:0;
        background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
        background-size: cover;
    }
    .container {
        max-width: 1100px;
        margin: 40px auto;
        background: rgba(255,255,255,0.95);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .logo {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo img {
        width: 140px;
        height: auto;
    }
    h2 {
        text-align: center;
        color: #274156;
        margin-bottom: 20px;
    }
    table {
        width:100%;
        border-collapse: collapse;
        margin-top:20px;
        background:#fff;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding:14px;
        text-align:left;
        border-bottom:1px solid #ddd;
    }
    th {
        background:#ef621b;
        color:#fff;
    }
    tr:hover {
        background:#f9f9f9;
    }
    a.respond-btn {
        display:inline-block;
        padding:8px 14px;
        background:#274156;
        color:#fff;
        border-radius:6px;
        text-decoration:none;
        font-size: 14px;
    }
    a.respond-btn:hover {
        background:#1b2738;
    }
    i {
        color:#888;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../Images/logo.png" alt="Logo">
        </div>
        <h2>Client Inquiries</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Blog</th>
                <th>Message</th>
                <th>Response</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['client_email']) ?></td>
                <td><?= htmlspecialchars($row['blog_title'] ?? 'N/A') ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= $row['response'] ? htmlspecialchars($row['response']) : "<i>Not yet responded</i>" ?></td>
                <td>
                    <a href="respond_inquiry.php?id=<?= $row['id'] ?>" class="respond-btn">Respond</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
