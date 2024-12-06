<?php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../dbcon/dbcon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Username = 'jaycarrent@gmail.com'; //my email
        $this->mail->Password = 'ygic eqgh ucoi bdio'; // this is may secret pass
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        $this->mail->setFrom('jaycarrent@gmail.com', 'Quick Wheels');
    }

    public function sendEmail($toEmail, $subject, $message) {
        try {
            $this->mail->addAddress($toEmail);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = nl2br($message);
            $this->mail->send();
            $this->mail->clearAddresses(); // Clear addresses for the next email
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}

class Handler {
    protected $conn;
    protected $mailer;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        $this->mailer = new Mailer();
    }

    protected function fetchUserEmail($user_id) {
        $stmt = $this->conn->prepare("SELECT email FROM user WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['email'] ?? null; // Return email or null if not found
    }
}

class RequestMailer extends Handler {
    public function updateUserVerification($user_id, $action) {
        try {
            $email = $this->fetchUserEmail($user_id);
            if (!$email) {
                echo "User  not found.";
                return;
            }

            if ($action === 'approve') {
                $stmt = $this->conn->prepare("UPDATE identityverification  SET verify_status = 'verified' WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $subject = "Account Verified";
                $message = "Dear User,\n\nYour account has been verified. Thank you for your patience.\n\nBest regards,\nAdmin";
            } else {
                $stmt = $this->conn->prepare("UPDATE identityverification SET  verify_status = 'rejected' WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                //delete when it is rejected
                $deleteStmt = $this->conn->prepare("DELETE FROM identityverification WHERE user_id = :user_id");
                $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $deleteStmt->execute();

                $subject = "Account Verification Failed";
                $message = "Dear User,\n\nYour account could not be verified. Please contact support for more details.\n\nBest regards,\nAdmin";
            }

            $this->mailer->sendEmail($email, $subject, $message);
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}

class PaymentMailer extends Handler {
    public function handlePayment($payment_id, $action) {
        try {
            $stmt = $this->conn->prepare("SELECT u.email FROM payment p JOIN user u ON p.user_id = u.user_id WHERE p.payment_id = :payment_id");
            $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO:: FETCH_ASSOC);
            $email = $user['email'] ?? null; // Return email or null if not found
            if (!$email) {
                echo "User  not found.";
                return;
            }

            if ($action === 'approve') {
                $stmt = $this->conn->prepare("UPDATE payment SET payment_status = 'approved' WHERE payment_id = :payment_id");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                $stmt = $this->conn->prepare("UPDATE rentedcar SET rent_status = 'rented' WHERE rent_id = (SELECT rent_id FROM payment WHERE payment_id = :payment_id)");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                $subject = "Payment Approved";
                $message = "Dear User,\n\nYour payment has been approved. Thank you for your transaction.\n\nBest regards,\nAdmin";
            } else {
                $stmt = $this->conn->prepare("UPDATE payment SET payment_status = 'rejected' WHERE payment_id = :payment_id");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                $subject = "Payment Rejected";
                $message = "Dear User,\n\nYour payment has been rejected. Please contact support for more details.\n\nBest regards,\nAdmin";
            }

            $this->mailer->sendEmail($email, $subject, $message);
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}
?>