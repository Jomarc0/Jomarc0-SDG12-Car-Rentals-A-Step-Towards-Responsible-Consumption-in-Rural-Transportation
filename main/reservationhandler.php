<?php
class ReservationHandler {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection; 
    }

    public function handleReservationSubmission() {
        $selectedBookingArea = $_POST['bookingArea'] ?? '';
        $selectedDestination = $_POST['destination'] ?? '';// initialize variables from post data

        $data = [
            'bookingArea' => trim($selectedBookingArea),
            'destination' => trim($selectedDestination),
            'tripDateTime' => trim($_POST['tripDateTime']),
            'returnDateTime' => trim($_POST['returnDateTime']),
            'vehicleType' => trim($_POST['vehicleType']),
        ];

        // checking if walang laman yung data
        foreach ($data as $value) {
            if (empty($value)) {
                $_SESSION['message'] = "All fields are required.";
                header('Location: reservation.php'); // redirect to reservastion
                exit;
            }
        }

        $userId = $this->getUserId(); //fetch the userdata

        if (!$userId) { //if no user fetch
            $_SESSION['message'] = "User is not logged in.";
            header('Location: login.php'); // redirect to login page
            exit;
        }

        // submit the reservation with user ID
        try {
            $reservation = new Reservation($this->conn); // create object
            $resultMessage = $reservation->submitReservation($data, $userId);
            $_SESSION['message'] = $resultMessage; // store success message in session
        } catch (Exception $e) {
            $_SESSION['message'] = "An error occurred: " . $e->getMessage(); // store error message in session
        }

        header('Location: generateReceipt.php'); //redirect to the receipt 
        exit;
    }

    private function getUserId() { //getting the email using session to fetch userid
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
