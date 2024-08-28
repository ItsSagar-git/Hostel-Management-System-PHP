<?php
session_start();
include('includes/config.php');
$errors = array();

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $contactno = $_POST['contact'];
    $emailid = $_POST['email'];
    $password = $_POST['password'];

    if (empty($fname)) {
        $errors['fname'] = "First Name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fname)) {
        $errors['fname'] = "First Name can only contain letters and spaces";
    }

    if (!empty($mname) && !preg_match('/^[a-zA-Z\s]+$/', $mname)) {
        $errors['mname'] = "Middle Name can only contain letters and spaces";
    }

    if (empty($lname)) {
        $errors['lname'] = "Last Name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $lname)) {
        $errors['lname'] = "Last Name can only contain letters and spaces";
    }

    if (empty($contactno)) {
        $errors['contact'] = "Contact No is required";
    } elseif (!is_numeric($contactno) || strlen($contactno) !== 10) {
        $errors['contact'] = "Contact No must be a 10-digit number";
    }

    if (empty($emailid)) {
        $errors['email'] = "Email is required";
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i', $emailid)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        $query = "INSERT INTO userregistration (firstName, middleName, lastName, gender, contactNo, email, password)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            trigger_error('Error: ' . $mysqli->error, E_USER_ERROR);
        }

        $stmt->bind_param('ssssiss', $fname, $mname, $lname, $gender, $contactno, $emailid, $password);

        if ($stmt->execute()) {
            $insertedUserId = $stmt->insert_id;

            $logQuery = "INSERT INTO userlog (userId, userEmail, Password)
                         VALUES (?, ?, ?)";
            $logStmt = $mysqli->prepare($logQuery);
            if ($logStmt === false) {
                trigger_error('Error: ' . $mysqli->error, E_USER_ERROR);
            }
            $logStmt->bind_param('sss', $insertedUserId, $emailid, $password);
            $logStmt->execute();
            $logStmt->close();

            echo "<script>alert('User Successfully registered');</script>";
        } else {
            echo "<script>alert('Error registering User');</script>";
        }

        $stmt->close();
        $mysqli->close();
    }
}
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
    <title>User Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #f9c30b;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #e1b208;
        }

        .text-center a {
            color: #3498db;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<div class="container">
    <h2 class="text-center" style="color: #3399cc;">Registration Form</h2>
    <form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">
        <div class="form-group">
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" class="form-control" required="required" value="<?php if (isset($fname)) echo $fname; ?>">
            <?php if (isset($errors['fname'])) echo "<span class='text-danger'>".$errors['fname']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="mname">Middle Name:</label>
            <input type="text" name="mname" id="mname" class="form-control" value="<?php if (isset($mname)) echo $mname; ?>">
            <?php if (isset($errors['mname'])) echo "<span class='text-danger'>".$errors['mname']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" class="form-control" required="required" value="<?php if (isset($lname)) echo $lname; ?>">
            <?php if (isset($errors['lname'])) echo "<span class='text-danger'>".$errors['lname']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="male" <?php if (isset($gender) && $gender === "male") echo "selected"; ?>>Male</option>
                <option value="female" <?php if (isset($gender) && $gender === "female") echo "selected"; ?>>Female</option>
                <option value="others" <?php if (isset($gender) && $gender === "others") echo "selected"; ?>>Others</option>
            </select>
            <?php if (isset($errors['gender'])) echo "<span class='text-danger'>".$errors['gender']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="contact">Contact No:</label>
            <input type="text" name="contact" id="contact" class="form-control" required="required" value="<?php if (isset($contactno)) echo $contactno; ?>">
            <?php if (isset($errors['contact'])) echo "<span class='text-danger'>".$errors['contact']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="email">Email ID:</label>
            <input type="email" name="email" id="email" class="form-control" onBlur="checkAvailability()" required="required" value="<?php if (isset($emailid)) echo $emailid; ?>">
            <span id="user-availability-status" style="font-size: 12px;"></span>
            <?php if (isset($errors['email'])) echo "<span class='text-danger'>".$errors['email']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required="required">
            <?php if (isset($errors['password'])) echo "<span class='text-danger'>".$errors['password']."</span>"; ?>
        </div>
        <div class="form-group">
            <label for="cpassword">Confirm Password:</label>
            <input type="password" name="cpassword" id="cpassword" class="form-control" required="required">
        </div>
        <div class="form-group">
            <button type="submit" name="submit" class="btn btn-primary">Register</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
    <div class="text-center mt-3">
        Already a member? <a href="login.php">Login here</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function valid() {
        if (document.registration.password.value != document.registration.cpassword.value) {
            alert("Password and Confirm Password do not match!");
            document.registration.cpassword.focus();
            return false;
        }
        return true;
    }

    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {
                alert('error');
            }
        });
    }
</script>
</body>

</html>
