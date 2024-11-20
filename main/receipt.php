<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'calculateprice.php'; // require the calculate price

class Receipt {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function generate() {
        $receiptData = $this->fetchLatestReceiptData();

        if ($receiptData) {
            $output = $this->displayReceipt($receiptData); //output are don sa genrate
            $output .= $this->calculatePrice($receiptData['rent_id']);
        } else {
            $output = "No reservations found.";
        }

        return $output; // Return the generated HTML
    }

    private function fetchLatestReceiptData() {
        $stmt = $this->conn->prepare("SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_id FROM rentedcar ORDER BY rent_id DESC LIMIT 1"); //sql statement i used order by rent id and DESC LIMIT 1 to alway select the last input data 
        
        if (!$stmt) { //if statement is error
            throw new Exception("Error preparing query: " . implode(":", $this->conn->errorInfo()));
        }
        if (!$stmt->execute()) { // if statment did not execute
            throw new Exception("Error executing query: " . implode(":", $stmt->errorInfo()));
        }

        return $stmt->fetch(PDO::FETCH_ASSOC); // fetsch and return as an associative array 
    }

    private function displayReceipt($receipt) {
        $html = "<p><strong>Booking Area: </strong><span style='margin-left: 110px;'>" . htmlspecialchars($receipt['booking_area']) . "</span></p>";
        $html .= "<p><strong>Destination:</strong><span style='margin-left: 132px;'>" . htmlspecialchars($receipt['destination']) . "</span></p>";
        $html .= "<p><strong>Trip Date and Time:</strong><span style='margin-left: 75px;'>" . htmlspecialchars($receipt['trip_date_time']) . "</span></p>";
        $html .= "<p><strong>Return Date and Time: </strong><span style='margin-left: 50px;'>" . htmlspecialchars($receipt['return_date_time']) . "</span></p>";
        $html .= "<p><strong>Vehicle Type: </strong><span style='margin-left: 120px;'>" . htmlspecialchars($receipt['vehicle_type']) . "</span></p>";
        return $html; // return to html string
    }

    private function calculatePrice($rentId) {
        $rentalCalculator = new RentalCalculator($this->conn); //rentelcalculator class from calulateprice.php
        $rentalCalculator->setBookingId($rentId); // sett the rent ID for the calculator
        $totalPrice = $rentalCalculator->calculateRentalPrice();

        if (is_numeric($totalPrice)) {
            return "<p><strong>Total Rental Price:</strong> <span style='margin-left: 80px;'> ₱" . number_format($totalPrice, 2) . "</span></p>";
        } else {
            return "<p>Error calculating price: " . htmlspecialchars($totalPrice) . "</p>"; //if error
        }
    }

    public function getRentId() {
        $stmt = $this->conn->prepare("SELECT rent_id FROM rentedcar ORDER BY rent_id DESC LIMIT 1"); 

        if (!$stmt) {
            throw new Exception("Error preparing query: " . implode(":", $this->conn->errorInfo()));
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . implode(":", $stmt->errorInfo()));
        }

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchColumn(); // return the latest rent id
        } else {
            return null; //if no reservations found return null
        }
    }
}
?>