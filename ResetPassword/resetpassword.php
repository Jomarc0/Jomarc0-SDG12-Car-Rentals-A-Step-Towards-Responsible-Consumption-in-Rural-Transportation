<?php
// reset_password.php
require_once __DIR__ . '/../dbcon/dbcon.php';

try {
    $database = new Database();
    $conn = $database->getConnec(); //to get database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["changePass"])) {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];
    $newPassword = $_POST['new_password'];

    // Validate the OTP
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("si", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $hashedConfirmPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);

        // Update the password and clear OTP
        $stmt = $conn->prepare("UPDATE user SET password = ?, confirm_password = ?  WHERE email = ?");
        $stmt->bind_param("sss", $hashedPassword ,$hashedConfirmPassword ,$email);
        $stmt->execute();

        echo "Your password has been reset successfully.";
    } else {
        echo "Invalid or expired OTP.";
    }
}
?>