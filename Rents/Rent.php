<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class Rental {
    private $conn;
    private $rentedCars = []; // Array for rented cars
    private $rentalHistory = []; // Array for completed rentals
    private $vehicleImages = [ // Associative array for vehicle images
        'Sedan' => '../pictures/sedan.jpg',
        'Fullsize SUV' => '../pictures/full size suv.jpg',
        'Midsize SUV' => '../pictures/mid size suv.png',
        'Pick Up' => '../pictures/pick up.png',
        'Subcompact Sedan' => '../pictures/subcompact.jpg',
        'Van' => '../pictures/VAN.jpg',
        'Sports Car' => '../pictures/Sports Car.jpg',
        'Tesla' => '../pictures/tesla.pmg'
    ];

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
        $this->fetchRentedCars();
        $this->fetchRentalHistory();
    }

    private function fetchRentedCars() {
        $query = "SELECT * FROM rentedcar WHERE rent_status = 'rented'"; // get all rented cars
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->rentedCars = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch as associative array

        $this->updateCarStatus(); // updatestatus if necessary
    }

    private function updateCarStatus() {
        $currentDateTime = new DateTime(); // get the current time
        foreach ($this->rentedCars as $car) {
            $returnDateTime = new DateTime($car['return_date_time']);
            if ($returnDateTime <= $currentDateTime) {
                $updateQuery = "UPDATE rentedcar SET rent_status = 'completed' WHERE rent_id = :rent_id"; // update status to completed
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute([':rent_id' => $car['rent_id']]); // update the rent status
            }
        }
    }

    private function fetchRentalHistory() {
        $stmt = $this->conn->prepare("SELECT * FROM rentedcar WHERE rent_status = 'completed'"); // fetch completed rentals
        $stmt->execute();
        $this->rentalHistory = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch as associative array
    }

    public function getRentedCars() { // getter for rented cars
        return $this->rentedCars;
    }

    public function getRentalHistory() { // getter for rental history
        return $this->rentalHistory;
    }

    public function getVehicleImage($vehicleType) { // getter for vehicle images
        return isset($this->vehicleImages[$vehicleType]) ? $this->vehicleImages[$vehicleType] : 'No image available';
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>