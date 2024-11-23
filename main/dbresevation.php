<?php
require_once __DIR__ . '/db.php';

class Reservation {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection; // Store the database connection
    }

    public function submitReservation($data) {
        // Prepare and bind
        $sql = $this->conn->prepare("INSERT INTO quotations (booking_area, destination, trip_date_time, return_date_time, vehicle_type) VALUES ( ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssss", 
            $data['bookingArea'], 
            $data['destination'], 
            $data['tripDate'],  
            $data['returnDate'], 
            $data['vehicleType'], 
        );

        // Execute the statement
        if ($sql->execute()) {
            return "Quotation request submitted successfully!";
        } else {
            return "Error: " . $sql->error;
        }
    }
}
?>