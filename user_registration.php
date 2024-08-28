<?php
session_start();
include('includes/config.php');

if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $stmt = $mysqli->prepare("INSERT INTO user_registration (first_name, middle_name, last_name, address, gender, contact, email, password, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssssdd', $first_name, $middle_name, $last_name, $address, $gender, $contact, $email, $hashed_password, $latitude, $longitude);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        .h-custom {
            height: 100vh !important;
        }
        .gradient-custom-2 {
            background: #a1c4fd;
            background: -webkit-linear-gradient(to right, rgba(161, 196, 253, 1), rgba(194, 233, 251, 1));
            background: linear-gradient(to right, rgba(161, 196, 253, 1), rgba(194, 233, 251, 1));
        }
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 15px;
            position: relative;
        }
        .locate-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .locate-button:hover {
            background-color: #e9ecef;
        }
    </style>
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
</head>
<body>
<section class="h-100 h-custom gradient-custom-2">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-8">
                <div class="card card-registration" style="border-radius: 15px;">
                    <div class="card-body p-5">
                        <h3 class="fw-normal mb-5" style="color: #4835d4;">General Information</h3>
                        <form method="POST" action="user_registration.php" onsubmit="return validateForm()">
                            <div class="row">
                                <div class="col-md-6 mb-4 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" name="first_name" id="form3Examplev2" class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Examplev2">First name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" name="middle_name" id="form3Examplev2" class="form-control form-control-lg"  />
                                        <label class="form-label" for="form3Examplev2">Middle name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" name="last_name" id="form3Examplev3" class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Examplev3">Last name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 pb-2">
                                <div data-mdb-input-init class="form-outline">
                                    <input type="text" name="address" id="form3Examplev4" class="form-control form-control-lg" required />
                                    <label class="form-label" for="form3Examplev4">Address</label>
                                </div>
                            </div>
                            <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">
                                <h6 class="mb-0 me-4">Gender: </h6>
                                <div class="form-check form-check-inline mb-0 me-4">
                                    <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="Female" required />
                                    <label class="form-check-label" for="femaleGender">Female</label>
                                </div>
                                <div class="form-check form-check-inline mb-0 me-4">
                                    <input class="form-check-input" type="radio" name="gender" id="maleGender" value="Male" />
                                    <label class="form-check-label" for="maleGender">Male</label>
                                </div>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input" type="radio" name="gender" id="otherGender" value="Other" />
                                    <label class="form-check-label" for="otherGender">Other</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" name="contact" id="form3Examplev5" class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Examplev5">Contact</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 pb-2">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="email" name="email" id="form3Examplev6" class="form-control form-control-lg" required />
                                        <label class="form-label" for="form3Examplev6">Email ID</label>
                                    </div>
                                </div>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" name="password" id="form3Examplev7" class="form-control form-control-lg" required />
                                <label class="form-label" for="form3Examplev7">Password</label>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" name="latitude" id="latitude" class="form-control form-control-lg" required />
                                <label class="form-label" for="latitude">Latitude</label>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" name="longitude" id="longitude" class="form-control form-control-lg" required />
                                <label class="form-label" for="longitude">Longitude</label>
                            </div>
                            <div id="map">

                            </div>
                            <div class="d-flex justify-content-end pt-3">
                                <button type="reset" class="btn btn-light btn-lg">Reset all</button>
                                <button type="submit" name="register" class="btn btn-warning btn-lg ms-2">Submit form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.css" />
<script src="https://unpkg.com/leaflet.locatecontrol@0.72.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>
    var map = L.map('map').setView([27.7172, 85.3240], 13); // Kathmandu, Nepal

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    map.on('click', function(e) {
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });

    L.control.locate({
        position: 'topleft',
        drawCircle: false,
        follow: true,
        setView: 'once',
        keepCurrentZoomLevel: true
    }).addTo(map);
</script>
</body>
</html>
