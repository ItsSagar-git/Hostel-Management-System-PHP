<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/config.php');

// Fetch notifications
$contactQuery = "SELECT id, name FROM contact_form WHERE status='unread'";
$contactStmt = $mysqli->prepare($contactQuery);
$contactStmt->execute();
$contactResult = $contactStmt->get_result();
$contacts = $contactResult->fetch_all(MYSQLI_ASSOC);
$contactStmt->close();

$bookingQuery = "SELECT booking_id FROM bookings WHERE status='pending'";
$bookingStmt = $mysqli->prepare($bookingQuery);
$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();
$bookings = $bookingResult->fetch_all(MYSQLI_ASSOC);
$bookingStmt->close();

// Insert notifications into notifications table
//foreach ($contacts as $contact) {
//    $notificationMessage = "New contact request from " . $contact['name'];
////    $insertQuery = "INSERT INTO notifications (message, type) VALUES (?, 'contact')";
//    $stmt = $mysqli->prepare($insertQuery);
//    $stmt->bind_param("s", $notificationMessage);
//    $stmt->execute();
//    $stmt->close();
//}
//
//foreach ($bookings as $booking) {
//    $notificationMessage = "New booking request (ID: " . $booking['booking_id'] . ")";
////    $insertQuery = "INSERT INTO notifications (message, type) VALUES (?, 'booking')";
//    $stmt = $mysqli->prepare($insertQuery);
//    $stmt->bind_param("s", $notificationMessage);
//    $stmt->execute();
//    $stmt->close();
//}

// Count total notifications
$totalNotifications = count($contacts) + count($bookings);
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="hostelDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Hostels
                </a>
                <div class="dropdown-menu" aria-labelledby="hostelDropdown">
                    <a class="dropdown-item" href="create-hostels.php">Add Hostel</a>
                    <a class="dropdown-item" href="manage-hostels.php">Manage Hostels</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage-bookings.php">Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage-users.php">Registered Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_panel.php">ContactForm Enquiry</a>
            </li>
        </ul>

        <!-- Notification Bell Icon -->
        <ul class="navbar-nav mr-3">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="notificationBell" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($totalNotifications > 0): ?>
                        <span class="badge badge-danger" id="notificationCount"><?php echo $totalNotifications; ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationBell">
                    <?php if ($totalNotifications > 0): ?>
                        <?php foreach ($contacts as $contact): ?>
                            <a class="dropdown-item" href="admin_panel.php?contact_id=<?php echo $contact['id']; ?>">
                                New contact request from <?php echo htmlspecialchars($contact['name']); ?>
                            </a>
                        <?php endforeach; ?>
                        <?php foreach ($bookings as $booking): ?>
                            <a class="dropdown-item" href="manage-bookings.php?booking_id=<?php echo $booking['booking_id']; ?>">
                                New booking request (ID: <?php echo $booking['booking_id']; ?>)
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a class="dropdown-item" href="#">No new notifications</a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>

        <!-- Admin Dropdown with Avatar -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="adminavatar.jpeg" alt="Admin Avatar" id="adminAvatar" style="width: 30px; height: 30px; border-radius: 50%;"> Admin
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                    <a class="dropdown-item" href="admin-profile.php">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<style>
    .navbar {
        background-color: #004d99;
    }
    .navbar-brand {
        color: #fff;
        font-size: 20px;
    }
    .nav-link {
        color: #fff !important;
        font-size: 14px;
    }
    .nav-link:hover {
        color: #ffcc00 !important;
    }
    #adminAvatar {
        display: inline-block;
        border: 2px solid #fff;
        background-color: #ddd;
    }
    .fas.fa-bell {
        color: #fff;
        font-size: 18px;
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
<script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome for icons -->
