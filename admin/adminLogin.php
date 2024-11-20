<?php
require_once __DIR__ . '/../dbcon/dbcon.php';

class Admin{
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConn(); 
    }

    public function login($username, $password) {
        try {
            $sql = $this->conn->prepare("SELECT * FROM admin WHERE username = :username"); // select all from the admin table
            $sql->bindParam(':username', $username);// to prevent SQL injection
            $sql->execute();
            
            if ($sql->rowCount() > 0) { // check if any rows were returned
                $admin = $sql->fetch(PDO::FETCH_ASSOC);
                
                // Verify the password 
                if (password_verify($password, $admin['password'])) { //if login successful //session start
                    session_start();
                    $_SESSION['admin_logged_in'] = true; // set this session variable
                    $_SESSION['admin_id'] = $admin['admin_id']; // user ID in session
                    $_SESSION['username'] = $admin['username']; // username in session
                    header("Location: adminHome.php");
                    exit;
                } else { //if login is in correct
                    return "Invalid Username or Password.";
                }
            } else { //if user not found
                return "No Found Username or Password.";
            }
        } catch (PDOException $e) { //handle pdo
            return "Database error: " . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>