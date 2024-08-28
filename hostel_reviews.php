<?php
session_start();
include('includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get hostel ID from query parameter
$hostel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($hostel_id <= 0) {
    die('Invalid hostel ID.');
}

// Debugging
echo "Hostel ID: " . $hostel_id . "<br>";

// Fetch hostel details
$query = "SELECT * FROM hostels WHERE hostel_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $hostel_id);
$stmt->execute();
$result = $stmt->get_result();
$hostel = $result->fetch_assoc();

if (!$hostel) {
    die('Hostel not found.');
}

// Fetch hostel reviews
$review_query = "
    SELECT user_registration.first_name, hostel_reviews.rating, hostel_reviews.review, hostel_reviews.created_at
    FROM hostel_reviews
    JOIN user_registration ON hostel_reviews.user_id = user_registration.user_id
    WHERE hostel_reviews.hostel_id = ?
    ORDER BY hostel_reviews.created_at DESC";
$review_stmt = $mysqli->prepare($review_query);
$review_stmt->bind_param('i', $hostel_id);
$review_stmt->execute();
$reviews_result = $review_stmt->get_result();
$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);
$review_stmt->close();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review_text = isset($_POST['review']) ? trim($_POST['review']) : '';

    if ($rating < 1 || $rating > 5) {
        $error = 'Rating must be between 1 and 5.';
    } elseif (empty($review_text)) {
        $error = 'Review cannot be empty.';
    } else {
        $user_id = $_SESSION['user_id'];

        // Insert review into the database
        $insert_query = "INSERT INTO hostel_reviews (hostel_id, user_id, rating, review) VALUES (?, ?, ?, ?)";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param('iiis', $hostel_id, $user_id, $rating, $review_text);

        if ($insert_stmt->execute()) {
            $success = 'Review submitted successfully!';
        } else {
            $error = 'Failed to submit review: ' . $mysqli->error;
        }

        $insert_stmt->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- ... (same as your previous HTML head section) ... -->
</head>
<body style="background-color: #f4f4f4;">
<?php include('includes/nav.php'); ?>

<div class="main-content flex-grow-1 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="page-title">Hostel Details</h2>
                <div class="panel-body">
                    <!-- Hostel details and reviews display -->
                    <!-- ... (same as your previous HTML body section) ... -->

                    <!-- Display success or error messages -->
                    <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
                    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">Post Review</button>
                        <a href="dashboard.php" class="btn btn-secondary">Go to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Submit a Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="hostel_reviews.php?id=<?php echo $hostel_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <div class="form-group">
                        <label for="rating">Rating (1-5):</label>
                        <div class="star-rating">
                            <i class="fa fa-star" data-value="1"></i>
                            <i class="fa fa-star" data-value="2"></i>
                            <i class="fa fa-star" data-value="3"></i>
                            <i class="fa fa-star" data-value="4"></i>
                            <i class="fa fa-star" data-value="5"></i>
                            <input type="hidden" id="rating" name="rating" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="review">Review:</label>
                        <textarea id="review" name="review" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS and dependencies -->
<script>
    // Initialize star rating
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-rating .fa-star');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = this.getAttribute('data-value');
                document.getElementById('rating').value = selectedRating;
                updateStars(selectedRating);
            });

            star.addEventListener('mouseover', function() {
                updateStars(this.getAttribute('data-value'));
            });

            star.addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            stars.forEach(star => {
                if (star.getAttribute('data-value') <= rating) {
                    star.classList.add('checked');
                } else {
                    star.classList.remove('checked');
                }
            });
        }
    });

    // Initialize map
    document.addEventListener('DOMContentLoaded', function() {
        var lat = <?php echo $hostel['latitude']; ?>;
        var lng = <?php echo $hostel['longitude']; ?>;

        var map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('<?php echo htmlspecialchars($hostel['hostel_name']); ?>')
            .openPopup();
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
