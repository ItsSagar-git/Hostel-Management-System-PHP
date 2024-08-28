<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('includes/config.php');

// Fetch all hostels from the database
$hostels = [];
$query = "SELECT hostel_id, hostel_name, hostel_description, hostel_photo FROM hostels";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $hostels[] = $row;
    }
    $stmt->close();
} else {
    echo "Failed to prepare the SQL statement.";
}

// Fetch the username for the dropdown
$query = "SELECT first_name FROM user_registration WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Failed to prepare the SQL statement.";
}

// Fetch booking request statuses for notifications
$notifications = [];
$query = "SELECT booking_id, status FROM bookings WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    $stmt->close();
} else {
    echo "Failed to prepare the SQL statement.";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#007bff">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .navbar-brand img {
            height: 40px; /* Adjust height as needed */
        }
        .hostel-card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .hostel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .hostel-card-body {
            padding: 15px;
        }
        .hostel-card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<?php include('includes/nav.php')?>

<!-- Main content -->
<div class="container mt-5">
    <div class="dashboard-header">
        <h2 class="font-weight-bold">Available Hostels</h2>
    </div>

    <div class="row">
        <?php if (!empty($hostels)): ?>
            <?php foreach ($hostels as $hostel): ?>
                <div class="col-md-4">
                    <div class="hostel-card">
                        <?php if (!empty($hostel['hostel_photo'])): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($hostel['hostel_photo']); ?>" alt="<?php echo htmlspecialchars($hostel['hostel_photo']); ?>">
                        <?php else: ?>
                            <img src="path/to/default-image.png" alt="Default Image">
                        <?php endif; ?>
                        <div class="hostel-card-body">
                            <h5 class="hostel-card-title"><?php echo htmlspecialchars($hostel['hostel_name']); ?></h5>
                            <p><?php echo htmlspecialchars($hostel['hostel_description']); ?></p>
                            <a href="hostel-detail.php?id=<?php echo htmlspecialchars($hostel['hostel_id']); ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hostels available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap and JS dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
