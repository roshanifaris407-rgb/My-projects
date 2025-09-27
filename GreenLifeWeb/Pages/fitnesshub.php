<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Images Over Background</title>
<style>
    /* Ensure the page takes up full height and has no margin/padding */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    /* Set background image */
    body {
        background-image: url("../Images/hub bg.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100%;
        position: relative; /* To position the images inside the body */
    }

    /* Overlay Container for Images */
    .overlay-images {
        position: absolute; /* Position images relative to body */
        top: 50%; /* Center vertically */
        left: 50%; /* Center horizontally */
        transform: translate(-50%, -50%); /* Center the container */
        display: flex;
        justify-content: space-between; /* Space images out */
        width: 95%; /* Adjust width to control space between images */
    }

    /* Each Image Container */
    .overlay-images a {
        display: block; /* Make the image clickable */
        transition: transform 0.3s ease; /* Smooth transition on hover */
    }

    .overlay-images img {
        width: 280px; /* Adjust the size of the image */
        height: 500px;
        border-radius: 10px; /* Optional rounded corners */
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3); /* Add some shadow for effect */
        transition: transform 0.3s ease; /* Smooth zoom effect */
    }

    /* Hover Effect - Zoom in image */
    .overlay-images a:hover img {
        transform: scale(1.1); /* Zoom in on hover */
    }
</style>
</head>

<body>
    <!-- Overlay Container for Images -->
    <div class="overlay-images">
        <!-- First Image: Success Story -->
        <a href="successtory.php">
            <img src="../Images/ss.jpg" alt="Success Story" />
        </a>
        
        <!-- Second Image: Health Recipes -->
        <a href="healthrecipes.php">
            <img src="../Images/hm.jpg" alt="Health Recipes" />
        </a>

        <!-- Third Image: Workout Routines -->
        <a href="workoutroutines.php">
            <img src="../Images/wr.jpg" alt="Workout Routines" />
        </a>
		
		 <!-- Fourth Image: trainers -->
        <a href="trainers.php">
            <img src="../Images/coach.jpg" alt="Nutrition Guide" />
    </div>
</body>
</html>
