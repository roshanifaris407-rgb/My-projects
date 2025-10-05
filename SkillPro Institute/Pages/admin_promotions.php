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

// Handle Add Promotion
if (isset($_POST['add_promo'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount = $_POST['discount'] ?? 0;

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO promotions (title, description, discount) VALUES (:title, :description, :discount)");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'discount' => $discount
        ]);
        header("Location: admin_promotions.php");
        exit();
    }
}

// Handle Delete Promotion
if (isset($_GET['delete'])) {
    $promotion_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM promotions WHERE promotion_id = :promotion_id");
    $stmt->execute(['promotion_id' => $promotion_id]);
    header("Location: admin_promotions.php");
    exit();
}

// Fetch all promotions
$stmt = $pdo->prepare("SELECT * FROM promotions ORDER BY promotion_id DESC");
$stmt->execute();
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Promotions - SkillPro Institute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa url("../Images/promotions_background.jpg") no-repeat center center/cover;
            min-height: 100vh;
            padding-top: 60px;
        }
        .container {
            max-width: 900px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
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
    </style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <a href="admin_dashboard.php" class="btn btn-secondary btn-back">&larr; Back to Dashboard</a>

    <h2>📢 Manage Promotions / Offers</h2>

    <!-- Add Promotion Form -->
    <div class="card p-3">
        <h5>Add New Promotion</h5>
        <form method="post">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="Promotion title" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" placeholder="Promotion description"></textarea>
            </div>
            <div class="mb-3">
                <label>Discount (%)</label>
                <input type="number" name="discount" class="form-control" placeholder="Enter discount percentage">
            </div>
            <button type="submit" name="add_promo" class="btn btn-primary">Add Promotion</button>
        </form>
    </div>

    <!-- Promotions Table -->
    <div class="card p-3">
        <h5>Existing Promotions</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Discount (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($promotions): ?>
                    <?php foreach ($promotions as $promo): ?>
                        <tr>
                            <td><?= htmlspecialchars($promo['promotion_id'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($promo['title'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($promo['description'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($promo['discount'] ?? '0'); ?></td>
                            <td>
                                <a href="admin_promotions.php?delete=<?= $promo['promotion_id'] ?? ''; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No promotions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
