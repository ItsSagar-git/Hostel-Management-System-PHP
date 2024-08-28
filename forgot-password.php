<?php
session_start();
include('includes/config.php');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    // Prepare and execute SQL statement
    $stmt = $mysqli->prepare("SELECT email, contact, password FROM user_registration WHERE email=? AND contact=?");
    $stmt->bind_param('ss', $email, $contact);
    $stmt->execute();
    $stmt->bind_result($retrievedEmail, $retrievedContact, $password);

    if ($stmt->fetch()) {
        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();                                      // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                 // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = 'hostel.mmanagement@gmail.com';   // SMTP username (your Gmail email address)
            $mail->Password   = 'nyqa liep ewen arou';            // SMTP password (use app-specific password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
            $mail->Port       = 587;                              // TCP port to connect to

            // Recipients
            $mail->setFrom('hostel.mmanagement@gmail.com', 'Hostel Management System'); // From email address and name
            $mail->addAddress($email); // Add recipient's email address

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Your Password Recovery';
            $mail->Body    = "We received a request to reset your password. Click the link below to create a new password:<br><br>
                              <a href='http://127.0.0.1/HMS/reset_password.php?token=$token&expires=$expires'>Reset Password</a><br><br>
                              This link will expire in 1 hour.";

            $mail->send();
            echo "<script>alert('Password has been sent to your email address.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send password. Error: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email/Contact number.');</script>";
    }

    $stmt->close();
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

    <title>User Forgot Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        .forgot-password-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .forgot-password-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
        }

        .form-control {
            height: 45px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-block {
            width: 100%;
            margin-top: 20px;
        }

        .login-link {
            display: block;
            margin-top: 30px;
            text-align: center;
            color: #007bff;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="forgot-password-container">
    <h2>Forgot Password</h2>
    <?php if (isset($_POST['login'])) { ?>
        <p class="alert alert-success">Please check your email for your password. Change the password after login.</p>
    <?php } ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" placeholder="Email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contact">Your Contact No</label>
            <input type="text" placeholder="Contact No" name="contact" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-block">Send Password</button>
    </form>

    <a href="login.php" class="login-link">Back to Login</a>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
