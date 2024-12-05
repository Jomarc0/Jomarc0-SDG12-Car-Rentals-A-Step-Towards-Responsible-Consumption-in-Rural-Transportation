<?php
session_start(); // session start
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
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword) {
        $errors = [];
    
        // validate passwords
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
    
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // check age must be 18 years old and above
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dobDate)->y; // calculate age

        if ($age < 18) {
            $errors[] = "You must be at least 18 years old to register.";
        }

        // validate email existence
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors[] = "Email already exists.";
        }
    
        // uf there are validation errors, return them as a string
        if (!empty($errors)) {
            return implode(" ", $errors); // combine errors into a single string
        }
    
        // hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // generate a random verification code
        $verification_code = rand(100000, 999999);
    
        // Insert values into the database
        $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, verification_code) 
                VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :verification_code)";
        $sql = $this->conn->prepare($query);
    
        if ($sql->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':email' => $email,
            ':phone_number' => $phoneNumber,
            ':password' => $hashedPassword,
            ':verification_code' => $verification_code,
        ])) {
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;
    
            return $this->sendVerificationEmail($email, $first_name, $last_name, $verification_code);
        } else {
            return "Registration failed: " . implode(", ", $sql->errorInfo());
        }
    }

    public function isUserRegistered($email) {
        // Prepare a SQL statement to check if the user exists
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // Return true if the user exists, false otherwise
        return $count > 0;
    }

    public function registerFromGoogle($first_name, $last_name, $email) {
        error_log("Registering user: $first_name $last_name, Email: $email");

        // Check if the user already exists in the database
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return "User  already exists. Please log in.";
        } else {
            // User does not exist, insert them into the database
            $address = '';
            $gender = '';
            $dob = '';
            $phoneNumber = '';
            $hashedPassword = '';
            $verification_code = rand(100000, 999999);

            $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, verification_code) 
                    VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :verification_code)";
            $sql = $this->conn->prepare($query);

            if ($sql->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':address' => $address,
                ':gender' => $gender,
                ':dob' => $dob,
                ':email' => $email,
                ':phone_number' => $phoneNumber,
                ':password' => $hashedPassword,
                ':verification_code' => $verification_code,
            ])) {
                session_start();
                $_SESSION['verification_code'] = $verification_code;
                $_SESSION['email'] = $email;
                
                return $this->sendVerificationEmail($email, $first_name, $last_name, $verification_code);
            } else {
                return "Error inserting user data: " . implode(", ", $sql->errorInfo());
            }
        }
    }

    private function sendVerificationEmail($email, $first_name, $last_name, $verification_code) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->Host       = 'smtp.gmail.com';
            $mail->Username   = 'jaycarrent@gmail.com'; // Gmail
            $mail->Password   = 'ygic eqgh ucoi bdio'; // Secret pass from my Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Receiver
            $mail->setFrom('jaycarrent@gmail.com', 'Mailer');
            $mail->addAddress($email, $first_name . ' ' . $last_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verification Code';
            $mail->Body    = '<h3>Verification code to access</h3>
                            <h3>Name: ' . $first_name . ' ' . $last_name . '</h3>
                            <h3>Email: ' . $email . '</h3>
                            <h3>Verification Code: ' . $verification_code . '</h3>';

            if ($mail->send()) {
                return "Registration successful. Verification code sent to your email.";
            } else {
                return "Registration successful. Unable to send verification code. Mailer Error: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            return "Registration successful. Unable to send verification code. Mailer Error: " . $mail->ErrorInfo;
        }
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>