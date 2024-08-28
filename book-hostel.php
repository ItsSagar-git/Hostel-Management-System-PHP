<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');

$errors = array(); // Initialize an empty array to store validation errors

// Fetch user_id from session email
$userEmail = $_SESSION['login'];
$user_id = null;

if ($userEmail) {
    $stmt = $mysqli->prepare("SELECT user_id FROM user_registration WHERE email = ?");
    $stmt->bind_param('s', $userEmail);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
}

// Fetch user details
$user = array();
if ($user_id) {
    $stmt = $mysqli->prepare("SELECT first_name, middle_name, last_name, gender, contact, email, address FROM user_registration WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($user['first_name'], $user['middle_name'], $user['last_name'], $user['gender'], $user['contact'], $user['email'], $user['address']);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $hostel_id = $_POST['hostel_id'] ?? '';
    $feespm = $_POST['fpm'] ?? '';
    $booking_date = $_POST['bdate'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $issue_date = $_POST['issue_date'] ?? '';
    $issue_place = $_POST['issue_place'] ?? '';
    $citizenship_photo = $_FILES['citizenship_photo']['name'] ?? '';

    if ($duration < 1 || $duration > 12) {
        $errors[] = 'Duration must be between 1 and 12 months.';
    }

    // Validate issue date (cannot be in the future)
    $currentDate = date('Y-m-d');
    if ($issue_date > $currentDate) {
        $errors[] = 'Issue date cannot be a future date.';
    }

    // Validate file upload
    if ($_FILES['citizenship_photo']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['citizenship_photo']['tmp_name'];
        $upload_dir = 'uploads/';
        $citizenship_photo = $upload_dir . basename($_FILES['citizenship_photo']['name']);
        move_uploaded_file($tmp_name, $citizenship_photo);
    } else {
        $errors[] = 'Error uploading citizenship photo.';
    }

    if (empty($errors)) {
        // Check if the user has any booking that is not cancelled
        $checkQuery = "SELECT status FROM bookings WHERE user_id = ? AND status != 'Cancelled'";
        $stmt = $mysqli->prepare($checkQuery);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();

//        if ($stmt->num_rows > 0) {
//            echo "<script>alert('You cannot book a hostel until your current booking is cancelled.');</script>";
//        } else {
        // Proceed with booking
        $query = "INSERT INTO bookings (fees, status, user_id, booking_date, duration, hostel_id, citizenship_photo, issue_date, issue_place) VALUES (?, 'Pending', ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $mysqli->prepare($query);
        $stmt2->bind_param('iissssss', $feespm, $user_id, $booking_date, $duration, $hostel_id, $citizenship_photo, $issue_date, $issue_place);

        if ($stmt2->execute()) {
            echo "<script>alert('Booking Request Submitted.');</script>";
        } else {
            echo "<script>alert('Error registering booking.');</script>";
        }

        $stmt2->close();
    }
    $stmt->close();
} else {
    foreach ($errors as $error) {
        echo "<script>alert('$error');</script>";
    }
}


// Fetch hostel name and ID if provided in the URL
$hostelName = '';
$hostelId = '';
$feespm = '';

if (isset($_GET['hostel_name']) && isset($_GET['hostel_id'])) {
    $hostelName = $_GET['hostel_name'];
    $hostelId = $_GET['hostel_id'];

    $stmt = $mysqli->prepare("SELECT fees FROM hostels WHERE hostel_id = ?");
    $stmt->bind_param('i', $hostelId);
    $stmt->execute();
    $stmt->bind_result($feespm);
    $stmt->fetch();
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include('includes/nav.php'); ?>

<div class="container mt-5">
    <div class="form-container">
        <h2 class="text-center">Hostel Booking Form</h2>

        <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
            <h4 class="text-center">Personal Information</h4>
            <div class="form-group row">
                <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                <div class="col-sm-10">
                    <input type="text" name="fname" id="fname" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="mname" class="col-sm-2 col-form-label">Middle Name</label>
                <div class="col-sm-10">
                    <input type="text" name="mname" id="mname" class="form-control" value="<?php echo htmlspecialchars($user['middle_name']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                <div class="col-sm-10">
                    <input type="text" name="lname" id="lname" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-10">
                    <input type="text" name="gender" id="gender" class="form-control" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="contact" class="col-sm-2 col-form-label">Contact No</label>
                <div class="col-sm-10">
                    <input type="text" name="contact" id="contact" class="form-control" value="<?php echo htmlspecialchars($user['contact']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <textarea name="address" id="address" class="form-control" readonly><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
            </div>

            <h4 class="text-center">Booking Information</h4>
            <div class="form-group row">
                <label for="hostel_name" class="col-sm-2 col-form-label">Hostel Name</label>
                <div class="col-sm-10">
                    <input type="text" name="hostel_name" id="hostel_name" class="form-control" value="<?php echo htmlspecialchars($hostelName); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="bdate" class="col-sm-2 col-form-label">Booking Date</label>
                <div class="col-sm-10">
                    <input type="date" name="bdate" id="bdate" class="form-control" placeholder="YYYY-MM-DD" required>
                </div>
            </div>

            <script>
                // Get today's date in YYYY-MM-DD format
                var today = new Date().toISOString().split('T')[0];

                // Set the minimum attribute to today's date
                document.getElementById('bdate').setAttribute('min', today);
            </script>
            <div class="form-group row">
                <label for="fpm" class="col-sm-2 col-form-label">Fees Per Month</label>
                <div class="col-sm-10">
                    <input type="text" name="fpm" id="fpm" class="form-control" value="<?php echo htmlspecialchars($feespm); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="duration" class="col-sm-2 col-form-label">Duration (Months)</label>
                <div class="col-sm-10">
                    <input type="number" name="duration" id="duration" class="form-control" min="1" max="12" placeholder="Enter duration of stay in months" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="total_fee" class="col-sm-2 col-form-label">Total Fee</label>
                <div class="col-sm-10">
                    <input type="text" name="total_fee" id="total_fee" class="form-control" readonly>
                </div>
            </div>


            </div>
            <div class="form-group row">
                <label for="issue_date" class="col-sm-2 col-form-label">Issue Date</label>
                <div class="col-sm-10">
                    <input type="date" name="issue_date" id="issue_date" class="form-control" placeholder="YYYY-MM-DD">
                </div>
            </div>
    <script>
        // Get today's date in YYYY-MM-DD format
        var today = new Date().toISOString().split('T')[0];

        // Set the max attribute to today's date
        document.getElementById('issue_date').setAttribute('max', today);
    </script>
            <div class="form-group row">
                <label for="issue_place" class="col-sm-2 col-form-label">Issue Place</label>
                <div class="col-sm-10">
                    <input type="text" name="issue_place" id="issue_place" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="citizenship_photo" class="col-sm-2 col-form-label">Citizenship Photo</label>
                <div class="col-sm-10">
                    <input type="file" name="citizenship_photo" id="citizenship_photo" class="form-control-file">
                </div>
            </div>
            <input type="hidden" name="hostel_id" value="<?php echo htmlspecialchars($hostelId); ?>">

            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });</script>
<script>
    // Function to calculate the total fee
    function calculateTotalFee() {
        var feesPerMonth = parseFloat(document.getElementById('fpm').value);
        var duration = parseFloat(document.getElementById('duration').value);

        if (!isNaN(feesPerMonth) && !isNaN(duration)) {
            var totalFee = feesPerMonth * duration;
            document.getElementById('total_fee').value = totalFee.toFixed(2); // Display total fee with 2 decimal points
        } else {
            document.getElementById('total_fee').value = '';
        }
    }

    // Attach event listener to the duration input field
    document.getElementById('duration').addEventListener('input', calculateTotalFee);
</script>
<script>
    $(document).ready(function() {
        $("#bdate, #issue_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0 // Disallow past dates
        });
    });
</script>
</body>
</html>
