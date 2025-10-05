<?php
session_start();
// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - SkillPro Institute</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
      body {
          font-family: Arial, sans-serif;
          background: #f4f6f9 url("../Images/admin.jpg") no-repeat center center/cover;
          min-height: 100vh;
      }
      .dashboard-card {
          background: #fff;
          border-radius: 12px;
          padding: 30px;
          box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
          max-width: 700px;
          margin: 50px auto;
          text-align: center;
      }
      .logo {
          width: 100px;
          margin-bottom: 15px;
      }
      h1 {
          font-size: 1.8rem;
          margin-bottom: 10px;
          color: #333;
      }
      p.subtitle {
          color: #666;
          margin-bottom: 25px;
      }
      .btn-custom {
          width: 100%;
          margin: 8px 0;
          font-weight: 500;
          border-radius: 8px;
      }
      /* Clock & Calendar */
      .time-display {
          position: absolute;
          top: 15px;
          right: 20px;
          background: rgba(255, 255, 255, 0.9);
          padding: 8px 15px;
          border-radius: 8px;
          text-align: right;
          font-size: 14px;
          color: #333;
          box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
      }
      .time-display .clock {
          font-weight: bold;
          font-size: 15px;
      }
      .time-display .date {
          font-size: 13px;
          color: #666;
      }
  </style>
</head>
<body>
  <!-- Clock & Calendar -->
  <div class="time-display">
      <div class="clock" id="clock"></div>
      <div class="date" id="date"></div>
  </div>

  <div class="container">
      <div class="dashboard-card">
          <img src="../Images/logo1.png" alt="SkillPro Institute Logo" class="logo">
          <h1>Welcome, Admin</h1>
          <p class="subtitle">Manage your institute easily</p>

          <!-- Existing options -->
          <a href="admin_user_management.php" class="btn btn-primary btn-custom">
              <i class="fas fa-users"></i> User Management
          </a>
          <a href="admin_content_management.php" class="btn btn-success btn-custom">
              <i class="fas fa-book"></i> Content Management
          </a>
          <a href="admin_promotions.php" class="btn btn-secondary btn-custom">
              <i class="fas fa-tags"></i> Promotions & Offers
          </a>
          <a href="admin_notifications.php" class="btn btn-light btn-custom">
              <i class="fas fa-bell"></i> Notifications / Announcements
          </a>

          <!-- Logout -->
          <a href="logout.php" class="btn btn-danger btn-custom">
              <i class="fas fa-sign-out-alt"></i> Logout
          </a>
      </div>
  </div>

  <script>
      function updateTime() {
          const now = new Date();
          const hours = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const seconds = String(now.getSeconds()).padStart(2, '0');
          document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;

          const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          document.getElementById('date').textContent = now.toLocaleDateString('en-US', options);
      }
      setInterval(updateTime, 1000);
      updateTime();
  </script>
</body>
</html>
