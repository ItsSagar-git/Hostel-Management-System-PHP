<?php
session_start();
include('includes/config.php');

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($booking_id <= 0) {
    http_response_code(400);
    exit('Invalid booking ID.');
}

// Fetch the photo file path based on booking_id
$stmt = $mysqli->prepare("SELECT citizenship_photo FROM bookings WHERE booking_id = ?");
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$stmt->bind_result($photoPath);
$stmt->fetch();
$stmt->close();

if (empty($photoPath) || !file_exists($photoPath)) {
    http_response_code(404);
    exit('Photo not found.');
}

// Set the content type based on file extension
$mimeType = mime_content_type($photoPath);
header("Content-Type: $mimeType");
header("Content-Length: " . filesize($photoPath));
readfile($photoPath);
?>
