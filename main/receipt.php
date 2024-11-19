<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'calculate_price.php'; // Ensure correct path

class Receipt {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function generate() {
        $receiptData = $this->fetchLatestReceiptData();

        if ($receiptData) {
            $output = $this->displayReceipt($receiptData);
            $output .= $this->calculatePrice($receiptData['rent_id']);
        } else {
            $output = "No reservations found.";
        }

        return $output; // Return the generated HTML
    }

    private function fetchLatestReceiptData() {
        $sql = "SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_id FROM rentedcar ORDER BY rent_id DESC LIMIT 1";    
        $stmt = $this->conn->prepare($sql); 
        if (!$stmt) {
            throw new Exception("Error preparing query: " . implode(":", $this->conn->errorInfo()));
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . implode(":", $stmt->errorInfo()));
        }

        return $stmt->fetch(PDO::FETCH_ASSOC); // Return associative array of the latest receipt
    }

    private function displayReceipt($receipt) {
        $html = "<p><strong>Booking Area: </strong><span style='margin-left: 110px;'>" . htmlspecialchars($receipt['booking_area']) . "</span></p>";
        $html .= "<p><strong>Destination:</strong><span style='margin-left: 132px;'>" . htmlspecialchars($receipt['destination']) . "</span></p>";
        $html .= "<p><strong>Trip Date and Time:</strong><span style='margin-left: 75px;'>" . htmlspecialchars($receipt['trip_date_time']) . "</span></p>";
        $html .= "<p><strong>Return Date and Time: </strong><span style='margin-left: 50px;'>" . htmlspecialchars($receipt['return_date_time']) . "</span></p>";
        $html .= "<p><strong>Vehicle Type: </strong><span style='margin-left: 120px;'>" . htmlspecialchars($receipt['vehicle_type']) . "</span></p>";
        return $html; // Return the HTML string
    }

    private function calculatePrice($bookingId) {
        $rentalCalculator = new RentalCalculator($this->conn);
        $rentalCalculator->setBookingId($bookingId); // Set the booking ID for the calculator
        $totalPrice = $rentalCalculator->calculateRentalPrice();

        if (is_numeric($totalPrice)) {
            return "<p><strong>Total Rental Price:</strong> <span style='margin-left: 80px;'> ₱" . number_format($totalPrice, 2) . "</span></p>";
        } else {
            return "<p>Error calculating price: " . htmlspecialchars($totalPrice) . "</p>";
        }
    }

    public function getRentId() {
        $sql = "SELECT rent_id FROM rentedcar ORDER BY rent_id DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing query: " . implode(":", $this->conn->errorInfo()));
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . implode(":", $stmt->errorInfo()));
        }

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchColumn(); // Return the latest rent_id
        } else {
            return null; // No reservations found
        }
    }
}
?>