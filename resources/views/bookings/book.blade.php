<!DOCTYPE html>
<html>
<head>
    <title>Booking Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Booking Form</h2>
    <form action="#">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="hostel">Select Hostel:</label>
            <select id="hostel" name="hostel" required>
                <option value="Hostel A">Hostel A</option>
                <option value="Hostel B">Hostel B</option>
                <option value="Hostel C">Hostel C</option>
            </select>
        </div>
        <div class="form-group">
            <label for="room">Room Number:</label>
            <input type="text" id="room" name="room" required>
        </div>
        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>
</body>
</html>

