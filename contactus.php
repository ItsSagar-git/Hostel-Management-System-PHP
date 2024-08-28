<?php
// Start session and include database configuration
session_start();
include('includes/config.php');

// Initialize feedback variable
$feedback = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare SQL statement
    $query = "INSERT INTO contact_form (name, email, subject, message) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        // Bind parameters and execute statement
        $stmt->bind_param('ssss', $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $feedback = "<div class='alert alert-success' role='alert'>
                            <h4 class='alert-heading'>Contact Form Submitted</h4>
                            <p><strong>Name:</strong> $name</p>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Subject:</strong> $subject</p>
                            <p><strong>Message:</strong> $message</p>
                        </div>";
        } else {
            $feedback = "<div class='alert alert-danger' role='alert'>Something went wrong. Please try again.</div>";
        }
        $stmt->close();
    } else {
        $feedback = "<div class='alert alert-danger' role='alert'>Database error. Please try again later.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<?php include("includes/navbar.php"); ?>
<?php //include("includes/header.php");?>
<div class="contact1">
    <div class="container-contact1">
        <div class="contact1-pic js-tilt" data-tilt>
            <img src="images/img-01.png" alt="IMG">
        </div>

        <form class="contact1-form validate-form" action="" method="post">
                <span class="contact1-form-title">
                    Get in touch
                </span>

            <?php if (!empty($feedback)) echo $feedback; ?>

            <div class="wrap-input1 validate-input" data-validate="Name is required">
                <input class="input1" type="text" name="name" placeholder="Name" required>
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                <input class="input1" type="email" name="email" placeholder="Email" required>
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Subject is required">
                <input class="input1" type="text" name="subject" placeholder="Subject" required>
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Message is required">
                <textarea class="input1" name="message" placeholder="Message" required></textarea>
                <span class="shadow-input1"></span>
            </div>

            <div class="container-contact1-form-btn">
                <button class="contact1-form-btn">
                        <span>
                            Send Email
                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                        </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/tilt/tilt.jquery.min.js"></script>
<script>
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-23581568-13');
</script>
<script src="js/main.js"></script>

</body>
</html>
