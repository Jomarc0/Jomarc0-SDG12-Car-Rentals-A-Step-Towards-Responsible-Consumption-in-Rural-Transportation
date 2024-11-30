<?php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require_once __DIR__ . '/../dbcon/dbcon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserHandler{
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function updateUserVerification($user_id, $action) {
        try {
            // Fetch user's email
            $stmt = $this->conn->prepare("SELECT email FROM user WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $user['email'];
            $stmt->closeCursor(); // Close the cursor

            $mail = new PHPMailer(true);
            // Server settings
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->Host       = 'smtp.gmail.com';
            $mail->Username   = 'jaycarrent@gmail.com';
            $mail->Password   = 'ygic eqgh ucoi bdio'; // Consider using environment variables for sensitive data
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('jaycarrent@gmail.com', 'Mailer');
            $mail->addAddress($email, 'User ');

            if ($action === 'approve') {
                // Update user verified_id to 'verified'
                $stmt = $this->conn->prepare("UPDATE user SET verified_id = 'verified' WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                // Email content
                $subject = "Account Verified";
                $message = "Dear User,\n\nYour account has been verified. Thank you for your patience.\n\nBest regards,\nAdmin";
            } else {
                // Update user verified_id to 'not verified'
                $stmt = $this->conn->prepare("UPDATE user SET verified_id = 'not verified' WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                // Email content
                $subject = "Account Verification Failed";
                $message = "Dear User,\n\nYour account could not be verified. Please contact support for more details.\n\nBest regards,\nAdmin";
            }

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = nl2br($message);

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}