<?php
session_start();
include 'DBconnect.php';

// Check if customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: loginpage.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Fetch all appointments for this customer with therapist info
$stmt = $conn->prepare("
    SELECT a.id, a.appointment_date, a.appointment_time, a.service_type, a.status, t.username AS therapist_name
    FROM appointments a
    JOIN therapist t ON a.therapist_id = t.id
    WHERE a.member_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Appointments - GreenLife Wellness</title>
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
    background: rgba(13, 27, 42, 0.9);
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
h2 {
    color: #ef621b;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: rgba(27,38,59,0.8);
}
table th, table td {
    padding: 12px;
    border: 1px solid #444;
    text-align: center;
}
table th {
    background-color: #ef621b;
    color: #fff;
}
.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #ef621b;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s;
}
.back-btn:hover {
    background: #e65100;
}
.status {
    font-weight: bold;
}
.status.Scheduled { color: #00ff88; }
.status.Completed { color: #00aaff; }
.status.Cancelled { color: #ff4444; }
</style>
</head>
<body>

<div class="container">
    <img src="../Images/logo.png" alt="GreenLife Wellness Logo" class="logo">
    <h2>My Appointments</h2>

    <?php if(count($appointments) > 0): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Therapist</th>
                <th>Service</th>
                <th>Status</th>
            </tr>
            <?php foreach($appointments as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                <td><?= htmlspecialchars($a['appointment_time']) ?></td>
                <td><?= htmlspecialchars($a['therapist_name']) ?></td>
                <td><?= htmlspecialchars($a['service_type']) ?></td>
                <td class="status <?= $a['status'] ?>"><?= htmlspecialchars($a['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have no appointments yet.</p>
    <?php endif; ?>

    <a href="customer_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
