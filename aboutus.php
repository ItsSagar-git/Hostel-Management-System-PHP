<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<?php include('includes/navbar.php')?>
<!-- About Us Section -->
<div class="container mt-5">
    <h1 class="text-center">About Us</h1>
    <p class="text-center">Welcome to our hostel management system. We provide a home away from home.</p>


    <!-- Team Section -->
    <div class="row mt-5">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-hover">
                <img src="av-1.jpg" class="card-img-top" alt="Team Member 1">
                <div class="card-body">
                    <h5 class="card-title">Sagar Adhikari</h5>
                    <p class="card-text">Manager</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-hover">
                <img src="av-2.webp" class="card-img-top" alt="Team Member 2">
                <div class="card-body">
                    <h5 class="card-title">Jane Smith</h5>
                    <p class="card-text">Receptionist</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-hover">
                <img src="av-3.jpeg" class="card-img-top" alt="Team Member 3">
                <div class="card-body">
                    <h5 class="card-title">Dhruv Rathee</h5>
                    <p class="card-text">Asst. Manager</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Hostel Management System</h5>
                <p>Providing comfort and convenience for travelers.</p>
            </div>
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contact Us</h5>
                <p>Email: info@hostelmanagement.com</p>
                <p>Phone: 014020000</p>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2024 Hostel Name
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
