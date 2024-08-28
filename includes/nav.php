<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('includes/config.php');

$username = ''; // Default username if not logged in

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user data
    $query = "SELECT first_name FROM user_registration WHERE user_id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Fetch unseen booking notifications (status changed by admin)
    $bookingQuery = "SELECT booking_id FROM bookings WHERE status != 'pending' AND user_id = ?";
    $bookingStmt = $mysqli->prepare($bookingQuery);
    $bookingStmt->bind_param('i', $user_id);
    $bookingStmt->execute();
    $bookingResult = $bookingStmt->get_result();
    $bookings = $bookingResult->fetch_all(MYSQLI_ASSOC);
    $bookingStmt->close();

    // Count total notifications
    $totalNotifications = count($bookings);
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="dashboard.php">Hostel Management</a>
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
            <!-- Notification Bell Icon -->
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationBell" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if (!empty($totalNotifications) && $totalNotifications > 0): ?>
                        <span class="badge badge-danger" id="notificationCount"><?php echo $totalNotifications; ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationBell">
                    <?php if (!empty($totalNotifications) && $totalNotifications > 0): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <a class="dropdown-item" href="my_bookings.php?booking_id=<?php echo $booking['booking_id']; ?>">
                                Booking status updated (ID: <?php echo $booking['booking_id']; ?>)
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a class="dropdown-item" href="#">No new notifications</a>
                    <?php endif; ?>
                </div>
            </li>
            <!-- User Dropdown with Avatar -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="av-1.jpg" alt="User Avatar" id="userAvatar" style="width: 30px; height: 30px; border-radius: 50%;"> <?php echo htmlspecialchars($username); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="my-profile.php">Profile</a>

                        <a class="dropdown-item" href="update_password.php">Update Password</a>
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
    .fas.fa-bell {
        color: #fff; /* White color for the bell icon */
        font-size: 18px; /* Adjust the size of the bell icon */
    }
    .badge-danger {
        position: relative;
        top: -10px;
        left: -5px;
        background-color: #ff0000;
    }
</style>

<!-- Include Bootstrap CSS and JS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
