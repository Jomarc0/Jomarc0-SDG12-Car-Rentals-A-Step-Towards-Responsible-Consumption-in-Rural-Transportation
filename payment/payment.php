<?php
require_once __DIR__ . '/../dbcon/dbcon.php';
require_once 'paymentProcessor.php'; //require the payment processor that holds api

try {
    $database = new Database(); 
    $conn = $database->getConn(); 
} catch (Exception $exception) {
    die("Database connection failed: " . $exception->getMessage());
}

$paymentProcessor = new PaymentProcessor($conn); //call the paymentProcessor class

$paymentProcessor->processPayment(); //call the payment processing method

$database->closeConn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing</title>
</head>
<body>

<?php
if ($paymentProcessor->getError()) { // erorr dispaly error message
    echo "<p style='color:red;'>" . $paymentProcessor->getError() . "</p>";
    
} elseif ($paymentProcessor->getSuccessMessage()) { //if success display success message
    echo "<p style='color:green;'>" . $paymentProcessor->getSuccessMessage() . "</p>";
}
?>

</body>
</html>