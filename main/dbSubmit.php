<?php
// Start the session
session_start();

require_once __DIR__ . '/../dbcon/dbcon.php';

try {
    $database = new Database();
    $conn = $database->getConn(); // Get the database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

class Reservation {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection; // Store the database connection
    }

    public function submitReservation($data, $userId) {
        // Prepare the SQL statement
        $sql = "INSERT INTO rentedcar (booking_area, destination, trip_date_time, return_date_time, vehicle_type, user_id) VALUES (:booking_area, :destination, :trip_date_time, :return_date_time, :vehicle_type, :user_id)";
        
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':booking_area', $data['bookingArea']);
        $stmt->bindParam(':destination', $data['destination']);
        $stmt->bindParam(':trip_date_time', $data['tripDateTime']);
        $stmt->bindParam(':return_date_time', $data['returnDateTime']);
        $stmt->bindParam(':vehicle_type', $data['vehicleType']);
        $stmt->bindParam(':user_id', $userId);

        // Execute the statement
        if ($stmt->execute()) {
            return "Reservation request submitted successfully!";
        } else {
            throw new Exception("Error: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

// Create an instance of the Reservation class
$reservation = new Reservation($conn); // Constructor is called here

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-btn"])) {
    $data = [
        'bookingArea' => trim($_POST['bookingArea']),
        'destination' => trim($_POST['destination']),
        'tripDateTime' => trim($_POST['tripDateTime']),
        'returnDateTime' => trim($_POST['returnDateTime']),
        'vehicleType' => trim($_POST['vehicleType']),
    ];

    // Validation
    foreach ($data as $key => $value) {
        if (empty($value)) {
            $_SESSION['message'] = "All fields are required.";
            header('Location: reservation.php'); // Redirect back to the form
            exit;
        }
    }

    // Fetch user data
    $email = $_SESSION['email'] ?? null;
    $userId = null;

    if ($email) {
        $query = "SELECT user_id FROM user WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $userId = $user['user_id']; // Get the user ID
        } else {
            $_SESSION['message'] = "User  not found.";
            header('Location: reservation.php'); // Redirect back to the form
            exit;
        }
    } else {
        $_SESSION['message'] = "User  is not logged in.";
        header('Location: login.php'); // Redirect to login page
        exit;
    }

    // Submit the reservation with user ID
    try {
        $resultMessage = $reservation->submitReservation($data, $userId);
        $_SESSION['message'] = $resultMessage; // Store success message in session
    } catch (Exception $e) {
        $_SESSION['message'] = "An error occurred: " . $e->getMessage();
    }

    // Redirect to the receipt page
    header('Location: generateReceipt.php'); exit; // Make sure to exit after redirection
}

// Close the connection
$conn = null; // Close the PDO connection
?>