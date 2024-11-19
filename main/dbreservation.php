<?php
class Reservation {
    private $conn;

    // Constructor accepts the database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection; // Store the database connection
    }

    // Method to submit reservation data to the database
    public function submitReservation($data, $userId) {
        // Prepare the SQL statement to insert data into the rentedcar table
        $sql = "INSERT INTO rentedcar (booking_area, destination, trip_date_time, return_date_time, vehicle_type, user_id) 
                VALUES (:booking_area, :destination, :trip_date_time, :return_date_time, :vehicle_type, :user_id)";
        
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Bind the form data to the SQL query
        $stmt->bindParam(':booking_area', $data['bookingArea']);
        $stmt->bindParam(':destination', $data['destination']);
        $stmt->bindParam(':trip_date_time', $data['tripDateTime']);
        $stmt->bindParam(':return_date_time', $data['returnDateTime']);
        $stmt->bindParam(':vehicle_type', $data['vehicleType']);
        $stmt->bindParam(':user_id', $userId);

        // Execute the statement and return success or failure message
        if ($stmt->execute()) {
            return "Reservation request submitted successfully!";
        } else {
            throw new Exception("Error: " . implode(", ", $stmt->errorInfo()));
        }
    }
}
?>
