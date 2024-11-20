<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
class AdminDashboard {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // get db connection
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM user"; // Replace 'user' with your actual users table name
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalRentedCars() {
        $query = "SELECT COUNT(*) as total FROM rentedcar WHERE rent_status = 'rented'"; // Replace 'rentedcar' with your actual rented cars table name
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPendingRequests() {
        $query = "SELECT COUNT(*) as total FROM payment WHERE payment_status = 'pending'"; // Replace 'payment' with your actual payment table name
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getRentedCars() {
        $query = "SELECT user_id, rent_id, booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status FROM rentedcar"; // Adjust the table name as needed
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getVerifiedUsers() {
        $query = "SELECT user_id, first_name, last_name, address, gender, dob, email, phone_number, verification_code FROM user WHERE verified = 1"; // Adjust the table name as needed
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentActivity() {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.address, u.gender, u.dob, u.email, u.phone_number, 
                        r.booking_area, r.destination, r.trip_date_time, r.return_date_time, 
                        r.vehicle_type
                    FROM user AS u 
                    JOIN rentedcar AS r ON u.user_id = r.user_id"; // Adjust the table name as needed
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>