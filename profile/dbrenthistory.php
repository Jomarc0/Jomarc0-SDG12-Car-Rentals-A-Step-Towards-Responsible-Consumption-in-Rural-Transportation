<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class RentalHistory {
    private $conn;
    private $cars = []; //set the array to null
    private $vehicleImages = [ //assosiative array for pics
        'Sedan' => '../pictures/sedan.jpg',
        'Fullsize SUV' => '../pictures/full size suv.jpg', // Updated to include Fullsize SUV
        'Midsize SUV' => '../pictures/mid size suv.png', // Updated to include Midsize SUV
        'Pick Up' => '../pictures/pick up.png', // Updated to include Pick Up
        'Subcompact Sedan' => '../pictures/subcompact.jpg', // Updated to include Subcompact Sedan
        'Van' => '../pictures/VAN.jpg', // Updated to include Van
        'Sports Car' => '../pictures/Sports Car.jpg' // Updated to include Sports Car
    ];

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
        $this->fetchRentedCars();
    }

    private function fetchRentedCars() {
        $stmt = $this->conn->prepare("SELECT * FROM rentedcar WHERE rent_status = 'completed'"); //select all the rent status equal to completed 
        $stmt->execute();
        $this->cars = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch as associative array
    }

    public function getCars() { //getter for car
        return $this->cars;
    }

    public function getVehicleImage($vehicleType) { //getter for the image
        return isset($this->vehicleImages[$vehicleType]) ? $this->vehicleImages[$vehicleType] : 'No image available';
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>