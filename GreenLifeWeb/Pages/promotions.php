<?php
session_start();
include 'DBconnect.php';

// Fetch all active promotions
$result = mysqli_query($conn, "SELECT * FROM promotions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Promotions - GreenLife Wellness</title>
<style>
/* Background Image */
body { 
    font-family: Arial, sans-serif; 
    background: url("../Images/Back Ground.jpg") no-repeat center center fixed; 
    background-size: cover;
    margin:0; 
    padding:0; 
}

/* Logo at the top */
header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    background: rgba(255, 255, 255, 0.8);
}
header img {
    height: 70px;
}

/* Container for content */
.container { 
    padding: 50px 20px; 
    max-width: 1200px; 
    margin: auto; 
    background: rgba(255,255,255,0.9); 
    border-radius: 15px;
}

/* Title */
h1 { 
    text-align:center; 
    color:#0D1B2A; 
    margin-bottom: 20px; 
    font-size: 32px; 
}

/* Buttons */
.add-btn, .back-btn {
    display: block;
    width: 220px;
    margin: 0 auto 20px auto;
    padding: 12px 20px;
    text-align: center;
    background:#0D1B2A;
    color:#fff;
    text-decoration:none;
    border-radius:25px;
    font-weight:bold;
    transition:0.3s;
}
.add-btn:hover, .back-btn:hover {
    background:#ef621b;
}

/* Promotions grid layout */
.promotions-grid { 
    display:grid; 
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
    gap:30px; 
}

/* Promotion card style */
.promotion-card { 
    background:#fff; 
    border-radius:15px; 
    box-shadow:0 4px 15px rgba(0,0,0,0.1); 
    padding:30px 20px; 
    text-align:center; 
    transition:0.3s ease-in-out; 
}
.promotion-card:hover { 
    transform: translateY(-8px); 
}
.promotion-card h3 { 
    margin:10px 0; 
    color:#0D1B2A; 
    font-size:22px; 
}
.promotion-card p { 
    margin:6px 0; 
    color:#333; 
    font-size:15px; 
}
</style>
</head>
<body>

<!-- Logo Header -->
<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo">
</header>

<div class="container">
    <h1>Promotions</h1>

    <!-- Back to Dashboard Button -->
    <a href= "admin_dashboard.php" class="back-btn">Back to Dashboard</a>

    <!-- Add New Promotion Button -->
    <a href="promotion_add.php" class="add-btn">Add New Promotion</a>

    <div class="promotions-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while ($promotion = mysqli_fetch_assoc($result)) { ?>
                <div class="promotion-card">
                    <h3><?php echo htmlspecialchars($promotion['title']); ?></h3>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($promotion['description']); ?></p>
                    <p><strong>Discount:</strong> <?php echo htmlspecialchars($promotion['discount']); ?>%</p>
                    <p><strong>Valid Until:</strong> <?php echo htmlspecialchars($promotion['valid_until']); ?></p>
                </div>
            <?php } ?>
        <?php else: ?>
            <p style="text-align:center;">No promotions available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
