<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
session_start();

class Verification {
    private $conn;

    public function __construct() {
        $database = new Database(); // Create a new Database instance
        $this->conn = $database->getConn(); // Get the database connection
    }

    public function verifyAccount($verification_code) {
        if (isset($_SESSION['verification_code']) && isset($_SESSION['email'])) {
            // Compare the provided verification code with the session code
            if ($verification_code == $_SESSION['verification_code']) {
                $email = $_SESSION['email'];
                $query = "UPDATE user SET verified = 1 WHERE email = :email";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $email);

                // Execute the update query
                if ($stmt->execute()) {
                    unset($_SESSION['verification_code']);
                    unset($_SESSION['email']);
                    header("Location: signIn.php");
                    exit; // Make sure to exit after redirection
                } else {
                    // Log the error for debugging
                    error_log("Failed to update verification status for email: $email");
                    return "Verification failed. Please try again.";
                }
            } else {
                return "Invalid verification code. Please try again.";
            }
        } else {
            return "No verification code found. Please register again.";
        }
    }

    public function __destruct() {
        // Close the database connection
        $this->conn = null; // Use null to close the connection properly
    }
}
?>