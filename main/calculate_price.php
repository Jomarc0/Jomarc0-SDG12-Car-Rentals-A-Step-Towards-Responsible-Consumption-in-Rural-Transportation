<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class RentalCalculator {
    private $conn;
    private $bookingId; // Booking ID to fetch trip details
    public $tripDate;
    public $returnDate;
    public $vehicleType;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function setBookingId($bookingId) {
        $this->bookingId = $bookingId;
    }

    public function calculateRentalPrice(): float {
        // Fetch trip details from the database
        $sql = "SELECT trip_date_time, return_date_time, vehicle_type FROM rentedcar WHERE rent_id = :rent_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rent_id', $this->bookingId);
        $stmt->execute();
        
        // Check if any results were returned
        if ($stmt->rowCount() === 0) {
            return "Booking not found.";
        }

        $tripDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->tripDate = $tripDetails['trip_date_time'];
        $this->returnDate = $tripDetails['return_date_time'];
        $this->vehicleType = $tripDetails['vehicle_type'];

        // Ensure the variables are defined
        if (empty($this->tripDate) || empty($this->returnDate) || empty($this->vehicleType)) {
            return "Trip details are incomplete.";
        }

        // Validate dates
        $startDate = new DateTime($this->tripDate);
        $endDate = new DateTime($this->returnDate);
        if ($startDate >= $endDate) {
            return "Return date must be after trip date.";
        }

        // Calculate the difference
        $interval = $startDate->diff($endDate);
        
        // Get total days and hours
        $totalDays = $interval->days;
        $totalHours = $interval->h + ($totalDays * 24); // Convert days to hours
        
        // Fetch pricing from the database
        $sql = "SELECT hourly_rate, daily_rate FROM vehicle_rate WHERE vehicle_type = :vehicle_type";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vehicle_type', $this->vehicleType);
        $stmt->execute();

        // Check if any results were returned
        if ($stmt->rowCount() === 0) {
            return "Vehicle type not found.";
        }

        $pricing = $stmt->fetch(PDO::FETCH_ASSOC);
        $hourlyRate = $pricing['hourly_rate'];
        $dailyRate = $pricing['daily_rate'];

        // Calculate total price
        $totalPrice = 0.0;
        if ($totalDays > 0) {
            // If the rental period is more than a day, use daily rate
            $totalPrice = ($totalDays * $dailyRate);
            // Add any additional hours beyond full days
            if ($interval->h > 0) {
                $totalPrice += ($interval->h * $hourlyRate);
            }
        } else {
            // If less than a day, charge hourly
            $totalPrice = $totalHours * $hourlyRate;
        }

        // Return the formatted total price
        return (float) number_format($totalPrice, 2, '.', '');
    }
}
?>