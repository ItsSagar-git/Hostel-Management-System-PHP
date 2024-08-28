<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate request
if (!isset($_POST['booking_id']) || !isset($_POST['status']) || !in_array($_POST['status'], ['Approved', 'Cancelled'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$bookingId = $_POST['booking_id'];
$status = $_POST['status'];

// Update booking status
$sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $bookingId);
if ($stmt->execute()) {
    // Get user_id associated with the booking
    $sql = "SELECT user_id FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    // Update notification count for the user
    $sql = "INSERT INTO notifications (user_id, count) VALUES (?, 1) ON DUPLICATE KEY UPDATE count = count + 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update booking status']);
}

$conn->close();
?>
