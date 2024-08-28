<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');

// Handle deletion of a booking
if (isset($_POST['delete_id'])) {
    $booking_id = $_POST['delete_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM bookings WHERE booking_id=?";
    $stmt = $mysqli->prepare($delete_query);

    if ($stmt) {
        $stmt->bind_param('i', $booking_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to manage bookings page to avoid resubmission
        header("Location: manage-bookings.php");
        exit();
    } else {
        echo "<script>alert('Failed to delete booking'); window.location.href = 'manage-bookings.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request'); window.location.href = 'manage-bookings.php';</script>";
}
?>
