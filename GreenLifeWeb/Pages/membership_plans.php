<?php
session_start();
include 'DBconnect.php';

// (Optional) show errors during development
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Check if customer is logged in
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'customer') {
    header("Location: loginpage.php");
    exit();
}

// Fetch customer details
$user = ['username' => 'User', 'email' => ''];
if ($stmt = $conn->prepare("SELECT id, username, email FROM newmember WHERE id = ?")) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user = $row;
    }
    $stmt->close();
}

// Handle membership plan application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_plan'])) {
    $plan_id = (int)($_POST['plan_id'] ?? 0);
    $member_id = (int)$_SESSION['user_id'];

    // Insert into customer_memberships table
    if ($plan_id > 0) {
        $stmt = $conn->prepare("
            INSERT INTO customer_memberships (member_id, plan_id, status, start_date, end_date)
            SELECT ?, id, 'Pending', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
            FROM membership_plans
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $member_id, $plan_id);

        if ($stmt->execute()) {
            $success_msg = "Membership plan applied successfully!";
        } else {
            $error_msg = "Failed to apply membership plan.";
        }
        $stmt->close();
    } else {
        $error_msg = "Invalid plan selected.";
    }
}

// Fetch all membership plans
$plansResult = null;
if ($stmt = $conn->prepare("SELECT id, membership_type, price, service, services, benefits FROM membership_plans ORDER BY id ASC")) {
    $stmt->execute();
    $plansResult = $stmt->get_result();
    // no need to close now; result set lives after closing
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Membership Plans - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: url('../Images/dashboard-bg.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
}
.container {
    background: rgba(13, 27, 42, 0.85);
    width: 90%;
    max-width: 1000px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.6);
    text-align: center;
}
.container img.logo {
    display: block;
    margin: 0 auto 20px;
    width: 120px;
}
h1 {
    color: #ef621b;
    margin-bottom: 20px;
}
h2 {
    margin-top: 30px;
    margin-bottom: 20px;
}
.plan-card {
    background: rgba(27,38,59,0.9);
    padding: 20px;
    margin: 15px;
    border-radius: 10px;
    display: inline-block;
    width: 280px;
    vertical-align: top;
    text-align: left;
}
.plan-card h3 {
    color: #ffb74d;
}
.plan-card p {
    margin: 5px 0;
}
.plan-card button {
    background: #ef621b;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}
.plan-card button:hover {
    background: #e65100;
}
.success-msg {
    color: #4caf50;
    font-weight: bold;
    margin-bottom: 15px;
}
.error-msg {
    color: #ff4444;
    font-weight: bold;
    margin-bottom: 15px;
}
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 15px;
    background: #2196f3;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}
.back-btn:hover {
    background: #1565c0;
}
</style>
</head>
<body>

<div class="container">
    <!-- Logo -->
    <img src="../Images/logo.png" alt="GreenLife Wellness Logo" class="logo">

    <!-- Back to Dashboard Button -->
    <a href= "customer_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <h1>Welcome, <?= htmlspecialchars($user['username'] ?? 'User') ?>!</h1>

    <?php if (isset($success_msg)): ?>
        <div class="success-msg"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <?php if (isset($error_msg)): ?>
        <div class="error-msg"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <h2>Available Membership Plans</h2>

    <?php if ($plansResult && $plansResult->num_rows > 0) { ?>
        <?php while ($plan = $plansResult->fetch_assoc()) { ?>
            <div class="plan-card">
                <h3><?= htmlspecialchars($plan['membership_type'] ?? 'N/A') ?></h3>
                <p><strong>Price:</strong> LKR <?= htmlspecialchars($plan['price'] ?? '0.00') ?></p>
                <p><strong>Service:</strong> <?= htmlspecialchars($plan['service'] ?? 'N/A') ?></p>
                <?php if (!empty($plan['services'])) { ?>
                    <p><strong>Additional Services:</strong> <?= htmlspecialchars($plan['services']) ?></p>
                <?php } ?>
                <?php if (!empty($plan['benefits'])) { ?>
                    <p><strong>Benefits:</strong> <?= htmlspecialchars($plan['benefits']) ?></p>
                <?php } ?>
                <form method="post">
                    <input type="hidden" name="plan_id" value="<?= (int)($plan['id'] ?? 0) ?>">
                    <button type="submit" name="apply_plan">Apply Plan</button>
                </form>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No membership plans available.</p>
    <?php } ?>
</div>

</body>
</html>
