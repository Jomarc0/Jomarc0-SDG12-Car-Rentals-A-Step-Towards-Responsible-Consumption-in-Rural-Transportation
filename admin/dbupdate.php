<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

require_once 'RentedCarManager.php'; // Ensure the class file is included

$rentedCarManager = new AdminRentedCar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for editing
    $data = [
        'user_id' => $_POST['user_id'],
        'booking_area' => $_POST['booking_area'],
        'destination' => $_POST['destination'],
        'trip_date_time' => $_POST['trip_date_time'],
        'return_date_time' => $_POST['return_date_time'],
        'vehicle_type' => $_POST['vehicle_type'],
        'rent_status' => $_POST['rent_status']
    ];

    if ($rentedCarManager->updateRentedCar($data)) {
        header("Location: admin_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating record.";
    }
}

// Fetch the existing data for the user
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userData = $rentedCarManager->getRentedCarById($userId);

    if (!$userData) {
        // Redirect if no user data found
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: dashboard.php");
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
</head>
<body>
    <h2>Edit Rented Car Details</h2>
    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userData['user_id']); ?>">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>">
        
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>" >
        
        <label>Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($userData['address']); ?>" >
        
        <label>Gender:</label>
        <select name="gender" >
            <option value="male" <?php echo ($userData['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($userData['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
        </select>
        
        <label>Birthday:</label>
        <input type="date" name="dob" value="<?php echo htmlspecialchars($userData['dob']); ?>" >
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" >
        
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" >
        
        <label>Verification Code:</label>
        <input type="text" name="verification_code" value="<?php echo htmlspecialchars($userData['verification_code']); ?>"
        
        <label>Booking Area:</label>
        <input type="text" name="booking_area" value="<?php echo htmlspecialchars($userData['booking_area']); ?>" 
        
        <label>Destination:</label>
        <input type="text" name="destination" value="<?php echo htmlspecialchars($userData['destination']); ?>">
        
        <label>Trip Date & Time:</label>
        <input type="datetime-local" name="trip_date_time" value="<?php echo htmlspecialchars($userData['trip_date_time']); ?>" >
        
        <label>Return Date & Time:</label>
        <input type="datetime-local" name="return_date_time" value="<?php echo htmlspecialchars($userData['return_date_time']); ?>" >
        
        <label>Vehicle Type:</label>
        <input type="text" name="vehicle_type" value="<?php echo htmlspecialchars($userData['vehicle_type']); ?>" >
        
        <label>Rent Status:</label>
        <select name="rent_status" required>
            <option value="rented" <?php echo ($userData['rent_status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
            <option value="available" <?php echo ($userData['rent_status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
        </select>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>