<?php
session_start(); // Start the session
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserRegistration {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // Get database connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword) {
        // Check if passwords match
        if ($password !== $confirmPassword) {
            return "Passwords do not match";
        }
        // Check if password is at least 8 characters long
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long";
        }
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashedConfirmPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);
    
        // Check if email already exists
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email); // Bind the email parameter
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array
    
        if ($result) {
            return 'Email already exists';
        }
    
        // Generate verification code
        $verification_code = rand(100000, 999999);
        
        $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, confirm_password, verification_code) 
                    VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :confirmPassword, :verification_code)";
    
        $stmt = $this->conn->prepare($query);
    
        // Execute the statement with an inline array of values
        if ($stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':email' => $email,
            ':phone_number' => $phoneNumber,
            ':password' => $hashedPassword,
            ':confirmPassword' => $hashedConfirmPassword,
            ':verification_code' => $verification_code
        ])) {
            // Store verification code in session
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;
    
            // Send verification code to email using PHPMailer
            return $this->sendVerificationEmail($email, $first_name, $last_name, $verification_code);
        } else {
            return "Registration failed: " . implode(", ", $stmt->errorInfo()); // Display error message
        }
    }

    private function sendVerificationEmail($email, $first_name, $last_name, $verification_code) {
        $mail = new PHPMailer(true);

        try {
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
            $mail->addAddress($email, $first_name . ' ' . $last_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject =    'Verification Code';
            $mail->Body    =    '<h3>Verification code to access</h3>
                                <h3>Name: ' . $first_name . ' ' . $last_name . '</h3>
                                <h3>Email: ' . $email . '</h3>
                                <h3>Verification Code: ' . $verification_code . '</h3>';

            if ($mail->send()) {
                return "Registration successful. Verification code sent to your email.";
                header('Location:' .'verify.php');
            } else {
                return "Registration successful. Unable to send verification code. Mailer Error: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            return "Registration successful. Unable to send verification code. Mailer Error: " . $mail->ErrorInfo;
        }
    }

    public function __destruct() {
        // Close the database connection
        $this->conn = null; // Use null to close the connection properly
    }
}

?>