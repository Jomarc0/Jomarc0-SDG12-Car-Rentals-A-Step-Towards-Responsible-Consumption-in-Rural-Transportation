<?php

class ReservationHandler {
    private $conn;

    // Constructor accepts a database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection; // Store the database connection
    }

    public function handleReservationSubmission() {
        // Initialize variables
        $selectedBookingArea = $_POST['bookingArea'] ?? '';
        $selectedDestination = $_POST['destination'] ?? '';

        // Collect other form data
        $data = [
            'bookingArea' => trim($selectedBookingArea),
            'destination' => trim($selectedDestination),
            'tripDateTime' => trim($_POST['tripDateTime']),
            'returnDateTime' => trim($_POST['returnDateTime']),
            'vehicleType' => trim($_POST['vehicleType']),
        ];

        // Validation
        foreach ($data as $value) {
            if (empty($value)) {
                $_SESSION['message'] = "All fields are required.";
                header('Location: reservation.php'); // Redirect back to the form
                exit;
            }
        }

        // Fetch user data
        $userId = $this->getUserId();

        if (!$userId) {
            $_SESSION['message'] = "User is not logged in.";
            header('Location: login.php'); // Redirect to login page
            exit;
        }

        // Submit the reservation with user ID
        try {
            $reservation = new Reservation($this->conn); // Create Reservation object
            $resultMessage = $reservation->submitReservation($data, $userId);
            $_SESSION['message'] = $resultMessage; // Store success message in session
        } catch (Exception $e) {
            $_SESSION['message'] = "An error occurred: " . $e->getMessage();
        }

        // Redirect to the receipt page
        header('Location: generateReceipt.php'); 
        exit;
    }

    // Method to get the user ID based on session data
    private function getUserId() {
        $email = $_SESSION['email'] ?? null;

        if ($email) {
            $query = "SELECT user_id FROM user WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? $user['user_id'] : null;
        }

        return null;
    }
}

?>
