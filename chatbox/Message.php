<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary

class Message {
    private $conn;
    private $admin_id;
    private $user_id;

    public function __construct($admin_id = null, $user_id = null) {
        try {
            $database = new Database();
            $this->conn = $database->getConn(); 
            $this->admin_id = $admin_id; //get admin_id
            $this->user_id = $user_id; //get user_id
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function adminMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adminMessage'])) {
            $admin_message = htmlspecialchars($_POST['adminMessage']);
            
            // insert admin's message 
            $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, admin_id, user_id, message_at) VALUES ('admin', ?, ?, ?, NOW())");
            $stmt->execute([$admin_message, $this->admin_id, $this->user_id]); 
            
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?user_id=" . $this->user_id); // redict to the same page and get the user_id
            exit();
        }
    }

    public function userMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['client_message'])) {
            $client_message = htmlspecialchars($_POST['client_message']);
            
            // insert user message
            $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, user_id, message_at) VALUES ('client', ?, ?, NOW())");
            $stmt->execute([$client_message, $this->user_id]);
            
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM messages WHERE sender = 'client' AND user_id = ?");
            $stmt->execute([$this->user_id]); // check if this is the first message from user
            $client_message_count = $stmt->fetchColumn();

            if ($client_message_count == 1) {
                
                $welcome_message = "Hello, welcome to my website!"; // echo welcome if first message
                $stmt = $this->conn->prepare("INSERT INTO messages (sender, message, user_id, message_at) VALUES ('admin', ?, ?, NOW())");
                $stmt->execute([$welcome_message, $this->user_id]);
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?user_id=" . $this->user_id);
            exit();
        }
    }

    public function getMessages($userId) {
        $stmt = $this->conn->prepare("SELECT sender, message, message_at FROM messages WHERE user_id = ? ORDER BY id ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers($search = '') {
        $query = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, profile_picture FROM user"; //sellect user with picture
        
        if (!empty($search)) { 
            $query .= " WHERE CONCAT(first_name, ' ', last_name) LIKE :searchTerm";
        } //concat the query to this where condition if the search is not empty
    
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) { // dind the search term 
            $stmt->bindValue(':searchTerm', '%' . $search . '%');
        }
    
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>