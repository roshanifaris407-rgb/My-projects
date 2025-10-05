<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Courses / Content Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          text-align: center;
          background: url("../Images/background.jpg") no-repeat center center fixed;
          background-size: cover;
      }
      .dashboard-container {
          max-width: 500px;
          margin: 80px auto;
          background: rgba(255, 255, 255, 0.95); /* slightly transparent */
          padding: 30px;
          border-radius: 12px;
          box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
      }
      .logo {
          width: 120px;
          margin-bottom: 15px;
      }
      h2 {
          margin-bottom: 25px;
          color: #333;
      }
      .btn {
          display: block;
          width: 100%;
          padding: 12px;
          margin: 10px 0;
          font-size: 16px;
          font-weight: bold;
          text-decoration: none;
          color: white;
          background: #007bff;
          border-radius: 8px;
          transition: 0.3s;
      }
      .btn:hover {
          background: #0056b3;
      }
      .back-btn {
          background: #6c757d;
      }
      .back-btn:hover {
          background: #444;
      }
  </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Logo -->
    <img src= "../Images/logo1.png" alt="Logo" class="logo">

    <h2>📚 Courses / Content Management</h2>
    
    <a href="view_courses.php" class="btn">View All Courses</a>
    <a href="manage_courses.php" class="btn">Add / Edit / Delete Courses</a>
    <a href="admin_dashboard.php" class="btn back-btn">⬅ Back</a>
</div>

</body>
</html>
