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
    <style>
/* admin.css */

/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

/* General Styles */
body {
    margin: 0;
    font-family: 'Montserrat', sans-serif; /* Clean and modern font */
    background: linear-gradient(135deg, #f0f4f8, #e1e9f0); /* Soft gradient background */
    color: #333; /* Dark text color */
}

/* Header Styles */
h2 {
    text-align: center; /* Center the heading */
    margin: 30px 0; /* Margin above and below */
    color: #333; /* Darker text color */
    font-size: 28px; /* Larger font size */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Subtle text shadow */
}

/* Form Styles */
form {
    max-width: 500px; /* Max width of the form */
    margin: 40px auto; /* Center the form with margin */
    padding: 30px; /* Padding inside the form */
    background-color: #ffffff; /* White background for the form */
    border-radius: 15px; /* More rounded corners */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
    transition: box-shadow 0.3s; /* Smooth shadow transition */
}

form:hover {
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); /* Shadow on hover */
}

/* Input and Label Styles */
label {
    display: block; /* Block display for labels */
    margin-bottom: 5px; /* Space below labels */
    font-weight: 700; /* Bold text for labels */
    color: #555; /* Darker color for labels */
}

input[type="text"],
input[type="email"],
input[type="date"],
select {
    width: 100%; /* Full width */
    padding: 12px; /* Increased padding inside the input */
    margin-bottom: 20px; /* Space below inputs */
    border: 2px solid #007bff; /* Blue border */
    border-radius: 10px; /* More rounded corners */
    box-sizing: border-box; /* Include padding and border in width */
    font-size: 16px; /* Font size */
    transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transitions */
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="date"]:focus,
select:focus {
    border-color: #0056b3; /* Darker blue border on focus */
    box-shadow: 0 0 8px rgba(0, 86, 179, 0.5); /* Glow effect */
    outline: none; /* Remove default outline */
}

/* Button Styles */
button {
    background: linear-gradient(90deg, #007bff, #0056b3); /* Gradient background */
    color: white; /* White text */
    padding: 12px 20px; /* Padding inside the button */
    border: none; /* No border */
    border-radius: 10px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background 0.3s, transform 0.2s, box-shadow 0.3s; /* Smooth transitions */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Button shadow */
}

button:hover {
    background: linear-gradient(90deg, #0056b3, #004494); /* Darker gradient on hover */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Enhanced shadow on hover */
}

/* Responsive Styles */
@media (max-width: 600px) {
    form {
        padding: 20px; /* Less padding on smaller screens */
    }

    button {
        width: 100%; /* Full width for buttons on small screens */
    }
}
    </style>
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