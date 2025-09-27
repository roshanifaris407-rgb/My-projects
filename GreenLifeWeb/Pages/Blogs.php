<?php
include 'DBconnect.php';
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>GreenLife Wellness - Blogs</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: white;
        background: linear-gradient(rgba(13,27,42,0.7), rgba(13,27,42,0.7)), 
                    url("../Images/Back Ground.jpg") no-repeat center center;
        background-size: cover;
    }

    header {
        display: flex;
        justify-content: center; 
        align-items: center;
        padding: 20px 0;
        width: 100%;
    }

    header img {
        max-height: 100px;
        width: auto;
    }

    .container {
        background-color: rgba(27,38,59,0.9);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.4);
        width: 90%;
        max-width: 1000px;
        text-align: center;
        margin-bottom: 30px;
    }

    h1 {
        margin-bottom: 30px;
        color: #ef621b;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .service-card {
        background: #274156;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0px 6px 18px rgba(0,0,0,0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        color: #fff;
    }

    .service-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .service-card .caption {
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        background: #ef621b;
        color: #fff;
    }

    .service-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 25px rgba(0,0,0,0.5);
    }

    .back-btn {
        display: inline-block;
        padding: 12px 25px;
        background-color: #ef621b;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #d15817;
    }

    @media (max-width: 600px) {
        .services-grid {
            grid-template-columns: 1fr;
        }
    }

    .blogs-section {
        margin-top: 50px;
    }

    .blogs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .blog-card {
        background:#0D1B2A;
        border-radius:10px;
        padding:15px;
        color:#fff;
        box-shadow:0 4px 6px rgba(0,0,0,0.4);
        text-align:left;
    }

    .blog-card img {
        width:100%;
        border-radius:10px;
    }

    .blog-card h3 {
        color:#ef621b;
        margin-top:10px;
    }

    .blog-card p {
        font-size:14px;
        line-height:1.5;
    }
</style>
</head>

<body>
    <header>
        <img src="../Images/Logo.png" alt="GreenLife Wellness Center">
    </header>

    <div class="container">
        <h1>Blogs</h1>

        <div class="services-grid">
            <a href="Success Stories.php" class="service-card">
                <img src="../Images/Articals 1.jpg" alt="Success Story">
                <div class="caption">Success Stories</div>
            </a>

            <div class="service-card">
                <img src= "../Images/Health Recipes.jpg" alt="Health Recipes">
                <div class="caption">Health Recipes</div>
            </div>

            <div class="service-card">
                <img src= "../Images/Workout Routines.jpg" alt="Workout Routines">
                <div class="caption">Workout Routines</div>
            </div>

            <div class="service-card">
                <img src= "../Images/Wellness Tips.png" alt="Wellness Tips">
                <div class="caption">Wellness Tips</div>
            </div>
        </div>

        <div class="blogs-section">
            <h2 style="color:#ef621b;">Latest Blogs</h2>
            <div class="blogs-grid">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($blog = mysqli_fetch_assoc($result)): ?>
                        <div class="blog-card">
                            <!-- Clickable blog image -->
                            <a href="view_blog.php?id=<?= $blog['id'] ?>" style="display:inline-block; text-decoration:none; border:none;">
                                <img src="<?= isset($blog['image']) && $blog['image'] != '' ? $blog['image'] : '../Images/default-blog.jpg' ?>" 
                                     alt="<?= htmlspecialchars($blog['title']) ?>" 
                                     style="cursor:pointer; border-radius:8px; max-width:250px; box-shadow:0px 4px 10px rgba(0,0,0,0.3);">
                            </a>
                            <h3><?= htmlspecialchars($blog['title']) ?></h3>
                            <p><strong>Category:</strong> <?= htmlspecialchars($blog['category']) ?></p>
                            <p><?= nl2br(htmlspecialchars(substr($blog['content'], 0, 100))) ?>...</p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:#ff5722;">No blogs available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="home.php" class="back-btn">Back to Home</a>
    </div>
</body>
</html>
