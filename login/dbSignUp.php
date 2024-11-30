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
        $errors = [];
    
        // Validate passwords
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
    
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
    
        // Validate email existence
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors[] = "Email already exists.";
        }
    
        // If there are validation errors, return them as a string
        if (!empty($errors)) {
            return implode(" ", $errors); // Combine errors into a single string
        }
    
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Handle profile picture upload
        $profilePicturePath = $this->UploadFile($profilePicture, "../uploads/profile_pictures/");
        if (!is_string($profilePicturePath)) { // If an error occurred, it will return an error message
            return $profilePicturePath;
        }

        // Handle driver's license upload
        $driversLicensePath = $this->UploadFile($driversLicense, "../uploads/drivers_licenses/");
        if (!is_string($driversLicensePath)) { // If an error occurred, it will return an error message
            return $driversLicensePath;
        }

    
        // Generate a random verification code
        $verification_code = rand(100000, 999999);
    
        // Insert values into the database
        $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, verification_code, profile_picture, drivers_license) 
                VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :verification_code, :profile_picture, :drivers_license)";
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
            ':profile_picture' => $profilePicturePath,
            ':drivers_license' => $driversLicensePath
        ])) {
            session_start();
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;
    
            return $this->sendVerificationEmail($email, $first_name, $last_name, $verification_code);
        } else {
            return "Registration failed: " . implode(", ", $sql->errorInfo());
        }
    }

    private function UploadFile($file, $uploadDir) {
        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory with full permissions
        }
    
        // Validate and move the uploaded file
        if (isset($file['name']) && $file['error'] === UPLOAD_ERR_OK) {
            $uniqueFileName = uniqid() . "_" . basename($file['name']);
            $filePath = $uploadDir . $uniqueFileName;
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                return $filePath; // Return the file path if successful
            } else {
                return "Error moving file.";
            }
        } else {
            return "Invalid file upload.";
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