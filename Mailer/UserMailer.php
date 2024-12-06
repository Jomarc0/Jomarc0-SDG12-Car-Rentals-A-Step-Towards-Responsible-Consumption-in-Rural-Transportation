<?php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php'; // PHPMailer
require_once __DIR__ . '/../dbcon/dbcon.php'; // Include the database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

abstract class Mailer {
    protected $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }

    private function setupSMTP() {
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Host = 'smtp.gmail.com'; // host
        $this->mail->Username = 'jaycarrent@gmail.com'; //email
        $this->mail->Password = 'ygic eqgh ucoi bdio'; // secret pass
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $this->mail->Port = 587; 
    }

    protected function sendEmail($toEmail, $subject, $body) {
        try {
            $this->mail->setFrom('jaycarrent@gmail.com', 'Quick Wheels'); //tittle sent
            $this->mail->addAddress($toEmail);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body; //the message

            $this->mail->send();
            $this->mail->clearAddresses(); // clear addresses for the next email
            return true; // Email sent successfully
        } catch (Exception $e) {
            error_log("Mailer Error: " . $this->mail->ErrorInfo); // error
            return false; // sending failed
        }
    }
}

class SendEMail extends Mailer {
    private $conn; 

    public function __construct() {
        parent::__construct(); // call the parent constructor
        $database = new Database();
        $this->conn = $database->getConn(); 
    }

    public function sendVerification($email, $first_name, $last_name, $verification_code) {
        $subject = 'Verification Code';
        $body = '<h3>Verification code to access</h3>
                <h3>Name: ' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</h3>
                <h3>Email: ' . htmlspecialchars($email) . '</h3>
                <h3>Verification Code: ' . htmlspecialchars($verification_code) . '</h3>';

        return $this->sendEmail($email, $subject, $body) 
            ? "Registration successful. Verification code sent to your email." 
            : "Unable to send verification code.";
    }

    public function sendOTP($toEmail) {
        $verification_code = rand(100000, 999999); // generate a random 6-digit code

        // Store OTP in the database
        if ($this->storeOTP($toEmail, $verification_code)) {
            $subject = 'Your OTP for Password Reset';
            $body = 'Your OTP is: <strong>' . htmlspecialchars($verification_code) . '</strong>';
            return $this->sendEmail($toEmail, $subject, $body) 
                ? true 
                : false; // failed to send
        }
        return false; // di nagstore otp
    }

    private function storeOTP($email, $verification_code) {
        $stmt = $this->conn->prepare("UPDATE user SET verification_code = :verification_code WHERE email = :email"); // update the verification code
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->bindParam(':email', $email);
        return $stmt->execute(); // ff successful, return true; otherwise false
    }
}
?>