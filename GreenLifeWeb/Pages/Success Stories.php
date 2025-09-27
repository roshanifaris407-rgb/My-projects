<?php
// successtory.php
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Success Stories - GreenLife Wellness</title>
<style>
    body {
        background: url( "../Images/Back Ground.jpg") no-repeat center center fixed; 
        background-size: cover; /* Make sure it covers the screen */
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.6;
        color: #fff;
    }

    /* Add a dark overlay so text is readable */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(13, 27, 42, 0.85); /* same as your old dark background but transparent */
        z-index: -1;
    }

    header {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px 0;
    }

    header img {
        max-height: 100px;
        width: auto;
    }

    .container {
        width: 80%;
        margin: 30px auto 50px auto;
        background-color: rgba(27, 38, 59, 0.9); /* semi-transparent container */
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0px 8px 25px rgba(0,0,0,0.4);
    }

    h1 {
        text-align: center;
        color: #ef621b;
        margin-bottom: 30px;
    }

    .story {
        margin-bottom: 40px;
    }

    .story img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: 0px 6px 15px rgba(0,0,0,0.5);
    }

    .story p {
        font-size: 18px;
        text-align: justify;
        color: #f0f0f0;
    }

    .back-link {
        display: block;
        margin-top: 30px;
        text-align: center;
    }

    .back-link a {
        text-decoration: none;
        color: #fff;
        background-color: #ef621b;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .back-link a:hover {
        background-color: #d15817;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 20px;
        }

        .story p {
            font-size: 16px;
        }
    }
</style>
</head>
<body>
    <!-- Centered Logo -->
    <header>
        <img src="../Images/Logo.png" alt="GreenLife Wellness Center">
    </header>

    <div class="container">
        <h1>Success Stories</h1>

        <div class="story">
            <img src= "../Images/Ayurveda theraphy.jpg" alt="Ayurvedic Therapy">
            <p>📝 <strong>1. Ayurvedic Therapy: Healing the Natural Way</strong><br><br>
            Ayurveda, the ancient Indian system of medicine, focuses on balancing the body, mind, and spirit using natural methods. 
            At GreenLife Wellness, our Ayurvedic Therapy sessions include herbal remedies, detox treatments, and lifestyle guidance 
            that restore harmony and promote long-term health.<br><br>
            Whether you’re struggling with stress, chronic pain, or simply want to refresh your body, Ayurveda offers a holistic 
            path to natural healing without side effects.</p>
        </div>

        <div class="story">
            <img src= "../Images/yoga.jpg" alt="Yoga & Meditation">
            <p>📝 <strong>2. Yoga & Meditation: Mind-Body Balance</strong><br><br>
            In today’s busy world, stress often takes a toll on our mental and physical health. Our Yoga and Meditation classes 
            are designed to help you relax, improve flexibility, and strengthen your immune system.<br><br>
            With certified trainers, we guide you through traditional yoga postures, breathing exercises, and mindfulness meditation 
            that bring peace to your mind and energy to your body. These sessions are suitable for beginners as well as advanced practitioners.</p>
        </div>

        <div class="story">
            <img src=  "../Images/Nutrition and Diet Consultation.jpg" alt="Nutrition & Diet Consultation">
            <p>📝 <strong>3. Nutrition & Diet Consultation: Eat Right, Live Bright</strong><br><br>
            Healthy living starts with the right nutrition. Our certified dieticians and nutritionists provide personalized meal plans 
            tailored to your health goals — whether it’s weight management, muscle building, or overall wellness.<br><br>
            We focus on creating balanced diets rich in essential nutrients, and also provide guidance on natural supplements and lifestyle 
            changes to help you achieve sustainable results.</p>
        </div>

        <div class="story">
            <img src="../Images/physiotherapy.jpg" alt="Physiotherapy">
            <p>📝 <strong>4. Physiotherapy: Restoring Mobility & Strength</strong><br><br>
            Our Physiotherapy service is dedicated to individuals recovering from injuries, surgeries, or chronic conditions like arthritis 
            and back pain.<br><br>
            We use advanced therapeutic techniques, strengthening exercises, and rehabilitation programs to help restore mobility, reduce pain, 
            and improve overall physical performance. Each session is tailored to your specific condition under the guidance of our professional physiotherapists.</p>
        </div>

        <div class="story">
            <img src= "../Images/Massage.jpg" alt="Massage Therapy">
            <p>📝 <strong>5. Massage Therapy: Relax, Recharge, Renew</strong><br><br>
            Massage Therapy is more than relaxation — it’s a powerful way to improve circulation, relieve stress, and reduce muscle tension.<br><br>
            At GreenLife Wellness, we offer a variety of massage techniques, from deep tissue to aromatherapy, ensuring your body and mind 
            feel refreshed after every session. This service is perfect for busy professionals, athletes, or anyone looking to release stress 
            and improve overall well-being.</p>
        </div>

        <div class="back-link">
            <a href= "home.php">← Back to home page </a>
        </div>
    </div>
</body>
</html>
