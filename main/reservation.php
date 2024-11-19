<?php
// Start the session
session_start();

// Include the database connection and the ReservationHandler class
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once __DIR__ . '/ReservationHandler.php';

try {
    $database = new Database();
    $conn = $database->getConn(); // Get the database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// Create an instance of ReservationHandler
$reservationHandler = new ReservationHandler($conn);

// Initialize selected booking area
$selectedBookingArea = $_POST['bookingArea'] ?? ''; // Get the selected booking area from POST or default to empty string

// Define the destinations based on booking area
$destinations = [
    'Metro Manila' => ['Makati', 'Taguig', 'Quezon City', 'Manila'],
    'Cebu' => ['Cebu City', 'Mactan', 'Lapu-Lapu'],
    'Davao' => ['Davao City', 'Tagum', 'Digos']
];

// Get the destinations based on the selected booking area
$destinationOptions = $selectedBookingArea && isset($destinations[$selectedBookingArea]) ? $destinations[$selectedBookingArea] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Car Rent</title>
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <?php include('../header/header.php'); ?>
    <div class="container">
        <section class="form-section">
            <h1>Reservation Form</h1>

            <!-- Progress bar -->
            <div class="form-progress">
                <div class="step active">
                    <span>1</span>
                    <p>Select Location</p>
                </div>
                <div class="step">
                    <span>2</span>
                    <p>Trip Details</p>
                </div>
                <div class="step">
                    <span>3</span>
                    <p>Service Options</p>
                </div>
                <div class="step">
                    <span>4</span>
                    <p>Receipt</p>
                </div>
            </div>

            <!-- Form -->
            <form method="post" action="">
                <!-- Step 1: Select Location -->
                <div class="form-step active">
                    <select id="bookingArea" name="bookingArea" required onchange="this.form.submit()">
                        <option value="">Select Area</option>
                        <option value="Metro Manila" <?php if ($selectedBookingArea === 'Metro Manila') echo 'selected'; ?>>Metro Manila</option>
                        <option value="Cebu" <?php if ($selectedBookingArea === 'Cebu') echo 'selected'; ?>>Cebu</option>
                        <option value="Davao" <?php if ($selectedBookingArea === 'Davao') echo 'selected'; ?>>Davao</option>
                    </select>
                    <div class="button-container">
                        <button type="button" class="next-btn">Next</button>
                    </div>
                </div>
                
                <!-- Trip Details -->
                <div class="form-step">
                    <h2>Trip Details</h2>
                    <label for="destination">Destination </label>
                    <select id="destination" name="destination" required>
                        <option value="">Select Destination</option>
                        <?php
                        // Show destinations based on the selected booking area
                        if ($selectedBookingArea && !empty($destinationOptions)) {
                            foreach ($destinationOptions as $destination) {
                                echo "<option value=\"$destination\">$destination</option>";
                            }
                        }
                        ?>
                    </select>
                    <label for="tripDateTime">Trip Date and Time </label>
                    <input type="datetime-local" id="tripDateTime" name="tripDateTime" required>
                    <label for="returnDateTime">Return Date and Time</label>
                    <input type="datetime-local" id="returnDateTime" name="returnDateTime">
                    <div class="button-container">
                        <button type="button" class="prev-btn">Previous</button>
                        <button type="button" class="next-btn">Next</button>
                    </div>
                </div>
                
                <!-- Service Options -->
                <div class="form-step">
                    <h2>Service Options</h2>
                    <label for="vehicleType">Vehicle Type </label>
                    <select id="vehicleType" name="vehicleType" required>
                        <option value="">Select Vehicle Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">Fullsize SUV</option>
                        <option value="Midsize SUV">Midsize SUV</option>
                        <option value="Pick Up">Pick Up</option>
                        <option value="Subcompact Sedan">Subcompact Sedan</option>
                        <option value="Van">Van</option>
                        <option value="Sports Car">Sports Car</option>
                    </select>
                    <div class="button-container">
                        <button type="button" class="prev-btn">Previous</button>
                        <button type="submit" class="submit-btn" name="submit-btn">Submit</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
