<?php
session_start();
include 'DBconnect.php';

// Check if customer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: loginpage.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $customer_id = $_SESSION['user_id'];
    $therapist_id = $_POST['therapist_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $service_type = $_POST['service_type'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO appointments 
        (member_id, therapist_id, appointment_date, appointment_time, service_type, price, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'Scheduled', NOW())");
    $stmt->bind_param("iisssd", $customer_id, $therapist_id, $appointment_date, $appointment_time, $service_type, $price);
    $stmt->execute();
    $stmt->close();

    $success = "Appointment booked successfully!";
}

// Fetch all active therapists
$therapists = $conn->query("SELECT id, username FROM therapist WHERE status='Active'");

// Fetch services from DB
$services = $conn->query("SELECT service_name, price FROM services WHERE status='Active'");
$serviceData = [];
while ($s = $services->fetch_assoc()) {
    $serviceData[$s['service_name']] = $s['price'];
}

// Add fixed services (if not already in DB)
$fixedServices = [
    "Massage Therapy" => 2500,
    "Yoga Classes" => 1500,
    "Physiotherapy" => 3000,
    "Nutrition Guidance" => 2000,
    "Ayurveda Therapy" => 3500
];
foreach ($fixedServices as $name => $price) {
    if (!isset($serviceData[$name])) {
        $serviceData[$name] = $price;
    }
}

// Fetch promotions
$promotions = $conn->query("SELECT * FROM promotions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment - GreenLife Wellness</title>
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
    max-width: 600px;
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
h2 { color: #ef621b; margin-bottom: 20px; }
form input, form select {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
    border: none;
}
form input[readonly] {
    background: #ddd;
    color: #000;
    font-weight: bold;
}
form button {
    padding: 12px 25px;
    background: #ef621b;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
}
form button:hover { background: #e65100; }
.success-msg { color: #00ff88; font-weight: bold; margin-bottom: 15px; }
.back-btn { display:inline-block; margin-top:10px; color:#fff; text-decoration:none; }
.back-btn:hover { text-decoration:underline; }

.promotions { 
    background: #1B263B; 
    padding: 15px; 
    border-radius: 10px; 
    margin-bottom: 20px; 
    color: #ffeb3b;
}
.promotions h3 { margin-bottom: 10px; }
.promotions ul { list-style-type: disc; margin-left: 20px; }
</style>
</head>
<body>

<div class="container">
    <img src="../Images/logo.png" alt="GreenLife Wellness Logo" class="logo">
    <h2>Book an Appointment</h2>

    <?php if(isset($success)) echo "<div class='success-msg'>$success</div>"; ?>

    <!-- Promotions -->
    <?php if($promotions->num_rows > 0): ?>
        <div class="promotions">
            <h3>Current Promotions</h3>
            <ul>
                <?php while($promo = $promotions->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($promo['title']) ?></strong>: <?= htmlspecialchars($promo['description']) ?>
                        <?php if(!empty($promo['discount_percent'])): ?>
                            - <?= $promo['discount_percent'] ?>% off
                        <?php elseif(!empty($promo['discount_amount'])): ?>
                            - LKR <?= $promo['discount_amount'] ?> off
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Select Therapist:</label>
        <select name="therapist_id" required>
            <option value="" disabled selected>Select a therapist</option>
            <?php while($t = $therapists->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['username']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Date:</label>
        <input type="date" name="appointment_date" required>

        <label>Time:</label>
        <input type="time" name="appointment_time" required>

        <label>Service Type:</label>
        <select name="service_type" id="service_type" required>
            <option value="" disabled selected>Select a service</option>
            <?php foreach($serviceData as $service => $price): ?>
                <option value="<?= htmlspecialchars($service) ?>"><?= htmlspecialchars($service) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Price (LKR):</label>
        <input type="text" id="price" name="price" readonly placeholder="Select a service">

        <button type="submit" name="book">Book Appointment</button>
    </form>

    <a href="customer_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<script>
// Service price data from PHP
const servicePrices = <?= json_encode($serviceData); ?>;
const serviceSelect = document.getElementById("service_type");
const priceInput = document.getElementById("price");

serviceSelect.addEventListener("change", function() {
    const selectedService = this.value;
    if (servicePrices[selectedService]) {
        priceInput.value = servicePrices[selectedService];
    } else {
        priceInput.value = "";
    }
});
</script>

</body>
</html>
