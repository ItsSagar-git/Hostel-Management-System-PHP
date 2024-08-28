<?php
session_start();
include('includes/config.php');
// Fetch hostels from the database
$stmt = $mysqli->prepare("SELECT hostel_id, hostel_name, hostel_address, hostel_email, hostel_contact FROM hostels");
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Hostels</title>
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
        .card-custom {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-custom:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
        .hostel-photo {
            height: 200px;
            object-fit: cover;
        }
        .footer {
            background-color: #004d99;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include("includes/navbar.php"); ?>

<!-- Hostels Section -->
<div class="container my-5">
    <div class="row">
        <?php while ($row = $res->fetch_assoc()) { ?>
            <div class="col-md-4">
                <div class="card card-custom">
                    <img src="get_photo.php?id=<?php echo htmlspecialchars($row['hostel_id']); ?>" class="card-img-top hostel-photo" alt="Hostel Photo">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['hostel_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['hostel_address']); ?></p>
                        <p class="card-text">Email: <?php echo htmlspecialchars($row['hostel_email']); ?></p>
                        <p class="card-text">Contact: <?php echo htmlspecialchars($row['hostel_contact']); ?></p>
                        <a href="hostel-detail.php?id=<?php echo htmlspecialchars($row['hostel_id']); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Hostel Management System. All rights reserved.</p>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
