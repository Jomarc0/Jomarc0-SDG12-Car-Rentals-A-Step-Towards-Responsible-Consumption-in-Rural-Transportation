<?php
session_start();
require_once __DIR__ . '/../dbcon/dbcon.php';

class DatabaseConnection {
    private $conn;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConn();
        } catch (Exception $exception) {
            die("Database connection failed: " . $exception->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        $this->conn = null;
    }
}

class UserProfile {
    private $conn;
    private $userId;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function getUserData() {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_id = ?"); 
        $stmt->execute([$this->userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null; 
    }
    
    public function uploadProfilePicture($file) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = '../uploads/profile_pictures/';
                $dest_path = $uploadFileDir . $this->userId . '.' . $fileExtension;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $query = "UPDATE user SET profile_picture = :profile_picture WHERE user_id = :user_id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([
                        ':profile_picture' => $dest_path,
                        ':user_id' => $this->userId
                    ]);
                    return "Profile picture updated successfully.";
                } else {
                    return "Error moving the uploaded file.";
                }
            } else {
                return "Upload failed. Allowed file types: " . implode(", ", $allowedfileExtensions);
            }
        } else {
            return "No file uploaded or there was an upload error.";
        }
    }
}

class IdentityVerification {
    private $conn;
    private $userId;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function verify($user_id, $country, $id_number, $id_photo, $first_name, $last_name, $dob, $address) {
        $errors = [];

        // Validate input
        if (empty($user_id) || empty($country) || empty($id_number)) {
            $errors[] = "User  ID, country, and ID number are required.";
        }

        // If there are validation errors, return them as a string
        if (!empty($errors)) {
            return implode(" ", $errors); // Combine errors into a single string
        }

        // Check if the user exists, if not, insert the user
        $this->insertUserIfNotExists($user_id, $first_name, $last_name, $dob, $address);

        // Fetch user details
        $userDetails = $this->getUserDetails($user_id);
        if (!$userDetails) {
            return "User  not found.";
        }

        // Check for existing records in identityverification
        $existingRecord = $this->getExistingRecord($user_id, $id_number);
        if ($existingRecord) {
            // Check if the ID photo is already uploaded
            if (!empty($existingRecord['valid_id'])) {
                return "This identity is already being verified.";
            } else {
                // Allow the upload if the ID photo is not uploaded
                $idPhotoPath = $this->uploadFile($id_photo, "../uploads/drivers_license/");
                if (!is_string($idPhotoPath)) { // If an error occurred, it will return an error message
                    return $idPhotoPath;
                }

                // Update the existing record with the new ID photo
                $this->updateIdPhoto($existingRecord['id'], $idPhotoPath);
                return "ID photo uploaded successfully for verification.";
            }
        }

        // Handle ID photo upload for new records
        $idPhotoPath = $this->uploadFile($id_photo, "../uploads/drivers_license/");
        if (!is_string($idPhotoPath)) { // If an error occurred, it will return an error message
            return $idPhotoPath;
        }

        // Insert values into the identityverification table, including user details
        $query = "INSERT INTO identityverification (user_id, country, id_number, valid_id, first_name, last_name, dob, address) 
                  VALUES (:user_id, :country, :id_number, :valid_id, :first_name, :last_name, :dob, :address)";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute([
            ':user_id' => $user_id,
            ':country' => $country,
            ':id_number' => $id_number,
            ':valid_id' => $idPhotoPath,
            ':first_name' => $userDetails['first_name'],
            ':last_name' => $userDetails['last_name'],
            ':dob' => $userDetails['dob'],
            ':address' => $userDetails['address']
        ])) {
            return "Identity verification successful.";
        } else {
            return "Verification failed: " . implode(", ", $stmt->errorInfo());
        }
    }

    private function insertUserIfNotExists($user_id, $first_name, $last_name, $dob, $address) {
        // Check if the user already exists
        $existingUser  = $this->getUserDetails($user_id);
        
        if (!$existingUser ) {
            // User does not exist, proceed to insert
            $query = "INSERT INTO user (user_id, first_name, last_name, dob, address) 
                    VALUES (:user_id, :first_name, :last_name, :dob, :address)";
            $stmt = $this->conn->prepare($query);
            
            if ($stmt->execute([
                ':user_id' => $user_id,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':dob' => $dob,
                ':address' => $address
            ])) {
                return "User  inserted successfully.";
        } else {
                return "Failed to insert user: " . implode(", ", $stmt->errorInfo());
            }
        } else {
            return "User   already exists.";
        }
    }

    private function getUserDetails($user_id) {
        $query = "SELECT first_name, last_name, dob, address FROM user WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the user details or false if not found
    }
    public function isVerified(): bool {
        $query = "SELECT verify_status = 'verified  ' FROM identityverification WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $this->userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if valid_id is not empty
        return !empty($result['valid_id']);
    }

    private function getExistingRecord($user_id, $id_number) {
        $query = "SELECT * FROM identityverification WHERE user_id = :user_id AND id_number = :id_number";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':id_number' => $id_number
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return the existing record or false if not found
    }

    private function updateIdPhoto($id, $idPhotoPath) {
        $query = "UPDATE identityverification SET valid_id = :valid_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':valid_id' => $idPhotoPath,
            ':id' => $id
        ]);
    }

    private function uploadFile($file, $uploadDir) {
        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate the uploaded file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "File upload error.";
        }

        $fileName = basename($file['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $targetFilePath; // Return the path of the uploaded file
        } else {
            return "Failed to move uploaded file.";
        }
    }
}

class UserAccount {
    private $dbConnection;
    private $userId;
    private $userProfile;
    private $verify;

    public function __construct() {
        $this->dbConnection = new DatabaseConnection();
        $this->userId = $this->getUserId();

        if ($this->userId) {
            $this->userProfile = new UserProfile($this->dbConnection->getConnection(), $this->userId);
            // Pass the database connection and user ID to the IdentityVerification constructor
            $this->verify = new IdentityVerification($this->dbConnection->getConnection(), $this->userId);
        } else {
            header("Location: ../login/signIn.php");
            exit;
        }
    }

    private function getUserId() {
        return isset($_SESSION['email']) ? $this->getUserIdByEmail($_SESSION['email']) : null;
    }

    private function getUserIdByEmail($email) {
        $stmt = $this->dbConnection->getConnection()->prepare("SELECT user_id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? null;
    }

    public function getUserData() {
        return $this->userProfile->getUserData();
    }

    public function uploadProfilePicture($file) {
        return $this->userProfile->uploadProfilePicture($file);
    }

    public function verify($user_id, $country, $id_number, $id_photo, $first_name, $last_name, $dob, $address) {
        return $this->verify->verify($user_id, $country, $id_number, $id_photo, $first_name, $last_name, $dob, $address);
    }

    public function isVerified() {
        return $this->verify->isVerified(); 
    }

    public function __destruct() {
        $this->dbConnection = null;
    }
}
?>