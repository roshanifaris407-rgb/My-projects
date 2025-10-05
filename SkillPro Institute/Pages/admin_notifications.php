<?php
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

// Database connection
$host = 'localhost';
$db   = 'skillpro_institute';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Add Notification
if (isset($_POST['add_notification'])) {
    $title = $_POST['title'];
    $message = $_POST['message'] ?? '';

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO notifications (title, message) VALUES (:title, :message)");
        $stmt->execute([
            'title' => $title,
            'message' => $message
        ]);
        header("Location: admin_notifications.php");
        exit();
    }
}

// Handle Delete Notification
if (isset($_GET['delete'])) {
    $notification_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE notification_id = :notification_id");
    $stmt->execute(['notification_id' => $notification_id]);
    header("Location: admin_notifications.php");
    exit();
}

// Fetch all notifications
$stmt = $pdo->prepare("SELECT * FROM notifications ORDER BY created_at DESC");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Notifications - SkillPro Institute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url("../Images/background.jpg") no-repeat center center/cover;
            min-height: 100vh;
            padding-top: 60px;
        }
        .container {
            max-width: 900px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            color: #333;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-back {
            margin-bottom: 20px;
        }
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            width: 120px;
        }
        table th, table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <a href="admin_dashboard.php" class="btn btn-secondary btn-back">&larr; Back to Dashboard</a>

    <h2>🔔 Manage Notifications / Announcements</h2>

    <!-- Add Notification Form -->
    <div class="card p-3">
        <h5>Add New Notification</h5>
        <form method="post">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="Notification title" required>
            </div>
            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" placeholder="Notification message"></textarea>
            </div>
            <button type="submit" name="add_notification" class="btn btn-primary">Add Notification</button>
        </form>
    </div>

    <!-- Notifications Table -->
    <div class="card p-3">
        <h5>Existing Notifications</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($notifications): ?>
                    <?php foreach ($notifications as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['notification_id'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($note['title'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($note['message'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($note['created_at'] ?? ''); ?></td>
                            <td>
                                <a href="admin_notifications.php?delete=<?= $note['notification_id'] ?? ''; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No notifications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
