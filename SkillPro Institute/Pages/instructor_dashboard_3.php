<?php
session_start();
// Only allow instructor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'instructor') {
    header("Location: loginpage.php");
    exit();
}

// Instructor details
$instructor_name = "Mr. Sandun Jayawardena";
$instructor_subject = "Engineering Courses";

// Example Data
$classSchedules = [
    ["Course" => "Hospitality 101", "Day" => "Monday", "Time" => "9:00 AM - 11:00 AM", "Room" => "H101"],
    ["Course" => "Culinary Arts", "Day" => "Wednesday", "Time" => "1:00 PM - 3:00 PM", "Room" => "C202"],
    ["Course" => "Event Management", "Day" => "Friday", "Time" => "10:00 AM - 12:00 PM", "Room" => "E303"],
];

$students = [
    ["ID"=>"S001", "Name"=>"Rimas", "Course"=>"Hospitality 101", "Enrollment"=>"2025-09-01"],
    ["ID"=>"S002", "Name"=>"Rimas", "Course"=>"Culinary Arts", "Enrollment"=>"2025-09-02"],
    ["ID"=>"S003", "Name"=>"Rimas", "Course"=>"Event Management", "Enrollment"=>"2025-09-03"],
];

// Store uploaded materials and assignments in session to persist between requests
if(!isset($_SESSION['materials'])) $_SESSION['materials'] = [];
if(!isset($_SESSION['assignments'])) $_SESSION['assignments'] = [];
$materials = &$_SESSION['materials'];
$assignments = &$_SESSION['assignments'];

