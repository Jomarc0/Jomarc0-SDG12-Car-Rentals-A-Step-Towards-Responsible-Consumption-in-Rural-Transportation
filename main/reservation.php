<?php
session_start();

require_once __DIR__ . '/../dbcon/dbcon.php';
require_once __DIR__ . '/reservationhandler.php';
require_once __DIR__ . '/dbreservation.php'; 

try {
    $database = new Database();
    $conn = $database->getConn(); 
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

$reservationHandler = new ReservationHandler($conn); // call reservationhandler class

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-btn'])) { // check if the form is submitted
    $reservationHandler->handleReservationSubmission();
}

// initialize selected booking area
$selectedBookingArea = $_POST['bookingArea'] ?? ''; //selected booking area from POST or default to empty string

// destinations based on booking area
$destinations = [
    'Cavite' => ['Tagaytay City', 'Corregidor Island', 'Aguinaldo Shrine', 'Mt. Pico De Loro', 'Kaybiang Tunnel', 'Balite Falls (Amadeo)', 'Other'],
    'Laguna' => ['Pagsanjan Fall', 'Enchanted Kingdom', 'Rizal Shrine in Calamba','Los Baños Hot Springs','Seven Lakes of San Pablo', 'Hidden Valley Springs (Calauan)', 'Hulugan Falls', 'Other'],
    'Batangas' => ['Taal Volcano', 'Anilao', 'Laiya Beach','Taal Heritage Town', 'Masasa Beach', 'Calatagan Sandbar', 'Fortune Island', 'Cape Santiago Lighthouse', 'Other'],
    'Rizal' => ['Antipolo Cathedral', 'Hinulugang Taktak Falls', 'Art Sector Gallery','Mount Daraitan and Tinipak River (Tanay)', 'Masungi Georeserve', 'Daranak Falls (Tanay)', 'Pililla Wind Farm' , 'Other'],
    'Quezon' => ['Borawan Island (Padre Burgos)', 'Alibijaban Island (San Andres)', 'Kwebang Lampas and Puting Buhangin (Pagbilao)','Balesin Island (Polillo Group)', 'Jomalig Island', 'Lucban', 'Mt. Banahaw', 'Cagbalete Island', 'Other']
];

// get the destinations based on the selected booking area
$destinationOptions = $selectedBookingArea && isset($destinations[$selectedBookingArea]) ? $destinations[$selectedBookingArea] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Car Rent</title>
    <style>
     body {
    font-family: 'Arial', sans-serif; /* Modern font */
    margin: 0;
    padding: 0;
    background-color: #121212; /* Dark background for the body */
    color: #ffffff; /* White text for better contrast */
}

.container {
    background-color: #1c1c1c; /* Dark background for the container */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
}

.form-section {
    background-color: #2c2c2c; /* Darker background for form section */
    padding: 60px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Deeper shadow for depth */
    width: 800px;
    text-align: center;
    transition: transform 0.3s ease; /* Smooth transition for hover effect */
}

.form-section:hover {
    transform: translateY(-5px); /* Lift effect on hover */
}

.form-section h1 {
    font-size: 24px;
    margin-bottom: 40px;
    color: #f7b531; /* Highlight color for the heading */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Subtle shadow for text */
}

/* Progress bar */
.form-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
}

.form-progress .step {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-progress .step span {
    width: 50px; /* Increased size for better visibility */
    height: 50px; /* Increased size for better visibility */
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background-color: #4F5576; /* Background color for steps */
    color: white;
    font-weight: bold;
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.form-progress .step.active span {
    background-color: #f7b531; /* Change active step color */
}

.form-progress .step p {
    margin-top: 10px;
    font-size: 14px;
    color: #888; /* Color for step description */
}

/* Form Steps */
.form-step {
    display: none;
    flex-direction: column;
}

.form-step.active {
    display: flex;
}

label {
    font-size: 18px;
    text-align: left;
    margin-bottom: 10px;
    color: #f7b531; /* Highlight color for labels */
}

select {
    padding: 12px; /* Increased padding for better usability */
    font-size: 16px;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 5px;
    border: 1px solid #4F5576; /* Lighter border color */
    transition: border-color 0.3s; /* Smooth transition for focus */
    background-color: #2c2c2c; /* Dark background for select */
    color: #ffffff; /* White text for select */
}

select:focus {
    border-color: #f7b531; /* Change border color on focus */
    outline: none; /* Remove default outline */
}

input {
    font-size: 18px;
    text-align: left;
    margin-bottom: 20px; /* Increased margin for spacing */
    border: 1px solid #4F5576; /* Lighter border color */
    border-radius: 5px; /* Added border radius for input fields */
    padding: 12px; /* Increased padding for better usability */
    transition: border-color 0.3s; /* Smooth transition for focus */
    background-color: #2c2c2c; /* Dark background for input */
    color: #ffffff; /* White text for input */
}

input:focus {
    border-color: #f7b531; /* Change border color on focus */
    outline: none; /* Remove default outline */
}

button {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: #f7b531; /* Updated button background color */
    color: #181b26; /* Dark text color*/
    border: none;
    border-radius: 5px;
    width: 70%;
    transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
}

button:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

button.prev-btn {
    background-color: #4F5576; /* Updated previous button color */
}

button.prev-btn:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

button[type="submit"] {
    background-color: #f7b531; /* Updated submit button color */
}

button[type="submit"]:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

.button-container {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically (if needed) */
    margin-top: 20px; /* Increased margin for better spacing */
}

.button-container button {
    margin: 0 10px; /* Add spacing between buttons */
}

     </style>
</head>
<body>
    <?php include('../header/header.php'); ?>
    <div class="container">
        <section class="form-section">
            <h1>Reservation Form</h1>

            <!-- Display session message if available -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

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

            <form method="post" action="">
                <!-- Step 1: Select Location -->
                <div class="form-step active">
                    <select id="bookingArea" name="bookingArea" required onchange="this.form.submit()">
                        <option value="">Select Area</option>
                        <option value="Cavite" <?php if ($selectedBookingArea === 'Cavite') echo 'selected'; ?>>Cavite</option>
                        <option value="Laguna" <?php if ($selectedBookingArea === 'Laguna') echo 'selected'; ?>>Laguna</option>
                        <option value="Batangas" <?php if ($selectedBookingArea === 'Batangas') echo 'selected'; ?>>Batangas</option>
                        <option value="Rizal" <?php if ($selectedBookingArea === 'Rizal') echo 'selected'; ?>>Rizal</option>
                        <option value="Quezon" <?php if ($selectedBookingArea === 'Quezon') echo 'selected'; ?>>Quezon</option>
                    </select>
                    <div class="button-container">
                        <button type="button" class="next-btn">Next</button>
                    </div>
                </div>
                
                <!-- Step 2: Trip Details -->
                <div class="form-step">
                    <h2>Trip Details</h2>
                    <label for="destination">Destination </label>
                    <select id="destination" name="destination" required>
                        <option value="">Select Destination</option>
                        <?php
                        if ($selectedBookingArea && !empty($destinationOptions)) { // show destinations based on the selected booking area
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
                
                <!-- Step 3: Service Options -->
                <div class="form-step">
                    <h2>Service Options</h2>
                    <label for="vehicleType">Vehicle Type </label>
                    <select id="vehicleType" name="vehicleType" required>
                        <option value="">Select Vehicle Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="Fullsize SUV">Fullsize SUV</option>
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
    <?php require ('../chatbox/clientmessage.php'); //include the chat ?> 
    <script src="../js/script.js"></script>
</body>
</html>