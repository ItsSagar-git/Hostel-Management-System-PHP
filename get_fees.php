<?php
include('includes/config.php');

if (isset($_POST['hostel_id'])) {
    $hostelId = $_POST['hostel_id'];

    $query = "SELECT fees FROM hostels WHERE hostel_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $hostelId);
    $stmt->execute();
    $stmt->bind_result($fees);
    $stmt->fetch();
    $stmt->close();

    echo $fees;
}
?>
