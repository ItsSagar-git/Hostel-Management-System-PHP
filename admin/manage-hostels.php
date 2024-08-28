<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $stmt = $mysqli->prepare("DELETE FROM hostels WHERE hostel_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Hostel deleted'); window.location.href='manage-hostels.php';</script>";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hostels</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
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
        .sidebar {
            height: 100%;
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #004d99;
            color: white;
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
            background-color: #003366;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .card-custom:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
        .table-custom th, .table-custom td {
            vertical-align: middle;
        }
        .hostel-photo {
            width: 100px;
            height: auto;
        }
        .dropdown-menu {
            min-width: 200px;
        }
    </style>
</head>
<body>
<?php include("includes/adnav.php"); ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Hostels</h1>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Hostel Details</div>
                    <div class="panel-body">
                        <table id="zctb" class="display table table-striped table-bordered table-hover table-custom" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Sno.</th>
                                <th>Hostel Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Hostel Email</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Fees</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $aid = $_SESSION['id'];
                            $ret = "SELECT hostel_id, hostel_name, hostel_address, hostel_contact,hostel_email, latitude, longitude, fees, hostel_photo FROM hostels";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $res->fetch_object()) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row->hostel_name); ?></td>
                                    <td><?php echo htmlspecialchars($row->hostel_address); ?></td>
                                    <td><?php echo htmlspecialchars($row->hostel_contact); ?></td>
                                    <td><?php echo htmlspecialchars($row->hostel_email); ?></td>
                                    <td><?php echo htmlspecialchars($row->latitude); ?></td>
                                    <td><?php echo htmlspecialchars($row->longitude); ?></td>
                                    <td><?php echo htmlspecialchars($row->fees); ?></td>
                                    <td>
                                        <?php if ($row->hostel_photo): ?>
                                            <img src="get-photo.php?id=<?php echo htmlspecialchars($row->hostel_id); ?>" alt="Hostel Photo" class="hostel-photo">
                                        <?php else: ?>
                                            No Photo
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit-hostel.php?id=<?php echo htmlspecialchars($row->hostel_id); ?>"><i class="bi bi-pencil-square"></i></a>&nbsp;&nbsp;
                                        <a href="manage-hostels.php?del=<?php echo htmlspecialchars($row->hostel_id); ?>" onclick="return confirm('Do you want to delete');"><i class="bi bi-trash"></i></a>
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
                <div class="text-center mt-4">
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });</script>

</body>
</html>
