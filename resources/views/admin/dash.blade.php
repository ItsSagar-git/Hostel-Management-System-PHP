<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hostel Management System</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
        }
        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 1rem;
            text-align: center;
            background-color: #1a252f;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar ul li {
            border-bottom: 1px solid #1a252f;
        }
        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 1rem;
            transition: background-color 0.2s;
        }
        .sidebar ul li a:hover {
            background-color: #34495e;
        }
        .sidebar ul li a.active {
            background-color: #16a085;
        }
        .content {
            flex-grow: 1;
            padding: 2rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ecf0f1;
            padding: 1rem 2rem;
            border-bottom: 1px solid #bdc3c7;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .main {
            padding: 2rem;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        Hostel Management
    </div>
    <ul>
        <li><a href="#" class="active">Dashboard</a></li>
        <li><a href="#">Manage Hostels</a></li>
        <li><a href="#">Bookings</a></li>
        <li><a href="#">Users</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="#">Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div>Welcome, Admin</div>
    </div>
    <div class="main">
        <div class="card">
            <h2>Overview</h2>
            <p>Welcome to the admin dashboard. Here you can manage hostels, bookings, users, and view reports.</p>
        </div>
        <div class="card">
            <h2>Manage Hostels</h2>
            <p>Here you can add, edit, or delete hostel information.</p>
        </div>
        <div class="card">
            <h2>Bookings</h2>
            <p>Manage bookings and check room availability.</p>
        </div>
        <div class="card">
            <h2>Users</h2>
            <p>View and manage user information.</p>
        </div>
        <div class="card">
            <h2>Reports</h2>
            <p>Generate and view reports.</p>
        </div>
    </div>
</div>

</body>
</html>
