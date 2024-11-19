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

    // Check if a rent_id was returned
    // if ($rentId !== null) {
    //     echo "The latest rent ID is: " . htmlspecialchars($rentId);
    // } else {
    //     echo "No rentals found.";
    // }
// Close the database connection
$database->closeConn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Car Rent - Receipt</title>
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/receipt.css">

</head>
<body>

<?php include('../header/header.php'); ?>

<div class="container">
    <section class="form-section">
        <h1>Receipt</h1>
        <div class="receipt-container">
            <h2>Your Receipt</h2>
            <div class="receipt-info">
                <?php echo $receiptHTML; // Output the generated HTML ?>
            </div>
            <form action="../payment/payment.php" method="post"> <!-- Change 'payment.php' to your actual payment processing page -->
                <input type="hidden" name="receipt_data" value="<?php echo htmlspecialchars($receiptHTML); ?>">
                <button type="submit" class="payment-button" name="payment-btn">Proceed to Payment</button>
            </form>
            <form action="cancel.php" method="post"> <!-- Change to the actual path of your cancel script -->
                <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rentId); ?>">
                <button type="submit" class="payment-button" name="cancel-btn">Cancel</button>
            </form>
        </div>
    </section>
</div>

<script src="../js/script.js"></script>
</body>
</html>