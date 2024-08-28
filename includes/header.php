<?php

include('config.php');

// Fetch counts for notifications
// Contact form requests count
$contactQuery = "SELECT id, name FROM contact_form WHERE status='unread'";
$contactStmt = $mysqli->prepare($contactQuery);
$contactStmt->execute();
$contactResult = $contactStmt->get_result();
$contacts = $contactResult->fetch_all(MYSQLI_ASSOC);
$contactStmt->close();

// Booking requests count
$bookingQuery = "SELECT booking_id FROM bookings WHERE status='pending'";
$bookingStmt = $mysqli->prepare($bookingQuery);
$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();
$bookings = $bookingResult->fetch_all(MYSQLI_ASSOC);
$bookingStmt->close();
$totalNotifications = count($contacts) + count($bookings);

// Check if username is set in session
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            display: flex;
            justify-content: flex-end; /* Aligns children to the end of the header */
            align-items: center;
        }

        .header .avatar-dropdown {
            display: flex;
            align-items: center;
            margin-left: 20px; /* Space between notification and avatar */
        }

        .header .avatar img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .header .dropdown-menu {
            min-width: 150px;
            margin-left:-25px;
        }

        .notification-icon {
            position: relative;
            display: inline-block;
        }

        .notification-icon .notification-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.75rem;
        }

        .notifications-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }

        .notifications-dropdown.show {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            display: block;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f4f4f4;
        }

        .no-notifications {
            padding: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <!-- Notification Bell Icon -->
    <div class="notification-icon" id="notificationIcon">
        <i class="fas fa-bell fa-2x"></i>
        <?php if ($totalNotifications > 0): ?>
            <span class="notification-badge"><?php echo $totalNotifications; ?></span>
        <?php endif; ?>
        <div class="notifications-dropdown" id="notificationsDropdown">
            <?php if ($totalNotifications === 0): ?>
                <div class="no-notifications">No new notifications</div>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                    <a href="admin_panel.php" class="notification-item">
                        <?php echo htmlspecialchars($contact['name']) . ' sent you a message'; ?>
                    </a>
                <?php endforeach; ?>
                <?php foreach ($bookings as $booking): ?>
                    <a href="manage-bookings.php" class="notification-item">
                        <?php echo htmlspecialchars($booking['booking_id']) . ' submitted a hostel booking request'; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Avatar and User Menu -->
    <div class="avatar-dropdown">
        <!--        <img src="path/to/avatar.jpg" alt="User Avatar">-->
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" id="userMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $username; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="userMenu">
                <a class="dropdown-item" href="admin-profile.php">My Profile</a>
                <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var notificationIcon = document.getElementById('notificationIcon');
        var notificationsDropdown = document.getElementById('notificationsDropdown');

        // Show notifications on hover
        notificationIcon.addEventListener('mouseenter', function () {
            notificationsDropdown.classList.add('show');
        });

        notificationIcon.addEventListener('mouseleave', function () {
            notificationsDropdown.classList.remove('show');
        });
    });
</script>
</body>
</html>
