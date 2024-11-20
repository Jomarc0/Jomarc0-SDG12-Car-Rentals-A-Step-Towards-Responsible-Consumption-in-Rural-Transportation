<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class RentedCars {
    private $conn;
    private $cars = []; //null array 
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
        $query = "SELECT * FROM rentedcar WHERE rent_status = 'rented'"; //fetch all the rent status equal to rented
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->cars = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch as an assosiative array

        $this->updateCarStatus();
    }

    private function updateCarStatus() {
        $currentDateTime = new DateTime(); //get the current time
        foreach ($this->cars as $car) {  //this foreach use to output if the current time is less rhe return time
            $returnDateTime = new DateTime($car['return_date_time']);
            if ($returnDateTime <= $currentDateTime) {

                $updateQuery = "UPDATE rentedcar SET rent_status = 'completed' WHERE rent_id = :rent_id"; //update if the return time is less than current time to completed and put in the rent history
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute([':rent_id' => $car['rent_id']]); //this $car hold the fetch assosiative array from db
            }
        }
    }

    public function getCars() { //getter for cars
        return $this->cars;
    }

    public function getVehicleImage($vehicleType) { //getter for image
        return isset($this->vehicleImages[$vehicleType]) ? $this->vehicleImages[$vehicleType] : 'No image available';
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>