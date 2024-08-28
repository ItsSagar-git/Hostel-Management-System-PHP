<?php
include('includes/config.php');

// Check if hostel_id is set and is a valid number
if (isset($_GET['hostel_id']) && is_numeric($_GET['hostel_id'])) {
    $hostelId = intval($_GET['hostel_id']);

    $query = "SELECT r.rating, r.review, r.created_at, u.first_name
              FROM hostel_reviews r
              JOIN user_registration u ON r.user_id = u.user_id
              WHERE r.hostel_id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $hostelId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review'>";
                echo "<p><strong>" . htmlspecialchars($row['first_name']) . "</strong> (" . htmlspecialchars($row['created_at']) . ")</p>";
                echo "<p>Rating: " . htmlspecialchars($row['rating']) . "/5</p>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        $stmt->close();
    } else {
        echo "Failed to fetch reviews.";
    }
} else {
    echo "Invalid or missing hostel ID.";
}
?>
