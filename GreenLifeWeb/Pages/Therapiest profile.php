<?php
session_start();
include 'DBconnect.php';

// Fetch active therapists only
$result = mysqli_query($conn, "SELECT * FROM therapist WHERE status='Active' ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Therapists - GreenLife Wellness</title>
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

/* Therapist grid layout */
.therapist-grid { 
    display:grid; 
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
    gap:30px; 
}

/* Therapist card style */
.therapist-card { 
    background:#fff; 
    border-radius:15px; 
    box-shadow:0 4px 15px rgba(0,0,0,0.1); 
    padding:30px 20px; 
    text-align:center; 
    transition:0.3s ease-in-out; 
}
.therapist-card:hover { 
    transform: translateY(-8px); 
}
.therapist-card img {
    width:120px; 
    height:120px; 
    object-fit:cover; 
    border-radius:50%; 
    margin-bottom:15px; 
    border:3px solid #ef621b;
}
.therapist-card h3 { 
    margin:10px 0; 
    color:#0D1B2A; 
    font-size:22px; 
}
.therapist-card p { 
    margin:6px 0; 
    color:#333; 
    font-size:15px; 
}

/* Update Profile Button style */
.btn {
    display:inline-block;
    margin-top:15px;
    padding:10px 18px;
    background:#0D1B2A;
    color:#fff;
    text-decoration:none;
    border-radius:25px;
    font-weight:bold;
    transition:0.3s;
}
.btn:hover { 
    background:#ef621b; 
}
</style>
</head>
<body>

<!-- Logo Header -->
<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Logo">
</header>

<div class="container">
    <h1>Manage Therapists</h1>

    <!-- Back to Dashboard Button -->
    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

    <!-- Add New Therapist Button -->
    <a href="therapist_add.php" class="add-btn">Add New Therapist</a>

    <div class="therapist-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while ($therapist = mysqli_fetch_assoc($result)) { ?>
                <div class="therapist-card">
                    <!-- Profile Image from database or default -->
                    <img src="<?php echo htmlspecialchars($therapist['profile_image'] ?? '../Images/Therapist_profile.png'); ?>" alt="Therapist Profile">
                    
                    <h3><?php echo htmlspecialchars($therapist['name'] ?? ''); ?></h3>
                    <p><strong>Specialty:</strong> <?php echo htmlspecialchars($therapist['specialty'] ?? ''); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($therapist['email'] ?? ''); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($therapist['phone'] ?? ''); ?></p>

                    <!-- Update Profile Button -->
                    <a href="therapist_update.php?id=<?php echo htmlspecialchars($therapist['id']); ?>" class="btn">Update Profile</a>
                </div>
            <?php } ?>
        <?php else: ?>
            <p style="text-align:center;">No therapists available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
