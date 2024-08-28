<?php
session_start();
include('includes/config.php');

// Check if admin is logged in
include('includes/checklogin.php');
checklogin();

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
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Dashboard Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .notification-dropdown {
            position: relative;
        }

        .notification-bell {
            font-size: 1.5rem;
            cursor: pointer;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            display: none;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu .notification-item {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .dropdown-menu .notification-item:last-child {
            border-bottom: none;
        }

        .dropdown-menu .notification-item a {
            text-decoration: none;
            color: #007bff;
        }

        .dropdown-menu .notification-item a:hover {
            text-decoration: underline;
        }

        .dropdown-menu .no-notifications {
            padding: 10px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>

<body style="background-color: #f4f4f4;">
<!-- Include your navigation bar -->
<?php include('includes/adnav.php') ?>

<div class="container mt-5">
    <h2>Notifications</h2>

    <div class="notification-dropdown">
        <i class="fas fa-bell notification-bell"></i>
        <div class="dropdown-menu">
            <?php if (count($contacts) > 0 || count($bookings) > 0): ?>
                <?php if (count($contacts) > 0): ?>
                    <div class="notification-item">
                        <strong>Contact Form Submissions:</strong>
                        <ul>
                            <?php foreach ($contacts as $contact): ?>
                                <li>
                                    New contact request from <?php echo htmlspecialchars($contact['name']); ?>.
                                    <a href="view-contact.php?contact_id=<?php echo $contact['id']; ?>">View Details</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (count($bookings) > 0): ?>
                    <div class="notification-item">
                        <strong>Booking Requests:</strong>
                        <ul>
                            <?php foreach ($bookings as $booking): ?>
                                <li>
                                    New booking request (ID: <?php echo $booking['booking_id']; ?>).
                                    <a href="manage-bookings.php?booking_id=<?php echo $booking['booking_id']; ?>">View Details</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-notifications">
                    No new notifications.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.notification-bell').hover(function() {
            $('.dropdown-menu').toggleClass('show');
        }, function() {
            $('.dropdown-menu').toggleClass('show');
        });
    });
</script>
</body>

</html>

