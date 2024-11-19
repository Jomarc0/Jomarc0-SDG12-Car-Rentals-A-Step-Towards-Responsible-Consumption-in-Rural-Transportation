<?php

require_once __DIR__ . '/../dbcon/dbcon.php';
require 'OTPmailer.php'; // Include the OTPService class

if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST["sendOTP"])) {
    $email = $_POST['email'];

    // Check if the email exists
    $database = new Database(); // Create a new Database instance
    $conn = $database->getConnec(); // Get the database connection

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create an instance of OTPService
        $otpService = new OTPService('smtp.gmail.com', 'jaycarrent@gmail.com', 'ygic eqgh ucoi bdio', 587, 'tls');

        // Send OTP
        if ($otpService->sendOTP($email)) {
            echo "OTP has been sent to your email.";
            header('Location:' . 'verifyOTP.php');
        } else {
            echo "Failed to send OTP. Please try again.";
        }
    } else {
        echo "No user found with that email address.";
    }
}
?>