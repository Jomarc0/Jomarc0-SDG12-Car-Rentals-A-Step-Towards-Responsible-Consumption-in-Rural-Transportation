<?php
// resetPassword.php
require_once __DIR__ . '/../dbcon/dbcon.php';

class PasswordReset {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn();
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function resetPassword($email, $verification_code, $newPassword, $newConfirmPassword) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email AND verification_code = :verification_code"); //select all from user table
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //fetch as assosiative array

        if ($result) {
            //hash the password 
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $hashedConfirmPassword = password_hash($newConfirmPassword, PASSWORD_DEFAULT);
            //update the password and confirm
            $stmt = $this->conn->prepare("UPDATE user SET password = :password, confirm_password = :confirmPassword WHERE email = :email");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':confirmPassword', $hashedConfirmPassword);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return "Your password has been reset successfully.";
        } else {
            return "Invalid or expired OTP.";
        }
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>