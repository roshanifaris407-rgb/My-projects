<?php
session_start();
include 'DBconnect.php';

// Block if not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die("<script>alert('Access denied. Please login as customer.');window.location='login.php';</script>");
}

// Fetch user info from database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, photo FROM newmember WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Default profile image if not uploaded
$profilePhoto = (!empty($user['photo'])) ? $user['photo'] : "../Images/default-profile.png";
$username = !empty($user['username']) ? $user['username'] : $user['email'];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Book a Service</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 30px;
            text-align: center;
            background: linear-gradient(rgba(13,27,42,0.6), rgba(13,27,42,0.6)),
                        url("../Images/Back Ground.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        h2 {
            color: #ffffff;
            margin-bottom: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        header img.logo {
            height: 100px;
        }

        header .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header img.profile {
            height: 80px;
            width: 80px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
        }

        form {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            text-align: left;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #fff;
        }

        input[type="text"], input[type="date"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* Service selection with images */
        .service-options {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            margin-bottom: 15px;
        }

        .service-card {
            min-width: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .service-card img {
            width: 100%;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .service-card.selected {
            border-color: #27ae60;
            background: rgba(39, 174, 96, 0.2);
        }

        button {
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #219150;
        }

        /* Toast styles */
        #toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 14px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 30px;
            transform: translateX(-50%);
            font-size: 15px;
            opacity: 0;
            transition: opacity 0.5s ease, top 0.5s ease;
        }

        #toast.show { visibility: visible; opacity: 1; top: 60px; }
        #toast.success { background-color: #27ae60; }
        #toast.error { background-color: #c0392b; }

        /* Back to Home button */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #217dbb;
        }
    </style>
</head>
<body>

<!-- Toast -->
<div id="toast"><?php echo isset($toastMessage) ? $toastMessage : ""; ?></div>
	
<header>
    <img src="../Images/Logo.png" alt="GreenLife Wellness Center" class="logo">
    <div class="user-info">
        <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
        <img src="<?php echo htmlspecialchars($profilePhoto); ?>" alt="My Profile" class="profile">
    </div>
</header>

<h2>Book a Service</h2>

<form action="viewbookservices.php" method="POST">
    <label for="service_name">Choose a Service:</label>
    <div class="service-options">
        <div class="service-card" data-value="Ayurvedic Therapy">
            <img src="../Images/ayurweda.jpg" alt="Ayurvedic Therapy">
            <span>Ayurvedic Therapy</span>
        </div>
        <div class="service-card" data-value="Yoga and Meditation Classes">
            <img src="../Images/Yoga and Meditation Classes.jpg" alt="Yoga and Meditation">
            <span>Yoga & Meditation</span>
        </div>
        <div class="service-card" data-value="Nutrition and Diet Consultation">
            <img src="../Images/Nutrition and Diet Consultation.jpg" alt="Nutrition & Diet">
            <span>Nutrition & Diet</span>
        </div>
        <div class="service-card" data-value="Physiotherapy">
            <img src="../Images/Physiotherapy.jpg" alt="Physiotherapy">
            <span>Physiotherapy</span>
        </div>
        <div class="service-card" data-value="Massage Therapy">
            <img src="../Images/Massage Therapy.jpg" alt="Massage Therapy">
            <span>Massage Therapy</span>
        </div>
    </div>

    <input type="hidden" name="service_name" id="serviceInput" required>

    <label for="member_name">Your Name:</label>
    <input type="text" name="member_name" placeholder="Enter your name" required>

    <label for="booking_date">Booking Date:</label>
    <input type="date" name="booking_date" required>

    <label for="booking_time">Booking Time:</label>
    <input type="time" name="booking_time" required>

    <button type="submit" name="book_service">Book Now</button>
</form>

<!-- Back to Home Button -->
<a href="home.php" class="back-btn">Back to Home</a>

<script>
    const serviceCards = document.querySelectorAll('.service-card');
    const serviceInput = document.getElementById('serviceInput');

    serviceCards.forEach(card => {
        card.addEventListener('click', () => {
            serviceCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            serviceInput.value = card.getAttribute('data-value');
        });
    });

    // Toast
    const toast = document.getElementById("toast");
    if (toast.textContent.trim() !== "") {
        toast.classList.add("show");
        setTimeout(() => { toast.classList.remove("show"); }, 1500);
    }
</script>

</body>
</html>
