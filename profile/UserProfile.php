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

        if (empty($user_id) || empty($country) || empty($id_number)) { //if may laman
            $errors[] = "User  ID, country, and ID number are required.";
        }

        if (!empty($errors)) {//if empty
            return implode(" ", $errors); 
        }

        // cehck if meron same na user, if not, insert the user
        $this->insertUserIfNotExists($user_id, $first_name, $last_name, $dob, $address);
        $userDetails = $this->getUserDetails($user_id);
        if (!$userDetails) {
            return "User  not found.";
        }

        $existingRecord = $this->getExistingRecord($user_id, $id_number); // check user existing records 
        if ($existingRecord) {
            if (!empty($existingRecord['valid_id'])) { //if may uloaded na
                return "This identity is already being verified.";
            } else {  //  upload if the ID photo is not uploaded
                $idPhotoPath = $this->uploadFile($id_photo, "../uploads/drivers_license/");
                if (!is_string($idPhotoPath)) { // If an error occurred, it will return an error message
                    return $idPhotoPath;
                }

                // update the table to a new ID
                $this->updateIdPhoto($existingRecord['id'], $idPhotoPath);
                return "ID photo uploaded successfully for verification.";
            }
        }

        $idPhotoPath = $this->uploadFile($id_photo, "../uploads/drivers_license/"); //maguupload ng id
        if (!is_string($idPhotoPath)) { //if error
            return $idPhotoPath;
        }

        $query = "INSERT INTO identityverification (user_id, country, id_number, valid_id, first_name, last_name, dob, address) 
                  VALUES (:user_id, :country, :id_number, :valid_id, :first_name, :last_name, :dob, :address)";
        $stmt = $this->conn->prepare($query); //nsert to my identityverification table

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

        $existingUser  = $this->getUserDetails($user_id); //check if user already exist
        
        if (!$existingUser ) {  //if walang laman yunf data o gumamit ng api maiinsert yunng data
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

    public function isVerified() {
        // Correct the SQL query to select the verify_status column
        $query = "SELECT verify_status FROM identityverification WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $this->userId]);
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the result is not empty and if verify_status is 'verified'
        return !empty($result) && $result['verify_status'] === 'verified';
    }

    private function getExistingRecord($user_id, $id_number) {
        $query = "SELECT * FROM identityverification WHERE user_id = :user_id AND id_number = :id_number";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':id_number' => $id_number
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // if record exist or false if not found
    }

    private function updateIdPhoto($id, $idPhotoPath) {
        $query = "UPDATE identityverification SET valid_id = :valid_id WHERE id = :id";
        $stmt = $this->conn->prepare($query); //update the id
        $stmt->execute([
            ':valid_id' => $idPhotoPath,
            ':id' => $id
        ]);
    }

    private function uploadFile($file, $uploadDir) {
        if (!is_dir($uploadDir)) { //if walang laman upload
            mkdir($uploadDir, 0755, true);
        }

        if ($file['error'] !== UPLOAD_ERR_OK) { //valid dito yung upload file
            return "File upload error.";
        }

        $fileName = basename($file['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) { // move the uploaded file to the target folder
            return $targetFilePath; //if path of the uploaded file
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