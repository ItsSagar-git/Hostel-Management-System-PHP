<?php
session_start();
include('includes/config.php');

// Handle approval of a booking
if (isset($_POST['approve_id'])) {
    $booking_id = $_POST['approve_id'];
    $update_query = "UPDATE bookings SET status='Approved' WHERE booking_id=?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-bookings.php"); // Redirect to avoid resubmission
    exit();
}

// Handle cancellation of a booking
if (isset($_POST['cancel_id'])) {
    $booking_id = $_POST['cancel_id'];
    $update_query = "UPDATE bookings SET status='Cancelled' WHERE booking_id=?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-bookings.php"); // Redirect to avoid resubmission
    exit();
}

// Fetch bookings from the database
$query = "SELECT b.booking_id, b.fees, b.status, u.first_name, u.last_name
          FROM bookings b
          JOIN user_registration u ON b.user_id = u.user_id";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            margin-top: 50px;
        }

        .table-responsive {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #3498db;
            color: #fff;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-footer .btn-group {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .modal-footer .btn-group .btn {
            margin-left: 5px;
        }

        /* Custom classes for status */
        .status-pending {
            color: #FFFF00; /* Yellow for Pending */
        }

        .status-approved {
            color: #28a745; /* Green for Approved */
        }

        .status-cancelled {
            color: #e74c3c; /* Red for Cancelled */
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"></head>
<body>
<?php include('includes/adnav.php'); ?>

<div class="container body-pd">
    <h2 class="text-center" style="color: #3399cc;">Manage Bookings</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Booking ID</th>
                <th>User Name</th>
                <th>Fees</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                $cnt = 1;
                while ($row = $result->fetch_assoc()) {
                    $statusClass = '';
                    if ($row['status'] == 'Pending') {
                        $statusClass = 'status-pending';
                    } elseif ($row['status'] == 'Approved') {
                        $statusClass = 'status-approved';
                    } elseif ($row['status'] == 'Cancelled') {
                        $statusClass = 'status-cancelled';
                    }

                    echo "<tr>";
                    echo "<td>" . $cnt . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fees']) . "</td>";
                    echo "<td class='" . $statusClass . "'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>
                                <a href='view_booking.php?id=" . $row['booking_id'] . "' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i> View</a>
                                <button class='btn btn-success btn-sm' data-toggle='modal' data-target='#approveModal' data-id='" . $row['booking_id'] . "'><i class='fas fa-check'></i> Approve</button>
                                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#cancelModal' data-id='" . $row['booking_id'] . "'><i class='fas fa-times'></i> Cancel</button>
                                <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal' data-id='" . $row['booking_id'] . "'><i class='fas fa-trash-alt'></i> Delete</button>
                              </td>";
                    echo "</tr>";
                    $cnt++;
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No Bookings Found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <p>Are you sure you want to approve this booking?</p>
                    <input type="hidden" name="approve_id" id="approve-id">
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking?</p>
                    <input type="hidden" name="cancel_id" id="cancel-id">
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancel Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="delete_booking.php">
                <div class="modal-body">
                    <p>Are you sure you want to delete this booking?</p>
                    <input type="hidden" name="delete_id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle the approve modal
    $('#approveModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#approve-id').val(id);
    });

    // Handle the cancel modal
    $('#cancelModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#cancel-id').val(id);
    });

    // Handle the delete modal
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#delete-id').val(id);
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
