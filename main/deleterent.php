<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class DeleteRent {
    private $conn;

    // Constructor to establish database connection
    public function __construct() {
        try {
            $database = new Database(); // Create a new Database instance
            $this->conn = $database->getConn(); // Get the database connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    // Method to delete a rental
    public function deleteRental(int $rent_id): string {
        // Validate rent_id
        if ($rent_id <= 0) {
            return "Invalid rental ID.";
        }

        // Start a transaction
        $this->conn->beginTransaction();
        try {
            // Move the rental record to rental_history
            $stmtInsert = $this->conn->prepare("INSERT INTO recentlydeleted (booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id)
                                                SELECT booking_area, destination, trip_date_time, return_date_time, vehicle_type, rent_status, user_id
                                                FROM rentedcar WHERE rent_id = :rent_id");
            $stmtInsert->bindParam(':rent_id', $rent_id);
            if (!$stmtInsert->execute()) {
                throw new Exception("Error moving rental to history: " . $stmtInsert->errorInfo()[2]);
            }

            // Delete the rental record from rentedcar
            $stmtDelete = $this->conn->prepare("DELETE FROM rentedcar WHERE rent_id = :rent_id");
            $stmtDelete->bindParam(':rent_id', $rent_id);
            if (!$stmtDelete->execute()) {
                throw new Exception("Error deleting rental: " . $stmtDelete->errorInfo()[2]);
            }

            // Commit the transaction
            $this->conn->commit();
            return "Rental has been deleted successfully and moved to history.";
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    // Destructor to close the database connection
    public function __destruct() {
        $this->conn = null; // Close the connection
    }
}
?>