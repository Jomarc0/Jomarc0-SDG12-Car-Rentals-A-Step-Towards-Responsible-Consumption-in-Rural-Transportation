<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
session_start(); //para magstart yung session

class Verification {
    private $conn;

    public function __construct() {
        $database = new Database(); 
        $this->conn = $database->getConn(); 
    }

    public function verifyAccount($verification_code) {
        if (isset($_SESSION['verification_code']) && isset($_SESSION['email'])) { //check if may laman yung verification code at email
            if ($verification_code == $_SESSION['verification_code']) { //sessioon yung code
                $email = $_SESSION['email']; //session the email to use in sql
                $query = "UPDATE user SET verified = 1 WHERE email = :email";
                $sql = $this->conn->prepare($query);
                $sql->bindParam(':email', $email); // to prevent SQL injection

                if ($sql->execute()) { // execute the update query
                    unset($_SESSION['verification_code']); //unset para mawala yung nasession
                    unset($_SESSION['email']);
                    header("Location: signIn.php"); //redirect na poara magsign in
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
        $this->conn = null; 
    }
}
?>