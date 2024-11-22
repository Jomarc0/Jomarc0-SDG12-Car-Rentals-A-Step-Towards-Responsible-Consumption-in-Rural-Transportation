<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

require_once 'dbadmin.php'; // Ensure the class file is included

$rentedCarManager = new AdminRentedCar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for editing
    $data = [
        'rent_id' => $_POST['rent_id'],
        'booking_area' => $_POST['booking_area'],
        'destination' => $_POST['destination'],
        'trip_date_time' => $_POST['trip_date_time'],
        'return_date_time' => $_POST['return_date_time'],
        'vehicle_type' => $_POST['vehicle_type'],
        'rent_status' => $_POST['rent_status']
    ];

    if ($rentedCarManager->updateRentedCar($data)) {
        header("Location: adminHome.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating record.";
    }
}

// Fetch the existing data for the rented car
if (isset($_GET['id'])) {
    $rentId = $_GET['id']; // Get rent_id from the query string
    $userData = $rentedCarManager->getRentedCarByRentId($rentId); // Fetch the rented car details

    if (!$userData) {
        header("Location: adminHome.php");
        exit();
    }
} else {
    header("Location: adminHome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rented Car</title>
    <link rel="stylesheet" href="../css/admin.css">
        <style>
/* admin.css */

/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

/* General Styles */
body {
    margin: 0;
    font-family: 'Montserrat', sans-serif; /* Clean and modern font */
    background: linear-gradient(135deg, #f0f4f8, #e1e9f0); /* Soft gradient background */
    color: #333; /* Dark text color */
}

/* Header Styles */
h2 {
    text-align: center; /* Center the heading */
    margin: 30px 0; /* Margin above and below */
    color: #333; /* Darker text color */
    font-size: 28px; /* Larger font size */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Subtle text shadow */
}

/* Form Styles */
form {
    max-width: 500px; /* Max width of the form */
    margin: 40px auto; /* Center the form with margin */
    padding: 30px; /* Padding inside the form */
    background-color: #ffffff; /* White background for the form */
    border-radius: 15px; /* More rounded corners */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    transition: box-shadow 0.3s; /* Smooth shadow transition */
}

form:hover {
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); /* Shadow on hover */
}

/* Input and Label Styles */
label {
    display: block; /* Block display for labels */
    margin-bottom: 5px; /* Space below labels */
    font-weight: 700; /* Bold text for labels */
    color: #555; /* Darker color for labels */
}

input[type="text"],
input[type="email"],
input[type="datetime-local"],
select {
    width: 100%; /* Full width */
    padding: 12px; /* Increased padding inside the input */
    margin-bottom: 20px; /* Space below inputs */
    border: 2px solid #007bff; /* Blue border */
    border-radius: 10px; /* More rounded corners */
    box-sizing: border-box; /* Include padding and border in width */
    font-size: 16px; /* Font size */
    transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transitions */
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="datetime-local"]:focus,
select:focus {
    border-color: #0056b3; /* Darker blue border on focus */
    box-shadow: 0 0 8px rgba(0, 86, 179, 0.5); /* Glow effect */
    outline: none; /* Remove default outline */
}

/* Button Styles */
button {
    background: linear-gradient(90deg, #007bff, #0056b3); /* Gradient background */
    color: white; /* White text */
    padding: 12px 20px; /* Padding inside the button */
    border: none; /* No border */
    border-radius: 10px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background 0.3s, transform 0.2s, box-shadow 0.3s; /* Smooth transitions */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Button shadow */
}

button:hover {
    background: linear-gradient(90deg, #0056b3, #004494); /* Darker gradient on hover */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Enhanced shadow on hover */
}

/* Responsive Styles */
@media (max-width: 600px) {
    form {
        padding: 20px; /* Less padding on smaller screens */
    }

    button {
        width: 100%; /* Full width for buttons on small screens */
    }
}
    </style>
</head>
<body>
    <h2>Edit Rented Car Details</h2>
    <form method="POST" action="">
        <label>Rent ID:</label>
        <input type="text" name="rent_id" value="<?php echo htmlspecialchars($userData['rent_id']); ?>" readonly>

        <label>Booking Area:</label>
        <input type="text" name="booking_area" value="<?php echo htmlspecialchars($userData['booking_area']); ?>" required>
        
        <label>Destination:</label>
        <input type="text" name="destination" value="<?php echo htmlspecialchars($userData['destination']); ?>" required>
        
        <label>Trip Date & Time:</label>
        <input type="datetime-local" name="trip_date_time" value="<?php echo htmlspecialchars($userData['trip_date_time']); ?>" required>
        
        <label>Return Date & Time:</label>
        <input type="datetime-local" name="return_date_time" value="<?php echo htmlspecialchars($userData['return_date_time']); ?>" required>
        
        <label>Vehicle Type:</label>
        <input type="text" name="vehicle_type" value="<?php echo htmlspecialchars($userData['vehicle_type']); ?>" required>
        
        <label>Rent Status:</label>
        <select name="rent_status" required>
            <option value="">Select status</option>
            <option value="rented" <?php echo ($userData['rent_status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
            <option value="completed" <?php echo ($userData['rent_status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
        </select>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>