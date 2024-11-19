<?php
// OTPService.php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../dbcon/dbcon.php'; // Include the database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OTPService {
    private $mail;
    private $conn; // Database connection

    public function __construct($host, $username, $password, $port, $encryption) {
        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $host;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $username;
        $this->mail->Password = $password;
        $this->mail->SMTPSecure = $encryption;
        $this->mail->Port = $port;

        // Instantiate the Database class and get the connection
        try {
            $database = new Database(); // Create a new Database instance
            $this->conn = $database->getConnec(); // Get the database connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function sendOTP($toEmail) {
        // Generate OTP
        $verification_code = rand(100000, 999999); // Generate a 6-digit OTP

        // Store OTP in the database
        if ($this->storeOTP($toEmail, $verification_code)) {
            try {
                $this->mail->setFrom('jaycarrent@gmail.com', 'Mailer'); // Sender's email and name
                $this->mail->addAddress($toEmail); // Add a recipient

                // Content
                $this->mail->isHTML(true);
                $this->mail->Subject = 'Your OTP for Password Reset';
                $this->mail->Body    = 'Your OTP is: <strong>' . $verification_code . '</strong>';
                $this->mail->AltBody = 'Your OTP is: ' .$verification_code;
                $this->mail->send();
                return true; // Email sent successfully
            } catch (Exception $e) {
                return false; // Email sending failed
            }
        }
        return false; // Failed to store OTP
    }

    private function storeOTP($email, $verification_code) {
        $stmt = $this->conn->prepare("UPDATE user SET verification_code WHERE email = ?");
        $stmt->bind_param("is", $verification_code, $email);
        return $stmt->execute(); // Return true if successful, false otherwise
    }
}
?>