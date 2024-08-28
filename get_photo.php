<?php
include('includes/config.php');

if (isset($_GET['id'])) {
    $hostel_id = $_GET['id'];

    // Prepare and execute query to fetch hostel photo
    $stmt = $mysqli->prepare("SELECT hostel_photo FROM hostels WHERE hostel_id = ?");
    $stmt->bind_param('i', $hostel_id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();

    if ($photo) {
        // Set the appropriate header for the image
        header("Content-Type: image/jpeg");
        echo $photo;
    } else {
        http_response_code(404);
        echo "Photo not found.";
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
