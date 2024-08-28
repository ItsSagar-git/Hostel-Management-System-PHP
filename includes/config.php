<?php
$mysqli = new mysqli("localhost", "root", "", "hostel");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
