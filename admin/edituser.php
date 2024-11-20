<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

require_once 'dbadmin.php'; // Ensure the class file is included

$rentedCarManager = new AdminRentedCar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for editing user details
    $data = [
        'user_id' => $_POST['user_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'address' => $_POST['address'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'email' => $_POST['email'],
        'phone_number' => $_POST['phone_number'],
        'verification_code' => $_POST['verification_code']
    ];

    if ($rentedCarManager->updateUser($data)) { // Assuming you have an updateUser  method
        header("Location: adminHome.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating record.";
    }
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userData = $rentedCarManager->getUserById($userId); // Assuming you have a method to get user by ID

    if (!$userData) {
        header("Location: adminHome.php");
        exit();
    }
} else {
    header("Location: adminHome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h2>Edit User Details</h2>
    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userData['user_id']); ?>">
        
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
        
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
        
        <label>Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($userData['address']); ?>" required>
        
        <label>Gender:</label>
        <select name="gender" required>
            <option value="male" <?php echo ($userData['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($userData['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
        </select>
        
        <label>Birthday:</label>
        <input type="date" name="dob" value="<?php echo htmlspecialchars($userData['dob']); ?>" required>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
        
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" required>
        
        <label>Verification Code:</label>
        <input type="text" name="verification_code" value="<?php echo htmlspecialchars($userData['verification_code']); ?>" required>

        <button type="submit">Update</button>
    </form>
</body>
</html>