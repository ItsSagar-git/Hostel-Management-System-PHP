<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch current hostel details
    $stmt = $mysqli->prepare("SELECT hostel_name, hostel_address, hostel_contact,hostel_email, latitude, longitude, fees, hostel_photo FROM hostels WHERE hostel_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($hostel_name, $hostel_address, $hostel_contact,$hostel_email, $latitude, $longitude, $fees, $hostel_photo);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hostel_name = $_POST['hostel_name'];
        $hostel_address = $_POST['hostel_address'];
        $hostel_contact = $_POST['hostel_contact'];
        $hostel_email=$_POST['hostel_email'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $fees = $_POST['fees'];

        // Handle file upload
        if (isset($_FILES['hostel_photo']) && $_FILES['hostel_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['hostel_photo']['tmp_name'];
            $hostel_photo = file_get_contents($file);
        }

        // Update hostel details
        $query = "UPDATE hostels SET hostel_name = ?, hostel_address = ?, hostel_contact = ?,hostel_email=?, latitude = ?, longitude = ?, fees = ?, hostel_photo = ? WHERE hostel_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssddsi', $hostel_name, $hostel_address, $hostel_contact,$hostel_email, $latitude, $longitude, $fees, $hostel_photo, $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Hostel updated successfully'); window.location.href='manage-hostels.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Hostel ID'); window.location.href='manage-hostels.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hostel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <style>
        .navbar-custom {
            background-color: #004d99;
        }
        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
            color: white;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ffcc00;
        }
        .main-content {
            margin-top: 80px; /* Adjusted to avoid overlap with navbar */
            padding: 20px;
        }
        .card-custom:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
        #map {
            height: 400px;
            width: 100%;
            position: relative;
        }
        .leaflet-bar {
            background-color: #004d99;
        }
        .leaflet-control {
            background: #ffffff;
            border: 1px solid #004d99;
            color: #004d99;
        }
        .leaflet-control .leaflet-bar a {
            background: #004d99;
            color: white;
        }
        .leaflet-control .leaflet-bar a:hover {
            background: #ffcc00;
            color: #004d99;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js"></script>
</head>
<body>
<?php include('includes/adnav.php')?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Hostel</h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Edit Hostel Details</div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="hostel_name">Hostel Name</label>
                                <input type="text" class="form-control" id="hostel_name" name="hostel_name" value="<?php echo htmlspecialchars($hostel_name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hostel_address">Address</label>
                                <input type="text" class="form-control" id="hostel_address" name="hostel_address" value="<?php echo htmlspecialchars($hostel_address); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hostel_contact">Contact</label>
                                <input type="text" class="form-control" id="hostel_contact" name="hostel_contact" value="<?php echo htmlspecialchars($hostel_contact); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hostel_email">Hostel Email</label>
                                <input type="text" class="form-control" id="hostel_email" name="hostel_email" value="<?php echo htmlspecialchars($hostel_email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($latitude); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($longitude); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="fees">Fees</label>
                                <input type="number" step="0.01" class="form-control" id="fees" name="fees" value="<?php echo htmlspecialchars($fees); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hostel_photo">Hostel Photo</label>
                                <?php if ($hostel_photo): ?>
                                    <div>
                                        <img src="get-photo.php?id=<?php echo $id; ?>" alt="Hostel Photo" width="100">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control-file" id="hostel_photo" name="hostel_photo">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Hostel</button>
                            <a href="manage-hostels.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Map Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Hostel Location</div>
                    <div class="panel-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Map Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([27.7172, 85.3240], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add Geocoder Control
        var geocoder = L.Control.Geocoder.nominatim();
        L.Control.geocoder({
            geocoder: geocoder,
            placeholder: 'Search for a location',
            defaultMarkGeocode: false
        })
            .on('markgeocode', function(e) {
                var bbox = e.geocode.bbox;
                var latlng = e.geocode.center;
                var lat = latlng.lat;
                var lng = latlng.lng;

                map.fitBounds(bbox);
                L.marker([lat, lng]).addTo(map)
                    .bindPopup(e.geocode.name)
                    .openPopup();

                // Update the latitude and longitude input fields
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            })
            .addTo(map);

        // Add Locate Control
        L.control.locate({
            position: 'topleft',
            drawCircle: false,
            follow: true,
            setView: 'once',
            keepCurrentZoomLevel: true,
            icon: 'fa fa-location-arrow' // Font Awesome arrow icon
        }).addTo(map);

        // Add click event to set marker and input values
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
    });
</script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
