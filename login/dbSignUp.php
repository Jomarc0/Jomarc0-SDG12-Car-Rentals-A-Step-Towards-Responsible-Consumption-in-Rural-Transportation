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

    public function register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword, $profilePicture, $driversLicense) {
        if ($password !== $confirmPassword) { //check if password match
            return "Passwords do not match";
        }
        
        if (strlen($password) < 8) {    // Check if password is at least 8 characters long
            return "Password must be at least 8 characters long";
        }
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashedConfirmPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);
    
        // Check if email already exists
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);  // to prevent SQL injection
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetch as an associative array
    
        if ($result) {
            return 'Email already exists';
        }

        // Handle profile picture upload
        if (isset($_FILES[$profilePicture])) {
            $file = $_FILES[$profilePicture];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                return "Error uploading profile picture.";
            }

            $fileType = mime_content_type($file['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedTypes)) {
                return "Invalid file type for profile picture. Allowed types: " . implode(", ", $allowedTypes);
            }

            if ($file['size'] > 2 * 1024 * 1024) { // 2MB limit
                return "File size exceeds the maximum limit of 2MB for profile picture.";
            }

            $uploadDir = __DIR__ . '/../uploads/'; // Ensure this directory exists and is writable
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create the directory if it does not exist
            }

            $profilePictureName = uniqid() . '-' . basename($file['name']);
            $profilePicturePath = $uploadDir . $profilePictureName;

            if (!move_uploaded_file($file['tmp_name'], $profilePicturePath)) {
                return "Error moving uploaded profile picture.";
            }
        } else {
            return "No profile picture uploaded.";
        }

        // Handle driver's license upload
        if (isset($_FILES['driversLicense'])) {
            $file = $_FILES['driversLicense'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                return "Error uploading driver's license.";
            }

            $fileType = mime_content_type($file['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!in_array($fileType, $allowedTypes)) {
                return "Invalid file type for driver's license. Allowed types: " . implode(", ", $allowedTypes);
            }

            if ($file['size'] > 2 * 1024 * 1024) { // 2MB limit
                return "File size exceeds the maximum limit of 2MB for driver's license.";
            }

            $driverLicenseName = uniqid() . '-' . basename($file['name']);
            $driverLicensePath = $uploadDir . $driverLicenseName;

            if (!move_uploaded_file($file['tmp_name'], $driverLicensePath)) {
                return "Error moving uploaded driver's license.";
            }
        } else {
            return "No driver's license uploaded.";
        }

        // Generate a random verification code
        $verification_code = rand(100000, 999999);
        
        // Insert values into the database
        $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, confirm_password, verification_code, profile_picture, drivers_license) 
                    VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :confirmPassword, :verification_code, :profile_picture, :drivers_license)";
        
        $sql = $this->conn->prepare($query);

        // Execute the statement with an array of values
        if ($sql->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':email' => $email,
            ':phone_number' => $phoneNumber,
            ':password' => $hashedPassword,
            ':confirmPassword' => $hashedConfirmPassword,
            ':verification_code' => $verification_code,
            ':profile_picture' => $profilePicturePath,
            ':drivers_license' => $driverLicensePath
        ])) {
            // Store verification code in session
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;

            // Send verification code to email using PHPMailer
            return $this->sendVerificationEmail($email, $first_name, $last_name, $verification_code);
        } else {
            return "Registration failed: " . implode(", ", $stmt->errorInfo());
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