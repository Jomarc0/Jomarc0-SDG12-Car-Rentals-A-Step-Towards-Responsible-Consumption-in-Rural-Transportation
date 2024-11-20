<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class AdminRentedCar {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConn();
    }


    // New method to get user ID by rent ID
    public function getUserIdByRentId($rentId) {
        $query = "SELECT u.user_id 
                  FROM user AS u 
                  JOIN rentedcar AS r ON u.user_id = r.user_id 
                  WHERE r.rent_id = :rent_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rent_id', $rentId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn(); // Fetch the user ID
    }

    public function getRentedCarByRentId($rentId) {
        $query = "SELECT r.rent_id, r.booking_area, r.destination, r.trip_date_time, r.return_date_time, r.vehicle_type, r.rent_status 
                  FROM rentedcar AS r 
                  WHERE r.rent_id = :rent_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rent_id', $rentId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the rented car details
    }
    public function getUserById($userId) {
        // SQL query to fetch user details by user ID
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.address, u.gender, u.dob, u.email, u.phone_number, u.verification_code 
                  FROM user AS u 
                  WHERE u.user_id = :user_id"; // Adjust table name and fields as necessary
    
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the user ID parameter
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch the user details
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the user details as an associative array
    }
    public function updateRentedCar($data) {
        $updateQuery = "UPDATE rentedcar SET 
                            booking_area = :booking_area, 
                            destination = :destination, 
                            trip_date_time = :trip_date_time, 
                            return_date_time = :return_date_time, 
                            vehicle_type = :vehicle_type, 
                            rent_status = :rent_status 
                        WHERE rent_id = :rent_id";
        
        $stmt = $this->conn->prepare($updateQuery);
        
        // Bind parameters
        $stmt->bindParam(':booking_area', $data['booking_area'], PDO::PARAM_STR);
        $stmt->bindParam(':destination', $data['destination'], PDO::PARAM_STR);
        $stmt->bindParam(':trip_date_time', $data['trip_date_time'], PDO::PARAM_STR);
        $stmt->bindParam(':return_date_time', $data['return_date_time'], PDO::PARAM_STR);
        $stmt->bindParam(':vehicle_type', $data['vehicle_type'], PDO::PARAM_STR);
        $stmt->bindParam(':rent_status', $data['rent_status'], PDO::PARAM_STR);
        $stmt->bindParam(':rent_id', $data['rent_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function updateUser($data) {
        $updateQuery = "UPDATE user SET 
                            first_name = :first_name, 
                            last_name = :last_name, 
                            address = :address, 
                            gender = :gender, 
                            dob = :dob, 
                            email = :email, 
                            phone_number = :phone_number, 
                            verification_code = :verification_code 
                        WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($updateQuery);
        
        // Bind parameters
        $stmt->bindParam(':first_name', $data['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $data['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':dob', $data['dob'], PDO::PARAM_STR);
        $stmt-> bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $data['phone_number'], PDO::PARAM_STR);
        $stmt->bindParam(':verification_code', $data['verification_code'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function deleteUser($userId) {
        // Start a transaction
        $this->conn->beginTransaction();
        
        try {
            // Step 1: Fetch the user record before deletion
            $fetchUserQuery = "SELECT * FROM user WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($fetchUserQuery);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Check if the user exists
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                throw new Exception("User  not found.");
            }
    
            // Step 2: Delete the user record
            $deleteUserQuery = "DELETE FROM user WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($deleteUserQuery);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Step 3: Insert the deleted user into the user_recently_deleted table
            $insertDeletedUserQuery = "INSERT INTO user_recently_deleted (user_id, name, email, deleted_at) VALUES (:user_id, :name, :email, NOW())";
            $stmt = $this->conn->prepare($insertDeletedUserQuery);
            $stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $user['name'], PDO::PARAM_STR); // Assuming 'name' is a field in your user table
            $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR); // Assuming 'email' is a field in your user table
            $stmt->execute();
    
            // Commit the transaction
            $this->conn->commit(); 
            
            return true; // Indicate success
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Indicate failure
        } catch (Exception $e) {
            // Handle user not found or other exceptions
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Indicate failure
        }
    }

    public function deleteRentedCar($rentId) {
        // Start a transaction
        $this->conn->beginTransaction();
        
        try {
            // Step 1: Fetch the rented car records before deletion
            $fetchRentedCarQuery = "SELECT * FROM rentedcar WHERE rent_id = :rent_id";
            $stmt = $this->conn->prepare($fetchRentedCarQuery);
            $stmt->bindParam(':rent_id', $rentId, PDO::PARAM_INT);
            $stmt->execute();
            
            $rentedCars = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rentedCars)) {
                throw new Exception("No rented cars found for this user.");
            }
    
            // Step 2: Move the rented car records to the recently deleted table
            $insertDeletedRentedCarQuery = "INSERT INTO recentlydeletedrent (rent_id, booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, deleted_at)
                                            VALUES (:rent_id, :booking_area, :destination, :trip_date_time, :return_date_time, :vehicle_type, :rent_status, NOW())";
            
            $insertStmt = $this->conn->prepare($insertDeletedRentedCarQuery);
            
            // Loop through each rented car record and insert it into the recently deleted table
            foreach ($rentedCars as $rentedCar) {
                $insertStmt->bindParam(':rent_id', $rentedCar['rent_id'], PDO::PARAM_INT);
                $insertStmt->bindParam(':booking_area', $rentedCar['booking_area'], PDO::PARAM_STR);
                $insertStmt->bindParam(':destination', $rentedCar['destination'], PDO::PARAM_STR);
                $insertStmt->bindParam(':trip_date_time', $rentedCar['trip_date_time'], PDO::PARAM_STR);
                $insertStmt->bindParam(':return_date_time', $rentedCar['return_date_time'], PDO::PARAM_STR);
                $insertStmt->bindParam(':vehicle_type', $rentedCar['vehicle_type'], PDO::PARAM_STR);
                $insertStmt->bindParam(':rent_status', $rentedCar['rent_status'], PDO::PARAM_STR);
                $insertStmt->execute();
            }
    
            // Step 3: Delete the rented car records
            $deleteRentedCarQuery = "DELETE FROM rentedcar WHERE rent_id = :rent_id";
            $stmt = $this->conn->prepare($deleteRentedCarQuery);
            $stmt->bindParam(':rent_id', $rentId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Commit the transaction
            $this->conn->commit(); 
            
            return true; // Indicate success
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Indicate failure
        } catch (Exception $e) {
            // Handle user not found or other exceptions
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Indicate failure
        }
    }

}
?>