<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class User {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // Get database connection
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function login($email, $password) {
        // Prepare the SQL statement to prevent SQL injection
        $sql = $this->conn->prepare("SELECT * FROM user WHERE email = :email AND verified = 1");
        
        // Bind the parameters
        $sql->bindParam(':email', $email);

        // Execute the statement
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array

        if ($result) { // User found
            // Verify the password 
            if (password_verify($password, $result['password'])) {
                // Login successful 
                $this->startSession($result);
                header("Location: /sampleRent/main/index.php");
                exit;
            } else {
                // Incorrect password
                return 'Invalid email or password. Please try again or register.';
            }
        } else {
            // User not found or not verified
            return 'Invalid email or password. Please try again or register.';
        }
    }

    private function startSession($user) {
        session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
    }

    public function __destruct() {
        // Close the database connection
        $this->conn = null; // Use null to close the connection properly
    }
}
?>