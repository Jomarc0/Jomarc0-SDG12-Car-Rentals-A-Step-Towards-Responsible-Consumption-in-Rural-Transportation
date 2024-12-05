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

        if (!isset($_SESSION['email'])) { // Check if the user is logged in
            header("Location: ../login/signIn.php");
            exit;
        }

        $this->email = $_SESSION['email'];
    }

    public function getUserData() {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?"); // Select all from user table where email
        $stmt->execute([$this->email]); 
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array

        return $result ?: null; // Return user data or null
    }

    public function uploadProfilePicture($file) {
        // Check if the file was uploaded without errors
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $userId = $this->getUserId(); // Get the user ID from the session or database
            $fileTmpPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Specify the allowed file extensions
            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Set the upload directory
                $uploadFileDir = '../uploads/profile_pictures/';
                $dest_path = $uploadFileDir . $userId . '.' . $fileExtension; // Save with user ID as filename

                // Move the file to the upload directory
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Update the user's profile picture in the database
                    $query = "UPDATE user SET profile_picture = :profile_picture WHERE user_id = :user_id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([
                        ':profile_picture' => $dest_path,
                        ':user_id' => $userId
                    ]);

                    return "Profile picture updated successfully.";
                } else {
                    return "There was an error moving the uploaded file.";
                }
            } else {
                return "Upload failed. Allowed file types: " . implode(", ", $allowedfileExtensions);
            }
        } else {
            return "No file uploaded or there was an upload error.";
        }
    }

    private function getUserId() {
        // Fetch the user ID based on the email stored in the session
        $stmt = $this->conn->prepare("SELECT user_id FROM user WHERE email = ?");
        $stmt->execute([$this->email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? null; // Return user ID or null
    }

    public function isVerified() {
        // Check the verification status from the identityverification table
        $userId = $this->getUserId();
        if ($userId) {
            $stmt = $this->conn->prepare("SELECT verify_status FROM identityverification WHERE user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['verify_status'] === 'verified'; // Adjust based on your database field
        }
        return false; // Return false if user ID is not found
    }

    public function __destruct() {
        $this->conn = null; 
    }
}
?>