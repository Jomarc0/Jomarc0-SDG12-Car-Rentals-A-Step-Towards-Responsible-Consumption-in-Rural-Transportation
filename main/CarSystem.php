<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class CarRentalSystem {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function submitReservation($data, $userId) {
        $reservation = new Reservation($this->conn);
        return $reservation->submitReservation($data, $userId);
    }

    public function calculateRentalPrice($rentId) {
        $rentalCalculator = new RentalCalculator($this->conn);
        $rentalCalculator->setBookingId($rentId);
        return $rentalCalculator->calculateRentalPrice();
    }

    public function generateReceipt() {
        $receipt = new Receipt($this->conn);
        return $receipt->generate();
    }

    public function handleReservationSubmission() {
        $selectedBookingArea = $_POST['bookingArea'] ?? '';
        $selectedDestination = $_POST['destination'] ?? '';

        $data = [
            'bookingArea' => trim($selectedBookingArea),
            'destination' => trim($selectedDestination),
            'tripDateTime' => trim($_POST['tripDateTime']),
            'returnDateTime' => trim($_POST['returnDateTime']),
            'vehicleType' => trim($_POST['vehicleType']),
        ];

        foreach ($data as $value) {
            if (empty($value)) {
                $_SESSION['message'] = "All fields are required.";
                header('Location: reservation.php');
                exit;
            }
        }

        $userId = $this->getUserId();
        if (!$userId) {
            $_SESSION['message'] = "User  is not logged in.";
            header('Location: login.php');
            exit;
        }

        try {
            $resultMessage = $this->submitReservation($data, $userId);
            $_SESSION['message'] = $resultMessage;
        } catch (Exception $e) {
            $_SESSION['message'] = "An error occurred: " . $e->getMessage();
        }

        header('Location: receipt.php');
        exit;
    }

    private function getUserId() {
        $email = $_SESSION['email'] ?? null;

        if ($email) {
            $query = "SELECT user_id FROM user WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user['user_id'] : null;
        }

        return null;
    }
}

class Reservation {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function submitReservation($data, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO rentedcar (booking_area, destination, trip_date_time, return_date_time, vehicle_type, user_id) 
                VALUES (:booking_area, :destination, :trip_date_time, :return_date_time, :vehicle_type, :user_id)");

        $stmt->bindParam(':booking_area', $data['bookingArea']);
        $stmt->bindParam(':destination', $data['destination']);
        $stmt->bindParam(':trip_date_time', $data['tripDateTime']);
        $stmt->bindParam(':return_date_time', $data['returnDateTime']);
        $stmt->bindParam(':vehicle_type', $data['vehicleType']);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            return "Reservation request submitted successfully!";
        } else {
            throw new Exception("Error: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

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

    public function calculateRentalPrice() {
        $sql = "SELECT trip_date_time, return_date_time, vehicle_type FROM rentedcar WHERE rent_id = :rent_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rent_id', $this->rentId);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) { 
            return "Booking not found.";
        }

        $tripDetails = $stmt->fetch(PDO::FETCH_ASSOC); 
        $this->tripDate = $tripDetails['trip_date_time'];
        $this->returnDate = $tripDetails['return_date_time'];
        $this->vehicleType = $tripDetails['vehicle_type']; 

        if (empty($this->tripDate) || empty($this->returnDate) || empty($this->vehicleType)) { 
            return "Trip details are incomplete.";
        }

        $startDate = new DateTime($this->tripDate);
        $endDate = new DateTime($this->returnDate);
        if ($startDate >= $endDate) {
            return "Return date must be after trip date.";
        }

        $interval = $startDate->diff($endDate);
        $totalDays = $interval->days;
        $totalHours = $interval->h + ($totalDays * 24); 
        
        $sql = "SELECT hourly_rate, daily_rate FROM vehicle_rate WHERE vehicle_type = :vehicle_type"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vehicle_type', $this->vehicleType); 
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return "Vehicle type not found.";
        }

        $pricing = $stmt->fetch(PDO::FETCH_ASSOC);
        $hourlyRate = $pricing['hourly_rate'];
        $dailyRate = $pricing['daily_rate'];

        $totalPrice = 0.0;
        if ($totalDays > 0) {
            $totalPrice = ($totalDays * $dailyRate);
            if ($interval->h > 0) {
                $totalPrice += ($interval->h * $hourlyRate);
            }
        } else {
            $totalPrice = $totalHours * $hourlyRate;
        }
        return (float) number_format($totalPrice, 2, '.', '');
    }
}

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

        return $output; 
    }

    private function fetchLatestReceiptData() {
        $stmt = $this->conn->prepare("SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_id FROM rentedcar ORDER BY rent_id DESC LIMIT 1"); 
        
        if (!$stmt) { 
            throw new Exception("Error preparing query: " . implode(":", $this->conn->errorInfo()));
        }
        if (!$stmt->execute()) { 
            throw new Exception("Error executing query: " . implode(":", $stmt->errorInfo()));
        }

        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    private function displayReceipt($receipt) {
        $html = "<p><strong>Booking Area: </strong><span style='margin-left: 110px;'>" . htmlspecialchars($receipt['booking_area']) . "</span></p>";
        $html .= "<p><strong>Destination:</strong><span style='margin-left: 132px;'>" . htmlspecialchars($receipt['destination']) . "</span></p>";
        $html .= "<p><strong>Trip Date and Time:</strong><span style='margin-left: 75px;'>" . htmlspecialchars($receipt['trip_date_time']) . "</span></p>";
        $html .= "<p><strong>Return Date and Time: </strong><span style='margin-left: 50px;'>" . htmlspecialchars($receipt['return_date_time']) . "</span></p>";
        $html .= "<p><strong>Vehicle Type: </strong><span style='margin-left: 120px;'>" . htmlspecialchars($receipt['vehicle_type']) . "</span></p>";
        return $html; 
    }

    private function calculatePrice($rentId) {
        $rentalCalculator = new RentalCalculator($this->conn); 
        $rentalCalculator->setBookingId($rentId); 
        $totalPrice = $rentalCalculator->calculateRentalPrice();

        if (is_numeric($totalPrice)) {
            return "<p><strong>Total Rental Price:</strong> <span style='margin-left: 80px;'> ₱" . number_format($totalPrice, 2) . "</span></p>";
        } else {
            return "<p>Error calculating price: " . htmlspecialchars($totalPrice) . "</p>"; 
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
            return $stmt->fetchColumn(); 
        } else {
            return null; 
        }
    }
}
?>