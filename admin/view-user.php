<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "SELECT * FROM user_registration WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('includes/adnav.php'); ?>
<div class="container mt-5">
    <h2>User Details</h2>
    <?php if ($user): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="card-text"><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p class="card-text"><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
                <p class="card-text"><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                <!-- Add more fields as needed -->
                <a href="manage-users.php" class="btn btn-primary">Back to Users</a>
            </div>
        </div>
    <?php else: ?>
        <p class="text-danger">User not found.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</body>
</html>
