<?php
class Database {
    private $host = "localhost";
    private $db_name = "carrental";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            // Use a DSN (Data Source Name) to specify the database type and connection details
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            // Create a new PDO instance
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set the default fetch mode
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Enable prepared statements by default
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("Database connection error.");
        }
    }

    public function getConn() {
        return $this->conn;
    }

    public function closeConn() {
        $this->conn = null; // Close the connection
    }
}

// Usage example
try {
    $db = new Database();
    $connection = $db->getConn();
    // Use $connection for your queries
} catch (Exception $e) {
    echo $e->getMessage();
}
?>