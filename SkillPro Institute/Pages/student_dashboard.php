<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: loginpage.php");
    exit();
}

// Get session variables
$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['username'];
$student_email = $_SESSION['email'];

include('dbconnect.php'); // DB connect

// Fetch student data
$stmt = $conn->prepare("SELECT full_name, username, email, qr_code FROM newmember WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetch assignments
$assignments_result = $conn->query("SELECT id, title, description, due_date FROM assignments ORDER BY due_date ASC");

// Fetch all admins for messaging
$admins_result = $conn->query("SELECT id, full_name FROM newmember WHERE role = 'admin' ORDER BY full_name ASC");

// Fetch notifications (best-effort: if queries fail, default to 0)
$new_assignments = 0;
$graded_assignments = 0;
$unread_messages = 0;

$q1 = $conn->query("SELECT COUNT(*) AS count FROM assignments a 
    LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = $student_id
    WHERE s.id IS NULL");
if ($q1) $new_assignments = $q1->fetch_assoc()['count'];

$q2 = $conn->query("SELECT COUNT(*) AS count FROM submissions WHERE student_id = $student_id AND COALESCE(grade,'') <> ''");
if ($q2) $graded_assignments = $q2->fetch_assoc()['count'];

$q3 = $conn->query("SELECT COUNT(*) AS count FROM messages WHERE student_id = $student_id AND status='unread'");
if ($q3) $unread_messages = $q3->fetch_assoc()['count'];

// Handle assignment submission
$submission_msg = "";
$msg_type = ""; // success or danger
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_assignment'])) {
    if (!empty($_FILES['assignment_file']['name'])) {
        $uploadDir = "uploads/assignments/";
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        $fileName = time() . "_" . basename($_FILES['assignment_file']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $targetFile)) {
            $assignment_id = intval($_POST['assignment_id']);
            $stmt = $conn->prepare("INSERT INTO submissions (student_id, assignment_id, file_path, submitted_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $student_id, $assignment_id, $targetFile);
            if ($stmt->execute()) {
                $submission_msg = "Assignment submitted successfully!";
                $msg_type = "success";
            } else {
                $submission_msg = "Error saving submission to database.";
                $msg_type = "danger";
            }
        } else {
            $submission_msg = "Error uploading the assignment file.";
            $msg_type = "danger";
        }
    } else {
        $submission_msg = "Please select a file to upload.";
        $msg_type = "danger";
    }
}

// Handle admin message submission
$message_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $message_text = trim($_POST['message_text']);
    $admin_ids = $_POST['admin_ids'] ?? [];

    if (!empty($message_text) && !empty($admin_ids)) {
        $stmt = $conn->prepare("INSERT INTO messages (student_id, admin_id, message, status, created_at) VALUES (?, ?, ?, 'unread', NOW())");
        foreach ($admin_ids as $admin_id) {
            $admin_id_int = intval($admin_id);
            $stmt->bind_param("iis", $student_id, $admin_id_int, $message_text);
            $stmt->execute();
        }
        $message_msg = "Message sent to selected admin(s)!";
    } else {
        $message_msg = "Please enter a message and select at least one admin.";
    }
}

// Payment simulation + message (Pay Online)
$payment_msg = "";
if (isset($_GET['payment']) && $_GET['payment'] === 'success') {
    $payment_msg = "Payment successful! Thank you for your payment.";

    $check = $conn->query("SHOW TABLES LIKE 'payments'");
    if ($check && $check->num_rows > 0) {
        $amount = 25000.00;
        $status = 'success';
        $stmt = $conn->prepare("INSERT INTO payments (student_id, amount, status, payment_date) VALUES (?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ids", $student_id, $amount, $status);
            $stmt->execute();
        }
    }
}

