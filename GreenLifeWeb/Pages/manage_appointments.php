<?php
include 'dbconnect.php';

// Initialize variables for editing
$edit_mode = false;
$edit_id = 0;
$member_id = '';
$therapist_id = '';
$appointment_date = '';
$appointment_time = '';
$service_type = '';
$status = '';

// Fetch data for editing if 'edit' GET parameter is set
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows > 0) {
        $edit_mode = true;
        $row_edit = $result_edit->fetch_assoc();
        $member_id = $row_edit['member_id'];
        $therapist_id = $row_edit['therapist_id'];
        $appointment_date = $row_edit['appointment_date'];
        $appointment_time = $row_edit['appointment_time'];
        $service_type = $row_edit['service_type'];
        $status = $row_edit['status'];
    }
    $stmt->close();
}

// Update Appointment
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE appointments SET member_id=?, therapist_id=?, appointment_date=?, appointment_time=?, service_type=?, status=? WHERE id=?");
    $stmt->bind_param(
        "iissssi",
        $_POST['member_id'],
        $_POST['therapist_id'],
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        $_POST['service_type'],
        $_POST['status'],
        $_POST['appointment_id']
    );
    $stmt->execute();
    $stmt->close();
    header("Location: manage_appointments.php"); // redirect after update
    exit();
}

// Insert Appointment
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO appointments (member_id, therapist_id, appointment_date, appointment_time, service_type, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param(
        "iissss",
        $_POST['member_id'],
        $_POST['therapist_id'],
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        $_POST['service_type'],
        $_POST['status']
    );
    $stmt->execute();
    $stmt->close();
}

// Delete Appointment
if (isset($_POST['delete'])) {
    $id = intval($_POST['appointment_id']);
    $conn->query("DELETE FROM appointments WHERE id = $id");
}

// Fetch Appointments
$result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date, appointment_time");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Appointments</title>
  <style>
    body {
        font-family: Roboto, sans-serif;
        color: white;
        background: url( "../Images/Back Ground.jpg") no-repeat center center fixed;
        background-size: cover;
    }
    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(13, 27, 42, 0.8); /* dark overlay for readability */
        z-index: -1;
    }
    h2 { text-align: center; background: #ef621b; padding: 20px; border-radius: 8px; }
    form, table { width: 90%; margin: 30px auto; background: rgba(27, 38, 59, 0.9); padding: 20px; border-radius: 10px; }
    input, select { width: 100%; padding: 10px; margin: 8px 0; border: none; border-radius: 5px; }
    table { border-collapse: collapse; color: #fff; }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #415A77; }
    th { background-color: #415A77; }
    tr:nth-child(even) { background-color: rgba(30, 42, 58, 0.8); }
    input[type=submit], .btn { background: #ef621b; color: white; border: none; cursor: pointer; padding: 6px 12px; border-radius:5px; text-decoration:none; }
    input[type=submit]:hover, .btn:hover { background: #e65100; }
    .logo { display:block; margin: 0 auto 20px; width:120px; }
    .back-btn { display:inline-block; margin-bottom:15px; padding:10px 20px; background:#ff6600; color:#fff; text-decoration:none; border-radius:6px; }
    .back-btn:hover { background:#e65c00; }
    .actions a { margin: 0 5px; }
  </style>
</head>
<body>

<h2>Admin - Appointment Management</h2>

<div style="text-align:center;">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<form method="POST" action="">
  <img src="../Images/Logo.png" class="logo" alt="GreenLife Wellness Logo">
  <h3><?= $edit_mode ? "Edit Appointment" : "Schedule New Appointment" ?></h3>
  
  <input type="number" name="member_id" placeholder="Member ID" required value="<?= htmlspecialchars($member_id) ?>" />
  <input type="number" name="therapist_id" placeholder="Therapist ID" required value="<?= htmlspecialchars($therapist_id) ?>" />
  <input type="date" name="appointment_date" required value="<?= htmlspecialchars($appointment_date) ?>" />
  <input type="time" name="appointment_time" required value="<?= htmlspecialchars($appointment_time) ?>" />
  
  <label>Service Type:</label>
  <select name="service_type" required>
    <option value="" disabled <?= !$edit_mode ? 'selected' : '' ?>>Select a service</option>
    <?php
    $services = ["Ayurvedic Therapy", "Yoga & Meditation", "Nutrition & Diet", "Physiotherapy", "Massage Therapy"];
    foreach ($services as $s) {
        $selected = ($service_type == $s) ? 'selected' : '';
        echo "<option value=\"$s\" $selected>$s</option>";
    }
    ?>
  </select>

  <select name="status" required>
    <option value="">Select Status</option>
    <?php
    $statuses = ["Scheduled", "Completed", "Cancelled"];
    foreach ($statuses as $st) {
        $selected = ($status == $st) ? 'selected' : '';
        echo "<option value=\"$st\" $selected>$st</option>";
    }
    ?>
  </select>

  <?php if ($edit_mode): ?>
    <input type="hidden" name="appointment_id" value="<?= $edit_id ?>" />
    <input type="submit" name="update" value="Update Appointment" />
  <?php else: ?>
    <input type="submit" name="add" value="Add Appointment" />
  <?php endif; ?>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Member ID</th>
    <th>Therapist ID</th>
    <th>Date</th>
    <th>Time</th>
    <th>Service</th>
    <th>Status</th>
    <th>Created At</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['member_id']) ?></td>
      <td><?= htmlspecialchars($row['therapist_id']) ?></td>
      <td><?= htmlspecialchars($row['appointment_date']) ?></td>
      <td><?= htmlspecialchars($row['appointment_time']) ?></td>
      <td><?= htmlspecialchars($row['service_type']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td><?= htmlspecialchars($row['created_at']) ?></td>
      <td class="actions">
        <form method="POST" action="" style="display:inline-block;">
          <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>" />
          <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this appointment?');" />
        </form>
        <a href="?edit=<?= $row['id'] ?>" class="btn">Edit</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
