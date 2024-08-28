<?php
include('includes/config.php');



// Check if the user is logged in by checking if `user_id` is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user data from the database
    $query = "SELECT first_name FROM user_registration WHERE user_id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}

// Use $username variable in your navbar
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Hostel Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="nearest_hostel.php">Nearest Hostels</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my_bookings.php">My Bookings</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="av-1.jpg" alt="User Avatar" id="userAvatar" style="width: 30px; height: 30px; border-radius: 50%;"> <?php echo htmlspecialchars($username); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="my-profile.php">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
    .navbar {
        background-color: #004d99; /* Matches the blue color in your image */
    }
    .navbar-brand {
        color: #fff; /* White text for brand */
        font-size: 18px; /* Adjust font size as needed */
    }
    .nav-link {
        color: #fff !important; /* White text for links */
        font-size: 14px; /* Adjust font size as needed */
    }
    .nav-link:hover {
        color: #ffcc00 !important; /* Yellow color on hover */
    }
</style>

<!-- Include Bootstrap CSS and JS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
