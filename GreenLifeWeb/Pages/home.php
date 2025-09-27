<?php
session_start();
include 'DBconnect.php'; // Database connection

// Fetch active promotions from database
$promo_result = mysqli_query($conn, "SELECT * FROM promotions WHERE status='Active' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>GreenLife Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
<style>
body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; background-color: #0D1B2A; color: #fff; }
header { background: #0D1B2A; color: #fff; padding: 20px 0; text-align: center; }
nav { display: flex; justify-content: space-between; align-items: center; background: #444; padding: 10px 20px; }
nav a { color: #fff; padding: 5px 10px; text-decoration: none; text-transform: uppercase; }
nav a:hover { background-color: #666; }
.cta-btn { background-color: #ff5722; color: white; padding: 5px 10px; text-decoration: none; font-weight: bold; border-radius: 5px; margin-left: 10px; }
.cta-btn:hover { background-color: #e64a19; }

.hero { background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../Images/Back Ground.jpg') center/cover no-repeat; position: relative; height: 100vh; width: 100%; overflow: hidden; }
.hero-text-box { position: relative; z-index: 1; background-color: rgba(0, 0, 0, 0.7); padding: 40px; border-radius: 10px; max-width: 700px; margin: 0 auto; text-align: center; top: 50%; transform: translateY(-50%); color: #fff; }
.hero-text-box h1 { font-size: 50px; margin-bottom: 10px; }
.hero-text-box p { font-size: 20px; margin-bottom: 30px; }

.about { padding: 60px 20px; background: #1B263B; text-align: center; }
.about h2 { font-size: 36px; margin-bottom: 20px; color: #ff5722; }
.about p { max-width: 800px; margin: 0 auto; font-size: 18px; line-height: 1.6; color: #ccc; }

.therapists { padding: 60px 20px; background: #0D1B2A; text-align: center; }
.therapists h2 { font-size: 36px; margin-bottom: 40px; color: #ff5722; }
.therapist-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
.therapist-card { background: #1B263B; border-radius: 10px; overflow: hidden; box-shadow: 0px 4px 6px rgba(0,0,0,0.4); transition: transform 0.3s ease; }
.therapist-card:hover { transform: translateY(-10px); }
.therapist-card img { width: 100%; height: 250px; object-fit: cover; }
.therapist-card h3 { margin: 15px 0; color: #fff; }
.therapist-card p { padding: 0 15px 20px; font-size: 15px; color: #ccc; }

.services { padding: 60px 20px; background: #1B263B; text-align: center; }
.services h2 { font-size: 36px; margin-bottom: 40px; color: #ff5722; }
.service-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
.service-card { background: #0D1B2A; border-radius: 10px; overflow: hidden; box-shadow: 0px 4px 6px rgba(0,0,0,0.4); transition: transform 0.3s ease; }
.service-card:hover { transform: translateY(-10px); }
.service-card img { width: 100%; height: 200px; object-fit: cover; }
.service-card h3 { margin: 15px 0; color: #fff; }
.service-card p { padding: 0 15px 20px; font-size: 15px; color: #ccc; }

.promotions { padding: 60px 20px; background: #1B263B; text-align: center; }
.promotions h2 { font-size: 36px; margin-bottom: 40px; color: #ff5722; }
.promo-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
.promo-card { background: #0D1B2A; border-radius: 10px; overflow: hidden; box-shadow: 0px 4px 6px rgba(0,0,0,0.4); transition: transform 0.3s ease; }
.promo-card:hover { transform: translateY(-10px); }
.promo-card img { width: 100%; height: 200px; object-fit: cover; }
.promo-card h3 { margin: 15px 0; color: #fff; }
.promo-card p { padding: 0 15px 20px; font-size: 15px; color: #ccc; }

footer { background: #0D1B2A; color: #fff; padding: 20px 0; text-align: center; margin-top: 40px; }
footer a { color: #ff5722; text-decoration: none; }
</style>
</head>
<body>
<header>
  <img src="../Images/Logo.png" alt="GreenLife Wellness Center" height="100">            
</header>

<nav>
  <div>
    <a href="Blogs.php">Blog Articles</a>
    <a href="#about">About Us</a>
    <a href="#therapists">Therapists</a>
    <a href="#services">Services</a>
    <a href="#promotions">Promotions</a>
  </div>
  <div>
    <a href="loginpage.php" class="cta-btn">Login</a>
    <a href="newmember.php" class="cta-btn">Sign Up</a>
  </div>
</nav>

<section class="hero">
  <div class="hero-text-box">
    <h1>Welcome to GreenLife Wellness</h1>
    <p>Holistic wellness for your mind, body and soul</p>
  </div>
</section>

<section class="about" id="about">
  <h2>About Us</h2>
  <p>
    At GreenLife Wellness Center, we believe in holistic healing and complete well-being. 
    Our mission is to provide a nurturing environment where traditional wisdom meets modern wellness practices. 
    With a dedicated team of therapists specializing in physiotherapy, yoga, ayurveda, massage, and nutrition guidance, 
    we aim to help you restore balance, reduce stress, and achieve a healthier lifestyle.  
    Discover a space where your mind, body, and spirit are cared for in harmony.
  </p>
</section>

<section class="therapists" id="therapists">
  <h2>Our Therapists</h2>
  <div class="therapist-grid">
    <div class="therapist-card">
      <img src= "../Images/Physiotherapiest.jpg" alt="Physiotherapist">
      <h3>Dr. Nirmala Fernando <small>(therapist3)</small></h3>
      <p>Specialist in Physiotherapy with 10+ years of experience in pain management and mobility restoration.</p>
    </div>
    <div class="therapist-card">
      <img src= "../Images/yoga therapiest.jpg" alt="Yoga Instructor">
      <h3>Mr. Sandun Jayawardena <small>(therapist2)</small></h3>
      <p>Certified Yoga Instructor and Meditation Coach, guiding clients towards inner peace and mindfulness.</p>
    </div>
    <div class="therapist-card">
      <img src= "../Images/ayurweda therapiest.jpg" alt="Ayurveda Practitioner">
      <h3>Dr. Anjali Perera <small>(therapist4)</small></h3>
      <p>Ayurveda Practitioner focusing on natural healing methods for holistic wellness of mind and body.</p>
    </div>
    <div class="therapist-card">
      <img src= "../Images/Massage therapiest.jpg" alt="Massage Therapist">
      <h3>Ms. Kavindi Senanayake <small>(admin)</small></h3>
      <p>Certified Massage Therapist with expertise in Swedish, Deep Tissue, and Aromatherapy massages to promote relaxation and healing.</p>
    </div>
    <div class="therapist-card">
      <img src= "../Images/Nutrition therapiest.jpg" alt="Nutrition Therapist">
      <h3>Mr. Tharindu Silva <small>(therapist5)</small></h3>
      <p>Expert in Nutrition Guidance with a focus on personalized diet plans, weight management, and holistic wellness for a healthier lifestyle.</p>
    </div>
  </div>
</section>

<section class="services" id="services">
  <h2>Our Services</h2>
  <div class="service-grid">
    <div class="service-card">
      <img src= "../Images/Massage Therapy.jpg" alt="Massage Therapy">
      <h3>Massage Therapy</h3>
      <p>Relax and rejuvenate with our professional massage therapies designed to reduce stress and improve circulation.</p>
    </div>
    <div class="service-card">
      <img src= "../Images/Yoga and Meditation Classes.jpg" alt="Yoga Classes">
      <h3>Yoga Classes</h3>
      <p>Join our yoga sessions for mental peace, flexibility, and improved overall well-being.</p>
    </div>
    <div class="service-card">
      <img src="../Images/physiotherapy.jpg" alt="Physiotherapy">
      <h3>Physiotherapy</h3>
      <p>Professional physiotherapy sessions to relieve pain, improve mobility, and restore your physical health effectively.</p>
    </div>
    <div class="service-card">
      <img src= "../Images/Nutrition and Diet Consultation.jpg" alt="Nutrition Guidance">
      <h3>Nutrition Guidance</h3>
      <p>Get personalized diet plans and nutritional advice from our expert wellness consultants.</p>
    </div>
    <div class="service-card">
      <img src="../Images/ayurveda.jpg" alt="Ayurveda Therapy">
      <h3>Ayurveda Therapy</h3>
      <p>Experience the ancient healing traditions of Ayurveda, with therapies designed to balance your body, mind, and spirit.</p>
    </div>
  </div>
</section>

<!-- Promotions Section -->
<section class="promotions" id="promotions">
  <h2>Current Promotions</h2>
  <div class="promo-grid">
    <?php
    if($promo_result && mysqli_num_rows($promo_result) > 0){
        while($promo = mysqli_fetch_assoc($promo_result)){
            echo '<div class="promo-card">';
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
  <p>&copy; 2025 GreenLife Wellness Center | 
     <a href="mailto:greenlifewellness@gmail.com">greenlifewellness@gmail.com</a> | 
     <a href="tel:+94771234567">+94 77 123 4567</a>
  </p>
</footer>

</body>
</html>
