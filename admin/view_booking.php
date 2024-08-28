<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');

// Fetch booking ID from URL
$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($booking_id <= 0) {
    echo "<script>alert('Invalid booking ID.');window.location.href='manage-bookings.php';</script>";
    exit;
}

// Fetch booking details from the database
$stmt = $mysqli->prepare("
    SELECT
        b.fees, b.booking_date, b.duration, b.hostel_id, b.citizenship_photo, b.issue_date, b.issue_place,
        h.hostel_name, u.first_name, u.middle_name, u.last_name, u.gender, u.contact, u.email, u.address
    FROM bookings b
    INNER JOIN user_registration u ON b.user_id = u.user_id
    INNER JOIN hostels h ON b.hostel_id = h.hostel_id
    WHERE b.booking_id = ?
");

if (!$stmt) {
    die("Preparation failed: " . $mysqli->error);
}

$stmt->bind_param('i', $booking_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('No booking found with this ID.');window.location.href='manage-bookings.php';</script>";
    exit;
}

$stmt->bind_result(
    $fees, $booking_date, $duration, $hostel_id, $citizenship_photo, $issue_date, $issue_place,
    $hostel_name, $first_name, $middle_name, $last_name, $gender, $contact, $email, $address
);
$stmt->fetch();
$stmt->close();

// Check if the booking details were retrieved successfully
if (empty($first_name)) {
    echo "<script>alert('Error fetching booking details.');window.location.href='manage-bookings.php';</script>";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking</title>
    <!-- Include your CSS here -->
    <link rel="stylesheet" href="path/to/your/styles.css">
</head>
<body class="bg-light">
<?php include('includes/adnav.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Booking Details</h2>

    <div class="form-container">
        <h4 class="text-center">Personal Information</h4>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">First Name</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($first_name); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Middle Name</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($middle_name); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Last Name</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($last_name); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($gender); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Contact No</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($contact); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Address</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($address); ?></p>
            </div>
        </div>

        <h4 class="text-center">Booking Information</h4>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Hostel Name</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel_name); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Fees Per Month</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($fees); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stay From</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($booking_date); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Duration</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($duration); ?> months</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Citizenship Photo</label>
            <div class="col-sm-10">
                <?php if ($citizenship_photo): ?>
                    <img src="get_image.php?id=<?php echo htmlspecialchars($booking_id); ?>" alt="Citizenship Photo" class="img-fluid">
                <?php else: ?>
                    <p class="form-control-plaintext">No photo uploaded</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Issue Date</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($issue_date); ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Issue Place</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext"><?php echo htmlspecialchars($issue_place); ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
