<?php
session_start();
require_once __DIR__ . '/../dbcon/dbcon.php';

class UserProfile {
    private $conn;
    private $email;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); 
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }

        if (!isset($_SESSION['email'])) { //check if the user login
            header("Location: login.php");
            exit;
        }

        $this->email = $_SESSION['email'];
    }

    public function getUserData() {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?"); //select all from user table where email
        $stmt->execute([$this->email]); 
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetch as an associative array

        return $result ?: null; // i user ternary 
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>