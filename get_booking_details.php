<?php
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for additional styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 8px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <?php
    if (isset($_GET['booking_id'])) {
        $booking_id = intval($_GET['booking_id']);

        $ret = "SELECT b.booking_id, h.hostel_name, b.fees, b.status, b.duration, b.booking_date, h.hostel_address, h.hostel_contact
                FROM bookings b
                JOIN hostels h ON b.hostel_id = h.hostel_id
                WHERE b.booking_id = ?";

        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $booking_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $hostelFee = $row['duration'] * $row['fees'];
            $totalFee = $hostelFee;
            ?>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Booking Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($row['booking_id']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hostel Name:</strong> <?php echo htmlspecialchars($row['hostel_name']); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Fees Per Month:</strong> <?php echo htmlspecialchars($row['fees']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($row['booking_date']); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Duration:</strong> <?php echo htmlspecialchars($row['duration']); ?> Months</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hostel Address:</strong> <?php echo htmlspecialchars($row['hostel_address']); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Hostel Contact:</strong> <?php echo htmlspecialchars($row['hostel_contact']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="badge
                                    <?php echo ($row['status'] == 'Approved') ? 'badge-success' : (($row['status'] == 'Pending') ? 'badge-warning' : 'badge-danger'); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
<!--                            <p><strong>Hostel Fee:</strong> --><?php //echo htmlspecialchars($hostelFee); ?><!--</p>-->
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Fee:</strong> <?php echo htmlspecialchars($totalFee); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="my_bookings.php" class="btn btn-secondary">Back to My Bookings</a>
                </div>
            </div>

            <?php
        } else {
            echo '<div class="alert alert-danger">No booking details found.</div>';
        }
    }
    ?>
</div>

<!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
