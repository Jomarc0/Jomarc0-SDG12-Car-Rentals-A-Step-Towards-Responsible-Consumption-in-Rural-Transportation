<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class CancelRent {
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

    // Method to cancel a rental
    public function cancelRental(int $rent_id): string {
        // Validate rent_id
        if ($rent_id <= 0) {
            return "Invalid rental ID.";
        }

        // Prepare the UPDATE statement to change the status to 'completed'
        $stmt = $this->conn->prepare("UPDATE rentedcar SET status = 'completed' WHERE rent_id = :rent_id");
        $stmt->bindParam(':rent_id', $rent_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Successfully updated
            return "Rental has been canceled successfully and status updated to 'completed'.";
        } else {
            // Failed to update
            throw new Exception("Error: Could not update the rental status. " . $stmt->errorInfo()[2]);
        }
    }

    // Destructor to close the database connection
    public function __destruct() {
        $this->conn = null; // Close the connection
    }
}
?>