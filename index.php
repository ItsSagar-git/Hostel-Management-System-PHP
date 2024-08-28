<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #004d99;
        }
        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
            color: white;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ffcc00;
        }
        .navbar-brand img {
            height: 40px; /* Adjust height as needed */
        }
        .carousel-item img {
            height: 600px;
            object-fit: cover;
        }
        .header-section {
            background-size: cover;
            text-align: center;
            padding: 150px 0;
            color: black; /* Changed text color to black */
        }
        .header-section h1 {
            font-size: 4rem;
            font-weight: 700;
        }
        .header-section p {
            font-size: 1.5rem;
        }
        .feature-box {
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .feature-box:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include("includes/navbar.php"); ?>

<!-- Header Section -->
<div class="header-section">
    <h1>Welcome to Our Hostel Management System</h1>
    <p>Find and Book Your Ideal Hostel</p>
    <a href="list-hostels.php" class="btn btn-primary btn-lg">Explore Hostels</a>
</div>

<!-- Image Carousel -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="2000">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="carousel/FirstCarousel.jpg" class="d-block w-100" alt="First slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>Comfortable Rooms</h5>
                <p>Experience the best comfort in our hostels.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="carousel/SecondCarousel.jpg" class="d-block w-100" alt="Second slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>Modern Facilities</h5>
                <p>Enjoy our state-of-the-art facilities.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="carousel/FourthCarousel.jpg" class="d-block w-100" alt="Third slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>Prime Locations</h5>
                <p>Stay in the heart of the city.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-house-fill" style="font-size: 2rem; color: #004d99;"></i>
                <h3>Affordable Prices</h3>
                <p>Get the best value for your money with our affordable prices.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-shield-check" style="font-size: 2rem; color: #004d99;"></i>
                <h3>Secure Environment</h3>
                <p>Your safety is our priority. Stay in a secure environment.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-people-fill" style="font-size: 2rem; color: #004d99;"></i>
                <h3>Friendly Community</h3>
                <p>Join a friendly and welcoming community at our hostels.</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-4" style="background-color: #004d99; color: white;">
    <p>&copy; 2024 Hostel Management System. All rights reserved.</p>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
