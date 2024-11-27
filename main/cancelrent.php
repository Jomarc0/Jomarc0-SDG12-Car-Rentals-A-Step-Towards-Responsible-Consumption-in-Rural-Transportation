<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class CancelRent {
    private $conn;

    public function __construct() {
        try {
            $database = new Database(); 
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function cancelRental(int $rent_id):string{ //function to cancel rent //: string is to handle the return type 
        // Validate rent_id
        if ($rent_id <= 0) {
            return "Invalid rental ID.";
        }

        $stmt = $this->conn->prepare("UPDATE rentedcar SET rent_status = 'completed' WHERE rent_id = :rent_id"); //if the user cancel the rent the status will be updfate to completed and display in history
        $stmt->bindParam(':rent_id', $rent_id);

        if ($stmt->execute()) { //execute the statement
            return "Rental has been canceled successfully and status updated to 'completed'.";
            header('Location:' .'reservation.php');
        } else {
            throw new Exception("Error: Could not update the rental status. " . $stmt->errorInfo()[2]); //error to update
        }
    }



    public function __destruct() {
        $this->conn = null; 
    }
}
?>