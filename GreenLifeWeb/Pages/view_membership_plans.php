<?php
session_start();
include 'DBconnect.php'; // use the same DB connection file for consistency

// Block if not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die("<script>alert('Access denied. Please login as customer.');window.location='login.php';</script>");
}

// Fetch user profile
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, photo FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Require profile photo
if (!$user['photo']) {
    die("<script>alert('Please upload a profile photo first!');window.location='signup.php';</script>");
}

// Predefined services with suggested prices
$services = [
    "Ayurvedic Therapy" => 5000,
    "Yoga and Meditation Classes" => 3000,
    "Nutrition and Diet Consultation" => 4000,
    "Physiotherapy" => 6000,
    "Massage Therapy" => 3500
];

// Base prices for membership types
$membershipPrices = [
    "Basic" => 2000,
    "Premium" => 4000,
    "VIP" => 6000
];

// Handle form submission to add membership
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $membership_type = $_POST['membership_type'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $service = $_POST['service'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO memberships (member_id, membership_type, status, start_date, end_date, service, price, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssssi", $member_id, $membership_type, $status, $start_date, $end_date, $service, $price);
    $stmt->execute();
    $stmt->close();
}

// Fetch existing membership plans
$result = $conn->query("SELECT * FROM memberships ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Membership Plans</title>
  <style>
    body { 
        font-family: Roboto, sans-serif; 
        color: white; 
        padding: 20px;
        margin: 0;
        background: linear-gradient(rgba(13,27,42,0.8), rgba(13,27,42,0.8)), url("../Images/Back Ground.jpg") no-repeat center center;
        background-size: cover;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    header img.logo { max-height: 100px; }
    header img.profile {
        height: 80px; width: 80px; border-radius: 50%; border: 3px solid #fff; object-fit: cover;
    }
    h2 { 
        text-align: center; 
        background: #ef621b; 
        padding: 20px; 
        border-radius: 10px; 
    }
    table { 
        width: 95%; 
        margin: 30px auto; 
        background: rgba(27,38,59,0.9); 
        border-collapse: collapse; 
        border-radius: 10px; 
        overflow: hidden; 
    }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #415A77; }
    th { background-color: #415A77; }
    tr:nth-child(even) { background-color: rgba(30,42,58,0.85); }
    form { width: 90%; margin: 20px auto; background: rgba(27,38,59,0.9); padding: 20px; border-radius: 10px; display: flex; flex-direction: column; gap: 12px; }
    input, select, button { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; }
    button { background-color: #ef621b; color: white; cursor: pointer; border: none; transition: 0.3s; }
    button:hover { background-color: #d15817; }
    .back-btn { display: block; width: 180px; margin: 20px auto; text-align: center; background-color: #ef621b; color: white; text-decoration: none; padding: 12px; border-radius: 8px; font-weight: bold; transition: 0.3s; }
    .back-btn:hover { background-color: #d15817; }
  </style>
</head>
<body>

<header>
   <img src="../Images/Logo.png" alt="GreenLife Wellness Center" class="logo">
   <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="profile">
</header>

<h2>Add New Membership Plan</h2>
<form method="POST">
    <label>Member ID:</label>
    <input type="number" name="member_id" value="<?php echo $user_id; ?>" readonly required>

    <label>Membership Type:</label>
    <select id="membership" name="membership_type" onchange="updatePrice()" required>
        <option value="">Select Membership</option>
        <option value="Basic">Basic</option>
        <option value="Premium">Premium</option>
        <option value="VIP">VIP</option>
    </select>

    <label>Status:</label>
    <select name="status" required>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>

    <label>Start Date:</label>
    <input type="date" name="start_date" required>

    <label>End Date:</label>
    <input type="date" name="end_date" required>

    <label>Service:</label>
    <select name="service" id="serviceSelect" onchange="updatePrice()" required>
        <?php foreach ($services as $serviceName => $servicePrice): ?>
            <option value="<?= htmlspecialchars($serviceName) ?>" data-price="<?= $servicePrice ?>"><?= htmlspecialchars($serviceName) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Price (LKR):</label>
    <input type="number" name="price" id="priceField" required>

    <button type="submit">Add Membership</button>
</form>

<h2>Membership Plans</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Member ID</th>
    <th>Type</th>
    <th>Status</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Service</th>
    <th>Price (LKR)</th>
    <th>Created At</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['member_id']) ?></td>
      <td><?= htmlspecialchars($row['membership_type']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td><?= htmlspecialchars($row['start_date']) ?></td>
      <td><?= htmlspecialchars($row['end_date']) ?></td>
      <td><?= htmlspecialchars($row['service']) ?></td>
      <td><?= htmlspecialchars($row['price']) ?></td>
      <td><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<a href="customer_page.php" class="back-btn">Back to Dashboard</a>

<script>
const membershipPrices = {
    "Basic": 2000,
    "Premium": 4000,
    "VIP": 6000
};

function updatePrice() {
    var membershipSelect = document.getElementById("membership");
    var serviceSelect = document.getElementById("serviceSelect");
    var priceField = document.getElementById("priceField");

    var selectedMembership = membershipSelect.value;
    var selectedServicePrice = parseInt(serviceSelect.options[serviceSelect.selectedIndex].getAttribute("data-price"));

    if(selectedMembership && selectedServicePrice) {
        priceField.value = membershipPrices[selectedMembership] + selectedServicePrice;
    } else {
        priceField.value = "";
    }
}

window.onload = updatePrice;
</script>

</body>
</html>

<?php $conn->close(); ?>
