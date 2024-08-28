<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['update_password'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current hashed password from the database
    $stmt = $mysqli->prepare("SELECT password FROM user_registration WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (password_verify($current_password, $hashed_password)) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $mysqli->prepare("UPDATE user_registration SET password = ? WHERE user_id = ?");
            $stmt->bind_param('si', $new_hashed_password, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Password updated successfully'); window.location.href = 'my-profile.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('New password and confirm password do not match');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            border-radius: 15px 15px 0 0;
            color: white;
        }
        .card-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #4facfe;
            border: none;
            border-radius: 10px;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #00f2fe;
        }
        .form-group label {
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
<?php include('includes/nav.php');?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Update Password</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="update_password.php">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-custom btn-block">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
