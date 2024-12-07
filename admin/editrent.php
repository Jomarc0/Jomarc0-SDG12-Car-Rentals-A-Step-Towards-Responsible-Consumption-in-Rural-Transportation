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
    <link rel="stylesheet" href="../css/edittable.css">
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