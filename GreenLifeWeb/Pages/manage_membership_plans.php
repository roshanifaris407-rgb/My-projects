<?php
include('dbconnect.php');

// Predefined base prices for memberships
$membershipBasePrices = [
    "Basic"   => 1000,
    "Premium" => 2000,
    "VIP"     => 3000
];

// Service prices in LKR
$servicePrices = [
    "Massage Therapy"    => 18000,  
    "Physiotherapy"      => 21000,  
    "Nutrition Guidance" => 15000,  
    "Yoga Classes"       => 12000,  
    "Ayurveda Therapy"   => 27000   
];

// Handle new membership plan insertion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['membership_type']) && !isset($_POST['delete_id'])) {
    $membership_type = $_POST['membership_type'];
    $selectedServices = isset($_POST['services']) ? $_POST['services'] : [];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $servicesStr = implode(", ", $selectedServices);

    $sql = "INSERT INTO membership_plans (membership_type, status, start_date, end_date, price, services, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $membership_type, $status, $start_date, $end_date, $price, $servicesStr);

    if ($stmt->execute()) {
        $message = "✅ Membership Plan added successfully!";
        $messageType = "success";
    } else {
        $message = "❌ Failed to add Membership Plan: " . $conn->error;
        $messageType = "error";
    }
    $stmt->close();
}

// Handle deletion of a membership plan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $sql = "DELETE FROM membership_plans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "✅ Membership Plan deleted successfully!";
        $messageType = "success";
    } else {
        $message = "❌ Failed to delete Membership Plan: " . $conn->error;
        $messageType = "error";
    }
    $stmt->close();
}

// Fetch all memberships
$sql = "SELECT * FROM membership_plans ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// Services list
$servicesList = array_keys($servicePrices);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Membership Plans</title>
<style>
body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed; 
    background-size: cover; 
    color: #fff; 
    margin:0; 
    padding:0; 
    line-height:1.6; 
}
.navbar { 
    width:100%; 
    background-color:rgba(0,0,0,0.85); 
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    padding:10px 20px; 
    position:sticky; 
    top:0; 
    z-index:999; 
}
.navbar img { height:50px; }
.nav-links { display:flex; gap:10px; }
.nav-links a { 
    color:white; 
    text-decoration:none; 
    padding:10px 16px; 
    font-weight:bold; 
    border-radius:8px; 
    transition:0.3s; 
}
.nav-links a:hover { background-color:#e74c3c; }
h2,h3 { text-align:center; margin-top:20px; color:#ff4500; text-shadow:2px 2px 4px rgba(0,0,0,0.7); font-weight:bold; }
form { background: rgba(0,0,0,0.8); padding:20px; border-radius:8px; max-width:600px; margin:0 auto 40px auto; box-shadow:0 4px 8px rgba(0,0,0,0.3);}
input[type="text"], input[type="number"], input[type="date"], select { width:90%; padding:12px; margin:10px 0; border:1px solid #555; background:#34495e; color:#fff; border-radius:6px; font-size:16px; }
select[multiple] { height:100px; overflow-y:scroll; }
input[type="submit"] { background:#e74c3c; color:#fff; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; font-weight:bold; font-size:16px; width:100%; transition:0.3s;}
input[type="submit"]:hover { background:#c0392b; }
#notification { display:none; width:70%; max-width:400px; padding:10px; margin:20px auto; border-radius:5px; font-size:16px; text-align:center; font-weight:bold; }
.success { background-color: #27ae60; color: white; }
.error { background-color: #e74c3c; color: white; }
table { width:100%; border-collapse:collapse; margin:20px 0; background:rgba(0,0,0,0.8); box-shadow:0 4px 12px rgba(0,0,0,0.4); border-radius:8px; }
th, td { padding:12px; border:1px solid #444; text-align:center; color:#fff; }
th { background-color:#e74c3c; font-size:16px; letter-spacing:1px; text-transform:uppercase; }
tr:nth-child(even){background-color: rgba(255,255,255,0.1);}
tr:hover { background-color: rgba(255,102,0,0.2);}
</style>
</head>
<body>

<div class="navbar">
    <img src="../Images/Logo.png" alt="Logo" height="100">
    <div class="nav-links">
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="home.php">Logout</a>
    </div>
</div>

<?php if(isset($message)): ?>
    <div class="<?= $messageType ?>" id="notification"><?= $message ?></div>
    <script>
        document.getElementById('notification').style.display="block";
        setTimeout(function(){ document.getElementById('notification').style.display="none"; },5000);
    </script>
<?php endif; ?>

<h3>Add New Membership Plan</h3>
<form method="POST" action="manage_membership_plans.php">
    <label for="membership_type">Membership Type:</label>
    <select id="membership_type" name="membership_type" required onchange="updatePrice()">
        <option value="">-- Select Membership --</option>
        <?php foreach($membershipBasePrices as $type=>$basePrice): ?>
            <option value="<?= $type ?>"><?= $type ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="services">Select Services:</label>
    <select id="services" name="services[]" multiple onchange="updatePrice()">
        <?php foreach($servicesList as $service): ?>
            <option value="<?= $service ?>"><?= $service ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required placeholder="Auto-calculated price"><br><br>

    <label for="status">Status:</label>
    <select id="status" name="status" required>
        <option value="Active">Active</option>
        <option value="Pending">Pending</option>
        <option value="Expired">Expired</option>
    </select><br><br>

    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required><br><br>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required><br><br>

    <input type="submit" value="Add Plan">
</form>

<h3>Existing Membership Plans</h3>
<table>
<tr>
    <th>Membership Type</th>
    <th>Services</th>
    <th>Status</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Price</th>
    <th>Action</th>
</tr>
<?php
if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
        echo "<tr>
                <td>".htmlspecialchars($row['membership_type'])."</td>
                <td>".htmlspecialchars($row['services'])."</td>
                <td>".htmlspecialchars($row['status'])."</td>
                <td>".htmlspecialchars($row['start_date'])."</td>
                <td>".htmlspecialchars($row['end_date'])."</td>
                <td>".htmlspecialchars($row['price'])."</td>
                <td>
                    <form method='POST' onsubmit=\"return confirm('Are you sure?');\">
                        <input type='hidden' name='delete_id' value='". $row['id'] ."'>
                        <input type='submit' value='Delete' style='background:#ff3300;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;'>
                    </form>
                </td>
              </tr>";
    }
} else { echo "<tr><td colspan='7'>No membership plans found</td></tr>"; }
mysqli_close($conn);
?>
</table>

<script>
const membershipBasePrices = <?= json_encode($membershipBasePrices) ?>;
const servicePrices = <?= json_encode($servicePrices) ?>;

function updatePrice(){
    const membership = document.getElementById('membership_type').value;
    const servicesSelect = document.getElementById('services');
    const selectedServices = Array.from(servicesSelect.selectedOptions).map(opt => opt.value);

    let totalPrice = 0;
    if(membershipBasePrices[membership]) totalPrice += membershipBasePrices[membership];
    selectedServices.forEach(service => {
        if(servicePrices[service]) totalPrice += servicePrices[service];
    });

    document.getElementById('price').value = totalPrice;
}
</script>

</body>
</html>