// Upload handling
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Upload Material
    if (isset($_FILES['materialsFile'])) {
        $file = $_FILES['materialsFile'];
        $target = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $target);
        $materials[] = ["File"=>$file['name'], "Course"=>"Uploaded Course", "UploadDate"=>date("Y-m-d")];
    }

    // Upload Assignment
    if (isset($_FILES['assignmentFile'])) {
        $file = $_FILES['assignmentFile'];
        $target = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $target);
        $assignments[] = ["Title"=>$file['name'], "Course"=>"Uploaded Course", "Due"=>"-", "Status"=>"Pending"];
    }

    // Delete Assignment
    if (isset($_POST['deleteAssignment'])) {
        $index = intval($_POST['deleteAssignment']);
        $fileToDelete = $uploadDir . $assignments[$index]['Title'];
        if(file_exists($fileToDelete)) unlink($fileToDelete);
        array_splice($assignments, $index, 1);
    }

    // Edit Assignment
    if (isset($_POST['editAssignment'])) {
        $index = intval($_POST['editAssignment']);
        $newTitle = trim($_POST['newTitle']);
        if(!empty($newTitle)) {
            $oldFile = $uploadDir . $assignments[$index]['Title'];
            $newFile = $uploadDir . $newTitle;
            if(file_exists($oldFile)) rename($oldFile, $newFile);
            $assignments[$index]['Title'] = $newTitle;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Instructor Dashboard - SkillPro Institute</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* --- Styles same as before --- */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Poppins', sans-serif; min-height:100vh; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); position:relative; }
body::before { content:''; position:fixed; top:0; left:0; width:100%; height:100%; background:url('../Images/Hospitality Instructor.jpg') no-repeat center center; background-size:cover; opacity:0.15; z-index:-1; }

.header { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); box-shadow:0 4px 15px rgba(0,0,0,0.1); padding:20px 30px; }
.header-content { max-width:1200px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px; }
.logo-section { display:flex; align-items:center; gap:15px; }
.logo { width:60px; height:60px; border-radius:50%; box-shadow:0 4px 10px rgba(0,0,0,0.2); border:3px solid #667eea; }
.institute-name { font-size:1.2rem; font-weight:600; color:#2c3e50; }
.instructor-info { text-align:right; }
.instructor-info h2 { color:#2c3e50; font-size:1.3rem; font-weight:600; margin-bottom:5px; }
.subject-badge { background: linear-gradient(135deg,#667eea,#764ba2); color:white; padding:4px 12px; border-radius:15px; font-size:0.85rem; display:inline-block; margin-top:5px; }

.main-container { max-width:900px; margin:40px auto; padding:0 20px; }
.dashboard-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius:20px; padding:40px 35px; box-shadow:0 10px 30px rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.3); }
.dashboard-title { text-align:center; color:#2c3e50; font-size:1.8rem; font-weight:600; margin-bottom:10px; }
.dashboard-subtitle { text-align:center; color:#7f8c8d; font-size:1rem; margin-bottom:35px; }

.menu-button { display:flex; align-items:center; gap:15px; width:100%; padding:18px 25px; margin:12px 0; font-size:1rem; font-weight:500; border:none; border-radius:12px; cursor:pointer; background:linear-gradient(135deg,#667eea,#764ba2); color:white; transition:all 0.3s ease; box-shadow:0 4px 15px rgba(102,126,234,0.3); text-align:left; }
.menu-button i { font-size:1.2rem; width:25px; text-align:center; }
.menu-button:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(102,126,234,0.4); }
.logout-button { background: linear-gradient(135deg,#e74c3c,#c0392b); box-shadow:0 4px 15px rgba(231,76,60,0.3); margin-top:25px; justify-content:center; }
.logout-button:hover { box-shadow:0 6px 20px rgba(231,76,60,0.4); }

.dashboard-table { width:100%; border-collapse: collapse; margin-top:20px; display:none; }
.dashboard-table th, .dashboard-table td { border:1px solid #ccc; padding:10px; text-align:center; }
.dashboard-table th { background: linear-gradient(135deg,#667eea,#764ba2); color:white; }

.upload-form { margin-top:15px; display:none; text-align:center; }
.upload-form input[type="file"] { padding:5px; }
.upload-form input[type="submit"] { margin-top:5px; padding:8px 15px; background:#667eea; color:white; border:none; border-radius:8px; cursor:pointer; }
.upload-form input[type="submit"]:hover { background:#764ba2; }

.action-btn { padding:4px 8px; margin:0 2px; border:none; border-radius:5px; cursor:pointer; color:white; }
.edit-btn { background:#f39c12; }
.delete-btn { background:#e74c3c; }

@media(max-width:768px){ .header-content{flex-direction:column;text-align:center;} .logo-section{flex-direction:column;} .instructor-info{text-align:center;} .dashboard-card{padding:30px 25px;} .dashboard-title{font-size:1.5rem;} }
@media(max-width:480px){ .main-container{margin:20px auto;padding:0 15px;} .dashboard-card{padding:25px 20px;} .header{padding:15px 20px;} .menu-button{padding:14px 18px;font-size:0.9rem;} .menu-button i{font-size:1rem;} }
.dashboard-card{animation:fadeInUp 0.6s ease-out;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);} }
</style>
</head>
<body>
<div class="header">
<div class="header-content">
    <div class="logo-section">
        <img src="../Images/logo1.png" alt="SkillPro Institute" class="logo">
        <div class="institute-name">SkillPro Institute</div>
    </div>
    <div class="instructor-info">
        <h2>Welcome, <?php echo htmlspecialchars($instructor_name); ?></h2>
        <p>Instructor Dashboard</p>
        <span class="subject-badge"><i class="fas fa-book"></i> <?php echo htmlspecialchars($instructor_subject); ?></span>
    </div>
</div>
</div>

<div class="main-container">
<div class="dashboard-card">
<h1 class="dashboard-title">Instructor Panel</h1>
<p class="dashboard-subtitle">Choose an option to manage your classes</p>

<!-- Materials -->
<button class="menu-button" id="uploadMaterialsBtn"><i class="fas fa-upload"></i> <span>Upload Study Materials</span></button>
<table class="dashboard-table" id="materialsTable">
<thead><tr><th>File Name</th><th>Course</th><th>Upload Date</th></tr></thead>
<tbody>
<?php foreach($materials as $row): ?>
<tr>
<td><?php echo $row['File']; ?></td>
<td><?php echo $row['Course']; ?></td>
<td><?php echo $row['UploadDate']; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<form class="upload-form" id="materialsForm" method="POST" enctype="multipart/form-data">
<input type="file" name="materialsFile" required>
<input type="submit" value="Upload Material">
</form>

<!-- Assignments -->
<button class="menu-button" id="assignmentsBtn"><i class="fas fa-tasks"></i> <span>Create & Grade Assignments</span></button>
<table class="dashboard-table" id="assignmentsTable">
<thead><tr><th>Title</th><th>Course</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($assignments as $index=>$row): ?>
<tr>
<td><?php echo $row['Title']; ?></td>
<td><?php echo $row['Course']; ?></td>
<td><?php echo $row['Due']; ?></td>
<td><?php echo $row['Status']; ?></td>
<td>
<form method="POST" style="display:inline-block;">
    <input type="hidden" name="editAssignment" value="<?php echo $index; ?>">
    <input type="text" name="newTitle" placeholder="New Title" required>
    <button type="submit" class="action-btn edit-btn">Edit</button>
</form>
<form method="POST" style="display:inline-block;">
    <input type="hidden" name="deleteAssignment" value="<?php echo $index; ?>">
    <button type="submit" class="action-btn delete-btn">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<form class="upload-form" id="assignmentsForm" method="POST" enctype="multipart/form-data">
<input type="file" name="assignmentFile" required>
<input type="submit" value="Upload Assignment">
</form>

<!-- Logout -->
<button class="menu-button logout-button" onclick="location.href='instructor_dashboard.php'"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></button>
</div>
</div>

<script>
function toggleTable(btnId, tableId, formId){
document.querySelectorAll('.dashboard-table').forEach(t => t.style.display='none');
document.querySelectorAll('.upload-form').forEach(f => f.style.display='none');

let table = document.getElementById(tableId);
table.style.display = (table.style.display==='table') ? 'none':'table';

if(formId){
    let form = document.getElementById(formId);
    form.style.display = (form.style.display==='block') ? 'none':'block';
}
}

document.getElementById('uploadMaterialsBtn').addEventListener('click',()=>toggleTable('uploadMaterialsBtn','materialsTable','materialsForm'));
document.getElementById('assignmentsBtn').addEventListener('click',()=>toggleTable('assignmentsBtn','assignmentsTable','assignmentsForm'));
</script>
</body>
</html>
