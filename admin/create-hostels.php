<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

if (isset($_POST['submit'])) {
    $hostelName = $_POST['hostelName'];
    $hostelAddress = $_POST['hostelAddress'];
    $hostelEmail = $_POST['hostelEmail'];
    $hostelContact = $_POST['hostelContact'];
    $latitude = round($_POST['latitude'], 4);
    $longitude = round($_POST['longitude'], 4);
    $fees = $_POST['fees'];
    $adminId = $_SESSION['id'];

    // Handle file upload if a photo is provided
    if (isset($_FILES['hostelPhoto']) && $_FILES['hostelPhoto']['error'] === UPLOAD_ERR_OK) {
        $photoTemp = $_FILES['hostelPhoto']['tmp_name'];
        $photoData = file_get_contents($photoTemp);

        // Check if hostel already exists
        $sql = "SELECT hostel_name FROM hostels WHERE hostel_name=?";
        $stmt1 = $mysqli->prepare($sql);
        $stmt1->bind_param('s', $hostelName);
        $stmt1->execute();
        $stmt1->store_result();
        $row_cnt = $stmt1->num_rows;

        if ($row_cnt > 0) {
            echo "<script>alert('Hostel already exists');</script>";
        } else {
            // Insert new hostel data
            $query = "INSERT INTO hostels (hostel_name, hostel_address, hostel_email, hostel_contact, latitude, longitude, fees, admin_id, hostel_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssddiss', $hostelName, $hostelAddress, $hostelEmail, $hostelContact, $latitude, $longitude, $fees, $adminId, $photoData);
            if ($stmt->execute()) {
                echo "<script>alert('Hostel has been added successfully');</script>";
            } else {
                echo "<script>alert('Failed to add hostel');</script>";
            }
            $stmt->close();
        }
        $stmt1->close();
    } else {
        echo "<script>alert('No photo uploaded or upload error');</script>";
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Add Hostel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #004d99;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar .active {
            background-color: #ffcc00;
            color: #004d99;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .form-control {
            border-radius: 0;
        }
        .btn-primary {
            background-color: #004d99;
            border-color: #004d99;
        }
        .btn-primary:hover {
            background-color: #003366;
            border-color: #003366;
        }
        #map {
            height: 400px;
            margin-top: 20px;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
        .dropdown-menu {
            min-width: 200px;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>
<body style="background-color: #f4f4f4;">
<?php include('includes/adnav.php'); ?>

<div class="main-content flex-grow-1 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="page-title">Add Hostel</h2>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="hostelName" class="col-sm-2 col-form-label">Hostel Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="hostelName" id="hostelName" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hostelAddress" class="col-sm-2 col-form-label">Hostel Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="hostelAddress" id="hostelAddress" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hostelEmail" class="col-sm-2 col-form-label">Hostel Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="hostelEmail" id="hostelEmail">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hostelContact" class="col-sm-2 col-form-label">Hostel Contact</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="hostelContact" id="hostelContact" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.0001" class="form-control" name="latitude" id="latitude" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.0001" class="form-control" name="longitude" id="longitude" required>
                            </div>
                        </div>
                        <div id="map"></div>
                        <div class="form-group row mt-3">
                            <label for="fees" class="col-sm-2 col-form-label">Fees</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" class="form-control" name="fees" id="fees" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hostelPhoto" class="col-sm-2 col-form-label">Hostel Photo</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="hostelPhoto" id="hostelPhoto" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" name="submit" class="btn btn-primary">Add Hostel</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="dashboard.php" class="btn btn-secondary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
</body>
</html>
