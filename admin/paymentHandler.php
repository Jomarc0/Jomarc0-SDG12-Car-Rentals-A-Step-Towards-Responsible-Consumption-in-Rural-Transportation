<?php
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require_once __DIR__ . '/../dbcon/dbcon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PaymentHandler {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function handlePayment($payment_id, $action) {
        try {
            // Fetch user's email
            $stmt = $this->conn->prepare("SELECT u.email FROM payment p JOIN users u ON p.user_id = u.id WHERE p.payment_id = :payment_id");
            $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
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
            $mail->Password   = 'ygic eqgh ucoi bdio';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('jaycarrent@gmail.com', 'Mailer');
            $mail->addAddress($email, 'User ');

            if ($action === 'approve') {
                // Update payment status to Approved
                $stmt = $this->conn->prepare("UPDATE payment SET payment_status = 'approved' WHERE payment_id = :payment_id");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                // Update rent statuses to Completed for all associated rents
                $stmt = $this->conn->prepare("UPDATE rentedcar SET rent_status = 'rented' WHERE rent_id = (SELECT rent_id FROM payment WHERE payment_id = :payment_id)");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                // Email content
                $subject = "Payment Approved";
                $message = "Dear User,\n\nYour payment has been approved. Thank you for your transaction.\n\nBest regards,\nAdmin";
            } else {
                // Update payment status to Rejected
                $stmt = $this->conn->prepare("UPDATE payment SET payment_status = 'rejected' WHERE payment_id = :payment_id");
                $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                // Email content
                $subject = "Payment Rejected";
                $message = "Dear User,\n\nYour payment has been rejected. Please contact support for more details.\n\nBest regards,\nAdmin";
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