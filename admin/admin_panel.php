<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
checklogin();

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path as needed

// Handle status updates, reply submission, and deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['mark_read'])) {
        $contact_id = $_POST['contact_id'];
        $stmt = $mysqli->prepare("UPDATE contact_form SET status='read' WHERE id=?");
        $stmt->bind_param('i', $contact_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['reply_submit'])) {
        $contact_id = $_POST['contact_id'];
        $reply = htmlspecialchars($_POST['reply']);

        // Fetch email address of the enquiry
        $stmt = $mysqli->prepare("SELECT email FROM contact_form WHERE id=?");
        $stmt->bind_param('i', $contact_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $contact = $result->fetch_assoc();
        $email_to = $contact['email'];
        $stmt->close();

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();                                      // Send using SMTP
            $mail->Host       = '';                 // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                             // Enable SMTP authentication
            $mail->Username   = '';     // SMTP username
            $mail->Password   = '';            // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
            $mail->Port       = 25;                              // TCP port to connect to

            // Recipients
            $mail->setFrom('hostel.mmanagement@gmail.com', 'Hostel Management');
            $mail->addAddress($email_to);                         // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reply to Your Inquiry';
            $mail->Body    = nl2br($reply);

            $mail->send();
            echo "<script>alert('Reply sent successfully.');</script>";

            // Update the contact form with the reply
            $stmt2 = $mysqli->prepare("UPDATE contact_form SET reply=? WHERE id=?");
            $stmt2->bind_param('si', $reply, $contact_id);
            $stmt2->execute();
            $stmt2->close();
        } catch (Exception $e) {
            echo "<script>alert('Failed to send reply. Error: " . $mail->ErrorInfo . "');</script>";
        }
    } elseif (isset($_POST['delete_contact'])) {
        $contact_id = $_POST['contact_id'];

        // Delete the contact form submission
        $stmt = $mysqli->prepare("DELETE FROM contact_form WHERE id=?");
        $stmt->bind_param('i', $contact_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Contact form submission deleted successfully.');</script>";
    }
}

// Fetch contact form submissions from the database
$query = "SELECT * FROM contact_form";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Contact Form Submissions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #004d99;
        }
        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
            color: white;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ffcc00;
        }
        .footer-custom {
            background-color: #004d99;
            color: white;
            padding: 20px 0;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"></head>
</head>
<body>
<?php include("includes/adnav.php"); ?>
<div class="container mt-5">
    <h1 class="text-center">Contact Form Submissions</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date Submitted</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <!-- Mark as Read Button -->
                    <?php if ($row['status'] == 'unread'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="contact_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="mark_read" class="btn btn-success">Mark as Read</button>
                        </form>
                    <?php endif; ?>

                    <!-- Reply Button -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#replyModal<?php echo $row['id']; ?>">
                        Reply
                    </button>

                    <!-- Delete Button -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="contact_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_contact" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this submission?');">Delete</button>
                    </form>

                    <!-- Reply Modal -->
                    <div class="modal fade" id="replyModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="replyModalLabel<?php echo $row['id']; ?>">Reply to <?php echo htmlspecialchars($row['name']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="reply">Reply</label>
                                            <textarea class="form-control" name="reply" rows="5" required></textarea>
                                        </div>
                                        <input type="hidden" name="contact_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="reply_submit" value="1">
                                        <button type="submit" class="btn btn-primary">Send Reply</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer class="footer-custom text-center">
    <div class="container">
        <p>&copy; 2024 Hostel Management System. All rights reserved.</p>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
