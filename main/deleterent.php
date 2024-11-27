<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 

class DeleteRent {
    private $conn;

    public function __construct() {
        try {
            $database = new Database(); 
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function deleteRental(int $rent_id): string { //: string is to handle the return type
        
        if ($rent_id <= 0) { //check the rent id
            return "Invalid rental ID.";
        }

        $this->conn->beginTransaction();
        try {
            // move all the rendtedcar to recentlydeleted once it is deleted
            $stmt = $this->conn->prepare("INSERT INTO recentlydeleted (booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id)
                                            SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id
                                            FROM rentedcar WHERE rent_id = :rent_id");
            $stmt->bindParam(':rent_id', $rent_id);

            if (!$stmt->execute()) { //if not executed
                throw new Exception('Error moving rental to history: ' .$stmt->errorInfo()[2]);
            }

            // delete the data from rentedcar
            $stmtDelete = $this->conn->prepare("DELETE FROM rentedcar WHERE rent_id = :rent_id");
            $stmtDelete->bindParam(':rent_id', $rent_id);
            if (!$stmtDelete->execute()) {
                throw new Exception("Error deleting rental: " . $stmtDelete->errorInfo()[2]);
            }

            // if successfuly deleted
            $this->conn->commit();
            header('Location:' .'reservation.php');
            return "Rental has been deleted successfully and moved to history.";
        } catch (Exception $e) {
            $this->conn->rollBack(); // rollback the transaction in case of error
            return "Error: " . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>