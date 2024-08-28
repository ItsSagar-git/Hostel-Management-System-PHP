<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

// Code to update email
if (isset($_POST['update'])) {
    $email = $_POST['emailid'];
    $aid = $_SESSION['id'];
    $udate = date('Y-m-d');
    $query = "UPDATE admin SET email=?, updation_date=? WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssi', $email, $udate, $aid);
    $stmt->execute();
    echo "<script>alert('Email has been successfully updated');</script>";
}


// Code to change password
if (isset($_POST['changepwd'])) {
    $op = $_POST['oldpassword'];
    $np = $_POST['newpassword'];
    $ai = $_SESSION['id'];
    $udate = date('Y-m-d');
    $sql = "SELECT password FROM admin WHERE password=?";
    $chngpwd = $mysqli->prepare($sql);
    $chngpwd->bind_param('s', $op);
    $chngpwd->execute();
    $chngpwd->store_result();
    $row_cnt = $chngpwd->num_rows;


    if ($row_cnt > 0) {
        $con = "UPDATE admin SET password=? WHERE admin_id=?";
        $chngpwd1 = $mysqli->prepare($con);
        $chngpwd1->bind_param('si', $np, $ai);  // Corrected to match the number of placeholders
        $chngpwd1->execute();
        $_SESSION['msg'] = "Password Changed Successfully !!";
    } else {
        $_SESSION['msg'] = "Old Password does not match !!";
    }
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
    <title>Admin Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .page-title {
            font-weight: bold;
            text-align: center;
            font-size: 2.5rem;
            color: #343a40;
            margin-bottom: 30px;
        }
        .panel {
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .panel-heading {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 1.25rem;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
            font-size: 1rem;
            padding: 10px;
        }
        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-default {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }
        .btn-default:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .alert {
            font-size: 1rem;
        }
    </style>

</head>

<body>
<?php include('includes/adnav.php'); ?>
<div class="ts-main-content">
<!--    --><?php //include('includes/sidebar.php'); ?>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-title">Admin Profile</h2>
                    <?php
                    $aid = $_SESSION['id'];
                    $ret = "SELECT * FROM admin WHERE admin_id=?";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('i', $aid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_object()) {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Admin Profile Details</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
                                            <div class="form-group">
                                                <label for="username" class="col-sm-4 control-label">Username</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="username" value="<?php echo $row->username; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="emailid" class="col-sm-4 control-label">Email</label>
                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" name="emailid" id="emailid" value="<?php echo $row->email; ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-8 offset-sm-4">
                                                    <button type="submit" class="btn btn-default">Cancel</button>
                                                    <input type="submit" name="update" value="Update Profile" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Change Password</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
                                            <?php if (isset($_POST['changepwd'])) { ?>
                                                <div class="alert alert-danger">
                                                    <?php echo htmlentities($_SESSION['msg']); ?>
                                                    <?php echo htmlentities($_SESSION['msg'] = ""); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label for="oldpassword" class="col-sm-4 control-label">Old Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="oldpassword" id="oldpassword" required>
                                                    <span id="password-availability-status" class="help-block"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="newpassword" class="col-sm-4 control-label">New Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="newpassword" id="newpassword" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="cpassword" class="col-sm-4 control-label">Confirm Password</label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="cpassword" id="cpassword" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-8 offset-sm-4">
                                                    <button type="submit" class="btn btn-default">Cancel</button>
                                                    <input type="submit" name="changepwd" value="Change Password" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    function checkpass() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'oldpassword=' + $("#oldpassword").val(),
            type: "POST",
            success: function(data) {
                $("#password-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    }
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
</body>
</html>
