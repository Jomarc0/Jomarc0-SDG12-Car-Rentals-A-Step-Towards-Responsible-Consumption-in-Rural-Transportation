<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'CarSystem.php'; // Include the CarRentalSystem class

// Instantiate the Database class
try {
    $database = new Database(); // Create a new Database instance
    $conn = $database->getConn(); // Get the database connection
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

// Generate receipt without user information
$carRentalSystem = new CarRentalSystem($conn); // Correctly instantiate the CarRentalSystem
$receiptHTML = $carRentalSystem->generateReceipt(); // Capture the HTML output

$receipt = new Receipt($conn);
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
    <link rel="stylesheet" href="../css/receipt.css">

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