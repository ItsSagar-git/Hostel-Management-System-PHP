<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
$aid = $_SESSION['user_id'];

$errorMessages = array(
    'first_name' => '',
    'middle_name' => '',
    'last_name' => '',
    'contact' => '',
    'latitude' => '',
    'longitude' => ''
);

if (isset($_POST['update'])) {
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $contactno = isset($_POST['contact']) ? $_POST['contact'] : '';
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

    if (!preg_match('/^[A-Za-z]+$/', $first_name)) {
        $errorMessages['first_name'] = 'First Name must contain only letters';
    }
    if (!preg_match('/^[A-Za-z]*$/', $middle_name)) {
        $errorMessages['middle_name'] = 'Middle Name must contain only letters';
    }
    if (!preg_match('/^[A-Za-z]+$/', $last_name)) {
        $errorMessages['last_name'] = 'Last Name must contain only letters';
    }
    if (!preg_match('/^\d{10}$/', $contactno)) {
        $errorMessages['contact'] = 'Contact No must be 10 digits';
    }
    if (!is_numeric($latitude) || $latitude == '') {
        $errorMessages['latitude'] = 'Latitude must be a valid number';
    }
    if (!is_numeric($longitude) || $longitude == '') {
        $errorMessages['longitude'] = 'Longitude must be a valid number';
    }

    if (empty(array_filter($errorMessages))) {
        $query = "UPDATE user_registration SET first_name=?, middle_name=?, last_name=?, gender=?, contact=?, latitude=?, longitude=? WHERE user_id=?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            die("Error: " . $mysqli->error);
        }

        $stmt->bind_param('sssssddi', $first_name, $middle_name, $last_name, $gender, $contactno, $latitude, $longitude, $aid);
        if (!$stmt->execute()) {
            die("Error: " . $stmt->error);
        }

        echo "<script>alert('Profile updated Successfully');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Updation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .page-title {
            color: #28a745;
        }
        .btn-custom {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-custom:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .error-msg {
            color: #dc3545;
            font-size: 0.875rem;
        }
        .success-msg {
            color: #28a745;
            font-size: 0.875rem;
        }
        .card-custom {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }
        .card-header-custom {
            background-color: #28a745;
            color: white;
        }
        .card-footer-custom {
            background-color: #f8f9fa;
        }
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<?php include("includes/nav.php"); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <?php
            $aid = $_SESSION['user_id'];
            $ret = "SELECT * FROM user_registration WHERE user_id=?";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $aid);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_object()) {
                ?>
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h2 class="page-title"><?php echo $row->first_name; ?>'s Profile</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" name="registration" class="row g-3">
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">User ID</label>
                                <input type="text" name="user_id" id="user_id" class="form-control" value="<?php echo $row->user_id; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control <?php if ($errorMessages['first_name']) echo 'is-invalid'; ?>" value="<?php echo $row->first_name; ?>" required>
                                <div class="invalid-feedback"><?php echo $errorMessages['first_name']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control <?php if ($errorMessages['middle_name']) echo 'is-invalid'; ?>" value="<?php echo $row->middle_name; ?>" >
                                <div class="invalid-feedback"><?php echo $errorMessages['middle_name']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control <?php if ($errorMessages['last_name']) echo 'is-invalid'; ?>" value="<?php echo $row->last_name; ?>" required>
                                <div class="invalid-feedback"><?php echo $errorMessages['last_name']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="male" <?php if ($row->gender == 'male') echo 'selected'; ?>>Male</option>
                                    <option value="female" <?php if ($row->gender == 'female') echo 'selected'; ?>>Female</option>
                                    <option value="others" <?php if ($row->gender == 'others') echo 'selected'; ?>>Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="contact" class="form-label">Contact No</label>
                                <input type="text" name="contact" id="contact" class="form-control <?php if ($errorMessages['contact']) echo 'is-invalid'; ?>" maxlength="10" value="<?php echo $row->contact; ?>" required>
                                <div class="invalid-feedback"><?php echo $errorMessages['contact']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email id</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $row->email; ?>" readonly>
                                <div id="user-availability-status" class="form-text"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control <?php if ($errorMessages['latitude']) echo 'is-invalid'; ?>" value="<?php echo $row->latitude; ?>" required>
                                <div class="invalid-feedback"><?php echo $errorMessages['latitude']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control <?php if ($errorMessages['longitude']) echo 'is-invalid'; ?>" value="<?php echo $row->longitude; ?>" required>
                                <div class="invalid-feedback"><?php echo $errorMessages['longitude']; ?></div>
                            </div>
                            <div class="col-md-12">
                                <div id="map" style="height: 300px;"></div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" name="update" class="btn btn-custom">Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer card-footer-custom">
                        <!-- Footer Content (if any) -->
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([27.7172, 85.3240], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        map.on('click', function(e) {
            var lat = parseFloat(e.latlng.lat.toFixed(4));
            var lon = parseFloat(e.latlng.lng.toFixed(4));

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lon]).addTo(map);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;
        });

        L.control.locate({
            position: 'topleft',
            drawCircle: false,
            follow: true,
            setView: 'once',
            keepCurrentZoomLevel: true
        }).addTo(map);

        var geocoder = L.Control.Geocoder.nominatim();
    });
</script>
<script>
    function validateForm() {
        var contact = document.getElementById("form3Examplev5").value;
        var email = document.getElementById("form3Examplev6").value;

        // Validate contact number (10 digits)
        var contactPattern = /^\d{10}$/;
        if (!contactPattern.test(contact)) {
            alert("Please enter a valid 10-digit contact number.");
            return false;
        }

        // Validate email contains "@" character
        if (!email.includes("@")) {
            alert("Please enter a valid email address.");
            return false;
        }

        return true;
    }
</script>
</body>
</html>
