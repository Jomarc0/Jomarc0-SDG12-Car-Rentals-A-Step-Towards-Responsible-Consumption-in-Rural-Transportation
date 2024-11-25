<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class AdminMessage {
    private $conn;
    private $admin_id;
    private $user_id;

    public function __construct($admin_id, $userId) {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); // Get database connection
            $this->admin_id = $admin_id; // Set admin_id
            $this->user_id = $userId;
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }
    
    public function messageSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adminMessage'])) {
            $admin_message = htmlspecialchars($_POST['adminMessage']);
            
            // Insert admin's message with admin_id
            $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, admin_id, user_id, message_at) VALUES ('admin', ?, ?, ?, NOW())");
            $stmt->execute([$admin_message, $this->admin_id, $this->user_id]); 
            
            // Redirect to the same page to refresh messages
            header("Location: " . $_SERVER['PHP_SELF'] . "?user_id=" . $this->user_id); // Redirect with user_id
            exit();
        }
    }

    public function getMessages($userId) {
        $stmt = $this->conn->prepare("SELECT sender, message, message_at FROM messages WHERE user_id = ? ORDER BY id ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers($search) {
        // Base query with additional fields, excluding online status
        $query = "SELECT user_id, CONCAT(first_name,' ',last_name) AS name, profile_picture FROM user";
        
        // Add search condition if search term is provided
        if (!empty($search)) {
            $query .= " WHERE CONCAT(first_name,' ',last_name) LIKE :searchTerm";
        }
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the search term if provided
        if (!empty($search)) {
            $stmt->bindValue(':searchTerm', '%' . $search . '%');
        }
    
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>