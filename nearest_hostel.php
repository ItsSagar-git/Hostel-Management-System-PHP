<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id'];

// Database connection
$mysqli = new mysqli("localhost", "root", "", "hostel");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to calculate the Haversine distance
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

// Fetch user location
$query = "SELECT latitude, longitude FROM user_registration WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($userLatitude, $userLongitude);
$stmt->fetch();
$stmt->close();

if (is_null($userLatitude) || is_null($userLongitude)) {
    die("User location not found in user_registration for user_id: " . htmlspecialchars($userId));
}
// Fetch hostels along with their location, fee, and photo
$query = "SELECT hostel_id, hostel_name, hostel_address, latitude, longitude, hostel_photo, fees FROM hostels";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$hostels = [];
while ($row = $result->fetch_assoc()) {
    $distance = haversineGreatCircleDistance($userLatitude, $userLongitude, $row['latitude'], $row['longitude']);
    $row['distance'] = $distance;
    $hostels[] = $row;
}

$stmt->close();
$mysqli->close();

// Sort hostels by distance
usort($hostels, function ($a, $b) {
    return $a['distance'] <=> $b['distance'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nearest Hostels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("includes/nav.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">Nearest Hostels</h2>
    <?php if (count($hostels) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Hostel ID</th>
                <th>Hostel Name</th>
                <th>Location</th>
                <th>Fee (Monthly)</th>
                <th>Distance (km)</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($hostels as $hostel): ?>
                <tr>
                    <td><?= htmlspecialchars($hostel['hostel_id']) ?></td>
                    <td><?= htmlspecialchars($hostel['hostel_name']) ?></td>
                    <td> <?= htmlspecialchars($hostel['hostel_address']) ?></td>
                    <td><?= htmlspecialchars($hostel['fees']) ?></td>
                    <td><?= number_format($hostel['distance'], 2) ?></td>
                    <td>
                        <a href="book-hostel.php?hostel_id=<?= urlencode($hostel['hostel_id']) ?>&hostel_name=<?= urlencode($hostel['hostel_name']) ?>" class="btn btn-primary">Book Hostel</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No hostels found.</div>
    <?php endif; ?>
</div>
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
