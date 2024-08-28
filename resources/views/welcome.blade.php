<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .logo {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            transition: background-color 0.2s;
        }
        .navbar a:hover {
            background-color: #555;
        }
        .nav-menu {
            display: flex;
            gap: 1rem;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #333;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {
            background-color: #575757;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .hero {
            background-color: #fff;
            padding: 4rem;
            text-align: center;
        }
        .hero h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        .hero p {
            margin: 1rem 0;
            font-size: 1.2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #3b82f6;
            color: #ffffff;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #2563eb;
        }
        .features {
            padding: 2rem;
        }
        .features .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .features img {
            max-width: 100%;
            border-radius: 8px;
        }
        .footer {
            background-color: #333;
            color: #ffffff;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="#" class="logo">Hostel Management</a>
    <div class="nav-menu">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
        <div class="dropdown">
            <a href="#" class="btn">Login</a>
            <div class="dropdown-content">
                <a href="#">Admin</a>
                <a href="#">User</a>
            </div>
        </div>
    </div>
</div>

<section class="hero">
    <h1>Welcome to the Hostel Management System</h1>
    <p>Your comfort is our priority. Manage your stay with ease.</p>
    <a href="#" class="btn">Get Started</a>
</section>

<section class="features">
    <div class="card">
        <img src="https://source.unsplash.com/400x300/?room" alt="Room">
        <h3>Comfortable Rooms</h3>
        <p>Enjoy well-furnished and comfortable rooms with all the amenities you need for a pleasant stay.</p>
        <a href="#" class="btn">Learn More</a>
    </div>
    <div class="card">
        <img src="https://source.unsplash.com/400x300/?food" alt="Food">
        <h3>Delicious Food</h3>
        <p>Savor a variety of delicious meals prepared with fresh ingredients and served with a smile.</p>
        <a href="#" class="btn">Learn More</a>
    </div>
    <div class="card">
        <img src="https://source.unsplash.com/400x300/?gym" alt="Gym">
        <h3>Fitness Center</h3>
        <p>Stay fit and healthy with access to our fully equipped fitness center and wellness programs.</p>
        <a href="#" class="btn">Learn More</a>
    </div>
</section>

<footer class="footer">
    <p>&copy; 2024 Hostel Management System. All rights reserved.</p>
</footer>
</body>
</html>
