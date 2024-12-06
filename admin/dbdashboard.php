<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once '../Mailer/AdminMailer.php';
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

    public function getTotalUsers() { //total number of user 
        $query = "SELECT COUNT(*) as total FROM user"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalRentedCars() { //get all the rented cars or in use car
        $query = "SELECT COUNT(*) as total FROM rentedcar WHERE rent_status = 'rented'"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRentedCars() { //all the rent cars from the clients 
        $query = "SELECT user_id, rent_id, booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status FROM rentedcar"; // Adjust the table name as needed
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getVerifiedUsers() { //get all the vrified users
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
    
    public function getPendingRequests() { //total number of pending payments
        $query = "SELECT COUNT(*) as total FROM payment WHERE payment_status = 'pending'"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function fetchPendingPayments() { //get all the payment status is pending
        $stmt = $this->conn->prepare("SELECT p.*, u.email as user_email FROM payment as p JOIN user as u ON p.user_id = u.user_id WHERE p.payment_status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalID() { //total pending request
        $query = "SELECT COUNT(*) as total FROM identityverification WHERE  verify_status = 'pending'"; 
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getPendingID() { //get all the pending valid_id
        $query = "SELECT user_id, CONCAT(first_name, ' ',last_name) AS name, dob, address, country, valid_id, id_number, verify_status FROM identityverification WHERE verify_status = 'pending'"; // Adjust the table name as needed
        $stmt = $this->conn->query($query); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handlePayment($payment_id, $action) {
        $payment = new PaymentMailer($this->conn); //email if parment is approve or rejected 
        $payment->handlePayment($payment_id, $action);
    }

    public function getverified($user_id, $action) {
        $request = new RequestMailer($this->conn); //send email to the user if valid id is verified/rejected
        $request->updateUserVerification($user_id, $action);
    }
    
}
?>