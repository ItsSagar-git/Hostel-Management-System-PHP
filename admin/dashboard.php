<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

// Fetch notifications
// Contact form requests count
$contactQuery = "SELECT id, name FROM contact_form WHERE status='unread'";
$contactStmt = $mysqli->prepare($contactQuery);
$contactStmt->execute();
$contactResult = $contactStmt->get_result();
$contacts = $contactResult->fetch_all(MYSQLI_ASSOC);
$contactStmt->close();

// Booking requests count
$bookingQuery = "SELECT booking_id FROM bookings WHERE status='pending'";
$bookingStmt = $mysqli->prepare($bookingQuery);
$bookingStmt->execute();
$bookingResult = $bookingStmt->get_result();
$bookings = $bookingResult->fetch_all(MYSQLI_ASSOC);
$bookingStmt->close();
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

    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stat-panel {
            padding: 20px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .panel-footer {
            display: block;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.1);
            text-align: center;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            color: #fff;
            font-size: 1.2rem;
        }

        .dashboard-header {
            font-size: 2.5rem;
            color: yellowgreen;
            text-align: center;
            margin-bottom: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 1.5rem;
        }

        .chart-container {
            margin-top: 30px;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar {
            margin-bottom: 30px;
        }
    </style>
</head>

<body style="background-color: #f4f4f4;">

<?php include('includes/adnav.php')?>

<div class="content-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <h2 class="dashboard-header">Dashboard</h2>

                <?php
                // Query to count available hostels
                $result = "SELECT count(*) FROM hostels";
                $stmt = $mysqli->prepare($result);
                if ($stmt) {
                    $stmt->execute();
                    $stmt->bind_result($hostelCount);
                    $stmt->fetch();
                    $stmt->close();
                } else {
                    $hostelCount = 0; // Fallback value
                }

                // Query to count bookings
                $result1 = "SELECT count(*) FROM bookings";
                $stmt1 = $mysqli->prepare($result1);
                if ($stmt1) {
                    $stmt1->execute();
                    $stmt1->bind_result($bookingCount);
                    $stmt1->fetch();
                    $stmt1->close();
                } else {
                    $bookingCount = 0; // Fallback value
                }

                // Query to count registered users
                $result2 = "SELECT count(*) FROM user_registration";
                $stmt2 = $mysqli->prepare($result2);
                if ($stmt2) {
                    $stmt2->execute();
                    $stmt2->bind_result($userCount);
                    $stmt2->fetch();
                    $stmt2->close();
                } else {
                    $userCount = 0; // Fallback value
                }
                ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-panel" style="background-color: #3498db;">
                            <div class="stat-number"><?php echo $hostelCount; ?></div>
                            <div class="stat-label">Available Hostels</div>
                            <a href="manage-hostels.php" class="panel-footer">Full Detail <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-panel" style="background-color: #1abc9c;">
                            <div class="stat-number"><?php echo $bookingCount; ?></div>
                            <div class="stat-label">Total Bookings</div>
                            <a href="manage-bookings.php" class="panel-footer">See All <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-panel" style="background-color: #e74c3c;">
                            <div class="stat-number"><?php echo $userCount; ?></div>
                            <div class="stat-label">Registered Users</div>
                            <a href="manage-users.php" class="panel-footer">View Users <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row chart-container">
                    <div class="col-md-6">
                        <canvas id="hostelsBookingsChart" width="300" height="200"></canvas>
                    </div>
                    <div class="col-md-3">
                        <canvas id="pieChart" width="150" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data for bar chart
        var hostelsBookingsData = {
            labels: ['Available Hostels', 'Total Bookings', 'Registered Users'],
            datasets: [{
                label: 'Count',
                data: [<?php echo $hostelCount; ?>, <?php echo $bookingCount; ?>, <?php echo $userCount; ?>],
                backgroundColor: ['#3498db', '#1abc9c', '#e74c3c'],
                borderColor: ['#2980b9', '#16a085', '#c0392b'],
                borderWidth: 1
            }]
        };

        // Data for pie chart
        var pieData = {
            labels: ['Available Hostels', 'Total Bookings', 'Registered Users'],
            datasets: [{
                data: [<?php echo $hostelCount; ?>, <?php echo $bookingCount; ?>, <?php echo $userCount; ?>],
                backgroundColor: ['#3498db', '#1abc9c', '#e74c3c'],
                borderColor: ['#2980b9', '#16a085', '#c0392b'],
                borderWidth: 1
            }]
        };

        // Hostels, Bookings, and Users bar chart
        var ctxHostelsBookings = document.getElementById('hostelsBookingsChart').getContext('2d');
        new Chart(ctxHostelsBookings, {
            type: 'bar',
            data: hostelsBookingsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Adjust step size as needed
                        }
                    }
                }
            }
        });

        // Pie chart
        var ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>

</body>

</html>
