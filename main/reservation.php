<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'CarSystem.php'; 
require_once '../profile/UserProfile.php';

try {
    $database = new Database();
    $conn = $database->getConn(); 
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

$userAccount = new UserAccount();

if (!$userAccount->isVerified()) {//if the user id is verified
    header("Location: ../profile/profile.php"); // back to profile page if not verified
    exit;
}

$carRentalSystem = new CarRentalSystem($conn); // call CarRentalSystem class

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-btn'])) { // check if the form is submitted
    $carRentalSystem->handleReservationSubmission();
}
$selectedBookingArea = $_POST['bookingArea'] ?? ''; // selected booking area from POST or default to empty string

$destinations = [// destinations based on booking area
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
    <link rel="stylesheet" href="../css/reservation.css">
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
                    <p>Select Location </p>
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
                        <option value="Tesla">Tesla</option>
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