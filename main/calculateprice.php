<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class RentalCalculator {
    private $conn;
    private $rentId; 
    public $tripDate;
    public $returnDate;
    public $vehicleType;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function setBookingId($rentId) {
        $this->rentId = $rentId;
    }

    public function calculateRentalPrice(): float { //make this class as float kasi calculation of the price
        // Fetch trip details from the database
        $sql = "SELECT trip_date_time, return_date_time, vehicle_type FROM rentedcar WHERE rent_id = :rent_id"; //retrive all the data in rented car
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rent_id', $this->rentId); //to prevent SQL Injection
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) { //check if the statement is not equal to 1 and return false
            return "Booking not found.";
        }

        $tripDetails = $stmt->fetch(PDO::FETCH_ASSOC); // fetch as an assosiatiove array
        $this->tripDate = $tripDetails['trip_date_time'];
        $this->returnDate = $tripDetails['return_date_time'];
        $this->vehicleType = $tripDetails['vehicle_type']; //thse 3 data are fetch from the database

        if (empty($this->tripDate) || empty($this->returnDate) || empty($this->vehicleType)) { //ensure that they are not empty
            return "Trip details are incomplete.";
        }

        //to check if the start date is greater then end 
        $startDate = new DateTime($this->tripDate);
        $endDate = new DateTime($this->returnDate);
        if ($startDate >= $endDate) {
            return "Return date must be after trip date.";
        }

        // calculate the difference of start and end date
        $interval = $startDate->diff($endDate);
        
        //total days and hours
        $totalDays = $interval->days;
        $totalHours = $interval->h + ($totalDays * 24); // convert days to hours
        
        // Fetch pricing from the database
        $sql = "SELECT hourly_rate, daily_rate FROM vehicle_rate WHERE vehicle_type = :vehicle_type"; //rate of the per vehicles
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vehicle_type', $this->vehicleType); // to prevent SQL injection
        $stmt->execute();

        //check if the statement is not equal to 1 and return false
        if ($stmt->rowCount() === 0) {
            return "Vehicle type not found.";
        }

        $pricing = $stmt->fetch(PDO::FETCH_ASSOC);//fetch as an assosiative array the hour and daily price from rates
        $hourlyRate = $pricing['hourly_rate'];
        $dailyRate = $pricing['daily_rate'];

        // Calculate price
        $totalPrice = 0.0;
        if ($totalDays > 0) {
            // if the rental period is more than a day, use daily rate
            $totalPrice = ($totalDays * $dailyRate);
            // add any additional hours beyond full days
            if ($interval->h > 0) {
                $totalPrice += ($interval->h * $hourlyRate);
            }
        } else {
            // if less than a day, charge hourly
            $totalPrice = $totalHours * $hourlyRate;
        }
        //return as number format float by 2
        return (float) number_format($totalPrice, 2, '.', '');
    }
}
?>