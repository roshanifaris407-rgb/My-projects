<?php
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}

// ✅ Hardcoded instructors
$instructors = [
    [
        "id" => 1,
        "username" => "amalp",
        "fullname" => "Mr. Amal Perera",
        "subjects" => ["ICT Training"]
    ],
    [
        "id" => 2,
        "username" => "nishaf",
        "fullname" => "Ms. Nisha Fernando",
        "subjects" => ["Hospitality Management"]
    ],
    [
        "id" => 3,
        "username" => "sandunj",
        "fullname" => "Mr. Sandun Jayawardena",
        "subjects" => ["Engineering Courses"]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Instructors - SkillPro Institute</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: url('../Images/background.jpg') no-repeat center center/cover;
    min-height: 100vh;
}
.container {
    max-width: 850px;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.96);
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.15);
}
h2 { 
    text-align: center; 
    margin-bottom: 25px; 
    font-weight: 600;
    color: #2c3e50; 
}
.logo {
    display: block;
    margin: 0 auto 20px auto;
    width: 120px;
    height: auto;
}
.btn-back { 
    margin-bottom: 20px; 
    background: linear-gradient(135deg, #6c757d, #495057); 
    color: #fff; 
    border-radius: 8px; 
    padding: 8px 15px;
    text-decoration: none;
}
.btn-back:hover { 
    background: linear-gradient(135deg, #5a6268, #343a40); 
    color: #fff; 
    text-decoration: none;
}
.table {
    border-radius: 12px;
    overflow: hidden;
}
.table thead {
    background: linear-gradient(135deg, #2980b9, #6dd5fa);
    color: #fff;
    font-size: 1rem;
}
.table tbody tr {
    transition: all 0.2s ease-in-out;
}
.table tbody tr:hover {
    background-color: #f2f9ff;
    transform: scale(1.01);
}
.subject-badge {
    margin: 2px;
    font-size: 0.85rem;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white !important;
    padding: 6px 10px;
    border-radius: 12px;
}
</style>
</head>
<body>
<div class="container">
    <!-- Logo -->
    <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">

    <a href="instructor_management.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
    <h2><i class="fas fa-chalkboard-teacher"></i> Instructor Dashboard</h2>

    <?php if(count($instructors) > 0): ?>
    <table class="table table-bordered text-center align-middle shadow-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Teaching Subjects</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($instructors as $index => $instructor): ?>
            <tr>
                <td><strong><?php echo $index + 1; ?></strong></td>
                <td><?php echo htmlspecialchars($instructor['username']); ?></td>
                <td><?php echo htmlspecialchars($instructor['fullname']); ?></td>
                <td>
                    <?php if(count($instructor['subjects']) > 0): ?>
                        <?php foreach($instructor['subjects'] as $subject): ?>
                            <span class="badge subject-badge"><?php echo htmlspecialchars($subject); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted">No subjects</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="text-center text-muted">No instructors found.</p>
    <?php endif; ?>
</div>
</body>
</html>
