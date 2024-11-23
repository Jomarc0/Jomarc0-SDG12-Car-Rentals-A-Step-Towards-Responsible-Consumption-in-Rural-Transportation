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
        if ($password !== $confirmPassword) { //check if password match
            return "Passwords do not match";
        }
        
        if (strlen($password) < 8) {    // Check if password is at least 8 characters long
            return "Password must be at least 8 characters long";
        }
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashedConfirmPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);
    
        //if email already exists
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);  // to prevent SQL injection
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetch as an associative array
    
        if ($result) {
            return 'Email already exists';
        }
        
    if (isset($_FILES['profilePicture'])) { //handle file upload
        $file = $_FILES['profilePicture'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) { //check if file upload error
            return "Error uploading file.";
        }

        //mime_content_type it detect MIME type of file format for a file
        $fileType = mime_content_type($file['tmp_name']); //tmp is the tempporay file name of the server
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; //allow picture types
        if (!in_array($fileType, $allowedTypes)) { //check if yung send na picture is allowed
            return "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        }

        if ($file['size'] > 2 * 1024 * 1024) { // file file size (
            return "File size exceeds the maximum limit of 2MB.";
        }

        $uploadDir = __DIR__ . '/../pictures/'; // ensure this directory exists and is writable
        $fileName = uniqid() . '-' . basename($file['name']); // uniqid function generate uniqie identifiersbese on current time
        $filePath = $uploadDir . $fileName; //concat 

        if (!move_uploaded_file($file['tmp_name'], $filePath)) { //if walang laman
            return "Error moving uploaded file.";
        }
    } else {
        return "No file uploaded.";
    }
        //generate as random verification code
        $verification_code = rand(100000, 999999);
        //input value to the xampp
        $query = "INSERT INTO user (first_name, last_name, address, gender, dob, email, phone_number, password, confirm_password, verification_code, profile_picture) 
                    VALUES (:first_name, :last_name, :address, :gender, :dob, :email, :phone_number, :password, :confirmPassword, :verification_code, :profile_picture)";
        
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
            ':profile_picture' => $filePath 
        ])) {
                // verification code in session
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['email'] = $email;

                // send verification code to email using PHPMailer with the name of the user
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
            $mail->Username   = 'jaycarrent@gmail.com'; //gmail 
            $mail->Password   = 'ygic eqgh ucoi bdio'; // secret pass from my gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Receiver
            $mail->setFrom('jaycarrent@gmail.com', 'Mailer');
            $mail->addAddress($email, $first_name . ' ' . $last_name);

            // content
            $mail->isHTML(true);
            $mail->Subject =    'Verification Code';
            $mail->Body    =    '<h3>Verification code to access</h3>
                                <h3>Name: ' . $first_name . ' ' . $last_name . '</h3>
                                <h3>Email: ' . $email . '</h3>
                                <h3>Verification Code: ' . $verification_code . '</h3>';

            if ($mail->send()) { //if successful magreredirect sa verify to input the code
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
        $this->conn = null; 
    }
}

?>