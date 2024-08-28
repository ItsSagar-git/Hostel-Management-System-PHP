<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin(); // Ensure this function does not cause a redirect loop

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize $hostels
$hostels = [];

// Check if the database connection is set
if (isset($mysqli)) {
    // Fetch hostel details from the database
    $query = "SELECT hostel_id, hostel_name, hostel_photo FROM hostels WHERE admin_id=?";
    $stmt = $mysqli->prepare($query);

    // Check if statement preparation was successful
    if ($stmt) {
        // Check if session user ID is set
        if (isset($_SESSION['user_id'])) {
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch all rows as an associative array
            if ($result->num_rows > 0) {
                $hostels = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $message = "No hostels available.";
            }
            $stmt->close();
        } else {
            $message = "User not logged in.";
        }
    } else {
        $message = "Failed to prepare statement: " . $mysqli->error;
    }
} else {
    $message = "Database connection not set.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Hostels</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #004d99;
        }
        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
            color: white;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ffcc00;
        }
        .card-custom:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
        .footer-custom {
            background-color: #004d99;
            color: white;
            padding: 20px 0;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="#">Hostel Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contactus.php">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Page Heading -->
<div class="container mt-5">
    <h1 class="text-center mb-4">Available Hostels</h1>
    <div class="row">
        <?php if (!empty($hostels)): ?>
            <?php foreach ($hostels as $hostel): ?>
                <div class="col-md-4">
                    <div class="card card-custom mb-4">
                        <img src="<?php echo htmlspecialchars($hostel['hostel_photo']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($hostel['hostel_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($hostel['hostel_name']); ?></h5>
                            <p class="card-text">Description of <?php echo htmlspecialchars($hostel['hostel_name']); ?>.</p>
                            <a href="book.php?hostel_id=<?php echo $hostel['hostel_id']; ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center"><?php echo isset($message) ? htmlspecialchars($message) : 'No hostels available.'; ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer-custom text-center">
    <div class="container">
        <p>&copy; 2024 Hostel Management System. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
