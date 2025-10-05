<?php
session_start();
include '../Pages/DBconnect.php'; // Correct relative path to DBconnect.php

// Fetch active promotions from database
$promo_result = mysqli_query($conn, "SELECT * FROM promotions WHERE status='Active'");

// Check for success message
$show_message = false;
if(isset($_GET['success']) && $_GET['success'] == 1){
    $show_message = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SkillPro Institute</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
<style>
/* Global Styles */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { 
  font-family: 'Poppins', sans-serif; 
  background: url("../Images/background.jpg") no-repeat center center fixed; 
  background-size: cover;
  color: #fff; 
}

/* Header */
header { background: linear-gradient(135deg, #0d47a1, #1976d2); padding: 15px 0; text-align: center; }
header img { height: 120px; }

/* Navbar */
nav { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background: #1565c0; flex-wrap: wrap; }
nav a { color: #fff; padding: 10px 15px; text-decoration: none; font-weight: 600; transition: 0.3s; }
nav a:hover { background: #0d47a1; border-radius: 5px; }
.cta-btn { background: #ff5722; border-radius: 6px; margin-left: 8px; }
.cta-btn:hover { background: #e64a19; }

/* Hero Section */
.hero { background: url('../Images/Back Ground.jpg') center/cover no-repeat; height: 90vh; display: flex; justify-content: center; align-items: center; text-align: center; position: relative; color: #fff; }
.hero::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.hero-text-box { position: relative; z-index: 2; max-width: 700px; }
.hero-text-box h1 { font-size: 50px; margin-bottom: 15px; font-weight: 700; color: #fff; }
.hero-text-box p { font-size: 20px; color: #fff; }

/* Section Common */
section { padding: 60px 20px; text-align: center; background: transparent; }
section h2 { font-size: 36px; margin-bottom: 30px; color: #fff; }

/* About */
.about p { max-width: 800px; margin: 0 auto; font-size: 18px; line-height: 1.7; color: #fff; }

/* Grid Layout */
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
.card { background: rgba(0,0,0,0.6); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card:hover { transform: translateY(-10px); box-shadow: 0 8px 20px rgba(0,0,0,0.5); }
.card img { width: 100%; height: 220px; object-fit: cover; }
.card h3 { margin: 15px 0; font-size: 20px; color: #fff; }
.card p { padding: 0 15px 20px; font-size: 15px; color: #fff; }

/* Inquiry Form */
.inquiry-form { max-width: 600px; margin: 0 auto; background: rgba(0,0,0,0.6); padding: 30px; border-radius: 12px; position: relative; }
.inquiry-form input, .inquiry-form textarea { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 6px; border: none; }
.inquiry-form button { background: #ff5722; color: #fff; padding: 12px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
.inquiry-form button:hover { background: #e64a19; }

/* Success Message */
.success-message {
    position: relative;
    background-color: #4caf50;
    color: #fff;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-weight: 600;
    text-align: center;
}

/* Promotions */
.promotions h2 { color: #fff; }
.promotions .card p { color: #fff; }

/* Footer */
footer { background: #0d47a1; color: #fff; padding: 20px; text-align: center; margin-top: 40px; }
footer a { color: #ffcc80; text-decoration: none; }
footer a:hover { text-decoration: underline; }

/* Responsive */
@media (max-width: 768px) {
  nav { flex-direction: column; }
  nav a { display: block; margin: 5px 0; }
  .hero-text-box h1 { font-size: 36px; }
  .hero-text-box p { font-size: 16px; }
  section h2 { font-size: 28px; }
  header img { height: 100px; }
}
</style>
</head>
<body>
<header>
  <img src="../Images/logo1.png" alt="SkillPro Institute Logo">            
</header>

<nav>
  <div>
    <a href="#Inquiries">Inquiries</a>
    <a href="#about">About Us</a>
    <a href="#Instructor">Instructors</a>
    <a href="#Events">Events</a>
    <a href="#promotions">Promotions</a>
  </div>
  <div>
    <a href="loginpage.php" class="cta-btn">Login</a>
    <a href="newmember.php" class="cta-btn">Sign Up</a>
  </div>
</nav>

<section class="hero">
  <div class="hero-text-box">
    <h1>Welcome to Skill Pro Institute</h1>
    <p>Learn. Grow. Succeed with SkillPro Institute.</p>
  </div>
</section>

<section class="about" id="about">
  <h2>About Us</h2>
  <p>
    SkillPro Institute is a leading vocational training institute in Sri Lanka, registered under the Tertiary and Vocational Education Commission (TVEC). With branches in Colombo, Kandy, and Matara, we provide practical, job-oriented training in fields such as ICT, Engineering, Hospitality, and more. Our mission is to equip students with the skills needed to succeed in today’s competitive job market and contribute to national development.
  </p>
</section>

<section class="Instructor" id="Instructor">
  <h2>Our Instructors</h2>
  <div class="grid">
    <div class="card">
      <img src="../Images/ICT Instructor.jpg" alt="ICT Instructor">
      <h3>Mr. Amal Perera</h3>
      <p>Specialist in ICT training with 10+ years of experience in software development, networking, and digital technologies.</p>
    </div>
    <div class="card">
      <img src="../Images/Hospitality Instructor.jpg" alt="Hospitality Instructor">
      <h3>Ms. Nisha Fernando</h3>
      <p>Certified Hospitality Trainer with experience in hotel management, customer service, and guest relations, guiding students for real-world industry skills.</p>
    </div>
    <div class="card">
      <img src="../Images/Engineering Instructor.jpg" alt="Engineering Instructor">
      <h3>Mr. Sandun Jayawardena</h3>
      <p>Engineering Specialist focusing on practical skills, project-based learning, and innovative solutions to prepare students for the engineering industry.</p>
    </div>
  </div>
</section>

<section class="Events" id="Events">
  <h2>Our Events</h2>
  <div class="grid">
    <div class="card">
      <img src="../Images/courses.jpg" alt="Courses">
      <h3>Courses</h3>
      <p>
        ICT Training – 10th September 2025  (LKR 15,000)<br>
        Engineering Courses – 15th September 2025 (LKR 18,000)<br>
        Hospitality Management – 20th September 2025 (LKR 20,000)
      </p>
    </div>
    <div class="card">
      <img src="../Images/Workshops.jpg" alt="Workshops & Seminars">
      <h3>Workshops & Seminars</h3>
      <p>
        Resume Building & Interview Skills Workshop – 25th September 2025 (LKR 3,000)<br>
        Advanced Excel Workshop – 30th September 2025 (LKR 4,000)<br>
        Hospitality Trends Seminar – 5th October 2025 (LKR 5,000)
      </p>
    </div>
    <div class="card">
      <img src="../Images/Exams.jpg" alt="Exams & Assessments">
      <h3>Exams & Assessments</h3>
      <p>
        ICT Module 1 Exam – 15th October 2025 (LKR 2,500)<br>
        Engineering Practical Assessment – 20th October 2025 (LKR 3,500)<br>
        Hospitality Management Evaluation – 25th October 2025 (LKR 4,000)
      </p>
    </div>
    <div class="card">
      <img src="../Images/Job Fairs.jpg" alt="Job Fairs & Career Events">
      <h3>Job Fairs & Career Events</h3>
      <p>
        Annual Job Fair for Graduates – 10th November 2025 (Free Entry)<br>
        Industry Networking Day – 15th November 2025 (LKR 1,500)
      </p>
    </div>
  </div>
</section>

<section class="Inquiries" id="Inquiries">
  <h2>Contact / Inquiries</h2>

  <?php if($show_message): ?>
    <div class="success-message">✅ Your inquiry has been submitted successfully!</div>
  <?php endif; ?>

  <div class="inquiry-form">
    <form action="submit_inquiry.php" method="post">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <input type="text" name="subject" placeholder="Subject" required>
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit">Send Inquiry</button>
    </form>
  </div>
</section>

<section class="promotions" id="promotions">
  <h2>Current Promotions</h2>
  <div class="grid">
    <?php
    if($promo_result && mysqli_num_rows($promo_result) > 0){
        while($promo = mysqli_fetch_assoc($promo_result)){
            echo '<div class="card">';
            if(!empty($promo['image'])){
                echo '<img src="../Images/Promotions/'.$promo['image'].'" alt="'.htmlspecialchars($promo['title']).'">';
            }
            echo '<h3>'.htmlspecialchars($promo['title']).'</h3>';
            echo '<p>'.htmlspecialchars($promo['description']).'</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No active promotions at the moment. Please check back later!</p>';
    }
    ?>
  </div>
</section>

<footer>
  <p>&copy; 2025 SkillPro Institute | 
     <a href="mailto:skillpro@gmail.com">skillpro@gmail.com</a> | 
     <a href="tel:+94771234567">+94 77 123 4567</a>
  </p>
</footer>

</body>
</html>
