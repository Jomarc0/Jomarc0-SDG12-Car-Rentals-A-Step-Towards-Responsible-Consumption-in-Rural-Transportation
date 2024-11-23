<?php

require_once __DIR__ . '/../dbcon/dbcon.php';
require 'OTPmailer.php'; // Include the OTPService class

class OTPServiceHandler {
    private $conn;
    private $otpService;

    public function __construct() {
        $database = new Database(); // Create a new Database instance
        $this->conn = $database->getConnec(); // Get the database connection
        // Initialize the OTPService with your SMTP settings
        $this->otpService = new OTPService('smtp.gmail.com', 'jaycarrent@gmail.com', 'ygic eqgh ucoi bdio', 587, 'tls');
    }

    public function sendOTP($email) {
        // Check if the email exists
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Send OTP
            if ($this->otpService->sendOTP($email)) {
                return "OTP has been sent to your email.";
            } else {
                return "Failed to send OTP. Please try again.";
            }
        } else {
            return "No user found with that email address.";
        }
    }

    public function __destruct() {
        // Close the database connection
        $this->conn->close();
    }
}

// Usage example
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sendOTP"])) {
    $email = $_POST['email'];

    // Create an instance of OTPServiceHandler
    $otpHandler = new OTPServiceHandler();
    $message = $otpHandler->sendOTP($email);

    // Display the message
    echo $message;

    // Redirect if OTP is sent successfully
    if (strpos($message, 'OTP has been sent') !== false) {
        header('Location: verifyOTP.php');
        exit(); // Ensure no further code is executed after redirect
    }
}
?>