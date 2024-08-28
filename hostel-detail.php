<?php
// Include database connection
include('includes/config.php');

// Get hostel ID from query parameter
$hostel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($hostel_id <= 0) {
    die('Invalid hostel ID.');
}

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
//$review_query = "
//    SELECT user_registration.first_name, hostel_reviews.rating, hostel_reviews.review, hostel_reviews.created_at
//    FROM hostel_reviews
//    JOIN user_registration ON hostel_reviews.user_id = user_registration.user_id
//    WHERE hostel_reviews.hostel_id = ?
//    ORDER BY hostel_reviews.created_at DESC";
//$review_stmt = $mysqli->prepare($review_query);
//$review_stmt->bind_param('i', $hostel_id);
//$review_stmt->execute();
//$reviews_result = $review_stmt->get_result();
//$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);
//
//$review_stmt->close();
//$stmt->close();
//?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Hostel Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #004d99;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar .active {
            background-color: #ffcc00;
            color: #004d99;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .form-control {
            border-radius: 0;
        }
        #map {
            height: 400px;
            margin-top: 20px;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .review-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .review-header {
            font-weight: bold;
            color: #004d99;
        }
        .review-date {
            font-size: 0.9rem;
            color: #999;
        }
        .review-rating {
            color: #ffcc00;
            font-size: 1.1rem;
        }
        .review-comment {
            margin-top: 10px;
        }
        .star-rating {
            display: flex;
            direction: row-reverse;
            font-size: 2rem;
            cursor: pointer;
        }
        .star-rating .fa-star {
            color: #ddd;
            transition: color 0.2s ease;
        }
        .star-rating .fa-star.checked {
            color: #ffcc00;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body style="background-color: #f4f4f4;">
<?php include('includes/nav.php'); ?>

<div class="main-content flex-grow-1 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="page-title">Hostel Details</h2>
                <div class="panel-body">
                    <div class="form-group row">
                        <label for="hostelName" class="col-sm-2 col-form-label">Hostel Name</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['hostel_name']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hostelAddress" class="col-sm-2 col-form-label">Hostel Address</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['hostel_address']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hostelEmail" class="col-sm-2 col-form-label">Hostel Email</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['hostel_email']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hostelContact" class="col-sm-2 col-form-label">Hostel Contact</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['hostel_contact']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['latitude']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['longitude']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fees" class="col-sm-2 col-form-label">Fees Per Month</label>
                        <div class="col-sm-10">
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($hostel['fees']); ?></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hostelPhoto" class="col-sm-2 col-form-label">Hostel Photo</label>
                        <div class="col-sm-10">
                            <img src="get_photo.php?id=<?php echo $hostel_id; ?>" alt="Hostel Photo" class="img-fluid">
                        </div>
                    </div>
                    <h2>User Reviews</h2>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <?php echo htmlspecialchars($review['first_name']); ?><br>
                                    <span class="review-rating">
                                        <?php echo str_repeat('★', intval($review['rating'])); ?>
                                    </span>
                                </div>
                                <div class="review-date">
                                    <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                </div>
                                <div class="review-comment">
                                    <?php echo nl2br(htmlspecialchars($review['review'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reviews yet.</p>
                    <?php endif; ?>
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
                <form method="POST" action="hostel_reviews.php">
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
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
