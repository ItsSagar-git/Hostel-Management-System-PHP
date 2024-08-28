<?php
session_start();
include('includes/config.php'); // Ensure this file contains your database connection

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare and execute the statement to check the user credentials
    $stmt = $mysqli->prepare("SELECT username, email, password, admin_id FROM admin WHERE (username=? OR email=?) AND password=?");
    $stmt->bind_param('sss', $username, $username, $password);
    $stmt->execute();
    $stmt->bind_result($fetched_username, $fetched_email, $fetched_password, $id);
    $rs = $stmt->fetch();
    $stmt->close();

    if ($rs) {
        $_SESSION['id'] = $id;
        $uip = $_SERVER['REMOTE_ADDR'];
        $ldate = date('d/m/Y h:i:s', time());

        // $insert = "INSERT into admin(adminid,ip)VALUES(?,?)";
        // $stmtins = $mysqli->prepare($insert);
        // $stmtins->bind_param('sH', $id, $uip);
        // $res = $stmtins->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username/Email or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form method="post" action="">
                    <h1>Admin Login</h1><br>
                    <div class="form-outline mb-4">
                        <input type="text" id="form1Example13" name="username" class="form-control form-control-lg" required />
                        <label class="form-label" for="form1Example13">Username or Email</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="password" id="form1Example23" name="password" class="form-control form-control-lg" required />
                        <label class="form-label" for="form1Example23">Password</label>
                    </div>

                    <div class="d-flex justify-content-around align-items-center mb-4">
                        <div class="form-check">



                    <button type="submit" name="login" class="btn btn-primary btn-lg btn-block">Sign in</button>

                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
