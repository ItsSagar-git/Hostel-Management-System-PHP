<?php
include('includes/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT hostel_photo FROM hostels WHERE hostel_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    if ($photo) {
        header("Content-type: image/jpeg"); // Adjust MIME type if needed
        echo $photo;
    } else {
        header("HTTP/1.0 404 Not Found");
    }
} else {
    header("HTTP/1.0 400 Bad Request");
}
