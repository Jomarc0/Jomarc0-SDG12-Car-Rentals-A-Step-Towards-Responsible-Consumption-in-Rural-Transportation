<?php
class Reservation {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection; 
    }

    public function submitReservation($data, $userId) { //function to submit reservation data to phpmyadmin
        // prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO rentedcar (booking_area, destination, trip_date_time, return_date_time, vehicle_type, user_id) 
                VALUES (:booking_area, :destination, :trip_date_time, :return_date_time, :vehicle_type, :user_id)");

        $stmt->bindParam(':booking_area', $data['bookingArea']);// bind to prevent SQL Injection
        $stmt->bindParam(':destination', $data['destination']);
        $stmt->bindParam(':trip_date_time', $data['tripDateTime']);
        $stmt->bindParam(':return_date_time', $data['returnDateTime']);
        $stmt->bindParam(':vehicle_type', $data['vehicleType']);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) { //execute the statement
            return "Reservation request submitted successfully!";
        } else {
            throw new Exception("Error: " . implode(", ", $stmt->errorInfo()));
        }
    }
}
?>
