<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class CarRental {
    private $conn;
    private $cars = [];
    private $vehicleImages = [
        'Sedan' => '../main/pictures/sedan.jpg',
        'SUV' => '../main/pictures/suv.jpg',
        'Truck' => '../main/pictures/truck.jpg',
        // Add more vehicle types and their corresponding images here
    ];

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnec(); // Get database connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
        $this->fetchRentedCars();
    }

    private function fetchRentedCars() {
        $query = "SELECT * FROM rentedcar WHERE rent_status = 'completed'";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->cars[] = $row; // Store fetched data
            }
        }
    }

    public function getCars() {
        return $this->cars;
    }

    public function getVehicleImage($vehicleType) {
        return isset($this->vehicleImages[$vehicleType]) ? $this->vehicleImages[$vehicleType] : 'No image available';
    }

    public function __destruct() {
        // Close the database connection
        $this->conn->close();
    }
}

// Instantiate the CarRental class
$carRental = new CarRental();
$cars = $carRental->getCars();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented Cars</title>
    <link rel="stylesheet" href="../header/header.css">
    <link rel="stylesheet" href="../main/vehicle.css">
    <link rel="stylesheet" href="profile.css">
    <style>
        .car-card {
            position: relative; /* Make the container relative for absolute positioning of the button */
        }
        .cancel-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .cancel-button:hover {
            background-color: darkred; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <header class="header">
        <?php include('../header/header.php'); ?>  
    </header>
    <?php include('sidebar.php'); ?>
    <div class="container">
        <div class="main-content" id="main-content">
            <div class="car-container">
                <?php foreach ($cars as $car): ?>
                    <div class="car-card">
                        <form action="../main/cancel.php" method="post"> <!-- Change to the actual path of your cancel script -->
                            <input type="hidden" name="rent_id" value="<?php echo $car['rent_id']; ?>">
                            <button type="submit" class="cancel-button" name="cancel-btn">X</button>
                        </form>
                        <?php
                        // Determine the image based on vehicle type
                        $imagePath = $carRental->getVehicleImage($car['vehicle_type']);
                        // Calculate remaining time until return
                        $returnDateTime = new DateTime($car['return_date_time']);
                        $currentDateTime = new DateTime();
                        $interval = $currentDateTime->diff($returnDateTime);
                        ?>
                        <img class="car-image" src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($car['vehicle_type']); ?>">
                        <div class="car-info">
                            <h2 class="car-title"><?php echo htmlspecialchars($car['vehicle_type']); ?></h2>
                            <span>Booking Area: <?php echo htmlspecialchars($car['booking_area']); ?></span><br>
                            <span>Destination: <?php echo htmlspecialchars($car['destination']); ?></span><br>
                            <span>Trip Date & Time: <?php echo htmlspecialchars($car['trip_date_time']); ?></span><br>
                            <span>Return Date & Time: <?php echo htmlspecialchars($car['return_date_time']); ?></span><br>
                            <span>Rent Status: <?php echo htmlspecialchars($car[' rent_status']); ?></span><br>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="../main/script.js"></script>
</body>
</html>