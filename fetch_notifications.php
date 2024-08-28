<?php
session_start();

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => null, 'error' => 'User ID not set in session']);
    exit;
}

$user_id = $_SESSION['user_id'];

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

// Fetch notification count
$sql = "SELECT count FROM notifications WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['count' => null, 'error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

echo json_encode(['count' => $count]);

$conn->close();
?>
