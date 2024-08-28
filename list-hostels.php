<?php
session_start();
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Hostels</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .card-custom:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
        .hostel-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .book-btn {
            background-color: #004d99;
            color: white;
        }
        .book-btn:hover {
            background-color: #ffcc00;
            color: black;
        }
    </style>
</head>
<body>
<?php include('includes/navbar.php'); ?>
<div class="main-content">
    <div class="container mt-5">
        <h2>Available Hostels</h2>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Sno.</th>
                <th>Hostel Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Fees (Per Month)</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT hostel_id, hostel_name, hostel_address, hostel_contact, latitude, longitude, fees, hostel_photo FROM hostels";
            $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $res = $stmt->get_result();
            $cnt = 1;
            while ($row = $res->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo htmlspecialchars($row['hostel_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['hostel_address']); ?></td>
                    <td><?php echo htmlspecialchars($row['hostel_contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['latitude']); ?></td>
                    <td><?php echo htmlspecialchars($row['longitude']); ?></td>
                    <td><?php echo htmlspecialchars($row['fees']); ?></td>
                    <td>
                        <?php if ($row['hostel_photo']): ?>
                            <img src="get_photo.php?id=<?php echo htmlspecialchars($row['hostel_id']); ?>" class="hostel-photo" alt="Hostel Photo">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="login.php?" class="btn book-btn">Book</a>
                    </td>
                </tr>
                <?php
                $cnt++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
