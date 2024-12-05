<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once __DIR__ . '/receipt.php'; // Include the Receipt class

// Instantiate the Database class
try {
    $database = new Database(); // Create a new Database instance
    $conn = $database->getConn(); // Get the database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// Generate receipt without user information
$receipt = new Receipt($conn);
$receiptHTML = $receipt->generate(); // Capture the HTML output

$rentId = $receipt->getRentId();

    // //debugging
    // if ($rentId !== null) {
    //     echo "The latest rent ID is: " . htmlspecialchars($rentId);
    // } else {
    //     echo "No rentals found.";
    // }

$database->closeConn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Car Rent - Receipt</title>
    <!-- <link rel="stylesheet" href="../css/service.css"> -->
    <!-- <link rel="stylesheet" href="../css/receipt.css"> -->
    <style>
          body {
    font-family: 'Arial', sans-serif; /* Modern font */
    margin: 0;
    padding: 0;
    background-color: #121212; /* Dark background for the body */
    color: #ffffff; /* White text for better contrast */
}

.container {
    background-color: #1c1c1c; /* Dark background for the container */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
}

.form-section {
    background-color: #2c2c2c; /* Darker background for form section */
    padding: 60px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Deeper shadow for depth */
    width: 800px;
    text-align: center;
    transition: transform 0.3s ease; /* Smooth transition for hover effect */
}

.form-section:hover {
    transform: translateY(-5px); /* Lift effect on hover */
}

.form-section h1 {
    font-size: 24px;
    margin-bottom: 40px;
    color: #f7b531; /* Highlight color for the heading */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Subtle shadow for text */
}

/* Progress bar */
.form-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
}

.form-progress .step {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-progress .step span {
    width: 50px; /* Increased size for better visibility */
    height: 50px; /* Increased size for better visibility */
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background-color: #4F5576; /* Background color for steps */
    color: white;
    font-weight: bold;
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.form-progress .step.active span {
    background-color: #f7b531; /* Change active step color */
}

.form-progress .step p {
    margin-top: 10px;
    font-size: 14px;
    color: #888; /* Color for step description */
}

/* Form Steps */
.form-step {
    display: none;
    flex-direction: column;
}

.form-step.active {
    display: flex;
}

label {
    font-size: 18px;
    text-align: left;
    margin-bottom: 10px;
    color: #f7b531; /* Highlight color for labels */
}

select {
    padding: 12px; /* Increased padding for better usability */
    font-size: 16px;
    margin-bottom: 20px;
    width: 100%;
    border-radius: 5px;
    border: 1px solid #4F5576; /* Lighter border color */
    transition: border-color 0.3s; /* Smooth transition for focus */
    background-color: #2c2c2c; /* Dark background for select */
    color: #ffffff; /* White text for select */
}

select:focus {
    border-color: #f7b531; /* Change border color on focus */
    outline: none; /* Remove default outline */
}

input {
    font-size: 18px;
    text-align: left;
    margin-bottom: 20px; /* Increased margin for spacing */
    border: 1px solid #4F5576; /* Lighter border color */
    border-radius: 5px; /* Added border radius for input fields */
    padding: 12px; /* Increased padding for better usability */
    transition: border-color 0.3s; /* Smooth transition for focus */
    background-color: #2c2c2c; /* Dark background for input */
    color: #ffffff; /* White text for input */
}

input:focus {
    border-color: #f7b531; /* Change border color on focus */
    outline: none; /* Remove default outline */
}

button {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: #f7b531; /* Updated button background color */
    color: #181b26; /* Dark text color*/
    border: none;
    border-radius: 5px;
    width: 70%;
    transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
}

button:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

button.prev-btn {
    background-color: #4F5576; /* Updated previous button color */
}

button.prev-btn:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

button[type="submit"] {
    background-color: #f7b531; /* Updated submit button color */
}

button[type="submit"]:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}

.button-container {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically (if needed) */
    margin-top: 20px; /* Increased margin for better spacing */
}

.button-container button {
    margin: 0 10px; /* Add spacing between buttons */
}
/* receipt*/
.receipt-container {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    background-color: #2c2c2c; /* Darker background for the receipt container */
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3); /* Slightly deeper shadow for depth */
    text-align: left;
    color: #ffffff; /* White text for better contrast */
}

.receipt-container h2 {
    text-align: center;
    margin-bottom: 15px;
    color: #f7b531; /* Highlight color for the heading */
}

.receipt-info {
    margin: 8px 0;
    font-size: 16px;
    color: #e0e0e0; /* Lighter text color for receipt info */
}

.payment-button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 20px;
    background-color: #f7b531; /* Highlight color for the payment button */
    color: #181b26; /* Dark text color for better contrast */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    font-size: 16px;
    transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
}

.payment-button:hover {
    background-color: #9FA7BF; /* Lighter color on hover */
    transform: translateY(-2px); /* Lift effect on hover */
}
     
    </style>

</head>
<body>

<?php include('../header/header.php'); ?>

<div class="container">
    <section class="form-section">
        <h1>Receipt</h1>
        <div class="form-progress">
            <div class="step active">
                <span>1</span>
                <p>Select Location</p>
            </div>
            <div class="step active">
                <span>2</span>
                <p>Trip Details</p>
            </div>
            <div class="step active">
                <span>3</span>
                <p>Service Options</p>
            </div>
            <div class="step active">
                <span>4</span>
                <p>Receipt</p>
            </div>
        </div>
        <div class="receipt-container">
            <h2>Your Receipt</h2>
            <div class="receipt-info">
                <?php echo $receiptHTML; //output in html ?>
            </div>
            <form action="../payment/payment.php" method="post"> <!-- form to the payment-->
                <input type="hidden" name="receipt_data" value="<?php echo htmlspecialchars($receiptHTML); ?>">
                <button type="submit" class="payment-button" name="payment-btn">Proceed to Payment</button>
            </form>
            <form action="delete.php" method="post"> <!-- form to cancel if the user dont want the price-->
                <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rentId); ?>">
                <button type="submit" class="payment-button" name="delete-btn">Cancel</button>
            </form>
        </div>
    </section>
</div>

<script src="../js/script.js"></script>
</body>
</html>