<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class UserLogin{
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // get db connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function login($email, $password) {
        $sql = $this->conn->prepare("SELECT * FROM user WHERE email = :email AND verified = 1");
        $sql->bindParam(':email', $email);  // to prevent SQL injection
        $sql->execute(); //execute the statement
        $result = $sql->fetch(PDO::FETCH_ASSOC); // fetch as an associative array

        if ($result) { // if userfound
            // password_verify use pag naka hash 
            if (password_verify($password, $result['password'])) {
                $this->startSession($result); //if login successful
                header("Location: /sampleRent/main/index.php");
                exit;
            } else {
                // Incorrect password
                return 'Invalid email or password. Please try again or register.';
            }
        } else {
            // Uif no user found or not verified
            return 'Invalid email or password. Please try again or register.';
        }
    }

    private function startSession($user) {
        session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['user_id'] = $user['user_id']; //session the user_id
        $_SESSION['email'] = $user['email']; //session the user_id
    }

    public function __destruct() {
        // to close database 
        $this->conn = null; // use null to close 
    }
}
?>