<?php
session_start(); // session start
require_once '../Mailer/UserMailer.php'; //SendEmail path

class UserRegistration {
    private $conn;
    private $emailService; // initialize for sendEmail class

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); 
            $this->emailService = new SendEMail(); // this class from usermailer
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword) {
        $errors = [];
        
        if ($password !== $confirmPassword) {// validate passwords
            $errors[] = "Passwords do not match.";
        }
    
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        $dobDate = new DateTime($dob); // check age must be 18 years old and above
        $today = new DateTime();
        $age = $today->diff($dobDate)->y; // calculate age

        if ($age < 18) {
            $errors[] = "You must be at least 18 years old to register.";
        }

        $query = "SELECT * FROM user WHERE email = :email";        // validate email existence
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errors[] = "Email already exists.";
        }
        if (!empty($errors)) {// if there are errors, return them as a string
            return implode(" ", $errors); 
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);// hash the password
    
        $verification_code = rand(100000, 999999);// generate a random verification code
    
        // Insert values into the database i did not include the confirm password i just use that for validation
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
            $_SESSION['verification_code'] = $verification_code; //sesstion if insert successful
            $_SESSION['email'] = $email;
    
            // call the sendVerificationEmail method
            return $this->emailService->sendVerification($email, $first_name, $last_name, $verification_code);
        } else {
            return "Registration failed: " . implode(", ", $sql->errorInfo());
        }
    }

    public function isUserRegistered($email) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email"); //check if email is already exist
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        // true if the user exists, false otherwise
        return $count > 0;
    }

    public function registerFromGoogle($first_name , $last_name, $email) { //firstname, lastname, and email from gmail api
        // error_log("Registering user: $first_name $last_name, Email: $email");  //check for error
        $query = "SELECT * FROM user WHERE email = :email";// check if the user already exists in the database
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return "User  already exists. Please log in.";
        } else {
            $address = '';//use null because this is for the gmail api
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
                
                return $this->emailService->sendVerification($email, $first_name, $last_name, $verification_code);
            } else {
                return "Error inserting user data: " . implode(", ", $sql->errorInfo());
            }
        }
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>