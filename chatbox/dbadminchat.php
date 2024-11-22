<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class AdminMessage {
    private $conn;
    private $admin_id;

    public function __construct($admin_id) {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // Get database connection
            $this->admin_id = $admin_id; // Set admin_id
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }
    
    public function messageSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adminMessage'])) {
            $admin_message = htmlspecialchars($_POST['adminMessage']);
            
            // Insert admin's message with admin_id
            $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, admin_id) VALUES ('admin', ?, ?)");
            $stmt->execute([$admin_message, $this->admin_id]);
            
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    public function getMessages() {
        $stmt = $this->conn->query("SELECT sender, message, user_id FROM messages ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserIds() {
        try {
            $stmt = $this->conn->query(" SELECT DISTINCT m.user_id  FROM messages m JOIN user u ON m.user_id = u.user_id");
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the user_id column
        } catch (PDOException $e) {
            // Handle the error (log it, display a message, etc.)
            return []; // Return an empty array on error
        }
    }
}
?>