<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class Admin {
    private $conn;

    public function __construct() {
        // Create a new Database instance and get the connection
        $database = new Database();
        $this->conn = $database->getConn(); // Get database connection
    }

    public function login($username, $password) {
        try {
            // Prepare the SQL statement to prevent SQL injection
            $sql = $this->conn->prepare("SELECT * FROM admin WHERE username = :username");
            $sql->bindParam(':username', $username);
            $sql->execute();
            
            if ($sql->rowCount() > 0) { // Check if any rows were returned
                $admin = $sql->fetch(PDO::FETCH_ASSOC);
                
                // Verify the password 
                if (password_verify($password, $admin['password'])) {
                    // Login successful 
                    // Start session and set session variables
                    session_start();
                    $_SESSION['admin_logged_in'] = true; // Set this session variable
                    $_SESSION['admin_id'] = $admin['admin_id']; // Store user ID in session
                    $_SESSION['username'] = $admin['username']; // Store username in session

                    header("Location: adminHome.php");
                    exit;
                } else {
                    // Login failed: incorrect password
                    return "Invalid Username or Password.";
                }
            } else {
                // Login failed: user not found
                return "Invalid Username or Password.";
            }
        } catch (PDOException $e) {
            // Handle any PDO exceptions
            return "Database error: " . $e->getMessage();
        }
    }

    public function __destruct() {
        // Close the database connection
        $this->conn = null; // PDO does not require an explicit close, but you can set it to null to close the connection
    }
}
?>