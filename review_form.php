<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('includes/config.php');

// Validate hostel ID
if (isset($_GET['hostel_id']) && is_numeric($_GET['hostel_id'])) {
    $hostelId = intval($_GET['hostel_id']);
} else {
    die("Invalid hostel ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure POST variables are set and valid
    if (isset($_POST['rating']) && isset($_POST['review'])) {
        $rating = intval($_POST['rating']);
        $review = trim($_POST['review']);
        $userId = $_SESSION['user_id'];

        if ($rating >= 1 && $rating <= 5 && !empty($review)) {
            $stmt = $mysqli->prepare("INSERT INTO hostel_reviews (hostel_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('iiis', $hostelId, $userId, $rating, $review);
                if ($stmt->execute()) {
                    $success = "Review submitted successfully!";
                } else {
                    $error = "Failed to submit review.";
                }
                $stmt->close();
            } else {
                $error = "Failed to prepare statement.";
            }
        } else {
            $error = "Invalid rating or review.";
        }
    } else {
        $error = "Please provide both rating and review.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        .btn-primary {
            margin-top: 1rem;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1>Submit Review</h1>

    <!-- Display success or error messages -->
    <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

    <!-- Review Form -->
    <form method="POST" action="">
        <input type="hidden" name="hostel_id" value="<?php echo htmlspecialchars($hostelId); ?>">
        <div class="form-group">
            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
