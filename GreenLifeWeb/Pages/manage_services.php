<?php
session_start();
include 'DBconnect.php';

// ✅ Allow only Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

$message = "";
$messageType = "";

// ✅ Handle adding a new service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_name'])) {
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $status = trim($_POST['status']);

    if (!empty($service_name) && !empty($price)) {
        $sql = "INSERT INTO services (service_name, description, price, status, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $service_name, $description, $price, $status);

        if ($stmt->execute()) {
            $message = "✅ Service added successfully!";
            $messageType = "success";
        } else {
            $message = "❌ Failed to add service: " . $conn->error;
            $messageType = "error";
        }
        $stmt->close();
    } else {
        $message = "⚠️ Please enter all required fields.";
        $messageType = "error";
    }
}

// ✅ Handle deletion of a service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $message = "✅ Service deleted successfully!";
        $messageType = "success";
    } else {
        $message = "❌ Failed to delete service: " . $conn->error;
        $messageType = "error";
    }
    $stmt->close();
}

// ✅ Fetch all services
$sql = "SELECT * FROM services ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Services - GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0; padding: 0;
        background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
        background-size: cover;
        color: #fff;
    }
    header {
        background: rgba(0,0,0,0.7);
        padding: 15px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }
    header img {
        height: 50px;
    }
    .container { padding: 30px; max-width: 1000px; margin: auto; background: rgba(0,0,0,0.65); border-radius: 12px; }
    .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
    .success { background: #2e7d32; color: #fff; }
    .error { background: #c62828; color: #fff; }
    .card { background: rgba(27,38,59,0.95); padding: 20px; margin: 20px 0; border-radius: 10px; }
    .card h3 { color: #ef621b; margin-bottom: 10px; }
    form input, form textarea, form select {
        width: 100%; padding: 10px; margin: 8px 0;
        border: none; border-radius: 6px;
    }
    form button {
        padding: 10px 20px;
        background: #ef621b; color: #fff;
        border: none; border-radius: 6px;
        cursor: pointer;
    }
    form button:hover { background: #d15817; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #444; }
    th { background: #ef621b; color: #fff; }
    tr:hover { background: rgba(255,255,255,0.1); }
    .delete-btn {
        background: #c62828;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
    }
    .delete-btn:hover { background: #a4161a; }
    .logout {
        display: inline-block; margin-top: 20px;
        padding: 10px 20px;
        background: #ef621b; color: #fff;
        text-decoration: none; border-radius: 6px;
    }
    .logout:hover { background: #d15817; }
</style>
</head>
<body>
<header>
    <img src= "../Images/Logo.png" alt="GreenLife Wellness Logo">
    Manage Services - GreenLife Wellness
</header>
<div class="container">

    <?php if (!empty($message)) { ?>
        <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <!-- ✅ Add New Service Form -->
    <div class="card">
        <h3>Add New Service</h3>
        <form method="POST">
            <input type="text" name="service_name" placeholder="Service Name" required>
            <textarea name="description" placeholder="Service Description"></textarea>
            <input type="number" name="price" placeholder="Price (LKR)" required>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit">Add Service</button>
        </form>
    </div>

    <!-- ✅ Services Table -->
    <div class="card">
        <h3>All Services</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price (USD)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <a href="admin_dashboard.php" class="logout">⬅ Back to Dashboard</a>
</div>
</body>
</html>