// Fetch student inbox messages
$inbox_result = false;
$checkMsgs = $conn->query("SHOW TABLES LIKE 'messages'");
if ($checkMsgs && $checkMsgs->num_rows > 0) {
    $inbox_result = $conn->query("SELECT m.id, m.message, m.status, m.created_at, a.full_name AS admin_name 
        FROM messages m
        LEFT JOIN newmember a ON m.admin_id = a.id
        WHERE m.student_id = $student_id
        ORDER BY COALESCE(m.created_at, m.id) DESC");
}

// Fetch payment history
$payments = [];
$checkPayments = $conn->query("SHOW TABLES LIKE 'payments'");
if ($checkPayments && $checkPayments->num_rows > 0) {
    $payments_result = $conn->query("SELECT id, amount, status, receipt_file, payment_date FROM payments WHERE student_id = $student_id ORDER BY payment_date DESC");
    if ($payments_result) {
        while ($p = $payments_result->fetch_assoc()) $payments[] = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body { font-family: 'Poppins', sans-serif; background: url("../Images/background.jpg") no-repeat center center fixed; background-size: cover; padding-top: 50px; }
.dashboard-container { max-width: 900px; margin: 0 auto; background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px; text-align: center; }
.logo { width: 120px; margin-bottom: 20px; }
h2 { color: #004080; margin-bottom: 20px; }
.info-box { background: #e6f0ff; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: left; }
.logout-btn, .download-btn, .payment-btn, .send-msg-btn, .reset-btn { background: #004080; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; transition: 0.3s; text-decoration: none; margin-top: 15px; display: inline-block; }
.logout-btn:hover, .download-btn:hover, .payment-btn:hover, .send-msg-btn:hover, .reset-btn:hover { background: #0066cc; }
img.qr { width: 200px; height: 200px; margin-top: 15px; border: 1px solid #ccc; border-radius: 10px; }
.assignment-box, .message-box, .payment-box { background: #f0f8ff; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: left; }
.alert { margin-top: 15px; }
.badge-notif { background: red; color: white; padding: 3px 8px; border-radius: 50%; font-size: 0.9em; }
</style>
</head>
<body>

<div class="dashboard-container">
    <img src="../Images/logo1.png" alt="Logo" class="logo">
    <h2>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h2>

    <!-- Notifications -->
    <div class="info-box">
        <h5>Notifications:</h5>
        <p>New Assignments: <span class="badge-notif"><?php echo (int)$new_assignments; ?></span></p>
        <p>Graded Assignments: <span class="badge-notif"><?php echo (int)$graded_assignments; ?></span></p>
        <p>Unread Admin Messages: <span class="badge-notif"><?php echo (int)$unread_messages; ?></span></p>
    </div>

    <!-- Alerts -->
    <?php if (!empty($submission_msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($submission_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($message_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($payment_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($payment_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Student Info -->
    <div class="info-box">
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($student['full_name']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
    </div>

    <!-- QR Code -->
    <?php if (!empty($student['qr_code']) && file_exists($student['qr_code'])): ?>
        <img class="qr" src="<?php echo htmlspecialchars($student['qr_code']); ?>" alt="QR Code">
        <br>
        <a href="<?php echo htmlspecialchars($student['qr_code']); ?>" download="<?php echo htmlspecialchars($student['username']); ?>_qrcode.png" class="download-btn">Download QR Code</a>
    <?php else: ?>
        <p>No QR Code generated yet.</p>
    <?php endif; ?>

    <br><br>
    <a href="logout.php" class="logout-btn">Logout</a>
    <!-- Reset Password Button -->
    <a href="reset_password.php" class="reset-btn">Reset Password</a>

    <!-- Assignments Section -->
    <h3 class="mt-4">Assignments</h3>
    <?php if ($assignments_result && $assignments_result->num_rows > 0): ?>
        <?php while($assignment = $assignments_result->fetch_assoc()): ?>
            <div class="assignment-box">
                <p><strong><?php echo htmlspecialchars($assignment['title']); ?></strong></p>
                <p><?php echo htmlspecialchars($assignment['description']); ?></p>
                <p><em>Due Date: <?php echo htmlspecialchars($assignment['due_date']); ?></em></p>
                <?php
                $stmt = $conn->prepare("SELECT file_path, submitted_at, grade FROM submissions WHERE student_id=? AND assignment_id=?");
                $stmt->bind_param("ii", $student_id, $assignment['id']);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                ?>
                <?php if ($res): ?>
                    <p><strong>Submitted File:</strong> <a href="<?php echo htmlspecialchars($res['file_path']); ?>" target="_blank">View/Download</a></p>
                    <p><strong>Submitted At:</strong> <?php echo htmlspecialchars($res['submitted_at']); ?></p>
                    <p><strong>Grade:</strong> <?php echo htmlspecialchars($res['grade'] ?? 'Pending'); ?></p>
                <?php else: ?>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="assignment_file" required>
                        <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($assignment['id']); ?>">
                        <button type="submit" name="submit_assignment" class="download-btn">Submit Assignment</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No assignments available.</p>
    <?php endif; ?>

    <!-- Message to Admins -->
    <h3 class="mt-4">Send Message to Admins</h3>
    <form method="post" class="message-box">
        <div class="mb-2">
            <label for="message_text"><strong>Message:</strong></label>
            <textarea name="message_text" id="message_text" rows="3" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
            <label><strong>Select Admin(s):</strong></label>
            <select name="admin_ids[]" multiple class="form-control" required>
                <?php if ($admins_result): while($admin = $admins_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($admin['id']); ?>"><?php echo htmlspecialchars($admin['full_name']); ?></option>
                <?php endwhile; else: ?>
                    <option disabled>No admins found</option>
                <?php endif; ?>
            </select>
        </div>
        <button type="submit" name="send_message" class="send-msg-btn">Send Message</button>
    </form>

    <!-- Inbox -->
    <h3 class="mt-4">Inbox</h3>
    <?php if ($inbox_result && $inbox_result->num_rows > 0): ?>
        <?php while($msg = $inbox_result->fetch_assoc()): ?>
            <div class="message-box">
                <p><strong>From Admin:</strong> <?php echo htmlspecialchars($msg['admin_name'] ?? 'Admin'); ?> <span>(<?php echo htmlspecialchars($msg['status'] ?? ''); ?>)</span></p>
                <p><?php echo htmlspecialchars($msg['message']); ?></p>
                <p><em>Sent At: <?php echo htmlspecialchars($msg['created_at'] ?? ''); ?></em></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages in inbox.</p>
    <?php endif; ?>

    <!-- Payment Section -->
    <h3 class="mt-4">Payments</h3>
    <a href="student_dashboard.php?payment=success" class="payment-btn mb-3">Pay Online</a>

    <h4 class="mt-3">Payment History</h4>
    <?php if (!empty($payments)): ?>
        <?php foreach ($payments as $payment): ?>
            <div class="payment-box">
                <p><strong>Amount:</strong> LKR <?php echo htmlspecialchars($payment['amount']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($payment['status'])); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($payment['payment_date']); ?></p>
                <?php if (!empty($payment['receipt_file']) && file_exists($payment['receipt_file'])): ?>
                    <a href="<?php echo htmlspecialchars($payment['receipt_file']); ?>" class="download-btn" download>Download Receipt</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No payments made yet.</p>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
