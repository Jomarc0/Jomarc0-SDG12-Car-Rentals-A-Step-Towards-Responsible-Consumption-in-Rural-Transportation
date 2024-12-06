<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class RentUpdate {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn();
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function cancelRental(int $rent_id): string {
        // Validate rent_id
        if ($rent_id <= 0) {
            return "Invalid rental ID.";
        }

        $stmt = $this->conn->prepare("UPDATE rentedcar SET rent_status = 'completed' WHERE rent_id = :rent_id");
        $stmt->bindParam(':rent_id', $rent_id);

        if ($stmt->execute()) {
            return "Rental has been canceled successfully and status updated to 'completed'.";
        } else {
            throw new Exception("Error: Could not update the rental status. " . $stmt->errorInfo()[2]);
        }
    }

    public function deleteRental(int $rent_id): string {
        // Validate rent_id
        if ($rent_id <= 0) {
            return "Invalid rental ID.";
        }

        $this->conn->beginTransaction();

        try {
            // Move all the rented car to recently deleted once it is deleted
            $stmt = $this->conn->prepare("INSERT INTO recentlydeleted (booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id) SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id FROM rentedcar WHERE rent_id = :rent_id");
            $stmt->bindParam(':rent_id', $rent_id);

            if (!$stmt->execute()) {
                throw new Exception('Error moving rental to history: ' . $stmt->errorInfo()[2]);
            }

            // Delete the data from rentedcar
            $stmtDelete = $this->conn->prepare("DELETE FROM rentedcar WHERE rent_id = :rent_id");
            $stmtDelete->bindParam(':rent_id', $rent_id);

            if (!$stmtDelete->execute()) {
                throw new Exception("Error deleting rental: " . $stmtDelete->errorInfo()[2]);
            }

            // If successful, commit the transaction
            $this->conn->commit();
            return "Rental has been deleted successfully and moved to history.";
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->conn = null;
    }
}