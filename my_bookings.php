<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');

$aid = $_SESSION['login']; // Assuming the user's email is stored in the session

// Handle booking cancellation
if (isset($_POST['cancel_booking'])) {
    $bookingId = $_POST['booking_id'];

    // Update the booking status to "Cancelled"
    $updateQuery = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = ? AND status = 'Pending'";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('i', $bookingId);
    $stmt->execute();
    $stmt->close();

    header("Location: my_bookings.php");
    exit;
}

// Handle booking deletion
if (isset($_POST['delete_booking'])) {
    $bookingId = $_POST['booking_id'];

    // Delete the booking record from the database
    $deleteQuery = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $mysqli->prepare($deleteQuery);
    $stmt->bind_param('i', $bookingId);
    $stmt->execute();
    $stmt->close();

    header("Location: my_bookings.php");
    exit;
}

// Handle booking editing
if (isset($_POST['edit_booking'])) {
    $bookingId = $_POST['booking_id'];
    $newDate = $_POST['new_booking_date'];
    $newDuration = $_POST['new_duration'];

    // Update the booking details (assuming editing date and duration)
    $updateQuery = "UPDATE bookings SET booking_date = ?, duration = ? WHERE booking_id = ? AND status = 'Pending'";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('sii', $newDate, $newDuration, $bookingId);
    $stmt->execute();
    $stmt->close();

    header("Location: my_bookings.php");
    exit;
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>My Bookings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-cancelled {
            color: red;
            font-weight: bold;
        }
        .square-table {
            border: 2px solid #dee2e6;
            border-radius: 0;
        }
        .square-table th, .square-table td {
            text-align: center;
            vertical-align: middle;
        }
        .square-table td {
            border-top: 1px solid #dee2e6;
        }
        .square-table th {
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
<?php include("includes/nav.php"); ?>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Bookings</h1>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered square-table">
                    <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Hostel Name</th>
                        <th>Stay From</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $ret = "SELECT b.booking_id, h.hostel_name, b.booking_date, b.duration, b.status
                            FROM bookings b
                            JOIN hostels h ON b.hostel_id = h.hostel_id
                            JOIN user_registration u ON b.user_id = u.user_id
                            WHERE u.email = ?";

                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('s', $aid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_assoc()) {
                        // Determine the status class
                        $statusClass = '';
                        if ($row['status'] == 'Pending') {
                            $statusClass = 'status-pending';
                        } elseif ($row['status'] == 'Approved') {
                            $statusClass = 'status-approved';
                        } elseif ($row['status'] == 'Cancelled') {
                            $statusClass = 'status-cancelled';
                        }
                        ?>

                        <tr>
                            <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['hostel_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['duration']); ?> Months</td>
                            <td class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="get_booking_details.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" onclick="showEditModal(<?php echo $row['booking_id']; ?>, '<?php echo $row['booking_date']; ?>', <?php echo $row['duration']; ?>)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <!-- Cancel Button -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                        <button type="submit" name="cancel_booking" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                <?php } elseif ($row['status'] == 'Cancelled') { ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                        <button type="submit" name="delete_booking" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?');">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for editing bookings -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Booking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="new_booking_date">New Booking Date</label>
                                    <input type="date" name="new_booking_date" id="new_booking_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_duration">New Duration (Months)</label>
                                    <input type="number" name="new_duration" id="new_duration" class="form-control" required>
                                </div>
                                <input type="hidden" name="booking_id" id="edit_booking_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="edit_booking" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function showEditModal(bookingId, bookingDate, duration) {
        $('#edit_booking_id').val(bookingId);
        $('#new_booking_date').val(bookingDate);
        $('#new_duration').val(duration);
        $('#editModal').modal('show');
    }
</script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
