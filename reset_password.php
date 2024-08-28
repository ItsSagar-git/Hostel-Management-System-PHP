<?php
include('includes/config.php');

if (isset($_GET['token']) && isset($_GET['expires'])) {
    $token = $_GET['token'];
    $expires = $_GET['expires'];

    // Validate token and expiration
    $stmt = $mysqli->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires > NOW()");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Display the reset password form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h3 class="text-center">Reset Password</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="reset_password.php">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                <div class="form-group">
                                    <label for="password">New Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <button type="submit" name="reset" class="btn btn-primary btn-block">Reset Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Invalid or expired token.');</script>";
    }

    $stmt->close();
} elseif (isset($_POST['reset'])) {
    $user_id = intval($_POST['user_id']);
    $password = $_POST['password'];

    if ($user_id > 0 && !empty($password)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $mysqli->prepare("UPDATE user_registration SET password=? WHERE user_id=?");
        $stmt->bind_param('si', $hashedPassword, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Delete the token after successful password reset
            $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('Your password has been updated successfully.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Failed to update the password. Please try again.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid request. Please provide a valid user ID and password.');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}
?>
