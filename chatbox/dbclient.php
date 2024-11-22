<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class UserMessage {
    private $conn;
    private $user_id;

    public function __construct($user_id) {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // Get database connection
            $this->user_id = $user_id; // Set user_id
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function messageSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['client_message'])) {
            $client_message = htmlspecialchars($_POST['client_message']);
            
            // Insert client's message with user_id
            $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, user_id) VALUES ('client', ?, ?)");
            $stmt->execute([$client_message, $this->user_id]);
            
            // Check if this is the first message from the client
            $stmt = $this->conn->query("SELECT COUNT(*) FROM messages WHERE sender = 'client' AND user_id = $this->user_id");
            $client_message_count = $stmt->fetchColumn();

            if ($client_message_count == 1) {
                // If it's the first message, send a welcome message from the admin
                $welcome_message = "Hello, welcome to my website!";
                $stmt = $this->conn->prepare("INSERT INTO messages (sender, message) VALUES ('admin', ?)");
                $stmt->execute([$welcome_message]);
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    public function getMessages() {
        $stmt = $this->conn->query("SELECT sender, message FROM messages ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>