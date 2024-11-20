<?php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php'; // PHPMailer
require_once __DIR__ . '/../dbcon/dbcon.php'; // Include the database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OTPService {
    private $mail;
    private $conn; 

    public function __construct() {
        // initialize PHPMailer
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Host = 'smtp.gmail.com'; // SMTP host
        $this->mail->Username = 'jaycarrent@gmail.com'; // email
        $this->mail->Password = 'ygic eqgh ucoi bdio'; // secret pass
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $this->mail->Port = 587; 

        $database = new Database();
        $this->conn = $database->getConn(); 
    }

    public function sendOTP($toEmail) {
        $verification_code = rand(100000, 999999); // generate a ramdon 6-digit code

        // store OTP in the database
        if ($this->storeOTP($toEmail, $verification_code)) {
            try {
                $this->mail->setFrom('jaycarrent@gmail.com', 'Mailer'); // sender
                $this->mail->addAddress($toEmail); // recipient

                // message
                $this->mail->isHTML(true);
                $this->mail->Subject = 'Your OTP for Password Reset';
                $this->mail->Body    = 'Your OTP is: <strong>' . $verification_code . '</strong>';
                $this->mail->AltBody = 'Your OTP is: ' . $verification_code;
                $this->mail->send();
                return true; // email sent successfully
            } catch (Exception $e) {
                error_log("Mailer Error: " . $this->mail->ErrorInfo); // log the error
                return false; // email sending failed
            }
        }
        return false; // Failed to store OTP
    }

    private function storeOTP($email, $verification_code) {
        $stmt = $this->conn->prepare("UPDATE user SET verification_code = :verification_code WHERE email = :email"); //up-date the verification code
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->bindParam(':email', $email);
        return $stmt->execute(); // if successful, false otherwise
    }
}
?>