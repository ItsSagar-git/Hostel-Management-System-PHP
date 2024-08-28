<?php
session_start();
include('includes/config.php'); // Ensure this file contains your database connection

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields');</script>";
    } else {
        $stmt = $mysqli->prepare("SELECT user_id, email, password FROM user_registration WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result(); // Store the result to free up resources for subsequent queries
            $stmt->bind_result($user_id, $fetched_email, $fetched_password);
            $stmt->fetch();

            // Check if a user was found and if the password matches the hashed password
            if ($stmt->num_rows == 1 && password_verify($password, $fetched_password)) {
                // Set session variables upon successful login
                $_SESSION['user_id'] = $user_id;
                $_SESSION['login'] = $fetched_email;

                // Close the statement to free up resources
                $stmt->close();

                // Logging user login
                $log = "INSERT INTO userlog (user_id, email, login_time) VALUES (?, ?, NOW())";
                $stmt_log = $mysqli->prepare($log);
                if ($stmt_log) {
                    $stmt_log->bind_param('is', $user_id, $fetched_email);
                    $stmt_log->execute();
                    $stmt_log->close();
                } else {
                    echo "Failed to prepare the log statement.";
                }

                // Redirect to the dashboard page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid Username/Email or password');</script>";
            }

            // Ensure the statement is closed
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
        .h-custom {
            height: calc(100% - 73px);
        }
        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
        .p {
            font-size: 14px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="login.webp" class="img-fluid" alt="Sample image">
            </div>

            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <form method="post" action="">
                    <h1>Hostel Management System</h1> <br>
                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" id="form3Example3" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" required />
                        <label class="form-label" for="form3Example3">Email address</label>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3">
                        <input type="password" id="form3Example4" name="password" class="form-control form-control-lg" placeholder="Enter password" required />
                        <label class="form-label" for="form3Example4">Password</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Checkbox -->
                        <div class="form-check mb-0">
                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                            <label class="form-check-label" for="form2Example3">Remember me</label>
                        </div>
                        <a href="forgot-password.php" class="text-body">Forgot password?</a>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" name="login" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                        <p style="font-size: 20px" class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="user_registration.php" class="link-danger">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
        <!-- Copyright -->
        <!-- Right -->
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

