<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class AdminRentedCar {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConn();
    }

    public function getRentedCarById($userId) {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.address, u.gender, u.dob, u.email, u.phone_number, u.verification_code, r.booking_area, r.destination, r.trip_date_time, r.return_date_time, r.vehicle_type, r.rent_status 
                FROM user AS u 
                JOIN rentedcar AS r ON u.user_id = r.user_id 
                WHERE u.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRentedCar($data) {
        $updateQuery = "UPDATE rentedcar SET 
                            booking_area = ?, 
                            destination = ?, 
                            trip_date_time = ?, 
                            return_date_time = ?, 
                            vehicle_type = ?, 
                            rent_status = ? 
                        WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($updateQuery);
        return $stmt->execute([$data['booking_area'], $data['destination'], $data['trip_date_time'], $data['return_date_time'], $data['vehicle_type'], $data['rent_status'], $data['user_id']]);
    }

    public function deleteUserAndRentedCar($userId) {
        // Start a transaction
        $this->conn->beginTransaction();
        
        try {
            // Step 1: Move the user's rented car records to the recently deleted table
            $insertQuery = "INSERT INTO recently_deleted_rentedcar (user_id, first_name, last_name, address, gender, dob, email, phone_number, verification_code, booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status)
                            SELECT u.user_id, u.first_name, u.last_name, u.address, u.gender, u.dob, u.email, u.phone_number, u.verification_code, r.booking_area, r.destination, r.trip_date_time, r.return_date_time, r.vehicle_type, r.rent_status 
                            FROM user AS u 
                            JOIN rentedcar AS r ON u.user_id = r.user_id 
                            WHERE u.user_id = ?";
            
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->execute([$userId]);
            
            // Step 2: Delete the rented car records
            $deleteRentedCarQuery = "DELETE FROM rentedcar WHERE user_id = ?";
            $stmt = $this->conn->prepare($deleteRentedCarQuery);
            $stmt->execute([$userId]);
    
            // Step 3: Delete the user record
            $deleteUserQuery = "DELETE FROM user WHERE user_id = ?";
            $stmt = $this->conn->prepare($deleteUserQuery);
            $stmt->execute([$userId]);
            
            // Commit the transaction
            $this->conn->commit();
            
            return true; // Indicate success
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Indicate failure
        }
    }
}